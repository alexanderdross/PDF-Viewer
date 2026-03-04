<?php
/**
 * Email template: License Revocation Notice.
 *
 * Available variables:
 * @var string $license_key   Masked license key.
 * @var string $type_label    License type display label.
 * @var string $plan_label    Plan display label.
 * @var string $reason        Reason for revocation.
 * @var string $customer_name Customer name or 'Customer'.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
Hi <?php echo $customer_name; ?>,

Your PDF Embed & SEO Optimize license has been revoked.

License: <?php echo $license_key; ?>

Plan: <?php echo $plan_label; ?> (<?php echo $type_label; ?>)
Reason: <?php echo $reason; ?>


Premium/Pro+ features have been disabled on all installations associated with this license.

If you believe this is an error or have questions, please contact our support team immediately.

Support: https://pdfviewer.drossmedia.de/support/

- The Dross:Media Team
