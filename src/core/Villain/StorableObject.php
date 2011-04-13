<?php
/** @file
 * Declaration of the StorableObject.
 *
 * This file declares the StorableObject.
 */

namespace Villain;

/**
 * An object which can provide a storable version of itself.
 *
 * StorableObjects are designed to be pure data classes, similar in intent
 * to Java Beans or similar technologies. 
 *
 * The guiding philosophy of the StorableObject is that it should represent
 * a data object that can be translated into a PHP array. The PHP array can then
 * be easily stored by any number of backends, from MongoDB to a simple serialization 
 * call.
 *
 * But to keep this fast, the assumption is that the exporting and importing operations
 * can be done with only minimal processing -- and things like data integrity checking 
 * are simply not performed during these operations. Should you need to ensure integrity,
 * you can extend the toArray() and fromArray() methods accordingly. However, this is
 * discouraged because it slows down a crucial layer of the system.
 *
 * THE IMPLEMENTATION
 *
 * The idea of this class is to provide an Infinite Decorator implementation. The base
 * class (StorableObject) will store any name/values that it is given without performing
 * any checks or whatnot on them. It will store them in a way that makes it easy for 
 * the implementation to generate serialized arrays.
 *
 * The base class also implements the __get() and __set() magic methods, which means that
 * any arbitrary property can be set or retrieved using those accessors. It also implements
 * a magic __call() function which handles setter/getter calls.
 * 
 */
class StorableObject implements Storable {
  
  // CLASS:
  
  /**
   * Create a new instance from an array.
   *
   * This is a convenience function that can be used in lieu of creating 
   * a new object and then instantiating it.
   */
  public static function newFromArray($array) {
    
    $klass = get_called_class();
    $o = new $klass();
    $o->fromArray($array);
    
    return $o;
  }
  
  // INSTANCE:
  
  protected $storage = array();
  
  public function __get($name) {
    // Use isset to avoid E_STRICT errors.
    return isset($this->storage[$name]) ? $this->storage[$name] : NULL;
  }
  
  public function __set($name, $value) {
    $this->storage[$name] = $value;
  }
  
  public function __isset($name) {
    return isset($this->storage[$name]);
  }
  
  public function __unset($name) {
    unset($this->storage[$name]);
  }
  
  public function __call($name, $args) {
    $pre = substr($name, 0, 3);
    $the_rest = substr($name, 3);
    
    // Nothing we can do in this case.
    if (empty($the_rest)) return;
    
    $the_rest = lcfirst($the_rest);
    
    switch ($pre) {
      case 'get':
        return $this->$the_rest;
      case 'set':
        if(empty($args)) {
          throw new Exception($name . ' requires a value parameter.');
        }
        return $this->$the_rest = $args[0];
    }
    
    throw new Exception('Unknown method called: ' . $name);
  }
  
  /**
   * Guaranteed function to do primordial get operations.
   *
   * With this model, we need an ensured way to allow subclasses to
   * set the actual value without being intercepted by another function.
   *
   * This should be used with care, as misuse can violate assumptions
   * about the data, and thus compromise data integrity.
   */
  protected final function primordialGet($name) {
    return $this->storage[$name];
  }
  /**
   * Guaranteed function to do primordial set operations.
   *
   * With this model, we need an ensured way to allow subclasses to
   * set the actual value without being intercepted by another function.
   *
   * This should be used with care, as misuse can violate assumptions
   * about the data, and thus compromise data integrity.
   */
  protected final function primordialSet($name, $value) {
    $this->storage[$name] = $value;
  }
  
  public function toArray() {
    return $this->storage;
  }
  
  
  public function fromArray(array $data) {
    $this->storage = $data;
  }
}