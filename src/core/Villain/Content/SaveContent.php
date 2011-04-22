<?php
/** @file
 *
 * SaveContent saves a Storable (such as a StorableObject) into a MongoDB.
 *
 * Created by Matt Butcher on 2011-04-21.
 */

namespace Villain\Content;

/**
 * Save content.
 *
 * Expects:
 * - content: A Storable
 * - datasource
 * - collection
 *
 * Fires:
 * - preSave
 * - onSave
 * - onSaveError
 *
 * @author Matt Butcher
 */
class SaveContent extends AbstractContentCommand {

  public function expects() {
    return $this
      ->description('Save a piece of content.')
      ->usesParam('content', 'A Storable.')
      ->whichIsRequired()
      // FIXME: Need a filter to verify that this is a Storable
      
      ->usesParam('datasource', 'Name of the MongoDB datasource to use. If none is specified, the default is used.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault(self::DEFAULT_COLLECTION)

      ->declaresEvent('preSave', 'Fires before the object is saved.')
      ->declaresEvent('onSave', 'Fires after the object is saved.')
      ->declaresEvent('onSaveError', 'Fires if the save failed.')
      
      ->andReturns('The stored object.')
    ;
  }

  public function doCommand() {
    
    $collection = $this->getCollection();
    $content = $this->param('content');
    
    if (!($content instanceof Storable)) {
      throw new \Villain\Exception('Content is not Storable.');
    }

    return $this->doSave($content, $collection);
  }
  
  /**
   * Save a piece of content.
   * 
   * This saves the Storable in the given MongoCollection. It fires
   * the following events:
   * - preSave: Called before the object is saved. Allows modification of the Storable
   *   as $e->data.
   * - onSave: Called immediately after the object is saved. $e->data will now have an _id
   *   attribute.
   * - onSaveError: Called only if the save failed. Typically, this only happens if the given
   *   Storable is empty.
   *
   * @param Storable $s
   *  The object to store.
   * @param MongoCollection $collection
   *  The collection to store into.
   * @return Storable
   *  The storable after saving. If it is a new object, it will have an _id attribute.
   * @todo Investigate whether MongoCollection::save() should be called in safe mode.
   */
  protected function doSave(Storable $s, $collection) {
    $e = $this->baseEvent();
    $e->data = $s;
    
    // Fire presave.
    $this->fireEvent('preSave', $e);
    
    // Save the array version of the content.
    $array = $s->toArray();
    $res = $collection->save($s);
    
    // This is unlikely to ever really happen unless we modify
    // save() to use safe mode.
    if (!$res) {
      $this->fireEvent('onSaveError', $e);
      $this->context->log('Failed to write content.', 'error');
      // XXX: Should this throw an exception?
      return FALSE;
    }
    
    // Reset the Storable. This has the advantage of retaining all of
    // the characteristics of the Storable.
    $s->fromArray($e);
    
    // Set it to exactly the thing saved. Note that save() will set
    // an _id attribute.
    $this->fireEvent('onSave', $e);
    return $s;
  }
}

