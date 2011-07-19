<?php
/** @file
 *
 * Defines the class CoreThemePackage.
 *
 * Created by Matt Butcher on 2011-05-12.
 */

namespace Villain\Theme;

/**
 * Defines CoreThemePackage.
 */
class CoreThemePackage extends \BaseThemePackage {
  
  public function templates() {
    return array(
      //'target' => 'path/to/template.tpl.php',
      'html' => __DIR__ . '/templates/html.tpl.php',
    );
  }
  
  public function functions() {
    return array(
      //'target' => callback,
      // General theme functions.
      'attributes' => array('Villain\Theme\Functions', 'renderAttributes'),
      
      // Form theming.
      'form.textfield' => array('Villain\Theme\Form', 'textfield'),

      // Region theming.
      'region.item' => array('Villain\Theme\Functions', 'regionItem'),
      'region.wrapper' => array('Villain\Theme\Functions', 'regionWrapper'),
    );
  }
  
  public function preprocessors() {
    return array(
      // 'target' => preprocessor_callback
    );
  }
  
  public function postprocessors() {
    return array(
      //'target' => postprocessor_callback,
    );
  }
}