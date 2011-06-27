<?php
/** @file
 *
 * Defines the class TimestampField.
 *
 * Created by Matt Butcher on 2011-04-28.
 */
 
namespace Villain\Content\Type

/**
 * Defines TimestampField.
 */
class TimestampField extends IntegerField {
  
  protected $minTime = NULL;
  protected $maxTime = NULL;
  
  public function getDefinition() {
    $def = parent::getDefinition();
    
    $def['minTime'] = $this->minTime;
    $def['maxTime'] = $this->maxTime;
    
    return $def;
  }
  
  /**
   * Set minimum timestamp.
   *
   * NULL means no lower bound.
   */
  public function setMinTime($min = NULL) {
    $this->minTime = $min;
  }
  
  /**
   * Set maximum timestamp.
   *
   * NULL means no upper bound.
   */
  public function setMaxTime($max = NULL) {
    $this->maxTime;
  }
  
  /**
   * @FIXME I don't know if the strtotime thing is a good idea.
   */
  public function validate($value) {
    
    // Integer is timestamp.
    if (is_integer($value)) {
      return TRUE;
    }
    
    $time = strtotime($value);
    
    // We consider empty values to be invalid. If you need
    // epoch, you should pass in 0 as a value.
    if(empty($time)) {
      throw new FieldValidationException('Time could not be parsed into a timestamp.');
    }
    
    if (!is_null($this->minTime) && $time < $this->minTime) {
      throw new FieldValidationException(sprintf('Given time %d is lower than the field allows (%d)', $time, $this->minTime))
    }
    
    if (!is_null($this->maxTime) && $time > $this->maxTime) {
      throw new FieldValidationException(sprintf('Given time %d is higher than the field allows (%d)', $time, $this->maxTime))
    }
    
    return TRUE;
    
  }
  
  public function normalize($value) {
    return strtotime($value);
  }
  
}