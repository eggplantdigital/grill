<?php
if ( ! class_exists( 'Grill_Post_Type_Settings' ) ) :
/**
 * Grill Post Type Settings
 *
 * Includes the functions to add settings for each post type.
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_Post_Type_Settings {
	/**
	 * Post type. (max. 20 characters, cannot contain capital letters or spaces).
	 *
	 * @var string $post_type
	 */
	public $post_type;
	
	/**
	 * Post Slug. Default setting as made in the post-types folder.
	 *
	 * @var string $post_slug
	 */
	public $post_slug;

	/**
	 * Post Public. Default setting as made in the post-types folder.
	 *
	 * @var string $post_public
	 */
	public $post_public;

	public function __construct( $post_type, $post_args=array() ) {
		
		$this->post_type = $post_type;

		if ( isset( $post_args['slug'] ) )
			$this->post_slug   = $post_args['slug'];
		
		if ( isset( $post_args['public'] ) )
			$this->post_public = $post_args['public'];
		
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}
		
	function settings_init() { 
		
		register_setting( 'grill-settings', 'grill_settings', array( $this, 'validate_input' ) );
		
		add_settings_field( 
			$this->post_type . '_slug', 
			sprintf( __( '%s Slug', 'grill' ), $this->post_type ), 
			array( $this, 'slug_field_render' ), 
			'grill-settings', 
			'grill_permalink_section'
		);	

		add_settings_field( 
			$this->post_type . '_page_type',
			sprintf( __( '%s Page', 'grill' ), $this->post_type ), 
			array( $this, 'page_type_field_render' ), 
			'grill-settings', 
			'grill_single_pages_section' 
		);

		add_settings_field( 
			$this->post_type . '_parent_page', 
			sprintf( __( '%s Parent', 'grill' ), $this->post_type ), 
			array( $this, 'parent_page_field_render' ), 
			'grill-settings', 
			'grill_parent_pages_section' 
		);

		add_settings_field( 
			$this->post_type . '_sidebar', 
			sprintf( __( '%s Sidebar', 'grill' ), $this->post_type ), 
			array( $this, 'sidebar_field_render' ), 
			'grill-settings', 
			'grill_sidebar_section' 
		);			
	}

	function slug_field_render() { 
	
		$options = get_option( 'grill_settings' );
		$field   = '_slug_' . $this->post_type;
		$default = $this->post_slug;

		?>
		<input type='text' name='grill_settings[<?php echo $field; ?>]' value='<?php echo ( isset( $options[$field] ) && $options[$field]!='' ) ? $options[$field] : $this->post_slug; ?>'>
		<?php
	}
	
	function page_type_field_render() { 
	
		$options = get_option( 'grill_settings' );
		$field = '_type_' . $this->post_type;

		if ( $this->post_public === NULL ) {
			$default = 1;
		} else {
			$default = 3;
		}
		
		$checked = ( isset( $options[$field] ) && $options[$field] != NULL ) ? $options[$field] : $default;		
		?>
		
		<fieldset>
			<label>
				<input type='radio' name='grill_settings[<?php echo $field; ?>]' <?php checked( $checked, 1 ); ?> value='1'>
				<span class=""><?php _e('Include Single Page', 'grill'); ?></span>
			</label><br>
			<label>
				<input type='radio' name='grill_settings[<?php echo $field; ?>]' <?php checked( $checked, 2 ); ?> value='2'>
				<span class=""><?php _e('Use Pop Up', 'grill'); ?></span>
			</label><br>		
			<label>
				<input type='radio' name='grill_settings[<?php echo $field; ?>]' <?php checked( $checked, 3 ); ?> value='3'>
				<span class=""><?php _e('None', 'grill'); ?></span>
			</label><br>			
		</fieldset>
		<?php
	
	}

	function parent_page_field_render() { 
	
		$options = get_option( 'grill_settings' );
		$pages   = get_all_page_ids();
		$field   = '_parent_' . $this->post_type;
		
		$selected = ( isset( $options[$field] ) ) ? $options[$field] : '';
		?>
		<fieldset>
			<label>
				<select name='grill_settings[<?php echo $field; ?>]'>
					<option value=''>- <?php _e('None', 'grill'); ?> -</option>
					<?php foreach ( $pages as $id ) { ?>
					<option value='<?php echo $id; ?>' <?php selected( $selected, $id ); ?>><?php echo get_the_title($id); ?></option>
					<?php } ?>
				</select>
			</label>
		</fieldset>
		<?php
	}
	
	function sidebar_field_render() {

		$options = get_option( 'grill_settings' );
		$select  = array(
			'one-col'		   => 'No Sidebars',
			'two-col-left'     => 'Righthand Sidebar',
			'two-col-right'    => 'Lefthand Sidebar',
			'three-col-middle' => 'Both'
		);
		$field   = '_sidebar_' . $this->post_type;
		
		$selected = ( isset( $options[$field] ) ) ? $options[$field] : '';
		?>
		<fieldset>
			<label>
				<select name='grill_settings[<?php echo $field; ?>]'>
					<?php 
					foreach ( $select as $key => $title ) { ?>
					
					<option value='<?php echo $key; ?>' <?php selected( $selected, $key ); ?>><?php echo $title; ?></option>
					<?php } ?>
				</select>
			</label>
		</fieldset>
		<?php		
	}
	
	function validate_input( $input ) {

	    // Create our array for storing the validated options
	    $output = array();
	     
	    // Loop through each of the incoming options
	    foreach( $input as $key => $value ) {

	        // Check to see if the current option has a value. If so, process it.
	        if( isset( $input[$key] ) ) {
				
				// Check if this is the slug field.
	         	if ( substr($key, 0, 6)  == '_slug_' ) {
		         	
		         	// Sanitize the slug.
		         	$output[$key] = sanitize_title( $input[ $key ] );
	         	
	         	} else {
		            // Strip all HTML and PHP tags and properly handle quoted strings.
		            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
				}
				
	        } // end if
	         
	    } // end foreach

	    // Return the array processing any additional functions filtered by this action
	    return $output;
	}	
}
endif;
?>