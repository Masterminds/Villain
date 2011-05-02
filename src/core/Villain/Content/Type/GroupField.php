<?php
/** @file
 *
 * Defines the class GroupField.
 *
 * Created by Matt Butcher on 2011-04-28.
 */

namespace Villain\Content\Type;

/**
 * A GroupField is a field used to encapsulate a group of related fields..
 */
class GroupField extends Field {
  
  protected $fields = array();
  
  /**
   * Add a new field to the definition.
   *
   * A field can be any valid Field instance, including another GroupField.
   */
  public function addField(Field $f) {
    $this->fields[] = $f;
  }
  
  public function setDefaultValue($val) {
    throw new Exception('Cannot set a default value on a group of fields.');
  }
  
  /**
   * Set an entire array of Field objects at once.
   */
  public function setFields(array $fields) {
    $this->fields = $fields;
  }
  
  public function getDefinition() {
    $def = parent::getDefinition();
    foreach ($this->fields as $f) {
      $def[$f->getName()] = $f->getDefinition();
    }
  }
  
  public function validate() {
    // Nothing really to validate.
    return TRUE;
  }
}