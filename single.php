<?php
/**
 * @package WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :
	
	tha_content_top();
	
	while ( have_posts() ) :
		the_post(); ?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<h2><?php the_title(); ?></h2>
			<?php
			if ( current_theme_supports( 'post-thumbnails' ) )
				the_post_thumbnail();
			the_content();
			wp_link_pages();
			if ( function_exists( 'archive_page_link' ) ) { archive_page_link(); } ?>
			
			<p class="info">
				<?php
				printf(
					__( 'Category: %s<br />%s updated at %s by %s, write at %s by %s', 'documentation' ),
					get_the_category_list( ', ' ),
					get_the_tag_list( __( 'Tags:', 'documentation' ) . ' ', ', ', '<br />' ),
					get_the_modified_date(),
					get_the_modified_author(),
					get_the_date(),
					get_the_author()
				);
				edit_post_link( __( 'Edit', 'documentation' ), ' | ', '' );
				?>
			</p>
			
			<?php tha_content_after(); ?>
			
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
	
	tha_content_bottom();
	
endif;

get_footer();