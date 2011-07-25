<?php
/**
 * @file
 * 
 * This file contains code to...
 * 
 * Initially created by mbutcher on Jul 25, 2011.
 */

require_once 'src/Fortissimo.php';
use Villain\Util\CommandRunner;

/**
 * Unit test for a command.
 *
 * @author Matt Butcher
 */
class CollectFromContextTest extends PHPUnit_Framework_TestCase {

  public function testDoCommand() {
    $cxt = new FortissimoExecutionContext();
    $cxt->add('Test1', 123);
    $cxt->add('Test2', 321);
    
    $params = array(
      'contextNames' => array('Test1', 'Test2'),
    );
    
    $runner = new CommandRunner();
    $result = $runner->run('\Villain\Util\CollectFromContext', $params, $cxt);
    
    $this->assertTrue(is_array($result));
    $this->assertEquals(2, count($result));
    $this->assertEquals(123, $result['Test1']);
    $this->assertEquals(321, $result['Test2']);
  }
  
}