<?php
require_once 'PHPUnit/Framework.php';
require_once 'VillainTestHarness.php';

class LoadThemeFromIniTest extends VillainTestCase {
  
  
  public function testDoCommand() {
    
    Config::initialize();
    Config::includePath('src/core/Fortissimo/Theme');
    Config::request('foo')
      ->doesCommand('test')
      ->whichInvokes('\Villain\Theme\LoadThemeFromIni')
      ->withParam('filename')->whoseValueIs('assets/test_theme.ini')
    ;
    
    
    
    $villain = new VillainTestHarness();
    //throw new Exception(print_r(get_include_path(), TRUE) . '===' . getcwd());
    $this->assertTrue($villain->hasRequest('foo'));
    
    $villain->handleRequest('foo');
    
    $cxt = $villain->getContext();
    
    $this->assertTrue($cxt->has('test'));
    $this->assertEquals('value', $cxt->get('test'));
    
  }
  
}