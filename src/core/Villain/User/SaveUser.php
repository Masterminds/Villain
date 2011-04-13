<?php
/** @file
 *
 * SaveUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace \Villain\User;

/**
 * Save a given user object.
 *
 * Note that while user objects can have all manner of arbitrary dangly apparatus, a
 * user must have a username. It is recommended that you set it like this:
 *
 * @code
 * <?php
 * $user = new BaseUser();
 * $user->setUsername('matt');
 * ?>
 * @endcode
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
    
    if (empty($user->getUsername)) {
      throw new \Villain\Exception('User must have a username.');
    }
    
    $users = $this->getUsersCollection();
    
    $users->save($user->toArray());

  }
  
}

