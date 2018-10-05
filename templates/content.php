<?php
	
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( locate_template( array("grill/content-{$post_type}.php")) != '' ||
	 file_exists( GRILL_TEMPLATE_PATH . "content-{$post_type}.php" )) :

	grill_get_template_part( 'content', $post_type );
else :
	grill_get_template_part( 'content', 'article' );
endif;