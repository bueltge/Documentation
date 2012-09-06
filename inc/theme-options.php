<?php
/**
 * Documentation Theme Options
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      08/09/2012
 */

class Documentation_Options {
	
	/**
	 * Identifier, namespace
	 */
	public static $theme_key = '';
	
	/**
	 * The option value in the database will be based on get_stylesheet()
	 * so child themes don't share the parent theme's option value.
	 */
	public static $option_key = '';
	
	/**
	 * Initialize our options.
	 */	
	var $options = array();
	
	/**
	 * Init
	 */
	public function __construct( $args = NULL ) {
		
		// Set option key based on get_stylesheet()
		if ( NULL === $args )
			$args['theme_key'] = strtolower( get_stylesheet() );
		
		// Set option key based on get_stylesheet()
		$this->theme_key  = $args['theme_key'];
		$this->option_key = $this->theme_key . '_theme_options';
		
		add_action( 'admin_init', array( $this, 'options_init' ) );
		add_action( 'admin_menu', array( $this, 'add_page'     ) );
	}

	/**
	 * Register the form setting for our options array.
	 *
	 * This function is attached to the admin_init action hook.
	 *
	 * This call to register_setting() registers a validation callback, validate(),
	 * which is used when the option is saved, to ensure that our option values are properly
	 * formatted, and safe.
	 */
	public function options_init() {
		
		// Load our options for use in any method.
		$this->options = $this->get_theme_options();
		
		// Register our option group.
		register_setting(
			$this->theme_key . '_options',    // Options group, see settings_fields() call in render_page()
			$this->option_key,          // Database option, see get_theme_options()
			array( $this, 'validate' )  // The sanitization callback, see validate()
		);
		
		// Register our settings field group.
		add_settings_section(
			'general',        // Unique identifier for the settings section
			'',               // Section title (we don't want one)
			'__return_FALSE', // Section callback (we don't want anything)
			'theme_options'   // Menu slug, used to uniquely identify the page; see add_page()
		);
		
		// Register our individual settings fields.
		add_settings_field(
			'rewrite_url',                                 // Unique identifier for the field for this section
			__( 'Rewrite URL', 'documentation' ),          // Setting field label
			array( $this, 'settings_field_enable_fonts' ), // Function that renders the settings field
			'theme_options',                               // Menu slug, used to uniquely identify the page; see add_page()
			'general'                                      // Settings section. Same as the first argument in the add_settings_section() above
		);
		
		// Register our individual settings fields for color
		add_settings_field(
			'text_color',                                 // Unique identifier for the field for this section
			__( 'Text color', 'documentation' ),          // Setting field label
			array( $this, 'settings_field_text_color' ), // Function that renders the settings field
			'theme_options',                               // Menu slug, used to uniquely identify the page; see add_page()
			'general'                                      // Settings section. Same as the first argument in the add_settings_section() above
		);
	}
	
	/**
	 * Add our theme options page to the admin menu.
	 *
	 * This function is attached to the admin_menu action hook.
	 * 
	 * @return   void
	 */
	public function add_page() {
		
		$theme_page = add_theme_page(
			__( 'Theme Options', 'documentation' ), // Name of page
			__( 'Theme Options', 'documentation' ), // Label in menu
			'edit_theme_options',                  // Capability required
			'theme_options',                       // Menu slug, used to uniquely identify the page
			array( $this, 'render_page' )          // Function that renders the options page
		);
		
		if ( ! $theme_page )
			return;
		
		add_action( 'admin_print_scripts-' . $theme_page, array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'load-' . $theme_page, array( $this, 'theme_options_help' ) );
	}
	
