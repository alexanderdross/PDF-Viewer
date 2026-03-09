<?php
/**
 * Scheduled cron tasks for license management.
 *
 * Cron hooks:
 *   plm_check_expired_licenses   — Daily: Transition expired/grace_period statuses.
 *   plm_check_stale_installations — Daily: Mark installations inactive after 7 days without heartbeat.
 *   plm_send_expiry_warnings     — Daily: Email customers 30/14/7 days before expiration.
 *   plm_cleanup_rate_limits      — Hourly: Remove expired rate limit records.
 *   plm_cleanup_old_data         — Weekly: Archive audit logs and stripe events older than 24 months.
 *   plm_update_geoip_db          — Monthly: Refresh MaxMind GeoLite2 database.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Cron {

	/**
	 * Register all cron hooks and schedules.
	 */
	public static function init(): void {
		// Register custom schedules.
		add_filter( 'cron_schedules', array( __CLASS__, 'add_schedules' ) );

		// Register cron callbacks.
		add_action( 'plm_check_expired_licenses', array( __CLASS__, 'check_expired_licenses' ) );
		add_action( 'plm_check_stale_installations', array( __CLASS__, 'check_stale_installations' ) );
		add_action( 'plm_send_expiry_warnings', array( __CLASS__, 'send_expiry_warnings' ) );
		add_action( 'plm_cleanup_rate_limits', array( __CLASS__, 'cleanup_rate_limits' ) );
		add_action( 'plm_cleanup_old_data', array( __CLASS__, 'cleanup_old_data' ) );
		add_action( 'plm_update_geoip_db', array( __CLASS__, 'update_geoip_db' ) );

		// Schedule events if not already scheduled.
		self::schedule_events();
	}

	/**
	 * Add custom cron schedules.
	 */
	public static function add_schedules( array $schedules ): array {
		$schedules['plm_weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => __( 'Once Weekly', 'pdf-license-manager' ),
		);
		$schedules['plm_monthly'] = array(
			'interval' => 30 * DAY_IN_SECONDS,
			'display'  => __( 'Once Monthly', 'pdf-license-manager' ),
		);
		return $schedules;
	}

	/**
	 * Schedule all cron events.
	 */
	private static function schedule_events(): void {
		$events = array(
			'plm_check_expired_licenses'    => 'daily',
			'plm_check_stale_installations' => 'daily',
			'plm_send_expiry_warnings'      => 'daily',
			'plm_cleanup_rate_limits'        => 'hourly',
			'plm_cleanup_old_data'           => 'plm_weekly',
			'plm_update_geoip_db'            => 'plm_monthly',
		);

		foreach ( $events as $hook => $recurrence ) {
			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $recurrence, $hook );
			}
		}
	}

	/**
	 * Unschedule all cron events (on plugin deactivation).
	 */
	public static function unschedule_all(): void {
		$hooks = array(
			'plm_check_expired_licenses',
			'plm_check_stale_installations',
			'plm_send_expiry_warnings',
			'plm_cleanup_rate_limits',
			'plm_cleanup_old_data',
			'plm_update_geoip_db',
		);

		foreach ( $hooks as $hook ) {
			$timestamp = wp_next_scheduled( $hook );
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, $hook );
			}
		}
	}

	/**
	 * Daily: Check for expired licenses and transition statuses.
	 *
	 * active → grace_period (when expires_at has passed)
	 * grace_period → expired (when grace period has ended)
	 */
	public static function check_expired_licenses(): void {
		global $wpdb;

		$table = PLM_Database::table( 'licenses' );
		$now   = current_time( 'mysql', true );
		$grace_cutoff = gmdate( 'Y-m-d H:i:s', time() - ( PLM_GRACE_PERIOD_DAYS * DAY_IN_SECONDS ) );

		// Active → Grace Period (expired but within grace period).
		$to_grace = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, license_key FROM {$table} WHERE status = 'active' AND expires_at IS NOT NULL AND expires_at < %s",
			$now
		) );

		foreach ( $to_grace as $license ) {
			$wpdb->update(
				$table,
				array( 'status' => 'grace_period' ),
				array( 'id' => $license->id ),
				array( '%s' ),
				array( '%d' )
			);
			PLM_License::audit_log( (int) $license->id, 'license.grace_period', array(
				'source' => 'cron',
			) );
		}

		// Grace Period → Expired (grace period has ended).
		$to_expired = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, license_key FROM {$table} WHERE status = 'grace_period' AND expires_at IS NOT NULL AND expires_at < %s",
			$grace_cutoff
		) );

		foreach ( $to_expired as $license ) {
			$wpdb->update(
				$table,
				array( 'status' => 'expired' ),
				array( 'id' => $license->id ),
				array( '%s' ),
				array( '%d' )
			);
			PLM_License::audit_log( (int) $license->id, 'license.expired', array(
				'source' => 'cron',
			) );
		}

		if ( $to_grace || $to_expired ) {
			error_log( sprintf(
				'[PLM Cron] License status transitions: %d → grace_period, %d → expired',
				count( $to_grace ),
				count( $to_expired )
			) );
		}
	}

	/**
	 * Daily: Mark installations as inactive if no heartbeat for 7+ days.
	 */
	public static function check_stale_installations(): void {
		global $wpdb;

		$table   = PLM_Database::table( 'installations' );
		$stale_days = defined( 'PLM_STALE_INSTALLATION_DAYS' ) ? (int) PLM_STALE_INSTALLATION_DAYS : 7;
		$cutoff  = gmdate( 'Y-m-d H:i:s', time() - ( $stale_days * DAY_IN_SECONDS ) );

		$stale = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, license_id, site_url FROM {$table} WHERE is_active = 1 AND last_checked_at < %s",
			$cutoff
		) );

		foreach ( $stale as $installation ) {
			$wpdb->update(
				$table,
				array(
					'is_active'      => 0,
					'deactivated_at' => current_time( 'mysql', true ),
				),
				array( 'id' => $installation->id ),
				array( '%d', '%s' ),
				array( '%d' )
			);

			PLM_License::audit_log( (int) $installation->license_id, 'installation.stale', array(
				'installation_id' => $installation->id,
				'site_url'        => $installation->site_url,
				'source'          => 'cron',
			) );
		}

		if ( $stale ) {
			error_log( sprintf( '[PLM Cron] Marked %d stale installations as inactive.', count( $stale ) ) );
		}
	}

	/**
	 * Daily: Send expiry warning emails at 30, 14, and 7 days before expiration.
	 */
	public static function send_expiry_warnings(): void {
		global $wpdb;

		$table = PLM_Database::table( 'licenses' );
		$warning_days = array( 30, 14, 7 );

		foreach ( $warning_days as $days ) {
			$target_date = gmdate( 'Y-m-d', time() + ( $days * DAY_IN_SECONDS ) );

			$licenses = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$table} WHERE status = 'active' AND expires_at IS NOT NULL AND DATE(expires_at) = %s AND customer_email != ''",
				$target_date
			) );

			foreach ( $licenses as $license ) {
				// Check if we already sent this warning (via audit log).
				$t_log     = PLM_Database::table( 'audit_logs' );
				$event_key = 'expiry_warning.' . $days . 'd';
				$already_sent = $wpdb->get_var( $wpdb->prepare(
					"SELECT COUNT(*) FROM {$t_log} WHERE license_id = %d AND event_type = %s",
					$license->id,
					$event_key
				) );

				if ( $already_sent ) {
					continue;
				}

				PLM_Email::send_expiry_warning( $license, $days );

				PLM_License::audit_log( (int) $license->id, $event_key, array(
					'days_remaining'   => $days,
					'customer_email'   => $license->customer_email,
					'source'           => 'cron',
				) );
			}
		}
	}

	/**
	 * Hourly: Clean up expired rate limit records.
	 */
	public static function cleanup_rate_limits(): void {
		global $wpdb;

		$table   = PLM_Database::table( 'rate_limits' );
		$now     = current_time( 'mysql', true );

		$deleted = $wpdb->query( $wpdb->prepare(
			"DELETE FROM {$table} WHERE expires_at < %s",
			$now
		) );

		if ( $deleted > 0 ) {
			error_log( sprintf( '[PLM Cron] Cleaned up %d expired rate limit records.', $deleted ) );
		}
	}

	/**
	 * Weekly: Archive old audit logs and stripe events (>24 months).
	 */
	public static function cleanup_old_data(): void {
		global $wpdb;

		$retention_months = defined( 'PLM_DATA_RETENTION_MONTHS' ) ? (int) PLM_DATA_RETENTION_MONTHS : 24;
		$cutoff = gmdate( 'Y-m-d H:i:s', time() - ( $retention_months * 30 * DAY_IN_SECONDS ) );

		// Clean audit logs.
		$t_audit = PLM_Database::table( 'audit_logs' );
		$deleted_audit = $wpdb->query( $wpdb->prepare(
			"DELETE FROM {$t_audit} WHERE created_at < %s",
			$cutoff
		) );

		// Clean processed stripe events.
		$t_stripe = PLM_Database::table( 'stripe_events' );
		$deleted_stripe = $wpdb->query( $wpdb->prepare(
			"DELETE FROM {$t_stripe} WHERE created_at < %s AND processed = 1",
			$cutoff
		) );

		if ( $deleted_audit || $deleted_stripe ) {
			error_log( sprintf(
				'[PLM Cron] Data cleanup: %d audit logs, %d stripe events archived.',
				$deleted_audit,
				$deleted_stripe
			) );
		}
	}

	/**
	 * Monthly: Update the MaxMind GeoLite2 database.
	 *
	 * Requires geoipupdate CLI tool to be installed, or a MaxMind license key.
	 * Configure via PLM_MAXMIND_LICENSE_KEY constant.
	 */
	public static function update_geoip_db(): void {
		if ( ! defined( 'PLM_MAXMIND_LICENSE_KEY' ) || empty( PLM_MAXMIND_LICENSE_KEY ) ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$target_dir = $upload_dir['basedir'] . '/plm';
		$target_file = $target_dir . '/GeoLite2-City.mmdb';

		// Ensure directory exists.
		if ( ! is_dir( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}

		$download_url = sprintf(
			'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=%s&suffix=tar.gz',
			urlencode( PLM_MAXMIND_LICENSE_KEY )
		);

		$tmp_file = download_url( $download_url, 300 );
		if ( is_wp_error( $tmp_file ) ) {
			error_log( '[PLM Cron] GeoIP download failed: ' . $tmp_file->get_error_message() );
			return;
		}

		// Extract .mmdb from tar.gz.
		$phar_path = 'phar://' . $tmp_file;
		try {
			$phar = new PharData( $tmp_file );
			foreach ( new RecursiveIteratorIterator( $phar ) as $file ) {
				if ( str_ends_with( $file->getPathname(), '.mmdb' ) ) {
					copy( $file->getPathname(), $target_file );
					error_log( '[PLM Cron] GeoIP database updated successfully.' );
					break;
				}
			}
		} catch ( \Exception $e ) {
			error_log( '[PLM Cron] GeoIP extraction failed: ' . $e->getMessage() );
		}

		wp_delete_file( $tmp_file );
	}
}
