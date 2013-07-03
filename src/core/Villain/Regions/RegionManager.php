<?php
/** @file
 *
 * Defines the class RegionManager.
 *
 * A RegionManager manages the Regions and items within a Region.
 *
 * Created by Matt Farina on 2011-07-19.
 */
namespace Villain\Regions;
/**
 * A RegionManager.
 *
 * @todo Add lots of documentation on usage.
 */
class RegionManager implements \Countable, \Iterator {
  
  protected $regions = array();

  /**
   * Add an item to a region.
   *
   * @param string $regionName
   *   The name of the region to add the item to. If the region does not exist it
   *   will be created.
   * @param string $name
   *   The name of the item to add to the region.
   * @param string $content
   *   The html content to add to the region. This is the content of the item.
   * @param float $weight
   *   Items in a region are rendered in a weighted order. This enables the weight
   *   to be set. Defaults to 0.
   * @param string $themeWrapper
   *   The name of the theme callback for the wrapper around the $content.
   *   Defaults to 'region.item'.
   *
   * @return \Villain\Regions\RegionManager
   *   $this is returned. Useful for chaining.
   */
  public function addToRegion($regionName, $name, $content, $weight = 0, $themeWrapper = 'region.item') {
    if (!array_key_exists($regionName, $this->regions)) {
      $this->startRegion($regionName);
    }
    $item = new RegionItem($name, $this->regions[$regionName], $content, $weight, $themeWrapper);
    $this->regions[$regionName]->withItem($item);
    return $this;
  }

  /**
   * Start a new region.
   *
   * @param string $name
   *   The name of the region.
   * @param sting $themeWrapper
   *   The name of teh theme callback for the wrapper around the region content.
   *   Defaults to 'region.wrapper'.
   *
   * @return \Villain\Regions\RegionManager
   *   $this is returned. Useful for chaining.
   */
  public function startRegion($name, $themeWrapper = 'region.wrapper') {
    $region = new Region($name, $themeWrapper);
    $this->regions[$name] = $region;
    return $this;
  }

  /**
   * Remove an item from a region.
   *
   * @param string $regionName
   *   The name of the region to remove an item from.
   * @param string $name
   *   The name of the item to remove from the region.
   *
   * @return \Villain\Regions\RegionManager
   *   $this is returned. Useful for chaining.
   */
  public function removeFromRegion($regionName, $name) {
    $this->regions[$regionName]->removeItem($name);
    return $this;
  }

  /**
   * Remove a region all together.
   *
   * @param string $name
   *   The name of the region to remove.
   *
   * @return \Villain\Regions\RegionManager
   *   $this is returned. Useful for chaining.
   */
  public function removeRegion($name) {
    unset($this->regions[$name]);
    return $this;
  }

  /**
   * Get an item from a region.
   *
   * @param string $region
   *   The name of the region to get the item from.
   * @param string $name
   *   The name of the item within the region to get.
   *
   * @return \Villain\Regions\RegionItem
   *   The reguested item.
   */
  public function getItemFromRegion($region, $name) {
    return $this->regions[$region]->getItem($name);
  }

  /**
   * Add a Region object to the list of regions being used.
   *
   * @param \Villain\Regions\Region $region
   *   The region object to use.
   *
   * @return \Villain\Regions\RegionManager
   *   $this is returned. Useful for chaining.
   */
  public function useRegion(Region $region) {
    $this->regions[$region->getName()] = $region;
    return $this;
  }

  /**
   * Get the names of the active regions.
   *
   * @return array
   *   The names of the regions.
   */
  public function getRegionNames() {
    return array_keys($this->regions);
  }

  /**
   * Get a \Villain\Regions\Region object.
   *
   * @param string $name
   *   The name of the region to get.
   *
   * @return \Villain\Regions\Region
   *   The region with the name $name.
   */
  public function getRegion($name) {
    return $this->regions[$name];
  }

  /**
   * Get the number of regions.
   *
   * @return int
   *   The number of regions.
   */
  public function count() {
    return count($this->regions);
  }

  /**
   * @see \Iterator::current()
   */
  public function current() {
    return current($this->regions);
  }

  /**
   * @see \Iterator::key()
   */
  public function key() {
    return key($this->regions);
  }

  /**
   * @see \Iterator::next()
   */
  public function next() {
    next($this->regions);
  }

  /**
   * @see \Iterator::rewind()
   */
  public function rewind() {
    reset($this->regions);
  }

  /**
   * @see \Iterator::valid()
   */
  public function valid() {
    $current_position = key($this->regions);
    return isset($this->regions[$current_position]);
  }
}