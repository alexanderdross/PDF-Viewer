<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Settings', 'pdf-license-manager' ); ?></h1>

	<!-- Stripe Product Mapping -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Stripe Product Mapping', 'pdf-license-manager' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Map Stripe products to license types. When a checkout completes, the matching license type will be created automatically.', 'pdf-license-manager' ); ?>
		</p>

		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Stripe Product ID', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'License Type', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Sites', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Duration (days)', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $mappings as $m ) : ?>
				<tr>
					<td><code><?php echo esc_html( $m->stripe_product_id ); ?></code></td>
					<td><?php echo 'pro_plus' === $m->license_type ? 'Pro+' : 'Premium'; ?></td>
					<td><?php echo esc_html( ucfirst( $m->plan ) ); ?></td>
					<td><?php echo 0 === (int) $m->site_limit ? 'Unlimited' : esc_html( $m->site_limit ); ?></td>
					<td><?php echo 0 === (int) $m->duration_days ? 'Lifetime' : esc_html( $m->duration_days ); ?></td>
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

	<!-- System Info -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'System Information', 'pdf-license-manager' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'API Base URL', 'pdf-license-manager' ); ?></th>
				<td><code><?php echo esc_html( rest_url( 'plm/v1/' ) ); ?></code></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Stripe Webhook URL', 'pdf-license-manager' ); ?></th>
				<td><code><?php echo esc_html( rest_url( 'plm/v1/webhook/stripe' ) ); ?></code></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Stripe', 'pdf-license-manager' ); ?></th>
				<td><?php echo defined( 'PLM_STRIPE_WEBHOOK_SECRET' ) && PLM_STRIPE_WEBHOOK_SECRET ? '<span class="plm-text-success">Configured</span>' : '<span class="plm-text-warning">Not configured</span>'; ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'GeoIP Database', 'pdf-license-manager' ); ?></th>
				<td><?php echo PLM_GeoIP::is_available() ? '<span class="plm-text-success">Available</span>' : '<span class="plm-text-warning">Not found</span>'; ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( PLM_VERSION ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'PHP Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( PHP_VERSION ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WordPress Version', 'pdf-license-manager' ); ?></th>
				<td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
			</tr>
		</table>
	</div>

	<!-- wp-config.php Instructions -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Configuration (wp-config.php)', 'pdf-license-manager' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Add these constants to your wp-config.php:', 'pdf-license-manager' ); ?></p>
		<pre style="background: #f0f0f0; padding: 16px; border-radius: 4px; overflow-x: auto;">
// Stripe Webhook Secret
define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_...' );

// Optional: Custom GeoIP database path
define( 'PLM_GEOIP_DB_PATH', '/path/to/GeoLite2-City.mmdb' );</pre>
	</div>
</div>
