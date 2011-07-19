<?php
require_once 'config/commands.php';

Config::request('@test')
  ->doesCommand('phpunit')
  ->withParam('tests')->whoseValueIs('test/Tests/Villain/')->from('arg:2')
  ->whichInvokes('\Villain\Testing\RunExternalPHPUnit')
;