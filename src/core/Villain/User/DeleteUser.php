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
class DeleteUser extends AbstractUserCommand {

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
      ->whichHasDefault('users')
      
      ->declaresEvent('preDelete', 'Fired before delete event.')
      ->declaresEvent('onDelete', 'Fired after delete event ONLY IF the delete succeeded.')
      ->declaresEvent('onFailedDelete', 'Fired after delete event ONLY IF the delete failed.')
      
      ->andReturns('Boolean indicating success or failure');
    ;
  }

  public function doCommand() {
    $username = $this->param('username');
    
    $result = $this->doDelete($username);
    
    return $result;
  }
  
  /**
   * Delete a user by username.
   *
   * Events:
   * - preDelete: Fired before delete is attempt. $data is passed with $username. If $username 
   *   is modified, then the modified username will be used. DANGER!
   * - onDelete: Fired when deletion succeeds. $data has the $username that was deleted.
   * - onFailedDelete: Fired when deletion fails (i.e. user not found). $data has the $username that
   *   was deleted.
   *
   * @param string $username
   *  The username
   * @return boolean
   *  The return value of the delete operation.
   */
  protected function doDelete($username) {
    
    $data = new stdClass();
    $data->username = $username;
    $this->fireEvent('preDelete', $data);
    $username = $data->username;
    
    $users = $this->getUsersCollection();
    $retval = $users->remove(array('username' => $username));
    
    if ($retval) {
      $this->fireEvent('onDelete', $data);
    }
    else {
      $this->fireEvent('onFailedDelete', $data);
    }
    
    return $retval;
  }
}

