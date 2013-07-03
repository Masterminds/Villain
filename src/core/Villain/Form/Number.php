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
class Number extends Textfield {

  /**
   * @see AbstractElement::elementAttributes()
   */
  public function elementAttributes() {
    return array(
      'type' => 'number',
    );
  }
}