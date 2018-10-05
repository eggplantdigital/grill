<?php
global $sc_atts;

if ( has_post_thumbnail() ) {

	$alignleft     = ( is_single() ) ? ' alignleft' : '';
	$popup       = ( grill_get_setting('type') == 2 ) ? ' popup' : '';
	$circleimg = ( isset( $sc_atts['circleimg'] ) && $sc_atts['circleimg'] == 'true' ) ? ' circleimg' : '';

	if ( ! is_single() && grill_get_setting('type') != 3 ) {
		$args = array(
			'before' => '<a class="%1$s" href="'.get_permalink().'" title="'.get_the_title().'">',
			'after'  => '</a>',
			'class'  => 'post-thumbnail'.$circleimg.$popup
		);
	} else {
		$args['class'] = 'post-thumbnail'.$circleimg.$alignleft.$popup;
	}

	grill_post_thumbnail( 'post-thumbnail', $args );
}
?>