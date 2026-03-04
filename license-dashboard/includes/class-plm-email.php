<?php
/**
 * Email template system for license notifications.
 *
 * Templates are loaded from email-templates/ directory.
 * Each template receives variables via extract() for use in the template file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Email {

	/**
	 * Send an email using a template.
	 *
	 * @param string $to        Recipient email.
	 * @param string $subject   Email subject.
	 * @param string $template  Template filename (without .php).
	 * @param array  $variables Variables to pass to the template.
	 * @return bool Whether the email was sent.
	 */
	public static function send( string $to, string $subject, string $template, array $variables = array() ): bool {
		if ( empty( $to ) ) {
			error_log( '[PLM Email] Cannot send email: no recipient.' );
			return false;
		}

		$template_path = PLM_PLUGIN_DIR . 'email-templates/' . $template . '.php';
		if ( ! file_exists( $template_path ) ) {
			error_log( sprintf( '[PLM Email] Template not found: %s', $template ) );
			return false;
		}

		// Render template.
		ob_start();
		extract( $variables, EXTR_SKIP );
		include $template_path;
		$message = ob_get_clean();

		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			'From: PDF Embed & SEO Optimize <noreply@pdfviewer.drossmedia.de>',
		);

		$sent = wp_mail( $to, $subject, $message, $headers );

		if ( ! $sent ) {
			error_log( sprintf( '[PLM Email] Failed to send "%s" email to %s', $template, $to ) );
		}

		return $sent;
	}

	/**
	 * Send license purchase email.
	 */
	public static function send_purchase( string $email, string $license_key, object $mapping, ?string $expires_at ): bool {
		$type_label = ucfirst( str_replace( '_', ' ', $mapping->license_type ) );
		$plan_label = ucfirst( $mapping->plan );
		$site_limit = 0 === (int) $mapping->site_limit ? 'Unlimited' : (string) $mapping->site_limit;
		$expiry_display = $expires_at ? date( 'F j, Y', strtotime( $expires_at ) ) : 'Lifetime';

		$subject = sprintf(
			__( 'Your %s License Key - PDF Embed & SEO Optimize', 'pdf-license-manager' ),
			$type_label
		);

		return self::send( $email, $subject, 'license-purchase', array(
			'license_key'    => $license_key,
			'type_label'     => $type_label,
			'plan_label'     => $plan_label,
			'site_limit'     => $site_limit,
			'expiry_display' => $expiry_display,
		) );
	}

	/**
	 * Send expiry warning email.
	 */
	public static function send_expiry_warning( object $license, int $days_remaining ): bool {
		$type_label = ucfirst( str_replace( '_', ' ', $license->license_type ) );
		$expiry_display = date( 'F j, Y', strtotime( $license->expires_at ) );

		$subject = sprintf(
			__( 'Your PDF Embed & SEO License Expires in %d Days', 'pdf-license-manager' ),
			$days_remaining
		);

		return self::send( $license->customer_email, $subject, 'expiry-warning', array(
			'license_key'    => PLM_License::mask_key( $license->license_key ),
			'type_label'     => $type_label,
			'plan_label'     => ucfirst( $license->plan ),
			'days_remaining' => $days_remaining,
			'expiry_display' => $expiry_display,
			'customer_name'  => $license->customer_name ?: 'Customer',
		) );
	}

	/**
	 * Send license renewal confirmation email.
	 */
	public static function send_renewal( object $license, string $new_expiry ): bool {
		$type_label = ucfirst( str_replace( '_', ' ', $license->license_type ) );
		$expiry_display = date( 'F j, Y', strtotime( $new_expiry ) );

		$subject = __( 'License Renewed - PDF Embed & SEO Optimize', 'pdf-license-manager' );

		return self::send( $license->customer_email, $subject, 'license-renewal', array(
			'license_key'    => PLM_License::mask_key( $license->license_key ),
			'type_label'     => $type_label,
			'plan_label'     => ucfirst( $license->plan ),
			'expiry_display' => $expiry_display,
			'customer_name'  => $license->customer_name ?: 'Customer',
		) );
	}

	/**
	 * Send license revocation email.
	 */
	public static function send_revocation( object $license, string $reason ): bool {
		$type_label = ucfirst( str_replace( '_', ' ', $license->license_type ) );

		$subject = __( 'License Revoked - PDF Embed & SEO Optimize', 'pdf-license-manager' );

		return self::send( $license->customer_email, $subject, 'license-revoked', array(
			'license_key'   => PLM_License::mask_key( $license->license_key ),
			'type_label'    => $type_label,
			'plan_label'    => ucfirst( $license->plan ),
			'reason'        => $reason,
			'customer_name' => $license->customer_name ?: 'Customer',
		) );
	}
}
