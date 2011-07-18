<?php
/** @file
 *
 * The command runner provides a simple way to execute one or more commands in
 * isolation of the rest of Fortissimo.
 *
 * Created by Matt Butcher on 2011-07-07.
 */

/**
 * Execute a command for the purpose of unit testing.
 */
class VillainCommandRunner {
  /**
   * The name of the command. Every command has a name and a class name.
   */
  public $commandName = 'TEST';
  /**
   * A boolean indicating whether the command should be using the cache.
   */
  public $commandIsCaching = FALSE;
  /**
   * A FortissimoLoggerManager.
   *
   * Unless explicitly set, it will be NULL.
   */
  public $logger = NULL;
  /**
   * A FortissimoCacheManager.
   *
   * Unless explicitly set, it will be NULL.
   */
  public $cache = NULL;
  /**
   * A FortissimoDatasourceManager.
   *
   * Unless explicitly set, it will be NULL.
   */
  public $datasource = NULL;
  /**
   * A FortissimoRequestMapper.
   *
   * Unless explicitly set, it will be NULL. Nothing is looked up
   * before it is processed, but you can use this to mock a request mapper.
   */
  public $mapper = NULL;
  
  /**
   * The FortissimoExecutionContext for this execution of the command.
   *
   * This is volatile, and will be re-created with each call to run().
   */
  public $cxt = NULL;
  
  /**
   * Construct a new VillainCommandRunner.
   */
  public function __construct() {

  }
  
  /**
   * Run a command and return the resulting context.
   *
   * Given a class name and the required parameters, execute a command. An optional
   * third param allows you to pass in an intiailized context. Note, however, that the
   * managers and mappers are NOT passed through the context. To set the managers, you should
   * set $logger, $cache, $datasource, and $mapper directly.
   *
   * Note that if you want to test caching, you should set $commandIsCaching to TRUE.
   *
   * The name of the command that is being executed is set to $commandName.
   *
   * The run() method may be called multiple times. Each time, a new context will be created
   * using the initial context.
   *
   * @param string $commandClass
   *  The name of the class that should be instantiated and executed.
   * @param array $params
   *  An associative array of parameters to pass into the command at execution time.
   * @param mixed $initialContext
   *  The initial context. This can be either a FortissimoExecutionContext or an associative
   *  array. Managers and mappers are not retrieved from this context, and must be explicitly set.
   * @return mixed
   *  The value that this command inserted into the context (assuming it does so in the style of
   *  a BaseFortissimoCommand) or NULL. If your command does something more sophisticated with 
   *  the context, you can use context() to retrieve the context.
   *
   */
  public function run($commandClass, array $params = array(), $initalContext = NULL) {
    
    
    $this->cxt = new FortissimoExecutionContext(
      $intialContext, 
      $this->logger, 
      $this->datasource, 
      $this->cache, 
      $this->mapper
    );
    
    set_error_handler(array('FortissimoErrorException', 'initializeFromError'), Fortissimo::ERROR_TO_EXCEPTION);
    $cmd = new $commandClass($this->commandName, $this->commandIsCaching);
    $cmd->execute($params, $this->cxt);
    restore_error_handler();
    
    $result = $this->cxt->get($this->commandName);
    
    return $result;
  }
  
  /**
   * Get the resulting context.
   *
   * After a call to run(), the context will be altered. This allows you to retrieve the context.
   *
   * @return FortissimoExecutionContext
   */
  public function context() {
    return $cxt;
  }
  
}