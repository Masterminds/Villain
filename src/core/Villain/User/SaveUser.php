<?php
/** @file
 *
 * SaveUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace \Villain\User;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class SaveUser extends AbstractUserCommand {

  public function expects() {
    return $this
      ->description('Saves a new user.')
      ->usesParam('user', 'A Storable object that represents a user.')
      ->whichIsRequired()
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get.')
      ->withFilter('string')
      ->whichHasDefault('villain')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      
      ->andReturns('Boolean TRUE if stored.')
    ;
  }

  public function doCommand() {
    
    $user = $this->param('user');
    if (!($user instanceof Storable)) {
      throw new \Villain\Exception('Attempted to store an entity that is not storable.');
    }
    
    $users = $this->getUsersCollection();
    
    $users->save($user->toArray());

  }
  
}

