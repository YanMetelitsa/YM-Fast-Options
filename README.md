Create custom options, settings, global data fields, and more for your WordPress site with just a few lines of code.

# Description

Creating and managing options and settings is an essential feature for any modern website. While WordPress provides a built-in Settings API, it can be cumbersome, leading to unnecessary code bloat and file clutter. With YM Fast Options, this process becomes significantly simpler!

Easily create and manage site settings, store contacts, and handle dynamic data with streamlined efficiency.

## Documentation

You can find more detailed documentation on the [official website](https://yanmet.com/blog/ym-fast-options-wordpress-plugin-documentation).

## Features

* Create settings pages, including top-level and sub-pages
* Add and manage options for your settings pages
* Enjoy full access to all WordPress Settings API features within a lightweight interface
* Seamlessly integrate settings into your multisite as needed

## Field Types

* text
* textarea
* number
* select
* checkbox
* radio
* tel
* email
* url
* image
* date
* datetime-local
* month
* week
* time
* color

# Changelog

## 2.3.0

*Added*

* `sanitize_callback` parameter in `YMFO_Page->add_field()` method.

## 2.2.3

* Minor fixes

## 2.2.1

*Fixed*

* WordPress 6.7.0 `get_plugin_data()`

## 2.2.0

*Added*

* `image` field type

## 2.1.0

*Added*

* Multisite mode – set `in_network` argument in `YMFO_Page` as `true` to set options as for multisite
* `ymfo_update_option`, `ymfo_get_option` and `ymfo_is_option_exists` can be used to work with multisite options
* "Settings saved" messages for non-general options pages
* Now all `YMFO_Page` instances stored in YMFO `$pages` property
* Documentation link on "Plugins" page

*Fixed*

* Better `select`, `checkbox` and `radio` fields work

## 2.0.6

*Added*

* Ability to display menu separator above top-level menu item with `has_separator` argument

## 2.0.5

*Fixed*

* Translations

## 2.0.2

*Added*

* Plugin's usage docs have been moved to the option page help tab

## 2.0.1

*Added*

* `ymfo_is_option_exists` function

*Fixed*

* Default checkbox value

## 2.0.0

*Added*

* Since 2.0.0 you will have to avoid using `add_action` callback to register YMFO options
* `format_field_slug` and `format_page_slug` methods are public and static now
* `parent_page` argument now can be of `string` type
* Ability to add HTML to section description

*Fixed*

* PHPDoc

## 1.0.12

*Added*

* Ability to add HTML to field description

## 1.0.11

*Added*

* `ymfo_add_option` and `ymfo_update_option` functions

## 1.0.10

*Added*

* Page argument `show_docs` (default `true`). Allows to hide plugin documentation on settings page

## 1.0.9

*Added*

* `radio` field type

## 1.0.8

*Added*

* Ability to copy fields slugs and code snippets by click

## 1.0.7

*Added*

* `[ymfo]` shortcode

## 1.0.6

*Added*

* `select` and `checkbox` field types
* `options` field argument

## 1.0.4

*Added*

* `number` field type
* `min`, `max`, `step` field arguments

## 1.0.3

*Added*

* Subpage functionality

## 1.0.2

*Added*

* `placeholder` field argument

## 1.0.1

*Added*

* `textarea` field type

## 1.0.0

* Initial release