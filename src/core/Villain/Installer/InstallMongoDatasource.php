<?php
/**
 * @file
 * Installer for mongo datasources.
 */
namespace Villain\Installer;
/**
 * Install MongoDB connection information.
 * 
 * This installs MongoDB connect information into a commands.php file. It is
 * intended only for use by an installer, and it installs a default datasource.
 */
use Villain\Util\String;

class InstallMongoDatasource extends \Villain\FU\ModifyFileInPlace {

  // See BaseFortissimoCommand::expects().
  public function expects() {
    return $this
      ->description('Replaces a placeholder with a datasource definition.')
      ->usesParam('server', 'The server URI, as defined in the MongoDB documentation.')
      ->whichHasDefault('mongodb://localhost:27017')
      ->usesParam('user', 'The username of the MongoDB user. Optional.')
      ->usesParam('password', 'The password of the MongoDB user. Optional.')
      ->usesParam('database', 'The database Villain will use.')
        ->withFilter('string')
        ->whichHasDefault('villain')
      ->usesParam('commandsFile', 'The path to the commands.php file that this will rewrite.')
        ->whichHasDefault('config/commands.php')
      ->andReturns('Nothing. The file is rewritten in place.')
    ;
  }

  // See BaseFortissimoCommand::doCommand().
  public function doCommand() {
    $file = $this->param('commandsFile');
    $server = $this->param('server');
    $user = $this->param('user', NULL);
    $password = $this->param('password', NULL);
    $db = $this->param('database');
    
    $this->configString = $this->buildDBConfig($server, $db, $user, $password);
    
    $this->iterateFile($file);
  }
  
  /**
   * Replace a placeholder with a datasource declaration.
   * 
   * @param string $line
   *   The line to scan.
   * @return string
   *   The resulting configuration directive.
   */
  protected function forEachLineInFile($line) {
    return preg_replace('|^//== MongoDB Config|', $this->configString, $line);
  }
  
  /**
   * Build the DB configuration directive.
   * 
   * This builds the appropriate Config::datasource() call for the commands file.
   * The datasource will be set as the default.
   * 
   * @param string $server
   *   The server name.
   * @param string $db
   *   The name of the default DB.
   * @param string $user
   *   The user name (optional)
   * @param string $password
   *   The password.
   * @return A string of PHP code for inserting into a commands file.
   */
  protected function buildDBConfig($server, $db, $user = NULL, $password = NULL) {
    $conf = 'Config::datasource(\'db\')->whichInvokes(\'FortissimoMongoDatasource\')';
    if (!empty($user)) {
      $conf .= $this->generateUsesParam('username', $user);
      if (!empty($password)) {
        $conf .= $this->generateUsesParam('password', $password);
      }
    }
    $conf .= $this->generateUsesParam('server', $server);
    $conf .= $this->generateUsesParam('defaultDB', $db);
    $conf .= $this->generateUsesParam('isDefault', 'true');
    
    
    $conf .= ";";
    
    return $conf;
  }
  
  /**
   * Generate a Config::withParam() function as a string.
   * 
   * This creates Config::withParam()->whoseValueIs().The code is returned
   * as a string. Minimal escaping is done of $param and $value, both of 
   * which are assumed to be strings.
   * 
   * @param string $param
   * @param string $value
   */
  protected function generateUsesParam($param, $value) {
    $template = PHP_EOL . '  ->withParam(\'%s\')->whoseValueIs(\'%s\')';
    $param = addslashes($param);
    $value = addslashes($value);
    return sprintf($template, $param, $value);
  }
}