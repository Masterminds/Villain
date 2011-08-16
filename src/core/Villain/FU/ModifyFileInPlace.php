<?php
/**
 * @file
 * 
 * Modify a file in place.
 * 
 * This base command provides a method for modifying a file in-place.
 * 
 * Initially created by mbutcher on Jul 23, 2011.
 */
namespace Villain\FU;
/**
 * Modify a file in place.
 * 
 * This provides an abstract command that can easily be extended to perform
 * in-place file modifications on a line-by-line basis. This handles all
 * of the locking and file IO, freeing up the implementation to focus on
 * performing the replacement task.
 * 
 * To iterate a file, use iterateFile(), passing in a path to the file. This
 * will perform all the necessary locking to ensure that other well-behaved
 * processes on the system do not alter this file during write operations.
 * 
 * iterateFile() will periodically call back to forEachLineInFile(), which 
 * is responsible for transforming the line, and returning the modified 
 * line. The resulting data will be flushed back to the file, and then
 * the file will be unlocked.
 * @author mbutcher
 *
 */
abstract class ModifyFileInPlace extends \BaseFortissimoCommand {


  /**
   * Iterate the given file, line by line, and modify it.
   * 
   * This will read a file and itereate line-by-line over its contents. Each
   * line is then passed to forEachLineInFile(), which should transform 
   * each line appropriately. The results are then written back to the original
   * file.
   * 
   * Warning: This will truncate the original file and replace its contents 
   * with the new contents.
   * 
   * @param string $file
   *   The string filename, with the appropriate path.
   * @throws \Villain\InterruptException
   *   Thrown if the file is not found or not writeable.
   */
  protected function iterateFile($file) {
    if (!is_writable($file)) {
      throw new \Villain\InterruptException(sprintf('File %s is not writable', $file));
    }
    
    // Open and lock.
    $fp = fopen($file, 'rw');
    flock($fp, LOCK_EX);
    
    // Do replacements.
    $buffer = array(); // This will not work well on large files. Use tmpfile?
    while ($line = fgets($fp)) {
      $buffer[] = $this->forEachLineInFile($line);
    }
    
    // Now we write.
    rewind($fp);
    ftruncate($fp, 0);
    // Need to see if this outperforms just looping and writing.
    fwrite($fp, implode('', $buffer));
    
    // Unlock and close.
    flock($fp, LOCK_UN);
    fclose($fp);
  }
  
  /**
   * Perform an operation on each line in a file.
   * 
   * @param string $line
   *   The line of the file.
   * @return string
   *   The modified line of the file.
   */
  abstract protected function forEachLineInFile($line);
}