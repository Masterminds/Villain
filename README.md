# Villain: It's not a bad idea, it's a BAAAAD idea.

Villain is a PHP Content Management Framework (CMF) developed to be feature rich, high performance, and easy to extend.

## Quickstart

To fetch and install Villain, do the following:

    $ git clone http://github.com/masterminds/Villain.git
    $ cd Villain
    $ ./fort --config config/install.php install

At this point, `Villain/src` will contain your new website.

## What Is Villain?

Villain is a content management framework -- a tool designed to help PHP developers quickly build a 
CMS system individualized to their needs. It has been designed to be fast, scalable, and easy to
extend.

## Prerequisites

Villain REQUIRES the following:

- PHP 5.3+
- PECL MongoDB driver (`pecl install mongodb`)
- A recent version of MongoDB

Villain SUGGESTS the following:

- phing
- PHPUnit
- doxygen 1.7.3+
- Apache 2+ with mod_php or another comparable webserver configuration

## Building a CMS with Villain

To build a CMS from Villain, you probably want to do the following:

* Install Villain
* Install whatever Bundles you need
* Beginning with `src/config/commands.php`, customize your site
* Develop your own bundles (try `./fort @create-bundle FOO` to get started)
* Test and deploy.

## Key Concepts

* Bundle: Roughly speaking, bundles are "plugins" or "modules" for 
  Villain. A bundle can provide commands, request implementations,
  assets like CSS, images, and JavaScript, and supporting files.
* Chain of Command: Villain maps a REQUEST to a CHAIN OF COMMANDS (and
  then maps URLs to requests). When Villain receives a request, it
  processes a series of commands in sequence. When you build
  your CMS with Villain, you will build your own chains.
* commands.php: The main configuration file for Villain. Among other things,
  it maps requests to chains of commands.
* fort: Fort is the command-line runner that Villain uses.
* Fortissimo: Villain is built on the Fortissimo framework. Fortissimo
  is a lightweight scalable PHP framework.
* Storable/Storage: Villain uses a very lightweight intermediate layer to translate
  objects into and out of MongoDB. The Storable system provides that 
  translation system. See src/core/Villain/Storage.