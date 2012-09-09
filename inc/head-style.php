<?php
/**
 * Documentation Theme Style
 * Write style settings in head
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      09/05/2012
 */

if ( ! class_exists( 'Documentation_Options' ) )
	return NULL;

class Documentation_Head_Style extends Documentation_Options {
	
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
		
		add_action( 'wp_head', array( $this, 'get_custom_style' ) );
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
	
} // end class