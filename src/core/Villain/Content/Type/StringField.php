<?php
/** @file
 *
 * Defines the class StringField.
 *
 * Created by Matt Butcher on 2011-04-28.
 */

namespace Villain\Content\Type;

/**
 * A StringField stores a string.
 */
class StringField extends Field {
  
  protected $strict = FALSE;
  
  /**
   * Normally, validate() allows other scalars (integers, floats) to be treated as strings.
   *
   * If strict is set then validate() will enforce that the value be a string,
   * and not some other scalar.
   */
  public function setStrict($use_strict = FALSE) {
    $this->strict = $use_strict;
  }
  
  /**
   * Set the maximum length allowed for the string.
   *
   * This will be checked during validate().
   */
  public function setMaxLength($len = -1) {
    $this->maxlen = $len;
  }
  
  public function validate($value) {
    
    if ($this->strict && !is_string($value)) {
      throw new FieldValidationException('Given value is not a string (checking was strict).');
    }
    
    if (!is_scalar($value)) {
      throw new FieldValidationException('Given value is not a string.');
    }

    // Need to use filter_var to filter from here.
    throw new FieldValidationException('FINISH ME');
    
    return TRUE;
  }
  
  public function getDefinition() {
    $def = parent::getDefinition();
    $def['maxlen'] = $this->maxlen;
    $def['strict'] = $this->strict;
  }
}