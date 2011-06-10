<?php

require_once 'src/Fortissimo.php';
require_once 'VillainTestCase.php';

/**
 * Harness methods for testing specific parts of Fortissimo.
 *
 * This can be used for executing commands and then extracting data
 * from outside of Fortissimo's core.
 */
class VillainTestHarness extends Fortissimo {
  
  public function __construct($file = NULL) {
    if (isset($file)) {
      Config::initialize();
    }
    parent::__construct($file);
  }
  
  /**
   * Check whether a request exists.
   */
  public function hasRequest($requestName) {
    
    $r = $this->requestMapper->uriToRequest($requestName);
    return $this->commandConfig->hasRequest($r);
    
  }
  
  /**
   * Access mock of input sources.
   *
   * Use this to simulate GET/POST/ARGV/SESSION, etc.
   */
  public $pSources = array(
    'get' => array(),
    'post' => array(),
    'cookie' => array(),
    'session' => array(),
    'env' => array(),
    'server' => array(),
    'argv' => array(),
  );
  
  /**
   * Push an exception into the system as if it were real.
   */
  public function logException($e = NULL) {
    if (empty($e)) {
      $e = new Exception('Dummy exception');
    }
    $this->logManager->log($e, 'Exception');
  }
  
  /**
   * Fetch the context.
   */
  public function getContext() {
    return $this->cxt;
  }
  
  public function fetchParam($param) {
    return $this->fetchParameterFromSource($param);
  }
  
  public function setParams($params = array(), $source = 'get') {
    $this->pSources[$source] = $params;
  } 
   
  protected function fetchParameterFromSource($from) {
    list($proto, $paramName) = explode(':', $from, 2);
    $proto = strtolower($proto);
    switch ($proto) {
      case 'g':
      case 'get':
        return isset($this->pSources['get'][$paramName]) ? $this->pSources['get'][$paramName] : NULL;
      case 'p':
      case 'post':
        return $this->pSources['post'][$paramName];
      case 'c':
      case 'cookie':
      case 'cookies':
        return $this->pSources['cookie'][$paramName];
      case 's':
      case 'session':
        return $this->pSources['session'][$paramName];
      case 'x':
      case 'cmd':
      case 'context':
        return $this->cxt->get($paramName);
      case 'e':
      case 'env':
      case 'environment':
        return $this->pSources['env'][$paramName];
      case 'server':
        return $this->pSources['server'][$paramName];
      case 'r':
      case 'request':
        return isset($this->pSources['get'][$paramName]) ? $this->pSources['get'][$paramName] : (isset($this->pSources['post'][$paramName]) ? $this->pSources['post'][$paramName] : NULL);
      case 'a':
      case 'arg':
      case 'argv':
        return $argv[(int)$paramName];
    }
  }
  
}