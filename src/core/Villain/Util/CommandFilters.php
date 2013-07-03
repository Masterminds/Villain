<?php
/** @file
 *
 * Command filters that can be used within ->filter('callback').
 *
 * Created by Matt Butcher on 2011-07-01.
 */
namespace Villain\Util;
/**
 * Provides commonly-used filters for commands.
 *
 * This provides filters that can be used from within a BaseFortissimoCommand::expects() call.
 *
 * Example:
 * @code
 * <?php
 * 
 * ?>
 * @endcode
 */
class CommandFilters {
  
  /**
   * Converts a date string to a MongoDate, validating as it goes.
   *
   * This does the following:
   *  - It takes a date in any of a number of formats (see `strtotime`) and converts to a timestamp
   *  - Minimal validation is done on the resulting date.
   *  - It creates a new MongoDate based on the timestamp.
   *
   * Note that int values are converted to strings, and are assumed to be of the format YYYYMMDD. To use a
   * Unix timestamp, prepend the int with '@'.
   *
   * @param string $value
   *  A string value.
   * @return MongoDate
   *  An initialized MongoDate object.
   */
  public static function sanitizeDate($value) {
    $time = strtotime($value);
    
    if ($time === FALSE) {
      throw new \Villain\Exception('Validation error: the given date will not parse: ' . $value);
    }
    elseif ($time == 240921544184) {
      // Looks like we've hit the 32-bit limit. This typically indicates a malformed date.
      throw new \Villain\Exception('Validation error: malformed date: ' . $value);
    }
    
    return new \MongoDate($time);
  }
  
}