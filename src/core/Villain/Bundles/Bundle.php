<?php
/** @file
 *
 * Core Bundle logic.
 *
 * Created by Matt Butcher on 2011-06-10.
 */

namespace Villain\Bundles;

/**
 * Manage Villain Bundles.
 *
 * Villain can be extended through the user of bundles. A "bundle" is a package of code and assets
 * that follow certain specifications.
 */
class Bundle {
  
  const DIR = 'bundles/';
  
  /**
   * The bundle manager.
   */
  protected static $bunnyman = NULL;
  
  /**
   * Test whether a bundle name is valid.
   *
   * Currently, valid bundle names are composed of a-z, A-Z, 0-9, - and _.
   *
   * @param string $bundleName
   *  The name of a bundle.
   * @return boolean
   *  TRUE if this name is valid, FALSE otherwise.
   */
  public static function isValidName($bundleName) {
    return !(empty($bundleName) || preg_match('/^[a-zA-Z0-9\.\-_]+$/', $bundleName) == 0);
  }
  
  /**
   * Use a bundle.
   * This will import it and register it with Villain. Typically, you will Bundle::use() from somewhere in
   * a commands.php file or similar auxilliary file. Since bundles assume that they are being added before a 
   * request is handled, they may not perform as expected if they are added later.
   *
   * @param string $bundleName
   *  The name of the bundle to add.
   * @param array $options
   *   Options to control execution of the bundle.
   *
   */
  public static function load($bundleName, $options = array()) {
    
    if (!self::isValidName($bundleName)) {
      throw new \Villain\InterruptException(sprintf('Bundle %s must have a valid name.', $bundleName));
    }
    
    // Let the bundle configure itself:
    require self::DIR . $bundleName . '/bundle.php';
    
    self::manager()->validate($bundleName);
  }
  
  /**
   * Declare that a new Bundle is available.
   *
   * Bundle creators should use this method to declare a new bundle.
   *
   * @return BundleSpecification
   *  Returns a new BundleSpecification describing this bundle.
   */
  public static function create($bundleName) {
    $spec = new BundleSpecification($bundleName);
    
    // Register the spec with the bunnyman.
    self::manager()->addBundle($spec);
    
    return $spec;
  }
  
  /**
   * Get the bundle manager.
   *
   * Per request, there is only one definitive BundleManager instance. Access
   * to this instance is controlled by Bundle::manager().
   *
   * The BundleManager should know about all of the bundles currently declared.
   */
  public static function manager() {
    if (!isset(self::$bunnyman)) {
      self::$bunnyman = new BundleManager();
    }
    
    return self::$bunnyman;
  }
  
}