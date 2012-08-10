<?php
/**
 * Documentation Theme Options
 *
 * @package WordPress
 * @subpackage Documentation
 * @since 08/09/2012
 */

class Documentation_Options {
	/**
	 * The option value in the database will be based on get_stylesheet()
	 * so child themes don't share the parent theme's option value.
	 */
	var $option_key = 'documentation_theme_options';
	
	/**
	 * Initialize our options.
	 */
	var $options = array();
	
	function documentation_Options() {
		// Set option key based on get_stylesheet()
		if ( 'documentation' != get_stylesheet() )
			$this->option_key = get_stylesheet() . '_theme_options';

		add_action( 'admin_init',         array( $this, 'options_init'       ) );
		add_action( 'admin_menu',         array( $this, 'add_page'           ) );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
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
	function options_init() {
		// Load our options for use in any method.
		$this->options = $this->get_theme_options();

		// Register our option group.
		register_setting(
			'documentation_options',   // Options group, see settings_fields() call in render_page()
			$this->option_key,         // Database option, see get_theme_options()
			array( $this, 'validate' ) // The sanitization callback, see validate()
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
			'rewrite_url',                                // Unique identifier for the field for this section
			__( 'Rewrite URL', 'documentation' ),          // Setting field label
			array( $this, 'settings_field_rewrite_url' ), // Function that renders the settings field
			'theme_options',                               // Menu slug, used to uniquely identify the page; see add_page()
			'general'                                      // Settings section. Same as the first argument in the add_settings_section() above
		);
	}

	/**
	 * Add our theme options page to the admin menu.
	 *
	 * This function is attached to the admin_menu action hook.
	 */
	function add_page() {
		
		$theme_page = add_theme_page(
			__( 'Theme Options', 'documentation' ), // Name of page
			__( 'Theme Options', 'documentation' ), // Label in menu
			'edit_theme_options',                   // Capability required
			'theme_options',                        // Menu slug, used to uniquely identify the page
			array( $this, 'render_page' )           // Function that renders the options page
		);
	}
	
	/**
	 * Returns the default options.
	 * 
	 * @return  Array
	 */
	function get_default_theme_options() {
		
		$default_theme_options = array(
			'rewrite_url' => 'wp-admin/edit.php',
		);
		
		return apply_filters( 'documentation_default_theme_options', $default_theme_options );
	}
	
	/**
	 * Returns the options array.
	 * 
	 * @return  String
	 */
	function get_theme_options() {
		
		return get_option( $this->option_key, $this->get_default_theme_options() );
	}

	/**
	 * Renders the input field
	 * 
	 * @return  void
	 */
	function settings_field_rewrite_url() {
		
		$options = $this->options; ?>
		<label for="enable-fonts"> 
			<input type="text" name="<?php echo $this->option_key; ?>[rewrite_url]" id="rewrite-url" value="<?php echo $options['rewrite_url']; ?>" class="regular-text code" />
			<br /><span class="description"><?php printf( __( 'Edit an URL in Backend for the Administration Link in Frontend. Example: %s', 'documentation' ), '<code>wp-admin/edit.php</code>' ); ?> </span>
		</label>
	<?php }
	
	/**
	 * Returns the options array.
	 *
	 * @uses get_current_theme() for back compat, fallback for < 3.4
	 */
	function render_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
			<h2><?php printf( __( '%s Theme Options', 'documentation' ), $theme_name ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'documentation_options' );
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
	function validate( $input ) {
		
		$output = $defaults = $this->get_default_theme_options();

		// The enable fonts checkbox should boolean TRUE or FALSE
		if ( ! isset( $input['rewrite_url'] ) )
			$input['rewrite_url'] = FALSE;
		
		$output['rewrite_url'] = trim( esc_attr( $input['rewrite_url'] ) );
		
		return apply_filters( 'documentation_options_validate', $output, $input, $defaults );
	}

	/**
	 * Implement theme options into Theme Customizer
	 *
	 * @param   $wp_customize Theme Customizer object
	 * @return  void
	 *
	 * @since 08/09/2012
	 */
	function customize_register( $wp_customize ) {
		
		$defaults = $this->get_default_theme_options();
		
		$wp_customize->add_section( $this->option_key . '_rewrite_url', array(
			'title'    => __( 'Rewrite', 'documentation' ),
			'priority' => 35,
		) );
		
		$wp_customize->add_setting( $this->option_key . '[rewrite_url]', array(
			'default'    => $defaults['rewrite_url'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );
		
		$wp_customize->add_control( $this->option_key . '_rewrite_url', array(
			'label'    => __( 'Rewrite URL', 'documentation' ),
			'section'  => $this->option_key . '_rewrite_url',
			'settings' => $this->option_key . '[rewrite_url]',
			'type'     => 'text',
		) );
	}
	
} // end class