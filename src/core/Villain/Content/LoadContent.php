<?php
/** @file
 *
 * LoadContent loads a piece of content.
 *
 * Created by Matt Butcher on 2011-04-19.
 */

namespace Villain\Content;

/**
 * Load a piece of content from the MongoDB.
 *
 * This retrieves a piece of content and returns it as a Storable.
 *
 * Expects:
 * - id
 * - datasource
 * - collection
 *
 * Fires:
 * - preLoad
 * - onLoad
 * - onNotFound
 *
 * @author Matt Butcher
 */
class LoadContent extends AbstractContentCommand {

  public function expects() {
    return $this
      ->description('Load a piece of content from the repository.')
      
      ->usesParam('id', 'The ID of the content to load. Typically this is a MongoID string.')
      ->whichIsRequired()
      
      ->usesParam('datasource', 'Name of the MongoDB datasource to use. If none is specified, the default is used.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault(self::DEFAULT_COLLECTION)
      
      ->declaresEvent('preLoad', 'Immediately BEFORE content is loaded, this event is fired.')
      ->declaresEvent('onLoad', 'Immediately after a piece of content is loaded, this event is fired.')
      ->declaresEvent('onNotFound', 'If content cannot be loaded, this event is fired.')

      ->andReturns('A piece of content as a Storable.')
    ;
  }

  public function doCommand() {
    
    $id = $this->param('id');
    $collection = $this->getCollection();
    
    $result = $this->loadById($id, $collection);
    $result = $this->prepareContent($result);
    
    return $result;
  }
  
  protected function loadContent(&$query, $collection) {
    // Fire the preload event.
    $e = $this->baseEvent();
    $e->query = $query;
    $this->fireEvent('preLoad', $e);
    
    // Search for the document.
    $result = $collection->findOne($query);
    
    // If not found, fire the appropriate event and return.
    if (empty($result)) {
      $this->fireEvent('onNotFound', $e);
      return;
    }
    
    // If found, make it Storable and fire onLoad event.
    $object = $this->createStorable($result);
    $e->data = $object;
    $this->fireEvent('onLoad', $e);    
    return $object;
  }
  
  /**
   * Load a document from the MongoDB.
   *
   * This loads a document from the repository.
   *
   * Fires the preLoad, onNotFound, and onLoad events as necessary.
   *
   * @param mixed $id
   *   A string or a MongoId object that identifies the desired piece of content.
   * @param MongoCollection $collection
   *   A collection to search.
   * @return StorableObject
   *  The content as a StorableObject.
   */
  protected function loadById($id, $collection) {
    
    // Make sure $id gets transformed into a MongoID.
    if (!($id instanceof MongoID)) {
      $id = new MongoId((string)$id);
    }
    
    // Search for the document.
    $search = array('_id' => $id);
    return $this->loadContent($query, $collection);
  }
  
  /**
   * Given an array, return a suitable Storable.
   *
   * The default instance returns a StorableObject, but subclasses may choose another
   * Storable or wrap with a StorableObjectDecorator.
   *
   * @param array $array
   *  An associative array.
   * @return Storable
   *  A storable representing the array's contents.
   */
  protected function createStorable($array) {
    return StorableObject::newFromArray($array);
  }
  
  /**
   * Do any preparation of the storable object.
   *
   * Subclasses may extend this to modify the object before it is inserted into
   * the context. This is called *after* the `onLoad` event is fired, which means
   * that modifications made by event handlers will be accessible in this method.
   *
   * This method returns a Storable which may be the same as the one passed in. However,
   * it is possible to return a clone, another object, or a decorator. For that reason, 
   * it is best to use the returned Storable and not assume that the passed in Storable
   * was modified.
   *
   * @param StorableObject $storable
   *  The document. It should always have an _id, which will be an instance of MongoId.
   * @return Storable
   *  The prepared storable.
   */
  protected function prepareContent($storable) {
    return $storable;
  }
}

