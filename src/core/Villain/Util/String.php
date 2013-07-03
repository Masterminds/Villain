<?php
/** @file
 * Villian String utilities.
 */

/**
 * The Villain utilities.
 *
 * This section provides a handful of utilities that do not exist in PHP or other
 * libraries upon which Villain depends.
 */
namespace Villain\Util;

/**
 * Common string utilities.
 *
 * Villain does not provide wrappers around functions that PHP includes.
 * So you won't find String::split() or String::tr(). However, sometimes
 * there are string functions that are commonly used, but which PHP has
 * no good implementation of. This class is intended to provide implementations
 * of such commonly used string manipulations.
 *
 * Most (if not all) of the methods in this class are static, and can be called
 * like this:
 *
 * @code
 * <?php
 * $shorty = String::shorten($longString, $max, '...');
 * ?>
 * @endcode
 */
class String {
  
  /**
   * Shorten a string.
   *
   * This shortens a string until it has no more than $maxlen characters.
   * If this string is shorter than or equal in length to $maxlen, then the original
   * string will be returned unchanged. Otherwise, the string will be truncated
   * to the nearest word boundary, and $append will be added.
   *
   * @param string $string
   *  The string to shorten (if necessary).
   * @param int $maxlen
   *  The maximum number of characters allowed.
   * @param string $appand
   *  An optional string to append to the end of the shortened string to indicate
   *  that the string has been shortened, e.g. '...'.
   * @return string
   *  A string, shortened if necessary.
   */
  public static function shorten($string, $maxlen, $append = '') {
    $strlen = mb_strlen($string);
    if ($strlen <= $maxlen) {
      return;
    }
    
    $appendlen = mb_strlen($append);
    
    $max = $maxlen - $appendlen;
    
    $test = mb_substr($string, 0, $max);
    $lastWhitespace = strrpos($string, ' ');
    
    // Weak equality is intentional -- we don't want ' ...' for a string.
    $chopAt = ($lastWhitespace == 0) ? $max : $lastWhitespace;
    
    return trim(mb_substr($test, 0, $chopAt)) . $append;
    
  }
  
}