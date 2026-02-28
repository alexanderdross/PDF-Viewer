<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Installations', 'pdf-license-manager' ); ?></h1>

	<!-- Filter -->
	<form method="get" class="plm-filter-bar">
		<input type="hidden" name="page" value="plm-installations">
		<select name="platform">
			<option value=""><?php esc_html_e( 'All Platforms', 'pdf-license-manager' ); ?></option>
			<option value="wordpress" <?php selected( $platform_filter, 'wordpress' ); ?>>WordPress</option>
			<option value="drupal" <?php selected( $platform_filter, 'drupal' ); ?>>Drupal</option>
			<option value="react" <?php selected( $platform_filter, 'react' ); ?>>React</option>
		</select>
		<?php submit_button( __( 'Filter', 'pdf-license-manager' ), 'secondary', 'submit', false ); ?>
	</form>

	<table class="widefat fixed striped plm-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Platform', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Root Domain', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Activation Date', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'License Type', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Country / Region', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Last Heartbeat', 'pdf-license-manager' ); ?></th>
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
						<?php echo $inst->is_local ? ' <em class="plm-text-muted">(local)</em>' : ''; ?>
					</td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->activated_at ) ) ); ?></td>
					<td>
						<?php if ( null === $days ) : ?>
							<span class="plm-text-success">Lifetime</span>
						<?php elseif ( $days <= 0 ) : ?>
							<strong class="plm-text-danger"><?php echo esc_html( $days ); ?></strong>
						<?php elseif ( $days <= 14 ) : ?>
							<strong class="plm-text-warning"><?php echo esc_html( $days ); ?></strong>
						<?php else : ?>
							<?php echo esc_html( $days ); ?>
						<?php endif; ?>
					</td>
					<td><span class="plm-badge plm-badge-<?php echo esc_attr( $inst->license_type ); ?>"><?php echo 'pro_plus' === $inst->license_type ? 'Pro+' : 'Premium'; ?></span></td>
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
