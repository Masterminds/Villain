<?php
/** 
 * @file
 * Default theming for forms.
 *
 * Created by Matt Farina on 2011-06-07.
 */

namespace Villain\Theme;

class Form {
  
  public static function textfield($variables) {
    return '<input' . $variables['element']->renderAttributes($variables['element']->buildAttributes()) . ">\n";
  }
  
}