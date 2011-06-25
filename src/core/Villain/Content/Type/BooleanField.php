<?php
/** @file
 *
 * Defines the class BooleanField.
 *
 * Created by Matt Butcher on 2011-06-25.
 */

namespace Villain\Content\Type;

/**
 * Defines a field that holds true/false, on/off, yes/no data.
 *
 * This allows the following values as TRUE:
 * - TRUE
 * - 1
 * - 'on'
 * - 'true'
 * - 'yes'
 * 
 * For FALSE:
 * - FALSE
 * - 0
 * - 'off'
 * - 'no'
 * - 'false'
 * - NULL
 */
class BooleanField extends Field {
  public function validate($value) {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
  }
}