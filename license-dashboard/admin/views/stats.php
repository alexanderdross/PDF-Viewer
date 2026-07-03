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

	<!-- World Map -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Installation World Map', 'pdf-license-manager' ); ?></h2>
		<?php if ( empty( $geo_points ) ) : ?>
			<p class="description"><?php esc_html_e( 'No geolocated installations yet. Points appear once the GeoIP database resolves installation locations.', 'pdf-license-manager' ); ?></p>
		<?php else : ?>
			<?php $plm_map_max = max( array_map( fn( $g ) => (int) $g->count, $geo_points ) ); ?>
			<div class="plm-worldmap-wrap" style="max-width: 860px;">
			<svg viewBox="0 0 360 180" role="img" aria-label="<?php esc_attr_e( 'World map of active installations', 'pdf-license-manager' ); ?>" style="width:100%;height:auto;background:#0b1f33;border-radius:6px;">
				<g stroke="#1c3a57" stroke-width="0.3" fill="none">
					<?php for ( $lon = 0; $lon <= 360; $lon += 30 ) : ?>
						<line x1="<?php echo esc_attr( $lon ); ?>" y1="0" x2="<?php echo esc_attr( $lon ); ?>" y2="180" />
					<?php endfor; ?>
					<?php for ( $lat = 0; $lat <= 180; $lat += 30 ) : ?>
						<line x1="0" y1="<?php echo esc_attr( $lat ); ?>" x2="360" y2="<?php echo esc_attr( $lat ); ?>" />
					<?php endfor; ?>
				</g>
				<g stroke="#2b567d" stroke-width="0.5">
					<line x1="0" y1="90" x2="360" y2="90" />
					<line x1="180" y1="0" x2="180" y2="180" />
				</g>
				<g fill="#5b7fa3" font-size="5" font-family="sans-serif" text-anchor="middle">
					<text x="80" y="45"><?php esc_html_e( 'N. America', 'pdf-license-manager' ); ?></text>
					<text x="120" y="108"><?php esc_html_e( 'S. America', 'pdf-license-manager' ); ?></text>
					<text x="196" y="38"><?php esc_html_e( 'Europe', 'pdf-license-manager' ); ?></text>
					<text x="202" y="88"><?php esc_html_e( 'Africa', 'pdf-license-manager' ); ?></text>
					<text x="272" y="46"><?php esc_html_e( 'Asia', 'pdf-license-manager' ); ?></text>
					<text x="315" y="118"><?php esc_html_e( 'Oceania', 'pdf-license-manager' ); ?></text>
				</g>
				<g fill="#41d1a7" fill-opacity="0.75" stroke="#0b1f33" stroke-width="0.2">
					<?php foreach ( $geo_points as $pt ) : ?>
						<?php
						$cx = (float) $pt->lng + 180;
						$cy = 90 - (float) $pt->lat;
						$r  = 1.2 + sqrt( (int) $pt->count / max( 1, (int) $plm_map_max ) ) * 4.5;
						?>
						<circle cx="<?php echo esc_attr( round( $cx, 2 ) ); ?>" cy="<?php echo esc_attr( round( $cy, 2 ) ); ?>" r="<?php echo esc_attr( round( $r, 2 ) ); ?>">
							<title><?php echo esc_html( sprintf( '%d @ %s, %s', (int) $pt->count, $pt->lat, $pt->lng ) ); ?></title>
						</circle>
					<?php endforeach; ?>
				</g>
			</svg>
			</div>
			<p class="description"><?php
				/* translators: %d: number of installation location clusters */
				printf( esc_html__( '%d location clusters of active installations (bubble size = install count).', 'pdf-license-manager' ), (int) count( $geo_points ) );
			?></p>
		<?php endif; ?>
	</div>

	<div class="plm-grid-2">
		<!-- Geo Distribution -->
		<div class="plm-section">
			<h2><?php esc_html_e( 'Geo Distribution (Top 20)', 'pdf-license-manager' ); ?></h2>
			<div class="plm-table-responsive">
			<table class="widefat fixed striped">
				<caption class="screen-reader-text"><?php esc_html_e( 'Installation count by country', 'pdf-license-manager' ); ?></caption>
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Country', 'pdf-license-manager' ); ?></th>
						<th scope="col" class="plm-text-right"><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $geo_distribution as $geo ) : ?>
					<tr>
						<td><?php echo esc_html( $geo->country_code . ' — ' . $geo->country_name ); ?></td>
						<td class="plm-text-right"><strong><?php echo esc_html( $geo->count ); ?></strong></td>
					</tr>
					<?php endforeach; ?>
					<?php if ( empty( $geo_distribution ) ) : ?>
					<tr><td colspan="2"><?php esc_html_e( 'No data.', 'pdf-license-manager' ); ?></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
			</div>
		</div>

		<!-- Platform Distribution -->
		<div class="plm-section">
			<h2><?php esc_html_e( 'Platform Distribution', 'pdf-license-manager' ); ?></h2>
			<?php
			$total_platform = array_sum( array_map( fn( $p ) => $p->count, $platform_distribution ) );
			foreach ( $platform_distribution as $p ) :
				$pct = $total_platform > 0 ? round( ( $p->count / $total_platform ) * 100 ) : 0;
			?>
			<div class="plm-bar-row" role="progressbar" aria-valuenow="<?php echo esc_attr( $pct ); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?php echo esc_attr( ucfirst( $p->platform ) ); ?>">
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

			<h2 class="plm-section-subtitle"><?php esc_html_e( 'Licenses by Type', 'pdf-license-manager' ); ?></h2>
			<table class="widefat fixed striped">
				<caption class="screen-reader-text"><?php esc_html_e( 'License count by type', 'pdf-license-manager' ); ?></caption>
				<tbody>
					<?php foreach ( $type_distribution as $t ) : ?>
					<tr>
						<td><?php echo esc_html( 'pro_plus' === $t->license_type ? __( 'Pro+ Enterprise', 'pdf-license-manager' ) : __( 'Premium', 'pdf-license-manager' ) ); ?></td>
						<td class="plm-text-right"><strong><?php echo esc_html( $t->count ); ?></strong></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2 class="plm-section-subtitle"><?php esc_html_e( 'Active Licenses by Plan', 'pdf-license-manager' ); ?></h2>
			<table class="widefat fixed striped">
				<caption class="screen-reader-text"><?php esc_html_e( 'Active license count by plan', 'pdf-license-manager' ); ?></caption>
				<tbody>
					<?php foreach ( $plan_distribution as $p ) : ?>
					<tr>
						<td><?php echo esc_html( ucfirst( $p->plan ) ); ?></td>
						<td class="plm-text-right"><strong><?php echo esc_html( $p->count ); ?></strong></td>
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
