<?php

// Enable bundle support.
use \Villain\Bundles\Bundle;

// Since Villain uses namespaces and a namespace-aware
// autoloader, you shouldn't have to do this very often.
Config::includePath('core/Fortissimo/Theme'); // Include the Theme classes, which are optional.

// Include the BasicBlog 
Bundle::load('BasicBlog');

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
  ->doesCommand('config')->whichInvokes('\Villain\Configuration\AddIniToContext')
    ->withParam('filename')->whoseValueIs('config/villain.ini')
  ->doesCommand('filters')
    ->whichInvokes('\Villain\Filters\InitializeFilters')
    ->withParam('collection')->whoseValueIs('filters')
  //->doesCommand('some_command')->whichInvokes('SomeCommandClass')
  //->doesCommand('some_other_command')->whichInvokes('SomeOtherCommandClass')
;

Config::group('renderHTML')
  ->doesCommand('theme_init')
    ->whichInvokes('InitializeTheme')
    ->withParam('path')->from('cxt:site.theme')->whoseValueIs('theme/vanilla')
;

/*
 * Authentication group.
 * Any pages that need authentication support should use this group.
 */
Config::group('auth')

;

Config::request('test')
  ->usesGroup('bootstrap')
  ->usesGroup('renderHTML')
  ->doesCommand('loadTheme')->whichInvokes('Villain\Theme\LoadThemeFromIni')
    ->withParam('filename')->whoseValueIs('../test/theme_test.ini')
    
    
  //->doesCommand('dump')->whichInvokes('FortissimoContextDump')
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
 * Internal commands.
 */
Config::request('@create-bundle')

  //->doesCommand('arguments')

  ->doesCommand('create')
  ->whichInvokes('\Villain\Bundles\ScaffoldNewBundle')
  ->withParam('name')->from('arg:2')
;

/*
Config::request('@install-bundle')
  ->doesCommand('arg')
    ->whichInvokes('\Villain\CLI\ParseOptions')
    ->withParam('optionSpec')
    ->whoseValueIs(array(
      '--all' => array(
        'help' => 'Try to ',
        'value' => TRUE,
      ),
      '--help' => array(
        'help' => 'Print help text for this command.',
        'value' => FALSE,
      ),
    ))
  ->withParam('offset')->whoseValueIs(1)
  
  //->doesCommand('install')
  
;

Config::request('@uninstall-bundle')
;
*/

// The Villain installer.
Config::request('@install-villain')
  ->usesGroup('bootstrap')
  ->doesCommand('step1')
  ->whichInvokes('\Villain\InstallVillain')
;

Config::request('@test')
  ->doesCommand('arguments')
  ->whichInvokes('\Villain\CLI\ParseOptions')
  ->withParam('optionSpec')
  ->whoseValueIs(array(
    '--test' => array(
      'help' => 'This is a test option',
      'value' => TRUE,
    ),
    '--help' => array(
      'help' => 'Print help text for this command.',
      'value' => FALSE,
    ),
  ))
  ->withParam('offset')->whoseValueIs(1)
  
  ->doesCommand('prompts')
  ->whichInvokes('\Villain\CLI\ReadLine')
  ->withParam('prompts')
  ->whoseValueIs(array(
    'first name' => array(
      'help' => 'What is your first name?',
      'default' => 'Anonymous',
    ),
    'last name' => array(
      'help' => 'What is your last name?',
    ),
  ))
  
  //->doesCommand('echo')->whichInvokes('\FortissimoEcho')->whoseDefaultIs()
  ->doesCommand('dump')->whichInvokes('FortissimoContextDump')
;

Config::request('@create-blog')
  ->usesGroup('bootstrap')
  ->doesCommand('input')
    ->whichInvokes('\Villain\CLI\Readline')
    ->withParam('prompts')
    ->whoseValueIs(array(
      'title' => array('help' => 'The title of the blog.'),
      'description' => array('help' => 'What the blog is about.'),
      'entries' => array('help' => 'Entries per page (10).', 'default' => 10),
      'createdBy' => array('help' => 'Username of user who created this.'),
      'url' => array('help' => 'Base URL.', 'default' => 'blog'),
    ))
  ->doesCommand('createBlog')
    ->whichInvokes('BasicBlog\Content\CreateBlog')
    ->withParam('title')->from('cxt:title')
    ->withParam('description')->from('cxt:description')
    ->withParam('entriesPerPage')->from('cxt:entries')
    ->withParam('createdBy')->from('cxt:createdBy')
    ->withParam('shortName')->from('cxt:url')
  ->doesCommand('save')
    ->whichInvokes('\Villain\Content\SaveContent')
    ->withParam('content')->from('cxt:createBlog')
    ->withParam('collection')->whoseValueIs('blog')
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