<?php
/** 
 * @file
 * Default theming functions.
 *
 * Created by Matt Farina on 2011-07-16.
 */

namespace Villain\Theme;

class Functions {

  /**
   * Render an attributes array into html element attributes.
   *
   * @param array $attributes
   *   An array of attributes to convert to html attributes.
   *
   * @return string
   *   The array of attributes as a string for use in a html element.
   */
  public static function renderAttributes(array $attributes) {
    foreach ($attributes as $attribute => &$data) {
      $data = isset($data) ? implode(' ', (array) $data) : null;
      $data = $attribute . (isset($data) ? '="' . $data . '"' : '');
    }

    return $attributes ? ' ' . implode(' ', $attributes) : '';
  }

  /**
   * A wrapper around Region Items.
   *
   * @param string $content
   *   The html content for the Region Item.
   *
   * @return string
   *   The html content for a Region Item with the content of this function
   *   applied as a wrapper.
   */
  public static function regionItem($content) {
    return $content;
  }

  /**
   * A wrapper around Region.
   *
   * @param string $content
   *   The html content for the Region.
   *
   * @return string
   *   The html content for a Region with the content of this function
   *   applied as a wrapper.
   */
  public static function regionWrapper($content) {
    return $content;
  }
  
}