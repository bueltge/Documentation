<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to documentation_comment() which is
 * located in the functions.php file.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @since      2.0.0
 */

tha_comments_before(); ?>
	
<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php esc_attr_e( 'This post is password protected. Enter the password to view any comments.', 'documentation' ); ?></p>
		<?php tha_comments_after(); ?>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
				printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'documentation' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" role="navigation">
			<h1 class="assistive-text section-heading"><?php esc_attr_e( 'Comment navigation', 'documentation' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_attr__( '&larr; Older Comments', 'documentation' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_attr__( 'Newer Comments &rarr;', 'documentation' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'documentation_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" role="navigation">
			<h1 class="assistive-text section-heading"><?php esc_attr_e( 'Comment navigation', 'documentation' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_attr__( '&larr; Older Comments', 'documentation' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_attr__( 'Newer Comments &rarr;', 'documentation' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
	
	<?php // If there are no comments and comments are closed, let's leave a note.
		elseif ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php esc_attr_e( 'Comments are closed.', 'documentation' ); ?></p>
	<?php endif; ?>
	
	<?php
	comment_form();
	
	tha_comments_after(); ?>
</div><!-- #comments -->