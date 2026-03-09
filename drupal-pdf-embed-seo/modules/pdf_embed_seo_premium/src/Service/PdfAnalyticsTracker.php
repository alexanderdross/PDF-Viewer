<?php

namespace Drupal\pdf_embed_seo_premium\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service for tracking PDF view analytics (Premium).
 */
class PdfAnalyticsTracker {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The analytics table name.
   *
   * @var string
   */
  protected $tableName = 'pdf_embed_seo_analytics';

  /**
   * Constructs a PdfAnalyticsTracker.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(
    Connection $database,
    AccountProxyInterface $current_user,
    RequestStack $request_stack,
    TimeInterface $time,
    ConfigFactoryInterface $config_factory,
    LoggerChannelFactoryInterface $logger_factory,
  ) {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
    $this->time = $time;
    $this->configFactory = $config_factory;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * Check if analytics tracking is enabled.
   *
   * @return bool
   *   TRUE if enabled.
   */
  public function isEnabled() {
    $config = $this->configFactory->get('pdf_embed_seo_premium.settings');
    return (bool) ($config->get('enable_analytics') ?? TRUE);
  }

  /**
   * Track a PDF view.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   */
  public function trackView(PdfDocumentInterface $pdf_document) {
    if (!$this->isEnabled()) {
      return;
    }

    $request = $this->requestStack->getCurrentRequest();
    $timestamp = $this->time->getRequestTime();

    try {
      $this->database->insert($this->tableName)
        ->fields(
                [
                  'pdf_document_id' => $pdf_document->id(),
                  'user_id' => $this->currentUser->id(),
                  'ip_address' => $request ? $request->getClientIp() : '',
                  'user_agent' => $request ? substr($request->headers->get('User-Agent', ''), 0, 255) : '',
                  'referer' => $request ? substr($request->headers->get('Referer', ''), 0, 255) : '',
                  'timestamp' => $timestamp,
                  'created' => $timestamp,
                ]
            )
        ->execute();
    }
    catch (\Exception $e) {
      // Silently fail - analytics should not break page viewing.
      $this->loggerFactory->get('pdf_embed_seo_premium')->warning(
            'Failed to track analytics: @message', [
              '@message' => $e->getMessage(),
            ]
        );
    }
  }

  /**
   * Get view statistics for a PDF document.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   *
   * @return array
   *   Array of statistics.
   */
  public function getDocumentStats(PdfDocumentInterface $pdf_document) {
    $total = $this->database->select($this->tableName, 'a')
      ->condition('pdf_document_id', $pdf_document->id())
      ->countQuery()
      ->execute()
      ->fetchField();

    $unique = $this->database->select($this->tableName, 'a')
      ->condition('pdf_document_id', $pdf_document->id())
      ->fields('a', ['ip_address'])
      ->distinct()
      ->countQuery()
      ->execute()
      ->fetchField();

    $today = $this->database->select($this->tableName, 'a')
      ->condition('pdf_document_id', $pdf_document->id())
      ->condition('timestamp', strtotime('today'), '>=')
      ->countQuery()
      ->execute()
      ->fetchField();

    return [
      'total_views' => (int) $total,
      'unique_visitors' => (int) $unique,
      'views_today' => (int) $today,
    ];
  }

  /**
   * Get popular documents.
   *
   * @param int $limit
   *   Number of documents to return.
   * @param int $days
   *   Number of days to consider.
   *
   * @return array
   *   Array of document IDs with view counts.
   */
  public function getPopularDocuments($limit = 10, $days = 30) {
    $since = $this->time->getRequestTime() - ($days * 86400);

    $results = $this->database->select($this->tableName, 'a')
      ->fields('a', ['pdf_document_id'])
      ->condition('timestamp', $since, '>=')
      ->groupBy('pdf_document_id')
      ->orderBy('count', 'DESC')
      ->range(0, $limit);

    $results->addExpression('COUNT(*)', 'count');

    return $results->execute()->fetchAllKeyed();
  }

