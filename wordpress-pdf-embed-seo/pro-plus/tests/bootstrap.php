<?php
/**
 * PHPUnit bootstrap file for Pro-plus tests.
 *
 * @package PDF_Embed_SEO_Pro_Plus
 * @subpackage Tests
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define test mode.
define( 'PDF_EMBED_SEO_TESTING', true );
define( 'PDF_EMBED_SEO_PRO_PLUS_TESTING', true );

// Load WordPress test environment.
$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$_phpunit_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $_phpunit_polyfills_path ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path );
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 *
 * Paths resolved from this file: <repo>/wordpress-pdf-embed-seo/pro-plus/tests/bootstrap.php
 *   dirname( __DIR__ )                 = pro-plus/
 *   dirname( dirname( __DIR__ ) )      = wordpress-pdf-embed-seo/
 */
function _manually_load_plugin() {
	$wp_plugin_dir = dirname( dirname( __DIR__ ) );

	require $wp_plugin_dir . '/pdf-embed-seo-optimize.php';

	if ( file_exists( $wp_plugin_dir . '/premium/class-pdf-embed-seo-premium.php' ) ) {
		require $wp_plugin_dir . '/premium/class-pdf-embed-seo-premium.php';
	}

	require dirname( __DIR__ ) . '/class-pdf-embed-seo-pro-plus.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require "{$_tests_dir}/includes/bootstrap.php";

// Include test case base class.
require_once __DIR__ . '/class-pro-plus-test-case.php';
