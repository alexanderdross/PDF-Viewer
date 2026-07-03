<?php
/**
 * PHPUnit bootstrap for PDF License Manager.
 *
 * Defines a minimal set of WordPress stubs (only the constants/functions used by
 * the classes under test) so the pure-logic classes can be unit-tested without a
 * full WordPress installation. Integration behaviour that depends on $wpdb / the
 * REST stack is out of scope for these unit tests.
 */

error_reporting( E_ALL & ~E_DEPRECATED );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
if ( ! defined( 'PLM_GRACE_PERIOD_DAYS' ) ) {
	define( 'PLM_GRACE_PERIOD_DAYS', 14 );
}
if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 86400 );
}
if ( ! defined( 'PLM_VERSION' ) ) {
	define( 'PLM_VERSION', 'test' );
}
// Deterministic salts so encryption round-trips are reproducible in tests.
if ( ! defined( 'AUTH_KEY' ) ) {
	define( 'AUTH_KEY', 'plm-test-auth-key-abcdefghijklmnopqrstuvwxyz-0123456789' );
}
if ( ! defined( 'SECURE_AUTH_KEY' ) ) {
	define( 'SECURE_AUTH_KEY', 'plm-test-secure-auth-key-zyxwvutsrqponml-9876543210' );
}

// In-memory option store backing get_option()/update_option().
$GLOBALS['__plm_test_options'] = array();

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $name, $default = false ) {
		return $GLOBALS['__plm_test_options'][ $name ] ?? $default;
	}
}
if ( ! function_exists( 'update_option' ) ) {
	function update_option( $name, $value ) {
		$GLOBALS['__plm_test_options'][ $name ] = $value;
		return true;
	}
}
if ( ! function_exists( 'wp_parse_url' ) ) {
	function wp_parse_url( $url, $component = -1 ) {
		return parse_url( $url, $component );
	}
}
if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		if ( ! is_string( $str ) ) {
			return $str;
		}
		return trim( preg_replace( '/[\r\n\t ]+/', ' ', wp_strip_all_tags( $str ) ) );
	}
}
if ( ! function_exists( 'wp_strip_all_tags' ) ) {
	function wp_strip_all_tags( $string ) {
		return trim( strip_tags( (string) $string ) );
	}
}

// Load the classes under test (definition-only; no WP calls at include time).
$plm_inc = dirname( __DIR__ ) . '/includes/';
require_once $plm_inc . 'class-plm-license.php';
require_once $plm_inc . 'class-plm-encryption.php';
require_once $plm_inc . 'class-plm-stripe.php';