	/**
	 * Properly enqueue styles and scripts for our theme options page.
	 * 
	 * @return   void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		
		// enqueue styles
		wp_register_style(
			$this->theme_key . '-theme-options',
			get_template_directory_uri() . '/inc/theme-options.css',
			FALSE,
			FALSE
		);
		wp_enqueue_style( $this->theme_key . '-theme-options' );
		wp_enqueue_style( 'farbtastic' );
		
		// enqueue scripts
		wp_register_script(
			$this->theme_key . '-theme-options',
			get_template_directory_uri() . '/inc/theme-options.js',
			array( 'farbtastic' ),
			FALSE
		);
		wp_enqueue_script( $this->theme_key . '-theme-options' );
	}
	
	/**
	 * Return the content for help area
	 * 
	 * @since    09/06/2012
	 * @return   void
	 */
	public function theme_options_help() {
		
		$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme provides the following Theme Options:', 'documentation' ) . '</p>' .
			'<ol>' . 
				'<li>' . __( '<strong>Rewrite URL</strong>: ', 'documentation' ) . '</li>' .
				'<li>' . __( '<strong>Text Color</strong>: ', 'documentation' ) . '</li>' .
				'<li>' . __( '<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'documentation' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'twentyeleven' ) . '</p>';
		
		$sidebar = '<p><strong>' . __( 'For more information:', 'documentation' ) . '</strong></p>';
		
		$screen = get_current_screen();
		
		if ( method_exists( $screen, 'add_help_tab' ) ) {
			// WordPress 3.3
			$screen->add_help_tab( array(
				'title'   => __( 'Overview', 'documentation' ),
				'id'      => 'theme-options-help',
				'content' => $help,
				)
			);
	
			$screen->set_help_sidebar( $sidebar );
		} else {
			// WordPress 3.2
			add_contextual_help( $screen, $help . $sidebar );
		}
	}
	
	/**
	 * Returns the default options.
	 * Use the hook 'documentation_default_theme_options' for change via plugin
	 * 
	 * @return   Array
	 */
	public function get_default_theme_options( $value = NULL ) {
		
		$default_theme_options = array(
			'rewrite_url' => 'wp-admin/edit.php',
			'text_color'  => '#333'
		);
		
		if ( NULL !== $value )
			return $default_theme_options[$value];
		
		return apply_filters( $this->theme_key . '_default_theme_options', $default_theme_options );
	}
	
	/**
	 * Returns the options array.
	 * 
	 * @return   Array
	 */
	public function get_theme_options() {
		
		$saved = (array) get_option( $this->option_key );
		$defaults = $this->get_default_theme_options();
		
		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );
		
		return $options;
	}
	
	/**
	 * Renders the enable fonts checkbox setting field.
	 */
	public function settings_field_enable_fonts() {
		
		$options = $this->options; ?>
		<label for="enable-fonts"> 
			<input type="text" name="<?php echo $this->option_key; ?>[rewrite_url]" id="rewrite-url" value="<?php echo $options['rewrite_url']; ?>" class="regular-text code" />
			<br /><span class="description"><?php printf( __( 'Edit an URL in Backend for the Administration Link in Frontend. Example: %s', 'documentation' ), '<code>wp-admin/edit.php</code>' ); ?> </span>
		</label>
	<?php
	}
	
	/**
	 * Render text field for text color
	 * 
	 * @since    09/05/2012
	 * @return   void
	 */
	public function settings_field_text_color() {
		
		$options = $this->options;
		?>
		<label for="text-color">
			<input type="text" name="<?php echo $this->option_key; ?>[text_color]" id="text-color" value="<?php echo $options['text_color']; ?>" />
			<a href="#" class="pickcolor hide-if-no-js" id="text-color-example"></a>
			<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'documentation' ); ?>" />
			<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
			<br />
			<span class="description"><?php printf( 
				__( 'Fill with an hex code for the text color. Default color: %s', 'documentation' ),
				'<code id="default-color">' . $this->get_default_theme_options( 'text_color' ) . '</code>'
			); ?> </span>
		</label>
	<?php
	}
	
	/**
	 * Returns the options array.
	 *
	 * @uses get_current_theme() for back compat, fallback for < 3.4
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
			<h2><?php printf( __( '%s Theme Options', 'documentation' ), $theme_name ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( $this->theme_key . '_options' );
					do_settings_sections( 'theme_options' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}
	
	/**
	 * Sanitize and validate form input. Accepts an array, return a sanitized array.
	 *
	 * @see options_init()
	 */
	public function validate( $input ) {
		
		$output = $defaults = $this->get_default_theme_options();

		// filter html for the field
		if ( isset( $input['rewrite_url'] ) && ! empty( $input['rewrite_url'] ) )
			$output['rewrite_url'] = wp_filter_nohtml_kses( $input['rewrite_url'] );
		
		if ( isset( $input['text_color'] ) && ! empty( $input['text_color'] ) )
			$output['text_color'] = wp_filter_nohtml_kses( $input['text_color'] );
		
		return apply_filters( $this->theme_key . '_options_validate', $output, $input, $defaults );
	}
	
} // end class