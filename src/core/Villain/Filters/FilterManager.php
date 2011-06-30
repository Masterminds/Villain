<?php
/** @file
 *
 * Defines the class FilterManager.
 *
 * The filter manager manages filter chains for Villain's pluggable filtering system. This 
 * should not be confused with Fortissimo's input filtering system used by commands. The
 * two can be used in conjunction, but that one uses PHP's built-in filtering system, and
 * is used for validating types, while this one is user space, and is designed to filter
 * strings.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * The filter manager.
 *
 * This provides standard access to the filter system. Generally, a FilterManager is 
 * created by the \Villain\Filters\InitializeFilters command, which then stores the
 * FilterManager inside of the \FortissimoExecutionContext as $context->get('filters');
 *
 * The FilterManager provides uniform access to the filter storage backend, and should
 * be used to process a filter chain.
 */
class FilterManager {
  
  protected $filterCollection = NULL;
  protected $cxt = NULL;
  
  /**
   * Construct a new FilterManager.
   *
   * @param FortissimoExecutionContext $cxt
   *  The context for the present request.
   * @param string $collectionName
   *  The collection that holds filter definitions. It is presumed that this 
   *  collection holds ONLY filter definitions.
   */
  public function __construct(\FortissimoExecutionContext $cxt, $collectionName = 'filters') {
    $this->cxt = $cxt;
    $db = $cxt->ds()->get();
    $this->filterCollection = $db->selectCollection($collectionName);
  }
  
  /**
   * Execute a filter chain with the given name.
   *
   * @param string $chain
   *  The name of the filter chain to run, e.g. 'html'.
   * @param string $value
   *  The value to filter.
   * @param array $options
   *  An array of options. CURRENTLY UNUSED, but reserved for future use.
   * @return string
   *  The filtered value string.
   * @throws \Villain\Exception
   *  Thrown when no filter chain matching $chain is found.
   */
  public function run($chain, $value, $options = NULL) {
    $filterChain = $this->filterCollection->findOne(array('name' => $chain));
    
    if (empty($filterChain)) {
      // Really, we should support a default (strong) filter and use that.
      throw new \Villain\Exception('No filter chain found.');
    }
    
    $chainList = $filterChain['chain'];
    
    return $this->runChain($filterChain, $value, $options);
  }
  
  /**
   * Save a filter chain.
   *
   * This saves a filter chain in the list of known filter chains.
   *
   * @param string $name
   *  The name of the filter chain.
   * @param array $filters
   *  An associative array, where the key is the fully qualified class name, 
   *  and the value is an initialization parameter that will be passed into
   *  the filter when it is constructed. This may be any scalar, an array of scalars, 
   *  or a NULL. The params are serialized into BSON and stored in MongoDB.
   * @param boolean $overwrite
   *  If this is true, then if a filter by the name $name already exists, it will
   *  be overwritten. If this is false and the name $name already exists, a 
   *  \Villain\Exception will be thrown.
   * @throws \Villain\Exception
   *  Only if $overwrite is false and $name already exists.
   */
  public function addChain($name, $filters, $overwrite = TRUE) {
    
    $chain = $this->filterColelction->findOne(array('name' => $name));
    
    if (empty($chain)) {
      $chain = array(
        'name' => $name,
        'chain' => $filters,
      );
    }
    elseif ($overwrite) {
      $chain['chain'] = $filters;
    }
    else {
      throw new \Villain\Exception(sprintf('A chain named %s already exists.', $name));
    }
    
    $this->filterCollection->save($chain);
    
  }
  
  /**
   * Remove the given chain.
   *
   * Given the name of a filter chain, this deletes that chain from
   * the filter collection.
   *
   * @param string $chain
   *  The chain to remove.
   * @return boolean 
   *  TRUE if the removal request was sent, FALSE otherwise. This issues an
   *  asynchronous request and does not wait to find out if any records are
   *  actually removed.
   */
  public function removeChain($chain) {
    return $this->filterCollection(array('name' => $chain));
  }
  
  /**
   * Check whether the named filter chain exists.
   *
   * This checks whether the given filter chain exists, returning TRUE
   * if the chain exists, and FALSE otherwise.
   *
   * @param string $name
   *  The name of the filter chain.
   * @return boolean
   *  TRUE if the chain exists, FALSE otherwise.
   */
  public function hasChain($name) {
    $res = $this->filterCollection->findOne(array('name' => $name));
    return !empty($res);
  }
  /**
   * Retrieve a collection of all chains.
   *
   * This queries the appropriate collection for all of the filter chains,
   * returning a cursor that points to the list of found items.
   *
   * @param array $query
   *  A query, formatted for MongoDB. If this is supplied, results will be
   *  constrained by the query. If not, all filters are returned.
   * @return MongoCursor
   *  A cursor pointing to the collection of all located filters.
   */
  public function getChains($query = array()) {
    return $this->filterCollection->find($query);
  }
  
  /**
   * Run the given chain against the supplied value.
   */
  protected function runChains($chain, $value) {
    foreach ($chain as $filterKlass => $args) {
      $obj = new $filterKlass($args);
      $value = $obj->run($value);
      
      // Fail fast.
      if (empty($value)) {
        return $value;
      }
    }
    return $value;
  }
  
}
