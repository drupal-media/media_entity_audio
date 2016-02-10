<?php

/**
 * @file
 * Contains \Drupal\media_entity_audio\Plugin\MediaEntity\Type\Audio.
 */

namespace Drupal\media_entity_audio\Plugin\MediaEntity\Type;

use Drupal\Core\Config\Config;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityManager;
use Drupal\media_entity\MediaBundleInterface;
use Drupal\media_entity\MediaInterface;
use Drupal\media_entity\MediaTypeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides media type plugin for Audio.
 *
 * @MediaType(
 *   id = "audio",
 *   label = @Translation("Audio"),
 *   description = @Translation("Provides business logic and metadata for Audio Files.")
 * )
 */
class Audio extends MediaTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(MediaBundleInterface $bundle) {
    $form = array();

    $options = array();
    $allowed_field_types = array('file');
    foreach ($this->entityFieldManager->getFieldDefinitions('media', $bundle->id()) as $field_name => $field) {
      if (in_array($field->getType(), $allowed_field_types) && !$field->getFieldStorageDefinition()->isBaseField()) {
        $options[$field_name] = $field->getLabel();
      }
    }
    $form['source_field'] = array(
      '#type' => 'select',
      '#title' => t('Field with source information'),
      '#description' => t('Field on media entity that stores Audio file.'),
      '#default_value' => empty($this->configuration['source_field']) ? NULL : $this->configuration['source_field'],
      '#options' => $options,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function providedFields() {

  }

  /**
   * {@inheritdoc}
   */
  public function getField(MediaInterface $media , $name) {

  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultThumbnail() {
    return $this->config->get('icon_base') . '/image.png';
  }

  /**
   * {@inheritdoc}
   */
  public function thumbnail(MediaInterface $media) {
    $source_field = $this->configuration['source_field'];

    /** @var \Drupal\file\FileInterface $file */
    if ($file = $this->entityTypeManager->getStorage('file')->load($media->{$source_field}->target_id)) {
      return $this->config->get('icon_base') . '/image.png';
    }

    return $this->getDefaultThumbnail();
  }

}
