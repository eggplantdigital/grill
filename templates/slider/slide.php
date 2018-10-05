<?php
/**
 * Single Slide
 *
 * The template for displaying a single slide within the slider.
 *
 * @package WordPress
 * @subpackage Grill
 * @since Grill 1.0.0
 */
 
global $sc_atts;

if ( isset( $sc_atts['height'] ) )
	$height = "height:{$sc_atts['height']};";
else
	$height = "height:450px;";
	
if ( $image_src = get_the_post_thumbnail_url( get_the_ID(), 'full' ) )
	$image_html = 'style="background: url('.$image_src.')no-repeat;background-size:cover;"';

?>
<li id="bx-slide-<?php echo get_the_ID(); ?>" class="slide" <?php echo $image_html; ?>>
	<div class="slide-content" style="<?php echo $height; ?>">
		<div class="slide-container">		
			<div class="copy-container">
				<div class="container">
					<div class="row">
					<?php grill_get_template_part( 'slider/parts/title' ); ?>
	
					<?php grill_get_template_part( 'slider/parts/excerpt' ); ?>
	
					<?php grill_get_template_part( 'slider/parts/link' ); ?>
					
					<?php edit_post_link( __( 'Edit Slide', 'salt' ), '<span class="edit-link">', '</span>' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>