<?php
/** @file
 *
 * HasUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace Villain\User;

/**
 * Check to see if a user record occurs for a given username.
 *
 * @author Matt Butcher
 */
class HasUser extends \Villain\Content\AbstractContentCommand {

  public function expects() {
    return $this
      ->description('Check to see if a user exists with this account.')
      ->usesParam('username', 'The username')
      ->withFilter('string')
      ->whichIsRequired()
      //->whichHasDefault('some value')
      
      ->usesParam('datasource', 'The name of the MongoDB datasource to get.')
      ->withFilter('string')
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault(self::DEFAULT_USER_COLLECTION)
      
      ->declaresEvent('preSearch', 'Event fired before looking to see if user exists.')
      
      ->andReturns('TRUE if the user exists, FALSE otherwise.')
    ;
  }

  public function doCommand() {
    $users = $this->getCollection();
    
    $data = $this->createEvent();
    $data->username =& $username;
    $this->fireEvent('preSearch', $data);
    
    $user = $users->findOne(array('username' => $username), array('username'));
    
    return !empty($user);
  }
}

