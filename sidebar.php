<?php
/**
 * @package    WordPress
 * @subpackage Documentation
 */

tha_sidebars_before();
?>
	<div id="sidebar">

		<?php tha_sidebar_top(); ?>

		<ul class="primary-widget-area">

			<?php if ( class_exists( 'SitePress' ) ) { ?>
				<li>
					<?php do_action( 'icl_language_selector' ); ?>
				</li>
			<?php } ?>

			<?php
			if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : // begin primary widget area
				?>
				<li id="search">
					<?php if ( defined( 'ASL_ACTIVE' ) && 'true' == ASL_ACTIVE ) {
						echo as_form( 'asl' );
					} else { ?>
						<form id="searchform" method="get" action="<?php home_url(); ?>">
							<p>
								<input type="text" value="" name="s" id="s" />
								<input type="submit" class="submit" accesskey="s" value="<?php esc_attr_e( 'Search', 'documentation' ); ?>" />
							</p>
						</form>
					<?php } ?>
				</li>

				<li><h2><?php esc_attr_e( 'Pages', 'documentation' ); ?></h2>
					<ul>
						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					</ul>
				</li>
				<?php //wp_list_pages('title_li=<h2>' . esc_attr__('Pages', 'documentation') . '</h2>');
				?>

				<?php wp_list_categories( 'sort_column=name&optioncount=1&hierarchical=1&hide_empty=0&title_li=<h2>' . esc_attr__( 'Categories', 'documentation' ) . '</h2>&show_count=1' ); ?>

				<?php if ( ! is_home() ) { ?>
				<li><h2><?php esc_attr_e( 'Last entries', 'documentation' ); ?></h2>
					<ul>
						<?php wp_get_archives( 'type=postbypost&limit=15' ); ?>
					</ul>
				</li>
			<?php }
			endif; // end primary widget area ?>
		</ul>

		<?php if ( is_active_sidebar( 'secondary-widget-area' ) && is_singular() ) : // Nothing here by default and design ?>
			<ul class="secondary-widget-area">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		<?php endif;

		tha_sidebar_bottom();
		?>
	</div>

<?php tha_sidebars_after(); ?>