<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** Main YM Fast Options class */
class YMFO {
	/** Inits YM Fast Options */
	public static function init () : void {
		self::enqueue_scripts();
		self::add_shortcodes();
	}

	/**
	 * Enqueues YM Fast Options scripts
	 * 
	 * @since 1.0.8
	 */
	private static function enqueue_scripts () : void {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_style( 'ymfo-styles', YMFO_ROOT_URI . 'assets/css/ymfo-style.css', [], YMFO_PLUGIN_DATA[ 'Version' ] );
			wp_enqueue_script( 'ymfo-scripts', YMFO_ROOT_URI . 'assets/js/ymfo-script.js',  [], YMFO_PLUGIN_DATA[ 'Version' ] );
		});
	}

	/**
	 * Adds shortcodes
	 * 
	 * @since 1.0.7
	 */
	private static function add_shortcodes () : void {
		add_shortcode( 'ymfo', function ( $atts ) {
			return ymfo_get_option( $atts[ 'page' ], $atts[ 'option' ] );
		});
	}

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