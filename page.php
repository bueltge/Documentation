<?php
/**
 * The template for displaying all single posts.
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * 
 * @package WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :
	tha_content_top();
	
	while ( have_posts() ) :
		the_post();
		get_template_part( 'parts/content', 'single' );
		
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || '0' != get_comments_number() )
			comments_template();
		
	endwhile;
	
	tha_content_bottom();
else:
	/**
	 * Include the template for the loop dosn't find and result
	 * If you will overwrite this in in a child theme the include a file
	 * called no-results-single.php and that will be used instead.
	 */
	get_template_part( 'parts/no-results', 'home' );
endif;

get_footer();