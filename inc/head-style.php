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
	private static $theme_key = '';
	
	/**
	 * The option value in the database will be based on get_stylesheet()
	 * so child themes don't share the parent theme's option value.
	 */
	private static $option_key = '';
	
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
		self::$theme_key  = $args['theme_key'];
		self::$option_key = self::$theme_key . '_theme_options';
	}
	
	/**
	 * The custom background callback.
	 * Write style in head of frontend.
	 * 
	 * @since   09/09/2012
	 * @return  void
	 */
	public function _custom_background_cb() {
		
		// $background is the saved custom image, or the default image.
		$background = set_url_scheme( get_background_image() );
		
		// $color is the saved custom color.
		// A default has to be specified in style.css. It will not be printed here.
		$color = get_theme_mod( 'background_color' );
		
		if ( ! $color )
			$color = esc_attr( get_theme_support( 'custom-background', 'default-color' ) );
		
		$style = $color ? "background-color: #$color;" : '';
		
		if ( $background ) {
			$image = " background-image: url('$background');";
			
			$repeat = get_theme_mod( 'background_repeat', 'repeat' );
			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$repeat = 'repeat';
			$repeat = " background-repeat: $repeat;";
	
			$position = get_theme_mod( 'background_position_x', 'left' );
			if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
				$position = 'left';
			$position = " background-position: top $position;";
	
			$attachment = get_theme_mod( 'background_attachment', 'scroll' );
			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
				$attachment = 'scroll';
			$attachment = " background-attachment: $attachment;";
			
			$style .= $image . $repeat . $position . $attachment;
		}
		
		// custom theme settings
		$options = parent::get_theme_options();
		foreach ($options as $key => $value) {
			trim( $value );
		}
	?>
	<style type="text/css" id="custom-theme-options">
		body { 
			<?php echo trim( $style ) . "\n"; ?>
			color: <?php echo $options['text_color']; ?>;
		}
		a:link, a:visited, 
		#content h2 a:link, #content h2 a:visited, 
		#header h1 a:link, #header h1 a:visited { color: <?php echo $options['link_color']; ?>; }
	</style>
	<?php
	}
	
} // end class