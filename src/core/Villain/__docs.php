<?php
/**
 * @file
 * Documentation for Villain\*
 */

/**
 * @defgroup VillainCore Villain
 *
 * Villain is a CMS framework.
 *
 * It provides you with the tools necessary for building a content management system. At the
 * programatic core of Villain are Fortissimo, the Villain Content and Storage systems, and MongoDB.
 *
 * Getting Started:
 *
 * - First, all Villain code is stored in the Villain namespace (in src/core/Villain). Fortissimo 
 *   code is in src/core/Fortissimo.
 * - You should be familiar with the config/commands.php file, which defines Villain's chains of 
 *   command.
 * - You can get a handle on Villain's storage system by looking at the Villain::Storage::Storable 
 *   and Villain::Storage::StorableObject
 *   files.
 * - To see how content works, take a look at Villain::Content::LoadContent and 
 *   Villain::Content::SaveContent.
 * - Front-end theming is done through Theme and RenderTheme -- both of which are provided by
 *   Fortissimo.
 * - The most important pieces of the command system are the BaseFortissimoCommand and the 
 *   FortissimoExecutionContext, which is passed from object to object during a chain of command.
 */

/**
 * @defgroup VillainContent Villain Content
 *
 * Villain has a content subsystem for creating content types and performing CRUD-style
 * operations against the Villain database.
 *
 * The Content system provides basic content handling, while the Type system provides the 
 * tools for building content types.
 *
 * Important classes:
 * - LoadContent: Load a piece of content
 * - SaveContent: Save a piece of content
 * - DeleteContent: Delete a piece of content
 * - TypeDefinition: Define a new content type
 * - Field: Parent to all field types
 *
 * Relation to other Villain concepts:
 *
 * - It is basically assumed that all Content is Storable, but it is not assumed that all Storable
 * objects are content.
 * - The User system is based on the Content system.
 */

/**
 * @defgroup VillainUser Villain User
 *
 * The Villain user system.
 * 
 */

/**
 * @defgroup VillainBundles Villain Bundles
 *
 * Bundles are Villain's system for building extensions.
 */

/**
 * @defgroup VillainForm Villain Forms
 *
 * The Villain form system.
 */

/**
 * The main Villain namespace.
 * @ingroup VillainCore
 */
namespace Villain {}


?>