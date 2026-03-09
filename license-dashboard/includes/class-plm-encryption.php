<?php
/**
 * AES-256-CBC encryption for sensitive settings.
 *
 * Uses WordPress AUTH_KEY + SECURE_AUTH_KEY as the encryption key source.
 * Falls back to storing values in plaintext if OpenSSL is not available.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_Encryption {

	private const CIPHER = 'aes-256-cbc';

	/**
	 * Get the encryption key derived from WordPress salts.
	 */
	private static function get_key(): string {
		$key_material = '';
		if ( defined( 'AUTH_KEY' ) ) {
			$key_material .= AUTH_KEY;
		}
		if ( defined( 'SECURE_AUTH_KEY' ) ) {
			$key_material .= SECURE_AUTH_KEY;
		}

		if ( empty( $key_material ) ) {
			$key_material = 'plm-default-key-change-your-salts';
		}

		return hash( 'sha256', $key_material, true );
	}

	/**
	 * Check if encryption is available.
	 */
	public static function is_available(): bool {
		return function_exists( 'openssl_encrypt' ) && function_exists( 'openssl_decrypt' );
	}

	/**
	 * Encrypt a value.
	 *
	 * @param string $plaintext The value to encrypt.
	 * @return string Base64-encoded ciphertext with IV prepended, or original value if encryption unavailable.
	 */
	public static function encrypt( string $plaintext ): string {
		if ( ! self::is_available() ) {
			return $plaintext;
		}

		$key     = self::get_key();
		$iv_len  = openssl_cipher_iv_length( self::CIPHER );
		$iv      = openssl_random_pseudo_bytes( $iv_len );
		$encrypted = openssl_encrypt( $plaintext, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv );

		if ( false === $encrypted ) {
			return $plaintext;
		}

		return 'enc:' . base64_encode( $iv . $encrypted );
	}

	/**
	 * Decrypt a value.
	 *
	 * @param string $ciphertext The encrypted value.
	 * @return string The decrypted value, or original value if not encrypted/decryption fails.
	 */
	public static function decrypt( string $ciphertext ): string {
		if ( ! self::is_available() ) {
			return $ciphertext;
		}

		// Not encrypted (no prefix).
		if ( ! str_starts_with( $ciphertext, 'enc:' ) ) {
			return $ciphertext;
		}

		$raw = base64_decode( substr( $ciphertext, 4 ), true );
		if ( false === $raw ) {
			return $ciphertext;
		}

		$key    = self::get_key();
		$iv_len = openssl_cipher_iv_length( self::CIPHER );

		if ( strlen( $raw ) <= $iv_len ) {
			return $ciphertext;
		}

		$iv         = substr( $raw, 0, $iv_len );
		$encrypted  = substr( $raw, $iv_len );
		$decrypted  = openssl_decrypt( $encrypted, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv );

		if ( false === $decrypted ) {
			return $ciphertext;
		}

		return $decrypted;
	}

	/**
	 * Get an encrypted option.
	 */
	public static function get_option( string $option_name, string $default = '' ): string {
		$value = get_option( $option_name, $default );
		if ( empty( $value ) || $value === $default ) {
			return $default;
		}
		return self::decrypt( $value );
	}

	/**
	 * Save an encrypted option.
	 */
	public static function update_option( string $option_name, string $value ): bool {
		$encrypted = self::encrypt( $value );
		return update_option( $option_name, $encrypted );
	}
}
