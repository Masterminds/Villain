<?php
/** @file
 *
 * Defines the class BlogTheme.
 *
 * Created by Matt Butcher on 2011-06-17.
 */
namespace BasicBlog\Theme;
/**
 * Defines BlogTheme.
 */
class BlogTheme extends \BaseThemePackage {
  
  public function templates() {
    return array(
      //'target' => 'path/to/template.tpl.php',
    );
  }
  
  public function functions() {
    return array(
      //'target' => callback,
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