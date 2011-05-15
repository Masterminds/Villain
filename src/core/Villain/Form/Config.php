<?php
/**
 * @file
 * Config object for global form system configuration.
 *
 * Created by Matt Farina on 2011-04-23
 */

namespace Villain\Form;

/**
 * Config object for global form system configuration.
 *
 * @author Matt Farina
 */
class Config {

  /**
   * A mapping between element names and the classes that implement them.
   *
   * @todo This should be turning into a get/set API.
   */
  public static $elementMap = array(
    'textfield' => 'Villain\Form\Textfield',
  );
}