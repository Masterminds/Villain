<?php
/** @file
 *
 * InstallVillain is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-29.
 */
namespace Villain\Installer;
/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class InstallVillain extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Installs Villain')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('')
    ;
  }

  public function doCommand() {

    $this->installFilters();

  }
  
  public function installFilters() {
    
    $filterManager = $this->context('filters');
    
    $safeHTML = array(
      '\Villain\Filters\WhitelistTagFilter' => NULL,
    );
    $safeASCII = array(
      '\Villain\Filters\PlaintextFilter' => NULL,
    );
    $plain = array(
      '\Villain\Filters\EscapeMarkupFilter' => NULL,
    );
    
    $filterManager->addChain('safeHTML', $safeHTML);
    $filterManager->addChain('safeASCII', $safeASCII);
    $filterManager->addChain('plain', $plain);
  }
}

