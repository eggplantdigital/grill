<?php
/**
 * Create a Text field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_Text extends Grill_Field {

	/**
	 * Sanitize the text value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_text_field/
	 * @since 1.0.2
	 */	
	function parse_save_value(){
		$this->value = sanitize_text_field( $this->value );
	}

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {
		echo '<input type="text" name="'.$this->get_the_name_attr().'" id="'.$this->id.'" value="'.esc_attr( $this->get_value() ).'" size="30" />';
	}	
}