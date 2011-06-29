<?php
/** @file
 *
 * Install is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-25.
 */

namespace BasicBlog;

use \Villain\Content\Type\StringField;
use \Villain\Content\Type\IntegerField;
use \Villain\Content\Type\BooleanField;
use \Villain\Content\Type\TimestampField;
use \Villain\Content\Type\MongoIdField;
use \Villain\Content\Type\ListMemberField;
use \Villain\Content\Type\TypeDefinition;

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class Install extends BaseFortissimoCommand {

  private $blogName = 'BasicBlog';
  private $blogLabel = 'Blog';
  private $blogEntryName = 'BasicBlogEntry';
  private $blogEntryLabel = 'Blog Entry';

  public function expects() {
    return $this
      ->description('Installs the Basic Blog bundle.')
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('Nothing.')
    ;
  }

  public function doCommand() {
    $this->addBlogContentType();
    $this->addBlogEntryContentType();
  }
  
  protected function addBlogContentType() {
    $type = new TypeDefinition($this->blogName, $this->blogLabel);
    
    // Add the title field.
    $title = new StringField('title', 'Title');
    $title->setDescription('The title of the blog.');
    $title->setMaxLenght(128);
    
    
    $subtitle = new StringField('subtitle', 'Subtitle');
    $subtitle->setDescription('The subtitle of the blog.');
    $subtitle->setMaxLength(256);
    
    $description = new StringField('description', 'Description');
    $description->setDescription('A brief statement of what this blog is about.');
    
    $footer = new StringField('footer', 'Footer');
    $footer->setDescription('Closing information (usage, ownership, etc.) about this blog.');
    
    $entriesPerPage = new IntegerField('entriesPerPage', 'Entries per page');
    $entriesPerPage->setDescription('The number of entries displayed on a page.')
    $entriesPerPage->setDefaultValue(10);
    // Do we want 0 to mean "no limit"?
    $entriesPerpage->setMin(1);
    
    $showFullArticle = new BooleanField('showFullArticle', 'Show the full article?');
    $showFullArticle->setDescription('Should this show the full article, or just the lede (preview)?');
    
    $path = new StringField('path', 'Path');
    $path->setDescription('The path alias to this blog. This will modify the URL on websites.');
    $path->setMaxLength(255); // Longer paths will cause problems in some (mobile, older) browsers.
    
    $createdOn = new TimestampField('createdOn', 'Created on');
    $createdOn->setDescription('The date upon which this blog was created.');
    
    $updatedOn = new TimestampField('updatedOn', 'Updated on');
    $updatedOn->setDescription('The last time this blog was updated.');
    
    $createdBy = new MongoIdField('createdBy', 'Created by');
    $createdBy->setDescription('The user who created this content.')
    
    $type->addField($title);
    $type->addField($subtitle);
    $type->addField($description);
    $type->addField($footer);
    $type->addField($entriesPerPage);
    $type->addField($showFullArticle);
    $type->addField($createdOn);
    $type->addField($updatedOn);
    $type->addField($createdBy);
    
  }
  protected function addBlogEntryContentType() {
    $type = new TypeDefinition($this->blogEntryName, $this->blogEntryLabel);
    
    
    $title = new StringField('title', 'Title');
    $title->setDescription('The title of the blog.');
    $title->setMaxLenght(128);
    
    $lede = new StringField('lede', 'Lede');
    $lede->setDescription('The intro paragraph or teaser paragraph for this entry.');
    
    $tags = new StringField('tags', 'Tags');
    $tags->setDescription('One or more tags describing this post.');
    // Allow the field to repeat indefinitely.
    $tags->repeats(-1);
    
    $workflowState = new ListMemberField('workflowState', 'Workflow State');
    $workflowState->acceptedValues(array('draft', 'published', 'unpublished'));
    
    $body = new StringField('body', 'Body');
    $body->setDescription('The body of the blog post.');
    
    $createdOn = new TimestampField('createdOn', 'Created on');
    $createdOn->setDescription('The date upon which this blog entry was created.');
    
    $updatedOn = new TimestampField('updatedOn', 'Updated on');
    $updatedOn->setDescription('The last time this blog entry was updated.');
    
    $createdBy = new MongoIdField('createdBy', 'Created by');
    $createdBy->setDescription('The user who created this content.')
    
    $type->addField($title);
    $type->addField($lede);
    $type->addField($tags);
    $type->addField($body);
    $type->addField($workflowState);
    $type->addField($createdOn);
    $type->addField($updatedOn);
    $type->addField($createdBy);
    
  }
}

