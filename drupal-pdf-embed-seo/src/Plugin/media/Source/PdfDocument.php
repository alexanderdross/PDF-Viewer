<?php

namespace Drupal\pdf_embed_seo\Plugin\media\Source;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;
use Drupal\media\MediaTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Media source plugin for PDF documents.
 *
 * This provides integration with Drupal's Media Library, allowing PDFs
 * to be selected and embedded using the standard media workflows while
 * leveraging the PDF Embed & SEO Optimize module's viewing capabilities.
 *
 * @MediaSource(
 *   id = "pdf_document",
 *   label = @Translation("PDF Document"),
 *   description = @Translation("Use PDF files with the PDF Embed & SEO viewer."),
 *   allowed_field_types = {"file"},
 *   default_thumbnail_filename = "pdf.png",
 *   thumbnail_alt_metadata_attribute = "alt",
 *   thumbnail_title_metadata_attribute = "title",
 * )
 */
class PdfDocument extends MediaSourceBase {

  /**
   * Constructs a PdfDocument media source plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $fileUrlGenerator
   *   The file URL generator.
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    EntityFieldManagerInterface $entity_field_manager,
    FieldTypePluginManagerInterface $field_type_manager,
    ConfigFactoryInterface $config_factory,
    protected FileUrlGeneratorInterface $fileUrlGenerator,
    protected ModuleExtensionList $moduleExtensionList,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('entity_type.manager'),
          $container->get('entity_field.manager'),
          $container->get('plugin.manager.field.field_type'),
          $container->get('config.factory'),
          $container->get('file_url_generator'),
          $container->get('extension.list.module'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes(): array {
    return [
      'file_name' => $this->t('File name'),
      'file_size' => $this->t('File size'),
      'mime_type' => $this->t('MIME type'),
      'page_count' => $this->t('Page count'),
      'title' => $this->t('Title'),
      'alt' => $this->t('Alternative text'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $attribute_name): mixed {
    $file = $this->getSourceFile($media);

    if (!$file) {
      return parent::getMetadata($media, $attribute_name);
    }

    return match ($attribute_name) {
      'file_name' => $file->getFilename(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'title' => $media->label(),
            'alt' => $this->t('PDF document: @name', ['@name' => $media->label()]),
            'thumbnail_uri' => $this->getThumbnailUri($media),
            'default_name' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
            'page_count' => NULL,
            default => parent::getMetadata($media, $attribute_name),
    };
  }

  /**
   * Get the source file for a media entity.
   *
   * @param \Drupal\media\MediaInterface $media
   *   The media entity.
   *
   * @return \Drupal\file\FileInterface|null
   *   The file entity or NULL.
   */
  protected function getSourceFile(MediaInterface $media): mixed {
    $source_field = $this->getSourceFieldDefinition($media->bundle->entity);
    if (!$source_field) {
      return NULL;
    }

    $field_name = $source_field->getName();
    if (!$media->hasField($field_name) || $media->get($field_name)->isEmpty()) {
      return NULL;
    }

    return $media->get($field_name)->entity;
  }

  /**
   * Get thumbnail URI for the media entity.
   *
   * @param \Drupal\media\MediaInterface $media
   *   The media entity.
   *
   * @return string
   *   The thumbnail URI.
   */
  protected function getThumbnailUri(MediaInterface $media): string {
    $file = $this->getSourceFile($media);

    if (!$file) {
      return $this->getDefaultThumbnailUri();
    }

    // Check if media has a thumbnail field.
    if ($media->hasField('thumbnail') && !$media->get('thumbnail')->isEmpty()) {
      $thumbnail = $media->get('thumbnail')->entity;
      if ($thumbnail) {
        return $thumbnail->getFileUri();
      }
    }

    return $this->getDefaultThumbnailUri();
  }

  /**
   * Get the default thumbnail URI for PDF documents.
   *
   * @return string
   *   The default thumbnail URI.
   */
  protected function getDefaultThumbnailUri(): string {
    $module_path = $this->moduleExtensionList->getPath('pdf_embed_seo');
    $default_thumbnail = $module_path . '/assets/images/pdf-icon.png';

    if (file_exists($default_thumbnail)) {
      return $default_thumbnail;
    }

    return $this->moduleExtensionList->getPath('media') . '/images/icons/generic.png';
  }

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type) {
    return parent::createSourceField($type)
      ->set('label', $this->t('PDF file'))
      ->set(
              'settings', [
                'file_extensions' => 'pdf',
                'file_directory' => 'pdf_documents',
                'max_filesize' => '50 MB',
              ]
          );
  }

  /**
   * {@inheritdoc}
   */
  public function prepareViewDisplay(MediaTypeInterface $type, $display): void {
    $display->setComponent(
          $this->getSourceFieldDefinition($type)->getName(),
          [
            'type' => 'pdf_embed_seo_viewer',
            'label' => 'hidden',
          ],
      );
  }

  /**
   * {@inheritdoc}
   */
  public function prepareFormDisplay(MediaTypeInterface $type, $display): void {
    parent::prepareFormDisplay($type, $display);

    $source_field = $this->getSourceFieldDefinition($type);
    $display->setComponent(
          $source_field->getName(),
          ['type' => 'file_generic'],
      );
  }

}
