<?php
/**
 * Template Hooks
 *
 * @package		WordPress
 * @subpack		Grill
 * @since		Grill 1.0.1
 */

/**
 * Content Wrappers.
 *
 * @see grill_output_content_wrapper()
 * @see grill_output_content_wrapper_end()
 */
add_action( 'grill_before_main_content', 'grill_output_content_wrapper', 10 );
add_action( 'grill_after_main_content', 'grill_output_content_wrapper_end', 10 );

/**
 * Loop Wrappers.
 *
 * @see grill_loop_content_wrapper_start()
 * @see grill_loop_content_wrapper_end()
 */
add_action( 'grill_before_loop', 'grill_loop_content_wrapper_start', 10 );
add_action( 'grill_after_loop', 'grill_loop_content_wrapper_end', 10 );

/**
 * Pagination after post loops.
 *
 * @see grill_pagination()
 */
add_action( 'grill_after_loop', 'grill_pagination', 10 );
?>