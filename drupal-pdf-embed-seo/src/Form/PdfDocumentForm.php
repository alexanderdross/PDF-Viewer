<?php

namespace Drupal\pdf_embed_seo\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Password\PasswordInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the PDF Document entity add/edit forms.
 */
class PdfDocumentForm extends ContentEntityForm {

  /**
   * Constructs a PdfDocumentForm.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\Password\PasswordInterface $password
   *   The password hashing service.
   */
  public function __construct(
    ModuleHandlerInterface $moduleHandler,
    protected PasswordInterface $password,
  ) {
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = new static(
          $container->get('module_handler'),
          $container->get('password'),
      );
    $instance->setEntityTypeManager($container->get('entity_type.manager'));
    $instance->setModuleHandler($container->get('module_handler'));
    $instance->setStringTranslation($container->get('string_translation'));
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    // Add fieldset for PDF settings.
    $form['pdf_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('PDF Settings'),
      '#open' => TRUE,
      '#weight' => 5,
    ];

    // Move PDF-related fields into the fieldset.
    if (isset($form['pdf_file'])) {
      $form['pdf_settings']['pdf_file'] = $form['pdf_file'];
      unset($form['pdf_file']);
    }

    if (isset($form['thumbnail'])) {
      $form['pdf_settings']['thumbnail'] = $form['thumbnail'];
      unset($form['thumbnail']);
    }

    // Add fieldset for permissions.
    $form['permissions'] = [
      '#type' => 'details',
      '#title' => $this->t('Permissions'),
      '#open' => TRUE,
      '#weight' => 10,
    ];

    if (isset($form['allow_download'])) {
      $form['permissions']['allow_download'] = $form['allow_download'];
      unset($form['allow_download']);
    }

    if (isset($form['allow_print'])) {
      $form['permissions']['allow_print'] = $form['allow_print'];
      unset($form['allow_print']);
    }

    // Premium features fieldset.
    if ($this->moduleHandler->moduleExists('pdf_embed_seo_premium')) {
      $form['premium_settings'] = [
        '#type' => 'details',
        '#title' => $this->t('Premium Settings'),
        '#open' => FALSE,
        '#weight' => 15,
      ];

      if (isset($form['password'])) {
        $form['premium_settings']['password'] = $form['password'];
        $form['premium_settings']['password']['widget'][0]['value']['#type'] = 'password';
        $form['premium_settings']['password']['widget'][0]['value']['#description'] = $this->t('Leave empty to remove password protection.');
        unset($form['password']);
      }
    }
    else {
      // Hide password field if premium is not active.
      if (isset($form['password'])) {
        $form['password']['#access'] = FALSE;
      }
    }

    // Add fieldset for publishing options.
    $form['publishing'] = [
      '#type' => 'details',
      '#title' => $this->t('Publishing Options'),
      '#open' => FALSE,
      '#weight' => 90,
    ];

    if (isset($form['status'])) {
      $form['publishing']['status'] = $form['status'];
      unset($form['status']);
    }

    if (isset($form['uid'])) {
      $form['publishing']['uid'] = $form['uid'];
      unset($form['uid']);
    }

    // Add fieldset for URL settings.
    $form['url_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('URL Settings'),
      '#open' => FALSE,
      '#weight' => 95,
    ];

    if (isset($form['path'])) {
      $form['url_settings']['path'] = $form['path'];
      unset($form['path']);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $entity = $this->entity;

    // Hash the password if set and not already hashed.
    $password_value = $entity->get('password')->value;
    if (!empty($password_value)) {
      // Check if password appears to be unhashed (doesn't start with $).
      if (!str_starts_with($password_value, '$')) {
        $hashed_password = $this->password->hash($password_value);
        $entity->set('password', $hashed_password);
      }
    }

    $status = parent::save($form, $form_state);

    match ($status) {
      SAVED_NEW => $this->messenger()->addStatus(
            $this->t('Created the %label PDF document.', ['%label' => $entity->label()]),
        ),
            default => $this->messenger()->addStatus(
            $this->t('Saved the %label PDF document.', ['%label' => $entity->label()]),
        ),
    };

    $form_state->setRedirectUrl($entity->toUrl('collection'));

    return $status;
  }

}
