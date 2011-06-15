<?php
/** @file
 *
 * Declarations for bundles.
 *
 * Created by Matt Butcher on 2011-06-14.
 */

namespace Villain\Bundles;

/**
 * A description of a bundle.
 *
 * A BundleSpecification describes the attributes of a bundle. It is used to determine how to load
 * a bundle.
 *
 * Bundles are defined using the Bundle class. 
 * @code
 * <?php
 * Bundle::create('myBundle')->description('Does stuff')->version('1.0.0')->dependsOn('blog');
 * ?>
 * @endcode
 *
 * BundleSpecifications are Storable, and can safely be serialized and stored.
 */
class BundleSpecification implements \Villain\Storage\Storable {
  
  protected $name = NULL;
  protected $version = NULL;
  protected $description = '';
  protected $dependencies = array();
  protected $virtuals = array();
  protected $conflicts = array();
  
  public function __construct($bundleName = NULL) {
    $this->name = $bundleName;
  }
  
  /**
   * Get the name of this bundle.
   *
   * @return string
   *  The name of the bundle.
   */
  public function getName() {
    return $this->name;
  }
  
  public function getVersion() {
    return $this->version;
  }
  
  public function getDependencies() {
    return $this->dependencies;
  }
  
  public function getConflicts() {
    return $this->conflicts;
  }
  
  public function getVirtuals() {
    return $this->virtuals();
  }
  
  /**
   * Declare (in a sentence) what this bundle does.
   *
   * This is a convenience for humans to understand what a bundle does.
   */
  public function description($summary) {
    $this->description = $summary;
  }
  
  /**
   * Declare a dependency.
   *
   * A bundle can depend upon another bundle. If a bundle depends on another,
   * then it cannot be loaded unless the other bundle is loaded first. For that
   * reason, you cannot declare circular dependencies.
   *
   * BundleSpecification::dependsOn() can be called once for each bundle that this module depends 
   * upon. Version information can be specified, too, allowing a developer to declare which versions
   * of a bundle are required before this bundle will function properly.
   *
   * @code
   * <?php
   * Bundle::create('MyBundle')
   *  ->dependsOn('BundleOne', '1.0.1')
   *  ->dependsOn('BundleTwo', '2.0.1', '2.1.0', array('2.0.99', '2.0.2-rc1'));
   * ?>
   * @endcode
   *
   * The code above declares that MyBundle requires BundleOne, version 1.0.1 or greater, and also 
   * BundleTwo between versions 2.0.1 and 2.1.0, excluding 2.0.99 and 2.0.2-rc1.
   *
   * @param string $otherBundleName
   *  The name of the bundle upon which this depends.
   * @param string $version
   *  The minimum version of the other bundle which must be installed for this to function.
   * @param string $maxVersion
   *  The maximum version of the other bundle that this bundle will work with.
   * @param array $excludeVersions
   *  Explicit version strings can be passed in, too. If the other bundle's version matches a 
   *  string in this list, then it will be marked incompatible.
   */
  public function dependsOn($otherBundleName, $version = NULL, $maxVersion = NULL, $excludeVersions = array()) {
    $this->dependencies[$otherBundleName] = array(
      'min' => $version,
      'max' => $maxVersion,
      'not' => $excludeVersions,
    );
    return $this;
  }
  
  /**
   * Declare the version of this bundle.
   *
   * For full information on version numbers, see the official PHP spec, discussed briefly in the documentation
   * for the PHP builtin version_compare().
   *
   * Examples:
   * - '1.0.0'
   * - '2.1.99'
   * - '3.2.1-beta-1'
   * - '4.5.6-rc1'
   *
   * @param string $version
   *  The version string, in standard PHP format (See version_compare()).
   * @return BundleSpecification
   *  The current bundle specification.
   * @see http://www.php.net/manual/en/function.version-compare.php
   */
  public function version($version) {
    $this->version = $version;
    return $this;
  }
  
  /**
   * Provides services described by a virtual bundle.
   *
   * EXPERIMENTAL
   *
   * @param string $virtualBundleName
   *  The virtual bundle name.
   * @return BundleSpecification
   *  The current bundle spec.
   */
  public function provides($virtualBundleName) {
    $this->virtuals[$virtualBundleName] = $virtualBundleName;
    return $this;
  }
  
  /**
   * Declare this bundle to conflict with or be incompatible with some other bundle.
   *
   * @param string $otherBundleName
   *  The name of the bundle with which this is incompatible.
   * @return BundleSpecification
   *  This bundle spec.
   */
  public function incompatibleWith($otherBundleName) {
    //$this->conflicts[$otherBundleName] = $otherBundleName;
    $this->conflicts[] = $otherBundleName;
    return $this;
  }
  
  // For Storable
  public function toArray() {
    return array(
      'name' => $this->name,
      'version' => $this->version,
      'description' => $this->description,
      'virtuals' => $this->virtuals,
      'dependencies' => $this->dependencies,
      'conflicts' => $this->conflicts,
    );
  }
  // For Storable
  public function fromArray($arr) {
    $this->name = $arr['name'];
    $this->version = $arr['version'];
    $this->description = $arr['description'];
    $this->virtuals = $arr['virtuals'];
    $this->dependencies = $arr['dependencies'];
    $this->conflicts = $arr['conflicts'];
  }
  
}