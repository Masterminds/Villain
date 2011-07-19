<?php
/** @file
 *
 * RunExternalPHPUnit is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-18.
 */
namespace Villain\Testing;
/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class RunExternalPHPUnit extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Execute PHPUnit as a command on the command line.')
      ->usesParam('tests', 'A path to the test or tests.')
      ->whichHasDefault('./')
      ->usesParam('command', 'The full path to the phpunit command')
      ->whichHasDefault('phpunit')
      
      ->usesParam('basedir', 'The root directory of Villain.')
      ->whichHasDefault('..') // Since this is run in src/, we go up one directory.
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('The integer return value of the system command (0 means success)')
    ;
  }

  public function doCommand() {
    
    $options = '--colors'; //' --include-path ' . $this->param('basedir', '.');
    
    $command = 'cd ' . $this->param('basedir', '.') . ' && ';
    $command .= $this->param('command') . ' ' . $options . ' ' . $this->param('tests');
    
    $retval = NULL;
    print $command . PHP_EOL;
    system($command, $retval);
    
    return $retval;
  }
}

