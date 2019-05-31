<?php
/**
 * @file
 * Definition of \Drupal\ckeditor_bootstrap_table\Plugin\CKEditorPlugin\BootstrapTable.
 */
 
namespace Drupal\ckeditor_bootstrap_table\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "BootstrapTable" plugin.
 *
 * @CKEditorPlugin(
 *   id = "bt_table",
 *   label = @Translation("Bootstrap table")
 * )
 */
 
class BootstrapTable extends PluginBase implements CKEditorPluginInterface, CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  function getFile() {
    return drupal_get_path('module', 'ckeditor_bootstrap_table') . '/js/plugins/bt_table/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    return TRUE;
  }
}