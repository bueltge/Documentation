<?php
/**
 * @package    WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :

	tha_content_top();

	the_archive_title( '<h2>', '</h2>' );

	while ( have_posts() ) :
		the_post();

		if ( is_archive() ) {
			get_template_part( 'parts/content', 'archive' );
		} else {
			get_template_part( 'parts/content', 'single' );
		}

	endwhile;

	tha_content_bottom();

else:

	/**
	 * Include the template for the loop if don't find and result
	 * If you will overwrite this in in a child theme the include a file
	 * called no-results-single.php and that will be used instead.
	 */
	get_template_part( 'parts/no-results', 'home' );

endif;

get_footer();
