<?php
/** @file
 *
 * LoadThemeFromIni is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-06.
 */

namespace Villain\Theme;

/**
 * Read a theme INI file and load the theme.
 *
 * The standard method of building a theme in Fortissimo is to create a subclass of BaseFortissimoCommand.
 * This is good from a performance standpoint, but a little out of character for a theme builder to have
 * to know. The LoadThemeFromIni command provides a way of loading a theme from an INI file instead of a
 * class file.
 *
 * The file format for theme INIs is this:
 *
 * @code
 * [templates]
 * my.template.name = /my/template/path.tpl.php
 * my.other.template.name = /some/other/path.tpl.php
 *
 * [functions]
 * my.func = callback_function_name
 * my.staticFunc = SomeClass::staticMethod
 *
 * ; This is equiv to $foo = new MyClass(); $foo->myMethod($vars)
 * my.classFunc[] = MyClass
 * my.classFunc[] = myMethod
 *
 * ; In the future, perhaps this will work:
 * my.otherClassFunc = MyOtherClass->myMethod($vars)
 * 
 * @endcode
 *
 * Notes:
 * - callback functions cannot be resolved automatically by the autoloader. Make sure they are included.
 *
 * @author Matt Butcher
 */
class LoadThemeFromIni extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('')
      ->usesParam('filename', 'The full path to the INI file that this should parse.')
      //->withFilter('string')
      ->whichIsRequired()
      
      ->usesParam('cacheLifetime', 'The lifetime in seconds that this should be cached.')
      
      ->declaresEvent('onLoad', 'Fires immediately after the theme file has been loaded and parsed. It receives the raw array representing the INI file.')
      
      ->andReturns('Boolean TRUE if the theme was initialized.')
    ;
  }

  public function doCommand() {
    $ini = $this->param('filename');
    
    $contents = $this->parseIni($ini);
    
    $e = new \stdClass();
    $e->context = $this->context;
    $e->commandName = $this->name;
    $e->data =& $contents;
    $this->fireEvent('onLoad', $e);


    $this->registerThemes($contents);
    
    return TRUE;
  }
  
  /**
   * Register the themes.
   *
   * @param array $themes
   *  An associative array (presumably parsed from an INI file) with 'templates', 'functions', 'preprocessors', and
   *  'postprocessors'.
   */
  protected function registerThemes($themes) {
    $package = new IniLoaderThemePackage($themes);
    
    \Theme::register($package);    
  }
  
  protected function parseIni($ini) {
    
    // We wrap all of this to allow subclasses to override.
    $data = parse_ini_file($ini, TRUE);
    return $data;
    
  }

  // ==========================
  //  Cacheable implementation
  // ==========================

  public function cacheKey() {
    $param = $this->param('filename', NULL);

    if (is_null($param)) return;

    return $this->name . '-' . $param;
  }

  public function cacheLifetime() {
    return (int)$this->param('cacheLifetime', 1800);
  }

  public function cacheBackend() {
    return $this->param('cacheBackend', NULL);
  }
  
  public function isCaching() {
    return $this->param('caching', TRUE);
  }
}

/**
 * A helper class for LoadThemeFromIni.
 *
 * This builds an ad hoc theme package to represent the contents of an INI file.
 */
class IniLoaderThemePackage extends \Villain\Theme\CoreThemePackage {
  
  protected $ini = array();
  protected $use_core = TRUE;
  
  public function __construct(array $ini_array) {
    $this->ini = $ini_array;
    if (isset($this->ini['settings']['use_core'])) {
      $this->use_core = (bool)$this->ini['settings']['use_core'];
    }
  }
  
  public function templates() {
    $templates = $this->extract('templates');
    if ($this->use_core) {
      $templates += parent::templates();
    }
    return $templates;
  }
  
  public function functions() {
    $functions = $this->extract('functions');
    if ($this->use_core) {
      $functions += parent::functions();
    }
    return $functions;
  }
  
  public function preprocessors() {
    $preprocessors = $this->extract('preprocessors');
    if ($this->use_core) {
      $preprocessors += parent::preprocessors();
    }
    return $preprocessors;
  }
  
  public function postprocessors() {
    $postprocessors = $this->extract('postprocessors');
    if ($this->use_core) {
      $postprocessors += parent::postprocessors();
    }
    return $postprocessors;
  }
  
  protected function extract($key) {
    return empty($this->ini[$key]) ? array() : $this->ini[$key];
  }
  
}

