<?php

namespace Drupal\avatars\Plugin\Field\FieldType;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\options\Plugin\Field\FieldType\ListStringItem;

/**
 * Plugin implementation of the 'list_string' field type.
 *
 * @FieldType(
 *   id = "avatar",
 *   label = @Translation("Avatar"),
 *   description = @Translation("This field stores the avatar generator to use for an entity (typically a user account)."),
 *   category = @Translation("Reference"),
 *   default_widget = "avatars_generator_preview",
 *   default_formatter = "avatar",
 * )
 *
 * We simply override the list_string field in order to keep things nicely
 * separated in the UI.
 */
class AvatarItem extends ListStringItem {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Text value'))
      ->addConstraint('Length', array('max' => 255))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'value' => array(
          'type' => 'varchar',
          'length' => 255,
        ),
      ),
      'indexes' => array(
        'value' => array('value'),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function allowedValuesDescription() {
    $description = '<p>' . t('The possible values this field can contain. Enter one value per line, in the format key|label.');
    $description .= '<br/>' . t('The key is the stored value. The label will be used in displayed values and edit forms.');
    $description .= '<br/>' . t('The label is optional: if a line contains a single string, it will be used as key and label.');
    $description .= '</p>';
    $description .= '<p>' . t('Allowed HTML tags in labels: @tags', array('@tags' => $this->displayAllowedTags())) . '</p>';
    return $description;
  }

  /**
   * {@inheritdoc}
   */
  protected static function validateAllowedValue($option) {
    if (Unicode::strlen($option) > 255) {
      return t('Allowed values list: each key must be a string at most 255 characters long.');
    }
  }

  /**
   * {@inheritdoc}
   */
  protected static function castAllowedValue($value) {
    return (string) $value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettableOptions(AccountInterface $account = NULL) {
    /** @var \Drupal\avatars\AvatarManager $avatarManager */
    $avatarManager = \Drupal::service('avatars.avatar_manager');

    $options = [];

    /** @var \Drupal\avatars\AvatarGeneratorInterface[] $generators */
    $generators = $avatarManager->getAvatarGeneratorsForUser($account);
    
    foreach ($generators as $generator) {
      $pluginDefinition = $generator->getPlugin()->getPluginDefinition();
      $options[$pluginDefinition['id']] = $pluginDefinition['label'];
    }

    return $options;
  }
}
