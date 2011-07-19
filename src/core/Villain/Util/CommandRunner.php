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
   * third param allows you to pass in an intiailized context.
   *
   * IMPORTANT: If your command needs access to a datasource, logger, cache, or 
   * request mapper, you MUST pass in a FortissimoExecutionContext with those 
   * facilities set.
   *
   * Note that if you want to test caching, you should set $commandIsCaching to TRUE and also
   * pass in a FortissimoExecutionContext.
   *
   * The name of the command that is being executed is set to $commandName.
   *
   * The run() method may be called multiple times. The context is not preserved across calls
   * (unless you pass in the same FortissimoExecutionContext as the third param, in which case
   * your external reference will keep the context preserved).
   *
   * @param string $commandClass
   *  The name of the class that should be instantiated and executed.
   * @param array $params
   *  An associative array of parameters to pass into the command at execution time.
   * @param mixed $initialContext
   *  The initial context. This can be either a FortissimoExecutionContext or an associative
   *  array. If it is 
   * @return mixed
   *  The value that this command inserted into the context (assuming it does so in the style of
   *  a BaseFortissimoCommand) or NULL. If your command does something more sophisticated with 
   *  the context, you can use context() to retrieve the context.
   *
   */
  public function run($commandClass, array $params = array(), $initialContext = array()) {
    
    if (empty($initialContext)) {
      // New empty context.
      $this->cxt = new \FortissimoExecutionContext(array(), NULL, NULL, NULL, NULL);
    }
    elseif(is_array($initialContext)) {
      // New context seeded with the array contents.
      $this->cxt = new \FortissimoExecutionContext($initialContext, NULL, NULL, NULL, NULL);
    }
    else {
      // Existing context.
      $this->cxt = $initialContext;
    }
    
    // To match Fortissimo's behavior, we convert errors to exceptions.
    set_error_handler(array('\FortissimoErrorException', 'initializeFromError'), \Fortissimo::ERROR_TO_EXCEPTION);
    
    // Build and execute the command just like Fortissimo does.
    $cmd = new $commandClass($this->commandName, $this->commandIsCaching);
    $cmd->execute($params, $this->cxt);
    
    restore_error_handler();
    
    // A BaseFortissimoCommand puts its return value in the context, with the command 
    // name as the key. This is what we want to return.
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