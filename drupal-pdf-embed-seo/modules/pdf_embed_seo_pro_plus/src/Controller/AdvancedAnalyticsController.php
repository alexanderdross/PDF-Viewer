<?php

namespace Drupal\pdf_embed_seo_pro_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_pro_plus\Service\AdvancedAnalytics;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller for the advanced analytics dashboard.
 */
class AdvancedAnalyticsController extends ControllerBase {

  /**
   * Constructs an AdvancedAnalyticsController.
   *
   * @param \Drupal\pdf_embed_seo_pro_plus\Service\AdvancedAnalytics $advancedAnalytics
   *   The advanced analytics service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    protected AdvancedAnalytics $advancedAnalytics,
    protected RequestStack $requestStack,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('pdf_embed_seo_pro_plus.advanced_analytics'),
      $container->get('request_stack'),
    );
  }

  /**
   * Display the advanced analytics dashboard.
   *
   * @return array
   *   A render array.
   */
  public function dashboard(): array {
    $request = $this->requestStack->getCurrentRequest();
    $period = $request ? $request->query->get('period', '30days') : '30days';
    $days = $this->getPeriodDays($period);

    $top_documents = $this->advancedAnalytics->getTopDocuments(10, $days);

    return [
      '#theme' => 'pdf_advanced_analytics',
      '#analytics' => [
        'top_documents' => $top_documents,
      ],
      '#period' => $period,
      '#attached' => [
        'library' => ['pdf_embed_seo/admin'],
      ],
    ];
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
