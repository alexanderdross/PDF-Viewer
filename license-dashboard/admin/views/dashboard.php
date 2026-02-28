<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'License Dashboard', 'pdf-license-manager' ); ?></h1>

	<!-- KPI Cards -->
	<div class="plm-kpi-grid">
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Active Licenses', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['active_licenses'] ); ?></span>
		</div>
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Active Installations', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['active_installations'] ); ?></span>
		</div>
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Total Licenses', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['total_licenses'] ); ?></span>
		</div>
		<div class="plm-kpi-card <?php echo $stats['expiring_30d'] > 0 ? 'plm-kpi-alert' : ''; ?>">
			<span class="plm-kpi-label"><?php esc_html_e( 'Expiring (30 days)', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['expiring_30d'] ); ?></span>
		</div>
	</div>

	<!-- Quick Links -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Quick Actions', 'pdf-license-manager' ); ?></h2>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&action=new' ) ); ?>" class="button button-primary">
			<?php esc_html_e( 'Create New License', 'pdf-license-manager' ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-installations' ) ); ?>" class="button">
			<?php esc_html_e( 'View Installations', 'pdf-license-manager' ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-stats' ) ); ?>" class="button">
			<?php esc_html_e( 'View Statistics', 'pdf-license-manager' ); ?>
		</a>
	</div>

	<!-- API Info -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'API Endpoints', 'pdf-license-manager' ); ?></h2>
		<table class="widefat fixed">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Method', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Endpoint', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Description', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$base = rest_url( 'plm/v1/' );
				$endpoints = array(
					array( 'POST', 'license/validate', 'Validate a license key' ),
					array( 'POST', 'license/activate', 'Activate license for a site' ),
					array( 'POST', 'license/deactivate', 'Deactivate license for a site' ),
					array( 'POST', 'license/check', 'Heartbeat / status check' ),
					array( 'POST', 'webhook/stripe', 'Stripe webhook receiver' ),
					array( 'GET', 'health', 'Health check' ),
				);
				foreach ( $endpoints as $ep ) :
				?>
				<tr>
					<td><code><?php echo esc_html( $ep[0] ); ?></code></td>
					<td><code><?php echo esc_html( $base . $ep[1] ); ?></code></td>
					<td><?php echo esc_html( $ep[2] ); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
