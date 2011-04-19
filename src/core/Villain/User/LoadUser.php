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
      
      // Events.
      ->declaresEvent('preLoad', 'Before user is loaded, this event is fired. It is given an object with the $username attribute set.')
      ->declaresEvent('onLoad', 'After user is loaded, this event is fired. It is given the BaseUser object')
      ->declaresEvent('onNotFound', 'If no such user is found, this event is fired. It is given an object with the $username set.')
      
      
      ->andReturns('A user object.')
    ;
  }

  public function doCommand() {
    $username = $this->param('username');
    
    $user = $this->loadUser($username);
    
    $this->prepareUser($user);
    
    return $user;
  }
  
  /**
   * Given a user name, load the corresponding user.
   *
   * Fires:
   * - preLoad: Given an object with $username. If username is changed, the changed value will be used.
   * - onLoad: Given the BaseUser object. Changes to that object will be propogated.
   * - onNotFound: Given an object with $username.
   *
   * @param string $username
   *  The name of the user to find.
   * @return BaseUser
   *  A BaseUser with the user's data.
   */
  protected function loadUser($username) {
    
    // Fire preLoad and give it a chance to modify the username.
    $data = new stdClass();
    $data->username = $username;
    $this->fireEvent('preLoad', $data);
    $username = $data->username;
    
    $users = $this->getUsersCollection();
    
    $userData = $users->findOne(array('username' => $username));
    
    if (empty($userData)) {
      $this->fireEvent('onNotFound', $data);
      return;
    }
    
    $user = BaseUser::newFromArray($userData);
    $this->fireEvent('onLoad', $user);
    return $user;
  }
  
  /**
   * Prepare the user object.
   *
   * This is called after the 'onLoad' event, and can be used to alter the user. Note that since
   * the passed-in value is an object, changes are visible outside of this method.
   *
   * @param BaseUser $user
   *  The user as a BaseUser object.
   */
  protected function prepareUser($user) {
    return;
  }
}

