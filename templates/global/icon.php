<?php
if ( $icon = get_post_meta( get_the_ID(), '_fontawesome_font', true ) ) :

	$icon_size     = get_post_meta( get_the_ID(), '_fontawesome_size', true );
	$icon_bg_color = get_post_meta( get_the_ID(), '_fontawesome_bg_color', true );
	$icon_color    = get_post_meta( get_the_ID(), '_fontawesome_color', true );
	
	if ( $icon_bg_color ) {
		$convert = grill_hex2rgb($icon_bg_color);
		$icon_bg_color = 'rgba('.$convert['r'].', '.$convert['g'].', '.$convert['b'].', 0.5)';	
	}

	if ( has_post_thumbnail() ) {
		$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), array( 300, 300 )); 
		$background = "background: url( '{$featured_img_url}' )no-repeat center center; background-size:cover; box-shadow: inset 0 0 0 1000px {$icon_bg_color};";
	} else {
		$background = "background:{$icon_bg_color};";
	}
	
	$popup = ( grill_get_setting('type') == 2 ) ? ' popup' : '';
	$style = 'style="'.$background.'; color:'.$icon_color.';"';
	$class = 'ico-holder ico-size-'.$icon_size.' '.$popup;
	$tag   = ( grill_get_setting('type') != 3 ) ? 'a' : 'span';

	$before_html = '<'.$tag.' class="'.$class.'" href="'.get_permalink().'" title="'.get_the_title().'" '.$style.'>';
	$after_html  = '</'.$tag.'>';
	
	echo $before_html.'<i class="fa fa-'.$icon.'"></i>'.$after_html;

endif;