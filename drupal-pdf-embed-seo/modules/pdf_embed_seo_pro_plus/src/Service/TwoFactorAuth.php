<?php

namespace Drupal\pdf_embed_seo_pro_plus\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Drupal\user\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Two-factor authentication service for Pro+ Enterprise.
 */
class TwoFactorAuth {

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;

  /**
   * The 2FA methods.
   */
  const METHOD_EMAIL = 'email';
  const METHOD_SMS = 'sms';
  const METHOD_TOTP = 'totp';

  /**
   * Token expiration in seconds (10 minutes).
   */
  const TOKEN_EXPIRATION = 600;

  /**
   * Constructs a TwoFactorAuth object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Password\PasswordInterface $password
   *   The password service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   The logger factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Mail\MailManagerInterface $mailManager
   *   The mail manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    protected Connection $database,
    protected AccountProxyInterface $currentUser,
    protected PasswordInterface $password,
    LoggerChannelFactoryInterface $loggerFactory,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected MailManagerInterface $mailManager,
    protected ConfigFactoryInterface $configFactory,
    protected RequestStack $requestStack,
  ) {
    $this->logger = $loggerFactory->get('pdf_embed_seo_pro_plus');
  }

  /**
   * Generate a 2FA token for a user.
   *
   * @param int $user_id
   *   The user ID.
   * @param string $method
   *   The 2FA method.
   *
   * @return string|false
   *   The generated token or FALSE on failure.
   */
  public function generateToken(int $user_id, string $method = self::METHOD_EMAIL): string|false {
    $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $hashed_token = $this->password->hash($token);

    try {
      // Invalidate any existing tokens.
      $this->database->update('pdf_2fa_tokens')
        ->fields(['used_at' => date('Y-m-d H:i:s')])
        ->condition('user_id', $user_id)
        ->isNull('used_at')
        ->execute();

      // Insert new token.
      $this->database->insert('pdf_2fa_tokens')
        ->fields(
                [
                  'user_id' => $user_id,
                  'token' => $hashed_token,
                  'method' => $method,
                  'expires_at' => date('Y-m-d H:i:s', time() + self::TOKEN_EXPIRATION),
                  'created_at' => date('Y-m-d H:i:s'),
                ]
            )
        ->execute();

      return $token;
    }
    catch (\Exception $e) {
      $this->logger->error(
            'Failed to generate 2FA token: @message',
            ['@message' => $e->getMessage()],
        );
      return FALSE;
    }
  }

