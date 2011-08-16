<?php
if (is_readable('config/commands.php')) {
  require_once 'config/commands.php';
}
else {
  trigger_error("It appears that Villain is not installed.", E_USER_NOTICE);
}


Config::request('@test')
  ->doesCommand('phpunit')
  // The get:flags is a cheap trick for running inside of IDEs. 
  // e.g. --get flags=--verbose+--colors
  ->withParam('options')->whoseValueIs('--colors --verbose')->from('get:flags')
  ->withParam('tests')->whoseValueIs('test/Tests/Villain/')->from('arg:2')
  ->whichInvokes('\Villain\Testing\RunExternalPHPUnit')
;