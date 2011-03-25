<?php
require_once 'StorableObject.php';

class StorableObjectTest extends PHPUnit_Framework_TestCase {
  
  public function testSetter() {
    $o = new StorableObject();
    
    $o->some_number = 1;
    $this->assertEquals(1, $o->some_number);
    
    $o->setFoo('bar');
    $this->assertEquals('bar', $o->foo);
    
  }
  
  public function testGetter() {
    $o = new StorableObject();
    
    $o->some_number = 2;
    $this->assertEquals(2, $o->some_number);
    
    $o->setFoo('bar');
    $this->assertEquals('bar', $o->getFoo());
    
  }
  
  public function testIsset() {
    $o = new StorableObject();
    
    $o->some_number = 2;
    $this->assertTrue(isset($o->some_number));
    $this->assertFalse(empty($o->some_number));
    
    $this->assertFalse(isset($o->some_other_number));
    $this->assertTrue(empty($o->some_other_number));
    
    
  }
  
  public function testUnset() {
    $o = new StorableObject();
    
    $o->some_number = 2;
    $this->assertTrue(isset($o->some_number));
    
    unset($o->some_number);
    $this->assertFalse(isset($o->some_number));
    $this->assertTrue(empty($o->some_number));

  }
  
  public function testFromArray() {
    $o = new StorableObject();
    
    $attrs = array(
      'foo' => 1,
      'bar' => 1.1,
      'baz' => array('hello', 'world'),
    );
    
    $o->fromArray($attrs);
    
    $this->assertEquals(1, $o->foo);
    $this->assertEquals(1.1, $o->bar);
    $this->assertEquals(2, count($o->baz));
    $this->assertEquals('hello', $o->baz[0]);
  }
  
  public function testToArray() {
    $o = new StorableObject();
    
    $o->foo = 1;
    $o->bar = 1.1;
    $o->baz = array('hello', 'world');
    
    $attrs = array(
      'foo' => 1,
      'bar' => 1.1,
      'baz' => array('hello', 'world'),
    );
    
    $array = $o->toArray();
    
    $this->assertEquals($attrs, $array);
  }
  
}