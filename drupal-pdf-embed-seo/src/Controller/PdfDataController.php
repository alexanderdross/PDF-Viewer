<?php

namespace Drupal\pdf_embed_seo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for serving PDF data via AJAX.
 *
 * This controller hides the direct PDF URL from the frontend,
 * making it harder for users to discover direct download links.
 */
class PdfDataController extends ControllerBase {

  /**
   * Constructs a PdfDataController.
   *
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   The file system service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   The logger factory.
   */
  public function __construct(
    protected FileSystemInterface $fileSystem,
    protected Connection $database,
    protected RequestStack $requestStack,
    protected TimeInterface $time,
    LoggerChannelFactoryInterface $loggerFactory,
  ) {
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
          $container->get('file_system'),
          $container->get('database'),
          $container->get('request_stack'),
          $container->get('datetime.time'),
          $container->get('logger.factory'),
      );
  }

  /**
   * Serve PDF data for the viewer.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   *
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   *   The PDF file response.
   */
  public function getData(PdfDocumentInterface $pdf_document): BinaryFileResponse {
    // Check if document is published.
    if (!$pdf_document->isPublished() && !$this->currentUser()->hasPermission('administer pdf embed seo')) {
      throw new NotFoundHttpException();
    }

    // Check for password protection.
    if ($pdf_document->hasPassword() && !$this->isPasswordUnlocked($pdf_document)) {
      throw new AccessDeniedHttpException('Password required.');
    }

    // Get the PDF file.
    $file = $pdf_document->getPdfFile();
    if (!$file) {
      throw new NotFoundHttpException('PDF file not found.');
    }

    // Get the file path.
    $file_uri = $file->getFileUri();
    $file_path = $this->fileSystem->realpath($file_uri);

    if (!$file_path || !file_exists($file_path)) {
      throw new NotFoundHttpException('PDF file not accessible.');
    }

    // Track view in analytics table without entity save (performance optimization).
    // Entity saves invalidate cache and cause performance issues under load.
    if ($this->database->schema()->tableExists('pdf_embed_seo_analytics')) {
      try {
        $request = $this->requestStack->getCurrentRequest();
        $this->database->insert('pdf_embed_seo_analytics')
          ->fields(
                  [
                    'pdf_id' => $pdf_document->id(),
                    'ip_address' => _pdf_embed_seo_anonymize_ip($request ? $request->getClientIp() : ''),
                    'user_agent' => $request ? substr($request->headers->get('User-Agent', ''), 0, 255) : '',
                    'referrer' => $request ? substr($request->headers->get('Referer', ''), 0, 255) : '',
                    'created' => $this->time->getRequestTime(),
                  ]
              )
          ->execute();
      }
      catch (\Exception $e) {
        // Log but don't fail.
        $this->loggerFactory->get('pdf_embed_seo')->warning(
              'Failed to track analytics: @message', [
                '@message' => $e->getMessage(),
              ]
          );
      }
    }

    // Create response with appropriate headers.
    $response = new BinaryFileResponse($file_path);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'inline; filename="' . $file->getFilename() . '"');

    // Set cache headers.
    $response->headers->set('Cache-Control', 'private, max-age=3600');

    // Prevent direct URL discovery.
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

    return $response;
  }

  /**
   * Check if the PDF password has been unlocked for current session.
   *
   * @param \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface $pdf_document
   *   The PDF document entity.
   *
   * @return bool
   *   TRUE if unlocked, FALSE otherwise.
   */
  protected function isPasswordUnlocked(PdfDocumentInterface $pdf_document): bool {
    // Admins can bypass password.
    if ($this->currentUser()->hasPermission('bypass pdf password')) {
      return TRUE;
    }

    $request = $this->requestStack->getCurrentRequest();
    if (!$request || !$request->hasSession()) {
      return FALSE;
    }

    $session = $request->getSession();
    $unlocked = $session->get('pdf_unlocked', []);

    return in_array($pdf_document->id(), $unlocked, TRUE);
  }

}
