<?php
/** @file
 *
 * AbstractUserCommand is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-04-13.
 */

namespace \Villain\User;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class AbstractUserCommand extends \BaseFortissimoCommand {

  /**
   * Get the MongoDB collection that has the users in it.
   */
  public function getUsersCollection() {
    $mongo = $this->context->ds($this->param('datasource'))->get();
    $users = $mongo->useCollection($this->param('collection'));
    
    return $users;
  }
  
}

