<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Settings', 'pdf-license-manager' ); ?></h1>

	<!-- Stripe Product Mapping -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Stripe Product Mapping', 'pdf-license-manager' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Map Stripe products to license types. When a checkout completes, the matching license type will be created automatically.', 'pdf-license-manager' ); ?>
		</p>

		<div class="plm-table-responsive">
		<table class="widefat fixed striped">
			<caption class="screen-reader-text"><?php esc_html_e( 'Stripe product to license type mappings', 'pdf-license-manager' ); ?></caption>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Stripe Product ID', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'License Type', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Sites', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Duration (days)', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $mappings as $m ) : ?>
				<tr>
					<td><code><?php echo esc_html( $m->stripe_product_id ); ?></code></td>
					<td><?php echo esc_html( 'pro_plus' === $m->license_type ? __( 'Pro+', 'pdf-license-manager' ) : __( 'Premium', 'pdf-license-manager' ) ); ?></td>
					<td><?php echo esc_html( ucfirst( $m->plan ) ); ?></td>
					<td><?php echo 0 === (int) $m->site_limit ? esc_html__( 'Unlimited', 'pdf-license-manager' ) : esc_html( $m->site_limit ); ?></td>
					<td><?php echo 0 === (int) $m->duration_days ? esc_html__( 'Lifetime', 'pdf-license-manager' ) : esc_html( $m->duration_days ); ?></td>
				</tr>
				<?php endforeach; ?>
				<?php if ( empty( $mappings ) ) : ?>
				<tr>
					<td colspan="5">
						<?php esc_html_e( 'No mappings configured. Add Stripe products once your Stripe integration is active.', 'pdf-license-manager' ); ?>
					</td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		</div>
	</div>

	<!-- Security -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Security', 'pdf-license-manager' ); ?></h2>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Encryption', 'pdf-license-manager' ); ?></th>
				<td>
					<?php if ( PLM_Encryption::is_available() ) : ?>
						<span class="plm-text-success"><?php esc_html_e( 'AES-256-CBC Available', 'pdf-license-manager' ); ?></span>
						<p class="description"><?php esc_html_e( 'Sensitive settings are encrypted using WordPress AUTH_KEY + SECURE_AUTH_KEY salts.', 'pdf-license-manager' ); ?></p>
					<?php else : ?>
						<span class="plm-text-warning"><?php esc_html_e( 'OpenSSL not available', 'pdf-license-manager' ); ?></span>
						<p class="description"><?php esc_html_e( 'Install the PHP OpenSSL extension to enable encryption for sensitive settings.', 'pdf-license-manager' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Rate Limiting', 'pdf-license-manager' ); ?></th>
				<td>
					<span class="plm-text-success"><?php esc_html_e( 'Database-backed', 'pdf-license-manager' ); ?></span>
					<p class="description">
						<?php
						printf(
							/* translators: 1: API limit, 2: Activate limit, 3: Check limit */
							esc_html__( 'API: %1$d/min per IP | Activate: %2$d/hour per key | Heartbeat: %3$d/day per key', 'pdf-license-manager' ),
							PLM_RATE_LIMIT_API_PER_MINUTE,
							PLM_RATE_LIMIT_ACTIVATE_PER_HOUR,
							PLM_RATE_LIMIT_CHECK_PER_DAY
						);
						?>
					</p>
				</td>
			</tr>
		</table>
	</div>

	<!-- Cron Jobs -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Scheduled Tasks', 'pdf-license-manager' ); ?></h2>
		<table class="form-table">
			<?php
			$cron_hooks = array(
				'plm_check_expired_licenses'    => array( __( 'Check Expired Licenses', 'pdf-license-manager' ), __( 'Daily', 'pdf-license-manager' ) ),
				'plm_check_stale_installations' => array( __( 'Check Stale Installations', 'pdf-license-manager' ), __( 'Daily', 'pdf-license-manager' ) ),
				'plm_send_expiry_warnings'      => array( __( 'Send Expiry Warnings', 'pdf-license-manager' ), __( 'Daily', 'pdf-license-manager' ) ),
				'plm_cleanup_rate_limits'        => array( __( 'Clean Rate Limits', 'pdf-license-manager' ), __( 'Hourly', 'pdf-license-manager' ) ),
				'plm_cleanup_old_data'           => array( __( 'Archive Old Data', 'pdf-license-manager' ), __( 'Weekly', 'pdf-license-manager' ) ),
				'plm_update_geoip_db'            => array( __( 'Update GeoIP Database', 'pdf-license-manager' ), __( 'Monthly', 'pdf-license-manager' ) ),
			);
			foreach ( $cron_hooks as $hook => $info ) :
				$next = wp_next_scheduled( $hook );
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $info[0] ); ?> <small>(<?php echo esc_html( $info[1] ); ?>)</small></th>
				<td>
					<?php if ( $next ) : ?>
						<span class="plm-text-success"><?php esc_html_e( 'Scheduled', 'pdf-license-manager' ); ?></span>
						<span class="description"> &mdash; <?php
							printf(
								/* translators: %s: next run time */
								esc_html__( 'Next: %s', 'pdf-license-manager' ),
								esc_html( date_i18n( 'Y-m-d H:i:s', $next ) )
							);
						?></span>
					<?php else : ?>
						<span class="plm-text-warning"><?php esc_html_e( 'Not scheduled', 'pdf-license-manager' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<!-- System Info -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'System Information', 'pdf-license-manager' ); ?></h2>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'API Base URL', 'pdf-license-manager' ); ?></th>
				<td><code><?php echo esc_html( rest_url( 'plm/v1/' ) ); ?></code></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Stripe Webhook URL', 'pdf-license-manager' ); ?></th>
				<td><code><?php echo esc_html( rest_url( 'plm/v1/webhook/stripe' ) ); ?></code></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Stripe', 'pdf-license-manager' ); ?></th>
				<td>
					<?php
					$stripe_configured = ( defined( 'PLM_STRIPE_WEBHOOK_SECRET' ) && PLM_STRIPE_WEBHOOK_SECRET )
						|| ! empty( PLM_Encryption::get_option( 'plm_stripe_webhook_secret', '' ) );
					?>
					<?php if ( $stripe_configured ) : ?>
						<span class="plm-text-success"><?php esc_html_e( 'Configured', 'pdf-license-manager' ); ?></span>
					<?php else : ?>
						<span class="plm-text-warning"><?php esc_html_e( 'Not configured', 'pdf-license-manager' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'GeoIP Database', 'pdf-license-manager' ); ?></th>
				<td>
					<?php if ( PLM_GeoIP::is_available() ) : ?>
						<span class="plm-text-success"><?php esc_html_e( 'Available', 'pdf-license-manager' ); ?></span>
					<?php else : ?>
						<span class="plm-text-warning"><?php esc_html_e( 'Not found', 'pdf-license-manager' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( PLM_VERSION ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'PHP Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( PHP_VERSION ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'WordPress Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
			</tr>
		</table>
	</div>

	<!-- wp-config.php Instructions -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Configuration (wp-config.php)', 'pdf-license-manager' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Add these constants to your wp-config.php to customize behavior:', 'pdf-license-manager' ); ?></p>
		<pre class="plm-code-block">
// Required: Stripe Webhook Secret
define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_...' );

// Optional: Stripe Secret Key (for API calls)
define( 'PLM_STRIPE_SECRET_KEY', 'sk_live_...' );

// Optional: Custom GeoIP database path
define( 'PLM_GEOIP_DB_PATH', '/path/to/GeoLite2-City.mmdb' );

// Optional: MaxMind license key for auto-update
define( 'PLM_MAXMIND_LICENSE_KEY', 'your_maxmind_key' );

// Optional: Override defaults
define( 'PLM_GRACE_PERIOD_DAYS', 14 );              // Grace period after expiration
define( 'PLM_STALE_INSTALLATION_DAYS', 7 );          // Days before marking install stale
define( 'PLM_DATA_RETENTION_MONTHS', 24 );            // Audit log retention
define( 'PLM_RATE_LIMIT_API_PER_MINUTE', 60 );       // API rate limit
define( 'PLM_RATE_LIMIT_ACTIVATE_PER_HOUR', 10 );    // Activation rate limit
define( 'PLM_RATE_LIMIT_CHECK_PER_DAY', 1000 );      // Heartbeat rate limit
define( 'PLM_DEV_DOMAINS', 'localhost,*.local,*.test,*.dev' );</pre>
	</div>
</div>
