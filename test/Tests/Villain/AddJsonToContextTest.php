<?php
/** @file
 *
 * Defines the class TestAddJsonToContext.
 *
 * Created by Matt Butcher on 2011-07-18.
 */
require 'VillainTestCase.php';
/**
 * Defines TestAddJsonToContext.
 */
class AddJsonToContextTest extends VillainTestCase {
  
  public function testDoCommand() {
    $runner = new \Villain\Util\CommandRunner();
    
    $klass = '\Villain\Configuration\AddJsonToContext';
    $params = array('data' => '{"test": "foo", "test2": 1234}');
    
    $runner->run($klass, $params);
    
    $result = $runner->context();
    
    $this->assertEquals('foo', $result->get('test'), 'Check string JSON data.');
    $this->assertEquals(1234, $result->get('test2'), 'Check integer JSON data.');
  }
  
}