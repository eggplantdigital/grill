<?php
/**
 * Create a Textarea field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_TextArea extends Grill_Field {

	/**
	 * Sanitize the textarea value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_textarea_field/
	 * @since 1.0.2
	 */	
	function parse_save_value(){
		$this->value = sanitize_textarea_field( $this->value );
	}

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {
		echo '<textarea rows="5" name="'.$this->get_the_name_attr().'" id="'.$this->id.'">'.esc_textarea( $this->get_value() ).'</textarea>';
	}	
}
?>