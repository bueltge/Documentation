<?php

/**
 * Documentation Theme Options in Theme Customizer
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      2012-09-06
 * @version    2015-03-16
 */
class Documentation_Customize {

	/**
	 * Identifier, namespace
	 */
	protected $theme_key = '';

	/**
	 * The option value in the database will be based on get_stylesheet()
	 * so child themes don't share the parent theme's option value.
	 */
	protected $option_key = '';

	/**
	 * Initialize
	 *
	 * @param null $args
	 */
	public function __construct( $args = NULL ) {

		// Set option key based on get_stylesheet()
		if ( NULL === $args ) {
			$args[ 'theme_key' ] = strtolower( get_stylesheet() );
		}

		// Set option key based on get_stylesheet()
		$this->theme_key  = $args[ 'theme_key' ];
		$this->option_key = $this->theme_key . '_theme_options';

		// register our custom settings
		add_action( 'customize_register', array( $this, 'customize_register' ) );

		// Scripts for Preview
		add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
	}

	/**
	 * Returns the default options.
	 * Use the hook 'documentation_default_theme_options' for change via plugin
	 *
	 * @since    08/09/2012
	 *
	 * @param null $value
	 *
	 * @return Array
	 */
	public function get_default_theme_options( $value = NULL ) {

		$default_theme_options = array(
			'echo_desc'    => '1',
			'layout'       => 'sidebar-right',
			'rewrite_url'  => 'wp-admin/edit.php',
			'color_scheme' => 'light',
			'text_color'   => '#111',
			'link_color'   => '#0100BE'
		);

		if ( NULL !== $value ) {
			return $default_theme_options[ $value ];
		}

		return apply_filters( $this->theme_key . '_default_theme_options', $default_theme_options );
	}

	/**
	 * Returns the options array.
	 *
	 * @since    08/09/2012
	 *
	 * @param null $value
	 *
	 * @return Array
	 */
	public function get_theme_options( $value = NULL ) {

		$saved    = (array) get_option( $this->option_key );
		$defaults = $this->get_default_theme_options();

		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );

		$options = apply_filters( $this->theme_key . '_theme_options', $options );

		if ( NULL !== $value ) {
			return $options[ $value ];
		}

		return $options;
	}

	/**
	 * Implement theme options into Theme Customizer on Frontend
	 *
	 * @see     examples for different input fields https://gist.github.com/2968549
	 * @since   08/09/2012
	 *
	 * @param   $wp_customize  Theme Customizer object
	 *
	 * @return  void
	 */
	public function customize_register( $wp_customize ) {

		$defaults = $this->get_default_theme_options();

		// defaults, import for live preview with js helper
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		// Add settings for output description
		$wp_customize->add_setting( $this->option_key . '[echo_desc]', array(
			'default'    => $defaults[ 'echo_desc' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options'
		) );

		// Add control and output for select field
		$wp_customize->add_control( $this->option_key . '_echo_desc', array(
			'label'    => esc_attr__( 'Display Description', 'documentation' ),
			'section'  => 'title_tagline',
			'settings' => $this->option_key . '[echo_desc]',
			'std'      => '1',
			'type'     => 'checkbox',

		) );

		// ===== Layout Section =====
		// Option for leave sidebar left or right
		$wp_customize->add_section( $this->option_key . '_layout', array(
			'title'       => esc_attr__( 'Layout', 'documentation' ),
			'description' => esc_attr__( 'Define main Layout', 'documentation' ),
			'priority'    => 30
		) );

		// Add field for radio buttons to set layout
		$wp_customize->add_setting( $this->option_key . '[layout]', array(
			'default'    => $defaults[ 'layout' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		// Add control and output for select field
		$wp_customize->add_control( $this->option_key . '_layout', array(
			'label'    => esc_attr__( 'Color Scheme', 'documentation' ),
			'section'  => $this->option_key . '_layout',
			'settings' => $this->option_key . '[layout]',
			'type'     => 'radio',
			'choices'  => array(
				'sidebar-left'  => esc_attr__( 'Sidebar on left', 'documentation' ),
				'sidebar-right' => esc_attr__( 'Sidebar on right', 'documentation' )
			),
		) );

		// ===== Custom Section =====
		// create custom section for rewrite url
		$wp_customize->add_section( $this->option_key . '_rewrite_url', array(
			'title'    => esc_attr__( 'Rewrite', 'documentation' ),
			'priority' => 35,
		) );

		// ===== Text Input Field =====
		// add field for rewrite url in custom section
		$wp_customize->add_setting( $this->option_key . '[rewrite_url]', array(
			'default'    => $defaults[ 'rewrite_url' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		// ===== Textarea Field via Custom Field =====
		// !!! Current NOT use, use the textarea field, see below.
		// use the custom class for add textarea and use it on this example
		/*
		$wp_customize->add_control( $this->option_key . '_rewrite_url', array(
			'label'      =>esc_attr__( 'Rewrite URL', 'documentation' ),
			'section'    => $this->option_key . '_rewrite_url',
			'settings'   => $this->option_key . '[rewrite_url]',
			'type'       => 'text',
		) );
		*/

		// add textarea field for change the rewrite url
		$wp_customize->add_control( $this->option_key . '_rewrite_url', array(
			'label'    => esc_attr__( 'Rewrite URL', 'documentation' ),
			'section'  => $this->option_key . '_rewrite_url',
			'settings' => $this->option_key . '[rewrite_url]',
			'type'     => 'textarea',
		) );

		// ===== Sample Radio Buttons Fields =====
		// Add field for radio buttons to dark or light scheme
		$wp_customize->add_setting( $this->option_key . '[color_scheme]', array(
			'default'    => $defaults[ 'color_scheme' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		// ===== Color picker Fields =====
		// add field for text color in default section for 'colors'
		$wp_customize->add_setting( $this->option_key . '[text_color]', array(
			'default'    => $defaults[ 'text_color' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		// add color field include color picker for text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $this->option_key . '_text_color', array(
			'label'    => esc_attr__( 'Text Color', 'documentation' ),
			'section'  => 'colors',
			'settings' => $this->option_key . '[text_color]',
		) ) );

		// add field for text color in default section for 'colors'
		$wp_customize->add_setting( $this->option_key . '[link_color]', array(
			'default'    => $defaults[ 'link_color' ],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		// add color field include color picker for link color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $this->option_key . '_link_color', array(
			'label'    => esc_attr__( 'Link Color', 'documentation' ),
			'section'  => 'colors',
			'settings' => $this->option_key . '[link_color]',
		) ) );

		// Add control and output for select field
		$wp_customize->add_control( $this->option_key . '_color_scheme', array(
			'label'    => esc_attr__( 'Color Scheme', 'documentation' ),
			'section'  => 'colors',
			'settings' => $this->option_key . '[color_scheme]',
			'type'     => 'radio',
			'choices'  => array(
				'dark'  => esc_attr__( 'Dark', 'documentation' ),
				'light' => esc_attr__( 'Light', 'documentation' )
			),
		) );
	}

	/**
	 * Mp reload for changes
	 *
	 * @since    10/02/2012
	 * @return   void
	 */
	public function customize_preview_js() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		wp_register_script(
			$this->theme_key . '-customizer',
			get_template_directory_uri() . '/js/theme-customizer' . $suffix . '.js',
			array( 'customize-preview' ),
			FALSE,
			TRUE
		);

		wp_enqueue_script( $this->theme_key . '-customizer' );
	}

} // end class
$documentation_customize = new Documentation_Customize();
