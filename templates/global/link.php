<?php
	
if ( grill_get_setting('type') == 3 )
	return false;
		
$link = get_post_meta( get_the_ID(), '_link_url', true );
$text = get_post_meta( get_the_ID(), '_link_text', true );

// Check if this post type is set to use popups
$settings = get_option('grill_settings');
$post_type = get_post_type();
$popup = ( grill_get_setting('type') == 2 ) ? 'popup' : '';

if ( ! $link )
	$link = get_permalink();
	
if ( ! $text )
	$text = __('Learn More', 'grill');	

if ( ! is_single() ) : ?>
	<a href="<?php echo esc_attr($link); ?>" class="button <?php echo $popup; ?>"><?php echo esc_attr($text); ?></a>
<?php endif;