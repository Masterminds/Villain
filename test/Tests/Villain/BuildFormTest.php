<?php
require_once 'PHPUnit/Framework.php';
require_once 'VillainTestHarness.php';

use Villain\Form\Form;

class BuildFormTest extends VillainTestCase {

  public function setUp() {
    Config::initialize();
    Config::includePath(getcwd() . '/src/core/Villain');
    Config::includePath(getcwd() . '/src/core/Fortissimo/Theme');
    Config::request('foo')
      ->doesCommand('themeInit')
        ->whichInvokes('InitializeTheme')
        ->withParam('path')->whoseValueIs('theme/vanilla')
      ->doesCommand('form')
        ->whichInvokes('\Villain\Form\InitializeForms')
      ->doesCommand('fooForm')
        ->whichInvokes('TestFormCommand')
    ;
  }

  public function testFormInstance() {
    $villain = new VillainTestHarness();

    // Make sure the request exists. If this doesn't exist there are some
    // serious problems going on.
    $this->assertTrue($villain->hasRequest('foo'), 'The request foo exists.');

    $villain->handleRequest('foo');

    $cxt = $villain->getContext();

    $this->assertTrue($cxt->has('form'), "Check that the form system is initialized.");
    
    $this->assertTrue($cxt->has('fooForm'), "Check that the form exists.");
  }
}

class TestFormCommand extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Create a Form for testing.')
      ->andReturns('A \Villain\Form\Form instance.')
    ;
  }

  public function doCommand() {
    $form = $this->context('form')->create();
    return $form;
  }
}