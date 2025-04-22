<?php

// Exits if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main YM Fast Options class.
 */
class YMFO {
	/**
	 * YMFO pages archive data.
	 * 
	 * @var YMFO_Page[]
	 */
	public static array $pages = [];

	/**
	 * Formats page slug tale.
	 * 
	 * @since 2.0.0 Is public and static
	 * 
	 * @param string $page_slug_tale Page slug tale.
	 * 
	 * @return string Full page slug.
	 */
	public static function format_page_slug ( string $page_slug_tale ) : string {
		return "ymfo-{$page_slug_tale}";
	}

	/**
	 * Formats field slug tale.
	 * 
	 * @since 2.0.0 Is public and static
	 * 
	 * @param string $page_slug_tale  Page slug tale.
	 * @param string $field_slug_tale Field slug tale.
	 * 
	 * @return string Full field slug.
	 */
	public static function format_field_slug ( string $page_slug_tale, string $field_slug_tale ) : string {
		return self::format_page_slug( $page_slug_tale ) . "-{$field_slug_tale}-field";
	}
}