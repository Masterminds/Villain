<?php
/** @file
 *
 * Defines the class EmailField.
 *
 * Created by Matt Butcher on 2011-06-27.
 */

namespace Villain\Content\Type;

/**
 * Provides an Email field.
 */
class EmailField extends Field {

  public function validate($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) != FALSE;
  }
  public function normalize($value) {
    filter_var($value, FILTER_SANITIZE_EMAIL);
  }
}