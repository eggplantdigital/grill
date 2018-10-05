<?php
/**
 * A template used for displaying the carousel type layout.
 *
 * @package WordPress
 * @subpackage Grill
 * @since Grill 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

	<?php if ( have_posts() ) : ?>

		<?php
			/**
			 * grill_before_loop hook.
			 * 
			 * @hooked grill_loop_content_wrapper_start - 10
			 */
			do_action( 'grill_before_loop' );
		?>
		
			<ul class="bxslider post-carousel post-grid" data-stop-auto-on-click="true" data-auto="true" data-max-slides="3" data-slide-width="370">
					
			<?php while ( have_posts() ) : the_post(); ?>
				
				<li class="slide">
					
				<?php grill_get_post_template(); ?>
				
				</li>
												
			<?php endwhile; // end the loop ?>	

			</ul>
			
		<?php
			/**
			 * grill_after_loop hook.
			 * 
			 * @hooked grill_pagination - 5
			 * @hooked grill_loop_content_wrapper_end - 10
			 */
			do_action( 'grill_after_loop' );
		?>

	<?php else : ?>
	
		<?php grill_get_template_part( 'loop/no-products-found.php' ); ?>
		
	<?php endif; ?>
	
<!--
<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery('.carousel .bxslider').bxSlider({
		    controls: false,
		    pager: true,
		    easing: 'easeInOutQuint',
		    infiniteLoop: false,
		    slideWidth: 370,
	        nextText: '',
	        prevText: '',
		    minSlides: 1,
		    maxSlides: 3,
		    slideMargin: 30,
			onSliderLoad: function() {
				jQuery(".bxslider").css("visibility", "visible");
			}
		});
	});
</script>
-->
