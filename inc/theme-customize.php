<?php
/**
 * Documentation Theme Options in Theme Customizer
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      09/06/2012
 */

if ( ! class_exists( 'Documentation_Options' ) )
	return NULL;

class Documentation_Customize extends Documentation_Options {
	
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
	 * Initialize
	 */
	public function __construct( $args = NULL ) {
		
		// Set option key based on get_stylesheet()
		if ( NULL === $args )
			$args['theme_key'] = strtolower( get_stylesheet() );
		
		// Set option key based on get_stylesheet()
		$this->theme_key  = $args['theme_key'];
		$this->option_key = $this->theme_key . '_theme_options';
		
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	/**
	 * Implement theme options into Theme Customizer on Frontend
	 * 
	 * @see     examples for different input fields https://gist.github.com/2968549
	 * @since   08/09/2012
	 * @param   $wp_customize  Theme Customizer object
	 * @return  void
	 */
	public function customize_register( $wp_customize ) {
		
		$defaults = parent::get_default_theme_options();
		
		// create custom section for rewrite url
		$wp_customize->add_section( $this->option_key . '_rewrite_url', array(
			'title'    => __( 'Rewrite', 'documentation' ),
			'priority' => 35,
		) );
		
		// add field for rewrite url in custom section
		$wp_customize->add_setting( $this->option_key . '[rewrite_url]', array(
			'default'    => $defaults['rewrite_url'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );
		
		$wp_customize->add_control( $this->option_key . '_rewrite_url', array(
			'label'      => __( 'Rewrite URL', 'documentation' ),
			'section'    => $this->option_key . '_rewrite_url',
			'settings'   => $this->option_key . '[rewrite_url]',
			'type'       => 'text',
		) );
		
		// add field for text color in default section for 'colors'
		$wp_customize->add_setting( $this->option_key . '[text_color]', array(
			'default'    => $defaults['text_color'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );
		
		// add color field include color picker for text color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $this->option_key . '_text_color', array(
			'label'      => __( 'Text Color', 'documentation' ),
			'section'    => 'colors',
			'settings'   => $this->option_key . '[text_color]',
		) ) );
		
		// add field for text color in default section for 'colors'
		$wp_customize->add_setting( $this->option_key . '[link_color]', array(
			'default'    => $defaults['link_color'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );
		
		// add color field include color picker for link color
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $this->option_key . '_link_color', array(
			'label'      => __( 'Link Color', 'documentation' ),
			'section'    => 'colors',
			'settings'   => $this->option_key . '[link_color]',
		) ) );
		
	}

} // end class