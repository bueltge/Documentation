<?php
/**
 * The template for displaying the header.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      2.0.0
 * @version    2015-06-10
 */
?>
	<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
		<?php tha_head_top(); ?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />

		<title><?php wp_title( '|', TRUE, 'right' ); ?> <?php bloginfo( 'name' ); ?></title>

		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="Shortcut Icon" type="image/x-icon" href="favicon.ico" />
		<?php
		tha_head_bottom();
		wp_head();

		if ( is_singular() ) {
			echo '<meta name="author" content="' . get_the_author_meta(
					'display_name', esc_attr( $GLOBALS[ 'post' ]->post_author )
				) . '" />' . "\n";
		}
		?>
	</head>

<body <?php body_class(); ?>>
<div id="wrap" class="hfeed">
<?php tha_header_before(); ?>
	<div id="header">
		<?php tha_header_top(); ?>
		<p id="login"><?php
			$redirect = documentation_get_options( 'rewrite_url' );
			if ( ! is_user_logged_in() ) {
				$link = '<a href="' . get_option( 'siteurl' ) . '/wp-login.php?redirect_to=' . home_url(
						'/'
					) . '">' . esc_attr__( 'Login', 'documentation' ) . '</a>';
			} else {
				$link = '<a href="' . get_option( 'siteurl' ) . '/' . $redirect . '">' . esc_attr__(
						'Administration', 'documentation'
					) . '</a>';
			}
			echo apply_filters( 'loginout', $link );
			?></p>

		<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<?php
		// check for custom options to echo the description
		if ( TRUE === documentation_get_options( 'echo_desc' ) ) {
			echo '<p class="site-description">' . get_bloginfo( 'description' ) . '</p>';
		}

		$args = array(
			'theme_location' => 'primary',
			'fallback_cb'    => FALSE
		);
		wp_nav_menu( $args );

		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header(
			)->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		<?php endif;
		tha_header_bottom();
		?>
	</div>
<?php tha_header_after(); ?>
	<div id="content">

<?php tha_content_before(); ?>