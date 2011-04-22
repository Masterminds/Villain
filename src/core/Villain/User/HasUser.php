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
      
      ->usesParam('collection', 'The MongoDB collection to use for accessing users')
      ->withFilter('string')
      ->whichHasDefault('users')
      
      ->declaresEvent('preSearch', 'Event fired before looking to see if user exists.')
      
      ->andReturns('TRUE if the user exists, FALSE otherwise.')
    ;
  }

  public function doCommand() {
    $users = $this->getUsersCollection();
    
    $data = new stdClass();
    $data->username = $username;
    $data->commandName = $this->name;
    $this->fireEvent('preSearch', $data);
    $username = $data->username;
    
    
    $user = $users->findOne(array('username' => $this->param('username')), array('username'));
    
    return !empty($user);
  }
}

