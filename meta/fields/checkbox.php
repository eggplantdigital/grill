<?php
/**
 * Create a Checkbox field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_Checkbox extends Grill_Field {

	/**
	 * Sanitize the checkbox value input
	 * 
	 * @link https://codex.wordpress.org/Function_Reference/sanitize_key
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
		echo '<input type="checkbox" name="'.$this->get_the_name_attr().'" id="'.$this->id.'" size="30" '.(( $this->get_value() =='on' ) ? 'checked' : '').' />';
	}	
}
?>
