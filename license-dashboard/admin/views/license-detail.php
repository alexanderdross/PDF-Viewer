<?php if ( ! defined( 'ABSPATH' ) ) exit;
$days = PLM_License::days_remaining( $license->expires_at );
$active_count = PLM_License::count_active_sites( (int) $license->id );
?>
<div class="wrap plm-wrap">
	<h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses' ) ); ?>">&larr;</a>
		<?php esc_html_e( 'License Detail', 'pdf-license-manager' ); ?>
	</h1>

	<?php if ( isset( $_GET['created'] ) ) : ?>
		<div class="notice notice-success"><p><?php esc_html_e( 'License created successfully.', 'pdf-license-manager' ); ?></p></div>
	<?php endif; ?>
	<?php if ( isset( $_GET['extended'] ) ) : ?>
		<div class="notice notice-success"><p><?php esc_html_e( 'License extended successfully.', 'pdf-license-manager' ); ?></p></div>
	<?php endif; ?>
	<?php if ( isset( $_GET['revoked'] ) ) : ?>
		<div class="notice notice-warning"><p><?php esc_html_e( 'License has been revoked.', 'pdf-license-manager' ); ?></p></div>
	<?php endif; ?>

	<!-- License Info -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'License Information', 'pdf-license-manager' ); ?></h2>
		<table class="form-table">
			<tr><th><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th><td><code><?php echo esc_html( $license->license_key ); ?></code></td></tr>
			<tr><th><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></th><td><span class="plm-badge plm-badge-<?php echo esc_attr( $license->license_type ); ?>"><?php echo 'pro_plus' === $license->license_type ? 'Pro+ Enterprise' : 'Premium'; ?></span></td></tr>
			<tr><th><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( ucfirst( $license->plan ) ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th><td><span class="plm-badge plm-badge-<?php echo esc_attr( $license->status ); ?>"><?php echo esc_html( str_replace( '_', ' ', $license->status ) ); ?></span></td></tr>
			<tr><th><?php esc_html_e( 'Site Limit', 'pdf-license-manager' ); ?></th><td><?php echo 0 === (int) $license->site_limit ? 'Unlimited' : esc_html( $license->site_limit ); ?> (<?php echo esc_html( $active_count ); ?> active)</td></tr>
			<tr>
				<th><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
				<td>
					<?php if ( null === $days ) : ?>
						<strong class="plm-text-success">Lifetime</strong>
					<?php else : ?>
						<strong class="<?php echo $days <= 14 ? 'plm-text-warning' : ''; ?> <?php echo $days <= 0 ? 'plm-text-danger' : ''; ?>"><?php echo esc_html( $days ); ?> <?php esc_html_e( 'days', 'pdf-license-manager' ); ?></strong>
					<?php endif; ?>
				</td>
			</tr>
			<tr><th><?php esc_html_e( 'Expires At', 'pdf-license-manager' ); ?></th><td><?php echo $license->expires_at ? esc_html( wp_date( 'd.m.Y H:i', strtotime( $license->expires_at ) ) ) : esc_html__( 'Never', 'pdf-license-manager' ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Customer Email', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->customer_email ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Customer Name', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->customer_name ?: '-' ); ?></td></tr>
			<?php if ( $license->stripe_customer_id ) : ?>
			<tr><th><?php esc_html_e( 'Stripe Customer', 'pdf-license-manager' ); ?></th><td><code><?php echo esc_html( $license->stripe_customer_id ); ?></code></td></tr>
			<?php endif; ?>
			<tr><th><?php esc_html_e( 'Created', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( wp_date( 'd.m.Y H:i', strtotime( $license->created_at ) ) ); ?></td></tr>
			<?php if ( $license->notes ) : ?>
			<tr><th><?php esc_html_e( 'Notes', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->notes ); ?></td></tr>
			<?php endif; ?>
		</table>

		<!-- Actions -->
		<div class="plm-actions" style="margin-top: 16px; display: flex; gap: 12px;">
			<?php if ( 'revoked' !== $license->status ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;">
				<?php wp_nonce_field( 'plm_extend_license' ); ?>
				<input type="hidden" name="action" value="plm_extend_license">
				<input type="hidden" name="license_id" value="<?php echo esc_attr( $license->id ); ?>">
				<select name="extend_days">
					<option value="365">+1 Year</option>
					<option value="180">+6 Months</option>
					<option value="90">+90 Days</option>
					<option value="30">+30 Days</option>
				</select>
				<button type="submit" class="button"><?php esc_html_e( 'Extend', 'pdf-license-manager' ); ?></button>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to revoke this license?');">
				<?php wp_nonce_field( 'plm_revoke_license' ); ?>
				<input type="hidden" name="action" value="plm_revoke_license">
				<input type="hidden" name="license_id" value="<?php echo esc_attr( $license->id ); ?>">
				<button type="submit" class="button button-link-delete"><?php esc_html_e( 'Revoke License', 'pdf-license-manager' ); ?></button>
			</form>
			<?php endif; ?>
		</div>
	</div>

	<!-- Installations -->
	<div class="plm-section">
		<h2><?php printf( esc_html__( 'Installations (%d active)', 'pdf-license-manager' ), count( array_filter( $installations, fn( $i ) => $i->is_active ) ) ); ?></h2>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Platform', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Root Domain', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Country', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Activated', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Last Check', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $installations as $inst ) : ?>
				<tr class="<?php echo ! $inst->is_active ? 'plm-row-inactive' : ''; ?>">
					<td><span class="plm-badge plm-badge-<?php echo esc_attr( $inst->platform ); ?>"><?php echo esc_html( ucfirst( $inst->platform ) ); ?></span></td>
					<td><code><?php echo esc_html( $inst->site_url ); ?></code><?php echo $inst->is_local ? ' <em>(local)</em>' : ''; ?></td>
					<td><?php echo esc_html( $inst->plugin_version ); ?></td>
					<td><?php echo esc_html( $inst->country_code ?? '-' ); ?></td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->activated_at ) ) ); ?></td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->last_checked_at ) ) ); ?></td>
					<td><?php echo $inst->is_active ? '<span class="plm-text-success">Active</span>' : '<span class="plm-text-muted">Inactive</span>'; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php if ( empty( $installations ) ) : ?>
				<tr><td colspan="7"><?php esc_html_e( 'No installations.', 'pdf-license-manager' ); ?></td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- Audit Log -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Activity Log', 'pdf-license-manager' ); ?></h2>
		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Time', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Event', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Details', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'IP', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $audit_logs as $log ) : ?>
				<tr>
					<td><?php echo esc_html( wp_date( 'd.m.Y H:i', strtotime( $log->created_at ) ) ); ?></td>
					<td><span class="plm-badge"><?php echo esc_html( $log->event_type ); ?></span></td>
					<td><small><?php echo esc_html( $log->details ); ?></small></td>
					<td><?php echo esc_html( $log->ip_address ?: '-' ); ?></td>
				</tr>
				<?php endforeach; ?>
				<?php if ( empty( $audit_logs ) ) : ?>
				<tr><td colspan="4"><?php esc_html_e( 'No events.', 'pdf-license-manager' ); ?></td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
