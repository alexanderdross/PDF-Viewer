<?php
/**
 * MaxMind GeoLite2 integration for IP geolocation.
 *
 * Requires GeoLite2-City.mmdb to be placed in wp-content/uploads/plm/GeoLite2-City.mmdb
 * or configured via PLM_GEOIP_DB_PATH constant.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PLM_GeoIP {

	/**
	 * Get the path to the GeoLite2 database file.
	 */
	private static function get_db_path(): string {
		if ( defined( 'PLM_GEOIP_DB_PATH' ) ) {
			return PLM_GEOIP_DB_PATH;
		}

		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . '/plm/GeoLite2-City.mmdb';
	}

	/**
	 * Check if the GeoLite2 database is available.
	 */
	public static function is_available(): bool {
		return file_exists( self::get_db_path() );
	}

	/**
	 * Look up an IP address and return geo data.
	 *
	 * Uses the MaxMind DB Reader (pure PHP, no extension required).
	 * Requires: composer require maxmind-db/reader
	 *
	 * Falls back to a basic lookup if the MaxMind reader is not available.
	 */
	public static function lookup( string $ip ): ?array {
		if ( ! self::is_available() ) {
			return null;
		}

		// Skip private/reserved IPs.
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
			return null;
		}

		$db_path = self::get_db_path();

		try {
			// Try MaxMind DB Reader (composer package).
			if ( class_exists( '\MaxMind\Db\Reader' ) ) {
				$reader = new \MaxMind\Db\Reader( $db_path );
				$record = $reader->get( $ip );
				$reader->close();

				if ( ! $record ) {
					return null;
				}

				return array(
					'country_code' => $record['country']['iso_code'] ?? 'XX',
					'country_name' => $record['country']['names']['en'] ?? 'Unknown',
					'region'       => $record['subdivisions'][0]['names']['en'] ?? null,
					'city'         => $record['city']['names']['en'] ?? null,
					'latitude'     => $record['location']['latitude'] ?? null,
					'longitude'    => $record['location']['longitude'] ?? null,
					'continent'    => $record['continent']['names']['en'] ?? null,
					'timezone'     => $record['location']['time_zone'] ?? null,
				);
			}

			// Fallback: try geoip_record_by_name() if PHP GeoIP extension is available.
			if ( function_exists( 'geoip_record_by_name' ) ) {
				$record = @geoip_record_by_name( $ip );
				if ( $record ) {
					return array(
						'country_code' => $record['country_code'] ?? 'XX',
						'country_name' => $record['country_name'] ?? 'Unknown',
						'region'       => $record['region'] ?? null,
						'city'         => $record['city'] ?? null,
						'latitude'     => $record['latitude'] ?? null,
						'longitude'    => $record['longitude'] ?? null,
						'continent'    => null,
						'timezone'     => null,
					);
				}
			}
		} catch ( \Exception $e ) {
			// Log but don't fail.
			error_log( '[PLM GeoIP] Lookup failed for ' . $ip . ': ' . $e->getMessage() );
		}

		return null;
	}

	/**
	 * Look up IP and store/update geo data for an installation.
	 */
	public static function lookup_and_store( int $installation_id, string $ip, bool $update = false ): void {
		global $wpdb;

		$geo = self::lookup( $ip );
		if ( ! $geo ) {
			return;
		}

		$table = PLM_Database::table( 'geo_data' );
		$data  = array(
			'installation_id' => $installation_id,
			'ip_anonymized'   => PLM_License::anonymize_ip( $ip ),
			'country_code'    => $geo['country_code'],
			'country_name'    => $geo['country_name'],
			'region'          => $geo['region'],
			'city'            => $geo['city'],
			'latitude'        => $geo['latitude'],
			'longitude'       => $geo['longitude'],
			'continent'       => $geo['continent'],
			'timezone'        => $geo['timezone'],
		);

		if ( $update ) {
			$existing = $wpdb->get_var(
				$wpdb->prepare( "SELECT id FROM {$table} WHERE installation_id = %d", $installation_id )
			);

			if ( $existing ) {
				unset( $data['installation_id'] );
				$wpdb->update( $table, $data, array( 'installation_id' => $installation_id ) );
				return;
			}
		}

		$wpdb->insert( $table, $data );
	}
}
