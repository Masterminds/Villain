<?php
/** @file
 *
 * Defines the class PlaintextFilter.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * Removes all HTML tags, and strips high and low ASCII characters.
 *
 * This is a very strong filter, and will strip out anything under ASCII 32 and anything over 126.
 * This includes newlines, carriage returns, and tabs. It is not particularly UTF-8 friendly.
 */
class PlaintextFilter implements Filter {
  
  public function __construct($initialArgs = NULL) {}
  
  public function run($value) {
    return filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
  }
  
}