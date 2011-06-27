<?php
/** @file
 *
 * Defines the class URLField.
 *
 * Created by Matt Butcher on 2011-06-27.
 */

namespace Villain\Content\Type;

/**
 * Defines URLField.
 */
class URLField extends Field {

  public function validate($value) {
    return filter_var($value, FILTER_VALIDATE_URL) != FALSE;
  }
  public function normalize($value) {
    return filter_var($value, FILTER_SANITIZE_URL);
  }
  
}