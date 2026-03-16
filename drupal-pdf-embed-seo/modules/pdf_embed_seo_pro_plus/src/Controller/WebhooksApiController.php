<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\WebhookDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API controller for webhook management.
 */
class WebhooksApiController extends ControllerBase {

  /**
   * Constructs a WebhooksApiController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\WebhookDispatcher $webhookDispatcher
   *   The webhook dispatcher service.
   */
  public function __construct(
    protected WebhookDispatcher $webhookDispatcher,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.webhook_dispatcher'),
    );
  }

  /**
   * Handle webhook API requests.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  public function handle(Request $request): JsonResponse {
    $method = $request->getMethod();

    switch ($method) {
      case 'GET':
        return $this->listWebhooks();

      case 'POST':
        return $this->createWebhook($request);

      case 'DELETE':
        return $this->deleteWebhook($request);
    }

    return new JsonResponse(['error' => 'Method not allowed.'], 405);
  }

  /**
   * List all webhooks.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with webhooks.
   */
  protected function listWebhooks(): JsonResponse {
    $webhooks = $this->webhookDispatcher->getAll();

    return new JsonResponse([
      'webhooks' => $webhooks,
      'available_events' => $this->webhookDispatcher->getEvents(),
    ]);
  }

  /**
   * Create a new webhook.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function createWebhook(Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);

    if (empty($content['name']) || empty($content['url']) || empty($content['events'])) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'name, url, and events are required.',
      ], 400);
    }

    $result = $this->webhookDispatcher->create(
      $content['name'],
      $content['url'],
      (array) $content['events'],
      $content['secret'] ?? NULL,
    );

    if ($result) {
      return new JsonResponse([
        'success' => TRUE,
        'webhook' => $this->webhookDispatcher->get((int) $result),
      ], 201);
    }

    return new JsonResponse([
      'success' => FALSE,
      'message' => 'Failed to create webhook.',
    ], 500);
  }

  /**
   * Delete a webhook.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response.
   */
  protected function deleteWebhook(Request $request): JsonResponse {
    $content = json_decode($request->getContent(), TRUE);
    $id = (int) ($content['id'] ?? $request->query->get('id', 0));

    if (!$id) {
      return new JsonResponse([
        'success' => FALSE,
        'message' => 'Webhook ID is required.',
      ], 400);
    }

    $deleted = $this->webhookDispatcher->delete($id);

    return new JsonResponse([
      'success' => $deleted,
      'message' => $deleted ? 'Webhook deleted.' : 'Webhook not found.',
    ], $deleted ? 200 : 404);
  }

}
