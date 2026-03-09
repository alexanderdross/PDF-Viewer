<?php

namespace Drupal\pdf_embed_seo\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a REST resource for PDF documents.
 *
 * @RestResource(
 *   id = "pdf_document_resource",
 *   label = @Translation("PDF Document Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/pdf-embed-seo/v1/documents/{id}",
 *     "collection" = "/api/pdf-embed-seo/v1/documents"
 *   }
 * )
 */
class PdfDocumentResource extends ResourceBase {

  /**
   * Constructs a PdfDocumentResource object.
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
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $fileUrlGenerator
   *   The file URL generator.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected FileUrlGeneratorInterface $fileUrlGenerator,
    protected ModuleHandlerInterface $moduleHandler,
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
          $container->get('file_url_generator'),
          $container->get('module_handler'),
      );
  }

  /**
   * Responds to GET requests for a collection of PDF documents.
   *
   * @param int|null $id
   *   Optional document ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the list of PDF documents.
   */
  public function get(?int $id = NULL): ResourceResponse {
    if ($id) {
      return $this->getDocument($id);
    }

    return $this->getDocuments();
  }

  /**
   * Get list of PDF documents.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the list of PDF documents.
   */
  protected function getDocuments(): ResourceResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');

    $query = $storage->getQuery()
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->range(0, 50)
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

    $response = new ResourceResponse(
          [
            'documents' => $data,
            'total' => count($data),
          ]
      );

    $response->addCacheableDependency($this->getCacheMetadata());
    return $response;
  }

  /**
   * Get a single PDF document.
   *
   * @param int $id
   *   The document ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the PDF document.
   */
  protected function getDocument(int $id): ResourceResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $document = $storage->load($id);

    if (!$document instanceof PdfDocumentInterface) {
      throw new NotFoundHttpException('PDF document not found.');
    }

    if (!$document->isPublished()) {
      throw new AccessDeniedHttpException('PDF document is not published.');
    }

    $data = $this->formatDocument($document, TRUE);

    $response = new ResourceResponse($data);
    $response->addCacheableDependency($document);
    return $response;
  }

  /**
   * Format a document for API response.
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
    $data = [
      'id' => (int) $document->id(),
      'title' => $document->label(),
      'slug' => $document->get('slug')->value ?? '',
      'url' => $document->toUrl('canonical', ['absolute' => TRUE])->toString(),
      'description' => $document->get('description')->value ?? '',
      'created' => date('c', $document->getCreatedTime()),
      'changed' => date('c', $document->getChangedTime()),
      'views' => (int) ($document->get('view_count')->value ?? 0),
      'allow_download' => (bool) $document->get('allow_download')->value,
      'allow_print' => (bool) $document->get('allow_print')->value,
    ];

    // Add thumbnail if available.
    if ($document->hasField('thumbnail') && !$document->get('thumbnail')->isEmpty()) {
      $file = $document->get('thumbnail')->entity;
      if ($file) {
        $data['thumbnail'] = $this->fileUrlGenerator
          ->generateAbsoluteString($file->getFileUri());
      }
    }

    if ($detailed) {
      $data['data_url'] = '/api/pdf-embed-seo/v1/documents/' . $document->id() . '/data';

      // Add SEO data if metatag module is available.
      if ($this->moduleHandler->moduleExists('metatag')) {
        $data['seo'] = [
          'title' => $document->label(),
          'description' => $document->get('description')->value ?? '',
        ];
      }
    }

    return $data;
  }

  /**
   * Get cache metadata.
   *
   * @return \Drupal\Core\Cache\CacheableMetadata
   *   The cache metadata.
   */
  protected function getCacheMetadata(): CacheableMetadata {
    $cache_metadata = new CacheableMetadata();
    $cache_metadata->setCacheTags(['pdf_document_list']);
    $cache_metadata->setCacheMaxAge(300);
    return $cache_metadata;
  }

}
