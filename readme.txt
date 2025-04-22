=== YM Fast Options ===
Contributors: yanmetelitsa
Tags: options, settings
Stable tag: 2.2.2
Requires PHP: 7.4
Requires at least: 6.0
Tested up to: 6.8
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create custom options, settings, global data fields, and more for your WordPress site with just a few lines of code.

== Description ==

Creating and managing options and settings is an essential feature for any modern website. While WordPress provides a built-in Settings API, it can be cumbersome, leading to unnecessary code bloat and file clutter. With YM Fast Options, this process becomes significantly simpler!

Easily create and manage site settings, store contacts, and handle dynamic data with streamlined efficiency.

= Documentation =

You can find more detailed documentation on the [official website](https://yanmet.com/blog/ym-fast-options-wordpress-plugin-documentation).

= Features =

* Create settings pages, including top-level and sub-pages
* Add and manage options for your settings pages
* Enjoy full access to all WordPress Settings API features within a lightweight interface
* Seamlessly integrate settings into your multisite as needed

= Field Types =

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

== Changelog ==

= 2.2.1 =
* Fix: WordPress 6.7.0 `get_plugin_data()`

= 2.2.0 =
* New: `image` field type

= 2.1.0 =
* New: Multisite mode – set `in_network` argument in YMFO_Page as true to set options as for multisite
* New: `ymfo_update_option`, `ymfo_get_option` and `ymfo_is_option_exists` can be used to work with multisite options
* New: 'Settings saved' messages for non-general options pages
* New: Now all YMFO_Page instances stored in YMFO `$pages` property
* New: Documentation link on `Plugins` page
* Fix: Better `select`, `checkbox` and `radio` fields work

= 2.0.6 =
* New: Ability to display menu separator above top-level menu item with `has_separator` argument

= 2.0.5 =
* Fix: Translations

= 2.0.2 =
* New: Plugin's usage docs have been moved to the option page help tab

= 2.0.1 =
* New: `ymfo_is_option_exists` function
* Fix: Default checkbox value

= 2.0.0 =
* New: Since `2.0.0` you will have to avoid using `add_action` callback to register YMFO options
* New: `format_field_slug` and `format_page_slug` methods are public and static now
* New: `parent_page` argument now can be of `string` type
* New: Ability to add HTML to section description
* Fix: PHPDoc improvements

= 1.0.12 =
* New: Ability to add HTML to field description

= 1.0.11 =
* New: `ymfo_add_option` and `ymfo_update_option` functions

= 1.0.10 =
* New: Page argument `show_docs` (default `true`). Allows to hide plugin documentation on settings page

= 1.0.9 =
* New: `radio` field type

= 1.0.8 =
* New: Ability to copy fields slugs and code snippets by click
* Fix: Visual improvement

= 1.0.7 =
* New: `[ymfo]` shortcode

= 1.0.6 =
* New: `select` and `checkbox` field types
* New: `options` field argument

= 1.0.4 =
* New: `number` field type
* New: `min`, `max`, `step` field arguments

= 1.0.3 =
* New: Subpages functionality

= 1.0.2 =
* New: `placeholder` field argument

= 1.0.1 =
* New: `textarea` field type

= 1.0.0 =
* Initial release