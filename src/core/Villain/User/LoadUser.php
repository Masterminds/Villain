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
class LoadUser extends \Villain\Content\AbstractContentCommand {

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
      ->whichHasDefault(self::DEFAULT_USER_COLLECTION)
      
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
    
    $user = $this->prepareUser($user);
    
    return $user;
  }
  
  /**
   * Given a user name, load the corresponding user.
   *
   * Fires:
   * - preLoad: Given an object with $username. If username is changed, the changed value will be used.
   * - onLoad: Given the BaseUser object. Changes to that object will be propagated.
   * - onNotFound: Given an object with $username.
   *
   * @param string $username
   *  The name of the user to find.
   * @param MongoCollection $collection
   *  The collection to search.
   * @return BaseUser
   *  A BaseUser with the user's data.
   */
  protected function loadUser($username, $collection) {
    
    $query = array('username' => $username);
    
    return $this->loadContent($query, $collection);
  }
  
  /**
   * Convert a storable array into a BaseUser.
   *
   * @param array $array
   *  Data returned from database.
   * @return BaseUser
   *  A BaseUser user object.
   */
  protected function createStorable($array) {
    // This gets called by loadContent.
    return BaseUser::newFromArray($array);
  }
  
  /**
   * Prepare the user object.
   *
   * This is called after the 'onLoad' event, and can be used to alter the user.
   *
   * @param BaseUser $user
   *  The user as a BaseUser object.
   */
  protected function prepareUser($user) {
    return $user;
  }
}

