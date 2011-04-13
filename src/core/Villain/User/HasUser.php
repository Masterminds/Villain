<?php
/** @file
 *
 * HasUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace \Villain\User;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class HasUser extends AbstractUserCommand {

  public function expects() {
    return $this
      ->description('Check to see if a user exists with this account.')
      ->usesParam('username', 'The username')
      ->withFilter('string')
      ->whichIsRequired()
      //->whichHasDefault('some value')
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get.')
      ->withFilter('string')
      ->whichHasDefault('villain')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      ->andReturns('TRUE if the user exists, FALSE otherwise.')
    ;
  }

  public function doCommand() {
    $users = $this->getUsersCollection();
    
    $user = $users->findOne(array('username' => $this->param('username')), array('username'));
    
    return !empty($user);
  }
}

