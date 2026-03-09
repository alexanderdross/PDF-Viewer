<?php

namespace Drupal\pdf_embed_seo_premium\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo_premium\Service\PdfAnalyticsTracker;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for PDF analytics dashboard (premium feature).
 */
class PdfAnalyticsController extends ControllerBase {

  /**
   * Constructs a PdfAnalyticsController.
   *
   * @param \Drupal\pdf_embed_seo_premium\Service\PdfAnalyticsTracker $analyticsTracker
   *   The analytics tracker service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    protected PdfAnalyticsTracker $analyticsTracker,
    protected RequestStack $requestStack,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
          $container->get('pdf_embed_seo.analytics_tracker'),
          $container->get('request_stack'),
      );
  }

  /**
   * Display the analytics dashboard.
   *
   * @return array
   *   A render array.
   */
  public function dashboard(): array {
    $storage = $this->entityTypeManager()->getStorage('pdf_document');

    // Get period from query string.
    $request = $this->requestStack->getCurrentRequest();
    $period = $request ? $request->query->get('period', '30days') : '30days';

    // Get statistics.
    $total_views = $this->analyticsTracker->getTotalViews();
    $total_documents = $storage->getQuery()
      ->accessCheck(TRUE)
      ->count()
      ->execute();

    // Get popular documents.
    $days = $this->getPeriodDays($period);
    $popular_ids = $this->analyticsTracker->getPopularDocuments(10, $days);
    $popular_documents = [];
    if (!empty($popular_ids)) {
      $documents = $storage->loadMultiple(array_keys($popular_ids));
      foreach ($documents as $id => $document) {
        $popular_documents[] = [
          'document' => $document,
          'views' => $popular_ids[$id],
        ];
      }
    }

    // Get recent views.
    $recent_views = $this->analyticsTracker->getRecentViews(50);
    $recent_data = [];
    foreach ($recent_views as $view) {
      $document = $storage->load($view->pdf_document_id);
      if ($document) {
        $user = NULL;
        if ($view->user_id > 0) {
          $user = $this->entityTypeManager()
            ->getStorage('user')
            ->load($view->user_id);
        }

        $recent_data[] = [
          'document' => $document,
          'user' => $user,
          'timestamp' => $view->timestamp,
          'ip_address' => $view->ip_address,
        ];
      }
    }

    return [
      '#theme' => 'pdf_analytics_dashboard',
      '#total_views' => $total_views,
      '#total_documents' => $total_documents,
      '#popular_documents' => $popular_documents,
      '#recent_views' => $recent_data,
      '#period' => $period,
      '#attached' => [
        'library' => ['pdf_embed_seo/admin'],
      ],
    ];
  }

  /**
   * Export analytics data as CSV.
   *
   * @return \Symfony\Component\HttpFoundation\StreamedResponse
   *   A streamed CSV response.
   */
  public function export(): StreamedResponse {
    $storage = $this->entityTypeManager()->getStorage('pdf_document');
    $analyticsTracker = $this->analyticsTracker;
    $entityTypeManager = $this->entityTypeManager();

    $response = new StreamedResponse(
          function () use ($analyticsTracker, $storage, $entityTypeManager) {
              $handle = fopen('php://output', 'w');

              // Headers.
              fputcsv(
                  $handle, [
                    'Document ID',
                    'Document Title',
                    'User ID',
                    'Username',
                    'IP Address',
                    'User Agent',
                    'Referer',
                    'Date/Time',
                  ]
              );

              // Get all views.
              $views = $analyticsTracker->getRecentViews(10000);

            foreach ($views as $view) {
              $document = $storage->load($view->pdf_document_id);
              $user = NULL;
              if ($view->user_id > 0) {
                  $user = $entityTypeManager
                    ->getStorage('user')
                    ->load($view->user_id);
              }

              fputcsv(
                  $handle, [
                    $view->pdf_document_id,
                    $document ? $document->label() : 'Deleted',
                    $view->user_id,
                    $user instanceof UserInterface ? $user->getAccountName() : 'Anonymous',
                    $view->ip_address,
                    $view->user_agent,
                    $view->referer,
                    date('Y-m-d H:i:s', $view->timestamp),
                  ]
              );
            }

              fclose($handle);
          }
      );

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="pdf-analytics-' . date('Y-m-d') . '.csv"');

    return $response;
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
