<?php
/** @file
 *
 * Add JSON data directly into the context.
 *
 * Created by Matt Butcher on 2011-05-10.
 */

namespace Villain\Configuration;

/**
 * Import JSON data directly into the context.
 *
 * This takes JSON data, parses it, and places it in the context. Ideally, the JSON
 * data should have objects at the top level. List-like data will be inserted into the 
 * context with integer-based keys.
 *
 * Example JSON:
 * @code
 * {
 *   "a":"b",
 *   "c":[1,2,3,4,5]
 * }
 * @endcode
 *
 * This will be inserted into the context as two items, which can be accessed like this:
 *
 * @code
 * $this->context('a'); // Returns 'b'
 * $this->context('c'); // Returns array(1, 2, 3, 4)
 * @endcode
 *
 * @author Matt Butcher
 */
class AddJsonToContext extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Parses JSON data and inserts it into the context.')
      ->usesParam('data', 'The JSON data.')
      ->whichIsRequired()
      
      ->declaresEvent('onLoad', 'Fired after the JSON data is fired.')
      
      ->andReturns('JSON data is inserted directly into the context, and nothing is returned.')
    ;
  }

  public function doCommand() {
    
    $raw_data = $this->param('data');
    
    $json = json_decode($raw_data);
    
    $e = new \stdClass();
    $e->context = $this->context;
    $e->commandName = $this->name;
    $e->data =& $json;
    $this->fireEvent('onLoad', $e);
    
    $this->injectIntoContext($json);
  }
  
  /**
   * Inject the data into the context.
   *
   * @param array $array
   *  The array of data to inject.
   */
  protected function injectIntoContext($data) {
    $this->context->addAll($data);
  }
}

