=== YM Fast Options ===
Contributors: yanmetelitsa
Tags: options, settings
Tested up to: 6.5.3
Requires at least: 6.4
Requires PHP: 8.1
Stable tag: 1.0.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create simple options, settings, global data fields and more for your WordPress website with a few lines of code.

== Description ==

= How to use =

First of all open your `functions.php` theme file. All the next steps will take place in it.

**1. Add callback function to `admin_menu` action hook**

`
if ( class_exists( 'YMFO_Page' ) ) {
	/** Registers YMFO custom options */
	add_action( 'admin_menu', function () {
		// Next code here
	});
}
`

**2. Create options page**

For example it will be a contacts page.

`
$contacts_page = new YMFO_Page( 'Contacts', 'contacts' );
`

The first function argument is the page title, and the second is the page slug.

**3. Create options section**

For example it will be a section with social media links.

`
$contacts_page->add_section( 'Social media', 'social_media' );
`

The first function argument is the section title, and the second is the section slug.

**4. Add options fields to section**

`
$contacts_page->add_field( 'YouTube', 'youtube_link', 'url', 'social_media' );
$contacts_page->add_field( 'Facebook', 'facebook_link', 'url', 'social_media' );
`

The first function argument is the field title, the second is the field slug, the third is the field type (you can find allowed types below) and the fourth is the field section slug.

**It's done!**

You can locate the new options page in the WordPress admin area on the sidebar.

Our result code is:

`
if ( class_exists( 'YMFO_Page' ) ) {
	/** Registers YMFO custom options */
	add_action( 'admin_menu', function () {
		// Create page
		$contacts_page = new YMFO_Page( 'Contacts', 'contacts' );

		// Create section
		$contacts_page->add_section( 'Social media', 'social_media' );

		// Create fields
		$contacts_page->add_field( 'YouTube', 'youtube_link', 'url', 'social_media' );
		$contacts_page->add_field( 'Facebook', 'facebook_link', 'url', 'social_media' );
	});
}
`

= How to get new options values =

To get your options values use `ymfo_get_option( $page_slug, $field_slug )`.

For example let's print a link to your YouTube channel:

`
<a href="<?php echo esc_attr( ymfo_get_option( 'contacts', 'youtube_link' ) ); ?>">YouTube</a>
`

= Allowed field types =

- text
- textarea
- number
- select
- checkbox
- tel
- email
- url
- date
- datetime-local
- month
- week
- time
- color

== Installation ==

1. **Visit** Plugins > Add New
1. **Search** for "YM Fast Options"
1. **Install and Activate** YM Fast Options from the Plugins page

== Changelog ==

= 1.0.6 =
* Added `select` and `checkbox` field types
* Added `options`, `max`, `step` field arguments

= 1.0.5 =
* Minor fixes

= 1.0.4 =
* Added `number` field type
* Added `min`, `max`, `step` field arguments

= 1.0.3 =
* Added subpages functionality

= 1.0.2 =
* Added `placeholder` field argument

= 1.0.1 =
* Added `textarea` field type

= 1.0.0 =
* Initial release