<?php

declare(strict_types=1);

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\media\Entity\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Provides a Download files form.
 */
final class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'download_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    

    $form['media'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a file to download'),
      '#options' => $this->getMediaOptions(),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  public function getMediaOptions() {
    // Get media items using Database ABstration Layer.
    // $results = \Drupal::database()
    // ->select('media_field_data', 'm')
    // ->fields('m', ['mid', 'name'])
    // ->condition('m.status', 1);

    // $results->leftJoin('media__field_media_file', 'mf', 'm.mid = mf.entity_id and m.vid = mf.revision_id');
    // $results->fields('mf', ['field_media_file_target_id']);
    
    // $results->leftJoin('file_managed', 'f', 'mf.field_media_file_target_id = f.fid');
    // $results->fields('f', ['uri']);

    // $results = $results->execute()->fetchAll();

    $config = \Drupal::config('download_files.settings');
    $media_types = $config->get('allowed_media_types');

    $ids = \Drupal::entityQuery('media')
      ->condition('status', 1)
      ->condition('bundle', $media_types, 'IN')
      ->accessCheck()
      ->execute();

    $results = Media::loadMultiple($ids);

    $options = [];

    foreach($results as $media) {
      // $options[$media->field_media_image->entity->uri->value] = $media->label();
      $options[$media->id()] = $media->label();
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);
    $email = $form_state->getValue('email');
    if (!strpos($email, '@evolvingweb.com')) {
      $form_state->setErrorByName('email', $this->t('Invalid Email'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $mid = $form_state->getValue('media');
    $media = Media::load($mid);

    $uri = NULL;

    switch ($media->bundle()) {
      case 'image':
        $uri = $media->field_media_image->entity->uri->value;

        break;
      case 'video':
        $uri = $media->field_media_oembed_video->value;
        $this->messenger()->addStatus($this->t('The video can be found in @url.', ['@url' => $uri]));
        
        break;
      default:
        // Document of files
        $uri = $media->field_media_file->entity->uri->value;

        break;
    }

    if ($uri) {
      $response = new BinaryFileResponse($uri);
      $response->setContentDisposition('attachment');
      $form_state->setResponse($response);
    }

    // $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('<front>');
  }

}
