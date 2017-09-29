<?php

namespace Drupal\media_entity_audio\Plugin\media\Source;

use Drupal\media\MediaTypeInterface;
use Drupal\media\Plugin\media\Source\File;

/**
 * Provides media type plugin for Audio.
 *
 * @MediaSource(
 *   id = "audio",
 *   label = @Translation("Audio"),
 *   description = @Translation("Provides business logic and metadata for Audio Files."),
 *   allowed_field_types = {"file"},
 *   default_thumbnail_filename = "audio.png",
 * )
 */
class Audio extends File {

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type) {
    return parent::createSourceField($type)->set('settings', ['file_extensions' => 'mp3 ogg wav wma aiff aac']);
  }

}
