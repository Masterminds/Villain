<?php
/** @file
 *
 * CreateBlogContent is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-29.
 */

namespace BasicBlog\Content;

/**
 * Prepare blog content for saving.
 *
 * This takes input and transforms it into a Blog. The blog can then be saved.
 *
 * @author Matt Butcher
 */
class CreateBlogContent extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Create a new blog.')
            
      ->usesParam('title', 'The title of this blog. Markup removed.')
      ->withFilter('this', 'validateTitle')
      ->whichIsRequired()
      
      ->usesParam('subtitle', 'The subtitle of this blog.')
      ->withFilter('string', FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)
      ->whichHasDefault('')
      
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
      ->withFilter('callback', 'strtotime')
      ->whichHasDefault(FORTISSIMO_REQ_TIME)
      
      ->usesParam('updatedOn', 'The date/time this was updated. Can be a timestamp or anything parseable with strtotime.')
      ->withFilter('callback', 'strtotime')
      ->whichHasDefault(FORTISSIMO_REQ_TIME)
      
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
      throw new \FortissimoException("Title is too long");
    }
    if ($len == 0) {
      throw new \FortissimoException("Title is required");
    }
    return $clean
  }

  public function doCommand() {

    // $myParam = $this->param('myParam', 'Default value');
    // $myCxt = $this->context('myContext', 'Default value');


    // return $result; // Insert into Context.
  }
}

