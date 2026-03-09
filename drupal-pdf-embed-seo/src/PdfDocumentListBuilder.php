<?php

namespace Drupal\pdf_embed_seo;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list builder for PDF Document entities.
 */
class PdfDocumentListBuilder extends EntityListBuilder {

  /**
   * Constructs a new PdfDocumentListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   The date formatter service.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    EntityStorageInterface $storage,
    protected DateFormatterInterface $dateFormatter,
  ) {
    parent::__construct($entity_type, $storage);
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type): static {
    return new static(
          $entity_type,
          $container->get('entity_type.manager')->getStorage($entity_type->id()),
          $container->get('date.formatter'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header = [
      'title' => $this->t('Title'),
      'status' => $this->t('Status'),
      'view_count' => $this->t('Views'),
      'author' => $this->t('Author'),
      'created' => $this->t('Created'),
    ];
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /**
* @var \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $entity
*/
    $row = [
      'title' => Link::createFromRoute(
          $entity->getTitle(),
          'entity.pdf_document.canonical',
          ['pdf_document' => $entity->id()],
      ),
      'status' => $entity->isPublished() ? $this->t('Published') : $this->t('Unpublished'),
      'view_count' => $entity->getViewCount(),
      'author' => $entity->getOwner() ? $entity->getOwner()->getDisplayName() : $this->t('Anonymous'),
      'created' => $this->dateFormatter->format($entity->getCreatedTime(), 'short'),
    ];
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();
    $build['table']['#empty'] = $this->t(
          'No PDF documents available. <a href=":url">Add a PDF document</a>.',
          [':url' => Url::fromRoute('entity.pdf_document.add_form')->toString()],
      );
    return $build;
  }

}
