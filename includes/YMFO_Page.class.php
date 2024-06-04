<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** YM Fast Options page class */
class YMFO_Page {
	/**
	 * Main part of page slug.
	 * 
	 * @var string
	 */
    private string $page_slug_tale;

	/**
	 * Page slug.
	 * 
	 * @var string
	 */
    private string $page_slug;

	/**
	 * Page title.
	 * 
	 * @var string
	 */
    private string $page_title;

	/**
	 * Page arguments.
	 * 
	 * @var array
	 */
    private array  $page_args;

    /**
     * Available option field types.
	 * 
	 * @var	string[]
     */
    private array $available_field_types = [
        'text',
		'textarea',			// Since 1.0.1
		'number',			// Since 1.0.4
		'select',			// Since 1.0.6
		'checkbox',			// Since 1.0.6
		'radio',			// Since 1.0.9
        'tel',
		'email',
		'url',
        'date',
		'datetime-local',
		'month',
		'week',
		'time',
        'color',
    ];

    /**
     * Adds a custom options page.
	 * 
	 * @since 1.0.3  Has `parent_page` and `callback` arguments.
	 * @since 1.0.10 Has `show_docs` argument.
     * 
     * @param string $page_title     The text to be displayed in the title tags of the page when the menu is selected.
     * @param string $page_slug_tale The tale part of slug name to refer to this menu by.
     * @param array  $page_args {
	 * 		Additional arguments
	 * 
	 * 		@type bool                  $is_top_level Set false to make menu item as submenu to Settings menu. Default true.
	 * 		@type null|string|YMFO_Page $parent_page  YMFO_Page class instance with parent page ot parent page slug. Default null.
	 * 		@type string                $menu_title   The text to be used for the menu. Default $page_title.
	 * 		@type string                $icon         The URL to the icon to be used for this menu. Default ''.
	 * 		@type int                   $position     Set false to make menu item as submenu to Settings menu. Default null.
	 * 		@type callable		        $callback     The function to be called to output the content for this page.
	 * 		@type string                $capability   The capability required for this menu to be
	 * 										          displayed to the user. Default 'manage_options'.
	 * 		@type bool                  $show_docs    Set false to hide plugin docs on this page. Default true.
	 * }
     */
    function __construct ( string $page_title, string $page_slug_tale, array $page_args = [] ) {
        // Save page data to instance
        $this->page_slug_tale = $page_slug_tale;
        $this->page_slug      = YMFO::format_page_slug( $page_slug_tale );
        $this->page_title     = $page_title;
        $this->page_args      = [
			'is_top_level' => true,
			'parent_page'  => null,

			'menu_title'   => $this->page_title,
			'icon'         => '',
			'position'     => null,

			'callback'     => function () {
                include YMFO_ROOT_DIR . 'page.php';
            },
			'capability'   => 'manage_options',

			'show_docs'    => true,

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

		// Add top page, settings page or child of parent page
		if ( $this->page_args[ 'parent_page' ] === null ) {
			if ( $this->page_args[ 'is_top_level' ] ) {
				add_action( 'admin_menu', function () use ( $add_page_args ) {
					add_menu_page( ...$add_page_args );
				});
			} else {
				// Remove icon argument value
				unset( $add_page_args[ 5 ] );
	
				add_action( 'admin_menu', function () use ( $add_page_args ) {
					add_options_page( ...$add_page_args );
				});
			}
		} else {
			// Get parent page slug
			$parent_page_slug = $this->page_args[ 'parent_page' ];

			if ( $this->page_args[ 'parent_page' ] instanceof YMFO_Page ) {
				$parent_page_slug = $this->page_args[ 'parent_page' ]->page_slug;
			}

			// Remove icon argument value
			unset( $add_page_args[ 5 ] );

			// Add parent slug argument value
			array_unshift( $add_page_args, $parent_page_slug );

			add_action( 'admin_menu', function () use ( $add_page_args ) {
				add_submenu_page( ...$add_page_args );
			});
		}
    }

    /**
     * Adds an options section.
     * 
     * @param string $section_title Section title.
     * @param string $section_slug  Section slug.
     * @param array  $section_args  {
	 * 		Additional arguments.
	 * 
	 * 		@type string $description Section description below title.
	 * }
     */
    public function add_section ( string $section_title, string $section_slug, array $section_args = [] ) : void {
		$add_settings_section_args = [
			$section_slug,              				// Section slug
            $section_title,             				// Section title
            function ( $args ) {        				// Callback
                include YMFO_ROOT_DIR . 'section.php';
            },
            $this->page_slug,           				// Page slug
            $section_args,              				// Additional arguments
		];
		add_action( 'admin_init', function () use ( $add_settings_section_args ) {
			add_settings_section( ...$add_settings_section_args );
		});
    }

    /**
     * Adds an option field.
	 * 
	 * @since 1.0.2 Has `placeholder` optional argument.
	 * @since 1.0.4 Has `min`, `max`, `step` optional arguments.
	 * @since 1.0.6 Has `options` optional argument.
	 * @since 2.0.0 Has `default` optional argument
     * 
     * @param string $field_title     Field title.
     * @param string $field_slug_tale Field slug.
     * @param string $field_type      Field type.
     * @param string $field_section   Field section.
     * @param array  $field_args {
	 * 		Additional arguments.
	 * 
	 * 		@type string $description Field description below input.
	 * 		@type string $placeholder Input placeholder text. Default empty.
	 * 		@type string $default     Default field value.
	 * 		@type bool   $required    Is field required. Default false.
	 * 
	 * 		# For number fields type
	 * 
	 * 		@type float|int $min  Minimum value.
	 * 		@type float|int $max  Maximum value.
	 * 		@type float|int $step Step of value change by arrows.
	 * 
	 * 		# For select and radio field types
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
        
        // Get full field slug
        $field_slug = YMFO::format_field_slug( $this->page_slug_tale, $field_slug_tale );

		// Register setting
		$register_setting_args = [
			$this->page_slug,
			$field_slug,
		];
        add_action( 'init', function () use ( $register_setting_args, $field_slug, $field_args ) {
			register_setting( ...$register_setting_args );

			// Set default option
			$current_option_value = get_option( $field_slug );

			if ( isset( $field_args[ 'default' ] ) ) {
				if ( empty( $current_option_value ) && $current_option_value !== '0' ) {
					update_option( $field_slug, $field_args[ 'default' ] );
				}
			}
		});

		// Add field
		$add_settings_field_args = [
			$field_slug,                				// Field slug
            $field_title,								// Field title
            function ( $args ) {        				// Callback
                include YMFO_ROOT_DIR . 'field.php';
            },
            $this->page_slug,           				// Page slug
            $field_section,             				// Section slug
            [											// Arguments
                ...$field_args,

                'field_type'        => $field_type,

				'field_title'       => $field_title,

                'field_name'        => $field_slug,
                'field_id'          => $field_slug,
                'label_for'         => $field_slug,

				'is_field_required' => isset( $field_args[ 'required' ] ) && $field_args[ 'required' ] == true,

                'page_slug_tale'    => $this->page_slug_tale,
                'field_slug_tale'   => $field_slug_tale,
            ],
		];
        add_action( 'admin_init', function () use ( $add_settings_field_args ) {
			add_settings_field( ...$add_settings_field_args );
		});
    }
}