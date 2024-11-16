<?php

/*
 * Plugin Name:       YM Fast Options
 * Description:       Create custom options for your WordPress site with just a few lines of code.
 * Version:           2.1.0
 * Requires PHP:      7.4
 * Requires at least: 6.0
 * Tested up to:      6.7
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ym-fast-options
 */

// Exits if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Gets plugin data.
if ( !function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$YMFO_plugin_data = get_plugin_data( __FILE__ );

// Defines plugin constants.
define( 'YMFO_PLUGIN_DATA', $YMFO_plugin_data );
define( 'YMFO_ROOT_DIR',    plugin_dir_path( __FILE__ ) );
define( 'YMFO_ROOT_URI',    plugin_dir_url( __FILE__ ) );

// Includes plugin components.
require_once YMFO_ROOT_DIR . 'includes/YMFO.class.php';
require_once YMFO_ROOT_DIR . 'includes/YMFO_Page.class.php';

// Adds docs link to plugin's card on Plugins page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	array_unshift( $links, sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://yanmet.com/blog/ym-fast-options-documentation' ),
		__( 'Documentation', 'ym-fast-options' ),
	));

	return $links;
});

// Connects styles and scripts.
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'ymfo-styles', YMFO_ROOT_URI . 'assets/css/ymfo-style.css', [], YMFO_PLUGIN_DATA[ 'Version' ] );
	wp_enqueue_script( 'ymfo-scripts', YMFO_ROOT_URI . 'assets/js/ymfo-script.js',  [], YMFO_PLUGIN_DATA[ 'Version' ], true );
});

// Adds 'ymfo' shortcode.
add_shortcode( 'ymfo', function ( $atts ) {
	return ymfo_get_option( $atts[ 'page' ], $atts[ 'option' ] );
});

/**
 * Adds new option.
 * 
 * @since 1.0.11
 * 
 * @param string      $page       Option page slug.
 * @param string      $option     Option slug.
 * @param mixed       $value      Option value.
 * @param string|bool $autoload   Whether to load the option when WordPress starts up.
 * 
 * @return bool Always returns true.
 */
function ymfo_add_option ( string $page, string $option, mixed $value = '', string|bool $autoload = 'yes' ) : bool {
	return add_option( YMFO::format_field_slug( $page, $option ), $value, '', $autoload );
}

/**
 * Updates option value.
 * 
 * @since 1.0.11
 * 
 * @param string      $page     Option page slug.
 * @param string      $option   Option slug.
 * @param mixed       $value    Option value.
 * @param string|bool $autoload Whether to load the option when WordPress starts up.
 * 
 * @return bool True if the value was updated, false otherwise.
 */
function ymfo_update_option ( string $page, string $option, mixed $value = '', string|bool $autoload = null ) : bool {
	$page_data   = YMFO::$pages[ $page ];
	$option_name = YMFO::format_field_slug( $page, $option );

	if ( !isset( $page_data ) ) {
		return false;
	}

	if ( $page_data->page_args[ 'in_network' ] ) {
		return update_site_option( $option_name, $value );
	}

	return update_option( $option_name, $value, $autoload );
}

/**
 * Returns option value.
 * 
 * @param string $page          Option page slug.
 * @param string $option        Option slug.
 * @param mixed  $default_value Default value if option is not exists.
 * 
 * @return mixed Option value or default value.
 */
function ymfo_get_option ( string $page, string $option, mixed $default_value = false ) : mixed {
	$page_data   = YMFO::$pages[ $page ];
	$option_name = YMFO::format_field_slug( $page, $option );

	if ( !isset( $page_data ) ) {
		return $default_value;
	}

	if ( $page_data->page_args[ 'in_network' ] ) {
		return get_site_option( $option_name, $default_value );
	}

	return get_option( $option_name, $default_value );
}

/**
 * Returns true if option exists in database.
 * 
 * @since 2.0.1
 * 
 * @param string $page   Option page slug.
 * @param string $option Option slug.
 * 
 * @return bool True if option exists.
 */
function ymfo_is_option_exists ( string $page, string $option ) : bool {
	$page_data = YMFO::$pages[ $page ];

	if ( ! isset( $page_data ) ) {
		return false;
	}

	global $wpdb;

	$in_network = $page_data->page_args[ 'in_network' ];

	$table  = esc_sql( $in_network ? $wpdb->sitemeta : $wpdb->options );
	$column = esc_sql( $in_network ? 'meta_key' : 'option_name' );
	
	return boolval( $wpdb->query(
		$wpdb->prepare( "SELECT * FROM `$table` WHERE `$column` = %s LIMIT 1",
			YMFO::format_field_slug( $page, $option ),
		)
	));
}