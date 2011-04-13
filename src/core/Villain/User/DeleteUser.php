<?php
/** @file
 *
 * DeleteUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class DeleteUser extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Deletes the user with the given username.')
      ->usesParam('username', 'User name of user to delete.')
      ->withFilter('string')
      ->whichIsRequired()
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get.')
      ->withFilter('string')
      ->whichHasDefault('villain')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      ->andReturns('Boolean indicating success or failure');
    ;
  }

  public function doCommand() {
    $mongo = $this->context->ds($this->param('datasource'))->get();
    $users = $mongo->useCollection($this->param('collection'));
    
    return $users->remove(array('username' => $this->param('username')));
  }
}

