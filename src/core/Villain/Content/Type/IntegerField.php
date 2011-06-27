<?php
/** @file
 *
 * Defines IntegerField.
 */

namespace Villain\Content\Type;

/**
 * An IntegerField describes a Field for a TypeDefinition.
 */
class IntegerField extends Field {
  
  protected $min = NULL;
  protected $max = NULL;

  /**
   * Set the minimum allowed value for this field.
   *
   * NULL means no minimum.
   */
  public function setMin($min = NULL) {
    $this->min = $min;
  }
  /**
   * Set the maximum allowed value for this field.
   * 
   * NULL means no max.
   */
  public function setMax($max = NULL) {
    $this->max = $max;
  }

  public function validate($value) {
    $options = array();
    if (isset($this->min)) {
      $options['options']['min_range'] = $this->min;
    }
    if (isset($this->max)) {
      $options['options']['max_range'] = $this->max;
    }
    return filter_var($value, FILTER_VALIDATE_INT, $options) !== FALSE;
  }
  
  public function normalize($value) {
    return (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
  }
  
  public function getDefinition() {
    $def = parent::getDefinition();
    $def['min'] = $min;
    $def['max'] = $max;
  }
}