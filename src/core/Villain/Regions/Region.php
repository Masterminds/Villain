<?php
/** @file
 *
 * Defines the class Region.
 *
 * A region is a collection for the content in a region. Each piece of content in
 * a region is stored in a \Villain\Regions\RegionItem.
 *
 * Created by Matt Farina on 2011-07-19.
 */
namespace Villain\Regions;
/**
 * A region.
 *
 * Regions are typically created by the \Villain\Regions\RegionManager and
 * stored there.
 */
class Region implements \Countable, \Iterator {

  protected $items = array();
  protected $name = null;
  protected $themeWrapper = 'region.wrapper';

  /**
   * Construct a new Region.
   *
   * @param string $name
   *   The human readable name for the region.
   * @param string $themeWrapper
   *   The theme callback name for the region wrapper.
   */
  public function __construct($name, $themeWrapper = 'region.wrapper') {
    $this->name = $name;
    $this->themeWrapper = $themeWrapper;
  }

  /**
   * Get the number of items in the region.
   *
   * @return int
   *   The number of items in the region.
   */
  public function count() {
    return count($this->items);
  }

  /**
   * Turn the region into a string for display.
   */
  public function __toString() {
    // Sort the RegionItems by weight.
    uasort($this->items, array($this, 'sortItems'));

    // Itterate over the items to produce the content.
    $content = '';
    foreach ($this as $item) {
      $content .= $item;
    }

    return \Theme::render($this->themeWrapper, $content);
  }

  /**
   * Sort the items in the Region by weight.
   */
  protected function sortItems($a, $b) {
    $aWeight = $a->getWeight();
    $bWeight = $b->getWeight();
    if ($aWeight == $bWeight) {
      return 0;
    }
    return ($aWeight < $bWeight) ? -1 : 1;
  }

  /**
   * Add an item to a region.
   *
   * @param \Villain\Regions\RegionItem $item
   *   The RegionItem to add to this Region.
   *
   * @return \Villain\Regions\Region
   *   The current region $this. Useful for chaining.
   */
  public function withItem(RegionItem $item) {
    $this->items[$item->getName()] = $item;
    return $this;
  }

  /**
   * Remove an item from this Region.
   *
   * @param string $name
   *   The name of an item to remove from a region.
   *
   * @return \Villain\Regions\Region
   *   The current region $this. Useful for chaining.
   */
  public function removeItem($name) {
    $this->items[$name]->whoseParentIs(null);
    unset($this->items[$name]);
    return $this;
  }

  /**
   * Set the name of the region.
   *
   * @param string $name
   *   The name for the region.
   *
   * @return \Villain\Regions\Region
   *   Returns $this. Useful when used in chaining.
   */
  public function whoseNameIs($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get the name for the current region.
   *
   * @return string
   *   The human readable name for the current region.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get the names names of all the items in the region.
   *
   * @return array
   *   An array of the names of the items in this region.
   */
  public function getItemNames() {
    return array_keys($this->items);
  }

  /**
   * Get a RegionItem.
   *
   * @param string $name
   *   The human readable name for the item.
   *
   * @return \Villain\Regions\RegionItem
   *   The item with the name @name.
   */
  public function getItem($name) {
    return $this->items[$name];
  }

  /**
   * @see \Iterator::current()
   */
  public function current() {
    return current($this->items);
  }

  /**
   * @see \Iterator::key()
   */
  public function key() {
    return key($this->items);
  }

  /**
   * @see \Iterator::next()
   */
  public function next() {
    next($this->items);
  }

  /**
   * @see \Iterator::rewind()
   */
  public function rewind() {
    reset($this->items);
  }

  /**
   * @see \Iterator::valid()
   */
  public function valid() {
    $current_position = key($this->items);
    return isset($this->items[$current_position]);
  }
}