<?php
	
function grill_timeline_block( $atts, $content = "" ) {
	
	global $sc_atts;

	$sc_atts = shortcode_atts( array(
		'title'   => '',
		'day'     => '',
		'month'   => '',
		'year'    => '',
	), $atts, 'grill_timeline_block' );

	$sc_atts['content'] = wpautop(trim($content));
	
	ob_start();

	grill_get_template_part( 'timeline/default' );

	$output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}
add_shortcode( 'grill_timeline_block', 'grill_timeline_block' );

add_filter("the_content", "the_content_filter");
function the_content_filter($content) {
	// array of custom shortcodes requiring the fix 
	$block = join("|",array( "grill_timeline_block"));
	// opening tag
	$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
	// closing tag
	$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
	return $rep;
}