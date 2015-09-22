<?php

/**
 * @file
 * Contains \Drupal\media_entity_audio\Plugin\Field\FieldFormatter\AudioPlayerBase.
 */

namespace Drupal\media_entity_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\field\FieldConfigInterface;
use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;

/**
 * Base class for Audio Player file formatters.
 */
abstract class AudioPlayerBase extends FileFormatterBase {

  /**
   * {@inheritdoc}
   */
  protected function getEntitiesToView(EntityReferenceFieldItemListInterface $items) {
	  
    return parent::getEntitiesToView($items);
  
  }
  

}
