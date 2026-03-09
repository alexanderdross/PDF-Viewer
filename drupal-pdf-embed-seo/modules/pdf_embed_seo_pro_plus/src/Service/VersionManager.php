<?php

namespace Drupal\pdf_embed_seo_pro_plus\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\file\FileInterface;
use Psr\Log\LoggerInterface;

/**
 * Document versioning service for Pro+ Enterprise.
 */
class VersionManager {

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;

  /**
   * Constructs a VersionManager object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   The logger factory.
   */
  public function __construct(
    protected Connection $database,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountProxyInterface $currentUser,
    LoggerChannelFactoryInterface $loggerFactory,
  ) {
    $this->logger = $loggerFactory->get('pdf_embed_seo_pro_plus');
  }

  /**
   * Create a new version of a document.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   * @param int $file_id
   *   The file entity ID.
   * @param string $file_url
   *   The file URL.
   * @param string $change_notes
   *   Optional change notes.
   *
   * @return int|false
   *   The new version ID or FALSE on failure.
   */
  public function createVersion(int $document_id, int $file_id, string $file_url, string $change_notes = '') {
    // Get current version number.
    $current = $this->getCurrentVersion($document_id);
    $version_parts = $current ? explode('.', $current['version_number']) : [0, 0];
    $new_version = (int) $version_parts[0] . '.' . ((int) ($version_parts[1] ?? 0) + 1);

    // Mark all existing versions as not current.
    $this->database->update('pdf_versions')
      ->fields(['is_current' => 0])
      ->condition('document_id', $document_id)
      ->execute();

    // Get file size.
    $file_size = 0;
    try {
      $file = $this->entityTypeManager->getStorage('file')->load($file_id);
      if ($file instanceof FileInterface) {
        $file_size = $file->getSize();
      }
    }
    catch (\Exception $e) {
      // Ignore file size errors.
    }

    // Insert new version.
    try {
      $id = $this->database->insert('pdf_versions')
        ->fields(
                [
                  'document_id' => $document_id,
                  'version_number' => $new_version,
                  'file_id' => $file_id,
                  'file_url' => $file_url,
                  'file_size' => $file_size,
                  'change_notes' => $change_notes,
                  'created_by' => $this->currentUser->id(),
                  'created_at' => date('Y-m-d H:i:s'),
                  'is_current' => 1,
                ]
            )
        ->execute();

      return $id;
    }
    catch (\Exception $e) {
      $this->logger->error(
            'Failed to create version: @message', [
              '@message' => $e->getMessage(),
            ]
        );
      return FALSE;
    }
  }

  /**
   * Get all versions of a document.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   *
   * @return array
   *   Array of version records.
   */
  public function getVersions(int $document_id): array {
    try {
      $query = $this->database->select('pdf_versions', 'v')
        ->fields('v')
        ->condition('document_id', $document_id)
        ->orderBy('created_at', 'DESC');

      return $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }
    catch (\Exception $e) {
      return [];
    }
  }

  /**
   * Get the current version of a document.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   *
   * @return array|null
   *   The current version record or NULL.
   */
  public function getCurrentVersion(int $document_id): ?array {
    try {
      $query = $this->database->select('pdf_versions', 'v')
        ->fields('v')
        ->condition('document_id', $document_id)
        ->condition('is_current', 1);

      $result = $query->execute()->fetchAssoc();
      return $result ?: NULL;
    }
    catch (\Exception $e) {
      return NULL;
    }
  }

  /**
   * Get a specific version.
   *
   * @param int $version_id
   *   The version ID.
   *
   * @return array|null
   *   The version record or NULL.
   */
  public function getVersion(int $version_id): ?array {
    try {
      $query = $this->database->select('pdf_versions', 'v')
        ->fields('v')
        ->condition('id', $version_id);

      $result = $query->execute()->fetchAssoc();
      return $result ?: NULL;
    }
    catch (\Exception $e) {
      return NULL;
    }
  }

  /**
   * Restore a previous version.
   *
   * @param int $version_id
   *   The version ID to restore.
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public function restoreVersion(int $version_id): bool {
    $version = $this->getVersion($version_id);
    if (!$version) {
      return FALSE;
    }

    try {
      // Mark all versions as not current.
      $this->database->update('pdf_versions')
        ->fields(['is_current' => 0])
        ->condition('document_id', $version['document_id'])
        ->execute();

      // Mark this version as current.
      $this->database->update('pdf_versions')
        ->fields(['is_current' => 1])
        ->condition('id', $version_id)
        ->execute();

      return TRUE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * Delete a version.
   *
   * @param int $version_id
   *   The version ID to delete.
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public function deleteVersion(int $version_id): bool {
    $version = $this->getVersion($version_id);
    if (!$version) {
      return FALSE;
    }

    // Don't delete the current version.
    if ($version['is_current']) {
      return FALSE;
    }

    try {
      $this->database->delete('pdf_versions')
        ->condition('id', $version_id)
        ->execute();

      return TRUE;
    }
    catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * Get version count for a document.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   *
   * @return int
   *   The number of versions.
   */
  public function getVersionCount(int $document_id): int {
    try {
      $query = $this->database->select('pdf_versions', 'v')
        ->condition('document_id', $document_id);
      $query->addExpression('COUNT(*)', 'count');

      return (int) $query->execute()->fetchField();
    }
    catch (\Exception $e) {
      return 0;
    }
  }

  /**
   * Check if document has versions.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   *
   * @return bool
   *   TRUE if document has versions.
   */
  public function hasVersions(int $document_id): bool {
    return $this->getVersionCount($document_id) > 0;
  }

  /**
   * Compare two versions.
   *
   * @param int $version_id_1
   *   First version ID.
   * @param int $version_id_2
   *   Second version ID.
   *
   * @return array
   *   Comparison data.
   */
  public function compareVersions(int $version_id_1, int $version_id_2): array {
    $v1 = $this->getVersion($version_id_1);
    $v2 = $this->getVersion($version_id_2);

    if (!$v1 || !$v2) {
      return [];
    }

    return [
      'version_1' => $v1,
      'version_2' => $v2,
      'size_diff' => ($v2['file_size'] ?? 0) - ($v1['file_size'] ?? 0),
      'days_between' => (strtotime($v2['created_at']) - strtotime($v1['created_at'])) / 86400,
    ];
  }

  /**
   * Cleanup old versions.
   *
   * @param int $document_id
   *   The PDF document entity ID.
   * @param int $keep_count
   *   Number of versions to keep.
   *
   * @return int
   *   Number of versions deleted.
   */
  public function cleanupVersions(int $document_id, int $keep_count = 10): int {
    $versions = $this->getVersions($document_id);

    if (count($versions) <= $keep_count) {
      return 0;
    }

    $deleted = 0;
    $to_delete = array_slice($versions, $keep_count);

    foreach ($to_delete as $version) {
      if (!$version['is_current'] && $this->deleteVersion($version['id'])) {
        $deleted++;
      }
    }

    return $deleted;
  }

}
