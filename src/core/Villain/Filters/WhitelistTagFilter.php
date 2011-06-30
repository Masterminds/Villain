<?php
/** @file
 *
 * Defines the class WhitelistTagFilter.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * Allows only the tags in the whitelist, and strips all others.
 */
class WhitelistTagFilter {
  
  protected $whitelist = array();
  
  /**
   * Construct a new WhitelistTagFilter.
   */
  public function __construct($initialArgs = NULL) {
    
  }
  
  public function run($value) {
    
  }
  
}