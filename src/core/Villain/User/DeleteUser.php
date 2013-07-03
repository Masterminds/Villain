<?php
/** @file
 *
 * DeleteUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace Villain\User;

/**
 * Delete a given user.
 *
 * This deletes a user by username.
 *
 * Params:
 * - username
 * - datasource
 * - collection
 *
 * Events
 * - preDelete
 * - onDelete
 * - onFailedDelete
 *
 * @author Matt Butcher
 */
class DeleteUser extends \Villain\Content\DeleteContent {

  public function expects() {
    return $this
      ->description('Deletes the user with the given username.')
      ->usesParam('username', 'User name of user to delete.')
      ->withFilter('string')
      ->whichIsRequired()
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get. If not set, the default datasource will be used.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault(self::DEFAULT_USER_COLLECTION) // FIXME?
      
      ->declaresEvent('preDelete', 'Fired before delete event.')
      ->declaresEvent('onDelete', 'Fired after delete event ONLY IF the delete succeeded.')
      ->declaresEvent('onFailedDelete', 'Fired after delete event ONLY IF the delete failed.')
      
      ->andReturns('Boolean indicating success or failure');
    ;
  }

  public function doCommand() {
    $username = $this->param('username');
    
    $query = array('username' => $username);
    $collection = $this->getCollection();
    
    return $this->doDelete($query, $collection);
  }
}

