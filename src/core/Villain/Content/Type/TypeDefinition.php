<?php
/** @file
 * 
 */

namespace Villain\Content\Type;

/**
 * Describes a storable type for Villain.
 */
class TypeDefinition {
  
  protected $name = NULL;
  protected $label = NULL;
  protected $fields = array();
  
  public function __construct($name, $label) {
    $this->name = $name;
    $this->label = $label;
  }
  
  public function addField($field) {
    $this->fields[] = $field;
  }
  
  public function getDefinition() {
    $data = array(
      'name' => $this->name,
      'label' => $this->label,
    );
    
    foreach ($fields as $field) {
      $data[$field->getName()] = $field->getDefinition();
    }
    
    return $data;
  }  
}