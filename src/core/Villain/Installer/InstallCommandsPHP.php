<?php
/** @file
 *
 * Install the commands.php file.
 *
 * Created by Matt Butcher on 2011-07-07.
 */
namespace Villain\Installer;
/**
 * Install the commands.php file.
 *
 * Note that this will overwrite an existing commands.php.
 *
 * @author Matt Butcher
 */
class InstallCommandsPHP extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Copy the base commands.php file to the configuration directory.')
      ->usesParam('basedir', 'Base directory in which the new file will be placed')
        ->whichHasDefault('config')
      ->usesParam('original', 'Full path to the original file.')
        ->whichHasDefault('config/commands-original.php')
      ->andReturns('TRUE if it is successful.')
    ;
  }

  public function doCommand() {
    
    $original = $this->param('original');
    $basedir = $this->param('basedir');
    $new = $basedir . '/commands.php';
    
    // See if original is there.
    if (!is_file($original)) {
      throw new \Villain\InterruptException(sprintf("Cannot find %s", $original));
    }
    
    // See if the destination directory is there.
    if (!is_dir($basedir) || !is_writable($basedir)) {
      throw new \Villain\InterruptException(sprintf("Cannot write to %s", $basedir));
    }
    
    copy($original, $new);

    return TRUE;
  }
}

