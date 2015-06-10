<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package    WordPress
 * @subpackage Documentation
 */

tha_sidebars_before();
?>
	<div id="sidebar" class="widget-area" role="complementary">

		<?php
		tha_sidebar_top();

		if ( is_active_sidebar( 'primary-widget-area' ) ) {
			dynamic_sidebar( 'primary-widget-area' );
		}

		if ( is_active_sidebar( 'secondary-widget-area' ) && is_singular() ) {
			dynamic_sidebar( 'secondary-widget-area' );
		}

		tha_sidebar_bottom();
		?>
	</div>

<?php tha_sidebars_after(); ?>