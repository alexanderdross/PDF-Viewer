<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\AnnotationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API controller for PDF annotations.
 */
class AnnotationsApiController extends ControllerBase {

  /**
   * Constructs an AnnotationsApiController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\AnnotationManager $annotationManager
   *   The annotation manager service.
   */
  public function __construct(
    protected AnnotationManager $annotationManager,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.annotation_manager'),
    );
  }

  /**
   * Handle annotation API requests.
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
        return $this->listAnnotations($document_id, $request);

      case 'POST':
        return $this->createAnnotation($document_id, $request);

      case 'PATCH':
        return $this->updateAnnotation($request);

      case 'DELETE':
        return $this->deleteAnnotation($request);
    }

    return new JsonResponse(['error' => 'Method not allowed.'], 405);
  }

  /**
   * List annotations for a document.
   *
   * @param int $document_id
   *   The document ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with annotations.
   */
  protected function listAnnotations(int $document_id, Request $request): JsonResponse {
    $page_number = $request->query->get('page_number') ? (int) $request->query->get('page_number') : NULL;
    $user_id = $request->query->get('user_id') ? (int) $request->query->get('user_id') : NULL;

    $annotations = $this->annotationManager->getByDocument($document_id, $page_number, $user_id);

    return new JsonResponse([
      'document_id' => $document_id,
      'annotations' => $annotations,
      'total' => $this->annotationManager->getCount($document_id),
      'types' => $this->annotationManager->getTypes(),
    ]);
  }

  /**
   * Create a new annotation.
   *
   * @param int $document_id
   *   The document ID.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function createAnnotation(int $document_id, Request $request): JsonResponse {
    if (!$this->currentUser()->hasPermission('create pdf annotations')) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Permission denied.',
      ], 403);
    }

    $content = json_decode($request->getContent(), TRUE);

    if (empty($content['page_number']) || empty($content['type'])) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'page_number and type are required.',
      ], 400);
    }

    $uuid = $this->annotationManager->create(
      $document_id,
      (int) $content['page_number'],
      $content['type'],
      $content['data'] ?? [],
    );

    if ($uuid) {
      return new JsonResponse([
        'success' => TRUE,
        'annotation' => $this->annotationManager->get($uuid),
      ], 201);
    }

    return new JsonResponse([
      'success' => FALSE,
      'message' => 'Failed to create annotation.',
    ], 500);
  }

  /**
   * Update an annotation.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function updateAnnotation(Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);
    $uuid = $content['uuid'] ?? '';

    if (empty($uuid)) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Annotation UUID is required.',
      ], 400);
    }

    $existing = $this->annotationManager->get($uuid);
    if (!$existing) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Annotation not found.',
      ], 404);
    }

    // Check permission: owner needs 'edit own', non-owner needs 'edit any'.
    $current_uid = (int) $this->currentUser()->id();
    $owner_uid = (int) ($existing['user_id'] ?? 0);
    $is_owner = ($current_uid === $owner_uid);
    if ($is_owner && !$this->currentUser()->hasPermission('edit own pdf annotations')) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Permission denied.',
      ], 403);
    }
    if (!$is_owner && !$this->currentUser()->hasPermission('edit any pdf annotations')) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Permission denied.',
      ], 403);
    }

    $updated = $this->annotationManager->update($uuid, $content['data'] ?? []);

    return new JsonResponse([
      'success' => $updated,
      'annotation' => $updated ? $this->annotationManager->get($uuid) : NULL,
    ]);
  }

  /**
   * Delete an annotation.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function deleteAnnotation(Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);
    $uuid = $content['uuid'] ?? $request->query->get('uuid', '');

    if (empty($uuid)) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Annotation UUID is required.',
      ], 400);
    }

    $existing = $this->annotationManager->get($uuid);
    if (!$existing) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Annotation not found.',
      ], 404);
    }

    // Check permission: owner needs 'delete own', non-owner needs 'delete any'.
    $current_uid = (int) $this->currentUser()->id();
    $owner_uid = (int) ($existing['user_id'] ?? 0);
    $is_owner = ($current_uid === $owner_uid);
    if ($is_owner && !$this->currentUser()->hasPermission('delete own pdf annotations')) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Permission denied.',
      ], 403);
    }
    if (!$is_owner && !$this->currentUser()->hasPermission('delete any pdf annotations')) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Permission denied.',
      ], 403);
    }

    $deleted = $this->annotationManager->delete($uuid);

    return new JsonResponse([
      'success' => $deleted,
      'message' => $deleted ? 'Annotation deleted.' : 'Failed to delete annotation.',
    ]);
  }

}
