<?php
/** @file
 *
 * Defines the class BundleManager.
 *
 * Created by Matt Butcher on 2011-06-14.
 */
 
namespace Villain\Bundles;

/**
 * The bundle manager.
 *
 * This class provides services for managing bundles.
 *
 * This object is initialized in stages. All bundles are first added with BundleManager::addBundle(). The specs
 * may then be mutated during the loading period. Once all bundles are loaded, the bundles can collectively be
 * initialized using BundleManager::initialize().
 *
 * Functions that work before initialize():
 *
 * - addBundle()
 * - has() Note that this check may be incomplete if bundles are still being added.
 * - getBundles() Note that this list may be incomplete if bundles are still being added.
 *
 * All others will have unpredictable behavior until after initialize() has been invoked.
 */
class BundleManager {
  
  protected $bundles = array();
  
  
  /**
   * Add a bundle to be managed.
   *
   * IMPORTANT: Bundles may not be fully defined by this point, so
   * it should not be assumed that anything other than BundleSpecification::getName() will be 
   * accurate,
   *
   * @param BundleSpecification $spec
   *  A partially-initialized BundleSpecification.
   */
  public function addBundle($spec) {
    $this->bundles[$spec->getName()] = $spec;
  }
  
  public function has($name) {
    return isset($this->bundles[$name]);
  }
  
  
  public function getBundles() {
    return $this->bundles;
  }
  
  public function initialize($bundleName) {
    if(!$this->has($bundleName)) {
      throw new \Villain\Exception('No bundle named ' . $bundleName);
    }
    
    // Check dependencies
    
    // Check virtuals
    
    // Check conflicts
    
  }
  
  public function update($name) {}
  public function install($name) {}
  public function uninstall($name) {}
  
  
}