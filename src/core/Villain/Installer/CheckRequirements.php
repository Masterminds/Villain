<?php
/** @file
 *
 * CheckRequirements is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-07.
 */
namespace Villain\Installer;
/**
 * Verify that the environment is correctly configured.
 *
 * @author Matt Butcher
 */
class CheckRequirements extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Ensure that the necessary requirements are met.')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('Boolean TRUE if environment is okay, FALSE otherwise.')
    ;
  }

  public function doCommand() {
    
    if (!class_exists('\MongoDB')) {
      throw new \Villain\InterruptException('MongoDB Extension is not installed.');
    }
    
    if (file_exists('config/commands.php')) {
      throw new \Villain\InterruptException('Villain appears to already be installed. commands.php exists.');
    }
    
    return TRUE;

  }
}

