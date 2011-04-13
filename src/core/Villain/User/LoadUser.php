<?php
/** @file
 *
 * LoadUser is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace \Villain\User;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class LoadUser extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Load a user')
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
      
      ->andReturns('A user object.')
    ;
  }

  public function doCommand() {
    
    $mongo = $this->context->ds($this->param('datasource'))->get();
    $users = $mongo->useCollection($this->param('collection'));
    
    $userData = $users->findOne(array('username' => $this->param('username')));
    
    $user = BaseUser::newFromArray($userData);
    
    return $user;
  }
}

