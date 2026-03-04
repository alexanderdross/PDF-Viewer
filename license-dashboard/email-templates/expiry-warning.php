<?php
/**
 * Email template: License Expiry Warning.
 *
 * Available variables:
 * @var string $license_key    Masked license key.
 * @var string $type_label     License type display label.
 * @var string $plan_label     Plan display label.
 * @var int    $days_remaining Days until expiration.
 * @var string $expiry_display Expiration date display string.
 * @var string $customer_name  Customer name or 'Customer'.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
Hi <?php echo $customer_name; ?>,

Your PDF Embed & SEO Optimize license is expiring soon.

License: <?php echo $license_key; ?>

Plan: <?php echo $plan_label; ?> (<?php echo $type_label; ?>)
Expires: <?php echo $expiry_display; ?> (in <?php echo $days_remaining; ?> days)

To continue using all <?php echo $type_label; ?> features without interruption, please renew your license before it expires.

Renew now: https://pdfviewer.drossmedia.de/renew/

What happens if your license expires?
  - After expiration, you have a <?php echo defined( 'PLM_GRACE_PERIOD_DAYS' ) ? PLM_GRACE_PERIOD_DAYS : 14; ?>-day grace period.
  - During the grace period, features continue to work but you will see reminders.
  - After the grace period, premium/pro+ features will be disabled.
  - Your data and settings are preserved and will be restored when you renew.

If you have any questions, please contact our support team.

Support: https://pdfviewer.drossmedia.de/support/

Best regards,
The Dross:Media Team
