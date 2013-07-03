<?php
require_once 'Villain/Storage/StorableObject.php';

use \Villain\Storage\StorableObject;

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
  
  public function testNewFromArray() {
    $attrs = array(
      'foo' => 1,
      'bar' => 1.1,
      'baz' => array('hello', 'world'),
    );
    
    
    $o = StorableObject::newFromArray($attrs);
        
    $this->assertEquals(1, $o->foo);
    $this->assertEquals(1.1, $o->bar);
    $this->assertEquals(2, count($o->baz));
    $this->assertEquals('hello', $o->baz[0]);
    
    $o2 = StorableObjectTestExtension::newFromArray($attrs);
    
    $this->assertTrue($o2 instanceof StorableObjectTestExtension);
    $this->assertEquals(1, $o2->foo);
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
  
  public function testExtensions() {
    $o = new StorableObjectTestExtension();
    $o->setOverride('hello');
    
    $this->assertEquals('FOO-hello', $o->getOverride());
    
    $o->setOtherValue('foo');
    $this->assertEquals('tinkerbell', $o->getOtherValue());
  }
  
  /*
  public function testDecorator() {
    $a = new StorableObjectTestExtension();
    $o = new StorableObjectTestDecorator($a);
    
    $o->setOverride('hello');
    
    $this->assertEquals('StorableObjectTestDecorator-hello', $o->getOverride());
    
    $o->setOtherValue('foo');
    $this->assertEquals('tinkerbell', $o->getOtherValue());
  }
  */
  
  public function testAutocast() {
    
    $foo = new StorableObject();
    $bar = new StorableObjectTestExtension();
    $foo->setBar($bar);
    
    $ser = $foo->toArray();
    
    $this->assertEquals('StorableObjectTestExtension', $ser['bar'][StorableObject::AUTOCAST_KEY]);
    
    $newObj = StorableObject::newFromArray($ser);
    $this->assertTrue($newObj->getBar() instanceof StorableObjectTestExtension);
    
  }
  
}

class StorableObjectTestExtension extends StorableObject {
  
  public function setOverride($value) {
    $this->override = 'FOO-' . $value;
  }
  
  public function getOtherValue() {
    return 'tinkerbell';
  }
  
}

