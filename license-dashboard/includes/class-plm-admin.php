<?php
/**
 * WordPress Admin dashboard pages for license management.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Admin {

	/**
	 * Initialize admin hooks.
	 */
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_plm_create_license', array( $this, 'handle_create_license' ) );
		add_action( 'admin_post_plm_revoke_license', array( $this, 'handle_revoke_license' ) );
		add_action( 'admin_post_plm_extend_license', array( $this, 'handle_extend_license' ) );
	}

	/**
	 * Register admin menu pages.
	 */
	public function add_menu_pages(): void {
		add_menu_page(
			__( 'License Dashboard', 'pdf-license-manager' ),
			__( 'Licenses', 'pdf-license-manager' ),
			'manage_options',
			'plm-dashboard',
			array( $this, 'render_dashboard' ),
			'dashicons-admin-network',
			3
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Dashboard', 'pdf-license-manager' ),
			__( 'Dashboard', 'pdf-license-manager' ),
			'manage_options',
			'plm-dashboard',
			array( $this, 'render_dashboard' )
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Licenses', 'pdf-license-manager' ),
			__( 'Licenses', 'pdf-license-manager' ),
			'manage_options',
			'plm-licenses',
			array( $this, 'render_licenses' )
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Installations', 'pdf-license-manager' ),
			__( 'Installations', 'pdf-license-manager' ),
			'manage_options',
			'plm-installations',
			array( $this, 'render_installations' )
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Statistics', 'pdf-license-manager' ),
			__( 'Statistics', 'pdf-license-manager' ),
			'manage_options',
			'plm-stats',
			array( $this, 'render_stats' )
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Audit Log', 'pdf-license-manager' ),
			__( 'Audit Log', 'pdf-license-manager' ),
			'manage_options',
			'plm-audit-log',
			array( $this, 'render_audit_log' )
		);

		add_submenu_page(
			'plm-dashboard',
			__( 'Settings', 'pdf-license-manager' ),
			__( 'Settings', 'pdf-license-manager' ),
			'manage_options',
			'plm-settings',
			array( $this, 'render_settings' )
		);
	}

	/**
	 * Enqueue admin CSS/JS.
	 */
	public function enqueue_assets( string $hook ): void {
		if ( strpos( $hook, 'plm-' ) === false && strpos( $hook, 'plm_' ) === false ) {
			return;
		}
		wp_enqueue_style( 'plm-admin', PLM_PLUGIN_URL . 'admin/css/admin.css', array(), PLM_VERSION );
		wp_enqueue_script( 'plm-admin', PLM_PLUGIN_URL . 'admin/js/admin.js', array(), PLM_VERSION, true );
	}

	/**
	 * Get common stats for the dashboard.
	 */
	private function get_stats(): array {
		global $wpdb;

		$t_lic  = PLM_Database::table( 'licenses' );
		$t_inst = PLM_Database::table( 'installations' );

		return array(
			'active_licenses'      => (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic} WHERE status = 'active'" ),
			'active_installations' => (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_inst} WHERE is_active = 1" ),
			'expiring_30d'         => (int) $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM {$t_lic} WHERE status = 'active' AND expires_at IS NOT NULL AND expires_at BETWEEN %s AND %s",
				current_time( 'mysql', true ),
				gmdate( 'Y-m-d H:i:s', time() + 30 * DAY_IN_SECONDS )
			) ),
			'total_licenses'       => (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic}" ),
			'expired_licenses'     => (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic} WHERE status = 'expired'" ),
			'revoked_licenses'     => (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic} WHERE status = 'revoked'" ),
		);
	}

	// -------------------------------------------------------------------------
	// Page Renderers
	// -------------------------------------------------------------------------

	public function render_dashboard(): void {
		$stats = $this->get_stats();
		include PLM_PLUGIN_DIR . 'admin/views/dashboard.php';
	}

	public function render_licenses(): void {
		global $wpdb;

		// Handle single license view.
		$license_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		if ( $license_id ) {
			$this->render_license_detail( $license_id );
			return;
		}

		// Filter parameters.
		$status_filter   = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
		$type_filter     = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : '';
		$search          = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		$table    = PLM_Database::table( 'licenses' );
		$where    = array( '1=1' );
		$values   = array();

		if ( $status_filter ) {
			$where[]  = 'status = %s';
			$values[] = $status_filter;
		}
		if ( $type_filter ) {
			$where[]  = 'license_type = %s';
			$values[] = $type_filter;
		}
		if ( $search ) {
			$like     = '%' . $wpdb->esc_like( $search ) . '%';
			$where[]  = '(license_key LIKE %s OR customer_email LIKE %s)';
			$values[] = $like;
			$values[] = $like;
		}

		$where_sql = implode( ' AND ', $where );
		$query     = "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY created_at DESC LIMIT 100";

		if ( ! empty( $values ) ) {
			$query = $wpdb->prepare( $query, $values );
		}

		$licenses = $wpdb->get_results( $query );

		include PLM_PLUGIN_DIR . 'admin/views/licenses.php';
	}

	private function render_license_detail( int $license_id ): void {
		global $wpdb;

		$t_lic  = PLM_Database::table( 'licenses' );
		$t_inst = PLM_Database::table( 'installations' );
		$t_geo  = PLM_Database::table( 'geo_data' );
		$t_log  = PLM_Database::table( 'audit_logs' );

		$license = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t_lic} WHERE id = %d", $license_id ) );
		if ( ! $license ) {
			wp_die( esc_html__( 'License not found.', 'pdf-license-manager' ) );
		}

		$installations = $wpdb->get_results( $wpdb->prepare(
			"SELECT i.*, g.country_code, g.country_name, g.region, g.city
			 FROM {$t_inst} i LEFT JOIN {$t_geo} g ON g.installation_id = i.id
			 WHERE i.license_id = %d ORDER BY i.activated_at DESC",
			$license_id
		) );

		$audit_logs = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$t_log} WHERE license_id = %d ORDER BY created_at DESC LIMIT 30",
			$license_id
		) );

		include PLM_PLUGIN_DIR . 'admin/views/license-detail.php';
	}

	public function render_installations(): void {
		global $wpdb;

		$t_inst = PLM_Database::table( 'installations' );
		$t_lic  = PLM_Database::table( 'licenses' );
		$t_geo  = PLM_Database::table( 'geo_data' );

		$platform_filter = isset( $_GET['platform'] ) ? sanitize_text_field( wp_unslash( $_GET['platform'] ) ) : '';

		$where  = array( 'i.is_active = 1' );
		$values = array();

		if ( $platform_filter ) {
			$where[]  = 'i.platform = %s';
			$values[] = $platform_filter;
		}

		$where_sql = implode( ' AND ', $where );
		$query     = "SELECT i.*, l.license_key, l.license_type, l.plan, l.status AS license_status, l.expires_at AS license_expires_at,
					  g.country_code, g.country_name, g.region, g.city
					  FROM {$t_inst} i
					  JOIN {$t_lic} l ON l.id = i.license_id
					  LEFT JOIN {$t_geo} g ON g.installation_id = i.id
					  WHERE {$where_sql}
					  ORDER BY i.last_checked_at DESC LIMIT 200";

		if ( ! empty( $values ) ) {
			$query = $wpdb->prepare( $query, $values );
		}

		$installations = $wpdb->get_results( $query );

		include PLM_PLUGIN_DIR . 'admin/views/installations.php';
	}

	public function render_stats(): void {
		global $wpdb;

		$t_inst = PLM_Database::table( 'installations' );
		$t_geo  = PLM_Database::table( 'geo_data' );
		$t_lic  = PLM_Database::table( 'licenses' );

		$stats = $this->get_stats();

		$geo_distribution = $wpdb->get_results(
			"SELECT g.country_code, g.country_name, COUNT(*) AS count
			 FROM {$t_geo} g JOIN {$t_inst} i ON i.id = g.installation_id
			 WHERE i.is_active = 1
			 GROUP BY g.country_code, g.country_name
			 ORDER BY count DESC LIMIT 20"
		);

		$platform_distribution = $wpdb->get_results(
			"SELECT platform, COUNT(*) AS count FROM {$t_inst} WHERE is_active = 1 GROUP BY platform ORDER BY count DESC"
		);

		$version_distribution = $wpdb->get_results(
			"SELECT plugin_version, COUNT(*) AS count FROM {$t_inst} WHERE is_active = 1 GROUP BY plugin_version ORDER BY count DESC"
		);

		$type_distribution = $wpdb->get_results(
			"SELECT license_type, COUNT(*) AS count FROM {$t_lic} GROUP BY license_type"
		);

		$plan_distribution = $wpdb->get_results(
			"SELECT plan, COUNT(*) AS count FROM {$t_lic} WHERE status = 'active' GROUP BY plan ORDER BY count DESC"
		);

		include PLM_PLUGIN_DIR . 'admin/views/stats.php';
	}

	public function render_audit_log(): void {
		global $wpdb;

		$t_log = PLM_Database::table( 'audit_logs' );
		$t_lic = PLM_Database::table( 'licenses' );

		$logs = $wpdb->get_results(
			"SELECT a.*, l.license_key, l.license_type
			 FROM {$t_log} a LEFT JOIN {$t_lic} l ON l.id = a.license_id
			 ORDER BY a.created_at DESC LIMIT 200"
		);

		include PLM_PLUGIN_DIR . 'admin/views/audit-log.php';
	}

	public function render_settings(): void {
		global $wpdb;

		$t_map    = PLM_Database::table( 'stripe_product_map' );
		$mappings = $wpdb->get_results( "SELECT * FROM {$t_map} ORDER BY created_at DESC" );

		include PLM_PLUGIN_DIR . 'admin/views/settings.php';
	}

	// -------------------------------------------------------------------------
	// Form Handlers
	// -------------------------------------------------------------------------

	public function handle_create_license(): void {
		check_admin_referer( 'plm_create_license' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'pdf-license-manager' ) );
		}

		global $wpdb;

		$type      = sanitize_text_field( wp_unslash( $_POST['license_type'] ?? 'premium' ) );
		$plan      = sanitize_text_field( wp_unslash( $_POST['plan'] ?? 'starter' ) );
		$email     = sanitize_email( wp_unslash( $_POST['customer_email'] ?? '' ) );
		$name      = sanitize_text_field( wp_unslash( $_POST['customer_name'] ?? '' ) );
		$site_limit = absint( $_POST['site_limit'] ?? 1 );
		$duration   = sanitize_text_field( wp_unslash( $_POST['duration'] ?? '365' ) );
		$notes     = sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) );

		$key_type    = 'pro_plus' === $type ? 'pro_plus' : 'premium';
		$license_key = PLM_License::generate_key( $key_type );

		$expires_at = null;
		if ( 'lifetime' !== $duration && is_numeric( $duration ) ) {
			$expires_at = gmdate( 'Y-m-d H:i:s', time() + ( absint( $duration ) * DAY_IN_SECONDS ) );
		}

		$table = PLM_Database::table( 'licenses' );
		$wpdb->insert(
			$table,
			array(
				'license_key'    => $license_key,
				'license_type'   => $type,
				'plan'           => $plan,
				'status'         => 'inactive',
				'site_limit'     => $site_limit,
				'customer_email' => $email,
				'customer_name'  => $name ?: null,
				'expires_at'     => $expires_at,
				'notes'          => $notes ?: null,
			),
			array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s' )
		);

		$license_id = $wpdb->insert_id;

		PLM_License::audit_log( $license_id, 'license.created', array(
			'source' => 'manual',
			'plan'   => $plan,
			'type'   => $type,
		) );

		wp_safe_redirect( admin_url( 'admin.php?page=plm-licenses&id=' . $license_id . '&created=1' ) );
		exit;
	}

	public function handle_revoke_license(): void {
		check_admin_referer( 'plm_revoke_license' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'pdf-license-manager' ) );
		}

		global $wpdb;

		$license_id = absint( $_POST['license_id'] ?? 0 );
		if ( ! $license_id ) {
			wp_die( esc_html__( 'Invalid license ID.', 'pdf-license-manager' ) );
		}

		$wpdb->update(
			PLM_Database::table( 'licenses' ),
			array( 'status' => 'revoked' ),
			array( 'id' => $license_id ),
			array( '%s' ),
			array( '%d' )
		);

		PLM_License::audit_log( $license_id, 'license.revoked', array( 'source' => 'admin' ) );

		wp_safe_redirect( admin_url( 'admin.php?page=plm-licenses&id=' . $license_id . '&revoked=1' ) );
		exit;
	}

	public function handle_extend_license(): void {
		check_admin_referer( 'plm_extend_license' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'pdf-license-manager' ) );
		}

		global $wpdb;

		$license_id = absint( $_POST['license_id'] ?? 0 );
		$days       = absint( $_POST['extend_days'] ?? 365 );

		if ( ! $license_id ) {
			wp_die( esc_html__( 'Invalid license ID.', 'pdf-license-manager' ) );
		}

		$table   = PLM_Database::table( 'licenses' );
		$license = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $license_id ) );

		if ( ! $license ) {
			wp_die( esc_html__( 'License not found.', 'pdf-license-manager' ) );
		}

		$current = $license->expires_at ? strtotime( $license->expires_at ) : time();
		$base    = max( $current, time() );
		$new_exp = gmdate( 'Y-m-d H:i:s', $base + ( $days * DAY_IN_SECONDS ) );

		$wpdb->update(
			$table,
			array( 'expires_at' => $new_exp, 'status' => 'active' ),
			array( 'id' => $license_id ),
			array( '%s', '%s' ),
			array( '%d' )
		);

		PLM_License::audit_log( $license_id, 'license.extended', array(
			'days'       => $days,
			'new_expiry' => $new_exp,
			'source'     => 'admin',
		) );

		wp_safe_redirect( admin_url( 'admin.php?page=plm-licenses&id=' . $license_id . '&extended=1' ) );
		exit;
	}
}
