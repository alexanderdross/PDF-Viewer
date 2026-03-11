<?php

namespace Drupal\pdf_embed_seo_premium\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * License validation service for Premium with remote API support.
 */
class LicenseValidator {

  /**
   * License API base URL.
   */
  const LICENSE_API_URL = 'https://pdfviewer.drossmedia.de/wp-json/plm/v1';

  /**
   * Valid license statuses.
   */
  const STATUS_VALID = 'valid';
  const STATUS_INVALID = 'invalid';
  const STATUS_EXPIRED = 'expired';
  const STATUS_GRACE_PERIOD = 'grace_period';
  const STATUS_INACTIVE = 'inactive';

  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * State service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a LicenseValidator object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    StateInterface $state,
    ClientInterface $http_client,
    LoggerInterface $logger
  ) {
    $this->configFactory = $config_factory;
    $this->state = $state;
    $this->httpClient = $http_client;
    $this->logger = $logger;
  }

  /**
   * Validate the license key via remote API with local fallback.
   *
   * @param string|null $license_key
   *   Optional license key to validate. Uses stored key if not provided.
   *
   * @return string
   *   The license status.
   */
  public function validate(?string $license_key = NULL): string {
    if ($license_key === NULL) {
      $config = $this->configFactory->get('pdf_embed_seo_premium.settings');
      $license_key = $config->get('license_key') ?? '';
    }

    if (empty($license_key)) {
      return self::STATUS_INACTIVE;
    }

    // Attempt remote validation.
    try {
      $response = $this->httpClient->request('POST', self::LICENSE_API_URL . '/license/validate', [
        'form_params' => [
          'license_key' => $license_key,
          'platform' => 'drupal',
        ],
        'timeout' => 15,
      ]);

      $data = json_decode($response->getBody()->getContents(), TRUE);

      if (!empty($data['valid'])) {
        return self::STATUS_VALID;
      }

      return $data['status'] ?? self::STATUS_INVALID;
    }
    catch (\Exception $e) {
      $this->logger->notice('License remote validation failed, using local fallback: @message', [
        '@message' => $e->getMessage(),
      ]);
      // Network failure: fall back to local validation.
      return $this->validateLocally($license_key);
    }
  }

  /**
   * Local-only license validation (fallback when API is unreachable).
   *
   * @param string $license_key
   *   The license key.
   *
   * @return string
   *   The license status.
   */
  protected function validateLocally(string $license_key): string {
    // Test/Development keys.
    if (preg_match('/^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key)) {
      return self::STATUS_VALID;
    }
    if (preg_match('/^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key)) {
      return self::STATUS_VALID;
    }

    // Standard Premium license.
    if (preg_match('/^PDF\$PRO#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key)) {
      return $this->checkExpiration('pdf_embed_seo_premium.settings');
    }

    // Pro+ license (superset of Premium, so also valid here).
    if (preg_match('/^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key)) {
      return $this->checkExpiration('pdf_embed_seo_premium.settings');
    }

    return self::STATUS_INVALID;
  }

  /**
   * Check license expiration.
   *
   * @param string $config_name
   *   The config name to read expiration from.
   *
   * @return string
   *   The license status based on expiration.
   */
  protected function checkExpiration(string $config_name = 'pdf_embed_seo_premium.settings'): string {
    $config = $this->configFactory->get($config_name);
    $expires = $config->get('license_expires');

    if (empty($expires)) {
      return self::STATUS_VALID;
    }

    $expiry_time = strtotime($expires);
    $now = time();

    if ($expiry_time > $now) {
      return self::STATUS_VALID;
    }

    // 14-day grace period.
    $grace_end = strtotime($expires . ' +14 days');
    if ($grace_end > $now) {
      return self::STATUS_GRACE_PERIOD;
    }

    return self::STATUS_EXPIRED;
  }

  /**
   * Check if the license is valid.
   *
   * @return bool
   *   TRUE if license is valid or in grace period.
   */
  public function isValid(): bool {
    $status = $this->validate();
    return in_array($status, [self::STATUS_VALID, self::STATUS_GRACE_PERIOD], TRUE);
  }

  /**
   * Get the current license status.
   *
   * @return string
   *   The license status.
   */
  public function getStatus(): string {
    return $this->validate();
  }

  /**
   * Activate a license key (remote + local).
   *
   * @param string $license_key
   *   The license key to activate.
   *
   * @return array
   *   Result with 'success' and 'message' keys.
   */
  public function activate(string $license_key): array {
    $status = $this->validate($license_key);

    if ($status === self::STATUS_INVALID) {
      return [
        'success' => FALSE,
        'message' => 'Invalid license key format.',
      ];
    }

    // Store the license key locally.
    $config = $this->configFactory->getEditable('pdf_embed_seo_premium.settings');
    $config->set('license_key', $license_key);
    $config->set('license_status', $status);
    $config->set('license_activated', date('Y-m-d H:i:s'));

    // Attempt remote activation.
    $remote_data = $this->activateRemote($license_key);
    if ($remote_data) {
      if (!empty($remote_data['expires_at'])) {
        $config->set('license_expires', date('Y-m-d H:i:s', strtotime($remote_data['expires_at'])));
      }
      $config->set('license_type', $remote_data['type'] ?? 'premium');
      $config->set('license_plan', $remote_data['plan'] ?? 'starter');
    }
    else {
      // Remote failed — set 1-year local expiry for standard keys.
      if (!preg_match('/^PDF\$(UNLIMITED|DEV)#/i', $license_key)) {
        $config->set('license_expires', date('Y-m-d H:i:s', strtotime('+1 year')));
      }
    }

    $config->save();
    $this->state->set('pdf_embed_seo_premium.license_valid', TRUE);
    $this->state->set('pdf_embed_seo_premium.last_license_check', time());

    return [
      'success' => TRUE,
      'message' => 'License activated successfully.',
      'status' => $status,
    ];
  }

  /**
   * Activate this site with the License Dashboard (remote).
   *
   * @param string $license_key
   *   The license key.
   *
   * @return array|null
   *   The activation response data or NULL on failure.
   */
  protected function activateRemote(string $license_key): ?array {
    try {
      $module_info = \Drupal::service('extension.list.module')
        ->getExtensionInfo('pdf_embed_seo_premium');
      $plugin_version = $module_info['version'] ?? '1.2.11';

      $response = $this->httpClient->request('POST', self::LICENSE_API_URL . '/license/activate', [
        'form_params' => [
          'license_key' => $license_key,
          'site_url' => \Drupal::request()->getSchemeAndHttpHost(),
          'platform' => 'drupal',
          'plugin_version' => $plugin_version,
          'php_version' => phpversion(),
          'cms_version' => \Drupal::VERSION,
        ],
        'timeout' => 15,
      ]);

      return json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (\Exception $e) {
      $this->logger->notice('License remote activation failed: @message', [
        '@message' => $e->getMessage(),
      ]);
      return NULL;
    }
  }

  /**
   * Deactivate the current license (remote + local).
   *
   * @return array
   *   Result with 'success' and 'message' keys.
   */
  public function deactivate(): array {
    $config = $this->configFactory->getEditable('pdf_embed_seo_premium.settings');
    $license_key = $config->get('license_key') ?? '';

    // Deactivate remotely first.
    if (!empty($license_key)) {
      $this->deactivateRemote($license_key);
    }

    $config->set('license_key', '');
    $config->set('license_status', self::STATUS_INACTIVE);
    $config->set('license_expires', NULL);
    $config->set('license_deactivated', date('Y-m-d H:i:s'));
    $config->save();

    $this->state->set('pdf_embed_seo_premium.license_valid', FALSE);

    return [
      'success' => TRUE,
      'message' => 'License deactivated successfully.',
    ];
  }

  /**
   * Deactivate this site from the License Dashboard (remote).
   *
   * @param string $license_key
   *   The license key.
   */
  protected function deactivateRemote(string $license_key): void {
    try {
      $this->httpClient->request('POST', self::LICENSE_API_URL . '/license/deactivate', [
        'form_params' => [
          'license_key' => $license_key,
          'site_url' => \Drupal::request()->getSchemeAndHttpHost(),
        ],
        'timeout' => 10,
      ]);
    }
    catch (\Exception $e) {
      $this->logger->notice('License remote deactivation failed: @message', [
        '@message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Daily heartbeat check against the License Dashboard API.
   *
   * @return bool
   *   TRUE if the check was successful.
   */
  public function heartbeatCheck(): bool {
    $config = $this->configFactory->get('pdf_embed_seo_premium.settings');
    $license_key = $config->get('license_key') ?? '';

    if (empty($license_key)) {
      return FALSE;
    }

    try {
      $module_info = \Drupal::service('extension.list.module')
        ->getExtensionInfo('pdf_embed_seo_premium');
      $plugin_version = $module_info['version'] ?? '1.2.11';

      $response = $this->httpClient->request('POST', self::LICENSE_API_URL . '/license/check', [
        'form_params' => [
          'license_key' => $license_key,
          'site_url' => \Drupal::request()->getSchemeAndHttpHost(),
          'plugin_version' => $plugin_version,
          'platform' => 'drupal',
        ],
        'timeout' => 15,
      ]);

      $data = json_decode($response->getBody()->getContents(), TRUE);
      $this->state->set('pdf_embed_seo_premium.last_license_check', time());

      if (isset($data['valid'])) {
        $status = $data['valid'] ? self::STATUS_VALID : ($data['status'] ?? self::STATUS_EXPIRED);
        $editable = $this->configFactory->getEditable('pdf_embed_seo_premium.settings');
        $editable->set('license_status', $status);

        if (!empty($data['expires_at'])) {
          $editable->set('license_expires', date('Y-m-d H:i:s', strtotime($data['expires_at'])));
        }

        $editable->save();
        $this->state->set('pdf_embed_seo_premium.license_valid', $data['valid']);
      }

      return TRUE;
    }
    catch (\Exception $e) {
      $this->logger->notice('License heartbeat check failed: @message', [
        '@message' => $e->getMessage(),
      ]);
      return FALSE;
    }
  }

  /**
   * Get license information.
   *
   * @return array
   *   License information array.
   */
  public function getInfo(): array {
    $config = $this->configFactory->get('pdf_embed_seo_premium.settings');

    return [
      'key' => $this->maskLicenseKey($config->get('license_key') ?? ''),
      'status' => $this->validate(),
      'activated' => $config->get('license_activated'),
      'expires' => $config->get('license_expires'),
      'type' => $config->get('license_type') ?? 'premium',
      'plan' => $config->get('license_plan') ?? '',
    ];
  }

  /**
   * Mask a license key for display.
   *
   * @param string $key
   *   The license key.
   *
   * @return string
   *   The masked key.
   */
  protected function maskLicenseKey(string $key): string {
    if (strlen($key) <= 10) {
      return str_repeat('*', strlen($key));
    }

    return substr($key, 0, 6) . str_repeat('*', strlen($key) - 10) . substr($key, -4);
  }

}
