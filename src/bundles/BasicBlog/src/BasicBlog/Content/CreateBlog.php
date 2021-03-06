<?php
/** @file
 *
 * CreateBlogContent is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-29.
 */

namespace BasicBlog\Content;
use \Villain\Storage\StorableObject;

/**
 * Prepare blog content for saving.
 *
 * This takes input and transforms it into a Blog. The blog can then be saved.
 *
 * @author Matt Butcher
 */
class CreateBlog extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Create a new blog.')
            
      ->usesParam('title', 'The title of this blog. Markup removed.')
      ->withFilter('this', 'validateTitle')
      ->whichIsRequired()
      
      ->usesParam('subtitle', 'The subtitle of this blog.')
      ->withFilter('string', FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)
      ->whichHasDefault('')
      
      ->usesParam('shortName', 'The short, computer-friendly name (mnemonic) of this blog. Used in URLs and other places. Valid characters: a-z, A-Z, 0-9, and -.')
      ->withFilter('validate_regexp', array('options' => array('regexp' => '/^[a-zA-Z0-9\-]+$/')))
      ->whichIsRequired()
      
      ->usesParam('descriptionFilter', 'The filter chain applied to the description')
      ->withFilter('string')
      ->whichHasDefault('safeHTML')
      
      ->usesParam('description', 'A description of this blog. May contain markup. Filtered by descriptionFilter.')
      ->withFilter('this', 'validateDescription')
      ->whichHasDefault('')
      
      ->usesParam('footer', 'The footer. Validated by descriptionFilter.')
      ->withFilter('this', 'validateDescription')
      ->whichHasDefault('')
      
      ->usesParam('entriesPerPage', 'Number of entries per page of the blog.')
      ->withFilter('number_int')
      ->whichHasDefault(10)
      
      ->usesParam('showFullArticle', 'Whether or not to show the full article.')
      ->whichHasDefault(TRUE)
      ->withFilter('boolean')
      
      ->usesParam('createdOn', 'The date/time this was created. Can be a timestamp or anything parseable with strtotime.')
      ->withFilter('callback', '\Villain\Util\CommandFilters::sanitizeDate')
      ->whichHasDefault('@' . FORTISSIMO_REQ_TIME)
      
      ->usesParam('updatedOn', 'The date/time this was updated. Can be a timestamp or anything parseable with strtotime.')
      ->withFilter('callback', '\Villain\Util\CommandFilters::sanitizeDate')
      ->whichHasDefault('@' . FORTISSIMO_REQ_TIME)
      
      ->usesParam('createdBy', 'The username of the user who created this.')
      ->withFilter('string')
      ->whichHasDefault('?')
      
      ->andReturns('Data structured for SaveContent.')
    ;
  }
  
  public function validateDescription($value) {
    if (empty($value)) {
      return $value;
    }
    $filter = $this->param('descriptionFilter');
    return $this->context('filters')->run($filter, $value);
  }
  
  public function validateTitle($title) {
    $clean = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $len = strlen($clean);
    if ($len > 255) {
      throw new \Villain\Exception("Title is too long");
    }
    if ($len == 0) {
      throw new \Villain\Exception("Title is required");
    }
    return $clean;
  }

  public function doCommand() {
    
    // Make sure this is cast into integer.
    $this->parameters['entriesPerPage'] = (int)$this->parameters['entriesPerPage'];
    
    $so = StorableObject::newFromArray($this->parameters);
    return $so;
  }
}

