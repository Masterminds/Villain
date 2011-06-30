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
 *
 * The whitelist tag filter acts on the principle that a specific
 * list of tags should be allowed, and all others should be considered
 * illegal (and thus stripped). It also does some extra stripping to 
 * remove the threat of "assembling" tags through the tag stripping
 * process (e.g. transforming <<foo>script <bar>> into <script>).
 *
 * In its current implementation, this does nothing with entities.
 */
class WhitelistTagFilter {

  /**
   * The whitelist.
   *
   * Default value:
   * @code
   * array('b', 'br', 'em', 'i', 'p', 'strong');
   * @endcode
   */
  protected $whitelist = array('b', 'br', 'em', 'i', 'p', 'strong');
  
  public function __construct($initialArgs = NULL) {
    if (!empty($initialArgs) && is_array($intialArgs)) {
      $this->whitelist = $initialArgs;
    }
  }
  
  public function run($value) {
    $whites = implode('|', $this->whitelist);
    
    // This does the following:
    // - Match things that look like tags in the whitelist
    // - OR match things that look like tags, but aren't in the whitelist
    // - OR match bare angle brackets
    // The third case basically prevents stray angle brackets from being
    // transformed into tags (e.g. <script<sneaky>> becomming <script>).
    $regex = '/(<([\/]?)(' . $whites . ')\b[^>\/]*([\/]?)>)|(<[\/]?([\w]+)[^>]*>)|[><]+/i';
    
    $result = preg_replace_callback($regex, array($thism 'pregReplaceCallback'), $test);

    return $result;
  }
  
  /**
   * Callback for run()'s preg_replace_callback() function.
   * 
   * This assumes a $matches array that looks like this:
   *
   * @code
   * <?php
   * Array(
   *  [0] => '<i>', // Full match
   *  [1] => '<i>', // Just the tag if it's in the whitelist
   *  [2] => NULL   // A slash if it is found. Used for closing tags.
   *  [3] => 'i',  // just the tagname if it's in the whitelist
   *  [4] => NULL, // A / if this is a self-closing tag.
   * );
   * ?>
   * @endcode
   *
   * @param array $matches
   *  A match array from a preg_replace_callback().
   * @return string
   *  The string to replace.
   */
  public function pregReplaceCallback($matches) {
    if (!empty($matches[3])) {
      $tag = implode('', array_slice($matches, 2, 4));
      return '<' . $tag . '>';
    }

    return '';
  }
  
}