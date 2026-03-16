<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\AuditLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller for the audit log dashboard.
 */
class AuditLogController extends ControllerBase {

  /**
   * Constructs an AuditLogController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\AuditLogger $auditLogger
   *   The audit logger service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    protected AuditLogger $auditLogger,
    protected RequestStack $requestStack,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.audit_logger'),
      $container->get('request_stack'),
    );
  }

  /**
   * Display the audit log list.
   *
   * @return array
   *   A render array.
   */
  public function list(): array {
    $request = $this->requestStack->getCurrentRequest();
    $page = $request ? (int) $request->query->get('page', 0) : 0;
    $action_filter = $request ? $request->query->get('action') : NULL;

    $filters = [];
    if ($action_filter) {
      $filters['action'] = $action_filter;
    }

    $limit = 50;
    $offset = $page * $limit;
    $entries = $this->auditLogger->getEntries($filters, $limit, $offset);
    $total = $this->auditLogger->getCount($filters);

    $header = [
      $this->t('Date'),
      $this->t('Action'),
      $this->t('User'),
      $this->t('Document'),
      $this->t('Details'),
      $this->t('IP Address'),
    ];

    $rows = [];
    foreach ($entries as $entry) {
      $rows[] = [
        date('Y-m-d H:i:s', $entry['timestamp'] ?? $entry['created'] ?? 0),
        $entry['action'] ?? '',
        $entry['user_id'] ?? 0,
        $entry['document_id'] ?? '-',
        is_array($entry['details'] ?? NULL) ? json_encode($entry['details']) : ($entry['details'] ?? ''),
        $entry['ip_address'] ?? '',
      ];
    }

    $build = [
      'filter' => [
        '#type' => 'select',
        '#title' => $this->t('Filter by action'),
        '#options' => ['' => $this->t('All actions')] + array_combine(
          $this->auditLogger->getActions(),
          $this->auditLogger->getActions()
        ),
        '#default_value' => $action_filter ?? '',
      ],
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No audit log entries found.'),
      ],
      'pager' => [
        '#type' => 'pager',
      ],
    ];

    return $build;
  }

}
