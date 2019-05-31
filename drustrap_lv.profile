<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Entity\ConfigurableLanguage;

/**
 * Implement hook_install_tasks_alter().
 * @param $tasks
 * @param $install_state
 */
function drustrap_lv_install_tasks_alter(&$tasks, $install_state) {
  $needs_installing_languages = !empty($install_state['parameters']['languages']);

  $task = [
    'drustrap_lv_install_additional_languages' => [
      'display_name' => t('Install languages'),
      'display' => $needs_installing_languages,
      'type' => 'batch',
      'run' => $needs_installing_languages ? INSTALL_TASK_RUN_IF_NOT_COMPLETED : INSTALL_TASK_SKIP,
    ],
  ];

  // Add this task before module installation so they are aware of multiple languages.
  $keys = array_keys($tasks);
  $pos = (int) array_search('install_profile_modules', $keys);
  $tasks = array_merge(array_slice($tasks, 0, $pos), $task, array_slice($tasks, $pos));
}

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function drustrap_lv_form_install_select_language_form_alter(&$form, FormStateInterface $form_state, $install_state = NULL) {
  // Disable ability to change default language.
  $form['langcode']['#title'] = 'Default configuration language';
  unset($form['langcode']['#title_display']);
  $form['langcode']['#disabled'] = TRUE;
  $form['langcode']['#default_value'] = 'en';

  // Use the same language list as populated in default language select list and remove english language because it's
  // installed by default.
  $options = $form['langcode']['#options'];
  if (isset($options['en'])) {
    unset($options['en']);
  }
  $form['languages'] = [
    '#type' => 'checkboxes',
    '#title' => 'Additional site languages',
    '#options' => $options,
    '#multiple' => TRUE,
  ];

  $form['#submit'][] = '_drustrap_lv_form_install_select_language_form_submit';
}

/**
 * @param array $form
 * @param FormStateInterface $form_state
 */
function _drustrap_lv_form_install_select_language_form_submit(array &$form, FormStateInterface $form_state) {
  $build_info = $form_state->getBuildInfo();
  $build_info['args'][0]['parameters']['languages'] = array_filter($form_state->getValue('languages'));
}

/**
 * @param $install_state
 */
function drustrap_lv_install_additional_languages($install_state) {
  $languages = $install_state['parameters']['languages'];
  foreach ($languages as $langcode) {
    if (!($language = ConfigurableLanguage::load($langcode))) {
      $language = ConfigurableLanguage::createFromLangcode($langcode);
    }
    $language->save();
  }
}

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function drustrap_lv_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['site_information']['site_mail']['#default_value'] = 'no-replay@wmc.lv';
  $form['admin_account']['account']['name']['#default_value'] = 'wmc_admin';
  $form['admin_account']['account']['mail']['#default_value'] = 'admin@wmc.lv';
  $form['regional_settings']['site_default_country']['#default_value'] = 'LV';
  $form['regional_settings']['date_default_timezone']['#default_value'] = 'Europe/Riga';
  $form['update_notifications']['enable_update_status_emails']['#default_value'] = 'Europe/Riga';
}

/**
 * Implements hook_menu_links_discovered_alter().
 *
 * @param $links
 */
function drustrap_lv_menu_links_discovered_alter(&$links) {
  if (\Drupal::moduleHandler()->moduleExists('admin_toolbar_tools')) {
    $links['system.themes_page']['parent'] = 'admin_toolbar_tools.help';
    $links['system.themes_page']['weight'] = -8;
    $links['system.modules_list']['parent'] = 'admin_toolbar_tools.help';
    $links['system.modules_list']['weight'] = -7;
    $links['system.admin_config']['parent'] = 'admin_toolbar_tools.help';
    $links['system.admin_config']['weight'] = -6;
    $links['system.admin_reports']['parent'] = 'admin_toolbar_tools.help';
    $links['system.admin_reports']['weight'] = -5;
    $links['admin_development']['weight'] = -4;
    $links['system.run_cron']['weight'] = -3;
    $links['system.db_update']['weight'] = -2;
    $links['help.main']['parent'] = 'admin_toolbar_tools.help';
    $links['help.main']['weight'] = -1;
  }
}
