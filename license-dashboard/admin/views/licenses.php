<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Licenses', 'pdf-license-manager' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=plm-licenses&action=new' ) ); ?>" class="page-title-action">
		<?php esc_html_e( 'Add New', 'pdf-license-manager' ); ?>
	</a>

	<?php if ( isset( $_GET['action'] ) && 'new' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) : ?>
		<!-- Create License Form -->
		<div class="plm-section" style="max-width: 600px;">
			<h2><?php esc_html_e( 'Create New License', 'pdf-license-manager' ); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'plm_create_license' ); ?>
				<input type="hidden" name="action" value="plm_create_license">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="license_type"><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="license_type" id="license_type">
								<option value="premium"><?php esc_html_e( 'Premium', 'pdf-license-manager' ); ?></option>
								<option value="pro_plus"><?php esc_html_e( 'Pro+ Enterprise', 'pdf-license-manager' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="plan"><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="plan" id="plan">
								<option value="starter"><?php esc_html_e( 'Starter (1 Site)', 'pdf-license-manager' ); ?></option>
								<option value="professional"><?php esc_html_e( 'Professional (5 Sites)', 'pdf-license-manager' ); ?></option>
								<option value="agency"><?php esc_html_e( 'Agency (Unlimited)', 'pdf-license-manager' ); ?></option>
								<option value="enterprise"><?php esc_html_e( 'Enterprise (Unlimited)', 'pdf-license-manager' ); ?></option>
								<option value="dev"><?php esc_html_e( 'Development', 'pdf-license-manager' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="customer_email"><?php esc_html_e( 'Customer Email', 'pdf-license-manager' ); ?></label></th>
						<td><input type="email" name="customer_email" id="customer_email" class="regular-text" required></td>
					</tr>
					<tr>
						<th scope="row"><label for="customer_name"><?php esc_html_e( 'Customer Name', 'pdf-license-manager' ); ?></label></th>
						<td><input type="text" name="customer_name" id="customer_name" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="site_limit"><?php esc_html_e( 'Site Limit', 'pdf-license-manager' ); ?></label></th>
						<td><input type="number" name="site_limit" id="site_limit" value="1" min="0" class="small-text"> <span class="description"><?php esc_html_e( '0 = Unlimited', 'pdf-license-manager' ); ?></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="duration"><?php esc_html_e( 'Duration', 'pdf-license-manager' ); ?></label></th>
						<td>
							<select name="duration" id="duration">
								<option value="365"><?php esc_html_e( '1 Year (365 days)', 'pdf-license-manager' ); ?></option>
								<option value="730"><?php esc_html_e( '2 Years (730 days)', 'pdf-license-manager' ); ?></option>
								<option value="lifetime"><?php esc_html_e( 'Lifetime', 'pdf-license-manager' ); ?></option>
								<option value="90"><?php esc_html_e( '90 Days (Trial)', 'pdf-license-manager' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="notes"><?php esc_html_e( 'Notes', 'pdf-license-manager' ); ?></label></th>
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
			<label for="plm-filter-status" class="screen-reader-text"><?php esc_html_e( 'Filter by status', 'pdf-license-manager' ); ?></label>
			<select name="status" id="plm-filter-status">
				<option value=""><?php esc_html_e( 'All Statuses', 'pdf-license-manager' ); ?></option>
				<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'pdf-license-manager' ); ?></option>
				<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'pdf-license-manager' ); ?></option>
				<option value="expired" <?php selected( $status_filter, 'expired' ); ?>><?php esc_html_e( 'Expired', 'pdf-license-manager' ); ?></option>
				<option value="grace_period" <?php selected( $status_filter, 'grace_period' ); ?>><?php esc_html_e( 'Grace Period', 'pdf-license-manager' ); ?></option>
				<option value="revoked" <?php selected( $status_filter, 'revoked' ); ?>><?php esc_html_e( 'Revoked', 'pdf-license-manager' ); ?></option>
			</select>
			<label for="plm-filter-type" class="screen-reader-text"><?php esc_html_e( 'Filter by type', 'pdf-license-manager' ); ?></label>
			<select name="type" id="plm-filter-type">
				<option value=""><?php esc_html_e( 'All Types', 'pdf-license-manager' ); ?></option>
				<option value="premium" <?php selected( $type_filter, 'premium' ); ?>><?php esc_html_e( 'Premium', 'pdf-license-manager' ); ?></option>
				<option value="pro_plus" <?php selected( $type_filter, 'pro_plus' ); ?>><?php esc_html_e( 'Pro+', 'pdf-license-manager' ); ?></option>
			</select>
			<label for="plm-search" class="screen-reader-text"><?php esc_html_e( 'Search licenses', 'pdf-license-manager' ); ?></label>
			<input type="search" name="s" id="plm-search" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search key or email...', 'pdf-license-manager' ); ?>">
			<?php submit_button( __( 'Filter', 'pdf-license-manager' ), 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( isset( $total_licenses ) && $total_licenses > 0 ) : ?>
		<p class="plm-result-count">
			<?php
			printf(
				/* translators: 1: Number of licenses shown, 2: Total number of licenses */
				esc_html__( 'Showing %1$d of %2$d licenses.', 'pdf-license-manager' ),
				count( $licenses ),
				$total_licenses
			);
			?>
		</p>
		<?php endif; ?>

		<!-- Licenses Table -->
		<div class="plm-table-responsive">
		<table class="widefat fixed striped plm-table">
			<caption class="screen-reader-text"><?php esc_html_e( 'List of licenses', 'pdf-license-manager' ); ?></caption>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'License Key', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Type', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Plan', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Status', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Sites', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Days Remaining', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Email', 'pdf-license-manager' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Created', 'pdf-license-manager' ); ?></th>
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
						<td><span class="plm-badge plm-badge-<?php echo esc_attr( $lic->license_type ); ?>"><?php echo esc_html( 'pro_plus' === $lic->license_type ? __( 'Pro+', 'pdf-license-manager' ) : __( 'Premium', 'pdf-license-manager' ) ); ?></span></td>
						<td><?php echo esc_html( ucfirst( $lic->plan ) ); ?></td>
						<td><span class="plm-badge plm-badge-<?php echo esc_attr( $lic->status ); ?>"><?php echo esc_html( str_replace( '_', ' ', $lic->status ) ); ?></span></td>
						<td><?php echo esc_html( $active_sites . '/' . ( 0 === (int) $lic->site_limit ? "\u{221E}" : $lic->site_limit ) ); ?></td>
						<td>
							<?php if ( null === $days ) : ?>
								<span class="plm-text-success"><?php esc_html_e( 'Lifetime', 'pdf-license-manager' ); ?></span>
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
		</div>

		<?php if ( ! empty( $pagination_links ) ) : ?>
		<div class="plm-pagination">
			<?php echo $pagination_links; // Already escaped by paginate_links(). ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
