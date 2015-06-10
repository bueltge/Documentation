<?php
/**
 * Custom functions, that hook in core for this theme.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-06-10
 */

if ( ! function_exists( 'documentation_wp_title' ) ) {

	add_filter( 'wp_title', 'documentation_wp_title', 10, 2 );
	/**
	 * Creates a nicely formatted and more specific title element text
	 * for output in head of document, based on current view.
	 *
	 * @since 10/02/2012
	 *
	 * @param   string $title Default title text for current view.
	 * @param   string $sep   Optional separator.
	 *
	 * @return  string Filtered title.
	 */
	function documentation_wp_title( $title, $sep ) {

		global $paged, $page;

		if ( is_feed() ) {
			return $title;
		}

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 ) {
			$title = "$title $sep " . sprintf( esc_attr__( 'Page %s', 'documentation' ), max( $paged, $page ) );
		}

		return $title;
	}

} // end if function exists
