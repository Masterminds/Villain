<?php
/** @file
 *
 * RebootForInstallation is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-07-06.
 */
namespace Villain\Installer;
/**
 * Do any necessary configuration to restart areas of the installer.
 *
 * @author Matt Butcher
 */
class RebootForInstallation extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Rebuild the Config object.')
      ->usesParam('server', 'The server URI, as defined in the MongoDB documentation.')
        ->whichHasDefault('mongodb://localhost:27017')
      ->usesParam('user', 'The username of the MongoDB user. Optional.')
      ->usesParam('password', 'The password of the MongoDB user. Optional.')
      ->usesParam('database', 'The database Villain will use.')
        ->withFilter('string')
        ->whichHasDefault('villain')
       ->usesParam('proxyDatasourceName')->whichHasDefault('db')
      ->andReturns('Nothing. However, the system is re-bootstrapped with new commands.')
    ;
  }

  public function doCommand() {
    
    $ds_params = array(
      'defaultDB' => $this->param('database'),
      'server'    => $this->param('server'),
      'username'  => $this->param('user'),
      'password'  => $this->param('password'),
    );
    
    $dsn = $this->param('proxyDatasourceName');
    
    $ds = $this->context->ds($dsn);
    
    if ($ds instanceof \Villain\Util\ProxyDatasource) {
      $mongo = $ds->createDatasource('\FortissimoMongoDatsource', $ds_params);
      $ds->setInnerDatasource($mongo);
    }
    else {
      throw new \Villain\Exception('No proxy datasource found.');
    }
    
    // This will throw an exception if it fails.
    $ds->get()->listCollections();
    
  }
}

