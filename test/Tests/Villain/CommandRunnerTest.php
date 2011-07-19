<?php
/** @file
 *
 * TestCommandRunner is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-18.
 */
require_once 'src/Fortissimo.php';
use Villain\Util\CommandRunner;
/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class CommandRunnerTest extends PHPUnit_Framework_TestCase {

  public function testRun() {
    $runner = new CommandRunner();
    $ret = $runner->run('StubCommandRunnerCommand', array('name' => 'HELLO'));
    $this->assertEquals('HELLO', $ret, 'Check return value of a base command.');
  }
  
  public function testContext() {

    $runner = new CommandRunner();
    $runner->run('\FortissimoAddToContext', array('testContext' => TRUE));
    $cxt = $runner->context();
    
    $this->assertTrue($cxt->get('testContext'), 'Verify that context was set.');
  }

}

class StubCommandRunnerCommand extends \BaseFortissimoCommand {
  
  public function expects() {
    return $this->description('Create a test command.')
      ->usesParam('name', 'A test param')
      ->whichIsRequired()
      ->andReturns('The name');
  }
  
  public function doCommand() {
    return $this->param('name');
  }
}

