<?php

/**
 * @file
 * Contains \Drupal\avatars_robohash\Plugin\AvatarGenerator\Robohash.
 */

namespace Drupal\avatars_robohash\Plugin\AvatarGenerator;

use Drupal\avatars\Plugin\AvatarGenerator\AvatarGeneratorBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\avatars_robohash\Robohash as RobohashAPI;

/**
 * Robohash robots avatar generator.
 *
 * @AvatarGenerator(
 *   id = "robohash_robots",
 *   label = @Translation("Robots"),
 *   description = @Translation("Robots from Robohash.org"),
 *   fallback = TRUE,
 *   dynamic = FALSE,
 *   remote = TRUE
 * )
 */
class Robohash extends AvatarGeneratorBase {

  /**
   * {@inheritdoc}
   */
  public function generateUri(AccountInterface $account) {
    $robohash = new RobohashAPI();
    return $robohash
      ->setIdentifier($this->getIdentifier($account))
      ->setType('robot')
      ->getUrl();
  }

}