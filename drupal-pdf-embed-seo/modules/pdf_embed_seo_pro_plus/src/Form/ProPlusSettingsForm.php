<?php

namespace Drupal\pdf_embed_seo_pro_plus\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure PDF Embed SEO Pro+ Enterprise settings.
 */
class ProPlusSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'pdf_embed_seo_pro_plus_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['pdf_embed_seo_pro_plus.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pdf_embed_seo_pro_plus.settings');
    $defaults = pdf_embed_seo_pro_plus_get_settings();

    // License section.
    $form['license'] = [
      '#type' => 'details',
      '#title' => $this->t('License'),
      '#open' => TRUE,
      '#weight' => -100,
    ];

    $is_valid = pdf_embed_seo_pro_plus_is_valid();
    $status_message = $is_valid
      ? $this->t('Your Pro+ Enterprise license is active.')
      : $this->t('Please enter your Pro+ Enterprise license key to activate enterprise features.');

    $form['license']['status_message'] = [
      '#markup' => '<div class="messages ' . ($is_valid ? 'messages--status' : 'messages--warning') . '">' . $status_message . '</div>',
    ];

    $form['license']['license_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('License Key'),
      '#description' => $this->t('Enter your Pro+ Enterprise license key. Get a license at <a href="@url" target="_blank">pdfviewer.drossmedia.de</a>', ['@url' => 'https://pdfviewer.drossmedia.de']),
      '#default_value' => $config->get('license_key') ?? '',
      '#maxlength' => 64,
    ];

    $form['license']['activate_license'] = [
      '#type' => 'submit',
      '#value' => $this->t('Activate License'),
      '#submit' => ['::activateLicense'],
      '#limit_validation_errors' => [['license_key']],
    ];

    if ($is_valid) {
      $form['license']['deactivate_license'] = [
        '#type' => 'submit',
        '#value' => $this->t('Deactivate License'),
        '#submit' => ['::deactivateLicense'],
        '#limit_validation_errors' => [],
      ];
    }

    // Feature toggles.
    $form['features'] = [
      '#type' => 'details',
      '#title' => $this->t('Enterprise Features'),
      '#open' => TRUE,
    ];

    $form['features']['enable_advanced_analytics'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Advanced Analytics'),
      '#description' => $this->t('Enable heatmaps, engagement scoring, and geographic tracking.'),
      '#default_value' => $defaults['enable_advanced_analytics'],
    ];

    $form['features']['enable_annotations'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Annotations'),
      '#description' => $this->t('Allow users to highlight, comment, and annotate PDF documents.'),
      '#default_value' => $defaults['enable_annotations'],
    ];

    $form['features']['enable_versioning'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Document Versioning'),
      '#description' => $this->t('Track document version history with rollback capability.'),
      '#default_value' => $defaults['enable_versioning'],
    ];

    $form['features']['enable_webhooks'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Webhooks'),
      '#description' => $this->t('Send events to external systems when PDFs are viewed, downloaded, etc.'),
      '#default_value' => $defaults['enable_webhooks'],
    ];

    $form['features']['enable_compliance'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Compliance Tools'),
      '#description' => $this->t('GDPR and HIPAA compliance tracking and data management.'),
      '#default_value' => $defaults['enable_compliance'],
    ];

    $form['features']['enable_white_label'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable White Label'),
      '#description' => $this->t('Custom branding and attribution removal.'),
      '#default_value' => $defaults['enable_white_label'],
    ];

    // Security settings.
    $form['security'] = [
      '#type' => 'details',
      '#title' => $this->t('Security'),
      '#open' => FALSE,
    ];

    $form['security']['two_factor_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Two-Factor Authentication'),
      '#description' => $this->t('Require 2FA for accessing sensitive PDF documents.'),
      '#default_value' => $defaults['two_factor_enabled'],
    ];

    $form['security']['ip_whitelist'] = [
      '#type' => 'textarea',
      '#title' => $this->t('IP Whitelist'),
      '#description' => $this->t('One IP address or CIDR range per line. Leave empty to allow all.'),
      '#default_value' => $defaults['ip_whitelist'],
    ];

    $form['security']['audit_log_retention'] = [
      '#type' => 'number',
      '#title' => $this->t('Audit Log Retention (days)'),
      '#description' => $this->t('Number of days to retain audit log entries.'),
      '#default_value' => $defaults['audit_log_retention'],
      '#min' => 30,
      '#max' => 3650,
    ];

    // Versioning settings.
    $form['versioning'] = [
      '#type' => 'details',
      '#title' => $this->t('Versioning'),
      '#open' => FALSE,
    ];

    $form['versioning']['keep_versions'] = [
      '#type' => 'number',
      '#title' => $this->t('Versions to Keep'),
      '#description' => $this->t('Maximum number of versions to keep per document.'),
      '#default_value' => $defaults['keep_versions'],
      '#min' => 1,
      '#max' => 100,
    ];

    $form['versioning']['auto_version'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto-Version on Upload'),
      '#description' => $this->t('Automatically create a new version when a PDF file is replaced.'),
      '#default_value' => $defaults['auto_version'],
    ];

    // Compliance settings.
    $form['compliance'] = [
      '#type' => 'details',
      '#title' => $this->t('Compliance'),
      '#open' => FALSE,
    ];

    $form['compliance']['gdpr_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('GDPR Mode'),
      '#description' => $this->t('Enable GDPR compliance features including consent tracking and data export.'),
      '#default_value' => $defaults['gdpr_mode'],
    ];

    $form['compliance']['hipaa_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('HIPAA Mode'),
      '#description' => $this->t('Enable HIPAA compliance features including enhanced audit logging.'),
      '#default_value' => $defaults['hipaa_mode'],
    ];

    $form['compliance']['data_retention_days'] = [
      '#type' => 'number',
      '#title' => $this->t('Data Retention Period (days)'),
      '#description' => $this->t('Number of days to retain user tracking data.'),
      '#default_value' => $defaults['data_retention_days'],
      '#min' => 30,
      '#max' => 3650,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('pdf_embed_seo_pro_plus.settings')
      ->set('settings', [
        'enable_advanced_analytics' => (bool) $form_state->getValue('enable_advanced_analytics'),
        'enable_annotations' => (bool) $form_state->getValue('enable_annotations'),
        'enable_versioning' => (bool) $form_state->getValue('enable_versioning'),
        'enable_webhooks' => (bool) $form_state->getValue('enable_webhooks'),
        'enable_compliance' => (bool) $form_state->getValue('enable_compliance'),
        'enable_white_label' => (bool) $form_state->getValue('enable_white_label'),
        'two_factor_enabled' => (bool) $form_state->getValue('two_factor_enabled'),
        'ip_whitelist' => $form_state->getValue('ip_whitelist'),
        'audit_log_retention' => (int) $form_state->getValue('audit_log_retention'),
        'keep_versions' => (int) $form_state->getValue('keep_versions'),
        'auto_version' => (bool) $form_state->getValue('auto_version'),
        'gdpr_mode' => (bool) $form_state->getValue('gdpr_mode'),
        'hipaa_mode' => (bool) $form_state->getValue('hipaa_mode'),
        'data_retention_days' => (int) $form_state->getValue('data_retention_days'),
      ])
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Activate license handler.
   */
  public function activateLicense(array &$form, FormStateInterface $form_state) {
    $license_key = $form_state->getValue('license_key');

    if (empty($license_key)) {
      $this->messenger()->addError($this->t('Please enter a license key.'));
      return;
    }

    if (strlen($license_key) < 20) {
      $this->config('pdf_embed_seo_pro_plus.settings')
        ->set('license_key', $license_key)
        ->set('license_status', 'invalid')
        ->save();
      $this->messenger()->addError($this->t('Invalid license key format.'));
      return;
    }

    // Use the LicenseValidator service for remote activation.
    if (\Drupal::hasService('pdf_embed_seo_pro_plus.license_validator')) {
      /** @var \Drupal\pdf_embed_seo_pro_plus\Service\LicenseValidator $validator */
      $validator = \Drupal::service('pdf_embed_seo_pro_plus.license_validator');
      $result = $validator->activate($license_key);

      if ($result['success']) {
        $this->messenger()->addStatus($this->t('Pro+ Enterprise license activated successfully!'));
      }
      else {
        $this->messenger()->addError($this->t('License activation failed: @message', ['@message' => $result['message'] ?? 'Unknown error']));
      }
      return;
    }

    // Fallback: local validation.
    if (preg_match('/^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key)) {
      $this->config('pdf_embed_seo_pro_plus.settings')
        ->set('license_key', $license_key)
        ->set('license_status', 'valid')
        ->set('license_expires', date('Y-m-d H:i:s', strtotime('+1 year')))
        ->save();
      $this->messenger()->addStatus($this->t('Pro+ Enterprise license activated successfully!'));
      return;
    }

    // Also accept unlimited/dev keys.
    $test_patterns = [
      '/^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i',
      '/^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i',
    ];
    foreach ($test_patterns as $pattern) {
      if (preg_match($pattern, $license_key)) {
        $this->config('pdf_embed_seo_pro_plus.settings')
          ->set('license_key', $license_key)
          ->set('license_status', 'valid')
          ->set('license_expires', NULL)
          ->save();
        $this->messenger()->addStatus($this->t('Pro+ Enterprise license activated successfully!'));
        return;
      }
    }

    $this->config('pdf_embed_seo_pro_plus.settings')
      ->set('license_key', $license_key)
      ->set('license_status', 'invalid')
      ->save();
    $this->messenger()->addError($this->t('Invalid license key. Pro+ keys should start with PDF$PRO+#'));
  }

  /**
   * Deactivate license handler.
   */
  public function deactivateLicense(array &$form, FormStateInterface $form_state) {
    if (\Drupal::hasService('pdf_embed_seo_pro_plus.license_validator')) {
      /** @var \Drupal\pdf_embed_seo_pro_plus\Service\LicenseValidator $validator */
      $validator = \Drupal::service('pdf_embed_seo_pro_plus.license_validator');
      $validator->deactivate();
    }
    else {
      $this->config('pdf_embed_seo_pro_plus.settings')
        ->set('license_key', '')
        ->set('license_status', 'inactive')
        ->set('license_expires', NULL)
        ->save();
    }

    $this->messenger()->addStatus($this->t('Pro+ Enterprise license deactivated.'));
  }

}
