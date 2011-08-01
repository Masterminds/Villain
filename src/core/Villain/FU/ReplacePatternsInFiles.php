<?php
/**
 * @file
 * 
 * File Utility for running in-place find-and-replace on one or more files.
 * 
 * Initially created by mbutcher on Jul 22, 2011.
 */
namespace Villain\FU;

/**
 * Given a file or list of files, perform a find-and-replace.
 * 
 * This basically does a regular expression-based find and replace on a file 
 * or files.
 * 
 * @author mbutcher
 *
 */
class ReplacePatternsInFiles extends ModifyFileInPlace {
  
  protected $patterns = NULL;
  protected $replace = NULL;
  
  // See BaseFortissimoCommand::expects().
  public function expects() {
    return $this
      ->description('Perform a file find-and-replace using regular expressions.')
      ->usesParam('file', 'A file or an iterable/array of files.')
      ->whichIsRequired()
      ->usesParam('pattern', 'A regular expression (or array of regular expressions)')
      ->whichIsRequired()
      ->usesParam('replacement', 'The replacement or replacements to be subsituted in.')
      ->whichIsRequired()
      ->andReturns('Nothing')
    ;
  }

  // See BaseFortissimoCommand::doCommand().
  public function doCommand() {
    $files = $this->param('file');
    $this->patterns = $this->param('pattern');
    $this->replace = $this->param('replacement');
    
    
    // Faster than detecting if $files is iterable.
    if (is_string($files)) {
      $files = array($files);
    }
  
    foreach ($files as $file) {
      $this->iterateFile($file);
    }
  }
  
  /**
   * Perform the regular expression replacement.
   * 
   * For each line, run the given find/replace operation(s).
   * 
   * @param string $line
   *   The line in the file.
   * @return string
   *   The altered line in the file.
   */
  protected function forEachLineInFile($line) {
    return preg_replace($this->pattern, $this->replace, $line);
  }
}