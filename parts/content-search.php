<?php
/**
 * @package    WordPress
 * @subpackage Documentation
 * @since      2015-01-09
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	<div class="story">
		<?php
		if ( current_theme_supports( 'post-thumbnails' ) ) {
			the_post_thumbnail();
		}
		the_excerpt();
		wp_link_pages();
		documentation_post_info();

		tha_content_after();
		?>
	</div>

</div>
