<?php if ( ! defined( 'ABSPATH' ) ) exit;
$days = PLM_License::days_remaining( $license->expires_at );
$active_count = PLM_License::count_active_sites( (int) $license->id );
?>
<div class="wrap plm-wrap">
	<h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses' ) ); ?>" aria-label="<?php esc_attr_e( 'Back to licenses', 'pdf-license-manager' ); ?>">&larr;</a>
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
			<tr>
				<th scope="row"><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th>
				<td>
					<code id="plm-license-key"><?php echo esc_html( $license->license_key ); ?></code>
					<button type="button" class="button button-small plm-copy-key" data-clipboard-target="#plm-license-key" aria-label="<?php esc_attr_e( 'Copy license key to clipboard', 'pdf-license-manager' ); ?>">
						<?php esc_html_e( 'Copy', 'pdf-license-manager' ); ?>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></th>
				<td><span class="plm-badge plm-badge-<?php echo esc_attr( $license->license_type ); ?>"><?php echo esc_html( 'pro_plus' === $license->license_type ? __( 'Pro+ Enterprise', 'pdf-license-manager' ) : __( 'Premium', 'pdf-license-manager' ) ); ?></span></td>
			</tr>
			<tr><th scope="row"><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( ucfirst( $license->plan ) ); ?></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th><td><span class="plm-badge plm-badge-<?php echo esc_attr( $license->status ); ?>"><?php echo esc_html( str_replace( '_', ' ', $license->status ) ); ?></span></td></tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Site Limit', 'pdf-license-manager' ); ?></th>
				<td>
					<?php echo 0 === (int) $license->site_limit ? esc_html__( 'Unlimited', 'pdf-license-manager' ) : esc_html( $license->site_limit ); ?>
					(<?php echo esc_html( $active_count ); ?> <?php esc_html_e( 'active', 'pdf-license-manager' ); ?>)
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
				<td>
					<?php if ( null === $days ) : ?>
						<strong class="plm-text-success"><?php esc_html_e( 'Lifetime', 'pdf-license-manager' ); ?></strong>
					<?php else : ?>
						<strong class="<?php echo $days <= 14 ? 'plm-text-warning' : ''; ?> <?php echo $days <= 0 ? 'plm-text-danger' : ''; ?>"><?php echo esc_html( $days ); ?> <?php esc_html_e( 'days', 'pdf-license-manager' ); ?></strong>
					<?php endif; ?>
				</td>
			</tr>
			<tr><th scope="row"><?php esc_html_e( 'Expires At', 'pdf-license-manager' ); ?></th><td><?php echo $license->expires_at ? esc_html( wp_date( 'd.m.Y H:i', strtotime( $license->expires_at ) ) ) : esc_html__( 'Never', 'pdf-license-manager' ); ?></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'Customer Email', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->customer_email ); ?></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'Customer Name', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->customer_name ?: '-' ); ?></td></tr>
			<?php if ( $license->stripe_customer_id ) : ?>
			<tr><th scope="row"><?php esc_html_e( 'Stripe Customer', 'pdf-license-manager' ); ?></th><td><code><?php echo esc_html( $license->stripe_customer_id ); ?></code></td></tr>
			<?php endif; ?>
			<tr><th scope="row"><?php esc_html_e( 'Created', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( wp_date( 'd.m.Y H:i', strtotime( $license->created_at ) ) ); ?></td></tr>
			<?php if ( $license->notes ) : ?>
			<tr><th scope="row"><?php esc_html_e( 'Notes', 'pdf-license-manager' ); ?></th><td><?php echo esc_html( $license->notes ); ?></td></tr>
			<?php endif; ?>
		</table>

		<!-- Actions -->
		<div class="plm-actions plm-actions-spaced">
			<?php if ( 'revoked' !== $license->status ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="plm-inline-form">
				<?php wp_nonce_field( 'plm_extend_license' ); ?>
				<input type="hidden" name="action" value="plm_extend_license">
				<input type="hidden" name="license_id" value="<?php echo esc_attr( $license->id ); ?>">
				<label for="plm-extend-days" class="screen-reader-text"><?php esc_html_e( 'Extension duration', 'pdf-license-manager' ); ?></label>
				<select name="extend_days" id="plm-extend-days">
					<option value="365"><?php esc_html_e( '+1 Year', 'pdf-license-manager' ); ?></option>
					<option value="180"><?php esc_html_e( '+6 Months', 'pdf-license-manager' ); ?></option>
					<option value="90"><?php esc_html_e( '+90 Days', 'pdf-license-manager' ); ?></option>
					<option value="30"><?php esc_html_e( '+30 Days', 'pdf-license-manager' ); ?></option>
				</select>
				<button type="submit" class="button"><?php esc_html_e( 'Extend', 'pdf-license-manager' ); ?></button>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="plm-inline-form plm-revoke-form">
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
		<div class="plm-table-responsive">
		<table class="widefat fixed striped">
			<caption class="screen-reader-text"><?php esc_html_e( 'List of installations for this license', 'pdf-license-manager' ); ?></caption>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Platform', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Root Domain', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Plugin Version', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Country', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Activated', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Last Check', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $installations as $inst ) : ?>
				<tr class="<?php echo ! $inst->is_active ? 'plm-row-inactive' : ''; ?>">
					<td><span class="plm-badge plm-badge-<?php echo esc_attr( $inst->platform ); ?>"><?php echo esc_html( ucfirst( $inst->platform ) ); ?></span></td>
					<td>
						<code><?php echo esc_html( $inst->site_url ); ?></code>
						<?php if ( $inst->is_local ) : ?>
							<em class="plm-text-muted"><?php esc_html_e( '(local)', 'pdf-license-manager' ); ?></em>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html( $inst->plugin_version ); ?></td>
					<td><?php echo esc_html( $inst->country_code ?? '-' ); ?></td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->activated_at ) ) ); ?></td>
					<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $inst->last_checked_at ) ) ); ?></td>
					<td>
						<?php if ( $inst->is_active ) : ?>
							<span class="plm-text-success"><?php esc_html_e( 'Active', 'pdf-license-manager' ); ?></span>
						<?php else : ?>
							<span class="plm-text-muted"><?php esc_html_e( 'Inactive', 'pdf-license-manager' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php if ( empty( $installations ) ) : ?>
				<tr><td colspan="7"><?php esc_html_e( 'No installations.', 'pdf-license-manager' ); ?></td></tr>
				<?php endif; ?>
			</tbody>
		</table>
		</div>
	</div>

	<!-- Audit Log -->
	<div class="plm-section">
		<h2><?php esc_html_e( 'Activity Log', 'pdf-license-manager' ); ?></h2>
		<div class="plm-table-responsive">
		<table class="widefat fixed striped">
			<caption class="screen-reader-text"><?php esc_html_e( 'Activity log for this license', 'pdf-license-manager' ); ?></caption>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Time', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Event', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Details', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'IP', 'pdf-license-manager' ); ?></th>
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
</div>
