<?php
/**
 * @file
 * A textfield html element.
 *
 * Created by Matt Farina on 2011-04-24
 */

namespace Villain\Form;

/**
 * A textfield html element.
 *
 * @author Matt Farina
 */
class Textfield extends AbstractElement {

  /**
   * @see AbstractElement::elementAttributes()
   */
  public function elementAttributes() {
    return array(
      'type' => 'text',
    );
  }

  /**
   * @see AbstractElement::renderElement()
   */
  public function renderElement() {
    return '<input' . $this->renderAttributes($this->buildAttributes()) . ">\n";
  }
}