<?php
/** @file
 *
 * InstallVillain is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-29.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class InstallVillain extends BaseFortissimoCommand {

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
      '\Villain\Filters\WhitelistTagFilter' => array(),
    );
    $safeASCII = array(
      '\Villain\Filters\PlaintextFilter' => NULL,
    );
    $plain = array(
      '\Villain\Filters\EscapeMarkupFilter' => NULL,
    );
    
    $filterManager->addFilter('safeHTML', $safeHTML);
    $filterManager->addFilter('safeASCII', $safeASCII);
    $filterManager->addFilter('plain', $plain)
  }
}

