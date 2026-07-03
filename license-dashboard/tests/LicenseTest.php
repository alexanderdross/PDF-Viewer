<?php
/**
 * Unit tests for PLM_License (key generation, format detection, status lifecycle,
 * local-domain detection, IP anonymization, masking).
 */

use PHPUnit\Framework\TestCase;

class LicenseTest extends TestCase {

	public function test_generate_key_matches_format_for_each_type(): void {
		$expectations = array(
			'premium'   => '/^PDF\$PRO#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/',
			'pro_plus'  => '/^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/',
			'unlimited' => '/^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/',
			'dev'       => '/^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/',
		);
		foreach ( $expectations as $type => $pattern ) {
			$key = PLM_License::generate_key( $type );
			$this->assertMatchesRegularExpression( $pattern, $key, "Key for {$type} must match its pattern." );
			$this->assertSame( $type, PLM_License::detect_type( $key ), "Generated {$type} key must detect as {$type}." );
		}
	}

	public function test_generate_key_defaults_to_premium(): void {
		$this->assertSame( 'premium', PLM_License::detect_type( PLM_License::generate_key( 'garbage' ) ) );
	}

	public function test_generate_key_round_trip_is_stable(): void {
		foreach ( array( 'premium', 'pro_plus', 'unlimited', 'dev' ) as $type ) {
			for ( $i = 0; $i < 25; $i++ ) {
				$this->assertSame( $type, PLM_License::detect_type( PLM_License::generate_key( $type ) ) );
			}
		}
	}

	public function test_detect_type_returns_null_for_invalid(): void {
		$this->assertNull( PLM_License::detect_type( 'not-a-key' ) );
		$this->assertNull( PLM_License::detect_type( '' ) );
		$this->assertNull( PLM_License::detect_type( 'PDF$PRO#TOO-SHORT' ) );
		$this->assertNull( PLM_License::detect_type( 'PDF$PRO#a1b2-c3d4@e5f6-g7h8!i9j0' ) ); // lowercase not allowed
	}

	public function test_pro_plus_not_misdetected_as_premium(): void {
		// pro_plus keys contain "PRO" as a substring; detection order must catch pro_plus first.
		$key = PLM_License::generate_key( 'pro_plus' );
		$this->assertSame( 'pro_plus', PLM_License::detect_type( $key ) );
	}

	public function test_is_valid_format(): void {
		$this->assertTrue( PLM_License::is_valid_format( PLM_License::generate_key( 'unlimited' ) ) );
		$this->assertFalse( PLM_License::is_valid_format( 'INVALID' ) );
	}

	public function test_is_local_domain(): void {
		$local = array(
			'http://localhost',
			'http://localhost:3000',
			'http://127.0.0.1:8080',
			'https://mysite.local',
			'https://foo.test',
			'https://bar.dev',
			'https://staging.example.com',
			'http://10.1.2.3',
			'http://192.168.1.50',
		);
		foreach ( $local as $url ) {
			$this->assertTrue( PLM_License::is_local_domain( $url ), "{$url} should be local." );
		}

		$public = array( 'https://example.com', 'https://www.drossmedia.de', 'https://sub.example.org' );
		foreach ( $public as $url ) {
			$this->assertFalse( PLM_License::is_local_domain( $url ), "{$url} should be public." );
		}
	}

	public function test_normalize_url_lowercases_host(): void {
		$this->assertSame( 'example.com', PLM_License::normalize_url( 'https://Example.com/path/?q=1' ) );
	}

	public function test_days_remaining(): void {
		$this->assertNull( PLM_License::days_remaining( null ), 'Lifetime returns null.' );

		$future = gmdate( 'Y-m-d H:i:s', time() + 10 * DAY_IN_SECONDS );
		$this->assertGreaterThanOrEqual( 9, PLM_License::days_remaining( $future ) );
		$this->assertLessThanOrEqual( 10, PLM_License::days_remaining( $future ) );

		$past = gmdate( 'Y-m-d H:i:s', time() - 5 * DAY_IN_SECONDS );
		$this->assertLessThan( 0, PLM_License::days_remaining( $past ) );
	}

	public function test_compute_status_lifetime_is_active(): void {
		$this->assertSame( 'active', PLM_License::compute_status( null, 'active' ) );
	}

	public function test_compute_status_dated_active(): void {
		$future = gmdate( 'Y-m-d H:i:s', time() + 30 * DAY_IN_SECONDS );
		$this->assertSame( 'active', PLM_License::compute_status( $future, 'active' ) );
	}

	public function test_compute_status_grace_period(): void {
		$recently_expired = gmdate( 'Y-m-d H:i:s', time() - 3 * DAY_IN_SECONDS );
		$this->assertSame( 'grace_period', PLM_License::compute_status( $recently_expired, 'active' ) );
	}

	public function test_compute_status_expired_past_grace(): void {
		$long_expired = gmdate( 'Y-m-d H:i:s', time() - ( PLM_GRACE_PERIOD_DAYS + 5 ) * DAY_IN_SECONDS );
		$this->assertSame( 'expired', PLM_License::compute_status( $long_expired, 'active' ) );
	}

	public function test_compute_status_preserves_terminal_states(): void {
		// revoked and inactive are sticky regardless of expiry.
		$this->assertSame( 'revoked', PLM_License::compute_status( null, 'revoked' ) );
		$this->assertSame( 'inactive', PLM_License::compute_status( null, 'inactive' ) );
		$future = gmdate( 'Y-m-d H:i:s', time() + DAY_IN_SECONDS );
		$this->assertSame( 'revoked', PLM_License::compute_status( $future, 'revoked' ) );
	}

	public function test_mask_key(): void {
		$key    = 'PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0';
		$masked = PLM_License::mask_key( $key );
		$this->assertStringStartsWith( 'PDF$PRO#', $masked );
		$this->assertStringEndsWith( 'I9J0', $masked );
		$this->assertStringNotContainsString( 'A1B2-C3D4', $masked );
	}

	public function test_anonymize_ipv4_zeros_last_octet(): void {
		$this->assertSame( '192.168.1.0', PLM_License::anonymize_ip( '192.168.1.123' ) );
		$this->assertSame( '8.8.8.0', PLM_License::anonymize_ip( '8.8.8.8' ) );
	}

	public function test_anonymize_ipv6_truncates(): void {
		$anon = PLM_License::anonymize_ip( '2001:0db8:85a3:0000:0000:8a2e:0370:7334' );
		$this->assertStringEndsWith( '::', $anon );
		$this->assertStringStartsWith( '2001:', $anon );
	}
}
