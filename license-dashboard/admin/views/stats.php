<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Statistics', 'pdf-license-manager' ); ?></h1>

	<!-- Overview -->
	<div class="plm-kpi-grid">
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Active', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value plm-text-success"><?php echo esc_html( $stats['active_licenses'] ); ?></span>
		</div>
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Expired', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value plm-text-danger"><?php echo esc_html( $stats['expired_licenses'] ); ?></span>
		</div>
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Revoked', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['revoked_licenses'] ); ?></span>
		</div>
		<div class="plm-kpi-card">
			<span class="plm-kpi-label"><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></span>
			<span class="plm-kpi-value"><?php echo esc_html( $stats['active_installations'] ); ?></span>
		</div>
	</div>

	<div class="plm-grid-2">
		<!-- Geo Distribution -->
		<div class="plm-section">
			<h2><?php esc_html_e( 'Geo Distribution (Top 20)', 'pdf-license-manager' ); ?></h2>
			<table class="widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Country', 'pdf-license-manager' ); ?></th>
						<th style="text-align:right;"><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $geo_distribution as $geo ) : ?>
					<tr>
						<td><?php echo esc_html( $geo->country_code . ' — ' . $geo->country_name ); ?></td>
						<td style="text-align:right;"><strong><?php echo esc_html( $geo->count ); ?></strong></td>
					</tr>
					<?php endforeach; ?>
					<?php if ( empty( $geo_distribution ) ) : ?>
					<tr><td colspan="2"><?php esc_html_e( 'No data.', 'pdf-license-manager' ); ?></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<!-- Platform Distribution -->
		<div class="plm-section">
			<h2><?php esc_html_e( 'Platform Distribution', 'pdf-license-manager' ); ?></h2>
			<?php
			$total_platform = array_sum( array_map( fn( $p ) => $p->count, $platform_distribution ) );
			foreach ( $platform_distribution as $p ) :
				$pct = $total_platform > 0 ? round( ( $p->count / $total_platform ) * 100 ) : 0;
			?>
			<div class="plm-bar-row">
				<span class="plm-bar-label"><?php echo esc_html( ucfirst( $p->platform ) ); ?></span>
				<div class="plm-bar-track">
					<div class="plm-bar-fill plm-bar-<?php echo esc_attr( $p->platform ); ?>" style="width: <?php echo esc_attr( $pct ); ?>%;"></div>
				</div>
				<span class="plm-bar-value"><?php echo esc_html( $p->count ); ?> (<?php echo esc_html( $pct ); ?>%)</span>
			</div>
			<?php endforeach; ?>
			<?php if ( empty( $platform_distribution ) ) : ?>
			<p><?php esc_html_e( 'No data.', 'pdf-license-manager' ); ?></p>
			<?php endif; ?>

			<h2 style="margin-top: 24px;"><?php esc_html_e( 'Licenses by Type', 'pdf-license-manager' ); ?></h2>
			<table class="widefat fixed striped">
				<tbody>
					<?php foreach ( $type_distribution as $t ) : ?>
					<tr>
						<td><?php echo 'pro_plus' === $t->license_type ? 'Pro+ Enterprise' : 'Premium'; ?></td>
						<td style="text-align:right;"><strong><?php echo esc_html( $t->count ); ?></strong></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2 style="margin-top: 24px;"><?php esc_html_e( 'Active Licenses by Plan', 'pdf-license-manager' ); ?></h2>
			<table class="widefat fixed striped">
				<tbody>
					<?php foreach ( $plan_distribution as $p ) : ?>
					<tr>
						<td><?php echo esc_html( ucfirst( $p->plan ) ); ?></td>
						<td style="text-align:right;"><strong><?php echo esc_html( $p->count ); ?></strong></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Version Distribution -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Plugin Version Distribution', 'pdf-license-manager' ); ?></h2>
		<div class="plm-version-grid">
			<?php foreach ( $version_distribution as $v ) : ?>
			<div class="plm-version-card">
				<span class="plm-version-number"><?php echo esc_html( $v->plugin_version ); ?></span>
				<span class="plm-version-count"><?php echo esc_html( $v->count ); ?> <?php esc_html_e( 'installations', 'pdf-license-manager' ); ?></span>
			</div>
			<?php endforeach; ?>
			<?php if ( empty( $version_distribution ) ) : ?>
			<p><?php esc_html_e( 'No data.', 'pdf-license-manager' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
