<?php

/*
 * Plugin Name:       YM Fast Options
 * Description:       Create simple options for your WordPress website with a few lines of code.
 * Version:           2.0.1
 * Tested up to:      6.5.3
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ym-fast-options
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Get plugin data
if( !function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$YMFO_plugin_data = get_plugin_data( __FILE__ );

// Define constants
define( 'YMFO_PLUGIN_DATA', $YMFO_plugin_data );
define( 'YMFO_ROOT_DIR',    plugin_dir_path( __FILE__ ) );
define( 'YMFO_ROOT_URI',    plugin_dir_url( __FILE__ ) );

// Include components
require_once YMFO_ROOT_DIR . 'includes/YMFO.class.php';
require_once YMFO_ROOT_DIR . 'includes/YMFO_Page.class.php';

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
    return update_option( YMFO::format_field_slug( $page, $option ), $value, $autoload );
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
    return get_option( YMFO::format_field_slug( $page, $option ), $default_value );
}

/**
 * Returns is option exists in database.
 * 
 * @since 2.0.1
 * 
 * @param string $page          Option page slug.
 * @param string $option        Option slug.
 * 
 * @return bool True if option exists.
 */
function ymfo_is_option_exists ( string $page, string $option ) : bool {
	global $wpdb;

	$full_option_name = esc_sql( YMFO::format_field_slug( $page, $option ) );
	
	return boolval( $wpdb->query( "SELECT * FROM `{$wpdb->options}` WHERE `option_name` = '{$full_option_name}' LIMIT 1" ) );
}

// Init YM Fast Options
YMFO::init();