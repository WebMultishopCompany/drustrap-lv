<?php

namespace Drupal\ckeditor_bootstrap_include\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "CKEditor Bootstrap Include" plugin.
 *
 * @CKEditorPlugin(
 *   id = "jsplus_bootstrap_include",
 *   label = @Translation("CKEditor Bootstrap Include"),
 *   module = "ckeditor_bootstrap_include"
 * )
 */
class ckeditor_bootstrap_include extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface, CKEditorPluginContextualInterface {

  public $plugins = array(
     'jsplus_bootstrap_include' => array(
         'urlDoc' => 'http://js.plus/products/bootstrap-tools/bootstrap-include-css-js-plugin',
         'params' => array(
            array(
                'name' => 'jsplus_bootstrap_include_version',
                'default' => '3',
                'type' => 'int',
                'order' => 1000,
                'title' => 'Bootstrap version',
                'hint' => '',
                'widget' => 'select',
                'widgetOptions' => array( '3' => 'Bootstrap v.3', '4' => 'Bootstrap v.4'),
            ),
            array(
                'name' => 'jsplus_bootstrap_include_url',
                'default' => '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/',
                'type' => 'str',
                'order' => 1010,
                'title' => 'Bootstrap URL',
                'hint' => 'Path to Bootstrap directory',
                'widget' => 'text',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_css_to_global_doc',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1020,
                'title' => 'Add Bootstrap CSS to theme (outside of CKEditor)',
                'hint' => 'Only for pages with CKEditor',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_js_to_global_doc',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1030,
                'title' => 'Add Bootstrap JS to theme (outside of CKEditor)',
                'hint' => 'Only for pages with CKEditor',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_jquery',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1040,
                'title' => 'Include JQuery',
                'hint' => 'Required by some Bootstrap widgets',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_in_container',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1050,
                'title' => 'Wrap with container tag',
                'hint' => 'Do not check if your content is already inside &lt;div class="container"&gt; tag',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_ie_fix',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1060,
                'title' => 'Add fixes for old IE',
                'hint' => 'Two JavaScripts for legacy IE',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_use_theme',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1070,
                'title' => 'Include Bootstrap theme',
                'hint' => 'Uncheck if you use your own one and include it manually',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_use_wet',
                'default' => FALSE,
                'type' => 'bool',
                'order' => 1080,
                'title' => 'Use WET (Web Experience Kit)',
                'hint' => 'Only for Bootstrap v.3 WET edition users',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_preview_styles',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1090,
                'title' => 'Inline some Bootstrap styles used for previews',
                'hint' => 'If you have not a Bootstrap theme but want to see preview i. e. in Button plugin',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_prevent_removing_divs',
                'default' => TRUE,
                'type' => 'bool',
                'order' => 1100,
                'title' => 'Prevent removing empty &lt;div&gt;&apos;s',
                'hint' => 'A little workaround. Uncheck if it does not work fine',
                'widget' => 'checkbox',
            ),
            array(
                'name' => 'jsplus_bootstrap_include_bw_icons',
                'default' => FALSE,
                'type' => 'bool',
                'order' => 1110,
                'title' => 'Black & white icons',
                'hint' => 'By default all toolbar icons are purple',
                'widget' => 'checkbox',
            ),
         )
     )
  );

