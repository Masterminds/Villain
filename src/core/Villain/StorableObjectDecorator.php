<?php
/** @file
 * Declares the generic decorator for StorableObject.
 */


namespace Villain;

/**
 * The decorator that can be extended and used with any storable.
 *
 * A StorableObject provides a generic object capable of preparing
 * itself for storage. But its inheritance model, limited by PHP's
 * single-inheritance closed class structure, cannot allow mixing
 * in other classes.
 *
 * To support mixing in additional features, we implement a decorator
 * pattern with a twist. It allows the attachment of arbitrary methods.
 *
 * The object itself implements the entire StorableObject 
 */
class StorableObjectDecorator implements Storable {
  
  /**
   * The wrapper StorableObject.
   *
   * This is the object passed into the constructor. Any 
   * supported methods can be called on this.
   */
  protected $inner = NULL;
  
  public function __construct(StorableObject $o) {
    $this->inner = $o;
  }
  
  public function __set($name, $value) {
    $this->inner->$name = $value;
  }
  
  public function __get($name) {
    return $this->inner->$name;
  }
  
  public function __isset($name) {
    return isset($this->inner->$name);
  }
  
  public function __call($name, $args) {
    return $this->inner->$name($args);
  }
  
  public function toArray() {
    return $this->inner->toArray();
  }
  
  public function fromArray(array $array) {
    return $this->inner->fromArray();
  }
  
  /**
   * Get the wrapped object.
   *
   * @return StorableObject
   *  The storable object that this decorator wraps.
   */
  final public function unwrap() {
    return $this->o;
  }
}