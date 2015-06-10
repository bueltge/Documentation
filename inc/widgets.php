<?php
/**
 * Widget area and his markup for this theme.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-06-10
 */

if ( ! function_exists( 'documentation_widgets_init' ) ) {

	add_action( 'widgets_init', 'documentation_widgets_init' );
	/**
	 * Register widgetized areas
	 *
	 * @return  void
	 */
	function documentation_widgets_init() {

		// Area 1
		register_sidebar(
			array(
				'name'          => esc_html__( 'Primary Widget Area', 'documentation' ),
				'id'            => 'primary-widget-area',
				'description'   => esc_html__(
					'The primary widget area is visible on all pages and posts.', 'documentation'
				),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title">',
				'after_title'   => '</h1>',
			)
		);

		// Area 2
		register_sidebar(
			array(
				'name'          => esc_html__( 'Secondary Widget Area', 'documentation' ),
				'id'            => 'secondary-widget-area',
				'description'   => esc_html__(
					'The secondary widget area down below Primary Widget Area only on pages and posts.', 'documentation'
				),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title">',
				'after_title'   => '</h1>',
			)
		);
	} // end theme_widgets_init

} // end if func exists