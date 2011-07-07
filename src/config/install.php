<?php
/** @file
 *
 * The commands file for the installer.
 *
 * This is used only when Villain is being installed. The villain.ini file points 
 * to this file in order to bootstrap a very minimal Fortissimo instance that 
 * can then be used to configure Villain.
 *
 * Created by Matt Butcher on 2011-07-06.
 */

// Use the Fortissimo CLI classes.
Config::includePath('core/Fortissimo/CLI');

// Log straight to the client.
Config::logger('foil')->whichInvokes('FortissimoOutputInjectionLogger');

Config::group('bootstrap')
  ->doesCommand('config')->whichInvokes('\Villain\Configuration\AddIniToContext')
    ->withParam('filename')->whoseValueIs('config/villain.ini')
  ->doesCommand('filters')
    ->whichInvokes('\Villain\Filters\InitializeFilters')
    ->withParam('collection')->whoseValueIs('filters')
  //->doesCommand('some_command')->whichInvokes('SomeCommandClass')
  //->doesCommand('some_other_command')->whichInvokes('SomeOtherCommandClass')
;


// The Villain installer.
Config::request('install')

  /*
   * - Preflight Check:
   *   * Check for Mongo
   *   * Check for existing files
   * - Get info
   *   * Database location
   *   * Other config stuff
   * - Generate files
   *   * INI file?
   *   * commands.php
   * - Create database collections, indexes, etc.
   */

  // Phase I: Get minimal information to build Villain.
  ->doesCommand('promptUser')
    ->whichInvokes('ReadLine')
    //->whichInvokes('\Villain\Installer\Questionnaire')
  ->doesCommand('reboot')
    ->whichInvokes('\Villain\Installer\RebootForInstallation')

  // Phase II: Bootstrap Villain and install.
  ->usesGroup('bootstrap')
  ->doesCommand('step1')
    ->whichInvokes('\Villain\Installer\InstallVillain')
;