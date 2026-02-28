<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Licenses', 'pdf-license-manager' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&action=new' ) ); ?>" class="page-title-action">
		<?php esc_html_e( 'Add New', 'pdf-license-manager' ); ?>
	</a>

	<?php if ( isset( $_GET['action'] ) && 'new' === $_GET['action'] ) : ?>
		<!-- Create License Form -->
		<div class="plm-section" style="max-width: 600px;">
			<h2><?php esc_html_e( 'Create New License', 'pdf-license-manager' ); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'plm_create_license' ); ?>
				<input type="hidden" name="action" value="plm_create_license">
				<table class="form-table">
					<tr>
						<th><label for="license_type"><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="license_type" id="license_type">
								<option value="premium">Premium</option>
								<option value="pro_plus">Pro+ Enterprise</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="plan"><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="plan" id="plan">
								<option value="starter">Starter (1 Site)</option>
								<option value="professional">Professional (5 Sites)</option>
								<option value="agency">Agency (Unlimited)</option>
								<option value="enterprise">Enterprise (Unlimited)</option>
								<option value="dev">Development</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="customer_email"><?php esc_html_e( 'Customer Email', 'pdf-license-manager' ); ?></label></th>
						<td><input type="email" name="customer_email" id="customer_email" class="regular-text" required></td>
					</tr>
					<tr>
						<th><label for="customer_name"><?php esc_html_e( 'Customer Name', 'pdf-license-manager' ); ?></label></th>
						<td><input type="text" name="customer_name" id="customer_name" class="regular-text"></td>
					</tr>
					<tr>
						<th><label for="site_limit"><?php esc_html_e( 'Site Limit', 'pdf-license-manager' ); ?></label></th>
						<td><input type="number" name="site_limit" id="site_limit" value="1" min="0" class="small-text"> <span class="description">0 = Unlimited</span></td>
					</tr>
					<tr>
						<th><label for="duration"><?php esc_html_e( 'Duration', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="duration" id="duration">
								<option value="365">1 Year (365 days)</option>
								<option value="730">2 Years (730 days)</option>
								<option value="lifetime">Lifetime</option>
								<option value="90">90 Days (Trial)</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="notes"><?php esc_html_e( 'Notes', 'pdf-license-manager' ); ?></label></th>
						<td><textarea name="notes" id="notes" rows="3" class="large-text"></textarea></td>
					</tr>
				</table>
				<?php submit_button( __( 'Create License', 'pdf-license-manager' ) ); ?>
			</form>
		</div>
	<?php else : ?>
		<!-- Filter Bar -->
		<form method="get" class="plm-filter-bar">
			<input type="hidden" name="page" value="plm-licenses">
			<select name="status">
				<option value=""><?php esc_html_e( 'All Statuses', 'pdf-license-manager' ); ?></option>
				<option value="active" <?php selected( $status_filter, 'active' ); ?>>Active</option>
				<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>>Inactive</option>
				<option value="expired" <?php selected( $status_filter, 'expired' ); ?>>Expired</option>
				<option value="grace_period" <?php selected( $status_filter, 'grace_period' ); ?>>Grace Period</option>
				<option value="revoked" <?php selected( $status_filter, 'revoked' ); ?>>Revoked</option>
			</select>
			<select name="type">
				<option value=""><?php esc_html_e( 'All Types', 'pdf-license-manager' ); ?></option>
				<option value="premium" <?php selected( $type_filter, 'premium' ); ?>>Premium</option>
				<option value="pro_plus" <?php selected( $type_filter, 'pro_plus' ); ?>>Pro+</option>
			</select>
			<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search key or email...', 'pdf-license-manager' ); ?>">
			<?php submit_button( __( 'Filter', 'pdf-license-manager' ), 'secondary', 'submit', false ); ?>
		</form>

		<!-- Licenses Table -->
		<table class="widefat fixed striped plm-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Sites', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Email', 'pdf-license-manager' ); ?></th>
					<th><?php esc_html_e( 'Created', 'pdf-license-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $licenses ) ) : ?>
					<tr><td colspan="8"><?php esc_html_e( 'No licenses found.', 'pdf-license-manager' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $licenses as $lic ) :
						$days = PLM_License::days_remaining( $lic->expires_at );
						$active_sites = PLM_License::count_active_sites( (int) $lic->id );
					?>
					<tr>
						<td>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&id=' . $lic->id ) ); ?>">
								<code><?php echo esc_html( PLM_License::mask_key( $lic->license_key ) ); ?></code>
							</a>
						</td>
						<td><span class="plm-badge plm-badge-<?php echo esc_attr( $lic->license_type ); ?>"><?php echo 'pro_plus' === $lic->license_type ? 'Pro+' : 'Premium'; ?></span></td>
						<td><?php echo esc_html( ucfirst( $lic->plan ) ); ?></td>
						<td><span class="plm-badge plm-badge-<?php echo esc_attr( $lic->status ); ?>"><?php echo esc_html( str_replace( '_', ' ', $lic->status ) ); ?></span></td>
						<td><?php echo esc_html( $active_sites . '/' . ( 0 === (int) $lic->site_limit ? "\u{221E}" : $lic->site_limit ) ); ?></td>
						<td>
							<?php if ( null === $days ) : ?>
								<span class="plm-text-success">Lifetime</span>
							<?php elseif ( $days <= 0 ) : ?>
								<span class="plm-text-danger"><?php echo esc_html( $days ); ?> <?php esc_html_e( 'days', 'pdf-license-manager' ); ?></span>
							<?php elseif ( $days <= 14 ) : ?>
								<span class="plm-text-warning"><?php echo esc_html( $days ); ?> <?php esc_html_e( 'days', 'pdf-license-manager' ); ?></span>
							<?php else : ?>
								<?php echo esc_html( $days ); ?> <?php esc_html_e( 'days', 'pdf-license-manager' ); ?>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $lic->customer_email ); ?></td>
						<td><?php echo esc_html( wp_date( 'd.m.Y', strtotime( $lic->created_at ) ) ); ?></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
