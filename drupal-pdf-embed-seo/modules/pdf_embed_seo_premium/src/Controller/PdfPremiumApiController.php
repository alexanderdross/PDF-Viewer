<?php

namespace Drupal\pdf_embed_seo_premium\Controller;

use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Drupal\pdf_embed_seo_premium\Service\PdfAnalyticsTracker;
use Drupal\taxonomy\TermInterface;
use Drupal\pdf_embed_seo_premium\Service\PdfProgressTracker;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for Premium REST API endpoints.
 */
class PdfPremiumApiController extends ControllerBase {

  /**
   * Constructs a PdfPremiumApiController object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\pdf_embed_seo_premium\Service\PdfAnalyticsTracker $analyticsTracker
   *   The analytics tracker.
   * @param \Drupal\pdf_embed_seo_premium\Service\PdfProgressTracker $progressTracker
   *   The progress tracker.
   * @param \Drupal\Core\Password\PasswordInterface $password
   *   The password service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\Access\CsrfTokenGenerator $csrfToken
   *   The CSRF token generator.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $entityTypeManager,
    protected PdfAnalyticsTracker $analyticsTracker,
    protected PdfProgressTracker $progressTracker,
    protected PasswordInterface $password,
    protected StateInterface $state,
    ModuleHandlerInterface $moduleHandler,
    protected CsrfTokenGenerator $csrfToken,
  ) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
          $container->get('config.factory'),
          $container->get('entity_type.manager'),
          $container->get('pdf_embed_seo.analytics_tracker'),
          $container->get('pdf_embed_seo.progress_tracker'),
          $container->get('password'),
          $container->get('state'),
          $container->get('module_handler'),
          $container->get('csrf_token'),
      );
  }

  /**
   * Get analytics overview.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with analytics.
   */
  public function getAnalytics(Request $request): JsonResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');

    $period = $request->query->get('period', '30days');
    $days = $this->getPeriodDays($period);

    // Get statistics.
    $total_views = $this->analyticsTracker->getTotalViews();
    $total_documents = $storage->getQuery()
      ->accessCheck(TRUE)
      ->count()
      ->execute();

    // Get popular documents.
    $popular_ids = $this->analyticsTracker->getPopularDocuments(10, $days);
    $top_documents = [];
    if (!empty($popular_ids)) {
      $documents = $storage->loadMultiple(array_keys($popular_ids));
      foreach ($documents as $id => $document) {
        $top_documents[] = [
          'id' => (int) $id,
          'title' => $document->label(),
          'url' => $document->toUrl('canonical', ['absolute' => TRUE])->toString(),
          'views' => $popular_ids[$id],
        ];
      }
    }

    // Calculate date range.
    $end_date = date('Y-m-d');
    $start_date = date('Y-m-d', strtotime("-{$days} days"));

