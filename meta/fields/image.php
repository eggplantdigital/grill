<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<div class="grill-image-upload-wrapper">
	<div class="grill-image-display grill-image-upload-button">
		<!-- Image -->
		<?php if ( isset( $meta ) && $meta != '' ) : ?>
		<?php $img = wp_get_attachment_image_src( $meta, 'medium' ); ?>
		<div class="grill-background-image-holder">
			<img src="<?php echo $img[0]; ?>" class="grill-background-image-preview" />
		</div>
		<a class="grill-image-remove" href="#"><span class="dashicons dashicons-no"></span></a>
		<?php else : ?>
		<div class="placeholder"><span class="dashicons dashicons-format-image"></span></div>
		<!-- Remove button -->
		<a class="grill-image-remove hidden" href="#"><span class="dashicons dashicons-no"></span></a>
		<?php endif; ?>
	</div>
	<input type="hidden" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php if ( isset ( $meta ) ) echo $meta; ?>" class="grill-image-upload-field" />
	<input type="button" class="button button-primary grill-image-button grill-choose-image" value="<?php _e('Choose Image', 'grill'); ?>" />
</div>