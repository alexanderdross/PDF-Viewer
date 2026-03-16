<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\AuditLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API controller for audit log.
 */
class AuditLogApiController extends ControllerBase {

  /**
   * Constructs an AuditLogApiController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\AuditLogger $auditLogger
   *   The audit logger service.
   */
  public function __construct(
    protected AuditLogger $auditLogger,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.audit_logger'),
    );
  }

  /**
   * Get audit log entries.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with audit log entries.
   */
  public function list(Request $request): JsonResponse {
    $page = (int) $request->query->get('page', 1);
    $per_page = min((int) $request->query->get('per_page', 50), 200);
    $action = $request->query->get('action');
    $document_id = $request->query->get('document_id');

    $filters = [];
    if ($action) {
      $filters['action'] = $action;
    }
    if ($document_id) {
      $filters['document_id'] = (int) $document_id;
    }

    $offset = ($page - 1) * $per_page;
    $entries = $this->auditLogger->getEntries($filters, $per_page, $offset);
    $total = $this->auditLogger->getCount($filters);

    return new JsonResponse([
      'page' => $page,
      'per_page' => $per_page,
      'total' => $total,
      'total_pages' => (int) ceil($total / $per_page),
      'entries' => $entries,
    ]);
  }

}
