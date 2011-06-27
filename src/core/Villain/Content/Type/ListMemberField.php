<?php
/** @file
 *
 * Defines the class ListMemberField.
 *
 * Created by Matt Butcher on 2011-04-28.
 */

namespace Villain\Content\Type;

/**
 * A ListMemberField is a field that accepts any values that are in the list of accepted values.
 *
 * For example, a list member field with the list array(1, 2, 7) will only successfully validate
 * when given one of the three integers 1, 2, or 7.
 */
class ListMemberField extends Field {
  protected $list = array();
  
  /**
   * Set the list of accepted values.
   *
   * Generally, the Iterable should be an indexed array.
   *
   * @param Iterable $list
   *  An Iterable of values that are allowed.
   */
  public function acceptedValues($list) {
    $this->list = $list;
  }
  
  public function validate($value) {
    
    // Since $list is an Iterable, we have to do this the slow way:
    $found = FALSE;
    foreach ($this->list as $item) {
      if ($value == $item) {
        $found = TRUE;
        break;
      }
    }
    
    if (!$found)) {
      throw new FieldValidationException(sprintf('The given value %s was not in the list of acceptable values.', $value));
    }
    
    return TRUE;
  }
  
  public function getDefinition() {
    $def = parent::getDefinition();
    // TODO: Should $list be converted to an array?
    $def['list'] = $this->list;
  }
}