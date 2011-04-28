<?php
/** @file
 *
 * Defines IntegerField.
 */

namespace Villain\Content\Type;

/**
 * An IntegerField describes a Field for a TypeDefinition.
 */
class IntegerField {
  
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
    throw new FieldValidationException('Not implemented.')
  }
  
  public function getDefinition() {
    $def = parent::getDefinition();
    $def['min'] = $min;
    $def['max'] = $max;
  }
}