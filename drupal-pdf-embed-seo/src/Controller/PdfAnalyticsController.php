<?php

namespace Drupal\pdf_embed_seo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Drupal\pdf_embed_seo_premium\Service\PdfAnalyticsTracker;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
   */
  public function __construct(
    protected PdfAnalyticsTracker $analyticsTracker,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
          $container->get('pdf_embed_seo.analytics_tracker'),
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

    // Get statistics.
    $total_views = $this->analyticsTracker->getTotalViews();
    $total_documents = $storage->getQuery()
      ->accessCheck(TRUE)
      ->count()
      ->execute();

    // Get popular documents.
    $popular_ids = $this->analyticsTracker->getPopularDocuments(10, 30);
    $popular_documents = [];
    if (!empty($popular_ids)) {
      $documents = $storage->loadMultiple(array_keys($popular_ids));
      foreach ($documents as $id => $document) {
        if (!$document instanceof PdfDocumentInterface) {
          continue;
        }
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
      if ($document instanceof PdfDocumentInterface) {
        $user = NULL;
        if ($view->user_id > 0) {
          $loaded_user = $this->entityTypeManager()
            ->getStorage('user')
            ->load($view->user_id);
          if ($loaded_user instanceof UserInterface) {
            $user = $loaded_user;
          }
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
                  $loaded_user = $entityTypeManager
                    ->getStorage('user')
                    ->load($view->user_id);
                if ($loaded_user instanceof UserInterface) {
                  $user = $loaded_user;
                }
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

}
