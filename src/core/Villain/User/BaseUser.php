<?php
/** @file
 * The main user storable object.
 */

/**
 * Villain's user subsystem.
 *
 * The user system works atop the Content system, and BaseUser objects are Storable.
 *
 * Villain provides Fortissimo commands for loading, saving, deleting, and checking access on
 * user objects.
 */
namespace Villain\User;

/**
 * The main user Storable object.
 *
 * This defines a base class that can be directly instantiated, but that provides only
 * rudimentary user support.
 */
class BaseUser extends \Villain\StorableObject {
  
}