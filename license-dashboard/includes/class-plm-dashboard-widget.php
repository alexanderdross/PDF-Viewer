<?php
/**
 * WordPress Dashboard widget with quick links to License Manager pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Dashboard_Widget {

	/**
	 * Register the dashboard widget.
	 */
	public static function init(): void {
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'register_widget' ) );
	}

	/**
	 * Register the widget with WordPress.
	 */
	public static function register_widget(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			'plm_quick_links',
			__( 'PDF License Manager', 'pdf-license-manager' ),
			array( __CLASS__, 'render_widget' )
		);
	}

	/**
	 * Render the widget content.
	 */
	public static function render_widget(): void {
		global $wpdb;

		$t_lic  = PLM_Database::table( 'licenses' );
		$t_inst = PLM_Database::table( 'installations' );

		$active   = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic} WHERE status = 'active'" );
		$expired  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic} WHERE status = 'expired'" );
		$total    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_lic}" );
		$installs = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t_inst} WHERE is_active = 1" );
		$expiring = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$t_lic} WHERE status = 'active' AND expires_at IS NOT NULL AND expires_at BETWEEN %s AND %s",
			current_time( 'mysql', true ),
			gmdate( 'Y-m-d H:i:s', time() + 30 * DAY_IN_SECONDS )
		) );

		?>
		<style>
			.plm-widget-stats {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 8px;
				margin-bottom: 16px;
			}
			.plm-widget-stat {
				background: #f6f7f7;
				border-radius: 4px;
				padding: 10px 12px;
				text-align: center;
			}
			.plm-widget-stat-value {
				display: block;
				font-size: 22px;
				font-weight: 600;
				color: #1d2327;
				line-height: 1.2;
			}
			.plm-widget-stat-label {
				display: block;
				font-size: 11px;
				color: #646970;
				margin-top: 2px;
			}
			.plm-widget-stat-value.plm-w-success { color: #059669; }
			.plm-widget-stat-value.plm-w-danger { color: #dc2626; }
			.plm-widget-stat-value.plm-w-warning { color: #d97706; }
			.plm-widget-links {
				list-style: none;
				margin: 0;
				padding: 0;
			}
			.plm-widget-links li {
				margin: 0;
				padding: 0;
				border-bottom: 1px solid #f0f0f1;
			}
			.plm-widget-links li:last-child {
				border-bottom: none;
			}
			.plm-widget-links a {
				display: flex;
				align-items: center;
				gap: 8px;
				padding: 8px 4px;
				text-decoration: none;
				color: #2271b1;
				font-size: 13px;
				transition: background 0.15s;
			}
			.plm-widget-links a:hover {
				background: #f6f7f7;
				color: #135e96;
			}
			.plm-widget-links .dashicons {
				font-size: 16px;
				width: 16px;
				height: 16px;
				color: #646970;
			}
			.plm-widget-links a:hover .dashicons {
				color: #2271b1;
			}
			.plm-widget-links .plm-link-count {
				margin-left: auto;
				background: #f0f0f1;
				color: #50575e;
				font-size: 11px;
				padding: 1px 8px;
				border-radius: 10px;
				font-weight: 500;
			}
		</style>

		<div class="plm-widget-stats">
			<div class="plm-widget-stat">
				<span class="plm-widget-stat-value plm-w-success"><?php echo esc_html( $active ); ?></span>
				<span class="plm-widget-stat-label"><?php esc_html_e( 'Active', 'pdf-license-manager' ); ?></span>
			</div>
			<div class="plm-widget-stat">
				<span class="plm-widget-stat-value"><?php echo esc_html( $installs ); ?></span>
				<span class="plm-widget-stat-label"><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></span>
			</div>
			<div class="plm-widget-stat">
				<span class="plm-widget-stat-value plm-w-danger"><?php echo esc_html( $expired ); ?></span>
				<span class="plm-widget-stat-label"><?php esc_html_e( 'Expired', 'pdf-license-manager' ); ?></span>
			</div>
			<div class="plm-widget-stat">
				<span class="plm-widget-stat-value <?php echo $expiring > 0 ? 'plm-w-warning' : ''; ?>"><?php echo esc_html( $expiring ); ?></span>
				<span class="plm-widget-stat-label"><?php esc_html_e( 'Expiring (30d)', 'pdf-license-manager' ); ?></span>
			</div>
		</div>

		<ul class="plm-widget-links">
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-dashboard' ) ); ?>">
					<span class="dashicons dashicons-dashboard"></span>
					<?php esc_html_e( 'Dashboard', 'pdf-license-manager' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses' ) ); ?>">
					<span class="dashicons dashicons-admin-network"></span>
					<?php esc_html_e( 'All Licenses', 'pdf-license-manager' ); ?>
					<span class="plm-link-count"><?php echo esc_html( $total ); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&action=new' ) ); ?>">
					<span class="dashicons dashicons-plus-alt"></span>
					<?php esc_html_e( 'Create New License', 'pdf-license-manager' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&status=active' ) ); ?>">
					<span class="dashicons dashicons-yes-alt"></span>
					<?php esc_html_e( 'Active Licenses', 'pdf-license-manager' ); ?>
					<span class="plm-link-count"><?php echo esc_html( $active ); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&status=expired' ) ); ?>">
					<span class="dashicons dashicons-warning"></span>
					<?php esc_html_e( 'Expired Licenses', 'pdf-license-manager' ); ?>
					<?php if ( $expired > 0 ) : ?>
					<span class="plm-link-count"><?php echo esc_html( $expired ); ?></span>
					<?php endif; ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-installations' ) ); ?>">
					<span class="dashicons dashicons-admin-site-alt3"></span>
					<?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?>
					<span class="plm-link-count"><?php echo esc_html( $installs ); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-stats' ) ); ?>">
					<span class="dashicons dashicons-chart-bar"></span>
					<?php esc_html_e( 'Statistics', 'pdf-license-manager' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-audit-log' ) ); ?>">
					<span class="dashicons dashicons-list-view"></span>
					<?php esc_html_e( 'Audit Log', 'pdf-license-manager' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-settings' ) ); ?>">
					<span class="dashicons dashicons-admin-generic"></span>
					<?php esc_html_e( 'Settings', 'pdf-license-manager' ); ?>
				</a>
			</li>
		</ul>
		<?php
	}
}
