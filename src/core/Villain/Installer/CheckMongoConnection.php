<?php
/** @file
 *
 * Check whether we can connect to MongoDB.
 *
 * Created by Matt Butcher on 2011-07-20.
 */
namespace Villain\Installer;
/**
 * Test whether MongoDB connection info is correct.
 *
 * This tests a connection to MongoDB.
 *
 * @author Matt Butcher
 */
class CheckMongoConnection extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Test a connection to MongoDB.')
      ->usesParam('server', 'The mongo URL to the server (mongodb://localhost:27017). You can supply a comma-separated list')
      ->whichIsRequired()
      ->usesParam('database', 'The name of the database to connect to.')
      ->usesParam('username', 'The username to connect')
      ->usesParam('password', 'The password for connecting')
      ->andReturns('Boolean TRUE if this succeeds. On fail, it throws an interrupting exception.')
    ;
  }

  public function doCommand() {

    $server = $this->param('server');
    $dbname = $this->param('database', NULL);
    $username = $this->param('username', NULL);
    $password = $this->param('password', NULL);
    
    $options = array(
      'connect' => TRUE,
      'timeout' => 5, // Assumption: We're just testing the connection.
    );
    
    if (!empty($username)) {
      $options['username'] = $username;
      if (!empty($password)) {
        $options['password'] = $password;
      }
    }
    
    try {
      $db = new \Mongo($server, $options);
    } catch (\Exception $e) {
      throw new \Villain\InterruptException($e);
    }

    return TRUE;
  }
}

