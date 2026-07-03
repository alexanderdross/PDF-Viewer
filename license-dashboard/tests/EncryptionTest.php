<?php
/**
 * Unit tests for PLM_Encryption (AES-256-CBC round-trips, passthrough behaviour,
 * and the encrypted-option helpers).
 */

use PHPUnit\Framework\TestCase;

class EncryptionTest extends TestCase {

	protected function setUp(): void {
		$GLOBALS['__plm_test_options'] = array();
	}

	public function test_is_available_returns_bool(): void {
		$this->assertIsBool( PLM_Encryption::is_available() );
	}

	public function test_encrypt_decrypt_round_trip(): void {
		if ( ! PLM_Encryption::is_available() ) {
			$this->markTestSkipped( 'OpenSSL not available.' );
		}
		$plaintext  = 'whsec_super_secret_value_1234567890';
		$ciphertext = PLM_Encryption::encrypt( $plaintext );

		$this->assertStringStartsWith( 'enc:', $ciphertext, 'Encrypted values are prefixed.' );
		$this->assertNotSame( $plaintext, $ciphertext, 'Ciphertext must differ from plaintext.' );
		$this->assertSame( $plaintext, PLM_Encryption::decrypt( $ciphertext ) );
	}

	public function test_encrypt_produces_distinct_ciphertexts(): void {
		if ( ! PLM_Encryption::is_available() ) {
			$this->markTestSkipped( 'OpenSSL not available.' );
		}
		// Random IV means two encryptions of the same value differ, but both decrypt back.
		$a = PLM_Encryption::encrypt( 'same-value' );
		$b = PLM_Encryption::encrypt( 'same-value' );
		$this->assertNotSame( $a, $b );
		$this->assertSame( 'same-value', PLM_Encryption::decrypt( $a ) );
		$this->assertSame( 'same-value', PLM_Encryption::decrypt( $b ) );
	}

	public function test_decrypt_passthrough_for_unencrypted(): void {
		$this->assertSame( 'plain-text', PLM_Encryption::decrypt( 'plain-text' ) );
	}

	public function test_option_round_trip(): void {
		PLM_Encryption::update_option( 'plm_test_secret', 'my-secret' );

		// Raw stored value must not equal the plaintext when encryption is available.
		if ( PLM_Encryption::is_available() ) {
			$this->assertNotSame( 'my-secret', get_option( 'plm_test_secret' ) );
		}
		$this->assertSame( 'my-secret', PLM_Encryption::get_option( 'plm_test_secret' ) );
	}

	public function test_get_option_returns_default_when_missing(): void {
		$this->assertSame( 'fallback', PLM_Encryption::get_option( 'plm_missing', 'fallback' ) );
	}
}
