<?php
/**
 * Class to create each metabox field.
 *
 * This class is called within the Grill_Post_Types class
 * the options are set within extension class in the metabox_options function.
 *
 * @package		Grill
 * @since		1.0.0
 */	
class Grill_Field {

	/**
	 * Current field value.
	 *
	 * @var mixed
	 */
	public $value;
	
	/**
	 * Current field placement index.
	 *
	 * @var int
	 */
	public $field_index = 0;
	
	/**
	 * Grill_Field constructor.
	 *
	 * @param string $name Field name/ID.
	 * @param string $label Label to display in field.
	 * @param array  $values Values to populate field(s) with.
	 * @param array  $args Optional. Field definitions/arguments.
	 */
	public function __construct( $name, $label, $value, $args = array() ) {

		$this->id    = $name;
		$this->name  = $name/*.'[]'*/;
		$this->label = $label;
		$this->value = $value;
		$this->set_arguments( $args );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}
	
	/**
	 * Establish baseline default arguments for a field.
	 *
	 * @return array Default arguments.
	 */
	public function default_args() {
		return array(
			'desc'                => '',
			'repeatable'          => false,
			'sortable'            => false,
			'repeatable_max'      => null,
			'show_label'          => false,
			'readonly'            => false,
			'disabled'            => false,
			'std'             	  => '',
			'width'               => '',
			'string-repeat-field' => __( 'Add New', 'grill' ),
			'string-delete-field' => __( 'Remove', 'grill' ),
			'confirm_delete'      => true,
		);
	}

	/**
	 * Get the default args for the abstract field.
	 * These args are available to all fields.
	 *
	 * @return array $args
	 */
	public function get_default_args() {
		/**
		 * Filter the default arguments passed by a field class.
		 *
		 * @param array $args default field arguments.
		 * @param string $class Field class being called
		 */
		return apply_filters( 'grill_field_default_args', $this->default_args(), get_class( $this ) );
	}

	/**
	 * Enqueue all scripts required by the field.
	 *
	 * @uses wp_enqueue_script()
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Load stylesheets in admin area for plugin use.
	 *
	 * @uses admin_enqueue_styles
	 */
	function enqueue_styles() {
	}
	
	/**
	 * Render Field.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
	 *
	 * @param int   $post_id
	 * @param $field Supports basic input types `text`, `checkbox`, `textarea` and `select`.
	 */
	public function render_field() {

		// Get the field wrapper and class
		$this->get_field_wrapper_start( $this->args );

		// Print label if necessary.
		$this->label();
		?>
		<div class="grill-field-holder">
			<?php $this->get_field(); ?>
		</div>
		<?php
		// Print description if necessary.
		$this->description();

		// Close of the field
		$this->get_field_wrapper_end();
	}
	
	/**
	 * 
	 * 
	 *
	 * 
	 */		
	function get_field() {}

	/**
	 * 
	 * 
	 *
	 * 
	 */	
	function label() {
		
		if ( $this->args['label'] ) : ?>

			<label for="<?php echo $this->id; ?>">
				<?php echo esc_html( $this->label ); ?>
			</label>
	
		<?php endif;

	}

	/**
	 * 
	 * 
	 *
	 * 
	 */	
	function description() {
		
		//<p class="grill-section-description"><?php echo $field["desc"];
				
		if ( ! empty( $this->args['desc'] ) ) : ?>
		
			<p class="grill-field-description">
				<?php echo $this->args['desc']; ?>
			</p>
			
		<?php endif;

	}
	
	/**
	 * 
	 * 
	 *
	 * 
	 */	
	function get_field_wrapper_start() {
		?>
		<div <?php echo $this->class_attr(); ?> <?php echo $this->get_field_width(); ?>>
		<?php
	}

	/**
	 * 
	 * 
	 *
	 * 
	 */	
	function get_field_wrapper_end() {
		?>
		</div>
		<?php
	}
		
	/**
	 * 
	 * 
	 *
	 * 
	 */		
	public function get_the_name_attr() {
		
		$name = $this->name;

		if ( isset( $this->parent ) ) {			
			
			$name = $this->parent->name . '[' . $this->parent->field_index . '][' . $name . ']';
		}

		return $name;
	}

