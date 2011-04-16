<?php
/** @file
 * The storable interface definition.
 */

namespace Villain\Storage;

/**
 * This interface describes a storable object.
 */
interface Storable {
  /**
   * Tranform the object to a storable array.
   */
  public function toArray();
  /**
   * Re-initialize a storage object with the given data.
   *
   * Note that this bypasses any data checks. The assumption is that
   * the data passed in is already valid data.
   *
   *
   */
  public function fromArray(array $arr);
}