<?php
/**
 * @package WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :
	
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	query_posts( $query_string . "&paged=$paged" );
	while ( have_posts() ) :
		the_post();
	?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<p class="info">
				<?php _e( 'Category:', 'documentation' ); ?> <?php the_category( ', ' ) ?>
			</p>
		</div>
	
	<?php
	endwhile;
else:
	
	/**
	 * Include the template for the loop dosn't find and result
	 * If you will overwrite this in in a child theme the include a file
	 * called no-results-single.php and that will be used instead.
	 */
	get_template_part( 'parts/no-results', 'home' );

endif;

get_footer();
