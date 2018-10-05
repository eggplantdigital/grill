<?php
class Grill_Group extends Grill_Field {
	
	/**
	 * Fields arguments and information.
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * CMB_Group_Field constructor.
	 */
	function __construct() {
		
		// You can't just put func_get_args() into a function as a parameter.
		$args = func_get_args();
		call_user_func_array( array( 'parent', '__construct' ), $args );
						
		if ( ! empty( $this->args['fields'] ) ) {
			foreach ( $this->args['fields'] as $f ) {
				
				$args = $f;
				unset( $args['id'] );
				unset( $args['name'] );
						
				$this->add_field( new Grill_Field( $f['id'], $f['label'], $value, $args ) );
			}
		}
	}

	/**
	 * Display output for group.
	 */
	public function render_field() {
		
		global $post;
		
		$field = $this->args;
		$values = $this->get_value();

		// Get the field wrapper and class
		$this->get_field_wrapper_start( $this->args );
		
		// Print label if necessary.
		$this->label();

		if ( ! $this->args['repeatable'] && empty( $values ) ) {
			$values = array( null );
		} else {
			$values = $this->get_value(); // Make PHP5.4 >= happy.
			$values = ( empty( $values ) ) ? array( '' ) : $values;
		}

		$i = 0;
		foreach ( $values as $value ) {

			$this->field_index = $i;
			//$this->value = $value;	
			?>

			<div class="grill-group" data-confirm-delete="<?php echo $this->args['confirm_delete']; ?>">
				<?php $this->render_group( $value ); ?>
			</div>

			<?php
			$i++;
		}

		if ( $this->args['repeatable'] ) {
			$this->field_index = 'x'; // X used to distinguish hidden fields.
			$this->value = '';

			?>

			<div class="grill-repeatable-group hidden" data-index="<?php echo $i; ?>" data-confirm-delete="<?php echo $this->confirm_delete; ?>">
				<?php $this->render_group(); ?>
			</div>

			<button class="button repeat-field">
				<?php echo esc_html( $this->args['string-repeat-field'] ); ?>
			</button>

		<?php }

		// Close of the field
		$this->get_field_wrapper_end( $field );			
			
	}

	/**
	 * Print out group field HTML.
	 */
	public function render_group( $value=array() ) {

		$fields = $this->get_fields();

		// Reset all field values.
		foreach ( $fields as $field ) {
			$field->set_values( array() );
		}

		// Set values for this field.
		if ( ! empty( $value ) ) {
			foreach ( $value as $field_id => $field_value ) {
				$field_value = ( ! empty( $field_value ) ) ? $field_value : array();
				if ( ! empty( $fields[ $field_id ] ) ) {
					$fields[ $field_id ]->set_value( $field_value );
				}
			}
		}
		?>

		<?php if ( $this->args['repeatable'] ) : ?>
			<button class="grill-delete-field">
				<span class="grill-delete-field-icon">&times;</span>
				<span class="grill-delete-field-string"><?php echo esc_html( $this->args['string-delete-field'] ); ?></span>
			</button>
		<?php endif; ?>

		<?php Grill_Meta_Box::render_fields( $fields ); ?>

	<?php }

	/**
	 * Assemble all defined fields for group.
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}
		
	/**
	 * Add assigned fields to group data.
	 *
	 * @param Grill_Field $field Field object.
	 */
	public function add_field( Grill_Field $field ) {
		$field->parent = $this;
		$this->fields[ $field->id ] = $field;
	}
	
	/**
	 * Set values for each field in the group.
	 *
	 * @param array $values Existing or default values for all fields.
	 */
	public function set_values( array $values ) {
		
		$fields       = $this->get_fields();
		$this->values = $values;
		
		// Reset all field values.
		foreach ( $fields as $field ) {
			$field->set_values( array() );
		}
		
		foreach ( $values as $value ) {
			foreach ( $value as $field_id => $field_value ) {
				$fields[ $field_id ]->set_values( (array) $field_value );
			}
		}
		
	}		
}