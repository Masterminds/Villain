<?php
/** @file
 *
 * CreateBlogEntry is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-30.
 */
namespace BasicBlog\Content;
/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class CreateBlogEntry extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('')
      
      ->usesParam('title', 'The title of the blog entry')
      ->usesParam('teaser', 'The teaser for the blog post')
      ->usesParam('tags', 'A list of tags associated with this blog post.')
      ->usesParam('body', 'The blog post content')
      ->usesParam('workflowStatus', 'The status (published, draft, unpublished, etc.)')
      ->usesParam('createdOn', 'The date or timestamp this was created. Anything that can be parsed with strtotime.')
      ->usesParam('createdBy', 'The username for the user that created this.')
      ->usesParam('updatedOn', 'The date or timestamp this was last updated. Anything that can be parsed with strtotime.')
      ->usesParam('blog', 'The blog that this entry belongs to. A MongoDBRef')
      
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('')
    ;
  }

  public function doCommand() {

    // $myParam = $this->param('myParam', 'Default value');
    // $myCxt = $this->context('myContext', 'Default value');


    // return $result; // Insert into Context.
  }
}

