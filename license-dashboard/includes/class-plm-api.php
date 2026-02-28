<?php
/**
 * Public REST API endpoints for license validation.
 *
 * Endpoints:
 *   POST /wp-json/plm/v1/license/validate
 *   POST /wp-json/plm/v1/license/activate
 *   POST /wp-json/plm/v1/license/deactivate
 *   POST /wp-json/plm/v1/license/check
 *   GET  /wp-json/plm/v1/health
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_API {

	private const NAMESPACE = 'plm/v1';

	/**
	 * Rate limit storage (transients).
	 */
	private static function check_rate_limit( string $identifier, string $type ): bool {
		$limits = array(
			'api'      => array( 'max' => 60, 'window' => 60 ),        // 60/min per IP.
			'activate' => array( 'max' => 10, 'window' => 3600 ),      // 10/hour per key.
			'check'    => array( 'max' => 1000, 'window' => 86400 ),   // 1000/day per key.
		);

		if ( ! isset( $limits[ $type ] ) ) {
			return true;
		}

		$config     = $limits[ $type ];
		$key        = 'plm_rl_' . md5( $type . ':' . $identifier );
		$count      = (int) get_transient( $key );

		if ( $count >= $config['max'] ) {
			return false;
		}

		set_transient( $key, $count + 1, $config['window'] );
		return true;
	}

	/**
	 * Register all public routes.
	 */
	public static function register_routes(): void {
		register_rest_route( self::NAMESPACE, '/license/validate', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'validate' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( self::NAMESPACE, '/license/activate', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'activate' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( self::NAMESPACE, '/license/deactivate', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'deactivate' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( self::NAMESPACE, '/license/check', array(
			'methods'             => 'POST',
			'callback'            => array( __CLASS__, 'check' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( self::NAMESPACE, '/health', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'health' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * GET /health
	 */
	public static function health(): WP_REST_Response {
		global $wpdb;

		$db_ok = (bool) $wpdb->get_var( 'SELECT 1' );

		return new WP_REST_Response( array(
			'status'    => $db_ok ? 'ok' : 'error',
			'version'   => PLM_VERSION,
			'database'  => $db_ok ? 'connected' : 'disconnected',
			'timestamp' => gmdate( 'c' ),
		), $db_ok ? 200 : 503 );
	}

	/**
	 * POST /license/validate
	 */
	public static function validate( WP_REST_Request $request ): WP_REST_Response {
		$ip = PLM_License::get_client_ip();
		if ( ! self::check_rate_limit( $ip, 'api' ) ) {
			return self::rate_limit_response();
		}

		$license_key = sanitize_text_field( $request->get_param( 'license_key' ) ?? '' );
		$platform    = sanitize_text_field( $request->get_param( 'platform' ) ?? '' );

		if ( empty( $license_key ) ) {
			return new WP_REST_Response( array(
				'error'   => 'missing_field',
				'message' => 'license_key is required.',
			), 400 );
		}

		$license = self::get_license_by_key( $license_key );
		if ( ! $license ) {
			return new WP_REST_Response( array(
				'error'   => 'not_found',
				'message' => 'License key not found.',
			), 404 );
		}

		$status         = PLM_License::compute_status( $license->expires_at, $license->status );
		$days_remaining = PLM_License::days_remaining( $license->expires_at );
		$active_sites   = PLM_License::count_active_sites( (int) $license->id );

		$message = 'License is valid.';
		if ( 'expired' === $status ) {
			$message = 'License has expired. Please renew at https://pdfviewer.drossmedia.de';
		} elseif ( 'grace_period' === $status ) {
			$grace_days = PLM_GRACE_PERIOD_DAYS + ( $days_remaining ?? 0 );
			$message    = sprintf( 'License is in grace period. Features will be disabled in %d days.', $grace_days );
		} elseif ( 'revoked' === $status ) {
			$message = 'License has been revoked.';
		}

		return new WP_REST_Response( array(
			'valid'          => in_array( $status, array( 'active', 'grace_period' ), true ),
			'status'         => $status,
			'type'           => $license->license_type,
			'plan'           => $license->plan,
			'expires_at'     => $license->expires_at,
			'days_remaining' => $days_remaining,
			'site_limit'     => (int) $license->site_limit,
			'active_sites'   => $active_sites,
			'message'        => $message,
		) );
	}

	/**
	 * POST /license/activate
	 */
	public static function activate( WP_REST_Request $request ): WP_REST_Response {
		global $wpdb;

		$ip = PLM_License::get_client_ip();
		if ( ! self::check_rate_limit( $ip, 'api' ) ) {
			return self::rate_limit_response();
		}

		$license_key    = sanitize_text_field( $request->get_param( 'license_key' ) ?? '' );
		$site_url       = esc_url_raw( $request->get_param( 'site_url' ) ?? '' );
		$platform       = sanitize_text_field( $request->get_param( 'platform' ) ?? '' );
		$plugin_version = sanitize_text_field( $request->get_param( 'plugin_version' ) ?? '' );
		$php_version    = sanitize_text_field( $request->get_param( 'php_version' ) ?? '' );
		$cms_version    = sanitize_text_field( $request->get_param( 'cms_version' ) ?? '' );
		$node_version   = sanitize_text_field( $request->get_param( 'node_version' ) ?? '' );

		if ( empty( $license_key ) || empty( $site_url ) || empty( $platform ) || empty( $plugin_version ) ) {
			return new WP_REST_Response( array(
				'error'   => 'missing_field',
				'message' => 'license_key, site_url, platform, and plugin_version are required.',
			), 400 );
		}

		if ( ! in_array( $platform, array( 'wordpress', 'drupal', 'react' ), true ) ) {
			return new WP_REST_Response( array(
				'error'   => 'invalid_platform',
				'message' => 'Platform must be wordpress, drupal, or react.',
			), 400 );
		}

		if ( ! self::check_rate_limit( $license_key, 'activate' ) ) {
			return self::rate_limit_response();
		}

		$license = self::get_license_by_key( $license_key );
		if ( ! $license ) {
			return new WP_REST_Response( array(
				'error'   => 'not_found',
				'message' => 'License key not found.',
			), 404 );
		}

		$status = PLM_License::compute_status( $license->expires_at, $license->status );
		if ( in_array( $status, array( 'expired', 'revoked', 'inactive' ), true ) ) {
			return new WP_REST_Response( array(
				'activated' => false,
				'message'   => sprintf( 'Cannot activate: license is %s.', $status ),
			), 403 );
		}

		$normalized_url = PLM_License::normalize_url( $site_url );
		$is_local       = PLM_License::is_local_domain( $site_url );
		$table_inst     = PLM_Database::table( 'installations' );

		// Check if already activated for this site.
		$existing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_inst} WHERE license_id = %d AND site_url = %s AND is_active = 1",
				$license->id,
				$normalized_url
			)
		);

		$active_sites = PLM_License::count_active_sites( (int) $license->id );

		if ( $existing ) {
			// Update existing installation.
			$wpdb->update(
				$table_inst,
				array(
					'plugin_version'  => $plugin_version,
					'php_version'     => $php_version ?: null,
					'cms_version'     => $cms_version ?: null,
					'node_version'    => $node_version ?: null,
					'last_checked_at' => current_time( 'mysql', true ),
				),
				array( 'id' => $existing->id ),
				array( '%s', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);

			return new WP_REST_Response( array(
				'activated'       => true,
				'activation_id'   => (int) $existing->id,
				'status'          => $status,
				'site_limit'      => (int) $license->site_limit,
				'active_sites'    => $active_sites,
				'remaining_sites' => 0 === (int) $license->site_limit ? -1 : (int) $license->site_limit - $active_sites,
				'expires_at'      => $license->expires_at,
				'message'         => sprintf( 'License already active for %s. Installation updated.', $normalized_url ),
			) );
		}

		// Check site limit.
		$site_limit = (int) $license->site_limit;
		if ( ! $is_local && $site_limit > 0 && $active_sites >= $site_limit ) {
			$active_domains = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT site_url FROM {$table_inst} WHERE license_id = %d AND is_active = 1 AND is_local = 0",
					$license->id
				)
			);

			return new WP_REST_Response( array(
				'activated'      => false,
				'error'          => 'site_limit_reached',
				'site_limit'     => $site_limit,
				'active_sites'   => $active_sites,
				'active_domains' => $active_domains,
				'message'        => 'Site limit reached. Deactivate an existing site or upgrade your plan.',
			), 403 );
		}

		// Create new installation.
		$wpdb->insert(
			$table_inst,
			array(
				'license_id'     => $license->id,
				'site_url'       => $normalized_url,
				'platform'       => $platform,
				'plugin_version' => $plugin_version,
				'php_version'    => $php_version ?: null,
				'cms_version'    => $cms_version ?: null,
				'node_version'   => $node_version ?: null,
				'is_local'       => $is_local ? 1 : 0,
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
		);
		$installation_id = $wpdb->insert_id;

		// Set activated_at on first activation.
		if ( ! $license->activated_at ) {
			$wpdb->update(
				PLM_Database::table( 'licenses' ),
				array(
					'activated_at' => current_time( 'mysql', true ),
					'status'       => 'active',
				),
				array( 'id' => $license->id ),
				array( '%s', '%s' ),
				array( '%d' )
			);
		}

		// Geo-IP lookup.
		PLM_GeoIP::lookup_and_store( $installation_id, $ip );

		// Audit log.
		PLM_License::audit_log( (int) $license->id, 'license.activated', array(
			'site_url'       => $normalized_url,
			'platform'       => $platform,
			'plugin_version' => $plugin_version,
			'is_local'       => $is_local,
		) );

		$new_active = $is_local ? $active_sites : $active_sites + 1;

		return new WP_REST_Response( array(
			'activated'       => true,
			'activation_id'   => $installation_id,
			'status'          => $status,
			'site_limit'      => $site_limit,
			'active_sites'    => $new_active,
			'remaining_sites' => 0 === $site_limit ? -1 : $site_limit - $new_active,
			'expires_at'      => $license->expires_at,
			'latest_version'  => PLM_LATEST_PLUGIN_VERSION,
			'message'         => sprintf( 'License activated successfully for %s', $normalized_url ),
		) );
	}

	/**
	 * POST /license/deactivate
	 */
	public static function deactivate( WP_REST_Request $request ): WP_REST_Response {
		global $wpdb;

		$ip = PLM_License::get_client_ip();
		if ( ! self::check_rate_limit( $ip, 'api' ) ) {
			return self::rate_limit_response();
		}

		$license_key = sanitize_text_field( $request->get_param( 'license_key' ) ?? '' );
		$site_url    = esc_url_raw( $request->get_param( 'site_url' ) ?? '' );

		if ( empty( $license_key ) || empty( $site_url ) ) {
			return new WP_REST_Response( array(
				'error'   => 'missing_field',
				'message' => 'license_key and site_url are required.',
			), 400 );
		}

		$license = self::get_license_by_key( $license_key );
		if ( ! $license ) {
			return new WP_REST_Response( array(
				'error'   => 'not_found',
				'message' => 'License key not found.',
			), 404 );
		}

		$normalized_url = PLM_License::normalize_url( $site_url );
		$table_inst     = PLM_Database::table( 'installations' );

		$installation = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_inst} WHERE license_id = %d AND site_url = %s AND is_active = 1",
				$license->id,
				$normalized_url
			)
		);

		if ( ! $installation ) {
			return new WP_REST_Response( array(
				'error'   => 'not_found',
				'message' => sprintf( 'No active installation found for %s.', $normalized_url ),
			), 404 );
		}

		// Deactivate.
		$wpdb->update(
			$table_inst,
			array(
				'is_active'      => 0,
				'deactivated_at' => current_time( 'mysql', true ),
			),
			array( 'id' => $installation->id ),
			array( '%d', '%s' ),
			array( '%d' )
		);

		PLM_License::audit_log( (int) $license->id, 'license.deactivated', array(
			'site_url' => $normalized_url,
		) );

		$active_sites = PLM_License::count_active_sites( (int) $license->id );
		$site_limit   = (int) $license->site_limit;

		return new WP_REST_Response( array(
			'deactivated'     => true,
			'active_sites'    => $active_sites,
			'remaining_sites' => 0 === $site_limit ? -1 : $site_limit - $active_sites,
			'message'         => sprintf( 'License deactivated for %s', $normalized_url ),
		) );
	}

	/**
	 * POST /license/check (Heartbeat)
	 */
	public static function check( WP_REST_Request $request ): WP_REST_Response {
		global $wpdb;

		$ip = PLM_License::get_client_ip();
		if ( ! self::check_rate_limit( $ip, 'api' ) ) {
			return self::rate_limit_response();
		}

		$license_key    = sanitize_text_field( $request->get_param( 'license_key' ) ?? '' );
		$site_url       = esc_url_raw( $request->get_param( 'site_url' ) ?? '' );
		$plugin_version = sanitize_text_field( $request->get_param( 'plugin_version' ) ?? '' );
		$platform       = sanitize_text_field( $request->get_param( 'platform' ) ?? '' );

		if ( empty( $license_key ) || empty( $site_url ) || empty( $plugin_version ) ) {
			return new WP_REST_Response( array(
				'error'   => 'missing_field',
				'message' => 'license_key, site_url, and plugin_version are required.',
			), 400 );
		}

		if ( ! self::check_rate_limit( $license_key, 'check' ) ) {
			return self::rate_limit_response();
		}

		$license = self::get_license_by_key( $license_key );
		if ( ! $license ) {
			return new WP_REST_Response( array(
				'error'   => 'not_found',
				'message' => 'License key not found.',
			), 404 );
		}

		$status           = PLM_License::compute_status( $license->expires_at, $license->status );
		$days_remaining   = PLM_License::days_remaining( $license->expires_at );
		$update_available = $plugin_version !== PLM_LATEST_PLUGIN_VERSION;
		$normalized_url   = PLM_License::normalize_url( $site_url );
		$table_inst       = PLM_Database::table( 'installations' );

		// Update installation heartbeat.
		$installation = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT i.*, g.updated_at AS geo_updated_at FROM {$table_inst} i
				LEFT JOIN " . PLM_Database::table( 'geo_data' ) . " g ON g.installation_id = i.id
				WHERE i.license_id = %d AND i.site_url = %s AND i.is_active = 1",
				$license->id,
				$normalized_url
			)
		);

		if ( $installation ) {
			$wpdb->update(
				$table_inst,
				array(
					'last_checked_at' => current_time( 'mysql', true ),
					'plugin_version'  => $plugin_version,
				),
				array( 'id' => $installation->id ),
				array( '%s', '%s' ),
				array( '%d' )
			);

			// Refresh geo data if older than 30 days.
			if ( $installation->geo_updated_at ) {
				$geo_age_days = ( time() - strtotime( $installation->geo_updated_at ) ) / DAY_IN_SECONDS;
				if ( $geo_age_days > 30 ) {
					PLM_GeoIP::lookup_and_store( (int) $installation->id, $ip, true );
				}
			}
		}

		// Build message.
		$message = null;
		if ( null !== $days_remaining && $days_remaining <= 14 && $days_remaining > 0 ) {
			$message = sprintf(
				'Your license expires in %d day%s. Renew at https://pdfviewer.drossmedia.de',
				$days_remaining,
				1 === $days_remaining ? '' : 's'
			);
		} elseif ( 'grace_period' === $status ) {
			$grace_days = PLM_GRACE_PERIOD_DAYS + ( $days_remaining ?? 0 );
			$message    = sprintf(
				'Your license is in grace period. Features will be disabled in %d day%s.',
				$grace_days,
				1 === $grace_days ? '' : 's'
			);
		} elseif ( $update_available ) {
			$message = sprintf( 'A new version (%s) is available. Update recommended.', PLM_LATEST_PLUGIN_VERSION );
		}

		$is_valid = in_array( $status, array( 'active', 'grace_period' ), true );

		return new WP_REST_Response( array(
			'valid'            => $is_valid,
			'status'           => $status,
			'expires_at'       => $license->expires_at,
			'days_remaining'   => $days_remaining,
			'latest_version'   => PLM_LATEST_PLUGIN_VERSION,
			'update_available' => $update_available,
			'update_url'       => $update_available && $is_valid
				? sprintf( 'https://pdfviewer.drossmedia.de/download/%s/%s', $license->license_type, PLM_LATEST_PLUGIN_VERSION )
				: null,
			'message'          => $message,
			'checked_at'       => gmdate( 'c' ),
		) );
	}

	/**
	 * Get a license row by key.
	 */
	private static function get_license_by_key( string $key ): ?object {
		global $wpdb;
		$table = PLM_Database::table( 'licenses' );

		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE license_key = %s", $key )
		);

		return $result ?: null;
	}

	/**
	 * Rate limit exceeded response.
	 */
	private static function rate_limit_response(): WP_REST_Response {
		return new WP_REST_Response( array(
			'error'   => 'rate_limit_exceeded',
			'message' => 'Too many requests. Please try again later.',
		), 429 );
	}
}
