<?php
/** @file
 *
 * AbstractContentCommand is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-19.
 */

namespace Villain\Content;

/**
 * Base content command.
 *
 * This class contains utility methods common to all content commands.
 *
 * @author Matt Butcher
 */
abstract class AbstractContentCommand extends BaseFortissimoCommand {

  const DEFAULT_COLLECTION = 'content';

  /**
   * Get the MongoDB collection that has the content in it.
   *
   * Uses the following local params:
   * - 'datasource'
   * - 'collection'
   *
   * @return MongoCollection
   *  The MongoCollection that contains user accounts.
   */
  public function getContentCollection() {
    
    // If no datasource is set, ds() will return the default datasource.
    $ds = $this->param('datasource');
    if (empty($ds)) {
      $mongo = $this->context->ds()->get();
    }
    else {
      $mongo = $this->context->ds($ds)->get();
    }

    $collection = $mongo->useCollection($this->param('collection', self::DEFAULT_COLLECTION));
    
    return $collection;
  }

}

