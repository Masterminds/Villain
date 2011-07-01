<?php
/** @file
 *
 * AbstractContentCommand is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-19.
 */

/**
 * This package defines the generic content system for Villain.
 *
 * "Content" is a generic abstraction in Villain that describes any piece of 
 * data that is considered content. The Content system is built atop the Villain Storage system 
 * (See Villain::Storage::Storable), though not all Storable objects are also content.
 *
 * Content types are defined using the Villain Content Type system (See 
 * Villain::Content::Type::TypeDefinition). 
 *
 */
namespace Villain\Content;

/**
 * Base content command.
 *
 * This class contains utility methods common to all content commands.
 *
 * @author Matt Butcher
 */
abstract class AbstractContentCommand extends \BaseFortissimoCommand {

  const DEFAULT_COLLECTION = 'content';
  const DEFAULT_USER_COLLECTION = 'users';

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
  protected function getCollection() {
    
    // If no datasource is set, ds() will return the default datasource.
    $ds = $this->param('datasource');
    if (empty($ds)) {
      $mongo = $this->context->ds()->get();
    }
    else {
      $mongo = $this->context->ds($ds)->get();
    }

    $collection = $mongo->selectCollection($this->param('collection', self::DEFAULT_COLLECTION));
    
    return $collection;
  }
  
  /**
   * Generate a base event object to be passed into events.
   */
  protected function baseEvent() {
    $e = new \stdClass();
    $e->context = $this->context;
    $e->commandName = $this->name;
  }

}

