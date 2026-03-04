<?php
/**
 * Plugin Name: PDF License Manager
 * Plugin URI: https://pdfviewer.drossmedia.de
 * Description: Central license management dashboard for PDF Embed & SEO Optimize (WordPress, Drupal, React/Next.js).
 * Version: 1.1.0
 * Author: Dross:Media
 * Author URI: https://dross.net/media/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pdf-license-manager
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLM_VERSION', '1.1.0' );
define( 'PLM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PLM_PLUGIN_FILE', __FILE__ );

// Grace period in days after license expiration.
if ( ! defined( 'PLM_GRACE_PERIOD_DAYS' ) ) {
	define( 'PLM_GRACE_PERIOD_DAYS', 14 );
}

// Latest plugin version (for update checks).
define( 'PLM_LATEST_PLUGIN_VERSION', '1.3.0' );

// Stale installation threshold (days without heartbeat before marking inactive).
if ( ! defined( 'PLM_STALE_INSTALLATION_DAYS' ) ) {
	define( 'PLM_STALE_INSTALLATION_DAYS', 7 );
}

// Data retention period in months for audit logs and stripe events.
if ( ! defined( 'PLM_DATA_RETENTION_MONTHS' ) ) {
	define( 'PLM_DATA_RETENTION_MONTHS', 24 );
}

// Rate limit defaults (can be overridden in wp-config.php).
if ( ! defined( 'PLM_RATE_LIMIT_API_PER_MINUTE' ) ) {
	define( 'PLM_RATE_LIMIT_API_PER_MINUTE', 60 );
}
if ( ! defined( 'PLM_RATE_LIMIT_ACTIVATE_PER_HOUR' ) ) {
	define( 'PLM_RATE_LIMIT_ACTIVATE_PER_HOUR', 10 );
}
if ( ! defined( 'PLM_RATE_LIMIT_CHECK_PER_DAY' ) ) {
	define( 'PLM_RATE_LIMIT_CHECK_PER_DAY', 1000 );
}

// Dev/local domain patterns (comma-separated, supports wildcards).
if ( ! defined( 'PLM_DEV_DOMAINS' ) ) {
	define( 'PLM_DEV_DOMAINS', 'localhost,*.local,*.test,*.dev' );
}

/**
 * Main plugin class.
 */
final class PDF_License_Manager {

	private static ?self $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->load_dependencies();
		$this->init_hooks();
	}

	private function load_dependencies(): void {
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-database.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-license.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-encryption.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-email.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-api.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-geoip.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-stripe.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-cron.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-admin.php';
		require_once PLM_PLUGIN_DIR . 'includes/class-plm-dashboard-widget.php';
	}

	private function init_hooks(): void {
		register_activation_hook( PLM_PLUGIN_FILE, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( PLM_PLUGIN_FILE, array( __CLASS__, 'deactivate' ) );

		add_action( 'rest_api_init', array( 'PLM_API', 'register_routes' ) );
		add_action( 'rest_api_init', array( 'PLM_Stripe', 'register_routes' ) );

		// Initialize cron jobs.
		PLM_Cron::init();

		if ( is_admin() ) {
			$admin = new PLM_Admin();
			$admin->init();
			PLM_Dashboard_Widget::init();
		}
	}

	/**
	 * Plugin activation: create tables and schedule crons.
	 */
	public static function activate(): void {
		PLM_Database::create_tables();
	}

	/**
	 * Plugin deactivation: unschedule crons.
	 */
	public static function deactivate(): void {
		PLM_Cron::unschedule_all();
	}
}

// Initialize.
PDF_License_Manager::instance();
