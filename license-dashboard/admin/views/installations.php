<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></h1>

	<!-- Filter -->
	<form method="get" class="plm-filter-bar">
		<input type="hidden" name="page" value="plm-installations">
		<label for="plm-filter-platform" class="screen-reader-text"><?php esc_html_e( 'Filter by platform', 'pdf-license-manager' ); ?></label>
		<select name="platform" id="plm-filter-platform">
			<option value=""><?php esc_html_e( 'All Platforms', 'pdf-license-manager' ); ?></option>
			<option value="wordpress" <?php selected( $platform_filter, 'wordpress' ); ?>><?php esc_html_e( 'WordPress', 'pdf-license-manager' ); ?></option>
			<option value="drupal" <?php selected( $platform_filter, 'drupal' ); ?>><?php esc_html_e( 'Drupal', 'pdf-license-manager' ); ?></option>
			<option value="react" <?php selected( $platform_filter, 'react' ); ?>><?php esc_html_e( 'React', 'pdf-license-manager' ); ?></option>
		</select>
		<?php submit_button( __( 'Filter', 'pdf-license-manager' ), 'secondary', 'submit', false ); ?>
	</form>

	<?php if ( isset( $total_installations ) && $total_installations > 0 ) : ?>
	<p class="plm-result-count">
		<?php
		printf(
			/* translators: 1: Number of installations shown, 2: Total number of installations */
			esc_html__( 'Showing %1$d of %2$d installations.', 'pdf-license-manager' ),
			count( $installations ),
			$total_installations
		);
		?>
	</p>
	<?php endif; ?>

	<div class="plm-table-responsive">
	<table class="widefat fixed striped plm-table">
		<caption class="screen-reader-text"><?php esc_html_e( 'List of active installations', 'pdf-license-manager' ); ?></caption>
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Platform', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Root Domain', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Activation Date', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'License Type', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Country / Region', 'pdf-license-manager' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Last Heartbeat', 'pdf-license-manager' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $installations ) ) : ?>
				<tr><td colspan="9"><?php esc_html_e( 'No active installations.', 'pdf-license-manager' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $installations as $inst ) :
					$days = PLM_License::days_remaining( $inst->license_expires_at );
				?>
				<tr>
					<td><span class="plm-badge plm-badge-<?php echo esc_attr( $inst->platform ); ?>"><?php echo esc_html( ucfirst( $inst->platform ) ); ?></span></td>
					<td>
						<code><?php echo esc_html( $inst->site_url ); ?></code>
						<?php if ( $inst->is_local ) : ?>
							<em class="plm-text-muted"><?php esc_html_e( '(local)', 'pdf-license-manager' ); ?></em>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->activated_at ) ) ); ?></td>
					<td>
						<?php if ( null === $days ) : ?>
							<span class="plm-text-success"><?php esc_html_e( 'Lifetime', 'pdf-license-manager' ); ?></span>
						<?php elseif ( $days <= 0 ) : ?>
							<strong class="plm-text-danger"><?php echo esc_html( $days ); ?></strong>
						<?php elseif ( $days <= 14 ) : ?>
							<strong class="plm-text-warning"><?php echo esc_html( $days ); ?></strong>
						<?php else : ?>
							<?php echo esc_html( $days ); ?>
						<?php endif; ?>
					</td>
					<td><span class="plm-badge plm-badge-<?php echo esc_attr( $inst->license_type ); ?>"><?php echo esc_html( 'pro_plus' === $inst->license_type ? __( 'Pro+', 'pdf-license-manager' ) : __( 'Premium', 'pdf-license-manager' ) ); ?></span></td>
					<td><?php echo esc_html( $inst->plugin_version ); ?></td>
					<td><code class="plm-text-muted"><?php echo esc_html( PLM_License::mask_key( $inst->license_key ) ); ?></code></td>
					<td>
						<?php if ( $inst->country_code ) : ?>
							<?php echo esc_html( $inst->country_code ); ?>
							<?php if ( $inst->region ) : ?> / <?php echo esc_html( $inst->region ); ?><?php endif; ?>
							<?php if ( $inst->city ) : ?> / <?php echo esc_html( $inst->city ); ?><?php endif; ?>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->last_checked_at ) ) ); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	</div>

	<?php if ( ! empty( $pagination_links ) ) : ?>
	<div class="plm-pagination">
		<?php echo $pagination_links; // Already escaped by paginate_links(). ?>
	</div>
	<?php endif; ?>
</div>
