Create custom options, settings, global data fields, and more for your WordPress site with just a few lines of code.

# Description

Creating and managing options and settings is an essential feature for any modern website. While WordPress provides a built-in API for custom options, it can be cumbersome, leading to unnecessary code bloat and file clutter. With YM Fast Options, this process becomes significantly simpler!

Easily create and manage site settings, store contacts, and handle dynamic data with streamlined efficiency.

## Documentation

You can find more detailed documentation on the [official website](https://yanmet.com/blog/ym-fast-options-wordpress-plugin-documentation).

## Features

* Create customization pages, including top-level and sub-pages
* Add and manage options for your customization pages
* Enjoy full access to all WordPress Settings API features within a lightweight interface
* Seamlessly integrate settings into your multisite as needed

# Installation

1. **Visit** Plugins > Add New
1. **Search** for "YM Fast Options"
1. **Install and Activate** YM Fast Options from the Plugins page

# Changelog

## 2.1.0
* Added multisite mode. Set `in_network` argument in YMFO_Page as true to set options as for multisite.
* Added documentation link on Plugins page
* `ymfo_update_option`, `ymfo_get_option` and `ymfo_is_option_exists` can be used to work with multisite options.
* 'Settings saved' messages for non general options pages.
* Better `select`, `checkbox` and `radio` fields work.
* Now all YMFO_Page instances stored in YMFO `$pages` property.

## 2.0.6
* Added ability to display menu separator above top-level menu item with `has_separator` argument

## 2.0.5
* Translation fix

## 2.0.2
* Plugin's usage docs have been moved to the option page help tab

## 2.0.1
* New `ymfo_is_option_exists` function
* Bug fix for default checkbox value

## 2.0.0
* YMFO `format_field_slug` and `format_page_slug` methods are public and static now
* Added ability to add HTML to section descriptions
* PHPDoc improvements
* YMFO_Page `parent_page` argument now can be `string` type
* Since 2.0.0 you will have to avoid using `add_action` callback to register YMFO options

## 1.0.12
* Added ability to add HTML to field descriptions

## 1.0.11
* Added `ymfo_add_option` and `ymfo_update_option` functions

## 1.0.10
* New page boolean argument `show_docs` (default true). Allows to hide plugin documentation on settings page

## 1.0.9
* Added `radio` field type

## 1.0.8
* Visual improvement
* Ability to copy fields slugs and code snippets by click

## 1.0.7
* Added `ymfo` get option shortcode

## 1.0.6
* Added `select` and `checkbox` field types
* Added `options` field argument

## 1.0.5
* Minor fixes

## 1.0.4
* Added `number` field type
* Added `min`, `max`, `step` field arguments

## 1.0.3
* Added subpages functionality

## 1.0.2
* Added `placeholder` field argument

## 1.0.1
* Added `textarea` field type

## 1.0.0
* Initial release
