<?php
/**
 * Create a Select field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_Select extends Grill_Field {

	/**
	 * Sanitize the select value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_text_field/
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
		?>
		<select name="<?php echo $this->get_the_name_attr(); ?>" id="<?php echo $this->id; ?>">
			<?php if ( is_array( $this->args['options'] ) ) foreach ( $this->args['options'] as $key => $value ) : ?>
		    	<option value="<?php echo $key; ?>" <?php echo ( $this->get_value()==$key ) ? 'selected' : ''; ?>><?php echo esc_attr( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}	
}