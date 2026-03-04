<?php
/**
 * Stripe webhook handling for automatic license management.
 *
 * Configure in wp-config.php:
 *   define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_...' );
 *
 * Or use encrypted settings:
 *   PLM_Encryption::update_option( 'plm_stripe_webhook_secret', 'whsec_...' );
 *
 * Stripe SDK is not required — webhook signature verification is done manually.
 * Webhook URL: https://pdfviewer.drossmedia.de/wp-json/plm/v1/webhook/stripe
 *
 * Events handled:
 *   checkout.session.completed      — Create new license
 *   invoice.paid                    — Extend existing license (renewal)
 *   customer.subscription.deleted   — Log cancellation
 *   customer.subscription.updated   — Update tier/plan on upgrade/downgrade
 *   invoice.payment_failed          — Log payment failure
 *   charge.refunded                 — Revoke license on refund
 *   charge.dispute.created          — Revoke license on dispute
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Stripe {

	private const NAMESPACE = 'plm/v1';

	/**
	 * Register webhook route.
	 */
	public static function register_routes(): void {
		register_rest_route( self::NAMESPACE, '/webhook/stripe', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'handle_webhook' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * Get the Stripe webhook secret (supports wp-config constant or encrypted option).
	 */
	private static function get_webhook_secret(): string {
		if ( defined( 'PLM_STRIPE_WEBHOOK_SECRET' ) && ! empty( PLM_STRIPE_WEBHOOK_SECRET ) ) {
			return PLM_STRIPE_WEBHOOK_SECRET;
		}

		return PLM_Encryption::get_option( 'plm_stripe_webhook_secret', '' );
	}

	/**
	 * Handle incoming Stripe webhook.
	 */
	public static function handle_webhook( WP_REST_Request $request ): WP_REST_Response {
		$payload   = $request->get_body();
		$signature = $request->get_header( 'stripe-signature' );

		if ( ! $signature ) {
			return new WP_REST_Response( array(
				'error'   => 'missing_signature',
				'message' => 'Missing stripe-signature header.',
			), 400 );
		}

		// Verify signature.
		$secret = self::get_webhook_secret();
		if ( empty( $secret ) ) {
			return new WP_REST_Response( array(
				'error'   => 'not_configured',
				'message' => 'Stripe webhook secret is not configured.',
			), 500 );
		}

		if ( ! self::verify_signature( $payload, $signature, $secret ) ) {
			return new WP_REST_Response( array(
				'error'   => 'invalid_signature',
				'message' => 'Webhook signature verification failed.',
			), 400 );
		}

		$event = json_decode( $payload, false );
		if ( ! $event || ! isset( $event->id, $event->type ) ) {
			return new WP_REST_Response( array(
				'error'   => 'invalid_payload',
				'message' => 'Could not parse webhook payload.',
			), 400 );
		}

		// Idempotency check.
		global $wpdb;
		$table_events = PLM_Database::table( 'stripe_events' );

		$existing = $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM {$table_events} WHERE stripe_event_id = %s", $event->id )
		);

		if ( $existing ) {
			return new WP_REST_Response( array( 'received' => true, 'message' => 'Event already processed.' ) );
		}

		// Store event.
		$wpdb->insert(
			$table_events,
			array(
				'stripe_event_id' => $event->id,
				'event_type'      => $event->type,
				'payload'         => $payload,
			),
			array( '%s', '%s', '%s' )
		);
		$event_row_id = $wpdb->insert_id;

		// Process event.
		try {
			switch ( $event->type ) {
				case 'checkout.session.completed':
					self::handle_checkout_completed( $event->data->object, $event_row_id );
					break;

				case 'invoice.paid':
					self::handle_invoice_paid( $event->data->object, $event_row_id );
					break;

				case 'customer.subscription.deleted':
					self::handle_subscription_deleted( $event->data->object, $event_row_id );
					break;

				case 'customer.subscription.updated':
					self::handle_subscription_updated( $event->data->object, $event_row_id );
					break;

				case 'invoice.payment_failed':
					self::handle_payment_failed( $event->data->object, $event_row_id );
					break;

				case 'charge.refunded':
					self::handle_charge_refunded( $event->data->object, $event_row_id );
					break;

				case 'charge.dispute.created':
					self::handle_dispute_created( $event->data->object, $event_row_id );
					break;
			}

			$wpdb->update(
				$table_events,
				array( 'processed' => 1 ),
				array( 'id' => $event_row_id ),
				array( '%d' ),
				array( '%d' )
			);
		} catch ( \Exception $e ) {
			error_log( '[PLM Stripe] Error processing ' . $event->type . ': ' . $e->getMessage() );
		}

		return new WP_REST_Response( array( 'received' => true ) );
	}

	/**
	 * Handle checkout.session.completed — create a new license.
	 */
	private static function handle_checkout_completed( object $session, int $event_row_id ): void {
		global $wpdb;

		$customer_id    = $session->customer ?? '';
		$customer_email = $session->customer_details->email ?? '';
		$subscription   = $session->subscription ?? null;
		$metadata       = $session->metadata ?? new \stdClass();
		$product_id     = $metadata->product_id ?? '';

		if ( empty( $product_id ) ) {
			error_log( '[PLM Stripe] No product_id in session metadata.' );
			return;
		}

		// Look up product mapping.
		$table_map = PLM_Database::table( 'stripe_product_map' );
		$mapping   = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table_map} WHERE stripe_product_id = %s", $product_id )
		);

		if ( ! $mapping ) {
			error_log( sprintf( '[PLM Stripe] No product mapping for %s.', $product_id ) );
			return;
		}

		// Generate license key.
		$key_type    = 'pro_plus' === $mapping->license_type ? 'pro_plus' : 'premium';
		$license_key = PLM_License::generate_key( $key_type );

		$expires_at = null;
		if ( (int) $mapping->duration_days > 0 ) {
			$expires_at = gmdate( 'Y-m-d H:i:s', time() + ( (int) $mapping->duration_days * DAY_IN_SECONDS ) );
		}

		$table_licenses = PLM_Database::table( 'licenses' );
		$wpdb->insert(
			$table_licenses,
			array(
				'license_key'            => $license_key,
				'license_type'           => $mapping->license_type,
				'plan'                   => $mapping->plan,
				'status'                 => 'inactive',
				'site_limit'             => (int) $mapping->site_limit,
				'customer_email'         => sanitize_email( $customer_email ),
				'stripe_customer_id'     => sanitize_text_field( $customer_id ),
				'stripe_subscription_id' => $subscription ? sanitize_text_field( $subscription ) : null,
				'expires_at'             => $expires_at,
			),
			array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s' )
		);
		$license_id = $wpdb->insert_id;

		// Link event to license.
		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license_id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( $license_id, 'license.created', array(
			'source'             => 'stripe',
			'stripe_customer_id' => $customer_id,
			'plan'               => $mapping->plan,
			'type'               => $mapping->license_type,
		) );

		// Send license key email to customer via template system.
		PLM_Email::send_purchase( $customer_email, $license_key, $mapping, $expires_at );

		error_log( sprintf( '[PLM Stripe] License created and emailed: %s for %s', PLM_License::mask_key( $license_key ), $customer_email ) );
	}

	/**
	 * Handle invoice.paid — extend existing license.
	 */
	private static function handle_invoice_paid( object $invoice, int $event_row_id ): void {
		global $wpdb;

		$subscription_id = $invoice->subscription ?? '';
		if ( empty( $subscription_id ) ) {
			return;
		}

		// Skip first invoice (handled by checkout.session.completed).
		$billing_reason = $invoice->billing_reason ?? '';
		if ( 'subscription_create' === $billing_reason ) {
			return;
		}

		$table = PLM_Database::table( 'licenses' );
		$license = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_subscription_id = %s", $subscription_id )
		);

		if ( ! $license ) {
			return;
		}

		// Extend by 365 days from current expiry or now.
		$current_expiry = $license->expires_at ? strtotime( $license->expires_at ) : time();
		$base           = max( $current_expiry, time() );
		$new_expiry     = gmdate( 'Y-m-d H:i:s', $base + ( 365 * DAY_IN_SECONDS ) );

		$wpdb->update(
			$table,
			array( 'expires_at' => $new_expiry, 'status' => 'active' ),
			array( 'id' => $license->id ),
			array( '%s', '%s' ),
			array( '%d' )
		);

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'license.renewed', array(
			'new_expiry' => $new_expiry,
			'source'     => 'stripe',
		) );

		// Send renewal confirmation email.
		if ( ! empty( $license->customer_email ) ) {
			PLM_Email::send_renewal( $license, $new_expiry );
		}
	}

	/**
	 * Handle customer.subscription.deleted — mark for expiration.
	 */
	private static function handle_subscription_deleted( object $subscription, int $event_row_id ): void {
		global $wpdb;

		$subscription_id = $subscription->id ?? '';
		$table           = PLM_Database::table( 'licenses' );

		$license = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_subscription_id = %s", $subscription_id )
		);

		if ( ! $license ) {
			return;
		}

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'subscription.cancelled', array(
			'stripe_subscription_id' => $subscription_id,
		) );
	}

	/**
	 * Handle customer.subscription.updated — update tier/plan on upgrade/downgrade.
	 */
	private static function handle_subscription_updated( object $subscription, int $event_row_id ): void {
		global $wpdb;

		$subscription_id = $subscription->id ?? '';
		$table           = PLM_Database::table( 'licenses' );

		$license = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_subscription_id = %s", $subscription_id )
		);

		if ( ! $license ) {
			return;
		}

		// Check if the plan/product changed via items.
		$items = $subscription->items->data ?? array();
		if ( ! empty( $items ) ) {
			$new_product_id = $items[0]->price->product ?? '';
			$new_price_id   = $items[0]->price->id ?? '';

			if ( ! empty( $new_product_id ) ) {
				$table_map = PLM_Database::table( 'stripe_product_map' );
				$mapping   = $wpdb->get_row(
					$wpdb->prepare( "SELECT * FROM {$table_map} WHERE stripe_product_id = %s", $new_product_id )
				);

				if ( $mapping ) {
					$old_type = $license->license_type;
					$old_plan = $license->plan;

					$wpdb->update(
						$table,
						array(
							'license_type' => $mapping->license_type,
							'plan'         => $mapping->plan,
							'site_limit'   => (int) $mapping->site_limit,
						),
						array( 'id' => $license->id ),
						array( '%s', '%s', '%d' ),
						array( '%d' )
					);

					PLM_License::audit_log( (int) $license->id, 'license.tier_changed', array(
						'old_type'       => $old_type,
						'old_plan'       => $old_plan,
						'new_type'       => $mapping->license_type,
						'new_plan'       => $mapping->plan,
						'new_site_limit' => (int) $mapping->site_limit,
						'source'         => 'stripe',
					) );

					error_log( sprintf(
						'[PLM Stripe] License #%d tier changed: %s/%s → %s/%s',
						$license->id,
						$old_type,
						$old_plan,
						$mapping->license_type,
						$mapping->plan
					) );
				}
			}
		}

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);
	}

	/**
	 * Handle invoice.payment_failed.
	 */
	private static function handle_payment_failed( object $invoice, int $event_row_id ): void {
		global $wpdb;

		$subscription_id = $invoice->subscription ?? '';
		if ( empty( $subscription_id ) ) {
			return;
		}

		$table   = PLM_Database::table( 'licenses' );
		$license = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_subscription_id = %s", $subscription_id )
		);

		if ( ! $license ) {
			return;
		}

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'payment.failed', array(
			'stripe_subscription_id' => $subscription_id,
			'amount'                 => $invoice->amount_due ?? 0,
		) );
	}

	/**
	 * Handle charge.refunded — revoke the associated license.
	 */
	private static function handle_charge_refunded( object $charge, int $event_row_id ): void {
		global $wpdb;

		$customer_id = $charge->customer ?? '';
		if ( empty( $customer_id ) ) {
			return;
		}

		$table   = PLM_Database::table( 'licenses' );
		$license = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_customer_id = %s AND status != 'revoked' ORDER BY created_at DESC LIMIT 1", $customer_id )
		);

		if ( ! $license ) {
			return;
		}

		// Only revoke if fully refunded.
		$amount_refunded = $charge->amount_refunded ?? 0;
		$amount_total    = $charge->amount ?? 0;
		$is_full_refund  = $amount_total > 0 && $amount_refunded >= $amount_total;

		if ( $is_full_refund ) {
			$wpdb->update(
				$table,
				array( 'status' => 'revoked' ),
				array( 'id' => $license->id ),
				array( '%s' ),
				array( '%d' )
			);
		}

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'charge.refunded', array(
			'stripe_customer_id' => $customer_id,
			'amount_refunded'    => $amount_refunded,
			'amount_total'       => $amount_total,
			'full_refund'        => $is_full_refund,
			'revoked'            => $is_full_refund,
		) );

		// Send revocation email for full refunds.
		if ( $is_full_refund && ! empty( $license->customer_email ) ) {
			PLM_Email::send_revocation( $license, 'Payment was fully refunded.' );
		}

		error_log( sprintf(
			'[PLM Stripe] Charge refunded for license #%d (%s/%d). %s',
			$license->id,
			$amount_refunded,
			$amount_total,
			$is_full_refund ? 'License revoked.' : 'Partial refund — license not revoked.'
		) );
	}

	/**
	 * Handle charge.dispute.created — revoke the associated license immediately.
	 */
	private static function handle_dispute_created( object $dispute, int $event_row_id ): void {
		global $wpdb;

		$charge_id = $dispute->charge ?? '';
		if ( empty( $charge_id ) ) {
			return;
		}

		// Get customer from the dispute's payment_intent or charge metadata.
		$customer_id = '';
		if ( isset( $dispute->customer ) ) {
			$customer_id = $dispute->customer;
		}

		// Try to find license by customer_id.
		$table   = PLM_Database::table( 'licenses' );
		$license = null;

		if ( ! empty( $customer_id ) ) {
			$license = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$table} WHERE stripe_customer_id = %s AND status != 'revoked' ORDER BY created_at DESC LIMIT 1", $customer_id )
			);
		}

		if ( ! $license ) {
			error_log( sprintf( '[PLM Stripe] Dispute created but no matching license found for charge %s', $charge_id ) );
			return;
		}

		// Immediately revoke on dispute.
		$wpdb->update(
			$table,
			array( 'status' => 'revoked' ),
			array( 'id' => $license->id ),
			array( '%s' ),
			array( '%d' )
		);

		$wpdb->update(
			PLM_Database::table( 'stripe_events' ),
			array( 'license_id' => $license->id ),
			array( 'id' => $event_row_id ),
			array( '%d' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'charge.disputed', array(
			'stripe_customer_id' => $customer_id,
			'charge_id'          => $charge_id,
			'amount'             => $dispute->amount ?? 0,
			'reason'             => $dispute->reason ?? 'unknown',
		) );

		// Send revocation email.
		if ( ! empty( $license->customer_email ) ) {
			$reason = sprintf( 'Payment dispute filed (reason: %s).', $dispute->reason ?? 'unknown' );
			PLM_Email::send_revocation( $license, $reason );
		}

		error_log( sprintf( '[PLM Stripe] License #%d revoked due to payment dispute.', $license->id ) );
	}

	/**
	 * Verify Stripe webhook signature (without Stripe SDK).
	 */
	private static function verify_signature( string $payload, string $sig_header, string $secret ): bool {
		// Parse sig header: t=timestamp,v1=signature.
		$elements = explode( ',', $sig_header );
		$timestamp = '';
		$signatures = array();

		foreach ( $elements as $element ) {
			$parts = explode( '=', $element, 2 );
			if ( count( $parts ) !== 2 ) {
				continue;
			}
			if ( 't' === $parts[0] ) {
				$timestamp = $parts[1];
			} elseif ( 'v1' === $parts[0] ) {
				$signatures[] = $parts[1];
			}
		}

		if ( empty( $timestamp ) || empty( $signatures ) ) {
			return false;
		}

		// Reject if timestamp is older than 5 minutes.
		if ( abs( time() - (int) $timestamp ) > 300 ) {
			return false;
		}

		$signed_payload   = $timestamp . '.' . $payload;
		$expected_sig     = hash_hmac( 'sha256', $signed_payload, $secret );

		foreach ( $signatures as $sig ) {
			if ( hash_equals( $expected_sig, $sig ) ) {
				return true;
			}
		}

		return false;
	}
}
