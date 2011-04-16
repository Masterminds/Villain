<?php
/** @file
 *
 * AbstractUserCommand is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace Villain\User;

/**
 * Provide basic functionality for user commands.
 *
 * @author Matt Butcher
 */
abstract class AbstractUserCommand extends \BaseFortissimoCommand {

  /**
   * Get the MongoDB collection that has the users in it.
   *
   * @return MongoCollection
   *  The MongoCollection that contains user accounts.
   */
  public function getUsersCollection() {
    
    // If no datasource is set, ds() will return the default datasource.
    $ds = $this->param('datasource');
    if (empty($ds)) {
      $mongo = $this->context->ds()->get();
    }
    else {
      $mongo = $this->context->ds($ds)->get();
    }

    $users = $mongo->useCollection($this->param('collection'));
    
    return $users;
  }
  
}

