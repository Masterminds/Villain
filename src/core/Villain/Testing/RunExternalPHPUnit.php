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
      
      ->usesParam('options', 'A string or an array of commandline options to be passed to phpunit')
      ->whichHasDefault('--colors')
      
      ->usesParam('basedir', 'The root directory of Villain.')
      ->whichHasDefault('..') // Since this is run in src/, we go up one directory.
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('The integer return value of the system command (0 means success)')
    ;
  }

  public function doCommand() {
    
    $options = $this->param('options', '--colors');
    if (is_array($options)) {
      $options = implode(' ', $options);
    }
    
    
    // Hack to run this from within the src/ directory, which is where Fort 
    // executes
    $command = 'cd ' . $this->param('basedir', '.') . ' && ';
    
    // Run phpunit
    $command .= $this->param('command') . ' ' . $options . ' ' . $this->param('tests');
    
    $retval = NULL;
    //print $command . PHP_EOL;
    system($command, $retval);
    
    return $retval;
  }
}

