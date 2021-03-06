<?php
/**
 * The Template for displaying all taxonomy / archive pages
 *
 * This template can be overridden by copying it to yourtheme/grill/archive.php.
 *
 * @see 	    
 * @package 	Grill/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_type = get_post_type();

get_header(); ?>

	<?php
		/**
		 * grill_before_main_content hook.
		 *
		 * @hooked grill_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action( 'grill_before_main_content' );
	?>

		<?php
		if ( apply_filters( 'grill_show_page_title', true ) ) :
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		endif;
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
			
				<?php while ( have_posts() ) : the_post(); ?>	
			
					<?php grill_get_post_template(); ?>
					
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

			<?php endif; ?>

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