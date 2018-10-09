<?php
if ( has_post_thumbnail() ) {

	if ( is_single() ) {

		grill_post_thumbnail( 'full' );
	
	} else {
		
		if ( get_post_meta( get_the_ID(), '_fontawesome_font', true ) )
			return false;
	
		$popup = ( grill_get_setting('type') == 2 ) ? ' popup' : '';
	
		if ( grill_get_setting('type') != 3 ) {
			$args = array(
				'before' => '<a class="%1$s" href="'.get_permalink().'" title="'.get_the_title().'">',
				'after'  => '</a>',
				'class'  => 'post-thumbnail'.$popup
			);
		} 
		
		grill_post_thumbnail( 'full', $args );

	}
}
?>