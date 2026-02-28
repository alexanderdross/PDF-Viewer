<?php
/**
 * License key generation, validation, and management.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_License {

	/**
	 * License key format patterns.
	 */
	private const PATTERNS = array(
		'premium'   => '/^PDF\$PRO#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/',
		'pro_plus'  => '/^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/',
		'unlimited' => '/^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/',
		'dev'       => '/^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/',
	);

	/**
	 * Local/staging domain patterns.
	 */
	private const LOCAL_PATTERNS = array(
		'/^localhost(:\d+)?$/',
		'/^127\.0\.0\.1(:\d+)?$/',
		'/\.local$/',
		'/\.test$/',
		'/\.dev$/',
		'/\.staging\./',
		'/\.stage\./',
		'/^10\.\d+\.\d+\.\d+/',
		'/^192\.168\.\d+\.\d+/',
	);

	/**
	 * Generate a random segment of uppercase alphanumeric characters.
	 */
	private static function random_segment( int $length ): string {
		$chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$result = '';
		$bytes  = random_bytes( $length );
		for ( $i = 0; $i < $length; $i++ ) {
			$result .= $chars[ ord( $bytes[ $i ] ) % strlen( $chars ) ];
		}
		return $result;
	}

	/**
	 * Generate a license key.
	 */
	public static function generate_key( string $type = 'premium' ): string {
		$s = fn( int $n ) => self::random_segment( $n );

		return match ( $type ) {
			'pro_plus'  => "PDF\$PRO+#{$s(4)}-{$s(4)}@{$s(4)}-{$s(4)}!{$s(4)}",
			'unlimited' => "PDF\$UNLIMITED#{$s(4)}@{$s(4)}!{$s(4)}",
			'dev'       => "PDF\$DEV#{$s(4)}-{$s(4)}@{$s(4)}!{$s(4)}",
			default     => "PDF\$PRO#{$s(4)}-{$s(4)}@{$s(4)}-{$s(4)}!{$s(4)}",
		};
	}

	/**
	 * Detect the license type from a key.
	 */
	public static function detect_type( string $key ): ?string {
		// Check pro_plus first (contains PRO as substring).
		if ( preg_match( self::PATTERNS['pro_plus'], $key ) ) {
			return 'pro_plus';
		}
		if ( preg_match( self::PATTERNS['premium'], $key ) ) {
			return 'premium';
		}
		if ( preg_match( self::PATTERNS['unlimited'], $key ) ) {
			return 'unlimited';
		}
		if ( preg_match( self::PATTERNS['dev'], $key ) ) {
			return 'dev';
		}
		return null;
	}

	/**
	 * Check if a license key format is valid.
	 */
	public static function is_valid_format( string $key ): bool {
		return null !== self::detect_type( $key );
	}

	/**
	 * Check if a domain is local/staging.
	 */
	public static function is_local_domain( string $site_url ): bool {
		$parsed = wp_parse_url( $site_url );
		$host   = $parsed['host'] ?? $site_url;

		foreach ( self::LOCAL_PATTERNS as $pattern ) {
			if ( preg_match( $pattern, $host ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Normalize a site URL to just the hostname.
	 */
	public static function normalize_url( string $site_url ): string {
		$parsed = wp_parse_url( $site_url );
		$host   = $parsed['host'] ?? $site_url;
		return strtolower( $host );
	}

	/**
	 * Get days remaining until expiration.
	 */
	public static function days_remaining( ?string $expires_at ): ?int {
		if ( ! $expires_at ) {
			return null; // Lifetime.
		}

		$expires = new DateTime( $expires_at, new DateTimeZone( 'UTC' ) );
		$now     = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
		$diff    = $now->diff( $expires );

		return $diff->invert ? -$diff->days : $diff->days;
	}

	/**
	 * Compute the effective status based on expiration date.
	 */
	public static function compute_status( ?string $expires_at, string $current_status ): string {
		if ( in_array( $current_status, array( 'revoked', 'inactive' ), true ) ) {
			return $current_status;
		}

		if ( ! $expires_at ) {
			return 'active'; // Lifetime.
		}

		$days = self::days_remaining( $expires_at );
		if ( null === $days ) {
			return 'active';
		}

		if ( $days > 0 ) {
			return 'active';
		}
		if ( $days >= -PLM_GRACE_PERIOD_DAYS ) {
			return 'grace_period';
		}
		return 'expired';
	}

	/**
	 * Mask a license key for display (show prefix + last 4 chars).
	 */
	public static function mask_key( string $key ): string {
		if ( strlen( $key ) < 10 ) {
			return '****';
		}
		$hash_pos = strpos( $key, '#' );
		if ( false === $hash_pos ) {
			return '****';
		}
		$prefix = substr( $key, 0, $hash_pos + 1 );
		$suffix = substr( $key, -4 );
		return $prefix . '****' . $suffix;
	}

	/**
	 * Anonymize an IP address (zero out last octet for IPv4).
	 */
	public static function anonymize_ip( string $ip ): string {
		if ( str_contains( $ip, ':' ) ) {
			// IPv6: zero out last 80 bits.
			$parts = explode( ':', $ip );
			if ( count( $parts ) >= 4 ) {
				return implode( ':', array_slice( $parts, 0, 4 ) ) . '::';
			}
			return $ip;
		}

		// IPv4: zero out last octet.
		$parts = explode( '.', $ip );
		if ( count( $parts ) === 4 ) {
			$parts[3] = '0';
			return implode( '.', $parts );
		}
		return $ip;
	}

	/**
	 * Get the client IP from the request.
	 */
	public static function get_client_ip(): string {
		$headers = array( 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' );
		foreach ( $headers as $header ) {
			if ( ! empty( $_SERVER[ $header ] ) ) {
				$ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );
				$ip  = trim( $ips[0] );
				if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					return $ip;
				}
			}
		}
		return '0.0.0.0';
	}

	/**
	 * Get count of active non-local installations for a license.
	 */
	public static function count_active_sites( int $license_id ): int {
		global $wpdb;
		$table = PLM_Database::table( 'installations' );

		return (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$table} WHERE license_id = %d AND is_active = 1 AND is_local = 0",
				$license_id
			)
		);
	}

	/**
	 * Log an audit event.
	 */
	public static function audit_log( ?int $license_id, string $event_type, array $details = array() ): void {
		global $wpdb;
		$table = PLM_Database::table( 'audit_logs' );

		$wpdb->insert(
			$table,
			array(
				'license_id' => $license_id,
				'event_type' => $event_type,
				'details'    => wp_json_encode( $details ),
				'ip_address' => self::anonymize_ip( self::get_client_ip() ),
				'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] )
					? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 500 )
					: null,
			),
			array( '%d', '%s', '%s', '%s', '%s' )
		);
	}
}
