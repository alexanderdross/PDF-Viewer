<?php
/**
 * Email template: License Renewal Confirmation.
 *
 * Available variables:
 * @var string $license_key    Masked license key.
 * @var string $type_label     License type display label.
 * @var string $plan_label     Plan display label.
 * @var string $expiry_display New expiration date display string.
 * @var string $customer_name  Customer name or 'Customer'.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
Hi <?php echo $customer_name; ?>,

Your PDF Embed & SEO Optimize license has been renewed successfully!

License: <?php echo $license_key; ?>

Plan: <?php echo $plan_label; ?> (<?php echo $type_label; ?>)
New Expiration: <?php echo $expiry_display; ?>


No action is required on your part. Your license will continue to work seamlessly across all your installations.

If you have any questions, please contact our support team.

Documentation: https://pdfviewer.drossmedia.de/documentation/
Support: https://pdfviewer.drossmedia.de/support/

Thank you for your continued support!
- The Dross:Media Team
