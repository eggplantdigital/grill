<?php
global $sc_atts;

if ( has_post_thumbnail() ) {

	$popup = ( grill_get_setting('type') == 2 ) ? ' popup' : '';

	if ( ! is_single() && grill_get_setting('type') != 3 ) {
		$args = array(
			'before' => '<a class="%1$s" href="'.get_permalink().'" title="'.get_the_title().'">',
			'after'  => '</a>',
			'class'  => 'post-thumbnail'.$popup
		);
	} else {
		$args['class'] = 'post-thumbnail'.$popup;
	}

	grill_post_thumbnail( 'full', $args );
}
?>