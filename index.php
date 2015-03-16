<?php
/**
 * @package    WordPress
 * @subpackage Documentation
 */

get_header();

if ( have_posts() ) :

	tha_content_top();

	/* If this is a category archive */
	if ( is_category() ) { ?>
		<h2><?php esc_attr_e( 'Archive for categorie', 'documentation' ); ?> &bdquo;<?php echo single_cat_title(); ?>&ldquo;</h2>
		<?php /* If this is a daily archive */
	} elseif ( is_tag() ) { ?>
		<h2><?php esc_attr_e( 'Archive for tag', 'documentation' ); ?> &bdquo;<?php single_tag_title(); ?>&ldquo;</h2>
		<?php /* If this is a daily archive */
	} elseif ( is_day() ) { ?>
		<h2><?php esc_attr_e( 'Daily archive', 'documentation' ); ?> <?php the_time( 'F jS, Y' ); ?></h2>
		<?php /* If this is a monthly archive */
	} elseif ( is_month() ) { ?>
		<h2><?php esc_attr_e( 'Monthly archive', 'documentation' ); ?> <?php the_time( 'F, Y' ); ?></h2>
		<?php /* If this is a yearly archive */
	} elseif ( is_year() ) { ?>
		<h2><?php esc_attr_e( 'Years of archive', 'documentation' ); ?> <?php the_time( 'Y' ); ?></h2>
		<?php /* If this is a search */
	} elseif ( is_search() ) { ?>
		<h2><?php esc_attr_e( 'Search results', 'documentation' ); ?></h2>
		<?php /* If this is an author archive */
	} elseif ( is_author() ) { ?>
		<h2><?php esc_attr_e( 'Author archive', 'documentation' ); ?></h2>
		<?php /* If a archive as fallback */
	} elseif ( is_archive() ) { ?>
		<h2><?php esc_attr_e( 'Archive', 'documentation' ); ?></h2>
		<?php /* If this is a paged archive */
	} elseif ( isset( $_GET[ 'paged' ] ) && ! empty( $_GET[ 'paged' ] ) ) { ?>
		<h2><?php esc_attr_e( 'Archive', 'documentation' ); ?></h2>
	<?php }

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
	 * Include the template for the loop dosn't find and result
	 * If you will overwrite this in in a child theme the include a file
	 * called no-results-single.php and that will be used instead.
	 */
	get_template_part( 'parts/no-results', 'home' );

endif;

get_footer();
