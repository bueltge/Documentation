<?php
/**
 * @package WordPress
 * @subpackage Documentation
 * @since 24.01.2015
 */
?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php documentation_post_info(); ?>
			
		</div>
