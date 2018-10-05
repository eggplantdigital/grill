<?php
$aid = $this->get_value();

if ( $aid ) :

	$url = wp_get_attachment_url( $this->get_value() );

endif; ?>

<div class="grill-file-wrapper <?php echo ( $aid ) ? 'grill-file-preview' : ''; ?>" data-type="file">
	<div class="grill-file-holder">
		<?php if ( $aid ) :
				echo get_attachment_icon( $aid ); ?>
				<div class="filename"><strong><?php echo basename ( get_attached_file( $aid ) ); ?></strong></div>
		<?php endif; ?>
	</div>
	<div class="grill-remove-file <?php echo ( !$aid ) ? 'hidden' : ''; ?> dashicons-before dashicons-no-alt"></div>
	<input type="hidden" name="<?php echo $this->get_the_name_attr(); ?>" id="<?php echo $this->id; ?>" value="<?php echo $aid; ?>" class="grill-file-upload-input" />
	<input type="button" class="button grill-file-upload <?php echo ( $aid ) ? 'hidden' : ''; ?>" value="<?php _e('Choose File', 'grill'); ?>" />
</div>