<?php

namespace Drupal\pdf_embed_seo\Controller;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Url;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for REST API endpoints.
 */
class PdfApiController extends ControllerBase {

  /**
   * Constructs a PdfApiController object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $fileUrlGenerator
   *   The file URL generator.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $entityTypeManager,
    protected ModuleExtensionList $moduleExtensionList,
    ModuleHandlerInterface $moduleHandler,
    protected FileUrlGeneratorInterface $fileUrlGenerator,
    protected Connection $database,
    protected TimeInterface $time,
    protected LoggerInterface $logger,
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
          $container->get('extension.list.module'),
          $container->get('module_handler'),
          $container->get('file_url_generator'),
          $container->get('database'),
          $container->get('datetime.time'),
          $container->get('logger.factory')->get('pdf_embed_seo'),
      );
  }

  /**
   * Get public plugin settings.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with settings.
   */
  public function getSettings(Request $request): JsonResponse {
    $config = $this->configFactory->get('pdf_embed_seo.settings');
    $module_info = $this->moduleExtensionList->getExtensionInfo('pdf_embed_seo');

    $settings = [
      'viewer_theme' => $config->get('viewer_theme') ?? 'light',
      'archive_url' => Url::fromRoute('pdf_embed_seo.archive', [], ['absolute' => TRUE])->toString(),
      'is_premium' => (bool) $config->get('premium_enabled'),
      'version' => $module_info['version'] ?? '1.1.5',
      'api_version' => '1.0',
      'endpoints' => [
        'documents' => Url::fromUri('base:/api/pdf-embed-seo/v1/documents', ['absolute' => TRUE])->toString(),
        'settings' => Url::fromRoute('pdf_embed_seo.api.settings', [], ['absolute' => TRUE])->toString(),
      ],
    ];

    // Allow other modules to alter settings.
    $this->moduleHandler->alter('pdf_embed_seo_api_settings', $settings);

    return new JsonResponse($settings);
  }

  /**
   * Get list of all published PDF documents.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with documents list.
   */
  public function getDocuments(Request $request): JsonResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');

    $page = max(0, (int) $request->query->get('page', 0));
    $limit = min(100, max(1, (int) $request->query->get('limit', 50)));
    $sort = $request->query->get('sort', 'created');
    $direction = strtoupper($request->query->get('direction', 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

    $allowed_sorts = ['created', 'title', 'view_count'];
    if (!in_array($sort, $allowed_sorts, TRUE)) {
      $sort = 'created';
    }

    $query = $storage->getQuery()
      ->condition('status', 1)
      ->sort($sort, $direction)
      ->range($page * $limit, $limit)
      ->accessCheck(TRUE);

    $ids = $query->execute();
    $documents = $storage->loadMultiple($ids);

    $data = [];
    foreach ($documents as $document) {
      if (!$document instanceof PdfDocumentInterface) {
        continue;
      }
      $data[] = $this->formatDocument($document);
    }

    return new JsonResponse(
          [
            'documents' => $data,
            'total' => count($data),
            'page' => $page,
            'limit' => $limit,
          ]
      );
  }

  /**
   * Get a single PDF document.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with document data.
   */
  public function getDocument(PdfDocumentInterface $pdf_document): JsonResponse {
    if (!$pdf_document->isPublished()) {
      return new JsonResponse(['error' => 'Document not found.'], 404);
    }

    return new JsonResponse($this->formatDocument($pdf_document, TRUE));
  }

  /**
   * Get secure PDF data URL for a document.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with the secure PDF URL.
   */
  public function getDocumentData(PdfDocumentInterface $pdf_document): JsonResponse {
    if (!$pdf_document->isPublished()) {
      return new JsonResponse(['error' => 'Document not found.'], 404);
    }

    $url = Url::fromRoute(
          'pdf_embed_seo.pdf_data',
          ['pdf_document' => $pdf_document->id()],
          ['absolute' => TRUE],
      )->toString();

    return new JsonResponse(
          [
            'id' => (int) $pdf_document->id(),
            'data_url' => $url,
          ]
      );
  }

  /**
   * Format a PDF document entity for API response.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $document
   *   The PDF document entity.
   * @param bool $detailed
   *   Whether to include detailed information.
   *
   * @return array
   *   The formatted document data.
   */
  protected function formatDocument(PdfDocumentInterface $document, bool $detailed = FALSE): array {
    $url = $document->toUrl('canonical', ['absolute' => TRUE])->toString();

    // Extract slug from path alias or URL.
    $path_alias = $document->get('path')->alias ?? '';
    $slug = $path_alias ? basename($path_alias) : '';

    $data = [
      'id' => (int) $document->id(),
      'title' => $document->label(),
      'slug' => $slug,
      'url' => $url,
      'description' => $document->get('description')->value ?? '',
      'created' => date('c', $document->getCreatedTime()),
      'modified' => date('c', $document->getChangedTime()),
      'views' => (int) ($document->get('view_count')->value ?? 0),
      'allow_download' => (bool) $document->get('allow_download')->value,
      'allow_print' => (bool) $document->get('allow_print')->value,
    ];

    // Add thumbnail if available.
    if (!$document->get('thumbnail')->isEmpty()) {
      $file = $document->get('thumbnail')->entity;
      if ($file) {
        $data['thumbnail'] = $this->fileUrlGenerator
          ->generateAbsoluteString($file->getFileUri());
      }
    }

    if ($detailed) {
      $data['data_url'] = Url::fromRoute(
            'pdf_embed_seo.pdf_data',
            ['pdf_document' => $document->id()],
            ['absolute' => TRUE],
        )->toString();
    }

    // Allow other modules to alter document data.
    $this->moduleHandler->alter('pdf_embed_seo_document_data', $data, $document);

    return $data;
  }

  /**
   * Track a PDF view.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function trackView(PdfDocumentInterface $pdf_document, Request $request): JsonResponse {
    // Track view in analytics table without entity save (performance).
    $view_count = 0;

    if ($this->database->schema()->tableExists('pdf_embed_seo_analytics')) {
      try {
        $this->database->insert('pdf_embed_seo_analytics')
          ->fields(
                  [
                    'pdf_id' => $pdf_document->id(),
                    'ip_address' => _pdf_embed_seo_anonymize_ip($request->getClientIp()),
                    'user_agent' => substr($request->headers->get('User-Agent', ''), 0, 255),
                    'referrer' => substr($request->headers->get('Referer', ''), 0, 255),
                    'created' => $this->time->getRequestTime(),
                  ]
              )
          ->execute();

        // Get view count from analytics table.
        $view_count = (int) $this->database->select('pdf_embed_seo_analytics', 'a')
          ->condition('pdf_id', $pdf_document->id())
          ->countQuery()
          ->execute()
          ->fetchField();
      }
      catch (\Exception $e) {
        $this->logger->warning(
              'Failed to track analytics: @message',
              ['@message' => $e->getMessage()],
          );
      }
    }

    // Invoke hook for other modules.
    $this->moduleHandler->invokeAll('pdf_embed_seo_view_tracked', [$pdf_document, $view_count]);

    return new JsonResponse(
          [
            'success' => TRUE,
            'views' => $view_count,
          ]
      );
  }

}
