<?php
/** @file
 *
 * LoadUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace Villain\User;

/**
 * Given a username, load a user.
 *
 * @author Matt Butcher
 */
class LoadUser extends AbstractUserCommand {

  public function expects() {
    return $this
      ->description('Load a user')
      ->usesParam('username', 'The username')
      ->withFilter('string')
      ->whichIsRequired()
      //->whichHasDefault('some value')
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      ->andReturns('A user object.')
    ;
  }

  public function doCommand() {
    
    $users = $this->getUsersCollection();
    
    $userData = $users->findOne(array('username' => $this->param('username')));
    
    $user = BaseUser::newFromArray($userData);
    
    return $user;
  }
}

