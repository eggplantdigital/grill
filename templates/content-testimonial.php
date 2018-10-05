<?php
/**
 * Template for displaying either grid or list items
 *
 * Used for shortcode.
 *
 * @package WordPress
 * @subpackage Grill
 * @since Grill 1.0.0
 *
 * Post type settings array get_option('grill_settings')
 *
 *     @slug	string 		permalink of the post type for the single page 
 * 	   @type	string 		1 - include a single page / 2 - Use a popup modal box / 3 - no single page
 *	   @parent  numerical 	id number of the parent page for this post type.
 *
 * Shortcode options var $sc_atts global variable of the shortcode attributes
 *
 *     @num		numerical	number of postst to show.
 *     @cols	numerical	number of columns 1,2,3,4,5 or 6.
 * 	   @content string		display the except, content or none.
 *	   @pid		numerical	only display a single post by id.
 *	   @gid		numerical	display posts within a particular term.
 *	   @orderby string		alphabetically, by menu order, by date, randomly.
 *	   @order	string		ascending or descending.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php grill_get_template_part('global/image'); ?>
	<?php grill_get_template_part('testimonial/blockquote'); ?>	
				
	<footer class="clearfix">
		<?php grill_get_template_part('global/title'); ?>
		<?php grill_get_template_part('testimonial/meta'); ?>
	</footer>
</article>