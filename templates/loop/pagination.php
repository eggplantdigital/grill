<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/grill/loop/pagination.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<nav class="pagination grill-pagination">
	<?php
		echo paginate_links( apply_filters( 'grill_pagination_args', array(
			'base'         => '%_%',
			'format'       => '?paged=%#%',
			'add_args'     => false,
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $wp_query->max_num_pages,
			'prev_text' 	=> __( '&laquo; Previous', 'grill' ),
			'next_text' 	=> __( 'Next &raquo;', 'grill' ), 	
			'end_size'     => 3,
			'mid_size'     => 3
		) ) );
	?>
</nav>
