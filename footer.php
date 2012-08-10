<?php
/**
 * @package WordPress
 * @subpackage Documentation
 */
 
if ( ! is_singular() )
	documentation_get_paginate_bar();
?>
	</div>
	
	<?php get_sidebar(); ?>
	
	<div id="footer">
		<p>&copy; 2007 - <?php echo date( 'Y' ); ?> | <a href="<?php home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
		<?php
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
			echo ' | <small>' . $wpdb->num_queries . 'q, ' . timer_stop() . 's</small>';
		
		$redirect = documentation_get_options( 'rewrite_url' );
		if ( ! is_user_logged_in() ) {
			$link = ' | <a href="' . get_option('siteurl') . '/wp-login.php?redirect_to=' . $redirect . '">' . __('Login', 'documentation') . '</a>';
		} else {
			$link = ' | <a href="' . get_option('siteurl') . '/wp-login.php?action=logout&amp;redirect_to=' . $redirect . '">' . __( 'Logout', 'documentation' ) . '</a>';
		}
		echo apply_filters( 'loginout', $link );
		?>
		</p>
	</div>

	<?php wp_footer(); ?>
</div>
</body>
</html>