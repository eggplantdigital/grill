<?php
/**
 * Template for selecting the template file based on the post type
 *
 * This template can be overridden by copying it to yourtheme/grill/content.php.
 *
 * @see 	    
 * @package 	Grill/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( locate_template( array("grill/content-{$post_type}.php")) != '' ||
	 file_exists( GRILL_TEMPLATE_PATH . "content-{$post_type}.php" )) :

	grill_get_template_part( 'content', $post_type );
else :
	grill_get_template_part( 'content', 'article' );
endif;