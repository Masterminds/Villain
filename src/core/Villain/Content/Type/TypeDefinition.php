<?php
/** @file
 * 
 */

/**
 * The Villain content type system.
 *
 * This package defines the type system for Villain content.
 *
 * The type system is structured as follows:
 *
 * A TypeDefinition is composed of zero or more Field objects, where each Field 
 * describes an individual storage unit (i.e. a Field) on the object. Villain comes with
 * a variety of predefined fields, including IntegerField for modeling integers, 
 * StringField to model text data, and GroupField to model a group of other fields.
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