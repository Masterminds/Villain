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
      ->description('')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('')
    ;
  }

  public function doCommand() {

    // $myParam = $this->param('myParam', 'Default value');
    // $myCxt = $this->context('myContext', 'Default value');


    // return $result; // Insert into Context.
  }
}

