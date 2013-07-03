<?php
/** @file
 *
 * Initialize the Regions System.
 *
 * Created by Matt Farina on 2011-07-19.
 */
namespace Villain\Regions;
/**
 * Initialize Regions.
 *
 * @todo Add lots of documentation on Regions.
 */
class InitializeRegions extends \BaseFortissimoCommand {
  public function expects() {
    return $this
      ->description('Initialize the region system.')
      ->andReturns('A RegionManager instance.')
    ;
  }

  public function doCommand() {
    $manager = new RegionManager();

    return $manager;
  }
}