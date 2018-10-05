<?php
/**
 * A template used for displaying a grid type layout.
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
		
		<?php $grid = new Grill_Grid(); ?>
			
			<?php while ( have_posts() ) : the_post(); ?>	
		
				<?php $grid->begin(); ?>
				
				<?php grill_get_post_template(); ?>
				
				<?php $grid->finish(); ?>
				
			<?php endwhile; // end the loop ?>	
			
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
