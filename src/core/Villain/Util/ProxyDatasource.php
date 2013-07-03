<?php
/**
 * @file
 * 
 * Provides a proxy for another datasource.
 * 
 * Initially created by mbutcher on Aug 18, 2011.
 */

namespace Villain\Util;

/**
 * Provides a proxy for another FortissimoDatasource.
 * 
 * This datasource can be used to wrap another datasource. In 
 * addition to proxying the main datasource methods, this allows
 * a datasource to be added, initialized, or replaced later in
 * the bootstrap process.
 * 
 * It is volatile by design, and should be used very carefully.
 * Its original intent was to provide late bootstrapping 
 * capabilities for the Villain installer, which represents a
 * highly controlled execution. (No bundles, no event listeners,
 * no alarms, and no surprises.)
 */
class ProxyDatasource extends \FortissimoDatasource {
  
  protected $ds = NULL;
  
  /**
   * Create and initialize a datasource.
   * 
   * Given a classname and the appropriate configuration information, 
   * create and initialize a new datasource.
   * 
   * Note that a datasource is initialized immediately, and is not lazy
   * like standard datasources.
   * 
   * @param string $className
   * @param array $params
   *   The configuration params for this datasource. This is in the form of 
   *   name/value pairs, where the name is what would normally be passed in
   *   Config::withParam(), and the value is what would normally be passed in
   *   Config::whoseValueIs().
   * @return \FortissimoDatasource
   *   Returns the new FortissimoDatasource, initialized and ready to use.
   *   This can be passed into setInnerDatasource().
   */
  public function createDatasource($className, $params) {
    
    // The default setting trickles down:
    $params['isDefault'] = $this->isDefault();
    
    $ds = new $className($params, $this->name . '_proxy_receiver');
    $ds->setCacheManager($this->cacheManager);
    $ds->setLogManager($this->logManager);
    $ds->init();
    
    return $ds;
  }
  
  
  public function init() {
    // We do NO intialization of the proxied object. Doing
    // that could result in an unknown state.
  }
  
  public function get() {
    if (!$this->hasInnerDatasource()) {
      throw new \Villain\Exception('Datasource proxy failed.');
    }
    return $this->ds;
  }
  
  public function setInnerDatasource(\FortissimoDatasource $ds) {
    $this->ds = $ds;
  }
  
  public function getInnerDatasource() {
    return $this->ds;
  }
  
  /**
   * Test whether this proxy currently has a target.
   * 
   * If this proxy is proxying to another datasource, this will return TRUE.
   * Otherwise, this will return FALSE.
   * 
   * @return boolean
   *   TRUE if this is proxying to another datasource. FALSE if no datasource
   *   is receiving proxy calls.
   */
  public function hasInnerDatasource() {
    return !empty($this->ds);
  }
  
  /**
   * Remove the datasource and no longer proxy to it.
   * 
   * This will remove the inner datasource and allow it to be 
   * garbage collected. Any further requests to this
   * datasource will result in proxy failures.
   */
  public function removeInnerDatasource() {
    $this->ds = NULL;
  }
}