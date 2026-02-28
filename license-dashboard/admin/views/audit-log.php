<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap plm-wrap">
	<h1><?php esc_html_e( 'Audit Log', 'pdf-license-manager' ); ?></h1>

	<table class="widefat fixed striped plm-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Time', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Event', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'License', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'Details', 'pdf-license-manager' ); ?></th>
				<th><?php esc_html_e( 'IP', 'pdf-license-manager' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $logs ) ) : ?>
				<tr><td colspan="5"><?php esc_html_e( 'No audit log entries.', 'pdf-license-manager' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $logs as $log ) : ?>
				<tr>
					<td><?php echo esc_html( wp_date( 'd.m.Y H:i:s', strtotime( $log->created_at ) ) ); ?></td>
					<td><span class="plm-badge"><?php echo esc_html( $log->event_type ); ?></span></td>
					<td>
						<?php if ( $log->license_key ) : ?>
							<code><?php echo esc_html( PLM_License::mask_key( $log->license_key ) ); ?></code>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
					<td><small><?php echo esc_html( mb_strimwidth( $log->details ?? '', 0, 120, '...' ) ); ?></small></td>
					<td><?php echo esc_html( $log->ip_address ?: '-' ); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
