<?php
/**
 * Grill Template
 *
 * Functions for the templating system.
 *
 * @author   CharityThemes
 * @category Core
 * @package  Grill/Functions
 * @version  1.0.1
 */

/** Global ****************************************************************/

if ( ! function_exists( 'grill_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function grill_output_content_wrapper() {
		grill_get_template_part( 'global/wrapper', 'start' );
	}
}
if ( ! function_exists( 'grill_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function grill_output_content_wrapper_end() {
		grill_get_template_part( 'global/wrapper', 'end' );
	}
}

/** Archive ****************************************************************/

if ( ! function_exists( 'grill_loop_content_wrapper_start' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function grill_loop_content_wrapper_start() {
		grill_get_template_part( 'loop/loop', 'start' );
	}
}
if ( ! function_exists( 'grill_loop_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function grill_loop_content_wrapper_end() {
		grill_get_template_part( 'loop/loop', 'end' );
	}
}

/** Loop ******************************************************************/

if ( ! function_exists( 'grill_pagination' ) ) {

	/**
	 * Output the pagination.
	 *
	 * @subpackage	Loop
	 */
	function grill_pagination() {
		grill_get_template_part( 'loop/pagination' );
	}
}