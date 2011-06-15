<?php
/**
 * @file
 * A form html element.
 *
 * Created by Matt Farina on 2011-04-24
 */

namespace Villain\Form;

/**
 * A form html element.
 *
 * @author Matt Farina
 */
class Form extends AbstractElement {
  
  /**
   * The collection holding the form elements.
   */
  protected $collection = null;

  /**
   * The method (get/post) the form should use.
   */
  protected $method = 'post';

  /**
   * The action for the form.
   */
  protected $action = null;
  
  protected $context = null;
  
  /**
   * Setup a form right out of the gate.
   */
  public function __construct(\FortissimoExecutionContext $context) {
    $this->collection = new Collection($this);

    // Set the action to the current url as a default.
    $this->action = $context->getRequestMapper()->baseURL();;
    
    $this->context = $context;
  }

  /**
   * Set the collection to use for this form.
   *
   * @param Collection $collection
   *   The collection object to use on the form.
   *
   * @return Form
   *   $this for the current object.
   */
  public function setCollection($collection) {
    $this->collection = $collection;
    $this->collection->setParent($this);
    return $this;
  }

  /**
   * Get the current collection object.
   *
   * This is useful if you want to do collection specific things like iterate
   * over all the elements in the collection.
   *
   * @return Collection
   *   The current collection object on the form.
   */
  public function getCollection() {
    return $this->collection;
  }

  /**
   * Set the method (get/post) for the form.
   *
   * @param string $method
   *   The method for the form of either get or post.
   *
   * @return Form
   *   $this for the corrent form.
   */
  public function isOfMethod($method = 'post') {
    $this->method = $method;
    return $this;
  }

  /**
   * Set the action for the current form.
   *
   * The default is the current URI.
   *
   * @param string $action
   *   The action for the current form.
   *
   * @return Form
   *   $this for the current form.
   */
  public function hasAction($action) {
    $this->action = $action;
    return $this;
  }

  /**
   * Render the form.
   */
  public function __toString() {
    $output = '<form' . $this->renderAttributes($this->buildAttributes()) . ">\n";
    $output .= $this->collection;
    $output .= "</form>\n";
    return $output;
  }

  /**
   * Build the attributes for the form.
   */
  public function buildAttributes() {
    // Start with the element attributes (e.g., type of text)
    $attributes = $this->elementAttributes();

    // The form attributes.
    $attributes += array(
      'action' => $this->action,
      'method' => $this->method,
    );

    // The attributes that have been set.
    $attributes += $this->attributes;

    return $attributes;
  }
  
  /**
   * Add a new element to the parent collection.
   *
   * @see Villain\Form\Collection::withElement().
   */
  public function withElement($name, $type) {
    if (!is_null($this->collection)) {
      return $this->collection->withElement($name, $type);
    }
    else {
      // we have no parent.
      throw \Villain\Exception('Collection is not properly set.');
    }
  }
  
  public function getElementNames() {
    return $this->collection->getElementNames();
  }
  
  public function removeElement($name) {
    $this->collection->removeElement($name);
    return $this;
  }

  /**
   * @see AbstractElement::elementAttributes()
   */
  public function elementAttributes() {
    return array();
  }

  /**
   * @see AbstractElement::renderElement()
   */
  public function renderElement() {
    return '';
  }
}