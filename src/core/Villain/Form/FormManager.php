<?php
/** @file
 *
 * Defines the class FormManager.
 *
 * The form manager provides a simple interface for working with Villain for system.
 *
 * Created by Matt Farina on 2011-07-09.
 */
namespace Villain\Form;
/**
 * The form manager.
 *
 * This class provides easy access to the form system. A FormManager is typically
 * created by the command \Villain\Form\InitializeForms, which stores FormManager
 * inside of the \FortissimoExecutionContext as $context->get('forms');
 *
 * @todo what other goodies should be added here?
 */
class FormManager {
  
  protected $cxt = NULL;
  
  /**
   * Construct a new FormManager.
   *
   * @param FortissimoExecutionContext $cxt
   *  The context for the present request.
   */
  public function __construct(\FortissimoExecutionContext $cxt) {
    $this->cxt = $cxt;
  }

  /**
   * Create a new form.
   *
   * @return \Villain\Form\Form
   *  A new an configured form object.
   */
  public function create() {
    return new Form($this->cxt);
  }
}