<?php
/** @file
 * This file contains the definition for the base Field class.
 */

namespace Villain\Content\Type;

/**
 * The base class for fields on a type definition.
 */
abstract class Field {
  
  protected $name = NULL;
  protected $label = NULL;
  protected $decscription = NULL;
  protected $max_repeat = 1;
  protected $min_repeat = 0;
  protected $default_value = NULL;
  
  /**
   * Construct a new Field.
   *
   * A Field should have at least a name and label.
   *
   * @param string $name
   *  The machine-readable name of the field.
   * @param string $label
   *  The human-readable label of this field.
   */
  public function __construct($name, $label) {
    $this->name = $name;
    $this->label = $label;
  }
  public function setDescription($desc) {
    $this->description = $desc;
  }
  
  public function setDefaultValue($val) {
    $this->default_value = $val;
  }
  public function getDefaultValue() {
    return $this->defaultValue;
  }
  
  
  /**
   * Return the machine-readable name of the field.
   * @return string
   *  The name of the field.
   */
  public function getName() {
    return $this->name;
  }
  /**
   * Get the definition of the field.
   * @return array
   *  An associative array that defines the field.
   */
  public function getDefinition() {
    return array(
      'name' => $this->name,
      'label' => $this->label,
      'max_repeat' => $this->max_repeat,
      'min_repeat' => $this->min_repeat,
      'description' => $this->description,
      'default_value' => $this->default_value,
    );
  }
  
  /**
   * Set how often this field can repeat.
   * 
   * Max Examples:
   * 0: No values ever
   * 1: one value
   * -1: indefinite (as many as one wants)
   * 14: up to 14 values
   *
   * Min examples:
   * 0: Nothing required
   * 1: At least one.
   * 35: At least 35.
   *
   * @param int $how_often
   *  How often this field should repeat.
   */
  public function repeats($max = 1, $min = 0) {
    $this->max_repeat = $max;
    $this->min_repeat = $min;
    return $this;
  }
  /**
   * Get the maximum number of times a field can repeat.
   *
   * The value 1 indicates that a field can only appear once. 0 means it can never
   * appear, <0 means it can repeat indefinitely. For any other integer value N, the field
   * will be repeatable N times.
   *
   * @return int
   *  The maximum number of times a field can repeat.
   */
  public function getMaxRepeat() {
    return $this->max_repeat;
  }
  /**
   * Get the minimum number of times a field can repeat.
   *
   * @return int
   *  The minimum number of times a field can repeat.
   */
  public function getMinRepeat() {
    return $this->min_repeat;
  }
  /**
   * Validate the field.
   * @throws FieldValidationException if the field does not validate.
   */
  abstract function validate($value);
  
  

}