<?php
/**
 * @file
 * 
 * Copy values from the context into an array.
 * 
 * This is used to streamline the passing of data from the context
 * into a command.
 * 
 * Initially created by mbutcher on Jul 23, 2011.
 */
namespace Villain\Util;

/**
 * Copy values from the FortissimoExecutionContext into an array.
 * 
 * This copies values out of the present context and into an array
 * (where the term "copy" is used loosely. Objects and resources are
 * not actually copied.)
 * 
 * This is useful as a way of grouping multiple context values
 * into an array that can then be processed by another command.
 * 
 * @author mbutcher
 *
 */
class CollectFromContext extends \BaseFortissimoCommand {

  // See BaseFortissimoCommand::expects().
  public function expects() {
    return $this
      ->description('Given a list of context names, put them into an array.')
      ->usesParam('contextNames', 'The names of the items to fetch from the context.')
      ->whichIsRequired()
      ->usesParam('assoc', 'If this is TRUE, the returned array will be associative. If FALSE, they array will be indexed.')
      ->whichHasDefault(TRUE)
      ->withFilter('boolean')
      ->andReturns('A new array containing the requested items (if found).')
    ;
  }

  // See BaseFortissimoCommand::doCommand().
  public function doCommand() {
  
    $names = $this->param('contextNames');
    $isAssoc = $this->param('assoc', TRUE);
    
    $buffer = array();
    foreach($names as $name) {
      $buffer[$name] = $this->context($name, NULL);
    }

    if (!$isAssoc) {
      return array_values($buffer);
    }
    
    return $buffer;
  }
}