    return new JsonResponse(
          [
            'period' => $period,
            'date_range' => [
              'start' => $start_date,
              'end' => $end_date,
            ],
            'total_views' => $total_views,
            'total_documents' => (int) $total_documents,
            'total_downloads' => $this->analyticsTracker->getTotalDownloads(),
            'top_documents' => $top_documents,
          ]
      );
  }

  /**
   * Get per-document analytics.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with per-document analytics.
   */
  public function getDocumentAnalytics(Request $request): JsonResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');

    $period = $request->query->get('period', '30days');
    $days = $this->getPeriodDays($period);
    $page = (int) $request->query->get('page', 1);
    $per_page = min((int) $request->query->get('per_page', 20), 100);
    $orderby = $request->query->get('orderby', 'views');
    $order = $request->query->get('order', 'desc');

    // Get all documents with analytics.
    $query = $storage->getQuery()
      ->accessCheck(TRUE);

    // Count total.
    $total = (clone $query)->count()->execute();

    // Apply pagination.
    $offset = ($page - 1) * $per_page;
    $query->range($offset, $per_page);

    $ids = $query->execute();
    $documents = $storage->loadMultiple($ids);

    $data = [];
    foreach ($documents as $document) {
      $doc_analytics = $this->analyticsTracker->getDocumentAnalytics($document->id(), $days);
      $data[] = [
        'id' => (int) $document->id(),
        'title' => $document->label(),
        'url' => $document->toUrl('canonical', ['absolute' => TRUE])->toString(),
        'views' => $doc_analytics['views'] ?? 0,
        'downloads' => $doc_analytics['downloads'] ?? 0,
        'unique_visitors' => $doc_analytics['unique_visitors'] ?? 0,
        'avg_time_spent' => $doc_analytics['avg_time_spent'] ?? 0,
        'last_viewed' => $doc_analytics['last_viewed'] ?? NULL,
      ];
    }

    // Sort results.
    usort(
          $data, function ($a, $b) use ($orderby, $order) {
              $val_a = $a[$orderby] ?? 0;
              $val_b = $b[$orderby] ?? 0;
              return $order === 'desc' ? $val_b - $val_a : $val_a - $val_b;
          }
      );

    return new JsonResponse(
          [
            'period' => $period,
            'page' => $page,
            'per_page' => $per_page,
            'total' => (int) $total,
            'total_pages' => (int) ceil($total / $per_page),
            'documents' => $data,
          ]
      );
  }

  /**
   * Export analytics data.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Symfony\Component\HttpFoundation\JsonResponse
   *   Streamed CSV or JSON response.
   */
  public function exportAnalytics(Request $request): StreamedResponse|JsonResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');

    $format = $request->query->get('format', 'csv');
    $period = $request->query->get('period', '30days');
    $days = $this->getPeriodDays($period);

    // Get all documents with analytics.
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->execute();
    $documents = $storage->loadMultiple($ids);

    $data = [];
    foreach ($documents as $document) {
      $doc_analytics = $this->analyticsTracker->getDocumentAnalytics($document->id(), $days);
      $data[] = [
        'id' => (int) $document->id(),
        'title' => $document->label(),
        'url' => $document->toUrl('canonical', ['absolute' => TRUE])->toString(),
        'views' => $doc_analytics['views'] ?? 0,
        'downloads' => $doc_analytics['downloads'] ?? 0,
        'unique_visitors' => $doc_analytics['unique_visitors'] ?? 0,
        'avg_time_spent' => $doc_analytics['avg_time_spent'] ?? 0,
      ];
    }

    if ($format === 'json') {
      return new JsonResponse(
            [
              'period' => $period,
              'exported_at' => date('c'),
              'data' => $data,
            ]
        );
    }

    // CSV export.
    $response = new StreamedResponse(
          function () use ($data) {
              $handle = fopen('php://output', 'w');

              // Header row.
              fputcsv($handle, ['ID', 'Title', 'URL', 'Views', 'Downloads', 'Unique Visitors', 'Avg Time Spent']);

              // Data rows.
            foreach ($data as $row) {
                fputcsv(
                      $handle, [
                        $row['id'],
                        $row['title'],
                        $row['url'],
                        $row['views'],
                        $row['downloads'],
                        $row['unique_visitors'],
                        $row['avg_time_spent'],
                      ]
                  );
            }

              fclose($handle);
          }
      );

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="pdf-analytics-' . date('Y-m-d') . '.csv"');

    return $response;
  }

  /**
   * Get PDF categories.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with categories.
   */
  public function getCategories(Request $request): JsonResponse {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');

    try {
      $terms = $storage->loadByProperties(['vid' => 'pdf_category']);
    }
    catch (\Exception $e) {
      return new JsonResponse(
            [
              'categories' => [],
              'message' => 'PDF categories taxonomy not configured.',
            ]
        );
    }

    $categories = [];
    foreach ($terms as $term) {
      if (!$term instanceof TermInterface) {
        continue;
      }
      $categories[] = [
        'id' => (int) $term->id(),
        'name' => $term->label(),
        'slug' => $term->get('path')->alias ?? '/taxonomy/term/' . $term->id(),
        'description' => $term->getDescription(),
        'count' => $this->getTermDocumentCount($term->id()),
      ];
    }

    return new JsonResponse(['categories' => $categories]);
  }

  /**
   * Get PDF tags.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with tags.
   */
  public function getTags(Request $request): JsonResponse {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');

    try {
      $terms = $storage->loadByProperties(['vid' => 'pdf_tag']);
    }
    catch (\Exception $e) {
      return new JsonResponse(
            [
              'tags' => [],
              'message' => 'PDF tags taxonomy not configured.',
            ]
        );
    }

    $tags = [];
    foreach ($terms as $term) {
      if (!$term instanceof TermInterface) {
        continue;
      }
      $tags[] = [
        'id' => (int) $term->id(),
        'name' => $term->label(),
        'slug' => $term->get('path')->alias ?? '/taxonomy/term/' . $term->id(),
        'count' => $this->getTermDocumentCount($term->id()),
      ];
    }

    return new JsonResponse(['tags' => $tags]);
  }

  /**
   * Start bulk import.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with import status.
   */
  public function startBulkImport(Request $request): JsonResponse {
    if (!$this->currentUser()->hasPermission('administer pdf embed seo')) {
      return new JsonResponse(
            [
              'success' => FALSE,
              'message' => 'Permission denied.',
            ], 403
        );
    }

    $content = json_decode($request->getContent(), TRUE);

    if (empty($content['source'])) {
      return new JsonResponse(
            [
              'success' => FALSE,
              'message' => 'Import source is required.',
            ], 400
        );
    }

    $batch_id = uniqid('pdf_import_', TRUE);

    $this->state->set(
          'pdf_import_' . $batch_id, [
            'status' => 'pending',
            'source' => $content['source'],
            'options' => $content['options'] ?? [],
            'created' => time(),
            'processed' => 0,
            'total' => 0,
            'success' => 0,
            'failed' => 0,
          ]
      );

    return new JsonResponse(
          [
            'success' => TRUE,
            'batch_id' => $batch_id,
            'message' => 'Import job queued.',
          ]
      );
  }

  /**
   * Get bulk import status.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param string|null $batch_id
   *   The batch ID (optional).
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with import status.
   */
  public function getBulkImportStatus(Request $request, ?string $batch_id = NULL): JsonResponse {
    if (empty($batch_id)) {
      $batch_id = $request->query->get('import_id');
    }

    if (empty($batch_id)) {
      $batch_id = $this->state->get('pdf_embed_seo_last_import_id');
    }

    if (empty($batch_id)) {
      return new JsonResponse(
            [
              'status' => 'none',
              'message' => 'No recent imports found.',
            ]
        );
    }

    $job = $this->state->get('pdf_import_' . $batch_id);

    if (!$job) {
      return new JsonResponse(
            [
              'success' => FALSE,
              'message' => 'Import job not found.',
            ], 404
        );
    }

    return new JsonResponse(
          [
            'batch_id' => $batch_id,
            'status' => $job['status'],
            'processed' => $job['processed'],
            'total' => $job['total'],
            'success' => $job['success'],
            'failed' => $job['failed'],
            'created' => date('c', $job['created']),
          ]
      );
  }

  /**
   * Track PDF download.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function trackDownload(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    $this->analyticsTracker->trackDownload(
          $pdf_document->id(), [
            'ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
            'referrer' => $request->headers->get('Referer'),
          ]
      );

    return new JsonResponse(
          [
            'success' => TRUE,
            'document_id' => (int) $pdf_document->id(),
          ]
      );
  }

  /**
   * Generate expiring access link.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with access link.
   */
  public function generateExpiringLink(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    if (!$this->currentUser()->hasPermission('administer pdf embed seo')) {
      return new JsonResponse(
            [
              'success' => FALSE,
              'message' => 'Permission denied.',
            ], 403
        );
    }

    $content = json_decode($request->getContent(), TRUE);
    // Default 1 hour.
    $expires_in = (int) ($content['expires_in'] ?? 3600);
    // 0 = unlimited.
    $max_uses = (int) ($content['max_uses'] ?? 0);

    // Use the new AccessTokenStorage service.
    $container = \Drupal::getContainer();
    if ($container && $container->has('pdf_embed_seo.access_token_storage')) {
      /**
       * @var \Drupal\pdf_embed_seo_premium\Service\AccessTokenStorage $token_storage
*/
      $token_storage = $container->get('pdf_embed_seo.access_token_storage');
      $token_data = $token_storage->createToken($pdf_document->id(), $expires_in, $max_uses);

      if (!$token_data) {
        return new JsonResponse(
              [
                'success' => FALSE,
                'message' => 'Failed to create access token.',
              ], 500
          );
      }

      $token = $token_data['token'];
      $expires_at = $token_data['expires'];
    }
    else {
      // Fallback to State API (legacy).
      $token = bin2hex(random_bytes(32));
      $expires_at = time() + $expires_in;

      $this->state->set(
            'pdf_access_token_' . $token, [
              'pdf_id' => $pdf_document->id(),
              'expires' => $expires_at,
              'max_uses' => $max_uses,
              'uses' => 0,
              'created_by' => $this->currentUser()->id(),
              'created' => time(),
            ]
        );
    }

    // Generate URL.
    $access_url = Url::fromRoute(
          'pdf_embed_seo_premium.expiring_access', [
            'pdf_document' => $pdf_document->id(),
            'token' => $token,
          ], ['absolute' => TRUE]
      )->toString();

    return new JsonResponse(
          [
            'success' => TRUE,
            'document_id' => (int) $pdf_document->id(),
            'access_url' => $access_url,
            'token' => $token,
            'expires_at' => date('c', $expires_at),
            'expires_in' => $expires_in,
            'max_uses' => $max_uses,
          ]
      );
  }

  /**
   * Validate expiring access link.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param string $token
   *   The access token.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with validation result.
   */
  public function validateExpiringLink(PdfDocumentInterface $pdf_document, string $token, Request $request): JsonResponse {
    // Try the new AccessTokenStorage service first.
    $container = \Drupal::getContainer();
    if ($container && $container->has('pdf_embed_seo.access_token_storage')) {
      /**
       * @var \Drupal\pdf_embed_seo_premium\Service\AccessTokenStorage $token_storage
*/
      $token_storage = $container->get('pdf_embed_seo.access_token_storage');
      $result = $token_storage->validateToken($token, (int) $pdf_document->id());

      if (!$result['valid']) {
        return new JsonResponse(
              [
                'valid' => FALSE,
                'message' => $result['message'],
              ], 403
          );
      }

      return new JsonResponse(
            [
              'valid' => TRUE,
              'document_id' => (int) $pdf_document->id(),
              'uses_remaining' => $result['data']['remaining_uses'] ?? NULL,
              'expires_at' => date('c', $result['data']['expires']),
            ]
        );
    }

    // Fallback to State API (legacy).
    $token_data = $this->state->get('pdf_access_token_' . $token);

    if (!$token_data) {
      return new JsonResponse(
            [
              'valid' => FALSE,
              'message' => 'Invalid or expired access link.',
            ], 403
        );
    }

    // Check document ID.
    $doc_id = $token_data['document_id'] ?? $token_data['pdf_id'] ?? NULL;
    if ($doc_id != $pdf_document->id()) {
      return new JsonResponse(
            [
              'valid' => FALSE,
              'message' => 'Invalid access link for this document.',
            ], 403
        );
    }

    // Check expiration.
    $expires = $token_data['expires_at'] ?? $token_data['expires'] ?? 0;
    if (time() > $expires) {
      $this->state->delete('pdf_access_token_' . $token);
      return new JsonResponse(
            [
              'valid' => FALSE,
              'message' => 'Access link has expired.',
            ], 403
        );
    }

    // Check max uses.
    $max_uses = $token_data['max_uses'] ?? 0;
    $uses = $token_data['uses'] ?? $token_data['use_count'] ?? 0;
    if ($max_uses > 0 && $uses >= $max_uses) {
      $this->state->delete('pdf_access_token_' . $token);
      return new JsonResponse(
            [
              'valid' => FALSE,
              'message' => 'Access link has reached maximum uses.',
            ], 403
        );
    }

    // Increment uses.
    $token_data['uses'] = ($token_data['uses'] ?? 0) + 1;
    $this->state->set('pdf_access_token_' . $token, $token_data);

    return new JsonResponse(
          [
            'valid' => TRUE,
            'document_id' => (int) $pdf_document->id(),
            'uses_remaining' => $max_uses > 0 ? $max_uses - $token_data['uses'] : NULL,
            'expires_at' => date('c', $expires),
          ]
      );
  }

  /**
   * Get reading progress.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with progress.
   */
  public function getProgress(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    $progress = $this->progressTracker->getProgress($pdf_document);

    return new JsonResponse(
          [
            'document_id' => (int) $pdf_document->id(),
            'progress' => $progress,
          ]
      );
  }

  /**
   * Save reading progress.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function saveProgress(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);

    $progress = $this->progressTracker->saveProgress(
          $pdf_document, [
            'page' => (int) ($content['page'] ?? 1),
            'scroll' => (float) ($content['scroll'] ?? 0),
            'zoom' => (float) ($content['zoom'] ?? 1),
          ]
      );

    return new JsonResponse(
          [
            'success' => TRUE,
            'document_id' => (int) $pdf_document->id(),
            'progress' => $progress,
          ]
      );
  }

  /**
   * Verify PDF password.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function verifyPassword(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    $ip_address = $request->getClientIp();

    // Check rate limiting to prevent brute force attacks.
    $container = \Drupal::getContainer();
    if ($container && $container->has('pdf_embed_seo.rate_limiter')) {
      /**
       * @var \Drupal\pdf_embed_seo_premium\Service\RateLimiter $rate_limiter
*/
      $rate_limiter = $container->get('pdf_embed_seo.rate_limiter');
      $limit_check = $rate_limiter->checkLimit('password_verify', $ip_address, (int) $pdf_document->id());

      if (!$limit_check['allowed']) {
        return new JsonResponse(
              [
                'success' => FALSE,
                'message' => $limit_check['message'] ?? $this->t('Too many attempts. Please try again later.'),
                'retry_after' => $limit_check['retry_after'] ?? 300,
              ], 429
          );
      }
    }

    $content = json_decode($request->getContent(), TRUE);
    $password_input = $content['password'] ?? '';

    // Check if document has password field and is protected.
    if (!$pdf_document->hasField('password') || $pdf_document->get('password')->isEmpty()) {
      return new JsonResponse(
            [
              'success' => TRUE,
              'protected' => FALSE,
              'message' => $this->t('This document is not password protected.'),
            ]
        );
    }

    $stored_password = $pdf_document->get('password')->value;

    // Verify password.
    $is_valid = $this->password->check($password_input, $stored_password);

    // Allow other modules to alter verification.
    $this->moduleHandler->alter('pdf_embed_seo_verify_password', $is_valid, $pdf_document, $password_input);

    // Record the attempt for rate limiting.
    if ($container && $container->has('pdf_embed_seo.rate_limiter')) {
      /**
       * @var \Drupal\pdf_embed_seo_premium\Service\RateLimiter $rate_limiter
*/
      $rate_limiter = $container->get('pdf_embed_seo.rate_limiter');
      $rate_limiter->recordAttempt('password_verify', $ip_address, (int) $pdf_document->id(), $is_valid);
    }

    if ($is_valid) {
      // Generate access token.
      $token = $this->csrfToken->get('pdf_access_' . $pdf_document->id());

      // Store in session.
      $session = $request->getSession();
      $session->set('pdf_access_' . $pdf_document->id(), TRUE);

      return new JsonResponse(
            [
              'success' => TRUE,
              'access_token' => $token,
              'expires_in' => 3600,
            ]
        );
    }

    return new JsonResponse(
          [
            'success' => FALSE,
            'message' => $this->t('Incorrect password.'),
          ], 403
      );
  }

  /**
   * Get number of days from period string.
   *
   * @param string $period
   *   The period string.
   *
   * @return int
   *   Number of days.
   */
  protected function getPeriodDays(string $period): int {
    return match ($period) {
      '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '12months' => 365,
            'all' => 9999,
            default => 30,
    };
  }

  /**
   * Get document count for a taxonomy term.
   *
   * @param int $term_id
   *   The term ID.
   *
   * @return int
   *   Number of documents with this term.
   */
  protected function getTermDocumentCount(int $term_id): int {
    // @todo Implement document count by term.
    return 0;
  }

}
