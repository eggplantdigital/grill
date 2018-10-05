<?php
$link = get_post_meta( get_the_ID(), '_link_url', true );
$text = get_post_meta( get_the_ID(), '_link_text', true );

// Check if this post type is set to use popups
$settings = get_option('grill_settings');
$post_type = get_post_type();
$popup = ( isset( $settings["_type_{$post_type}"] ) && $settings["_type_{$post_type}"] == 2 ) ? 'popup' : '';

if ( ! $link )
	$link = get_permalink();
	
if ( ! $text )
	$text = __('Learn More', 'grill');	

if ( ! is_single() ) : ?>
	<a href="<?php echo $link; ?>" class="button <?php echo $popup; ?>"><?php echo $text; ?></a>
<?php endif;