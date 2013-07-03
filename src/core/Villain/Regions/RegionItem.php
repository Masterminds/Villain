<?php
/** @file
 *
 * Defines the class RegionItem.
 *
 * A region item is an item within a region. Each individual piece of content
 * or block is a region item.
 *
 * Created by Matt Farina on 2011-07-18.
 */
namespace Villain\Regions;
/**
 * A region item.
 *
 * Region items are typically created by the \Villain\Regions\RegionManager and
 * live in a single \Villain\Regions\Region container.
 */
class RegionItem {

  protected $content = null;
  protected $weight = 0;
  protected $parent = null;
  protected $name = null;
  protected $themeWraper = 'region.item';

  /**
   * Create a new RegionItem. This is normally done by \Villain\Regions\RegionManager.
   *
   * @param string $name
   *   A human readable name for the item.
   * @param Region $parent
   *   The parent region this item is apart of.
   * @param string $content
   *   The content for this item.
   * @param float $weight
   *   Items in a region are displayed in weighted order. This is the weight for
   *   this item.
   * @param string $themeWrapper
   *   The name of the wrapper theme function for this region item. Defaults to
   *   'region.item'.
   */
  public function __construct($name, Region $parent, $content, $weight = 0, $themeWrapper = 'region.item') {
    $this->name = $name;
    $this->parent = $parent;
    $this->content = $content;
    $this->weight = $weight;
    $this->themeWrapper = $themeWrapper;
  }

  /**
   * Set the weight for this Region Item.
   *
   * @param float $weight
   *   Items in a region are displayed in weighted order. This is the weight for
   *   this item.
   *
   * @return \Villain\Regions\RegionItem
   *   Returns $this. Useful when used in chaining.
   */
  public function whoseWeightIs($weight) {
    $this->weight = $weight;
    return $this;
  }

  /**
   * Get the current weight for this Region Item.
   *
   * @return float
   *   The current weight for this region item.
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * Set the content for this Region Item.
   *
   * @param string $content
   *   The content for this item.
   *
   * @return \Villain\Regions\RegionItem
   *   Returns $this. Useful when used in chaining.
   */
  public function whoseContentIs($content) {
    $this->content = $content;
    return $this;
  }

  /**
   * Get the current content for this Region Item.
   *
   * @return string
   *   The current content for this item.
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * Set the parent \Villain\Regions\Region for this item.
   *
   * @param \Villain\Regions\Region $parent
   *   The parent region this item is attached to.
   *
   * @return \Villain\Regions\RegionItem
   *   Returns $this. Useful when used in chaining.
   */
  public function whoseParentIs($parent) {
    $this->parent = $parent;
    return $this;
  }

  /**
   * Get the current \Villain\Regions\Region this item is attached to.
   *
   * @return \Villain\Regions\Region
   *   The current region this item is attached to.
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * Set the name of the item.
   *
   * @param string $name
   *   The name for the item.
   *
   * @return \Villain\Regions\RegionItem
   *   Returns $this. Useful when used in chaining.
   */
  public function whoseNameIs($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get the name for the current item.
   *
   * @return string
   *   The human readable name for the current item.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Turn the item into a string for display.
   */
  public function __toString() {
    return \Theme::render($this->themeWrapper, $this->content);
  }

  /**
   * Remove this region item from it's parent.
   *
   * @return \Villain\Regions\RegionItem
   *   Returns $this. Useful when used in chaining.
   */
  public function removeFromParent() {
    $this->parent->removeItem($this->name);
    return $this;
  }
}