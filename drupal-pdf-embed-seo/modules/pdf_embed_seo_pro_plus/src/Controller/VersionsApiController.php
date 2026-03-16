<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\VersionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API controller for document versions.
 */
class VersionsApiController extends ControllerBase {

  /**
   * Constructs a VersionsApiController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\VersionManager $versionManager
   *   The version manager service.
   */
  public function __construct(
    protected VersionManager $versionManager,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.version_manager'),
    );
  }

  /**
   * Handle version API requests.
   *
   * @param int $document_id
   *   The PDF document ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function handle(int $document_id, Request $request): JsonResponse {
    $method = $request->getMethod();

    switch ($method) {
      case 'GET':
        return $this->listVersions($document_id);

      case 'POST':
        return $this->createVersion($document_id, $request);
    }

    return new JsonResponse(['error' => 'Method not allowed.'], 405);
  }

  /**
   * List versions for a document.
   *
   * @param int $document_id
   *   The document ID.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with versions.
   */
  protected function listVersions(int $document_id): JsonResponse {
    $versions = $this->versionManager->getVersions($document_id);
    $current = $this->versionManager->getCurrentVersion($document_id);

    return new JsonResponse([
      'document_id' => $document_id,
      'current_version' => $current,
      'versions' => $versions,
      'total' => $this->versionManager->getVersionCount($document_id),
    ]);
  }

  /**
   * Create a new version.
   *
   * @param int $document_id
   *   The document ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function createVersion(int $document_id, Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);

    if (empty($content['file_id']) || empty($content['file_url'])) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'file_id and file_url are required.',
      ], 400);
    }

    $version_id = $this->versionManager->createVersion(
      $document_id,
      (int) $content['file_id'],
      $content['file_url'],
      $content['change_notes'] ?? '',
    );

    if ($version_id) {
      return new JsonResponse([
        'success' => TRUE,
        'version_id' => $version_id,
        'version' => $this->versionManager->getVersion($version_id),
      ], 201);
    }

    return new JsonResponse([
      'success' => FALSE,
      'message' => 'Failed to create version.',
    ], 500);
  }

}
