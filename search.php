<?php
/**
 * @package WordPress
 * @subpackage Documentation
 * @version 2015-01-09
 */

get_header();

if ( have_posts() ) :
	
	tha_content_top(); 
	?>
		<h2 class="page-title"><?php printf( esc_attr__( 'Search Results for: %s', 'documentation' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
	<?php
	while ( have_posts() ) :
		the_post();
		
		get_template_part( 'parts/content', 'search' );
		
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
