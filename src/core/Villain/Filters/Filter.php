<?php
/** @file
 *
 * The interface for a filter.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * The interface for a filter.
 *
 * Filter chains are composed of chains of Filter classes that are
 * instantiated at evaluation time. See \Villain\Filters\FilterManager
 * and \Villain\Filters\InitializeFilters for an overview.
 *
 * A filter has one job: Given input and possibly some configuration, 
 * transform a given string value.
 *
 * @author Matt Butcher
 */
interface Filter {
  /**
   * Construct a new filter.
   *
   * The argument is an optional configuration argument. Each Filter implementation
   * may use it as desired. However, the value must be serializable into BSON, as
   * it is stored in MongoDB. Thus, it may be a scalar, and array, or a NULL.
   *
   * @param $initialArgs
   *  An argument or a NULL.
   */
  public function __construct($initialArgs = NULL);
  /**
   * Execute a filter.
   *
   * Given a value, run the filter over the value and return the results.
   *
   * @param string $value
   *  The value to filter.
   * @return string
   *  The filtered data.
   */
  public function run($value);
}