<?php
require_once 'PHPUnit/Framework.php';
require_once 'VillainTestHarness.php';

class LoadThemeFromIniTest extends VillainTestCase {
  
  
  public function testDoCommand() {
    $villain = new VillainTestHarness();
    
    Config::initialize();
    Config::includePath('src/core/Fortissimo/Theme');
    Config::request('foo')
      ->doesCommand('test')
      ->whichInvokes('\Villain\Theme\LoadThemeFromIni')
      ->withParam('filename')->whoseValueIs('test/test_theme.ini')
    ;
    //throw new Exception(print_r(get_include_path(), TRUE) . '===' . getcwd());
    $this->assertTrue($villain->hasRequest('foo'));
    
    $villain->handleRequest('foo');
    
    $cxt = $villain->getContext();
    
    $this->assertTrue($cxt->has('test'), "Check that 'test' exists.");
    $this->assertEquals('value', $cxt->get('test'));
    
  }
  
}