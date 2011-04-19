<?php
/** @file
 *
 * LoadContent is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-19.
 */

namespace Villain\Content;

/**
 * Load a piece of content from the MongoDB.
 *
 * This retrieves a piece of content and returns it as a Storable.
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
    
    $result = $this->loadById($id);
    
    $this->prepareContent($result);
    
    return $result;
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
   * @return StorableObject
   *  The content as a StorableObject.
   */
  protected function loadById($id) {
    
    // Make sure $id gets transformed into a MongoID.
    if (!($id instanceof MongoID)) {
      $id = new MongoId((string)$id);
    }
    
    // Fire the preload event.
    $preloadData = new stdClass;
    $preloadData->id = $id;
    $this->fireEvent('preLoad', $preloadData);
    
    // Search for the document.
    $collection = $this->getContentCollection();
    $search = array('_id' => $id);
    $result = $collection->findOne($search);
    
    // If not found, fire the appropriate event and return.
    if (empty($result)) {
      $this->fireEvent('onNotFound', $preloadData);
      return;
    }
    
    // If found, make it Storable and fire onLoad event.
    $object = StorableObject::newFromArray($result);
    $this->fireEvent('onLoad', $object);    
    return $object;
  }
  
  /**
   * Do any preparation of the storable object.
   *
   * Subclasses may extend this to modify the object before it is inserted into
   * the context.
   *
   * Since this is an object, any modifications made to $storable will be accessible
   * outside of this method.
   *
   * @param StorableObject $storable
   *  The document. It should always have an _id, which will be an instance of MongoId.
   */
  protected function prepareContent($storable) {
    return;
  }
}

