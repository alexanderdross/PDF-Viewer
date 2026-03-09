<?php

namespace Drupal\pdf_embed_seo\Plugin\rest\resource;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a REST resource for PDF analytics (Premium).
 *
 * @RestResource(
 *   id = "pdf_analytics_resource",
 *   label = @Translation("PDF Analytics Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/pdf-embed-seo/v1/analytics"
 *   }
 * )
 */
class PdfAnalyticsResource extends ResourceBase {

  /**
   * Constructs a PdfAnalyticsResource object.
   *
   * @param array $configuration
   *   A configuration array.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected Connection $database,
    protected AccountProxyInterface $currentUser,
    protected RequestStack $requestStack,
    protected TimeInterface $time,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->getParameter('serializer.formats'),
          $container->get('logger.factory')->get('pdf_embed_seo'),
          $container->get('entity_type.manager'),
          $container->get('database'),
          $container->get('current_user'),
          $container->get('request_stack'),
          $container->get('datetime.time'),
      );
  }

  /**
   * Responds to GET requests for analytics data.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing analytics data.
   */
  public function get(): ResourceResponse {
    if (!$this->currentUser->hasPermission('view pdf analytics')) {
      throw new AccessDeniedHttpException('Access denied.');
    }

    $request = $this->requestStack->getCurrentRequest();
    $period = $request ? $request->query->get('period', '30days') : '30days';
    $date_range = $this->getDateRange($period);

    // Get analytics from database.
    $table_exists = $this->database->schema()->tableExists('pdf_embed_seo_analytics');

    if ($table_exists) {
      $stats = $this->database->query(
            "SELECT
          COUNT(*) as total_views,
          COUNT(DISTINCT ip_address) as unique_visitors,
          AVG(time_spent) as avg_time_spent
        FROM {pdf_embed_seo_analytics}
        WHERE created >= :start AND created <= :end",
            [
              ':start' => $date_range['start'],
              ':end' => $date_range['end'],
            ],
        )->fetchAssoc();
    }
    else {
      $stats = [
        'total_views' => $this->getTotalViewsFromEntities(),
        'unique_visitors' => 0,
        'avg_time_spent' => 0,
      ];
    }

    $data = [
      'period' => $period,
      'date_range' => [
        'start' => date('c', $date_range['start']),
        'end' => date('c', $date_range['end']),
      ],
      'total_views' => (int) ($stats['total_views'] ?? 0),
      'unique_visitors' => (int) ($stats['unique_visitors'] ?? 0),
      'avg_time_spent' => round((float) ($stats['avg_time_spent'] ?? 0), 2),
      'top_documents' => $this->getTopDocuments(5),
    ];

    $response = new ResourceResponse($data);
    $response->addCacheableDependency($this->getCacheMetadata());
    return $response;
  }

  /**
   * Get date range for a period.
   *
   * @param string $period
   *   The period identifier.
   *
   * @return array
   *   Array with 'start' and 'end' timestamps.
   */
  protected function getDateRange(string $period): array {
    $end = $this->time->getRequestTime();

    $start = match ($period) {
      '7days' => strtotime('-7 days', $end),
            '90days' => strtotime('-90 days', $end),
            '12months' => strtotime('-12 months', $end),
            'all' => 0,
            default => strtotime('-30 days', $end),
    };

    return [
      'start' => $start,
      'end' => $end,
    ];
  }

  /**
   * Get total views from entity field.
   *
   * @return int
   *   Total view count.
   */
  protected function getTotalViewsFromEntities(): int {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $query = $storage->getQuery()
      ->condition('status', 1)
      ->accessCheck(FALSE);

    $ids = $query->execute();
    $total = 0;

    foreach ($storage->loadMultiple($ids) as $document) {
      if (!$document instanceof PdfDocumentInterface) {
        continue;
      }
      $total += (int) ($document->get('view_count')->value ?? 0);
    }

    return $total;
  }

  /**
   * Get top documents by views.
   *
   * @param int $limit
   *   Number of documents to return.
   *
   * @return array
   *   Array of top documents.
   */
  protected function getTopDocuments(int $limit = 5): array {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $query = $storage->getQuery()
      ->condition('status', 1)
      ->sort('view_count', 'DESC')
      ->range(0, $limit)
      ->accessCheck(TRUE);

    $ids = $query->execute();
    $documents = $storage->loadMultiple($ids);

    $result = [];
    foreach ($documents as $document) {
      if (!$document instanceof PdfDocumentInterface) {
        continue;
      }
      $result[] = [
        'id' => (int) $document->id(),
        'title' => $document->label(),
        'url' => $document->toUrl('canonical', ['absolute' => TRUE])->toString(),
        'views' => (int) ($document->get('view_count')->value ?? 0),
      ];
    }

    return $result;
  }

  /**
   * Get cache metadata.
   *
   * @return \Drupal\Core\Cache\CacheableMetadata
   *   The cache metadata.
   */
  protected function getCacheMetadata(): CacheableMetadata {
    $cache_metadata = new CacheableMetadata();
    $cache_metadata->setCacheTags(['pdf_analytics']);
    $cache_metadata->setCacheMaxAge(60);
    return $cache_metadata;
  }

}
