<?php
/** @file
 *
 * FindContent is a BaseFortissimoCommand class.
 *
 * This was ported from Matt Butcher's Lumberjack logger utility.
 *
 * Created by Matt Butcher on 2011-07-01.
 */
namespace Villain\Content;

/**
 * Search a collection for content.
 *
 * This command provides a convenience command wrapper around a basic Mongo query. It
 * can be used for executing a Mongo database query from the command layer.
 *
 * @author Matt Butcher
 */
class FindContent extends \BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Executes a query with the given filter')
      ->usesParam('filter', 'The MongoDB filter array')
      ->whichIsRequired()
      
      ->usesParam('fields', 'The list of fields to return')
      ->usesParam('sort', 'The sort fields as an associative array')
      
      ->usesParam('limit', 'Integer indicating the max number of items to return')
      ->withFilter('callback', 'intval')
      ->whichHasDefault(0)
      
      ->usesParam('skip', 'Integer indicating the offset')
      ->withFilter('callback', 'intval')
      ->whichHasDefault(0)
      
      ->usesParam('collection', 'The name of the collection, if this differs from the default.')
      ->withFilter('string')
      
      ->andReturns('A MongoCursor (Iterable) with the data.')
    ;
  }
  
  public function doCommand() {
    $db = $this->context->ds('db')->get();
    $collection = $db->selectCollection($this->collectionName());
    
    return $this->query($collection);
  }

  /**
   * Given a MongoCollection, execute a query.
   *
   * @param MongoCollection $collection
   *  An initialized MongoDB collection.
   * @return MongoCursor
   *  A mongo cursor with the search prepared. Note that the cursor is
   *  lazy, and is not executed until an access is attempted.
   */
  public function query(MongoCollection $collection) {
    $query = $this->param('filter', array());
    $fields = $this->param('fields', NULL);
    
    // MongoCollection won't accept a NULL for the second param, and array() has special meaning.
    if (empty($fields)) {
      $results = $collection->find($query);
    }
    else {
      $results = $collection->find($query, $fields);
    }
    
    
    $sort = $this->param('sort', NULL);
    $limit = $this->param('limit', NULL);
    $skip = $this->param('skip', NULL);
    
    if (!empty($sort)) {
      $results->sort($sort);
    }
    
    if (!empty($skip)) {
      $results->skip($skip);
    }
    
    if (!empty($limit)) {
      $results->limit($limit);
    }
    
    return $results;
  }
  
  public function collectionName() {
    $collectionName = $this->param('collection', NULL);
    if (empty($collectionName)) {
      throw new \Villain\InterruptException("Attempt to query a non-collection.");
    }
    
    return $collectionName;
  }
}

