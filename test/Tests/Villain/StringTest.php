<?php
require 'Villain/Util/String.php';
use \Villain\Util\String;

class StringTest extends PHPUnit_Framework_TestCase {
  
  public function testShorten() {
    $test = 'This is a test.';
    $max = 8;
    
    $short = String::shorten($test, $max);
    $this->assertEquals(7, mb_strlen($short));
    $this->assertEquals('This is', $short);
    
    $test = '1234567890';
    $max = 8;
    
    $short = String::shorten($test, $max);
    $this->assertEquals('12345678', $short);
    $this->assertEquals(8, mb_strlen($short));
    
    $test = '123 4567890';
    $max = 8;
    
    $short = String::shorten($test, $max);
    $this->assertEquals('123', $short);
    $this->assertEquals(3, mb_strlen($short));
    
    $test = '123 4567890';
    $max = 8;
    
    $short = String::shorten($test, $max, '...');
    $this->assertEquals('123...', $short);
    $this->assertEquals(6, mb_strlen($short));
  }
  
}