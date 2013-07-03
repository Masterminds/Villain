<?php
/** @file
 *
 * Defines the class EscapeMarkupFilter.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Filters;
/**
 * Escapes HTML/XML markup as well as low and high ASCII/ISO-8859-1 values.
 */
class EscapeMarkupFilter implements Filter {
  
  public function __construct($args = NULL) {}
  public function run($value) {
    return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
  }
  
}