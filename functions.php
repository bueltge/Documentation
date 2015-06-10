<?php
/**
 * Functions and definitions
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-03-16
 */

if ( ! function_exists( 'documentation_setup' ) ) {

	add_action( 'after_setup_theme', 'documentation_setup' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function documentation_setup() {

		/**
		 * Post content often has specified width.
		 * Thus, images which has bigger size than content width can break up the layout of theme.
		 * To prevent this situation, WordPress provides one way to specify the maximum image size for themes.
		 * Define the maximum image size
		 */
		if ( ! isset( $GLOBALS[ 'content_width' ] ) ) {
			$GLOBALS[ 'content_width' ] = 900;
		}

		/**
		 * Make Documentation available for translation.
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Documentation, use a find and replace
		 * to change 'documentation' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'documentation', get_template_directory() . '/languages' );

		/**
		 * Login + Admin Bar Branding
		 *
		 * @see  https://github.com/bueltge/WordPress-Basis-Theme/tree/namespace/inc/admin
		 */
		require_once( get_template_directory() . '/inc/class-branding.php' );
		new Documentation_Admin_Branding( array() );

		/**
		 * Add support for Theme Customizer
		 *
		 * @since  09/06/2012
		 */
		add_theme_support( 'documentation_customizer', array( 'all' ) );
		// Include the theme customizer for options of theme options, if theme supported
		require_if_theme_supports( 'documentation_customizer', get_template_directory() . '/inc/theme-customize.php' );

		/**
		 * Add support for custom style to write in head
		 *
		 * @since  09/06/2012
		 */
		// include to write the custom theme options in theme head
		require_if_theme_supports( 'documentation_customizer', get_template_directory() . '/inc/head-style.php' );

		/**
		 * Custom template tags for this theme.
		 *
		 * @since  2015-06-10
		 */
		require get_template_directory() . '/inc/template-tags.php';

		/**
		 * Custom functions, that hook in core for this theme.
		 *
		 * @since  2015-06-10
		 */
		require get_template_directory() . '/inc/extras.php';

		/**
		 * Widget areas in the sidebar and his markup.
		 *
		 * @since  2015-06-10
		 */
		require get_template_directory() . '/inc/widgets.php';

		/**
		 * Custom comment callback function.
		 *
		 * @since  2015-06-10
		 */
		require get_template_directory() . '/inc/comments.php';

		/**
		 * Add support for the hook alliance
		 *
		 * @see    https://github.com/zamoose/themehookalliance
		 * @since  2012-02-10
		 */
		add_theme_support( 'tha_hooks', array( 'all' ) );
		// include the file from this project, if theme supported
		if ( file_exists( get_template_directory() . '/inc/tha/tha-theme-hooks.php' ) ) {
			require_if_theme_supports( 'tha_hooks', get_template_directory() . '/inc/tha/tha-theme-hooks.php' );
		} else {
			require_if_theme_supports( 'tha_hooks', get_template_directory() . '/inc/tha-1.0/tha-theme-hooks.php' );
		}

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', esc_attr__( 'Primary Menu', 'documentation' ) );

		// Add support for custom background.
		$args = array(
			'default-image'          => '',
			'default-color'          => 'fff',
		);
		add_theme_support( 'custom-background', apply_filters( 'documentation_custom_background_args', $args ) );

		// define suffix for development on scripts and styles
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style( 'css/editor-style' . $suffix . '.css' );
	}

} // end if func exists

if ( ! function_exists( 'documentation_get_options' ) ) {

	/**
	 * Return options value or array of all values
	 * Small wrapper for the class
	 *
	 * @since   08/09/2012
	 *
	 * @param string $value
	 *
	 * @return String , Array  Value of the options item
	 */
	function documentation_get_options( $value = '' ) {

		// failed to load file for options, then return NULL
		if ( ! class_exists( 'Documentation_Customize' ) ) {
			return NULL;
		}

		$documentation_options = new Documentation_Customize();

		$options = $documentation_options->get_theme_options();

		if ( ! empty( $value ) ) {
			$options = $options[ $value ];
		}

		return $options;
	}

} // end if func exists

if ( ! function_exists( 'documentation_scripts_styles' ) ) {

	add_action( 'wp_enqueue_scripts', 'documentation_scripts_styles' );
	/**
	 * Enqueue scripts and styles for front-end.
	 *
	 * @since 2.0
	 */
	function documentation_scripts_styles() {

		// set suffix for debug mode
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		/**
		 * Add JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Register responsive table script
		// Kudos to Responsive Tables project
		// @see  http://www.zurb.com/playground/responsive-tables
		wp_register_script(
			'documentation-responsive-tables',
			get_template_directory_uri() . '/js/responsive-tables' . $suffix . '.js',
			array( 'jquery' ),
			'01/14/2013',
			TRUE
		);
		// Register responsive table style
		wp_register_style(
			'documentation-responsive-tables',
			get_template_directory_uri() . '/css/responsive-tables' . $suffix . '.css',
			array(),
			'01/14/2013',
			'screen'
		);

		// Register main style
		wp_register_style( 'documentation-style', get_stylesheet_directory_uri() . '/css/style' . $suffix . '.css' );
		// Register print CSS file
		wp_register_style(
			'documentation-print-style',
			get_stylesheet_directory_uri() . '/css/print' . $suffix . '.css',
			array(),
			FALSE,
			'print'
		);

		wp_enqueue_style( 'documentation-style' );
		wp_enqueue_style( 'documentation-print-style' );

		wp_enqueue_script( 'documentation-responsive-tables' );
		wp_enqueue_style( 'documentation-responsive-tables' );
	}

} // end if func exists