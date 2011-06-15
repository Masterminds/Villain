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
    );
  }
  
  public function functions() {
    return array(
      //'target' => callback,
      'form.textfield' => array('Villain\Theme\Form', 'textfield'),
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