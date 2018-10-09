<?php
/**
 * Template for displaying the default single post content
 *
 * This template can be overridden by copying it to yourtheme/grill/content-single-article.php.
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
	<?php grill_get_template_part( 'global/image' ); ?>
	<div class="entry-content">
		<?php
		grill_get_template_part( 'global/title', 'single' );			
		grill_get_template_part( 'global/content' ); 
		?>
	</div>
	<?php  grill_get_template_part( 'global/link' ); ?>
</article>