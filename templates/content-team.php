<?php
/**
 * Template for displaying the team content
 *
 * This template can be overridden by copying it to yourtheme/grill/content-team.php.
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
		grill_get_template_part( 'team/gravatar' );
		grill_get_template_part( 'team/image' );
		?>
	</header>
	<div class="entry-content">
		<?php
		grill_get_template_part( 'global/title' );	
		grill_get_template_part( 'team/position' );		
		grill_get_template_part( 'global/content' );
		grill_get_template_part( 'team/social', 'icons' );
		?>
	</div>
</article>