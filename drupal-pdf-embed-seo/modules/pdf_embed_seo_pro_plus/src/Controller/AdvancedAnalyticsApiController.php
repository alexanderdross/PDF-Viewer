<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\AdvancedAnalytics;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API controller for advanced analytics.
 */
class AdvancedAnalyticsApiController extends ControllerBase {

  /**
   * Constructs an AdvancedAnalyticsApiController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\AdvancedAnalytics $advancedAnalytics
   *   The advanced analytics service.
   */
  public function __construct(
    protected AdvancedAnalytics $advancedAnalytics,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.advanced_analytics'),
    );
  }

  /**
   * Get advanced analytics data.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with analytics data.
   */
  public function get(Request $request): JsonResponse {
    $document_id = $request->query->get('document_id');
    $period = $request->query->get('period', '30days');
    $days = $this->getPeriodDays($period);

    $data = [
      'period' => $period,
      'top_documents' => $this->advancedAnalytics->getTopDocuments(10, $days),
    ];

    if ($document_id) {
      $document_id = (int) $document_id;
      $data['document'] = [
        'id' => $document_id,
        'metrics' => $this->advancedAnalytics->getDocumentMetrics($document_id, $days),
        'engagement_score' => $this->advancedAnalytics->getEngagementScore($document_id),
        'heatmap' => $this->advancedAnalytics->getHeatmapData($document_id, NULL, $days),
      ];
    }

    return new JsonResponse($data);
  }

  /**
   * Get number of days from period string.
   *
   * @param string $period
   *   The period string.
   *
   * @return int
   *   Number of days.
   */
  protected function getPeriodDays(string $period): int {
    return match ($period) {
      '7days' => 7,
      '30days' => 30,
      '90days' => 90,
      '12months' => 365,
      'all' => 9999,
      default => 30,
    };
  }

}
