<?php
/** @file
 *
 * InitializeForms is a BaseFortissimoCommand class.
 *
 * Created by Matt Farina on 2011-07-09.
 */
namespace Villain\Form;
/**
 * Initialize the form system.
 *
 * We provide a common system for programming forms in a consistem manner. To
 * create a new form inside of a command:
 * 
 * @code
 * <?php
 * $form = $this->context('form')->create();
 * ?>
 * @endcode
 *
 * @author Matt Farina
 */
class InitializeForms extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Initialize the form system.')
      ->andReturns('A FormManager instance.')
    ;
  }

  public function doCommand() {
    $manager = new FormManager($this->context);

    return $manager;
  }
}

