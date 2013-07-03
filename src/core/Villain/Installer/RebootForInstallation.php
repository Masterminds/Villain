<?php
/** @file
 *
 * RebootForInstallation is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-06.
 */
namespace Villain\Installer;
/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class RebootForInstallation extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Rebuild the Config object.')
      ->usesParam('configFile', 'The configuration file to load.')
      ->whichIsRequired()
      ->andReturns('Nothing. However, the system is re-bootstrapped with new commands.')
    ;
  }

  public function doCommand() {
    
    $config = $this->param('configFile');
    
    if (!is_readable($config)) {
      throw new \Villain\InterruptException('Configuration file not found.');
    }
    
    \Config::initialize(array());
    require $config;

    // $myParam = $this->param('myParam', 'Default value');
    // $myCxt = $this->context('myContext', 'Default value');


    // return $result; // Insert into Context.
  }
}

