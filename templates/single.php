<?php
/**
 * The Template for displaying all single post types
 *
 * This template can be overridden by copying it to yourtheme/grill/single.php.
 *
 * @see 	    
 * @package 	Grill/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post_type;

get_header(); ?>
	<?php
		/**
		 * grill_before_main_content hook.
		 *
		 * @hooked grill_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action( 'grill_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>
			
			<?php
			if ( locate_template( array("grill/content-single-{$post_type}.php")) != '' ||
				 file_exists( GRILL_TEMPLATE_PATH . "content-single-{$post_type}.php" )) :
				
				grill_get_template_part( "content", "single-{$post_type}" );
			else :
				grill_get_template_part( 'content', 'single-article' );
			endif;
			?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * grill_after_main_content hook.
		 *
		 * @hooked grill_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'grill_after_main_content' );
	?>

	<?php
		/**
		 * grill_sidebar hook.
		 *
		 * @hooked grill_get_sidebar - 10
		 */
		do_action( 'grill_sidebar' );
	?>

<?php get_footer(); ?>