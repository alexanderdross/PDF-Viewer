<?php
/**
 * Email template: License Purchase Confirmation.
 *
 * Available variables:
 * @var string $license_key    Full license key.
 * @var string $type_label     License type display label.
 * @var string $plan_label     Plan display label.
 * @var string $site_limit     Site limit display string.
 * @var string $expiry_display Expiration date display string.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
Thank you for purchasing PDF Embed & SEO Optimize <?php echo $type_label; ?>!

Here is your license key:

    <?php echo $license_key; ?>


License Details:
  Plan: <?php echo $plan_label; ?>

  Type: <?php echo $type_label; ?>

  Sites: <?php echo $site_limit; ?>

  Valid until: <?php echo $expiry_display; ?>


Activation Instructions:

  WordPress:
    1. Go to PDF Documents > License (or Pro+ License)
    2. Paste your license key and click Save

  Drupal:
    1. Go to Configuration > PDF Embed SEO > Premium Settings
    2. Paste your license key and click Activate License

  React / Next.js:
    1. Add PDF_LICENSE_KEY to your .env file
    2. Use the useLicense() hook or server-side validation

Documentation: https://pdfviewer.drossmedia.de/documentation/
Support: https://pdfviewer.drossmedia.de/support/

Thank you for your purchase!
- The Dross:Media Team
