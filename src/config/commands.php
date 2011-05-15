<?php

// Since Villain uses namespaces and a namespace-aware
// autoloader, you shouldn't have to do this very often.
Config::includePath('core/Fortissimo/Theme'); // Include the Theme classes, which are optional.

/*
 * The main storage backend for Villain.
 */
Config::datasource('db') // Name of datasource
  ->whichInvokes('FortissimoMongoDatasource') // The class it uses
  ->withParam('server')->whoseValueIs('mongodb://localhost:27017')
  ->withParam('defaultDB')->whoseValueIs('villain')
  ->withParam('isDefault')->whoseValueIs(TRUE) // Only datasource one can be default.
;


/*
 * Bootstrap.
 * Just about every page should bootstrap.
 */
Config::group('bootstrap')
  ->doesCommand('config')->whichInvokes('Villain\Configuration\AddIniToContext')
    ->withParam('filename')->whoseValueIs('config/villain.ini')
  //->doesCommand('some_command')->whichInvokes('SomeCommandClass')
  //->doesCommand('some_other_command')->whichInvokes('SomeOtherCommandClass')
;

Config::group('renderHTML')
  ->doesCommand('theme_init')
    ->whichInvokes('InitializeTheme')
    ->withParam('path')->from('cxt:site.theme')->whoseDefaultIs('theme/vanilla')
;

/*
 * Authentication group.
 * Any pages that need authentication support should use this group.
 */
Config::group('auth')

;

Config::request('test')
  ->usesGroup('bootstrap')
  ->doesCommand('dump')->whichInvokes('FortissimoContextDump')
;

Config::request('login')
  ->usesGroup('bootstrap')
;

/*
 *
 */
Config::request('default')
  // Bootstrap
  ->usesGroup('bootstrap')
  // Initialize the context with some values.
  ->doesCommand('initContext')
    ->whichInvokes('FortissimoAddToContext')
    ->withParam('title')
      ->whoseValueIs('Villain')
    ->withParam('welcome')
      ->whoseValueIs('Fortissimo has been successfully installed.')
  // Use the template engine to generate a welcome page.
  ->doesCommand('tpl')
    ->whichInvokes('FortissimoTemplate')
    ->withParam('template')
      ->whoseValueIs('example.twig')
    ->withParam('templateDir')
      ->whoseValueIs('theme/vanilla')
    ->withParam('templateCache')
      ->whoseValueIs('./cache')
    ->withParam('disableCache')
      ->whoseValueIs(TRUE) // This should be FALSE on production.
    // ->withParam('debug')->whoseValueIs(FALSE)
    // ->withParam('trimBlocks')->whoseValueIs(TRUE)
    // ->withParam('auto_reload')->whoseValueIs(FALSE)
    
  // Send the rendered welcome page to the browser.
  ->doesCommand('echo')
    ->whichInvokes('FortissimoEcho')
    ->withParam('text')
      ->from('context:tpl')
;

/*
 */
Config::logger('foil')
  ->whichInvokes('FortissimoOutputInjectionLogger')
;

/*
 * For production use this:
 */
/*
Config::logger('fizzle')
  ->whichInvokes('FortissimoSyslogLogger')
;
*/

/*
 * Caching support is built into Fortissimo.
 *
 * Fortissimo has built-in support for multiple caching backends. For example, applications could
 * strategically cache some data in memcache and some in APC. Fortissimo includes a simple 
 * implementation of a Memcached caching layer (FortissimoMemcacheCache). 
 * @code
 * <?php
 * Config::cache('memcache')
 *   ->whichInvokes('FortissimoMemcacheCache')
 *   ->withParam('servers')
 *     ->whoseValueIs(array('example.com:11211', 'example.com:11212'))
 *   ->withParam('persistent')
 *     ->whoseValueIs(FALSE)
 *   ->withParam('compress')
 *     ->whoseValueIs(FALSE)
 * ;
 * ?>
 * @endcode
 *
 * If you want commands to cache (as opposed to just entire requests), your classes will need
 * to implement Cacheable and extend BaseFortissimoCommand (or you can handle caching yourself
 * in FortissimoCommand::execute()).
 */