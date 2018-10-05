<?php
/**
 * The default view for the slider post type
 *
 * This is the template that displays for the slider shortcode by default.
 *
 * @package WordPress
 * @subpackage Grill
 * @since Grill 1.6.0
 */

global $grill_query;

if ( $grill_query->have_posts() ) :
?>

<div class="slider-wrapper">
	
	<ul class="slides bxslider" style="visibility: hidden;">				
	<?php 
	// Start the loop
	while ( $grill_query->have_posts() ) : $grill_query->the_post();
	
		grill_get_template_part( 'slider/slide' );

	// End the loop
	endwhile;
	?>
	</ul>
</div>

<?php wp_reset_postdata(); ?>

<script type="text/javascript">
jQuery(window).load(function() {
	jQuery('.slider-wrapper .bxslider').bxSlider({
		easing			: 'ease-in-out',
		auto			: <?php echo ( get_theme_mod('salt_slider_auto_scroll', '1') == '1' ) ? 'true' : 'false'; ?>,
		mode			: '<?php echo ( $a = get_theme_mod('salt_slider_animation') ) ? $a : 'horizontal'; ?>',
		controls		: <?php echo ( get_theme_mod('salt_slider_direction_nav', '1') == '1' ) ? 'true' : 'false'; ?>,
	    nextText		: '',
	    prevText		: '',
	    preloadImages	: 'all',
		pager			: <?php echo ( get_theme_mod('salt_slider_control_nav', '1') == '1' ) ? 'true' : 'false'; ?>,
		speed			: <?php echo ( $s = get_option('salt_slider_speed') ) ? $s : '500'; ?>,
		adaptiveHeight	: 'true',
		useCSS			: 'false',
		onSliderLoad: function() {
			jQuery(".bxslider").css("visibility", "visible");
		}
	});
});
</script>

<?php
endif; ?>

<?php wp_reset_query(); ?>