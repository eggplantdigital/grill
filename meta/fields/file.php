<?php
/**
 * Create a File field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_File extends Grill_Field {

	/**
	 * Sanitize the file value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_key/
	 * @since 1.0.2
	 */	
	function parse_save_value(){
		$this->value = sanitize_key( $this->value );
	}

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {
		$aid = $this->get_value();
		
		if ( $aid ) {
			$url = wp_get_attachment_url( $aid );
		} ?>
		<div class="grill-file-wrapper <?php echo ( $aid ) ? 'grill-file-preview' : ''; ?>" data-type="file">
			<div class="grill-file-holder">
				<?php if ( $aid ) :
						echo wp_get_attachment_image( $aid, 'thumbnail', true ); ?>
						<div class="filename"><strong><?php echo esc_attr( basename ( get_attached_file( $aid ) )); ?></strong></div>
				<?php endif; ?>
			</div>
			<div class="grill-remove-file <?php echo ( !$aid ) ? 'hidden' : ''; ?> dashicons-before dashicons-no-alt"></div>
			<input type="hidden" name="<?php echo $this->get_the_name_attr(); ?>" id="<?php echo $this->id; ?>" value="<?php echo esc_attr( $aid ); ?>" class="grill-file-upload-input" />
			<input type="button" class="button grill-file-upload <?php echo ( $aid ) ? 'hidden' : ''; ?>" value="<?php _e('Choose File', 'grill'); ?>" />
		</div>
		<?php
	}
	
	/**
	 * Load JS scripts in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_scripts
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( 'grill-meta-file-js', GRILL_URL . '/assets/js/meta.file.js', array( 'jquery' ) );
	}		
}