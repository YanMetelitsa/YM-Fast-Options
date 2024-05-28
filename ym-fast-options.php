<?php

/*
 * Plugin Name:       YM Fast Options
 * Description:       Create simple options for your WordPress website with a few lines of code.
 * Version:           1.0.8
 * Tested up to:      6.5.3
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ym-fast-options
 */

/** Exit if accessed directly */
if ( !defined( 'ABSPATH' ) ) exit;

// Connects styles
add_action( 'admin_enqueue_scripts', function () {
	if( !function_exists( 'get_plugin_data' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
	$plugin_data = get_plugin_data( __FILE__ );

    wp_enqueue_style( 'ymfo-styles', plugins_url( 'assets/css/ymfo-style.css', __FILE__ ), [], $plugin_data[ 'Version' ] );
	wp_enqueue_script( 'ymfo-scripts', plugins_url( 'assets/js/ymfo-script.js', __FILE__ ),  [], $plugin_data[ 'Version' ] );
});

// Registers get option shortcode
add_shortcode( 'ymfo', function ( $atts ) {
	return ymfo_get_option( $atts[ 'page' ], $atts[ 'option' ] );
});

class YMFO_Page {
    private string $page_slug_tale;
    private string $page_slug;
    private string $page_title;
    private array  $page_args;

    /**
     * Available option field types
     */
    private array $available_field_types = [
        'text', 'textarea', 'number',
		'select', 'checkbox',
        'tel', 'email', 'url',
        'date', 'datetime-local', 'month', 'week', 'time',
        'color',
    ];

    /**
     * Adds a custom options page
     * 
     * @param string $page_title     The text to be displayed in the title tags of the page when the menu is selected.
     * @param string $page_slug_tale The tale part of slug name to refer to this menu by.
     * @param array  $page_args {
	 * 		Additional arguments
	 * 
	 * 		@type bool           $is_top_level Set false to make menu item as submenu to Settings menu. Default true.
	 * 		@type null|YMFO_Page $parent_page  YMFO_Page class object with parent page. Default null.
	 * 		@type string         $menu_title   The text to be used for the menu. Default $page_title.
	 * 		@type string         $icon         The URL to the icon to be used for this menu. Default ''.
	 * 		@type float|int      $position     Set false to make menu item as submenu to Settings menu. Default null.
	 * 		@type callable		 $callback     The function to be called to output the content for this page.
	 * 		@type string         $capability   The capability required for this menu to be
	 * 										   displayed to the user. Default 'manage_options'.
	 * }
     */
    function __construct ( string $page_title, string $page_slug_tale, array $page_args = [] ) {
        // Save page data to object
        $this->page_slug_tale = $page_slug_tale;
        $this->page_slug      = $this->format_page_slug( $page_slug_tale );
        $this->page_title     = $page_title;
        $this->page_args      = [
			'is_top_level' => true,
			'parent_page'  => null,

			'menu_title'   => $this->page_title,
			'icon'         => '',
			'position'     => null,

			'callback'     => function () {
                include 'page.php';
            },
			'capability'   => 'manage_options',

			...$page_args,
		];

        // Set page arguments
        $add_page_args = [
            $this->page_title,          		// Page title
            $this->page_args[ 'menu_title' ],	// Menu title
            $this->page_args[ 'capability' ],	// Capability
            $this->page_slug,           		// Slug
            $this->page_args[ 'callback' ],		// Callback
			$this->page_args[ 'icon' ],			// Icon
			$this->page_args[ 'position' ],		// Position
        ];

		// Add top page, settings page or child to parent page
		if ( $this->page_args[ 'parent_page' ] === null ) {
			if ( $this->page_args[ 'is_top_level' ] ) {
				add_menu_page( ...$add_page_args );
			} else {
				// Remove icon argument value
				unset( $add_page_args[ 5 ] );
	
				add_options_page( ...$add_page_args );
			}
		} else {
			// Exit if parent page not YMFO_Page object
			if ( !$this->page_args[ 'parent_page' ] instanceof YMFO_Page ) {
				return;
			}

			// Remove icon argument value
			unset( $add_page_args[ 5 ] );

			// Add parent slug argument value
			array_unshift( $add_page_args, $this->page_args[ 'parent_page' ]->page_slug );

			add_submenu_page( ...$add_page_args );
		}
    }

    /**
     * Adds an options section
     * 
     * @param string $section_title Section title
     * @param string $section_slug  Section slug
     * @param array  $section_args  {
	 * 		Additional arguments
	 * 
	 * 		@type string $description Section description below title.
	 * }
     */
    public function add_section ( string $section_title, string $section_slug, array $section_args = [] ) : void {
        add_settings_section(
            $section_slug,              	// Section slug
            $section_title,             	// Section title
            function ( $args ) {        	// Callback
                include 'section.php';
            },
            $this->page_slug,           	// Page slug
            $section_args,              	// Additional arguments
        );
    }

    /**
     * Adds an option field
     * 
     * @param string $field_title     Field title
     * @param string $field_slug_tale Field slug
     * @param string $field_type      Field type
     * @param string $field_section   Field section
     * @param array  $field_args {
	 * 		Additional arguments
	 * 
	 * 		@type string $description Field description below input.
	 * 		@type string $placeholder Input placeholder text. Default empty.
	 * 		@type bool   $required    Is field required. Default false.
	 * 
	 * 		# For number fields type
	 * 
	 * 		@type float|int $min  Minimum value.
	 * 		@type float|int $max  Maximum value.
	 * 		@type float|int $step Step of value change by arrows.
	 * 
	 * 		# For select field type
	 * 
	 * 		@type array $options {
	 * 			@type string $label Option label.
	 * 			@type string $value Option value.
	 * 		}
	 * }
     */
    public function add_field ( string $field_title, string $field_slug_tale, string $field_type, string $field_section, array $field_args = [] ) : void {
        // Check is field type allowed
        if ( !in_array( $field_type, $this->available_field_types ) ) {
            return;
        }
        
        // Set full field slug
        $field_slug = $this->format_field_slug( $this->page_slug_tale, $field_slug_tale );

		// Register setting
        register_setting( $this->page_slug, $field_slug );

		// Add field
        add_settings_field(
            $field_slug,                	// Field slug
            $field_title,					// Field title
            function ( $args ) {        	// Callback
                include 'field.php';
            },
            $this->page_slug,           	// Page slug
            $field_section,             	// Section slug
            [								// Arguments
                ...$field_args,

                'field_type'        => $field_type,

                'field_name'        => $field_slug,
                'field_id'          => $field_slug,
                'label_for'         => $field_slug,

				'is_field_required' => isset( $field_args[ 'required' ] ) && $field_args[ 'required' ] == true,

                'page_slug_tale'    => $this->page_slug_tale,
                'field_slug_tale'   => $field_slug_tale,
            ],
        );
    }


	/**
	 * Formats page slug tale
	 * 
	 * @param string $page_slug_tale Page slug tale
	 * 
	 * @return string Full page slug
	 */
	private function format_page_slug ( string $page_slug_tale ) : string {
		return "ymfo-{$page_slug_tale}";
	}

	/**
	 * Formats field slug tale
	 * 
	 * @param string $page_slug_tale  Page slug tale
	 * @param string $field_slug_tale Field slug tale
	 * 
	 * @return string Full field slug
	 */
	private function format_field_slug ( string $page_slug_tale, string $field_slug_tale ) : string {
		return $this->format_page_slug( $page_slug_tale ) . "-{$field_slug_tale}-field";
	}
}

/**
 * Returns YM Fast Options option value
 * 
 * @param string $page          Option page slug
 * @param string $option        Option slug
 * @param mixed  $default_value Default value if option is not exists
 * 
 * @return mixed Option value or default value
 */
function ymfo_get_option ( string $page, string $option, mixed $default_value = false ) : mixed {
    return get_option( "ymfo-{$page}-{$option}-field", $default_value );
}