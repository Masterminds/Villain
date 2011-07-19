<?php
require_once 'config/commands.php';

Config::request('@test')
  ->doesCommand('phpunit')
  ->withParam('tests')->whoseValueIs('test/Tests/Villain/')
  ->whichInvokes('\Villain\Testing\RunExternalPHPUnit')
;