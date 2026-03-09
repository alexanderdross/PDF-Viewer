<?php

namespace Drupal\pdf_embed_seo\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\user\UserDataInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a REST resource for PDF reading progress (Premium).
 *
 * @RestResource(
 *   id = "pdf_progress_resource",
 *   label = @Translation("PDF Progress Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/pdf-embed-seo/v1/documents/{id}/progress"
 *   }
 * )
 */
class PdfProgressResource extends ResourceBase {

  /**
   * Constructs a PdfProgressResource object.
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
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   * @param \Drupal\user\UserDataInterface $userData
   *   The user data service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   *   The private temp store factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountProxyInterface $currentUser,
    protected UserDataInterface $userData,
    protected PrivateTempStoreFactory $tempStoreFactory,
    protected RequestStack $requestStack,
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
          $container->get('current_user'),
          $container->get('user.data'),
          $container->get('tempstore.private'),
          $container->get('request_stack'),
      );
  }

  /**
   * Responds to GET requests for reading progress.
   *
   * @param int $id
   *   The PDF document ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing reading progress.
   */
  public function get(int $id): ResourceResponse {
    $this->loadDocument($id);
    $user_id = $this->getUserIdentifier();
    $progress_key = 'pdf_progress_' . $id . '_' . $user_id;

    $progress = NULL;

    if ($this->currentUser->isAuthenticated()) {
      $progress = $this->userData->get('pdf_embed_seo', $this->currentUser->id(), $progress_key);
    }
    else {
      $tempstore = $this->tempStoreFactory->get('pdf_embed_seo');
      $progress = $tempstore->get($progress_key);
    }

    if (!$progress) {
      $progress = [
        'page' => 1,
        'scroll' => 0,
        'zoom' => 1,
      ];
    }

    $response = new ResourceResponse(
          [
            'document_id' => (int) $id,
            'progress' => $progress,
            'last_read' => $progress['timestamp'] ?? NULL,
          ]
      );

    // Don't cache progress data.
    $response->addCacheableDependency((new CacheableMetadata())->setCacheMaxAge(0));
    return $response;
  }

  /**
   * Responds to POST requests to save reading progress.
   *
   * @param int $id
   *   The PDF document ID.
   * @param array $data
   *   The progress data.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The response.
   */
  public function post(int $id, array $data): ModifiedResourceResponse {
    $this->loadDocument($id);
    $user_id = $this->getUserIdentifier();
    $progress_key = 'pdf_progress_' . $id . '_' . $user_id;

    $progress = [
      'page' => (int) ($data['page'] ?? 1),
      'scroll' => (float) ($data['scroll'] ?? 0),
      'zoom' => (float) ($data['zoom'] ?? 1),
      'timestamp' => date('c'),
    ];

    if ($this->currentUser->isAuthenticated()) {
      $this->userData->set('pdf_embed_seo', $this->currentUser->id(), $progress_key, $progress);
    }
    else {
      $tempstore = $this->tempStoreFactory->get('pdf_embed_seo');
      $tempstore->set($progress_key, $progress);
    }

    return new ModifiedResourceResponse(
          [
            'success' => TRUE,
            'document_id' => (int) $id,
            'progress' => $progress,
          ], 200
      );
  }

  /**
   * Load a PDF document.
   *
   * @param int $id
   *   The document ID.
   *
   * @return \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface
   *   The PDF document.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   */
  protected function loadDocument(int $id): PdfDocumentInterface {
    $storage = $this->entityTypeManager->getStorage('pdf_document');
    $entity = $storage->load($id);

    if (!$entity instanceof PdfDocumentInterface) {
      throw new NotFoundHttpException('PDF document not found.');
    }

    return $entity;
  }

  /**
   * Get a unique user identifier.
   *
   * @return string
   *   The user identifier.
   */
  protected function getUserIdentifier(): string {
    if ($this->currentUser->isAuthenticated()) {
      return 'user_' . $this->currentUser->id();
    }

    // For anonymous users, use session ID.
    $request = $this->requestStack->getCurrentRequest();
    $session = $request?->getSession();
    return 'anon_' . ($session ? $session->getId() : 'unknown');
  }

}
