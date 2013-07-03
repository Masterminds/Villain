<?php
/**
 * @file
 * A url html input element.
 *
 * Created by Matt Farina on 2011-05-15
 */

namespace Villain\Form;

/**
 * A textfield html element.
 *
 * @author Matt Farina
 */
class URL extends Textfield {

  /**
   * @see AbstractElement::elementAttributes()
   */
  public function elementAttributes() {
    return array(
      'type' => 'url',
    );
  }
}