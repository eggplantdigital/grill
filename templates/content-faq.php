<?php
/**
 * Template for displaying FAQ content
 *
 * This template can be overridden by copying it to yourtheme/grill/content-faq.php.
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
	<header class="entry-header">
		<?php 
		grill_get_template_part( 'faq/title' );
		?>
	</header>
	<div class="entry-content">
		<?php
		grill_get_template_part( 'global/content' ); 
		?>
	</div>
</article>