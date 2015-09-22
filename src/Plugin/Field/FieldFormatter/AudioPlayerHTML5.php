<?php

/**
 * @file
 * Contains \Drupal\media_entity_audio\Plugin\Field\FieldFormatter\AudioPlayerHTML5.
 */

namespace Drupal\media_entity_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

//use Drupal\file\Plugin\Field\FieldFormatter\GenericFileFormatter;

/**
 * Plugin implementation of the 'Audio Player (HTML5)' formatter.
 *
 * @FieldFormatter(
 *   id = "audio_player_html5",
 *   label = @Translation("Audio Player (HTML5)"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AudioPlayerHTML5 extends AudioPlayerBase implements ContainerFactoryPluginInterface{


/**
   * Constructs an ImageFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings']
    );
  }



	/**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings['provide_download_link'] = TRUE;
	$settings['audio_attributes']='';
	
    return $settings;
  }
  
  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['provide_download_link'] = [
      '#title' => $this->t('Provide Download Link'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('provide_download_link'),
    ];
    
    $form['audio_attributes'] = [
      '#title' => $this->t('Audio Tag Attributes'),
      '#type' => 'textfield',
      '#description'=>'Give values Like controls preload="auto" loop',
      '#default_value' => $this->getSetting('audio_attributes'),
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items) {
    $elements = array();
	//var_dump($this);die("ARIT");
	$provide_download_link=$this->getSetting('provide_download_link');
	$audio_attributes = $this->getSetting('audio_attributes');
	//var_dump($audio_attributes);die("HELLO");
    foreach ($this->getEntitiesToView($items) as $delta => $file) {
      $item = $file->_referringItem;
      $elements[$delta] = array(
        '#theme' => 'media_file_formatter',
        '#file' => $file,
        '#description' => $item->description,
        '#value'=>$provide_download_link,
        '#extravalue'=>$audio_attributes,
        '#cache' => array(
          'tags' => $file->getCacheTags(),
          
        ),
      );
      // Pass field item attributes to the theme function.
      if (isset($item->_attributes)) {
        $elements[$delta] += array('#attributes' => array());
        $elements[$delta]['#attributes'] += $item->_attributes;
        // Unset field item attributes since they have been included in the
        // formatter output and should not be rendered in the field template.
        unset($item->_attributes);
      }
    }
    /*if (!empty($elements)) {
      $elements['#attached'] = array(
        'library' => array('file/drupal.file.formatter.generic'),
      );
    }*/
    
    
    //var_dump($elements);die("HELLO");
    return $elements;
  }
  
  /**
   * {@inheritdoc}
   */
  /*public function settingsForm(array $form, FormStateInterface $form_state) {
    

    return $element;
  }*/

}

