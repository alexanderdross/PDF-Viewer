<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\pdf_embed_seo_pro_plus\Service\VersionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for document version history dashboard.
 */
class VersionsController extends ControllerBase {

  /**
   * Constructs a VersionsController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\VersionManager $versionManager
   *   The version manager service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(
    protected VersionManager $versionManager,
    EntityTypeManagerInterface $entityTypeManager,
  ) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.version_manager'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Display version history for a document.
   *
   * @param int $pdf_document
   *   The PDF document entity ID.
   *
   * @return array
   *   A render array.
   */
  public function list(int $pdf_document): array {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $document = $storage->load($pdf_document);

    if (!$document) {
      return [
        '#markup' => $this->t('PDF document not found.'),
      ];
    }

    $versions = $this->versionManager->getVersions($pdf_document);
    $current = $this->versionManager->getCurrentVersion($pdf_document);

    return [
      '#theme' => 'pdf_version_history',
      '#document_id' => $pdf_document,
      '#versions' => $versions,
      '#current_version' => $current,
    ];
  }

}
