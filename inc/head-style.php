<?php
/**
 * Documentation Theme Style
 * Write style settings in head
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      09/05/2012
 */

// extends the class with the seetings via Customizer
class Documentation_Head_Style extends Documentation_Customize {
	
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
	 * Constructor
	 * 
	 * @since   09/07/2012
	 * @param   $args   Array
	 * @return  void
	 */
	public function __construct( $args = NULL ) {
		
		if ( is_admin() )
			return NULL;
		
		// Set option key based on get_stylesheet()
		if ( NULL === $args )
			$args['theme_key'] = strtolower( get_stylesheet() );
		
		// Set option key based on get_stylesheet()
		$this->theme_key  = $args['theme_key'];
		$this->option_key = $this->theme_key . '_theme_options';
		
		// add the custom styles in head
		add_action( 'wp_head', array( $this, 'get_custom_style' ) );
		// enqueue the the different color scheme
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_color_scheme' ) );
	}
		
	/**
	 * Styles from theme options
	 * Write in head of frontend
	 * 
	 * @since    09/07/2012
	 * @return   void
	 */
	public function get_custom_style() {
		
		$options = parent::get_theme_options();
		?>
		<style type="text/css">
			body { color: <?php echo $options['text_color']; ?>; }
			a:link, a:visited, 
			#content h2 a:link, #content h2 a:visited, 
			#header h1 a:link, #header h1 a:visited { color: <?php echo $options['link_color']; ?>; }
		</style>
		<?php
	}
	
	/**
	 * Enqueue the color scheme
	 * 
	 * @since    09/18/2012
	 * @return   void
	 */
	public function enqueue_color_scheme() {
		
		// get the option value for item 'color_scheme'
		$option = parent::get_theme_options( 'color_scheme' );
		// define suffix for development on scripts and styles
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		// register the styles; easy to change from outside the theme
		wp_register_style(
			$this->theme_key . '_dark',
			get_stylesheet_directory_uri() . '/css/dark' . $suffix . '.css',
			array(),
			NULL
		);
		wp_register_style(
			$this->theme_key . '_light',
			get_stylesheet_directory_uri() . '/css/light' . $suffix . '.css',
			array(),
			NULL
		);
		// check options and enqueue style
		if ( 'dark' === $option )
			wp_enqueue_style( $this->theme_key . '_dark' );
		else
			wp_enqueue_style( $this->theme_key . '_light' );
		// use this hook for add to the set option
		do_action( $this->theme_key . '_enqueue_color_scheme', $option );
	}
	
} // end class