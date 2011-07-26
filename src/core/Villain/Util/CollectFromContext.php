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
 * Sometimes it is necessary to collect several items from the context and 
 * place them into a new associative array. This provides that capability.
 * 
 * Additionally, this allows an opportunity to rename the items.
 * 
 * @code
 * <?php
 * 
 * Config::request('foo')
 * // Dump a couple of values into the context:
 * ->doesCommand('add')->whichInvokes('FortissimoAddToContext')
 *   ->withParam('link')->whoseValueIs('http://example.com')
 *   ->withParam('name')->whoseValueIs('Example.Com')
 * // At some point, we might need to pass these on to some other command
 * // as an associative array, instead of as individual context values:
 * ->doesCommand('combine')->whichInvokes('\Villain\Util\CollectFromContext')
 *   ->withParam('contextMap')->whoseValueIs(array(
 *     // Get 'link' from the context and put it in the new 
 *     // array as 'url' => 'http://example.com'.
 *     'url' => 'link',
 *     // And get 'name', putting it in as 'title' => 'Example.Com'
 *     'title' => 'name',  
 *   ))
 * // Now we can pass the new array to another command:
 * ->doesCommand('template')->whichInvokes('\RenderTheme')
 *   ->withParam('vars')->from('context:combine')
 *   ->withparam('theme')->whoseValueIs('makeLink')
 * ;
 * ?>
 * @endcode
 *
 * 
 * This copies values out of the present context and into an array
 * (where the term "copy" is used loosely. Objects and resources are
 * not actually copied.)
 * 
 * This is useful as a way of grouping multiple context values
 * into an array that can then be processed by another command.
 * 
 * Params:
 * - contextMap: Map new variable names to existing context items. The key
 *   is the new variable name, the value is the name to get from the context.
 * - mergeWith: In some cases, it is necessary to start with an existing array
 *   and add more values to it. 'mergeWith' allows you to pass in an existing
 *   array. Values from context will overwrite values in the existing array.
 *   The resulting (combined) array is put into the context.
 * - assoc: If this is set to TRUE (the default), then the result is an 
 *   associative array. If this is set to FALSE, then the result is an 
 *   indexed array.
 * 
 * @author mbutcher
 *
 */
class CollectFromContext extends \BaseFortissimoCommand {

  // See BaseFortissimoCommand::expects().
  public function expects() {
    return $this
      ->description('Given a list of context names, put them into an array.')
      ->usesParam('contextMap', 'An associative array of the form new_name => context_name. ' 
        . 'This will fetch the values from the context and put them into a final array with the new_name as the key, and the context value as the value.')
      ->whichIsRequired()
      
      ->usesParam('assoc', 'If this is TRUE, the returned array will be associative. If FALSE, they array will be indexed.')
      ->whichHasDefault(TRUE)
      ->withFilter('boolean')
      
      ->usesParam('mergeWith', 'The name of an existing context item that has data that should be merged in with this. '
        . 'It is assumed that this value is an array. The order of merging is to being with this array and merge other data on top.')
      
      ->andReturns('A new array containing the requested items (if found).')
    ;
  }

  // See BaseFortissimoCommand::doCommand().
  public function doCommand() {
  
    $names = $this->param('contextMap');
    $isAssoc = $this->param('assoc', TRUE);
    $destination = $this->param('mergeWith', array());
    
    foreach($names as $newName => $oldName) {
      $destination[$newName] = $this->context($oldName, NULL);
    }

    if (!$isAssoc) {
      return array_values($destination);
    }
    
    return $destination;
  }
}