  /**
   * Get recent views.
   *
   * @param int $limit
   *   Number of views to return.
   *
   * @return array
   *   Array of recent view records.
   */
  public function getRecentViews($limit = 50) {
    return $this->database->select($this->tableName, 'a')
      ->fields('a')
      ->orderBy('timestamp', 'DESC')
      ->range(0, $limit)
      ->execute()
      ->fetchAll();
  }

  /**
   * Get total view count across all documents.
   *
   * @return int
   *   Total view count.
   */
  public function getTotalViews() {
    return (int) $this->database->select($this->tableName, 'a')
      ->countQuery()
      ->execute()
      ->fetchField();
  }

  /**
   * Get total download count across all documents.
   *
   * @return int
   *   Total download count.
   */
  public function getTotalDownloads(): int {
    try {
      return (int) $this->database->select($this->tableName, 'a')
        ->condition('event_type', 'download')
        ->countQuery()
        ->execute()
        ->fetchField();
    }
    catch (\Exception $e) {
      return 0;
    }
  }

  /**
   * Get analytics for a specific document.
   *
   * @param int|string|null $document_id
   *   The document ID.
   * @param int $days
   *   Number of days to consider.
   *
   * @return array
   *   Array of analytics data including views, downloads, unique visitors.
   */
  public function getDocumentAnalytics(int|string|null $document_id, int $days = 30): array {
    $since = $this->time->getRequestTime() - ($days * 86400);

    try {
      $views = (int) $this->database->select($this->tableName, 'a')
        ->condition('pdf_document_id', $document_id)
        ->condition('timestamp', $since, '>=')
        ->countQuery()
        ->execute()
        ->fetchField();

      $unique = (int) $this->database->select($this->tableName, 'a')
        ->condition('pdf_document_id', $document_id)
        ->condition('timestamp', $since, '>=')
        ->fields('a', ['ip_address'])
        ->distinct()
        ->countQuery()
        ->execute()
        ->fetchField();

      $last_viewed = $this->database->select($this->tableName, 'a')
        ->fields('a', ['timestamp'])
        ->condition('pdf_document_id', $document_id)
        ->orderBy('timestamp', 'DESC')
        ->range(0, 1)
        ->execute()
        ->fetchField();

      return [
        'views' => $views,
        'downloads' => 0,
        'unique_visitors' => $unique,
        'avg_time_spent' => 0,
        'last_viewed' => $last_viewed ? date('c', (int) $last_viewed) : NULL,
      ];
    }
    catch (\Exception $e) {
      return [
        'views' => 0,
        'downloads' => 0,
        'unique_visitors' => 0,
        'avg_time_spent' => 0,
        'last_viewed' => NULL,
      ];
    }
  }

  /**
   * Track a PDF download event.
   *
   * @param int|string|null $document_id
   *   The document ID.
   * @param array $context
   *   Additional context (ip, user_agent, referrer).
   */
  public function trackDownload(int|string|null $document_id, array $context = []): void {
    if (!$this->isEnabled()) {
      return;
    }

    $timestamp = $this->time->getRequestTime();

    try {
      $this->database->insert($this->tableName)
        ->fields(
                [
                  'pdf_document_id' => $document_id,
                  'user_id' => $this->currentUser->id(),
                  'ip_address' => $context['ip'] ?? '',
                  'user_agent' => substr($context['user_agent'] ?? '', 0, 255),
                  'referer' => substr($context['referrer'] ?? '', 0, 255),
                  'timestamp' => $timestamp,
                  'created' => $timestamp,
                ]
            )
        ->execute();
    }
    catch (\Exception $e) {
      $this->loggerFactory->get('pdf_embed_seo_premium')->warning(
            'Failed to track download: @message', [
              '@message' => $e->getMessage(),
            ]
        );
    }
  }

  /**
   * Clear analytics data older than configured retention period.
   *
   * @return int
   *   Number of rows deleted.
   */
  public function clearOldData() {
    $config = $this->configFactory->get('pdf_embed_seo_premium.settings');
    $days = $config->get('analytics_retention_days') ?? 365;

    if ($days <= 0) {
      return 0;
    }

    $cutoff = $this->time->getRequestTime() - ($days * 86400);

    return $this->database->delete($this->tableName)
      ->condition('timestamp', $cutoff, '<')
      ->execute();
  }

}
