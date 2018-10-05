<?php
	
global $sc_atts;

if ( is_single() ) :
	$content = get_the_content();
	echo apply_filters( 'the_content', $content );

elseif ( isset( $sc_atts['content_body'] ) && $sc_atts['content_body'] == 'fulltext' ) :
	$content = get_the_content();
	echo apply_filters( 'the_content', $content );

elseif ( isset( $sc_atts['content_body'] ) && $sc_atts['content_body'] == 'none' ) :

else :
	$content = get_the_excerpt();
	echo apply_filters( 'the_content', $content ); ?>
<?php endif;