<?php
/**
 * The template for displaying the footer.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-06-10
 */

tha_content_after();

if ( ! is_singular() ) {
	documentation_get_paginate_bar();
}
?>
</div><!-- #content @see header.php -->

<?php
if ( ! is_page() )
	get_sidebar();

tha_footer_before();
?>

<div id="footer">
	<?php tha_footer_top(); ?>
	<p>&copy; 2007 - <?php echo date( 'Y' ); ?> | <a href="<?php home_url( '/' ); ?>" class="site-name"><?php bloginfo(
				'name'
			); ?></a>
		<?php
		$redirect = documentation_get_options( 'rewrite_url' );
		if ( ! is_user_logged_in() ) {
			$link = ' | <a href="' . get_option(
					'siteurl'
				) . '/wp-login.php?redirect_to=' . $redirect . '">' . esc_attr__( 'Login', 'documentation' ) . '</a>';
		} else {
			$link = ' | <a href="' . home_url( '/' ) . '/wp-login.php?action=logout&amp;redirect_to=' . home_url(
					'/'
				) . '">' . esc_attr__( 'Logout', 'documentation' ) . '</a>';
		}
		echo apply_filters( 'loginout', $link );
		?>
	</p>
	<?php tha_footer_bottom(); ?>
</div>

<?php
wp_footer();

tha_footer_after();
?>
</div><!-- #wrap @see header.php -->
</body>
</html>