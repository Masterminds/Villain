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

// KISS
//Config::logger('soil')->whichInvokes('SimpleOutputInjectionLogger');

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

  // Verify Requirements
  ->doesCommand('requirements')
    ->whichInvokes('\Villain\Installer\CheckRequirements')
  ->doesCommand('promptUser')
    ->whichInvokes('\ReadLine')
    ->withParam('prompts')
    ->whoseValueIs(array(
      'MongDB_Server' => array(
        'help' => 'The URL of your MongoDB (mongodb://localhost:27017)',
        'default' => 'mongodb://localhost:27017',
      ),
      'MongDB_Database' => array(
        'help' => 'The default database name (villain)',
        'default' => 'villain',
      ),
      'MongDB_User' => array(
        'help' => 'Username to authenticate to MongoDB (leave empty if none)',
        'default' => '',
      ),
      'MongDB_Password' => array(
        'help' => 'The password for authenticating to MongoDB (leave empty if none)',
        'default' => '',
      ),
    )
  )
  ->doesCommand('testConnection')
    ->whichInvokes('\Villain\Installer\CheckMongoConnection')
    ->withParam('server')->from('cxt:MongoDB_Server')
    ->withParam('username')->from('cxt:MongoDB_User')
    ->withParam('password')->from('cxt:MongoDB_Password')
    ->withParam('database')->from('cxt:MongoDB_Database')
  ->doesCommand('installDB')
    ->whichInvokes('\Villain\Installer\InstallMongoDatasource')
    ->withParam('server')->from('cxt:MongoDB_Server')
    ->withParam('username')->from('cxt:MongoDB_User')
    ->withParam('password')->from('cxt:MongoDB_Password')
    ->withParam('database')->from('cxt:MongoDB_Database')
    
  // Install the commands.php
  ->doesCommand('commands')
    ->whichInvokes('\Villain\Installer\InstallCommandsPHP')

  // Add the Mongo DB config to the new commands file.
  ->doesCommand('configureDatasource')
    ->whichInvokes('\Villain\Installer\InstallMongoDatasource')
    ->withParam('file')->whoseValueIs('config/commands.php')
    ->withParam('pattern')->whoseValueIs()
    ->withParam('replacement')->whoseValueIs()
    
  /*
   * - Get info
   *   * Database location
   *   * Other config stuff
   * - Generate files
   *   * INI file?
   *   * commands.php
   * - Create database collections, indexes, etc.
   */
  
/*
  // Phase I: Get minimal information to build Villain.

  ->doesCommand('reboot')
    ->whichInvokes('\Villain\Installer\RebootForInstallation')

  // Phase II: Bootstrap Villain and install.
  ->usesGroup('bootstrap')
  ->doesCommand('step1')
    ->whichInvokes('\Villain\Installer\InstallVillain')
*/
;