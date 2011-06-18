<?php
/**
 * @file
 * The configuration file for BasicBlog.
 */

Config::group('blogSetup')
  ->doesCommand('setupBlogTheme')
    ->whichInvokes('InitializeTheme')
    ->withParam('register')->whoseValueIs(array('\BasicBlog\Theme\BlogTheme'))
    ->withParam('path')->whoseValueIs('bundles/BasicBlog/media')
;

Config::request('blog')
  ->usesGroup('blogSetup')
;