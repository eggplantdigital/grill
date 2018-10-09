<?php
/**
 * Template for displaying a default content article
 *
 * This template can be overridden by copying it to yourtheme/grill/content-article.php.
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
	<header class="entry-header">
		<?php 
		grill_get_template_part( 'global/icon' );
		grill_get_template_part( 'global/title' );
		?>
	</header>
	<div class="entry-content">
		<?php
		grill_get_template_part( 'global/content' ); 
		grill_get_template_part( 'global/link' );
		?>
	</div>
	<div class="entry-footer"></div>
</article>