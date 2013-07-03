<?php
/** @file
 *
 * Defines the class CheckMongoConnectionTest.
 *
 * Created by Matt Butcher on 2011-07-20.
 */
require_once 'src/Fortissimo.php';
use Villain\Util\CommandRunner;

/**
 * Defines CheckMongoConnectionTest.
 */
class CheckMongoConnectionTest  extends PHPUnit_Framework_TestCase {
  
  const REAL_MONGO = 'mongodb://localhost';
  const REAL_MONGOS = 'mongodb://fakehost,localhost';
  
  public function setUp() {
    // We need to see (for real) if we can connect to the real
    // database. If not, we skip the test.
    try {
      $db = new Mongo(self::REAL_MONGO);
    } catch (Exception $e) {
      $this->markTestSkipped('No MongoDB found for testing.');
    }
  }
  
  public function testDoCommand() {
    $runner = new CommandRunner();
    
    $params = array(
      'server' => self::REAL_MONGO,
    );
    $res = $runner->run('\Villain\Installer\CheckMongoConnection', $params);
    
    $this->assertTrue($res, 'Test if connection succeeded.');
  }
  
  /**
   * @expectedException \Villain\InterruptException
   */
  public function testNoServer() {
    $runner = new CommandRunner();
    
    $params = array(
      'server' => 'mongodb://foo',
    );
    $res = $runner->run('\Villain\Installer\CheckMongoConnection', $params);
  }
  
  /**
   * @expectedException \Villain\InterruptException
   */
  public function testNoUser() {
    $runner = new CommandRunner();
    
    $params = array(
      'server' => 'mongodb://userfoo:passfoo@localhost',
    );
    $res = $runner->run('\Villain\Installer\CheckMongoConnection', $params);
  }
  
  public function testMultipleServers() {
    $runner = new CommandRunner();
    
    $params = array(
      'server' => self::REAL_MONGOS,
    );
    
    // This should return true because we connected to ONE server.
    $res = $runner->run('\Villain\Installer\CheckMongoConnection', $params);
    $this->assertTrue($res, 'Test connecting to at least one server.');
  }
}