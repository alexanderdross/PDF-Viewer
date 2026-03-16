<?php

namespace Drupal\pdf_embed_seo\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computed field for PDF document view count.
 *
 * This field reads the view count from the analytics table instead of
 * storing it on the entity. This prevents entity saves on each page view,
 * which would invalidate caches and cause performance issues.
 */
class ComputedViewCount extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * Computes the view count from the analytics table.
   */
  protected function computeValue() {
    $entity = $this->getEntity();

    if (!$entity || $entity->isNew()) {
      $this->list[0] = $this->createItem(0, 0);
      return;
    }

    $view_count = $this->getViewCountFromAnalytics($entity->id());
    $this->list[0] = $this->createItem(0, $view_count);
  }

  /**
   * Get view count from the analytics table.
   *
   * @param int $pdf_id
   *   The PDF document ID.
   *
   * @return int
   *   The view count.
   */
  protected function getViewCountFromAnalytics(int $pdf_id): int {
    $database = \Drupal::database();

    if (!$database->schema()->tableExists('pdf_embed_seo_analytics')) {
      return 0;
    }

    try {
      $count = $database->select('pdf_embed_seo_analytics', 'a')
        ->condition('pdf_document_id', $pdf_id)
        ->countQuery()
        ->execute()
        ->fetchField();

      return (int) $count;
    }
    catch (\Exception $e) {
      \Drupal::logger('pdf_embed_seo')->warning(
        'Failed to compute view count: @message', [
          '@message' => $e->getMessage(),
        ]
      );
    }

    return 0;
  }

}
