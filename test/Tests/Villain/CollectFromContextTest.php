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
      'contextMap' => array('foo1' => 'Test1', 'Test2' => 'Test2'),
    );
    
    $runner = new CommandRunner();
    $result = $runner->run('\Villain\Util\CollectFromContext', $params, $cxt);
    
    $this->assertTrue(is_array($result));
    $this->assertEquals(2, count($result));
    $this->assertEquals(123, $result['foo1']);
    $this->assertEquals(321, $result['Test2']);
  }
  
  
  public function testMerge() {
    $cxt = new FortissimoExecutionContext();
    $cxt->add('Test1', 123);
    $cxt->add('Test2', 321);
    
    $params = array(
          'contextMap' => array('foo1' => 'Test1', 'Test2' => 'Test2'),
          'mergeWith' => array('foo1' => 'ORIGINAL', 'foo2' => 'ORIGINAL2')
    );
    
    $runner = new CommandRunner();
    $result = $runner->run('\Villain\Util\CollectFromContext', $params, $cxt);
    
    $this->assertTrue(is_array($result));
    $this->assertEquals(3, count($result));
    $this->assertEquals(123, $result['foo1']);
    $this->assertEquals(321, $result['Test2']);
    $this->assertEquals('ORIGINAL2', $result['foo2']);
  }
  
  public function testMissingValueStillHasKey() {
    $cxt = new FortissimoExecutionContext();
    $cxt->add('Test1', 123);
    
    $params = array(
      'contextMap' => array('foo1' => 'Test1', 'Test2' => 'Test2'),
    );
    
    $runner = new CommandRunner();
    $result = $runner->run('\Villain\Util\CollectFromContext', $params, $cxt);
    
    $this->assertTrue(is_array($result));
    $this->assertEquals(2, count($result));
    $this->assertEquals(123, $result['foo1']);
    $this->assertNull($result['Test2']);
  }
  
  /**
   * Test to make sure that a NULL value passed into mergeWith will still work.
   * Enter description here ...
   */ 
  public function testNullMergeArray() {
    $cxt = new FortissimoExecutionContext();
    $cxt->add('Test1', 123);
    $cxt->add('Test2', 321);
    
    $params = array(
              'contextMap' => array('foo1' => 'Test1', 'Test2' => 'Test2'),
              'mergeWith' => NULL,
    );
    
    $runner = new CommandRunner();
    $result = $runner->run('\Villain\Util\CollectFromContext', $params, $cxt);
    
    $this->assertTrue(is_array($result));
    $this->assertEquals(2, count($result));
    $this->assertEquals(123, $result['foo1']);
    $this->assertEquals(321, $result['Test2']);
  }
}