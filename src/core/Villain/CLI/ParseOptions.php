<?php
/** @file
 *
 * This file contains commands for parsing options from ARGV or another source.
 *
 * Created by Matt Butcher on 2011-06-18.
 */
namespace Villain\CLI;
/**
 * Parse options (flags) from ARGV or another source.
 *
 * This command is designed to facilitate subcommands that can be run from the command line, 
 * much like Git or Subversion. It is intended to augment Fort, which provides a standard
 * CLI client for Fortissimo.
 *
 * Example:
 * @code
 * $ fort --no-internals myCommand --a foo --b bar
 * @endcode
 *
 * Fort itself will handle its own options, and will consume --no-internals. Fortissimo/Villain will see
 * the ARGV string as this:
 *
 * @code
 * <?php
 * array(
 *  [0] => fort
 *  [1] => myCommand
 *  [2] => --a
 *  [3] => foo
 *  [4] => --b
 *  [5] => bar
 * );
 * ?>
 * @endcode
 *
 * This command is designed to take a specifications array that specifies which options are supported, and then
 * parse out the args, inserting the parsed args into the context.
 *
 * In the example above, assuming that both a and b are legitimate flags, this would insert two entries into the 
 * context: `$cxt->add('a', 'foo'); $cxt->add('b', 'bar');`. Any trailing data (e.g. not part of the parsed options)
 * will be returned as the return value for this command. So if this command is named `baz`, then the data will be 
 * accessible as `$cxt->get('baz')`. Given this, you will be able to parse commands like this:
 *
 * @code
 * $ fort --no-internals myCommand --a foo --b bar SomeOtherData
 * @endcode
 *
 * In this example, three values are inserted into the context: a, b, and the command results for this command. So if the
 * command is named `baz`, then the context will contain:
 *
 * @code
 * fort SomeOtherData
 * @endcode
 *
 * Note that the initial Fort call (or whatever the command call is named) will be returned to simulate the normal
 * contents of argv.
 *
 * It should be possible to recurse, provided that there is a delimiter between flags. `--` will stop this parser from
 * continuing. Unknown flags will generate a \Villain\Exception.
 *
 * <b>The OptionSpec Format</b>
 *
 * An option spec is an associative array that looks like this:
 *
 * @code
 * array(
 *   '--option' => array(
 *      'help' => 'This is the help text for --option.',
 *      'value' => FALSE, // This option does NOT take a value.
 *   ),
 *   '--file' => array(
 *      'help' => 'The file this should process. Requires a file. Example: --file foo.txt',
 *      'value' => TRUE, // This option takes a filename, so value is TRUE.
 *   ),
 * );
 * @endcode
 *
 * @author Matt Butcher
 */
class ParseOptions extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Parse an option string, typically from ARGV.')
      ->usesParam('options', 'The array of values to parse. If none is supplied, ARGV is used.')
      ->usesParam('optionSpec', 'An option spec array. See the code documentation for the correct format.')
      ->whichIsRequired()
      ->usesParam('help', 'Display help instead of processing the arguments.')
      ->withFilter('boolean')
      ->whichHasDefault(FALSE)
      ->andReturns('The remaining (unprocessed) values. Any parsed options are placed directly into the context.')
    ;
  }

  public function doCommand() {
    $optionSpec = $this->param('optionSpec');
    $help = $this->param('help');
    $argArray = $this->param('options', NULL);
    
    if($help) {
      return $this->generateHelp($optionSpec);
    }
    
    if (!isset($argArray)) {
      global $argv;
      $argArray = $argv;
    }
    
    
    
    if (!empty($argArray)) {
      $argArray = $this->extractOptions($optionSpec, $argArray);
    }
    
    return $argArray;
  }
  
  /**
   * Extract options.
   *
   * This takes a specification and an array, and attempts to 
   * extract all of the options from the array, according to the specification.
   *
   * @param array $optionSpec
   *  The specifications array.
   * @param array $args
   *  The arguments to parse.
   * @return
   *  The arguments array without any options in it. Options are placed directly
   *  into the context as name/value pairs. Boolean flag options will have the 
   *  value TRUE.
   */
  public function extractOptions(array $optionSpec, array $args) {
    //$modifiers = array();
    $endOpts = 1;
    $count = count($args);

    for ($i = 1; $i < $count; ++$i) {
      if (isset($optionSpec[$args[$i]])) {
        $flag = substr($args[$i], 2);

        // If option needs a value...
        if ($optionSpec[$args[$i]]['value']) {
          if (!isset($args[$i + 1])) {
            throw new \Villain\Exception($args[$i] . ' requires a valid value.');
          }
          //$modifiers[$flag] = $args[++$i];
          $this->context->add($flag, $args[++$i]);
        }
        // Option doesn't need a value
        else {
          //$modifiers[$flag] = TRUE;
          $this->context->add($flag, TRUE);
        }
        $endOpts = $i + 1;
      }
      // Stop if we hit --
      elseif ($args[$i] == '--') {
        $endOpts = ++$i;
        break;
      }
      // Fail if we hit unknown option
      elseif (strpos($args[$i], '--') === 0) {
        throw new \Villain\Exception(sprintf("Unrecognized option %s.", $args[$i]));
      }
      // Looks like we're done.
      else {
        $endOpts = $i;
        break;
      }
    }
    
    // Now we splice the parsed options out of args
    $ret = array_splice($args, 1, $endOpts - 1);

    return $ret;
  }
  
  public function generateHelp($optionSpec) {
    $buffer = array();
    $format = '%s:  %s';
    foreach ($optionSpec as $flag => $spec) {
      $help = isset($spec['help']) ? $spec['help'] : '(undocumented)';
      $buffer[] = sprintf($format, $flag, $help);
    }
    return $buffer;
  }
}

