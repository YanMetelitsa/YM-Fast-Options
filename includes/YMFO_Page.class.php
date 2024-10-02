<?php

// Exits if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * YM Fast Options page class.
 */
class YMFO_Page {
	/**
	 * Main part of page slug.
	 * 
	 * @var string
	 */
	public string $page_slug_tale;

	/**
	 * Page slug.
	 * 
	 * @var string
	 */
	public string $page_slug;

	/**
	 * Page title.
	 * 
	 * @var string
	 */
	public string $page_title;

	/**
	 * Page arguments.
	 * 
	 * @var array
	 */
	public array  $page_args;

	/**
	 * Available option field types.
	 * 
	 * @var	string[]
	 */
	public array $available_field_types = [
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
	 * @since 2.0.2  `show_docs` argument removed.
	 * @since 2.0.6  Has `has_separator` argument.
	 * @since 2.1.0  Has `in_network` argument.
	 * 
	 * @param string $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string $page_slug_tale The tale part of slug name to refer to this menu by.
	 * @param array  $page_args {
	 * 		Additional arguments
	 * 
	 * 		@type bool                  $in_network    If true, the page will be created in multisite network. Default false.
	 * 		@type bool                  $has_separator If true, new separator will be displayed above the menu item. Default false.
	 * 		@type null|string|YMFO_Page $parent_page   YMFO_Page class instance with parent page ot parent page slug. Default null.
	 * 		@type bool                  $is_top_level  Set false to make menu item as submenu to Settings menu. Default true.
	 * 		@type int                   $position      Set false to make menu item as submenu to Settings menu. Default null.
	 * 		@type string                $icon          The URL to the icon to be used for this menu. Default ''.
	 * 		@type string                $menu_title    The text to be used for the menu. Default $page_title.
	 * 		@type callable		        $callback      The function to be called to output the content for this page.
	 * 		@type string                $capability    The capability required for this menu to be
	 * 										           displayed to the user. Default 'manage_options'.
	 * }
	 */
	function __construct ( string $page_title, string $page_slug_tale, array $page_args = [] ) {
		// Sуеы page data to instance.
		$this->page_slug_tale = $page_slug_tale;
		$this->page_slug      = YMFO::format_page_slug( $page_slug_tale );
		$this->page_title     = $page_title;
		$this->page_args      = [
			'in_network'    => false,

			'has_separator' => false,

			'parent_page'   => null,
			'is_top_level'  => true,

			'position'      => null,
			'icon'          => '',
			'menu_title'    => $this->page_title,

			'callback'      => function () {
				include YMFO_ROOT_DIR . 'page.php';
			},
			'capability'    => 'manage_options',

			...$page_args,
		];

		// Check parent page.
		if ( $this->page_args[ 'parent_page' ] instanceof YMFO_Page ) {
			// Sets `in_network` argument the same as parent's.
			$this->page_args[ 'in_network' ] = $this->page_args[ 'parent_page' ]->page_args[ 'in_network' ];

			// Resets parent_page as string (slug).
			$this->page_args[ 'parent_page' ] = $this->page_args[ 'parent_page' ]->page_slug;
		}

		// Is network.
		if ( ! is_multisite() ) {
			$this->page_args[ 'in_network' ] = false;
		}

		$admin_menu_action_tag = 'admin_menu';

		if ( $this->page_args[ 'in_network' ] ) {
			$admin_menu_action_tag = 'network_admin_menu';

			add_action( 'admin_init', function () {
				add_action( "network_admin_edit_{$this->page_slug}", function () {
					add_settings_error( $this->page_slug, 'settings_updated', __( 'Settings saved.' ), 'success' );

					check_admin_referer( 'ymfo_network_nonce' );

					foreach ( $_POST as $label => $value ) {
						if ( str_contains( $label, 'ymfo-' ) ) {
							update_site_option( $label, $value );
						}
					}

					if ( isset( $_POST[ '_wp_http_referer' ] ) ) {
						wp_redirect( wp_unslash( $_POST[ '_wp_http_referer' ] ) );
					}

					exit;
				});
			});
		}

		// Sets page arguments.
		$add_page_args = [
			$this->page_title,					// Page title.
			$this->page_args[ 'menu_title' ],	// Menu title.
			$this->page_args[ 'capability' ],	// Capability.
			$this->page_slug,					// Slug.
			$this->page_args[ 'callback' ],		// Callback.
			$this->page_args[ 'icon' ],			// Icon.
			$this->page_args[ 'position' ],		// Position.
		];

		// Adds as top=level page or child page.
		if ( is_null( $this->page_args[ 'parent_page' ] ) ) {
			// Adds as top level or as options page.
			if ( $this->page_args[ 'is_top_level' ] ) {
				add_action( $admin_menu_action_tag, function () use ( $add_page_args ) {
					// Adds separator.
					if ( $this->page_args[ 'has_separator' ] ) {
						add_menu_page(
							'', '', $add_page_args[ 2 ],
							' wp-menu-separator ymfo-menu-separator',
							'', '', $add_page_args[ 6 ]
						);
					}

					add_menu_page( ...$add_page_args );
				});
			} else {
				// Removes icon argument value.
				unset( $add_page_args[ 5 ] );

				// Is network.
				if ( $this->page_args[ 'in_network' ] ) {
					// Adds parent slug argument value.
					array_unshift( $add_page_args, 'settings.php' );

					add_action( $admin_menu_action_tag, function () use ( $add_page_args ) {
						add_submenu_page( ...$add_page_args );
					});
				} else {
					add_action( $admin_menu_action_tag, function () use ( $add_page_args ) {
						add_options_page( ...$add_page_args );
					});
				}
			}
		} else {
			// Removes icon argument value.
			unset( $add_page_args[ 5 ] );

			// Adds parent slug argument value.
			array_unshift( $add_page_args, $this->page_args[ 'parent_page' ] );

			add_action( $admin_menu_action_tag, function () use ( $add_page_args ) {
				add_submenu_page( ...$add_page_args );
			});
		}

		// Adds to YMFO pages archive data.
		YMFO::$pages[ $this->page_slug_tale ] = $this;

		// Prints `help` tab.
		add_action( 'admin_head', function () {
			$current_screen = get_current_screen();

			if ( str_contains( $current_screen->base, $this->page_slug ) ) {
				ob_start();
				
				?>
					<h3><?php esc_html_e( 'How to get option value', 'ym-fast-options' ) ?></h3>
					<p><?php esc_html_e( 'To get option value you can use function in your theme, or shortcode in posts content.', 'ym-fast-options' ); ?></p>
					<p><?php echo wp_kses_post( __( 'In both cases, you\'ll need a <code>label</code> value – you will be able to find it under each field.', 'ym-fast-options' ) ); ?></p>

					<h3><?php esc_html_e( 'Function', 'ym-fast-options' ) ?></h3>
					<p><code class="ymfo-copyable">ymfo_get_option( '<?php echo esc_attr( $this->page_slug_tale ); ?>', 'label' )</code></p>

					<h3><?php esc_html_e( 'Shortcode', 'ym-fast-options' ) ?></h3>
					<p><code class="ymfo-copyable">[ymfo page="<?php echo esc_attr( $this->page_slug_tale ); ?>" option="label"]</code></p>
				<?php

				$how_to_use_content = ob_get_clean();

				$current_screen->add_help_tab([
					'title'    => __( 'How to use', 'ym-fast-options' ),
					'id'       => 'ymfo-how-to-use',
					'content'  => $how_to_use_content,
				]);
			}
		});
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
			$section_slug,								// Section slug.
			$section_title,								// Section title.
			function ( $args ) {					// Callback.
				include YMFO_ROOT_DIR . 'section.php';
			},
			$this->page_slug,							// Page slug.
			$section_args,								// Additional arguments.
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
	 * @param string $field_type      Field type. Allowed types:
	 *                                - 'text'
	 *                                - 'textarea'
	 *                                - 'number'
	 *                                - 'select'
	 *                                - 'checkbox'
	 *                                - 'radio'
	 *                                - 'tel'
	 *                                - 'email'
	 *                                - 'url'
	 *                                - 'date'
	 *                                - 'datetime-local'
	 *                                - 'month'
	 *                                - 'week'
	 *                                - 'time'
	 *                                - 'color'
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
		// Checks is field type allowed.
		if ( !in_array( $field_type, $this->available_field_types ) ) {
			return;
		}
		
		// Gets full field slug.
		$field_slug = YMFO::format_field_slug( $this->page_slug_tale, $field_slug_tale );
		
		// Registers setting.
		$page_slug_tale = $this->page_slug_tale;
		$register_setting_args = [
			$this->page_slug,
			$field_slug,
		];
		add_action( 'init', function () use ( $register_setting_args, $page_slug_tale, $field_slug_tale, $field_type, $field_args ) {
			register_setting( ...$register_setting_args );

			// Sets default value.
			$current_option_value   = ymfo_get_option( $page_slug_tale, $field_slug_tale );
			$is_current_value_empty = empty( $current_option_value ) && $current_option_value !== '0';
			$is_option_exists       = ymfo_is_option_exists( $page_slug_tale, $field_slug_tale );

			// If checkbox and exists - not update.
			if ( $field_type == 'checkbox' && $is_option_exists ) {
				$is_current_value_empty = false; 
			}

			// Updates option value.
			if ( isset( $field_args[ 'default' ] ) && $is_current_value_empty ) {
				ymfo_update_option( $page_slug_tale, $field_slug_tale, $field_args[ 'default' ] );
			}
		});

		// Adds option field.
		$add_settings_field_args = [
			$field_slug,								// Field slug.
			$field_title,								// Field title.
			function ( $args ) {					// Callback.
				include YMFO_ROOT_DIR . 'field.php';
			},
			$this->page_slug,							// Page slug.
			$field_section,								// Section slug.
			[											// Arguments.
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