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
 * This object is initialized in stages. Each bundles is first added with BundleManager::addBundle(). The specs
 * may then be mutated during the loading period. Once the bundle has finished loading, BundleManager::validate()
 * is called. This does the requisite testing and configuration.
 *
 * Functions that work before validate():
 *
 * - addBundle()
 * - has()
 * - getBundles()
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
  
  /**
   * Initialize the bundle.
   *
   * This does initial checks on the bundle, and then sets up the 
   * bundle for use inside of Villain.
   *
   * @param string $bundleName
   *  The name of the bundle.
   * @param boolean $force
   *  Under rare cases, it may be desirable to force a load even if 
   *  conflicts or dependencies are not met. If this is TRUE, such 
   *  will be the case. USE WITH GREAT CAUTION.
   * @throws \Villain\Exception
   *  Is thrown if...
   *  - dependencies aren't met
   *  - conflicts are encountered
   */
  public function validate($bundleName, $force = FALSE) {
    if(!$this->has($bundleName)) {
      throw new \Villain\Exception('No bundle named ' . $bundleName);
    }
    
    $spec = $this->bundles[$bundleName];
    
    // Check dependencies, virtuals, and conflicts.
    if (!$force) {
      $this->checkDependencies($spec);
      $this->checkVirtuals($spec);
      $this->checkConflicts($spec);
    }
    
  }
  
  /**
   * Enable all validated bundles.
   *
   * Once all of the bundles are added and validated, the whole lot of
   * them can be enabled. Enabling them will do the following:
   *
   * - Check that the modules are registered
   * - Add the bundle's src/ folder to the include path
   * - Run any necessary initialization code
   *
   * @param FortissimoExecutionContext $cxt
   *  A context.
   */
  public function enable(FortissimoExecutionContext $cxt) {
    
  }
  
  public function update($name) {}
  public function install($name) {}
  public function uninstall($name) {}
  
  /**
   * Check bundle dependencies.
   *
   * This verifies that (a) the required bundles are all already initialized, and (b) that
   * the required bundles are within the right version range.
   *
   * @param BundleSpecification $spec
   *  The BundleSpecification that we are currently checking.
   */
  protected function checkDependencies($spec) {
    $required = $spec->getDependencies();
    
    // Return fast on empty.
    if (empty($required)) {
      return;
    }

    // For each requirement, test version info.
    foreach ($required as $name => $version) {
      
      // First, make sure the required module is there.
      if (!isset($this->bundles[$name])) {
        throw new \Villain\Exception(sprintf('%s is required by %s, but is missing.', $name, $spec->getName()));
      }
      
      $ver = $this->bundles[$name]->getVersion();
      
      // Next, make sure we exceed the minimum.
      if (!empty($version['min']) && version_compare($ver, $version['min'], '<')) {
        throw new \Villain\Exception(sprintf(
          '%s %s is too old. %s requires at least version %s.', 
          $name, 
          $ver,
          $spec->getName(), 
          $version['min']
        ));
      }
      
      // Make sure we don't exceed the maximum.
      if (!empty($version['max']) && version_compare($ver, $version['max'], '>')) {
        throw new \Villain\Exception(sprintf(
          '%s %s is too new. %s requires a version no newer than %s.', 
          $name, 
          $ver,
          $spec->getName(), 
          $version['max']
        ));
      }
      
      // Finally, make sure this is not explicitly excluded.
      if (!empty($version['not'])) {
        foreach ($version['not'] as $bad_ver) {
          if (version_compare($ver, $bad_ver, '==')) {
            throw new \Villain\Exception(sprintf(
              '%s %s is incompatible with %s.', 
              $name, 
              $ver,
              $spec->getName()
            ));
          }
        }
      }
      
      // If we get here, this bundle is okay.
    }
    
    // If we get here, all requirements are met.
    return TRUE;
  }
  
  /**
   * @param BundleSpecification $spec
   *  The BundleSpecification that we are currently checking.
   */
  protected function checkVirtuals($spec) {
    // XXX: Do we check if multiple things fill the same virtual?
  }
  
  /**
   * Check that there are no bundles known to conflict with this one. 
   *
   * @param BundleSpecification $spec
   *  The BundleSpecification that we are currently checking.
   */
  protected function checkConflicts($spec) {
    $avoid = $spec->getConflicts();
    $all = array_keys($this->bundles);
    
    // Insertsections are bad. If there is anything in the intersection, 
    // then there are conflicts.
    $conflicts = array_intersect($all, $avoid);
    
    if (!empty($conflicts)) {
      $conflict_names = implode(', ', $conflicts);
      $format = '%s is incompatible with: %s';
      throw new \Villain\Exception(sprintf($format, $spec->getName(), $conflict_names));
    }
    
    return TRUE;
  }
}