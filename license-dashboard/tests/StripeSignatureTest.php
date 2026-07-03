<?php
/**
 * Unit tests for PLM_Stripe::verify_signature() — the native (no-SDK) Stripe
 * webhook signature verification. The method is private, so it is exercised via
 * reflection, mirroring how Stripe signs webhook payloads.
 */

use PHPUnit\Framework\TestCase;

class StripeSignatureTest extends TestCase {

	private const SECRET = 'whsec_test_secret_key';

	/**
	 * Invoke the private static verify_signature() via reflection.
	 */
	private function verify( string $payload, string $sig_header, string $secret ): bool {
		$method = new ReflectionMethod( 'PLM_Stripe', 'verify_signature' );
		$method->setAccessible( true );
		return (bool) $method->invoke( null, $payload, $sig_header, $secret );
	}

	/**
	 * Build a valid Stripe signature header for a payload at a given timestamp.
	 */
	private function sign( string $payload, int $timestamp, string $secret ): string {
		$sig = hash_hmac( 'sha256', $timestamp . '.' . $payload, $secret );
		return 't=' . $timestamp . ',v1=' . $sig;
	}

	public function test_valid_signature_passes(): void {
		$payload = '{"id":"evt_123","type":"checkout.session.completed"}';
		$header  = $this->sign( $payload, time(), self::SECRET );
		$this->assertTrue( $this->verify( $payload, $header, self::SECRET ) );
	}

	public function test_tampered_payload_fails(): void {
		$payload = '{"id":"evt_123"}';
		$header  = $this->sign( $payload, time(), self::SECRET );
		$this->assertFalse( $this->verify( $payload . 'tampered', $header, self::SECRET ) );
	}

	public function test_wrong_secret_fails(): void {
		$payload = '{"id":"evt_123"}';
		$header  = $this->sign( $payload, time(), self::SECRET );
		$this->assertFalse( $this->verify( $payload, $header, 'whsec_wrong' ) );
	}

	public function test_old_timestamp_is_rejected(): void {
		$payload = '{"id":"evt_123"}';
		// 400 seconds old — beyond the 300s tolerance.
		$header  = $this->sign( $payload, time() - 400, self::SECRET );
		$this->assertFalse( $this->verify( $payload, $header, self::SECRET ) );
	}

	public function test_malformed_header_is_rejected(): void {
		$this->assertFalse( $this->verify( '{}', 'garbage', self::SECRET ) );
		$this->assertFalse( $this->verify( '{}', '', self::SECRET ) );
		$this->assertFalse( $this->verify( '{}', 't=' . time(), self::SECRET ) ); // no v1
	}

	public function test_multiple_signatures_one_valid_passes(): void {
		$payload = '{"id":"evt_123"}';
		$ts      = time();
		$valid   = hash_hmac( 'sha256', $ts . '.' . $payload, self::SECRET );
		$header  = 't=' . $ts . ',v1=deadbeef,v1=' . $valid;
		$this->assertTrue( $this->verify( $payload, $header, self::SECRET ) );
	}
}
