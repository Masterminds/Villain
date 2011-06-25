<?php
/** @file
 *
 * Defines the class MongoIDField.
 *
 * Created by Matt Butcher on 2011-06-25.
 */

namespace Villain\Content\Type;

/**
 * Defines MongoIDField.
 */
class MongoIdField extends Field {
  
  public function validate($value) {
    return preg_match('/[[:xdigit:]]{24}/', $value) == 1;
    /*
    // A more involved test:
    try {
     $id = new \MongoId($value); 
     
     // Test that the given ID matches the 
     return $value == (string)$id;
    }
    catch (Exception $e) {
      return FALSE;
    }
    */
  }
}