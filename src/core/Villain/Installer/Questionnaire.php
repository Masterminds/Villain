<?php
/** @file
 *
 * Questionnaire is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-06.
 */
namespace Villain\Installer;
/**
 * Prompt user to enter key pieces of site information.
 *
 * This is used by the CLI installer to build a villain.ini.
 *
 * @author Matt Butcher
 */
class Questionnaire extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Prompt the user to enter core site information.')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('Injects multiple fields into the context.')
    ;
  }

  public function doCommand() {

    // $myParam = $this->param('myParam', 'Default value');
    // $myCxt = $this->context('myContext', 'Default value');


    // return $result; // Insert into Context.
  }
}

