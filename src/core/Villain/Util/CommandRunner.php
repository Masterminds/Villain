<?php
/** @file
 *
 * The command runner provides a simple way to execute one or more commands in
 * isolation of the rest of Fortissimo.
 *
 * This is intended to be a tool used for unit testing, not a general-purpose
 * command execution framework. Best practices dictate that commands that are
 * part of a request should be executed by Fortissimo.
 *
 * Created by Matt Butcher on 2011-07-07.
 */

namespace Villain\Util;

/**
 * Execute a command.
 *
 * The command runner executes a command in isolation from the rest of the
 * system. It is intended to be used for automated testing (like unit testing)
 * as a way of verifying that a command in isolation is behaving as expected.
 *
 * Best practices dictate that normal execution of commands should be done
 * within the traditional Fortissimo chain-of-command structure. This provides
 * the maximum level of transparency.
 *
 * Example usage:
 * @code
 * <?php
 * use Villain\Util\CommandRunner;
 * class TestCommandRunner extends PHPUnit_Framework_TestCase {
 *
 *   public function testRun() {
 *     // Create a new command runner.
 *     $runner = new CommandRunner();
 *
 *     // Execute a particular command, passing in the param name: HELLO.
 *     $ret = $runner->run('StubCommand', array('name' => 'HELLO'));
 *
 *     // Check to see if the value was returned (assumes StubCommand extends BaseFortissimoCommand).
 *     $this->assertEquals('HELLO', $ret, 'Check return value of a base command.');
 *
 *     // Check to make sure that the key is in the context, too.
 *     $this->assertEquals('HELLO', $runner->context()->get($runner->commandName), 'Verify that context was set.');
 *   }
 * }
 * ?>
 * @endcode
 */
class CommandRunner {
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
  public function run($commandClass, array $params = array(), $initialContext = NULL) {
    
    
    $this->cxt = new \FortissimoExecutionContext(
      $initialContext, 
      $this->logger, 
      $this->datasource, 
      $this->cache, 
      $this->mapper
    );
    
    set_error_handler(array('\FortissimoErrorException', 'initializeFromError'), \Fortissimo::ERROR_TO_EXCEPTION);
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
    return $this->cxt;
  }
  
}