  /**
   * Verify a 2FA token.
   *
   * @param int $user_id
   *   The user ID.
   * @param string $token
   *   The token to verify.
   *
   * @return bool
   *   TRUE if valid.
   */
  public function verifyToken(int $user_id, string $token): bool {
    try {
      $query = $this->database->select('pdf_2fa_tokens', 't')
        ->fields('t', ['id', 'token', 'expires_at'])
        ->condition('user_id', $user_id)
        ->isNull('used_at')
        ->condition('expires_at', date('Y-m-d H:i:s'), '>')
        ->orderBy('created_at', 'DESC')
        ->range(0, 1);

      $result = $query->execute()->fetchAssoc();

      if (!$result) {
        return FALSE;
      }

      if ($this->password->check($token, $result['token'])) {
        $this->database->update('pdf_2fa_tokens')
          ->fields(['used_at' => date('Y-m-d H:i:s')])
          ->condition('id', $result['id'])
          ->execute();

        return TRUE;
      }

      return FALSE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * Send 2FA token via email.
   *
   * @param int $user_id
   *   The user ID.
   *
   * @return bool
   *   TRUE if sent successfully.
   */
  public function sendEmailToken(int $user_id): bool {
    $token = $this->generateToken($user_id, self::METHOD_EMAIL);
    if (!$token) {
      return FALSE;
    }

    try {
      $user = $this->entityTypeManager->getStorage('user')->load($user_id);
      if (!$user instanceof UserInterface) {
        return FALSE;
      }

      $result = $this->mailManager->mail(
            'pdf_embed_seo_pro_plus',
            '2fa_token',
            $user->getEmail(),
            $user->getPreferredLangcode(),
            [
              'token' => $token,
              'user' => $user,
              'expires_in' => self::TOKEN_EXPIRATION / 60,
            ],
        );

      return (bool) $result['result'];
    }
    catch (\Exception $e) {
      $this->logger->error(
            'Failed to send 2FA email: @message',
            ['@message' => $e->getMessage()],
        );
      return FALSE;
    }
  }

  /**
   * Check if 2FA is required for a document.
   *
   * @param int $document_id
   *   The document ID.
   *
   * @return bool
   *   TRUE if 2FA is required.
   */
  public function isRequiredForDocument(int $document_id): bool {
    $config = $this->configFactory->get('pdf_embed_seo_pro_plus.settings');

    if (!$config->get('two_factor_enabled')) {
      return FALSE;
    }

    try {
      $document = $this->entityTypeManager->getStorage('pdf_document')->load($document_id);
      if ($document instanceof PdfDocumentInterface && $document->hasField('require_2fa')) {
        return (bool) $document->get('require_2fa')->value;
      }
    }
    catch (\Exception $e) {
      // Ignore and use global setting.
    }

    return (bool) $config->get('two_factor_required_all');
  }

  /**
   * Check if user has passed 2FA for a document.
   *
   * @param int $user_id
   *   The user ID.
   * @param int $document_id
   *   The document ID.
   *
   * @return bool
   *   TRUE if passed.
   */
  public function hasPassedForDocument(int $user_id, int $document_id): bool {
    $session_key = "pdf_2fa_passed_{$document_id}";

    $request = $this->requestStack->getCurrentRequest();
    if ($request && $request->hasSession()) {
      $session = $request->getSession();
      if ($session->has($session_key)) {
        $passed_at = $session->get($session_key);
        if (time() - $passed_at < 86400) {
          return TRUE;
        }
      }
    }

    return FALSE;
  }

  /**
   * Mark 2FA as passed for a document.
   *
   * @param int $user_id
   *   The user ID.
   * @param int $document_id
   *   The document ID.
   */
  public function markPassedForDocument(int $user_id, int $document_id): void {
    $session_key = "pdf_2fa_passed_{$document_id}";
    $request = $this->requestStack->getCurrentRequest();
    if ($request && $request->hasSession()) {
      $request->getSession()->set($session_key, time());
    }
  }

  /**
   * Cleanup expired tokens.
   *
   * @return int
   *   Number of deleted tokens.
   */
  public function cleanup(): int {
    try {
      return $this->database->delete('pdf_2fa_tokens')
        ->condition('expires_at', date('Y-m-d H:i:s'), '<')
        ->execute();
    }
    catch (\Exception $e) {
      return 0;
    }
  }

  /**
   * Generate TOTP secret for a user.
   *
   * @param int $user_id
   *   The user ID.
   *
   * @return string
   *   Base32 encoded secret.
   */
  public function generateTotpSecret(int $user_id): string {
    $secret = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    for ($i = 0; $i < 16; $i++) {
      $secret .= $chars[random_int(0, 31)];
    }

    try {
      $this->database->merge('pdf_2fa_secrets')
        ->keys(['user_id' => $user_id])
        ->fields(
                [
                  'secret' => $secret,
                  'updated_at' => date('Y-m-d H:i:s'),
                ]
            )
        ->execute();
    }
    catch (\Exception $e) {
      // Table might not exist, ignore.
    }

    return $secret;
  }

  /**
   * Verify TOTP code.
   *
   * @param int $user_id
   *   The user ID.
   * @param string $code
   *   The TOTP code.
   *
   * @return bool
   *   TRUE if valid.
   */
  public function verifyTotp(int $user_id, string $code): bool {
    try {
      $query = $this->database->select('pdf_2fa_secrets', 's')
        ->fields('s', ['secret'])
        ->condition('user_id', $user_id);

      $secret = $query->execute()->fetchField();
      if (!$secret) {
        return FALSE;
      }

      // Verify TOTP (allow 1 time step drift).
      for ($drift = -1; $drift <= 1; $drift++) {
        $expected = $this->generateTotp($secret, $drift);
        if (hash_equals($expected, $code)) {
          return TRUE;
        }
      }

      return FALSE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * Generate TOTP code.
   *
   * @param string $secret
   *   Base32 encoded secret.
   * @param int $drift
   *   Time step drift.
   *
   * @return string
   *   6-digit TOTP code.
   */
  protected function generateTotp(string $secret, int $drift = 0): string {
    $timestamp = floor(time() / 30) + $drift;
    $binary_secret = $this->base32Decode($secret);

    $time = pack('N*', 0) . pack('N*', $timestamp);
    $hash = hash_hmac('sha1', $time, $binary_secret, TRUE);
    $offset = ord(substr($hash, -1)) & 0x0F;
    $code = (
      ((ord($hash[$offset]) & 0x7F) << 24) |
      ((ord($hash[$offset + 1]) & 0xFF) << 16) |
      ((ord($hash[$offset + 2]) & 0xFF) << 8) |
      (ord($hash[$offset + 3]) & 0xFF)
      ) % 1000000;

    return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
  }

  /**
   * Decode base32 string.
   *
   * @param string $input
   *   Base32 encoded string.
   *
   * @return string
   *   Decoded binary string.
   */
  protected function base32Decode(string $input): string {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $input = strtoupper($input);
    $binary = '';

    foreach (str_split($input) as $char) {
      $binary .= str_pad(decbin(strpos($chars, $char)), 5, '0', STR_PAD_LEFT);
    }

    $output = '';
    foreach (str_split($binary, 8) as $byte) {
      if (strlen($byte) === 8) {
        $output .= chr(bindec($byte));
      }
    }

    return $output;
  }

}