	/**
	 * 
	 * 
	 *
	 * 
	 */	
	public function class_attr( $classes = '' ) {
		
		// Combine any passed-in classes and the ones defined in the arguments and sanitize them.
		$all_classes = array_unique( explode( ' ', $classes . ' ' ) );
		$classes     = array_map( 'sanitize_html_class', array_filter( $all_classes ) );
		
		$classes[] = "grill-field";

		// Create class based on the field type	
		if ( $this->args['type'] != 'group' ) {	
			
			$classes[] = "grill-{$this->args['type']}";

		}

		if ( ! empty( $this->args['width'] ) ) {
			
			$clasess[] = "has-width";
	
			$classes[] = "width-{$this->args['width']}";
	
		}
		
		if ( $classes = implode( ' ', $classes ) ) { ?>

			class="<?php echo esc_attr( $classes ); ?>"

		<?php }
	}

	/**
	 * Get JS Safe ID.
	 *
	 * For use as a unique field identifier in javascript.
	 *
	 * @return string JS-escaped ID string.
	 */
	public function get_js_id() {
		return str_replace( array( '-', '[', ']', '--' ),'_', $this->id );
	}
	
	/**
	 * 
	 * 
	 *
	 * 
	 */
	public function get_field_width() {
		if ( ! empty( $this->args['width'] ) ) {
			$style = "width:{$this->args['width']}%;";
			return 'style="'.esc_attr( $style ).'"';
		}	
	}

	/**
	 * Define multiple values for a field and completely remove the singular value variable.
	 *
	 * @param array $values Field values.
	 */
	public function set_value( $value ) {
		$this->value = $value;
		unset( $this->values );
	}
	
	/**
	 * Get the existing or default value for a field.
	 *
	 * @return mixed
	 */
	public function get_value() {
		return ( $this->value || '0' === $this->value  ) ? $this->value : $this->args['std'];
	}

	/**
	 * Get multiple values for a field.
	 *
	 * @return array
	 */
	public function get_values() {
		return $this->values;
	}
	
	/**
	 * Define multiple values for a field and completely remove the singular value variable.
	 *
	 * @param array $values Field values.
	 */
	public function set_values( array $values ) {
		$this->values = $values;
		unset( $this->value );
	}
	
	/**
	 * Setup arguments for the class.
	 *
	 * @param $arguments
	 */
	public function set_arguments( $arguments ) {
		// Initially set arguments up.
		$this->args = wp_parse_args( $arguments, $this->get_default_args() );
	}

	/**
	 * Parse and validate a single value.
	 *
	 * Meant to be extended.
	 */
	public function parse_save_value() {}
	
	/**
	 * Save values for the field.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $value Value to save.
	 */
	public function save( $post_id ) {
		
		// Don't save readonly values.
		if ( $this->args['readonly'] ) {
			return;
		}
		
		// If we are not on a post edit screen.
		if ( ! $post_id ) {
			return;
		}

		// Get the post meta for the current field
        $old = get_post_meta( $post_id, $this->id, true );

		// Parse and validate values
		$this->parse_save_value();

        // If there is a difference between the 'new' posted field and the 'old' saved field
        if ( ( $this->value || '0' === $this->value ) && ! $this->is_array_empty( $this->value[0] ) && $this->value != $old ) :

			// Save the post meta as the newly posted data
			update_post_meta( $post_id, $this->id, $this->value );

		// If the old field exists, but the new field is empty
        elseif ( '' == $this->value && $old ) :

	        // Delete the old post meta
            delete_post_meta( $post_id, $this->id, $old );
        
        // If the old field exists, but the new field is an empty array (group field)
        elseif ( is_array( $this->value ) && ( $this->is_array_empty( $this->value[0] ) || empty( $this->value ) ) && $old ) :
			
	        // Delete the old post meta
            delete_post_meta( $post_id, $this->id, $old );
        
        endif;		
	}	
	
	function is_array_empty($array) {
		
		if ( $array == NULL )
			return false;
			
		if ( ! is_array( $array ) )
			return false;

	    foreach($array as $key => $val) {
	        if ($val !== '')
	            return false;
	    }
	    return true;
	}

	
	/**
	 * Decode JSON
	 *
	 * Attempts to decode json into an array.
	 * This new function accounts for servers
	 * running an older version of PHP with
	 * magic quotes gpc enabled.
	 * 
	 * @param  string  $str   - JSON string to convert into an array
	 * @param  boolean $accoc [- Whether to return an associative array
	 * @return array - Decoded JSON array
	 */
	public static function json_decode( $str = '', $accoc = false ) {
		$json_string = get_magic_quotes_gpc() ? stripslashes( $str ) : $str;
		return json_decode( $json_string, $accoc );
	}
}