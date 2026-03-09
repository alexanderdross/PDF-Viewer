<?php

namespace Drupal\pdf_embed_seo\Plugin\rest\resource;

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
 * Provides a REST resource for PDF document data (secure file access).
 *
 * @RestResource(
 *   id = "pdf_data_resource",
 *   label = @Translation("PDF Data Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/pdf-embed-seo/v1/documents/{id}/data"
 *   }
 * )
 */
class PdfDataResource extends ResourceBase {

  /**
   * Constructs a PdfDataResource object.
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
   * Responds to GET requests for PDF document data.
   *
   * @param int $id
   *   The PDF document ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the PDF file data.
   */
  public function get(int $id): ResourceResponse {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $document = $storage->load($id);

    if (!$document instanceof PdfDocumentInterface) {
      throw new NotFoundHttpException('PDF document not found.');
    }

    if (!$document->isPublished()) {
      throw new AccessDeniedHttpException('PDF document is not published.');
    }

    // Get the PDF file.
    $pdf_file = NULL;
    if ($document->hasField('pdf_file') && !$document->get('pdf_file')->isEmpty()) {
      $pdf_file = $document->get('pdf_file')->entity;
    }

    if (!$pdf_file) {
      throw new NotFoundHttpException('No PDF file attached to this document.');
    }

    $data = [
      'id' => (int) $document->id(),
      'pdf_url' => $this->fileUrlGenerator->generateAbsoluteString($pdf_file->getFileUri()),
      'allow_download' => (bool) $document->get('allow_download')->value,
      'allow_print' => (bool) $document->get('allow_print')->value,
      'filename' => $pdf_file->getFilename(),
      'filesize' => $pdf_file->getSize(),
      'mime_type' => $pdf_file->getMimeType(),
    ];

    // Allow other modules to alter the data.
    $this->moduleHandler->alter('pdf_embed_seo_document_data', $data, $document);

    $response = new ResourceResponse($data);
    $response->addCacheableDependency($document);
    return $response;
  }

}
