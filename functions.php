<?php
/**
 * Functions and definitions
 * 
 * @package    WordPress
 * @subpackage Documentation
 * @version    10/17/2012
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
		if ( ! isset( $GLOBALS['content_width'] ) )
			$GLOBALS['content_width'] = 900;

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
		 * Add support for the hook alliance
		 * 
		 * @see    https://github.com/zamoose/themehookalliance
		 * @since  10/02/2012
		 */
		add_theme_support( 'tha_hooks', array( 'all' ) );
		// include the file from this project, if theme supported
		if ( file_exists( get_template_directory() . '/inc/tha/tha-theme-hooks.php' ) ) // beta release from repo
			require_if_theme_supports( 'tha_hooks', get_template_directory() . '/inc/tha/tha-theme-hooks.php' );
		else
			require_if_theme_supports( 'tha_hooks', get_template_directory() . '/inc/tha-1.0/tha-theme-hooks.php' );
		
		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );
		
		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'documentation' ) );
		
		// Add support for custom background.
		$args = array( 
			'default-image'          => '',
			'default-color'          => 'fff',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		);
		add_theme_support( 'custom-background', $args );
		
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
	 * @return  String, Array  Value of the options item
	 */
	function documentation_get_options( $value = '' ) {
		
		// failed to load file for options, then return NULL
		if ( ! class_exists( 'Documentation_Customize' ) )
			return NULL;
		
		$documentation_options = new Documentation_Customize();
		
		$options = $documentation_options->get_theme_options();
		
		if ( ! empty( $value ) )
			$options = $options[$value];
		
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
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		
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
		
		// Register main and print CSS file
		wp_register_style( 'documentation-style', get_stylesheet_directory_uri() . '/css/style' . $suffix . '.css' );
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


if ( ! function_exists( 'documentation_widgets_init' ) ) {
	
	add_action( 'widgets_init', 'documentation_widgets_init' );
	/**
	 * Register widgetized areas
	 *
	 * @return  void
	 */
	function documentation_widgets_init() {
		
		// Area 1
		register_sidebar( array (
			'name'          => __( 'Primary Widget Area', 'documentation' ),
			'id'            => 'primary-widget-area',
			'description'   => __( 'The primary widget area is visible on all pages and posts.', 'documentation' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget'  => "</li>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		
		// Area 2
		register_sidebar( array (
			'name'          => __( 'Secondary Widget Area', 'documentation' ),
			'id'            => 'secondary-widget-area', 
			'description'   => __( 'The secondary widget area down below Primary Widget Area only on pages and posts.' , 'documentation' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget'  => "</li>",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	} // end theme_widgets_init
	
} // end if func exists


if ( ! function_exists( 'documentation_comment' ) ) {
	
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own documentation_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since   2.0.0
	 * @param   $comment  
	 * @param   $args  
	 * @param   $depth  
	 * @return  void
	 */
	function documentation_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'documentation' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'documentation' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
			break;
		default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
							$avatar_size = 68;
							if ( '0' != $comment->comment_parent )
								$avatar_size = 39;
	
							echo get_avatar( $comment, $avatar_size );
	
							/* translators: 1: comment author, 2: date and time */
							printf( __( '%1$s on %2$s <span class="says">said:</span>', 'documentation' ),
								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
								sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* translators: 1: date, 2: time */
									sprintf( __( '%1$s at %2$s', 'documentation' ), get_comment_date(), get_comment_time() )
								)
							);
						?>
						
						<?php edit_comment_link( __( 'Edit', 'documentation' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-author .vcard -->
					
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'documentation' ); ?></em>
						<br />
					<?php endif; ?>
				</footer>
				
				<div class="comment-content"><?php comment_text(); ?></div>
				
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'documentation' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->
			
		<?php
				break;
		endswitch;
	}
	
} // end if function exists


if ( ! function_exists( 'documentation_get_paginate_bar' ) ) {
	
	/**
	 * Create a pagination bar
	 * 
	 * @since   08/09/2012
	 * @param   $args  Array  see default array inside fct.
	 * @return  $pagination String
	 */
	function documentation_get_paginate_bar( $args = FALSE ) {
		global $wp_rewrite, $wp_query;
		
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		
		if ( empty($rules) )
			$rulestouse = @add_query_arg( 'paged','%#%' );
		else
			$rulestouse = @add_query_arg( 'page','%#%' );
		
		if ( ! $args ) {
			// default arguments
			$args = array(
				'base'         => $rulestouse,
				'format'       => '',
				'total'        => $wp_query->max_num_pages,
				'current'      => $current,
				'show_all'     => FALSE,
				'prev_next'    => TRUE,
				'prev_text'    => __( '&laquo; Previous', 'documentation' ),
				'next_text'    => __( 'Next &raquo;', 'documentation' ),
				'end_size'     => 3,
				'mid_size'     => 5,
				'type'         => 'plain',
				'add_args'     => FALSE, // array of query args to add
				'add_fragment' => '',
				'show_total'   => FALSE,
				'display'      => TRUE,
				'markup'       => 'div'
			);
		}
		
		if ( $wp_rewrite->using_permalinks() ) {
			$args['base'] = user_trailingslashit( 
				trailingslashit( remove_query_arg( 's', get_pagenum_link(1) ) ) . 'page/%#%/', 'paged'
			);
		}
		
		if ( ! empty( $wp_query->query_vars['s'] ) )
			$args['add_args'] = array( 's' => get_query_var('s') );
		
		$pagination = paginate_links( $args );
		
		if ( $args['show_total'] )
			$pagination .= sprintf( __( '&emsp;(%d)', 'documentation' ), $wp_query->max_num_pages );
		
		if ( ! empty( $args['markup'] ) )
			$pagination = apply_filters( 
				'documentation_paginate_bar',
				'<' . $args['markup'] . ' class="paginate_bar">' . $pagination . '</' . $args['markup'] . '>'
			);
		
		if ( $args['display'] && 1 < $wp_query->max_num_pages )
			echo $pagination;
		else
			return $pagination;
	}
	
} // end if function exists

if ( ! function_exists( 'documentation_wp_title' ) ) {
	
	add_filter( 'wp_title', 'documentation_wp_title', 10, 2 );
	/**
	 * Creates a nicely formatted and more specific title element text
	 * for output in head of document, based on current view.
	 *
	 * @since 10/02/2012
	 *
	 * @param   string $title Default title text for current view.
	 * @param   string $sep Optional separator.
	 * @return  string Filtered title.
	 */
	function documentation_wp_title( $title, $sep ) {
		global $paged, $page;
	
		if ( is_feed() )
			return $title;
	
		// Add the site name.
		$title .= get_bloginfo( 'name' );
	
		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";
	
		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );
	
		return $title;
	}
	
}  // end if function exists
