<?php
/**
 * Create a color field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_Color extends Grill_Field {

	/**
	 * Sanitize the color value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_hex_color/
	 * @since 1.0.2
	 */	
	function parse_save_value(){
		$this->value = sanitize_hex_color( $this->value );
	}

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {
		echo '<input type="text" name="'.$this->get_the_name_attr().'" id="'.$this->id.'>" value="'.esc_attr( $this->get_value() ).'" size="30" class="grill-select-color" />';
	}	

	/**
	 * Load JS scripts in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_scripts
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( 'grill-meta-color-js', GRILL_URL . '/assets/js/meta.color.js', array( 'jquery', 'wp-color-picker' ) );
	}
}
?>