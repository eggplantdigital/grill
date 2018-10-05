<?php
if ( ! function_exists( 'grill_pagination' ) ) :
/**
 * Custom loop pagination function.
 *
 * grill_pagination() is used for paginating the various archive pages created by WordPress. This is not
 * to be used on single.php or other single view pages.
 *
 * @param array $args (Optional) ...
 *
 *     @type arg 		$base Reference the url, which will be used to create the paginated links.
 *     @type string 	$format Used for replacing the page number.
 *     @type numerical	$total Total amount of pages and is an integer.
 *     @type numerical	$current The current page number. 
 *     @type boolean	$prev_next Include prev and next links in the list by setting the ‘prev_next’ argument to true.
 *     @type string		$prev_text Change the text for the prev link.
 *     @type string		$next_text Change the text for the next link.
 *     @type boolean	$show_all Show all of the pages instead of a short list of the pages near the current page, by default is false
 *     @type numerical	$end_size How many numbers on either the start and the end list edges, by default is 1.
 *     @type numerical	$mid_size How many numbers to either side of current page, but not including current page.
 *     @type string		$add_args It is possible to add query vars to the link by using the ‘add_args’
 *     @type string		$before Text added before the page number – within the anchor tag.
 *     @type string		$after Text added after the page number – within the anchor tag.
 *     @type boolean	$jump_to Include a Jump To option on the pagination.
 *     @type boolean	$echo If set to true the pagination will be displayed or false will return as a string.
 *     
 * @param array	$query Pass the query that paginatio is being created for, by default uses $wp_query.
 *
 * @return (array|string|void) String of page links or array of page links.
 * @link https://developer.wordpress.org/reference/functions/paginate_links/
 *
 * @since Grill 1.0.0
 */
 
 function grill_pagination( $args = array(), $query = '' ) {
	global $wp_rewrite, $wp_query;
	
	if ( $query ) {
		$wp_query = $query;
	}

	/* If there's not more than one page, return nothing. */
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}

	/* Get the current page. */
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
	/* Get the max number of pages. */
	$max_num_pages = intval( $wp_query->max_num_pages );

	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base' 			=> add_query_arg( 'paged', '%#%' ),
		'format' 		=> '',
		'total' 		=> $max_num_pages,
		'current' 		=> $current,
		'prev_next' 	=> true,
		'prev_text' 	=> __( '&laquo; Previous', 'grill' ),
		'next_text' 	=> __( 'Next &raquo;', 'grill' ), 	
		'show_all' 		=> false,
		'end_size' 		=> 1,
		'mid_size' 		=> 1,
		'add_args'		=> '',
		'type' 			=> 'plain',
		'before' 		=> '<div class="pagination grill-pagination">', // Begin grill_pagination() arguments.
		'after' 		=> '</div>',
		'jumpto' 		=> false,
		'echo' 			=> true,
	);

	/* Add the $base argument to the array if the user is using permalinks. */
	if( $wp_rewrite->using_permalinks() ) {
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	}
	
	/* If we're on a search results page, we need to change this up a bit. */
	if ( is_search() ) {
		/* If we're in BuddyPress, use the default "unpretty" URL structure. */
		if ( class_exists( 'BP_Core_User' ) ) {
			$search_query = get_query_var( 's' );
			$paged = get_query_var( 'paged' );
			
			$base = user_trailingslashit( esc_url( home_url('/') ) ) . '?s=' . $search_query . '&paged=%#%';
			
			$defaults['base'] = $base;
		} else {
			$search_permastruct = $wp_rewrite->get_search_permastruct();
			if ( !empty( $search_permastruct ) ) {
				$defaults['base'] = user_trailingslashit( trailingslashit( get_search_link() ) . 'page/%#%' );
			}
		}
	}

	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );

	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'grill_pagination_args', $args );

	/* Don't allow the user to set this to an array. */
	if ( 'array' == $args['type'] ) {
		$args['type'] = 'plain';
	}
	
	/* Make sure raw querystrings are displayed at the end of the URL, if using pretty permalinks. */
	$pattern = '/\?(.*?)\//i';
	
	preg_match( $pattern, $args['base'], $raw_querystring );
	
	if( $wp_rewrite->using_permalinks() && $raw_querystring ) {
		$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
	}

	@$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
	@$args['base'] .= substr( $raw_querystring[0], 0, -1 );
	
	/* Get the paginated links. */
	$page_links = paginate_links( $args );

	/* Remove 'page/1' from the entire output since it's not needed. */
	$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );

	if( $args['jumpto'] ) {
		$page_links .= '<form class="pagination-jump" method="get" action="">';
		$page_links .= '<label>' . __('Jump to', 'grill');
		$page_links .= '<input type="text" size="2" id="page-number" value="" />';
		$page_links .= '</label>';
		$page_links .= '<input type="hidden" id="pagination-base" value="' . $args['base'] . '" />';
		$page_links .= '<input type="submit" id="pagination-submit" value="' . __('Go', 'grill') . '" />';
		$page_links .= '</form>';
		
		ob_start();
		?>
		
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function(){
			jQuery('form.pagination-jump').submit(function(){
				var number = parseInt( jQuery('#page-number').val(), 10);
				var base = jQuery('#pagination-base').val();
				var action = base.replace( /%#%/g, number );
				
				jQuery(this).attr('action', action);
			});
		});
		//]]>
		</script>
		
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		
		$page_links .= $js;
	}

	/* Wrap the paginated links with the $before and $after elements. */
	$page_links = $args['before'] . $page_links . $args['after'];

	/* Allow devs to completely overwrite the output. */
	$page_links = apply_filters( 'grill_pagination', $page_links );

	do_action( 'grill_pagination_end' );
	
	/* Return the paginated links for use in themes. */
	if ( $args['echo'] ) {
		echo $page_links;
	} else {
		return $page_links;
	}
} // End grill_pagination()
endif;