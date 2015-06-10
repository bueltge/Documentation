<?php
/**
 * Custom functions for comments callback in this theme.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-06-10
 */

if ( ! function_exists( 'documentation_comment' ) ) {

	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own documentation_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since   2.0.0
	 *
	 * @param   $comment
	 * @param   $args
	 * @param   $depth
	 *
	 * @return  void
	 */
	function documentation_comment( $comment, $args, $depth ) {

		$GLOBALS[ 'comment' ] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="post pingback">
				<p><?php esc_attr_e( 'Pingback:', 'documentation' ); ?> <?php comment_author_link(
					); ?><?php edit_comment_link(
						esc_attr__( 'Edit', 'documentation' ), '<span class="edit-link">', '</span>'
					); ?></p>
				<?php
				break;
			default :
				?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$avatar_size = 68;
							if ( '0' != $comment->comment_parent ) {
								$avatar_size = 39;
							}

							echo get_avatar( $comment, $avatar_size );

							/* translators: 1: comment author, 2: date and time */
							printf(
								esc_attr__( '%1$s on %2$s <span class="says">said:</span>', 'documentation' ),
								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
								sprintf(
									'<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* translators: 1: date, 2: time */
									sprintf(
										esc_attr__( '%1$s at %2$s', 'documentation' ), get_comment_date(),
										get_comment_time()
									)
								)
							);
							?>

							<?php edit_comment_link(
								esc_attr__( 'Edit', 'documentation' ), '<span class="edit-link">', '</span>'
							); ?>
						</div>
						<!-- .comment-author .vcard -->

						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em class="comment-awaiting-moderation"><?php esc_attr_e(
									'Your comment is awaiting moderation.', 'documentation'
								); ?></em>
							<br>
						<?php endif; ?>
					</footer>

					<div class="comment-content"><?php comment_text(); ?></div>

					<div class="reply">
						<?php comment_reply_link(
							array_merge(
								$args, array(
									     'reply_text' => esc_attr__( 'Reply <span>&darr;</span>', 'documentation' ),
									     'depth'      => $depth,
									     'max_depth'  => $args[ 'max_depth' ]
								     )
							)
						); ?>
					</div>
					<!-- .reply -->
				</article>
				<!-- #comment-## -->

				<?php
				break;
		endswitch;
	}

} // end if function exists
