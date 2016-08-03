<?php

namespace Drupal\avatars\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Plugin implementation of the 'image' field type.
 *
 * @FieldType(
 *   id = "avatar",
 *   label = @Translation("Avatar"),
 *   description = @Translation("This field stores information about the avatar generator to use and the ID of an image file as an integer value."),
 *   category = @Translation("Reference"),
 *   default_widget = "image_avatar",
 *   default_formatter = "avatar",
 * )
 */
class AvatarItem extends ImageItem {
  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return array(
      'generator' => 'user',
    ) + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = parent::schema($field_definition);

    $schema['columns']['generator'] = array(
      'description' => "The avatar generator selected.",
      'type' => 'varchar',
      'length' => 512,
    );
    $schema['columns']['target_id']['description'] = 'In case of a locally uploaded file, the ID of the file entity.';

    return $schema;
  }
}
