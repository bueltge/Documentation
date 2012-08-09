<?php
/**
 * @package WordPress
 * @subpackage Documentation
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />

	<title><?php wp_title( '|', TRUE, 'right' ); ?> <?php bloginfo( 'name' ); ?></title>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="Shortcut Icon" type="image/x-icon" href="favicon.ico" />
	<?php
	wp_head();
	
	if ( is_singular() )
		echo '<meta name="author" content="' . get_the_author_meta( 'display_name', esc_attr( $GLOBALS['post']->post_author ) ) . '" />' . "\n";
	?>
</head>

<body <?php body_class(); ?>>
<div id="wrap" class="hfeed">
	<div id="header">
		<p id="login"><?php
		$redirect = documentation_get_options( 'rewrite_url' );
		if ( ! is_user_logged_in() ) {
			$link = '<a href="' . get_option( 'siteurl' ) . '/wp-login.php?redirect_to='.$redirect.'">' . __('Login', 'documentation') . '</a>';
		} else {
			$link = '<a href="' . get_option( 'siteurl' ) . '/wp-admin/edit.php">' . __('Administration', 'documentation') . '</a>';
		}
		echo apply_filters('loginout', $link);
		?></p>
		<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?></a></h1>
		<p><?php bloginfo('description'); ?></p>
		<?php
		$args = array(
			'theme_location'  => 'primary',
			'fallback_cb'     => FALSE
		);
		wp_nav_menu( $args );
		
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		<?php endif; ?>
	</div>

	<div id="content">
