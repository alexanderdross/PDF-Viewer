<?php

namespace Drupal\pdf_embed_seo\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\pdf_embed_seo\Entity\PdfDocumentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Form for entering PDF password.
 */
class PdfPasswordForm extends FormBase {

  /**
   * The PDF document.
   *
   * @var \Drupal\pdf_embed_seo\Entity\PdfDocumentInterface|null
   */
  protected ?PdfDocumentInterface $pdfDocument = NULL;

  /**
   * Constructs a PdfPasswordForm.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Password\PasswordInterface $password
   *   The password hashing service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected PasswordInterface $password,
    RequestStack $requestStack,
  ) {
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
          $container->get('entity_type.manager'),
          $container->get('password'),
          $container->get('request_stack'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'pdf_embed_seo_password_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PdfDocumentInterface $pdf_document = NULL): array {
    $this->pdfDocument = $pdf_document;

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => $this->t('Enter password'),
        'autofocus' => 'autofocus',
      ],
    ];

    $form['pdf_document_id'] = [
      '#type' => 'hidden',
      '#value' => $pdf_document ? $pdf_document->id() : '',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Unlock'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $pdf_document_id = $form_state->getValue('pdf_document_id');
    $password = $form_state->getValue('password');

    // Load the PDF document.
    $pdf_document = $this->entityTypeManager
      ->getStorage('pdf_document')
      ->load($pdf_document_id);

    if (!$pdf_document instanceof PdfDocumentInterface) {
      $form_state->setErrorByName('password', $this->t('PDF document not found.'));
      return;
    }

    // Check password using Drupal's password hashing service for security.
    $stored_password = $pdf_document->getPassword();
    if (!empty($stored_password)) {
      if (!$this->password->check($password, $stored_password)) {
        $form_state->setErrorByName('password', $this->t('Incorrect password.'));
      }
    }
    else {
      // Fallback for empty passwords (shouldn't happen in normal use).
      $form_state->setErrorByName('password', $this->t('Incorrect password.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $pdf_document_id = (int) $form_state->getValue('pdf_document_id');

    // Store unlocked status in session.
    $request = $this->requestStack->getCurrentRequest();
    if ($request && $request->hasSession()) {
      $session = $request->getSession();
      $unlocked = $session->get('pdf_unlocked', []);
      $unlocked[] = $pdf_document_id;
      $session->set('pdf_unlocked', array_unique($unlocked));
    }

    // Redirect to the PDF document.
    $form_state->setRedirect(
          'entity.pdf_document.canonical', [
            'pdf_document' => $pdf_document_id,
          ]
      );

    $this->messenger()->addStatus($this->t('PDF unlocked successfully.'));
  }

}
