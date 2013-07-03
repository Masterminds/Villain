<?php
/** @file
 *
 * Install is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-25.
 */

namespace BasicBlog;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class Install extends \BaseFortissimoCommand {

  private $blogName = 'BasicBlog';
  private $blogLabel = 'Blog';
  private $blogEntryName = 'BasicBlogEntry';
  private $blogEntryLabel = 'Blog Entry';

  public function expects() {
    return $this
      ->description('Installs the Basic Blog bundle.')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('Nothing.')
    ;
  }

  public function doCommand() {
  }
  

}

