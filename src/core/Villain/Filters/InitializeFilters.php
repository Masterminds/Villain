<?php
/** @file
 *
 * InitializeFilters is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * Initialize the filter system.
 *
 * In Villain, we augment the existing Fortissimo filter system with an advanced filter chain mechanism.
 *
 * Filter chains work by associating a filter name (e.g. 'html') with a chain of filtering classes
 * (e.g `array('\Villain\Filters\DeCSSFilter' => NULL, '\Villain\Filters\HTMLFilter' => NULL)`). When a 
 * filter is run, a new instance of each class is created, and data is filtered through each class.
 *
 * To run a filter from inside of a command:
 *
 * @code
 * <?php
 * $clean = $this->context('filters')->run('html', $dirty);
 * ?>
 * @endcode
 *
 * To create a new filter, implement \Villain\Filters\Filter:
 * @code
 * <?php
 * class LowercaseFilter implements \Villain\Filters\Filter {
 *   public function __construct($initialArgs = NULL) {
 *     // Initial args are passed in from the filter chain.
 *   }
 *   public function run($value) {
 *     return strtolower($value);
 *   }
 * }
 * ?>
 * @endcode
 *
 * To define a new filter, use the FilterManager in the context. For example, inside of 
 * a command, it is done this way:
 *
 * @code
 * <?php
 * $filters = array(
 *   // Name is the class, value is passed to the filter as $initialArgs
 *   '\Foo\Bar\PlainTextFilter' => array('strip_carraige_returns' => TRUE),
 *   '\LowercaseFilter' => NULL, // No args.
 * );
 * $this->context('filters')->addChain('plainLowercaseText', $filters);
 * ?>
 * @endcode
 *
 *
 * @author Matt Butcher
 */
class InitializeFilters extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Initialize the filter system.')
      // ->usesParam('db', 'The database to use. If none is specified, the default database is used.')
      //         ->withFilter('string')
      ->usesParam('collection', 'The collection in which filter information is stored.')
        ->whichHasDefault('filters')
        ->withFilter('string')
      ->andReturns('A FilterManager instance.')
    ;
  }

  public function doCommand() {

    $db = $this->param('db', NULL);
    $collection = $this->param('collection');
    
    $manager = new FilterManager($this->context, $collection);
    
    return $manager;
  }
}

