<?php
/**
 * Template for displaying the testimonial content
 *
 * This template can be overridden by copying it to yourtheme/grill/content-testimonial.php.
 *
 * @see 	    
 * @package 	Grill/Templates
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php grill_get_template_part('global/image'); ?>
	<?php grill_get_template_part('testimonial/blockquote'); ?>	
				
	<footer class="clearfix">
		<?php grill_get_template_part('global/title'); ?>
		<?php grill_get_template_part('testimonial/meta'); ?>
	</footer>
</article>