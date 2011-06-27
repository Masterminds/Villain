<?php
/** @file
 *
 * Defines FloatField.
 */

namespace Villain\Content\Type;

/**
 * Describes a field that contains a floating point value.
 */
class FloatField extends Field {
  public function validate($value) {
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== FALSE;
  }
  public function normalize($value) {
    return (float)filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
  }
}