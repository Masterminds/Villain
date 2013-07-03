<?php
/** @file
 *
 * DeleteContent handles deletion of content.
 *
 * Created by Matt Butcher on 2011-04-21.
 */

namespace Villain\Content;

/**
 * Delete content from the database.
 *
 * Expects:
 *  - id
 *  - datasource
 *  - collection
 *
 * Fires:
 * - preDelete
 * - onDelete
 * - onDeleteError
 *
 * @author Matt Butcher
 */
class DeleteContent extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Deletes the content with the given ID.')
      ->usesParam('id', 'The ID of the content to delete. The ID can be a string or MongoId')
      ->whichIsRequired()
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get. If not set, the default datasource will be used.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing content')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      ->declaresEvent('preDelete', 'Fired before delete event.')
      ->declaresEvent('onDelete', 'Fired after delete event ONLY IF the delete succeeded.')
      ->declaresEvent('onDeleteError', 'Fired after delete event ONLY IF the delete failed.')
      
      ->andReturns('Boolean indicating success or failure');
    ;
  }

  public function doCommand() {
    $collection = $this->getCollection();
    $id = $this->param('id');
    
    if (is_string($id)) {
      $id = new MongoId($id);
    }
    
    $query = array(
      '_id' => $id,
    );
    
    return $this->doDelete($query, $collection);
  }
  
  /**
   * Given a query array and a collection, delete a piece of content.
   *
   * The query should be in the form of a name/value mapping of items that should be matched.
   * Anything that matches will be deleted.
   *
   * During processing, this may fire any of the following events:
   *
   * - preDelete: Prior to deletion. Given the query in $e->query.
   * - onDelete: Upon successful deletion.
   * - onDeleteError: Upon an error encountered during deletion.
   *
   * @param array $query
   *  A query to run the delete.
   * @param MongoCollection $collection
   *  The collection against which the query is run.
   * @return boolean
   *  TRUE if successful, FALSE otherwise.
   */
  public function doDelete(&$query, $collection) {
    
    $e = $this->createEvent();
    $e->query = $query;
    
    $this->fireEvent('preDelete', $e);
    $res = $collection->remove($query);
    
    if (!$res) {
      $this->fireEvent('onDeleteError', $e);
      $this->context->log('Failed to delete.', 'error');
      return FALSE;
    }
    
    $this->fireEvent('onDelete', $e);
    return TRUE;
  }
}

