<?php
/**
 * @file
 * Collection holds a collection of form elements.
 *
 * Created by Matt Farina on 2011-04-23
 */

namespace Villain\Form;

/**
 * A Collection for Form elements.
 *
 * @author Matt Farina
 */
class Collection implements \Countable, \Iterator {

  /**
   * The parent element this collection is attached to.
   */
  protected $parent = null;

  /**
   * The elements stored in the collection.
   */
  protected $elements = array();

  /**
   * When creating an instance of the object allow for the parent to be set.
   *
   * @param mixed $parent
   *   (optional) The parent object this collection is attached to.
   */
  public function __construct($parent = null) {
    $this->setParent($parent);
  }

  /**
   * Set the parent the collection is attached to.
   *
   * @param mixed $parent
   *   The parent object this collection is attached to.
   *
   * @return Collection
   *   $this for the current object.
   */
  public function setParent($parent) {
    $this->parent = $parent;

    return $this;
  }

  /**
   * Get the number of elements within the collection.
   *
   * @return int
   *   The number of elements in the collection.
   */
  public function count() {
    return count($this->elements);
  }

  /**
   * Render all of the elements within the collection.
   *
   * @return string
   *   An html string.
   */
  public function render() {
    $output = '';
    foreach ($this->elements as $element) {
      $output .= $element->render() . "\n";
    }
    return $output;
  }

  /**
   * Add a form element to the collection.
   *
   * @param string $name
   *   The name of the form element to add.
   * @param mixed $type
   *   The type of form element to add. This could be one of the following:
   *   - A string of the type (e.g., textfield, password, etc)
   *   - A element object to use.
   *
   * @return mixed
   *   The form element object.
   */
  public function withElement($name, $type) {
    if (is_string($type)) {
      // Create an instance of the element.
      if (isset(Config::$elementMap[$type])) {
        $class = Config::$elementMap[$type];
        $type = new $class;
      }
      else {
        throw new \Villain\Exception('Form element does not exist.');
      }
    }

    // Attach the element
    $type->hasName($name);
    $type->setParent($this);
    $this->elements[$name] = $type;

    return $type;
  }

  /**
   * Remove an element from the collection.
   *
   * @param string $name
   *   The name of the element to remove.
   *
   * @return Collection
   *   $this is returned.
   */
  public function removeElement($name) {
    if (isset($this->elements[$name])) {
      $this->elements[$name]->setParent(null);
      unset($this->elements[$name]);
    }
    return $this;
  }

  /**
   * Return the form object this Collection is attached to.
   *
   * @return Villain\Form\Form
   *   The parent form object up the chian of objects.
   */
  public function returnForm() {
    if ($this->parent) {
      return $this->parent;
    }
    // No parent.
    throw new \Villain\Exception('Collection trying to return parent Form when not attached to a Form.');
  }

  /**
   * Get the names of the elements in the collection.
   *
   * @return array
   *   An array of element names.
   */
  public function getElementNames() {
    return array_keys($this->elements);
  }
  
  /**
   * @see \Iterator::current()
   */
  public function current() {
    return current($this->elements);
  }

  /**
   * @see \Iterator::key()
   */
  public function key() {
    return key($this->elements);
  }

  /**
   * @see \Iterator::next()
   */
  public function next() {
    next($this->elements);
  }

  /**
   * @see \Iterator::rewind()
   */
  public function rewind() {
    reset($this->elements);
  }

  /**
   * @see \Iterator::valid()
   */
  public function valid() {
    $current_position = key($this->elements);
    return isset($this->elements[$current_position]);
  }
}