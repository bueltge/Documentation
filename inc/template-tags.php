<?php
/**
 * Custom template tags for this theme.
 *
 * @package    WordPress
 * @subpackage Documentation
 * @version    2015-06-10
 */

if ( ! function_exists( 'documentation_get_paginate_bar' ) ) {

	/**
	 * Create a pagination bar
	 *
	 * @since   08/09/2012
	 *
	 * @param Array|bool $args Array  see default array inside fct.
	 *
	 * @return array|mixed|string|void $pagination String
	 */
	function documentation_get_paginate_bar( $args = FALSE ) {

		global $wp_rewrite, $wp_query;

		$wp_query->query_vars[ 'paged' ] > 1 ? $current = $wp_query->query_vars[ 'paged' ] : $current = 1;

		if ( empty( $rules ) ) {
			$rulestouse = @add_query_arg( 'paged', '%#%' );
		} else {
			$rulestouse = @add_query_arg( 'page', '%#%' );
		}

		if ( ! $args ) {
			// default arguments
			$args = array(
				'base'         => $rulestouse,
				'format'       => '',
				'total'        => $wp_query->max_num_pages,
				'current'      => $current,
				'show_all'     => FALSE,
				'prev_next'    => TRUE,
				'prev_text'    => esc_attr__( '&laquo; Previous', 'documentation' ),
				'next_text'    => esc_attr__( 'Next &raquo;', 'documentation' ),
				'end_size'     => 3,
				'mid_size'     => 5,
				'type'         => 'plain',
				'add_args'     => FALSE, // array of query args to add
				'add_fragment' => '',
				'show_total'   => FALSE,
				'display'      => TRUE,
				'markup'       => 'div'
			);
		}

		if ( $wp_rewrite->using_permalinks() ) {
			$args[ 'base' ] = user_trailingslashit(
				trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged'
			);
		}

		if ( ! empty( $wp_query->query_vars[ 's' ] ) ) {
			$args[ 'add_args' ] = array( 's' => get_query_var( 's' ) );
		}

		$pagination = paginate_links( $args );

		if ( $args[ 'show_total' ] ) {
			$pagination .= sprintf( esc_attr__( '&emsp;(%d)', 'documentation' ), $wp_query->max_num_pages );
		}

		if ( ! empty( $args[ 'markup' ] ) ) {
			$pagination = apply_filters(
				'documentation_paginate_bar',
				'<' . $args[ 'markup' ] . ' class="paginate_bar">' . $pagination . '</' . $args[ 'markup' ] . '>'
			);
		}

		if ( $args[ 'display' ] && 1 < $wp_query->max_num_pages ) {
			echo $pagination;
		}

		return $pagination;
	}

} // end if function exists

if ( ! function_exists( 'documentation_post_info' ) ) {

	/**
	 * Prints HTML with meta information for the current post
	 *
	 * @return  void
	 * @since   2015-06-10
	 */
	function documentation_post_info() {

		?>
		<p class="info">
			<?php
			if ( get_the_category_list() ) {
				printf(
					esc_attr__( 'Category: %s%s', 'documentation' ),
					get_the_category_list( ', ' ),
					'<br>'
				);
			}

			printf(
				esc_attr__( '%s updated at %s by %s, write at %s by %s', 'documentation' ),
				get_the_tag_list( esc_attr__( 'Tags:', 'documentation' ) . ' ', ', ', '<br>' ),
				esc_html( get_the_modified_date() ),
				esc_html( get_the_modified_author() ),
				esc_html( get_the_date() ),
				esc_html( get_the_author() )
			);

			edit_post_link( esc_attr__( 'Edit', 'documentation' ), ' | ', '' );
			?>
		</p>
	<?php
	}

} // end if function exists