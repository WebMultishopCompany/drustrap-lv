<?php

namespace Drupal\drustrap_theme\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;

/** *
 * @Block(
 *   id = "logo",
 *   admin_label = @Translation("Logo")
 * )
 */
class Logo extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#markup' => '',
    ];

    $link_markup = '';

    $theme_logo_settings = theme_get_setting('logo');
    if (!is_null($theme_logo_settings)) {
      $link_markup .= '<img src="' . $theme_logo_settings['url'] . '" alt="' . t('Logo') . '" class="img-responsive logo-main" />';
    }

    $build['#markup'] .= Link::createFromRoute([
      '#markup' => $link_markup,
    ], '<front>')->toString();

    return $build;
  }
}