    /**
     * {@inheritdoc}
     */
    public function getButtons() {
        if (!$this->isInstalled())
            return array();

        $buttons = array();
        foreach ($this->plugins as $pluginName => $pluginDef) {

            if (isset($pluginDef['buttons'])) {
                foreach($pluginDef['buttons'] as $buttonName => $buttonDef) {
                    $image = '/icons/' . $pluginName . '.png';
                    if (isset($buttonDef['image']))
                        $image = $pluginDef['image'];
                    $button = array(
                        'label' => $buttonDef['label'],
                        'image' => libraries_get_path('jsplus_bootstrap_include') . $image
                    );
                    $buttons[$pluginName] = $button;
                }
            }

        }
        return $buttons;
    }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return libraries_get_path('jsplus_bootstrap_include') . '/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    return isset($editor->getSettings()['plugins']['jsplus_bootstrap_include']);
  }

  function getConfigParam($settings, $param, $default, $type, $inside) {
    $name = $param;
    if ($inside != null)
        $name = $inside . '.' . $name;
    if (isset($settings[$name]) && strlen($settings[$name]) > 0)
        $value = $settings[$name];
    else
        $value = $default;
    if (isset($type) && $type == 'int') {
        $value = intval($value);
    } else if (isset($type) && $type == 'bool') {
        if ($value == '1')
            $value = true;
        else if ($value == '0')
            $value = false;
    } else if (isset($type) && $type == 'json') {
        $value = json_decode($value);
    }
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $settings = $editor->getSettings()['plugins']['jsplus_bootstrap_include'];
    $result = array();
    foreach ($this->plugins as $pluginName => $pluginDef) {
        if (isset($pluginDef['params'])) {
            foreach ($pluginDef['params'] as $paramDef) {
                $value = $this->getConfigParam($settings, $paramDef['name'], $paramDef['default'], $paramDef['type'], isset($paramDef['inside']) ? $paramDef['inside'] : null);
                $key = null;
                if (!isset($pluginDef['inside']))
                    $key = $paramDef['name'];
                else {
                    if (!isset($result[$paramDef['inside']]))
                        $result[$paramDef['inside']] = array();
                    $key = $paramDef['inside'][$paramDef['name']];
                }
                $result[$key] = $value;
            }
        }
    }

    return $result;
  }

  function addSelectToForm(& $form, $settings, $param, $title, $default, $options, $inside, $urlHelp) {
      $form[$param] = array(
        '#type' => 'select',
        '#title' => $title,
        '#options' => $options,
        '#default_value' => $this->getConfigParam($settings, $param, $default, 'str', $inside),
        '#attributes' => array('data-url-help' => $urlHelp == '' ? '' : ($urlHelp . '#' . $param), 'data-param-name' => $param)
      );
  }

  function addTextboxToForm(& $form, $settings, $param, $title, $default, $desc, $inside, $urlHelp) {
        $form[$param] = array(
          '#type' => 'textfield',
          '#title' => $title,
          '#default_value' => $this->getConfigParam($settings, $param, $default, 'str', $inside),
          '#description' => $desc,
          '#attributes' => array('data-url-help' => $urlHelp == '' ? '' : ($urlHelp . '#' . $param), 'data-param-name' => $param)
        );
  }

  function addTextareaToForm(& $form, $settings, $param, $title, $default, $desc, $inside, $urlHelp) {
        $form[$param] = array(
          '#type' => 'textarea',
          '#title' => $title,
          '#default_value' => $this->getConfigParam($settings, $param, $default, 'str', $inside),
          '#description' => $desc,
          '#attributes' => array('data-url-help' => $urlHelp == '' ? '' : ($urlHelp . '#' . $param), 'data-param-name' => $param)
        );
  }

  function addCheckboxToForm(& $form, $settings, $param, $title, $default, $desc, $inside, $urlHelp) {
        $form[$param] = array(
          '#type' => 'checkbox',
          '#title' => $title,
          '#default_value' => $this->getConfigParam($settings, $param, $default, 'bool', $inside),
          '#description' => $desc,
          '#attributes' => array('data-url-help' => $urlHelp == '' ? '' : ($urlHelp . '#' . $param), 'data-param-name' => $param)
        );
  }

  public function isInstalled() {
    return libraries_get_path('jsplus_bootstrap_include');
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    $editor_settings = $editor->getSettings();
    if (isset($editor_settings['plugins']['jsplus_bootstrap_include'])) {
      $settings = $editor_settings['plugins']['jsplus_bootstrap_include'];
    } else {
      $settings = [];
    }

    $form['#attached']['library'][] = 'ckeditor_bootstrap_include/ckeditor_bootstrap_include.admin';

    if (!$this->isInstalled()) {
        $form['warning'] = array(
            '#markup' => 'Looks like CKEditor Bootstrap Include (Drupal 8 module) is installed but CKEditor add-ons not found. In order to use this module please copy CKEditor add-ons into "libraries" forder in the root of your Drupal 8 installation (create the directory if it does not exist).'
        );
        return $form;
    }

    $params = array();
    foreach ($this->plugins as $pluginName => $pluginDef) {
        if (isset($pluginDef['params']))
            foreach ($pluginDef['params'] as $paramDef) {
                $paramDef['urlDoc'] = $pluginDef['urlDoc'];
                $params[$paramDef['order']] = $paramDef;
            }
    }
    ksort($params); // sort by key (order)

    foreach ($params as $order => $paramDef) {
        $inside = isset($paramDef['inside']) ? $paramDef['inside'] : null;
        if ($paramDef['widget'] == 'select') {
            $this->addSelectToForm($form, $settings, $paramDef['name'], t($paramDef['title']), $paramDef['default'], $paramDef['widgetOptions'], $inside, $paramDef['urlDoc']);
        } else if ($paramDef['widget'] == 'checkbox') {
            $this->addCheckboxToForm($form, $settings, $paramDef['name'], t($paramDef['title']), $paramDef['default'], $paramDef['hint'], $inside, $paramDef['urlDoc']);
        } else if ($paramDef['widget'] == 'textarea') {
            $this->addTextareaToForm($form, $settings, $paramDef['name'], t($paramDef['title']), $paramDef['default'], $paramDef['hint'], $inside, $paramDef['urlDoc']);
        } else {
            $this->addTextboxToForm($form, $settings, $paramDef['name'], t($paramDef['title']), $paramDef['default'], $paramDef['hint'], $inside, $paramDef['urlDoc']);
        }
    }

    return $form;
  }

}
