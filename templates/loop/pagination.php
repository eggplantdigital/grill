<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/grill/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    
 * @author 		Grill
 * @package 	Grill/Templates
 * @version     1.0.0
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
