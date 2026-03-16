<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\pdf_embed_seo_pro_plus\Service\WebhookDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for webhook management dashboard.
 */
class WebhooksController extends ControllerBase {

  /**
   * Constructs a WebhooksController.
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
   * Display the webhooks list.
   *
   * @return array
   *   A render array.
   */
  public function list(): array {
    $webhooks = $this->webhookDispatcher->getAll();

    $header = [
      $this->t('Name'),
      $this->t('URL'),
      $this->t('Events'),
      $this->t('Status'),
      $this->t('Operations'),
    ];

    $rows = [];
    foreach ($webhooks as $webhook) {
      $events = is_array($webhook['events'] ?? NULL) ? implode(', ', $webhook['events']) : ($webhook['events'] ?? '');
      $status = !empty($webhook['active']) ? $this->t('Active') : $this->t('Inactive');

      $rows[] = [
        $webhook['name'] ?? '',
        $webhook['url'] ?? '',
        $events,
        $status,
        $this->t('Edit / Delete'),
      ];
    }

    $build = [
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No webhooks configured. Use the API to create webhooks.'),
      ],
      'info' => [
        '#type' => 'markup',
        '#markup' => '<p>' . $this->t('Webhooks can be managed via the REST API at <code>/api/pdf-embed-seo/v1/webhooks</code>.') . '</p>',
      ],
      'events' => [
        '#type' => 'details',
        '#title' => $this->t('Available Events'),
        '#open' => FALSE,
        'list' => [
          '#theme' => 'item_list',
          '#items' => $this->webhookDispatcher->getEvents(),
        ],
      ],
    ];

    return $build;
  }

}
