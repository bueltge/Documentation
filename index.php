<?php
/**
 * @package WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :
	
	tha_content_top(); 
	
	/* If this is a category archive */ if ( is_category() ) { ?>
		<h2><?php _e('Archive for categorie', 'documentation'); ?> &bdquo;<?php echo single_cat_title(); ?>&ldquo;</h2>
	<?php /* If this is a daily archive */ } elseif ( is_tag() ) { ?>
		<h2><?php _e('Archive for tag', 'documentation'); ?> &bdquo;<?php single_tag_title(); ?>&ldquo;</h2>
	<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
		<h2><?php _e('Daily archive', 'documentation'); ?> <?php the_time('F jS, Y'); ?></h2>
	<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
		<h2><?php _e('Monthly archive', 'documentation'); ?> <?php the_time('F, Y'); ?></h2>
	<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
		<h2><?php _e('Years of archive', 'documentation'); ?> <?php the_time('Y'); ?></h2>
	<?php /* If this is a search */ } elseif ( is_search() ) { ?>
		<h2><?php _e('Search results', 'documentation'); ?></h2>
	<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
		<h2><?php _e('Author archive', 'documentation'); ?></h2>
	<?php /* If a archive as fallback */ } elseif ( is_archive() ) { ?>
		<h2><?php _e('Archive', 'documentation'); ?></h2>
	<?php /* If this is a paged archive */ } elseif ( isset($_GET['paged']) && ! empty($_GET['paged']) ) { ?>
		<h2><?php _e('Archive', 'documentation'); ?></h2>
	<?php }
		
	while ( have_posts() ) :
		the_post();
	?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			
			<div class="story">
			<?php
			if ( is_search() || is_archive() ) {
				the_content( __( '... more &raquo;', 'documentation' ) . the_title( ' ', '', FALSE ) ); ?>
				<p class="info">
					<?php
					printf(
						__( 'Category: %s, write at %s by %s', 'documentation' ),
						get_the_category_list( ', ' ),
						get_the_date(),
						get_the_author()
					);
					?>
				</p>
			<?php
			} else {
				the_content( __( '... more &raquo;', 'documentation' ) . the_title( ' ', '', FALSE ) );
				wp_link_pages();
			} ?>
			</div>
		
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
