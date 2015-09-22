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
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityManager $entity_manager
   *   Entity manager service.
   * @param \Drupal\Core\Image\ImageFactory $image_factory
   *   The image factory.
   * @param \Drupal\Core\Config\Config $config
   *   Media entity config object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManager $entity_manager, Config $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_manager, $config);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager'),
      $container->get('config.factory')->get('media_entity.settings')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(MediaBundleInterface $bundle) {
    $form = array();

    $options = array();
    $allowed_field_types = array('file');
    foreach ($this->entityManager->getFieldDefinitions('media', $bundle->id()) as $field_name => $field) {
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
  public function validate(MediaInterface $media) {
    // This should be handled by Drupal core.
  }

  
  /**
   * {@inheritdoc}
   */
  public function providedFields() {
    
  }

  /**
   * {@inheritdoc}
   */
  public function getField(MediaInterface $media, $name) {
	  
	$source_field = $this->configuration['source_field'];
    $property_name = $media->{$source_field}->first()->mainPropertyName();
    
    
    $file = $this->entityManager->getStorage('file')->load($media->{$source_field}->first()->{$property_name});
    
    $uri = $file->getFileUri();

   
    return FALSE;
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function thumbnail(MediaInterface $media) {

    $source_field = $this->configuration['source_field'];

    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityManager->getStorage('file')->load($media->{$source_field}->target_id);

    if (!$file) {
      return $this->config->get('icon_base') . '/image.png';
    }

    return $file->getFileUri();
  }
  
}
