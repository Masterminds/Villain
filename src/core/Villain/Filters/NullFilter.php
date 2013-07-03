<?php
/** @file
 *
 * Defines the class NullFilter.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * A filter that does nothing but return the value unaltered.
 */
class NullFilter implements Filter {
  public function __construct($args = NULL) {}
  public function run($value) {return $value;}
}