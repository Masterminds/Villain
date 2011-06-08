<?php
/**
 * @file
 * An abstract base for form elements.
 *
 * Created by Matt Farina on 2011-04-23
 */

namespace Villain\Form;

/**
 * An abstract base for form elements.
 *
 * There are two abstract methods which need to be filled in.
 * - renderElement()
 * - elementAttributes()
 *
 * @todo Add mroe documentation (examples) about these two abastract methods.
 *
 * @author Matt Farina
 */
abstract class AbstractElement {

  /**
   * The parent collection element.
   */
  protected $parent = null;

  /**
   * The element attributes.
   */
  protected $attributes = array();

  /**
   * The label attributes.
   */
  protected $labelAttributes = array();

  /**
   * The elements label.
   */
  protected $label = null;

  /**
   * The elements description.
   */
  protected $description = null;

  /**
   * The elements disabled status.
   */
  protected $disabled = null;

  /**
   * The elements default value.
   */
  protected $defaultValue = null;

  /**
   * The name of the element.
   */
  protected $name = null;

  /**
   * Set the name for the element.
   *
   * @param string $name
   *   The name of the element.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function hasName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Set the parent element/collection.
   *
   * @param Collection $parent
   *   The parent element/collection.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function setParent(Collection $parent) {
    $this->parent = $parent;
    return $this;
  }

  /**
   * Add a new element to the parent collection.
   *
   * @see Villain\Form\Collection::withElement().
   */
  public function withElement($name, $type) {
    if (!is_null($this->parent)) {
      return $this->parent->withElement($name, $type);
    }
    else {
      // we have no parent.
      throw Villain\Exception('Parent Form Element is not properly set.');
    }
  }
  
  /**
   * Render the form element.
   *
   * @return string
   *   The html for the form element.
   */
  public function __toString() {
    $output = '<div>'; // @todo Add wrapper id.
    $output .= $this->renderLabel();
    $output .= $this->renderElement();
    $output .= $this->renderDescription();
    $output .= '</div>';
    return $output;
  }

  /**
   * Render the elements label.
   */
  protected function renderLabel() {
    // @todo Switch to the theme system.
    // @todo Escape the label name.
    // Should the label be run through a translation system?
    $output = '';
    if (!is_null($this->label)) {
      $output = '<label' . $this->renderAttributes($this->buildLabelAttributes()) . '>' . $this->label . "</label>\n";
    }
    return $output;
  }

  /**
   * Render the element itself.
   */
  abstract protected function renderElement();

  /**
   * Render the elements description.
   */
  protected function renderDescription() {
    $output = '';
    if (!is_null($this->description)) {
      // @todo Integrate this into the theme system.
      $output .= '<div class="description">' . $this->description . "</div>\n";
    }
    return $output;
  }

  /**
   * Set the elements default value.
   *
   * @param mixed $value
   *   The default element value.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function whoseDefaultValueIs($value) {
    $this->defaultValue = $value;
    return $this;
  }

  /**
   * Set the elements label.
   *
   * @param string $label
   *   The elements label.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function whoseLabelIs($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * Set the elements description.
   *
   * @param string $description
   *   The elements description.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function withDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * Set if the label is disabled or not.
   *
   * @param bool $disabled.
   *   If the element is disabled or not.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function whoIsDisabled($disabled = true) {
    if ($disabled === true) {
      $this->disabled = 'disabled';
    }
    elseif ($disabled === false) {
      $this->disabled = null;
    }
    return $this;
  }

  /**
   * Define element specific attributes (e.g., type of text).
   *
   * @return array
   *   An array of attributes defined as key value pairs. For example,
   *   return array(
   *     'type' => 'text',
   *   );
   */
  abstract public function elementAttributes();

  /**
   * Set one or more attributes on the element.
   *
   * @param mixed $name
   *   This could either be the name of the attributer or an array of key value
   *   pairs where each key is the name of an attribute and each value is the
   *   value to set. This allows for multiple values to be set at one time.
   *
   * @param mixed $value
   *   The value to set if the name is an attribute name.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function withAttribute($name, $value = null) {
    // If we have an array it's an array or key value pairs to set as attributes.
    if (is_array($name)) {
      foreach ($name as $key => $value) {
        $this->attributes[$key] = $value;
      }
    }
    else {
      // We are setting just a single element.
      $this->attributes[$name] = $value;
    }
    return $this;
  }

  /**
   * Set one or more attributes on the elements label.
   *
   * @param mixed $name
   *   This could either be the name of the attributer or an array of key value
   *   pairs where each key is the name of an attribute and each value is the
   *   value to set. This allows for multiple values to be set at one time.
   *
   * @param mixed $value
   *   The value to set if the name is an attribute name.
   *
   * @return AbstractElement
   *   $this for the current element.
   */
  public function withLabelAttribute($name, $value = null) {
    // If we have an array it's an array or key value pairs to set as attributes.
    if (is_array($name)) {
      foreach ($name as $key => $value) {
        $this->labelAttributes[$key] = $value;
      }
    }
    else {
      // We are setting just a single element.
      $this->labelAttributes[$name] = $value;
    }
    return $this;
  }

  /**
   * Build the element attributes.
   *
   * Some of the attributes are set via the attributes while others are part
   * of settings (e.g., disabled, input type, etc.). This function takes the
   * different parts and combines them into one array.
   *
   * @return array
   *   An attributes array which can be rendered to html with renderAttributes().
   */
  protected function buildAttributes() {

    // Start with the element attributes (e.g., type of text)
    $attributes = $this->elementAttributes();

    // The name of the element.
    if (isset($this->name)) {
      $attributes += array(
        'name' => $this->name,
      );
    }

    // If the element is disabled.
    if ($this->disabled) {
      $attributes += array(
        'disabled' => null,
      );
    }

    // If there is a default value.
    if (isset($this->defaultValue)) {
      $attributes += array(
        'value' => $this->defaultValue,
      );
    }

    // The attributes that have been set.
    $attributes += $this->attributes;

    return $attributes;
  }

  /**
   * Build the label attributes.
   *
   * @return array
   *   An attributes array which can be rendered to html with renderAttributes().
   */
  protected function buildLabelAttributes() {
    $attributes = array();

    // The name of the element.
    if (isset($this->name)) {
      $attributes += array(
        'for' => $this->name,
      );
    }

    // The attributes that have been set.
    $attributes += $this->attributes;

    return $attributes;
  }

  /**
   * Render an attributes array into html element attributes.
   *
   * @param array $attributes
   *   An array of attributes to convert to html attributes.
   *
   * @return string
   *   The array of attributes as a string for use in a html element.
   */
  protected function renderAttributes(array $attributes) {
    foreach ($attributes as $attribute => &$data) {
      $data = isset($data) ? implode(' ', (array) $data) : null;
      $data = $attribute . (isset($data) ? '="' . $data . '"' : '');
    }

    return $attributes ? ' ' . implode(' ', $attributes) : '';
  }

  /**
   * Return the form object this element is attached to.
   *
   * @return Villain\Form\Form
   *   The parent form object up the chian of objects.
   */
  public function returnForm() {
    if ($this->parent) {
      return $this->parent->returnForm();
    }
    // No parent.
    throw new \Villain\Exception('Element trying to return parent Form when not attached to a Collection.');
  }
}