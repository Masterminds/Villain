<?php
/** @file
 *
 * AddIniToContext provides INI file parsing.
 *
 * Created by Matt Butcher on 2011-05-10.
 */

/**
 * Parse INI files and insert the data into the context.
 *
 * This parses configuration files in the INI format (like php.ini) and places
 * the configuration into the context.
 *
 * @author Matt Butcher
 */
class AddIniToContext extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Parse an INI file and insert the data into the context.')
      ->usesParam('filename', 'A full path to a file to parse.')
        ->whichIsRequired()
      ->usesParam('parseSections', 'If this is true, this will parse [section] sections.')
        ->withFilter('boolean')
        ->whichHasDefault(TRUE)
      ->usesParam('useSection', 'Extract only the named section and insert its values into the context.')
        ->withFilter('string')
      
      ->declaresEvent('onLoad', 'Fires after the file has loaded. This always provides the entire contents of the parsed INI in $e->data.')
      
      ->andReturns('The entire INI is inserted into the context. Nothing is returned.')
    ;
  }

  public function doCommand() {
    $filename = $this->param('filename');
    $parseSections = $this->param('parseSections');
    $useSection = $this->param('useSection', NULL);
    
    $ini = parse_ini_file($filename, $parseSection);
    
    $e = $this->baseEvent();
    $e->data =& $ini;
    $this->fireEvent('onLoad', $e);
    
    if ($parseSections && !empty($useSection)) {
      $ini = $this->filterBySection($ini, $section);
    }

    $this->injectIntoContext($ini);
  }
  
  /**
   * Return only configuration for the specified section.
   *
   * @param array $ini
   *  The INI array.
   * @param string $section
   *  The section to check.
   * @return array
   *  The specified subsection. Note that if the subsection does not exist, an empty array
   *  is returned.
   */
  protected function filterBySection($ini, $section) {
    // Avoid E_STRICT errors.
    return isset($ini[$section]) ? $ini[$section] : array();
  }
  
  /**
   * Inject the data into the context.
   *
   * @param array $array
   *  The array of data to inject.
   */
  protected function injectIntoContext($array) {
    $this->context->addAll($array);
  }
  
}

