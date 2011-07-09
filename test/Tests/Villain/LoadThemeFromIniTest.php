<?php
require_once 'PHPUnit/Framework.php';
require_once 'VillainTestHarness.php';

// Phing's autoloader is preventing the Fortissimo autoloader from working
// so we have to do this manually:
/*
require_once 'src/core/Fortissimo/Theme/InitializeTheme.php';
require_once 'src/core/Fortissimo/Theme/Theme.php';
require_once 'src/core/Villain/Theme/LoadThemeFromIni.php';
*/

class LoadThemeFromIniTest extends VillainTestCase {
  
  
  public function testDoCommand() {
    
    
    Config::initialize();
    Config::includePath(getcwd() . '/src/core/Villain');
    Config::includePath(getcwd() . '/src/core/Fortissimo/Theme');
    Config::request('foo')
      ->doesCommand('themeInit')
        ->whichInvokes('InitializeTheme')
        ->withParam('path')->whoseValueIs('theme/vanilla')
      ->doesCommand('test')
        ->whichInvokes('\Villain\Theme\LoadThemeFromIni')
        ->withParam('filename')->whoseValueIs('test/theme_test.ini')
      ->doesCommand('cxtAdd')
        ->whichInvokes('FortissimoAddToContext')
        ->withParam('foo')->whoseValueIs('value')
      
    ;
    Config::logger('foil')->whichInvokes('FortissimoOutputInjectionLogger');
    
    $villain = new VillainTestHarness();
    //throw new Exception(print_r(get_include_path(), TRUE) . '===' . getcwd());
    $this->assertTrue($villain->hasRequest('foo'));
    
    $villain->handleRequest('foo');
    
    $cxt = $villain->getContext();
    
    //throw new Exception(print_r($cxt, TRUE));
    
    $this->assertTrue($cxt->has('test'), "Check that 'test' exists.");
    $this->assertEquals('value', $cxt->get('foo'));
    // Test that the count theme is there:
    $this->assertTrue(\Theme::isRegistered('my.count'), 'Check that count theme is registered.');
    
    $vars = array(1,2, 3, 4);
    $res = \Theme::render('my.count', $vars);
    
    
    $this->assertEquals(4, $res);

    // Test a core theme callback.
    $this->assertTrue(\Theme::isRegistered('form.textfield'), 'Check that core theme callback is registered.');
    //$element = new \Villain\Form\Textfield();
    //$textfield = \Theme::render('form.textfield', $element);

    
  }
  
}