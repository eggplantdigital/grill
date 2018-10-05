<?php
if ( $icon = get_post_meta( get_the_ID(), '_fontawesome_font', true ) ) :

	$icon_size     = get_post_meta( get_the_ID(), '_fontawesome_size', true );
	$icon_bg_color = get_post_meta( get_the_ID(), '_fontawesome_bg_color', true );
	$icon_color    = get_post_meta( get_the_ID(), '_fontawesome_color', true );
	?>
<div class="ico-holder ico-size-<?php echo $icon_size; ?>" style="background: <?php echo $icon_bg_color; ?>;color: <?php echo $icon_color; ?>;">
	<i class='fa fa-<?php echo $icon; ?>'></i>
</div>
	<?php
endif;