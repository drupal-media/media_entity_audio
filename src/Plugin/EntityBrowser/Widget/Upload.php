<?php

/**
 * @file
 * Contains \Drupal\media_entity_audio\Plugin\EntityBrowser\Widget\Upload.
 */

namespace Drupal\media_entity_audio\Plugin\EntityBrowser\Widget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_browser\Plugin\EntityBrowser\Widget\Upload as FileUpload;

/**
 * Uses upload to create media entity audios.
 *
 * @EntityBrowserWidget(
 *   id = "media_entity_audio_upload",
 *   label = @Translation("Upload audio files"),
 *   description = @Translation("Upload widget that creates media entity audios.")
 * )
 */
class Upload extends FileUpload {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'extensions' => 'mp3 wav ogg',
      'media bundle' => NULL,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function getForm(array &$original_form, FormStateInterface $form_state, array $aditional_widget_parameters) {
    /** @var \Drupal\media_entity\MediaBundleInterface $bundle */
    if (!$this->configuration['media bundle'] || !($bundle = $this->entityManager->getStorage('media_bundle')->load($this->configuration['media bundle']))) {
      return ['#markup' => t('The media bundle is not configured correctly.')];
    }

    if ($bundle->getType()->getPluginId() != 'audio') {
      return ['#markup' => t('The configured bundle is not using audio plugin.')];
    }

    $form = parent::getForm($original_form, $form_state, $aditional_widget_parameters);
    $form['upload']['upload_validators']['file_validate_extensions'] = [$this->configuration['extensions']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array &$element, array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\media_entity\MediaBundleInterface $bundle */
    $bundle = $this->entityManager
      ->getStorage('media_bundle')
      ->load($this->configuration['media bundle']);
    $files = $this->extractFiles($form_state);

    $audios = [];
    foreach ($files as $file) {
      /** @var \Drupal\media_entity\MediaInterface $audio */
      $audio = $this->entityManager->getStorage('media')->create([
        'bundle' => $bundle->id(),
        $bundle->getTypeConfiguration()['source_field'] => $file,
      ]);
      $audio->save();
      $audios[] = $audio;
    }

    $this->selectEntities($audios);
    $this->clearFormValues($element, $form_state);
  }

}
