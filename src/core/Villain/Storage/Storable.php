<?php
/** @file
 * The storable interface definition.
 * @ingroup VillainStorage
 */

/** @defgroup VillainStorage Villain Storage
 *
 * Villain uses an object-oriented storage system to provide a simple abstraction
 * for storing and retrieving objects.
 *
 * Objectst that can be stored should implement the Storable interface. Typically, they are
 * best off if they extend StorableObject or decorate other Storable objects as a 
 * StorableObjectDecorator.
 */

/**
 * The Villain storage system.
 *
 * Villain provides a seemless translation layer for persisting Villain objects. This is
 * the Storage layer. Objects that are Storable can be stored in the Villain database. The
 * StorableObject is the main type of stored content.
 */
namespace Villain\Storage;

/**
 * This interface describes a storable object.
 *
 * Storable objects can be transformed into and out of a serialization state optimized for 
 * storage in Villain's database. Typically, the StorableObject class is the best place to
 * begin. However, specialized classes may benefit from implementing Storable directly.
 *
 * @ingroup VillainStorage
 */
interface Storable {
  /**
   * Tranform the object to a storable array.
   *
   * @return array
   *  An array representing the storable object.
   */
  public function toArray();
  /**
   * Re-initialize a storage object with the given data.
   *
   * Note that this bypasses any data checks. The assumption is that
   * the data passed in is already valid data.
   *
   * @param array $arr
   *  An associative array of name/value pairs. Most Storable implementations will allow
   *  scalar, array, and object values in the array.
   */
  public function fromArray(array $arr);
}