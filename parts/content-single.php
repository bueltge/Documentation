<?php
/**
 * @package WordPress
 * @subpackage Documentation
 * @since 02/11/2013
 */
?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="story">
			<?php
			if ( current_theme_supports( 'post-thumbnails' ) )
				the_post_thumbnail();
			the_content( __( '... more &raquo;', 'documentation' ) );
			wp_link_pages();
			documentation_post_info();
			
			tha_content_after();
			?>
			</div>
			
		</div>
