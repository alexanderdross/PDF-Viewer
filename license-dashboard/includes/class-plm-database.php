<?php
/**
 * Database table creation and schema management.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Database {

	/**
	 * Create all custom tables.
	 */
	public static function create_tables(): void {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Licenses table.
		$table_licenses = $wpdb->prefix . 'plm_licenses';
		$sql_licenses   = "CREATE TABLE {$table_licenses} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			license_key VARCHAR(100) NOT NULL,
			license_type ENUM('premium','pro_plus') NOT NULL DEFAULT 'premium',
			plan ENUM('starter','professional','agency','enterprise','lifetime','dev') NOT NULL DEFAULT 'starter',
			status ENUM('active','expired','grace_period','revoked','inactive') NOT NULL DEFAULT 'inactive',
			site_limit INT UNSIGNED NOT NULL DEFAULT 1,
			customer_email VARCHAR(255) NOT NULL DEFAULT '',
			customer_name VARCHAR(255) DEFAULT NULL,
			stripe_customer_id VARCHAR(255) DEFAULT NULL,
			stripe_subscription_id VARCHAR(255) DEFAULT NULL,
			expires_at DATETIME DEFAULT NULL,
			activated_at DATETIME DEFAULT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			notes TEXT DEFAULT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY license_key (license_key),
			KEY status (status),
			KEY customer_email (customer_email),
			KEY stripe_customer_id (stripe_customer_id),
			KEY expires_at (expires_at)
		) {$charset_collate};";

		dbDelta( $sql_licenses );

		// Installations table.
		$table_installations = $wpdb->prefix . 'plm_installations';
		$sql_installations   = "CREATE TABLE {$table_installations} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			license_id BIGINT UNSIGNED NOT NULL,
			site_url VARCHAR(500) NOT NULL,
			platform ENUM('wordpress','drupal','react') NOT NULL,
			plugin_version VARCHAR(20) NOT NULL DEFAULT '',
			php_version VARCHAR(20) DEFAULT NULL,
			cms_version VARCHAR(20) DEFAULT NULL,
			node_version VARCHAR(20) DEFAULT NULL,
			activated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			last_checked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			deactivated_at DATETIME DEFAULT NULL,
			is_active TINYINT(1) NOT NULL DEFAULT 1,
			is_local TINYINT(1) NOT NULL DEFAULT 0,
			PRIMARY KEY (id),
			UNIQUE KEY license_site (license_id, site_url),
			KEY license_id (license_id),
			KEY site_url (site_url(191)),
			KEY is_active (is_active),
			KEY platform (platform),
			KEY last_checked_at (last_checked_at)
		) {$charset_collate};";

		dbDelta( $sql_installations );

		// Geo data table.
		$table_geo = $wpdb->prefix . 'plm_geo_data';
		$sql_geo   = "CREATE TABLE {$table_geo} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			installation_id BIGINT UNSIGNED NOT NULL,
			ip_anonymized VARCHAR(45) DEFAULT NULL,
			country_code CHAR(2) NOT NULL DEFAULT 'XX',
			country_name VARCHAR(100) NOT NULL DEFAULT 'Unknown',
			region VARCHAR(100) DEFAULT NULL,
			city VARCHAR(100) DEFAULT NULL,
			latitude DECIMAL(10,7) DEFAULT NULL,
			longitude DECIMAL(10,7) DEFAULT NULL,
			continent VARCHAR(50) DEFAULT NULL,
			timezone VARCHAR(50) DEFAULT NULL,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY installation_id (installation_id),
			KEY country_code (country_code)
		) {$charset_collate};";

		dbDelta( $sql_geo );

		// Audit logs table.
		$table_audit = $wpdb->prefix . 'plm_audit_logs';
		$sql_audit   = "CREATE TABLE {$table_audit} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			license_id BIGINT UNSIGNED DEFAULT NULL,
			event_type VARCHAR(50) NOT NULL,
			details LONGTEXT DEFAULT NULL,
			ip_address VARCHAR(45) DEFAULT NULL,
			user_agent VARCHAR(500) DEFAULT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY license_id (license_id),
			KEY event_type (event_type),
			KEY created_at (created_at)
		) {$charset_collate};";

		dbDelta( $sql_audit );

		// Stripe events table.
		$table_stripe = $wpdb->prefix . 'plm_stripe_events';
		$sql_stripe   = "CREATE TABLE {$table_stripe} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			stripe_event_id VARCHAR(100) NOT NULL,
			event_type VARCHAR(100) NOT NULL,
			license_id BIGINT UNSIGNED DEFAULT NULL,
			payload LONGTEXT DEFAULT NULL,
			processed TINYINT(1) NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY stripe_event_id (stripe_event_id),
			KEY event_type (event_type)
		) {$charset_collate};";

		dbDelta( $sql_stripe );

		// Stripe product mapping table.
		$table_product_map = $wpdb->prefix . 'plm_stripe_product_map';
		$sql_product_map   = "CREATE TABLE {$table_product_map} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			stripe_product_id VARCHAR(100) NOT NULL,
			stripe_price_id VARCHAR(100) DEFAULT NULL,
			license_type ENUM('premium','pro_plus') NOT NULL DEFAULT 'premium',
			plan ENUM('starter','professional','agency','enterprise') NOT NULL DEFAULT 'starter',
			site_limit INT UNSIGNED NOT NULL DEFAULT 1,
			duration_days INT UNSIGNED NOT NULL DEFAULT 365,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) {$charset_collate};";

		dbDelta( $sql_product_map );

		update_option( 'plm_db_version', PLM_VERSION );
	}

	/**
	 * Get table name with prefix.
	 */
	public static function table( string $name ): string {
		global $wpdb;
		return $wpdb->prefix . 'plm_' . $name;
	}
}
