<?php
if ( ! class_exists( 'Grill_Meta_Box' ) ) :
/**
 * Class to create a meta box on a post type.
 *
 * This class is called within the Grill_Post_Types class
 * the options are set within extension class in the metabox_options function.
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_Meta_Box {
	
	/**
	 * Fields in a collection.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * User defined string for the post type assigned on __construct(). 
	 *
	 * @var string Hold the post type that this meta box is added to.
	 */	
	public $post_type;
	
	/**
	 * Constructor
	 *
	 * Initiate the class.
	 *
	 * @param string $post_type The name of the post type.
	 * @param array $settings User submitted options.
	 */
	public function __construct( $post_type = 'post', $args=array() ) {

		$this->_meta_box = $args;
		
		$this->post_type = $post_type;

		$this->init();
	}

	/**
	 * Init
	 *
	 * Register the hooks to add the meta and save it.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_action
	 */
	public function init() {
		
		add_action( 'dbx_post_advanced', array( &$this, 'init_fields_for_post' ) );
		
		// Enqueue scripts used with the default meta boxes.
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles' ) );
		add_action( 'wp_ajax_grill_post_select', array( $this, 'grill_ajax_post_select' ) );

		// Add the meta box using WordPress function.
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );

		// Save the post when it's updated.
		add_action( 'save_post', array( &$this, 'save_post_meta' ) );

	}

	/**
	 * Initialize metabox box fields.
	 *
	 * @param int $post_id Optional. Post ID.
	 */	
	public function init_fields( $post_id=0 ){

		if ( $this->_meta_box['fields'] ) {

			foreach ( $this->_meta_box['fields'] as $field ) {
	
				$value = '';
	
				$args = $field;
				unset( $args['id'] );
				unset( $args['name'] );
				
				// Get metadata value of the field for this post.
				$value = get_post_meta( $post_id, $field['id'], true );
	
				if ( /* $field['repeatable'] &&  */ $field['type'] == 'group' ) {		
		
					$field = new Grill_Group( $field['id'], $field['label'], $value, $args );				
					
				} else {
	
					$field = new Grill_Field( $field['id'], $field['label'], $value, $args );
					
				}
	
	//			if ( $field->is_displayed( $post_id ) ) {
					$this->fields[] = $field;
	//			}
			}
		}
	}

	/**
	 * Initialize fields during the metabox loading process.
	 *
	 * @global int $post
	 *
	 * @uses dbx_post_advanced
	 *
	 * @return bool false if post ID fails or is on wrong screen.
	 */
	public function init_fields_for_post() {
		
		global $post;
		$post_id = null;
		
		// Get the current ID.
		if ( isset( $_GET['post'] ) ) {
			$post_id = wp_unslash( $_GET['post'] );
		} elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = wp_unslash( $_POST['post_ID'] );
		} elseif ( ! empty( $post->ID ) ) {
			$post_id = $post->ID;
		}
		
		if ( is_page() || ! isset( $post_id ) ) {
			return false;
		}
		
		if ( ! is_numeric( $post_id ) || $post_id != floor( $post_id ) ) {
			return false;
		}
		
		$this->init_fields( (int) $post_id );
	}

	/**
	 * Load JS scripts in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_scripts
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_style( 'select2', GRILL_URL . '/assets/css/select2.min.css' );
		wp_enqueue_script( 'select2', GRILL_URL . '/assets/js/select2.min.js', array('jquery') );
		//wp_enqueue_script( 'grill-meta-select2-js', GRILL_URL . '/assets/js/meta.select2.js', array( 'jquery', 'select2' ) ); 
	
		wp_enqueue_script( 'grill-meta-file-js', GRILL_URL . '/assets/js/meta.file.js', array( 'jquery' ));
        wp_enqueue_script( 'grill-meta-js', GRILL_URL . '/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );
		wp_localize_script( 'grill-meta-js', 'GrillMetaData', array(
			'strings' => array(
				'confirmDeleteField' => esc_html__( 'Are you sure you want to delete this field?', 'grill' ),
			),
		) );    
		
		foreach ( $this->fields as $field ) {
			$field->enqueue_scripts();
		}		   
	}
	
	/**
	 * Load stylesheets in admin area for plugin use.
	 *
	 * @uses admin_enqueue_styles
	 */
	function enqueue_styles() {
		
		wp_enqueue_style(  'grill-meta-styles', GRILL_URL . '/assets/css/meta.css', false, '1.0.0' );
		wp_enqueue_style( 'fontawesome'	, GRILL_URL . '/assets/css/font-awesome.min.css', false, '4.2.0');	

		foreach ( $this->fields as $field ) {
			$field->enqueue_styles();
		}
	}
	
   /**
	 * Adds the meta box on the edit post screen
	 * 
	 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
	 */
	public function add_meta_box() {
		
		// Add the meta box using the WordPress function
		if ( isset( $this->_meta_box->template ) ) {
			
			global $post;
			if ( $this->_meta_box->template == 'front-page' &&
				get_option('page_on_front') == $post->ID || 
			    get_page_template_slug( $post->ID ) == $this->_meta_box->template ) {
				
				add_meta_box (
			        $this->_meta_box['id'],
			        $this->_meta_box['title'],
					array( $this, "render" ),
					$this->post_type,
					$this->_meta_box['context'],
					$this->_meta_box['priority'] );
			
				add_filter( "postbox_classes_{$this->post_type}_{$this->_meta_box->id}", array( $this, 'postbox_classes' ) );
			}
			
		} else {
			add_meta_box (
		        $this->_meta_box['id'],
		        $this->_meta_box['title'],
				array( $this, "render" ),
				$this->post_type,
				$this->_meta_box['context'],
				$this->_meta_box['priority'] );
				
			add_filter( "postbox_classes_{$this->post_type}_{$this->_meta_box['id']}", array( $this, 'postbox_classes' ) );
		}
	}
	
	/**
	 * Classes to add to the post meta box
	 */
	public function postbox_classes( $classes ) {
		$classes[] = 'grill-box';
		return $classes;
	}
	
	/**
	 * Render
	 *
	 * Renders the input wrapper and calls $this->render_loop() for the foreach of fields.
	 */
	public function render() {
		?>

		<input type="hidden" name="grill_metabox_nonce" value="<?php esc_attr_e( wp_create_nonce( basename( __FILE__ ) ) ); ?>" />

		<?php 
		$this->render_fields( $this->fields );
	}

	/**
	 * Layout an array of fields, depending on their 'cols' property.
	 *
	 * This is a static method so other fields can use it that rely on sub fields.
	 *
	 * @param array $fields Fields in a collection.
	 */
	public function render_fields( array $fields ) {
		?>
		<div class="grill-fields-container">
		
		<?php $current_width = 0; $section = false; $row = false;

		// Render the section tabs, if any sections exist.
		Grill_Meta_Box::render_section_tabs( $fields );
			
		foreach ( $fields as $field ) :
			
			if ( $field->args['type'] == 'section' ) :

				if ( $section ) : 

					if ( $row ) : ?>
						
						</div><!-- .grill-fields-row -->
					
					<?php $row = false;
						
					endif; ?>
					
					</div><!-- .grill-fields-section -->
						
				<?php endif; ?>
				
				<div id="<?php echo $field->id; ?>" class="grill-fields-section">
					
				<?php $field->description(); ?>				
			
			<?php $section = true;
				
			endif;  			
			
			if ( 0 == $current_width && $field->args['type'] != 'section' && $field->args['type'] != 'group' ) : ?>

				<div class="grill-fields-row">

			<?php $row = true;
				
			endif;

			if ( $field->args['width'] == '' ) {
				$field->args['width'] = 100;
			}

			$current_width += $field->args['width'];

/* TODO - need this soon
if ( ! empty( $field->args['repeatable'] ) ) {
	$classes[] = 'repeatable';
}
			
$attrs = array(
	sprintf( 'id="%s"', sanitize_html_class( $field->id ) ),
	sprintf( 'class="%s"', esc_attr( implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ) ),
);
// Field Repeatable Max.
if ( isset( $field->args['repeatable_max'] ) ) {
	$attrs[] = sprintf( 'data-rep-max="%s"', intval( $field->args['repeatable_max'] ) );
}
// Ask for confirmation before removing field.
if ( isset( $field->args['confirm_delete'] ) ) {
	$attrs[] = sprintf( 'data-confirm-delete="%s"', $field->args['confirm_delete'] ? 'true' : 'false' );
}
*/
						
			$field->render_field();

			if ( $current_width > 99 || $field === end( $fields ) && $field->args['type'] != 'group' ) :
			$current_width = 0; ?>
			
				</div><!-- .grill-fields-row -->
			
			<?php $row = false;
				
			endif;

			if ( $section && $field === end( $fields ) ) : ?>
				
				</div><!-- .grill-fields-section -->
					
			<?php $section = false;
				
			endif;  			
	
		endforeach; ?>
		
		</div>
		
	<?php }
	
	/**
	 * Render Tabs
	 *
	 * Renders the tabs that are used to navigate the different sections.
	 */
	public function render_section_tabs( $fields ) {
		$tabs='';
		// Loop through the fields
		foreach ( $fields as $field ) {
			
			// If the type is a section, we need to create a tab.
			if ( $field->args['type'] == 'section' ) {
				// Create a tab item.
				$tabs .= '<li><a href="javascript:" title="'.$field->args['label'].'" data-link="'.$field->id.'"><span>'.$field->args['label'].'</span></a></li>';
			}
		}	
		
		// If any items are created, we can wrap that in a ul tag list.
		if ( $tabs ) {
			
			// Create tabs list.
			echo '<ul class="grill-tabs-nav">'.$tabs.'</ul><!-- grill-tabs-nav -->';
			
		}		
	}
	

	/**
	 * Verify Post Meta
	 *
	 * Safety net for the post_meta save
	 *
	 * @param integer $post_id Pass the id of the current post.
	 */	
	public function verify_post_meta( $post_id ) {

		// Verify the nonce field exists - won't on quick edit.
		if ( ! isset( $_POST['grill_metabox_nonce'] ) ) {
			return $post_id;
		}
			
		// Verify the nonce field that is added in the metabox.
	    if ( ! wp_verify_nonce( $_POST['grill_metabox_nonce'], basename( __FILE__ ) ) ) {
	        return $post_id;
	    }
	    
	    // Make sure we are not doing an Auto Save.
	    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	        return $post_id;
	    }

		// Verify if this post type is the same as the current post.
		if ( $this->post_type != $_POST['post_type'] ) {
			return $post_id;
		}

/*
// Verify this meta box is for the right post type
if ( ! in_array( get_post_type( $post_id ), (array) $this->_meta_box['pages'], true ) ) {
	return $post_id;
}
*/
							
		// Check if we are editing a page.
	    if ( 'page' == $_POST['post_type'] ) {
	    
	    	// Check if this user is allowed to edit pages.
	        if ( !current_user_can( 'edit_page', $post_id ) ) 
	        	return $post_id;
		
		// Check if this user can edit other post types.
	    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
		
			return $post_id;
	    }	
	}
	
	/**
	 * Save Post Meta
	 *
	 * Save the post meta fields we have added using settings
	 * 
	 * @param integer $post_id Pass the ID of the post we are saving
	 */	
	public function save_post_meta( $post_id ) {
		// Call the function to verify we should be here.
	    if ( ! $this->verify_post_meta( $post_id ) ) :
			
		    // Cycle through the settings
		    foreach ( $this->_meta_box['fields'] as $field ) :
				
				if ( isset( $_POST[ $field['id'] ] ) ) {
					$value = $_POST[ $field['id'] ];
				} else {
					$value = '';
				}

				if ( /* $field['repeatable'] &&  */$field['type'] == 'group' ) {		

					$value = $this->strip_repeatable( $value );
		
					$field_obj = new Grill_Group( $field['id'], $field['label'], $value, $field );				
					
				} else {
	
					$field_obj = new Grill_Field( $field['id'], $field['label'], $value, $field );
					
				}
				
				$field_obj->save( $post_id, $value );

		    endforeach;
	    endif;
    }

	/**
	 * Remove unwanted hidden field values recursively.
	 *
	 * @param array $values Field value(s).
	 * @return array mixed Cleaned value(s)
	 */
	function strip_repeatable( $values ) {

		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( false !== strpos( $key, 'x' ) || false !== strpos( $key, 'x' ) ) {
					unset( $values[ $key ] );
				}
			}
		}
		
		return $values;
	}

	/**
	 * AJAX callback for select fields.
	 */	
	public function grill_ajax_post_select(){
	 	
		$post_id = ! empty( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;
		$nonce   = ! empty( $_POST['nonce'] ) ? $_POST['nonce'] : false;
		$args    = ! empty( $_POST['query'] ) ? $_POST['query'] : array();
		
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'grill_select_field' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			echo json_encode( array( 'total' => 0, 'posts' => array() ) );
			exit;
		}
		
		$args['s']      = $_POST['query']['q'];
		$args['fields'] = 'ids'; // Only need to retrieve post IDs.

		$query = new WP_Query( $args );

		foreach ( $query->posts as $post_id ) {
			$json[] = array( $post_id, html_entity_decode( get_the_title( $post_id ) ) );
		}

		echo json_encode( $json );

		exit;
		
	}
	
}

/**
 * Class to extend the main Meta Box class with one specifically to add background images.
 *
 * This class is called within the Grill_Post_Types class with predefined options.
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_Background_Meta_Box extends Grill_Meta_Box {
	
	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type  The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$this->_meta_box = array(
			'id'		 => 'background',
			'title'      => __('Background', 'grill'),
			'pages'      => array( $this->post_type ),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(	
				array(
			        'label'	=> __('Upload Image', 'grill'),
					'desc'	=> __('Upload Image', 'grill'),
			        'id'    => '_background_image',
			        'type'  => 'image'
				),
				array(
			        'label'	=> __('Set Image Height (px)', 'grill'),
			        'std' 	=> '450',
			        'id'    => '_background_height',
			        'type'  => 'text',
			        'width'	=> 50
				),
				array(
			        'label'	=> __('Background Color', 'grill'),
			        'id'    => '_background_color',
			        'type'  => 'color',
			        'width'	=> 50
				),
				array(
			        'label'	=> __('Repeat', 'grill'),
			        'id'    => '_background_repeat',
			        'type'  => 'select',
			        'width'	=> 25,
			        'options' => array(
				        'no-repeat' => 'No Repeat',
				        'repeat'	=> 'Repeat',
				        'repeat-x'  => 'Repeat Horizontally',
				        'repeat-y'  => 'Repeat Vertically'
			        )
				),
				array(
			        'label'	=> __('Position', 'grill'),
			        'id'    => '_background_position',
			        'type'  => 'select',
			        'width'	=> 25,
			        'options' => array(
				        'center' => 'Center',
				        'top' 	 => 'Top',
				        'bottom' => 'Bottom',
				        'left' 	 => 'Left',
				        'right'  => 'Right'
			        )
				),
				array(
			        'label'	=> __('Stretch', 'grill'),
			        'id'    => '_background_stretch',
			        'type'  => 'checkbox',
			        'width'	=> 25
				),
				array(
			        'label'	=> __('Darken', 'grill'),
			        'id'    => '_background_darken',
			        'type'  => 'checkbox',
			        'width'	=> 25
				)
			)
		);
		
		// Initiate the meta box
		$this->init();
	}
	
	/**
	 * Enqeue
	 * 
	 * Enqueue background iamge related scripts.
	 */
	function enqueue() {
	
		// Enqueue the media panel.
        wp_enqueue_media();
        
        // Enqueue metabox styling
		wp_enqueue_style(  'grill-meta-css', GRILL_URL.'/assets/css/meta.css', false, '1.0.0' );

		// Enqueue the stylesheet for the color picker.
		wp_enqueue_style( 'wp-color-picker' );
		
		// Enqueue the JS to initiate the color picker if it is displayed.
        wp_enqueue_script( 'grill-meta-js', GRILL_URL . '/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );
        		
        // Register and enqueue the JS to add a background image.
        wp_localize_script( 'grill-meta-js', 'meta_image',
            array(
                'title' => __( 'Set background image', 'grill' ),
                'button' => __( 'Set background image', 'grill' ),
            )
        );
        wp_enqueue_script( 'grill-meta-js' );
	}
}

/**
 * Class to extend the main Meta Box class with one specifically to add links / buttons.
 *
 * This class is called within the Grill_Post_Types class with predefined options.
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_Link_Meta_Box extends Grill_Meta_Box {
	
	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$this->_meta_box = array(
			'id'		 => 'link',
			'title'      => __('Link / Button', 'grill'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label' => __('URL', 'grill'),
					'id'    => '_link_url',
					'type'  => 'text',
					'width' => 25
				),
				array(
					'label' => __('Label', 'grill'),
					'id'    => '_link_text',
					'type'  => 'text',
					'width' => 25
				),
				array(
					'label' => __('Type', 'grill'),
					'id'    => '_link_type',
					'type'  => 'select',
					'width' => 25,
					'options' => array(
						'' 	 	 => 'Text Link',
						'button' => 'Button'
					)
				),
				array(
					'label' => __('Open in Tab', 'grill'),
					'id'    => '_link_target',
					'type'  => 'checkbox',
					'width' => 25
				),
			)
		);
		
		// Initiate the meta box
		$this->init();		
	}
}

/**
 * Class to extend the main Meta Box class with one specifically to add a simple text box.
 *
 * This class is called within the Grill_Post_Types class with predefined options.
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_Text_Meta_Box extends Grill_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var stinrg The prefix used on the id for the fields
	 */
	public $prefix = '_text_';

	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$this->_meta_box = array(
			'id'		 => 'text',
			'title'      => __('Text', 'grill'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label' => '',
					'id'    => $this->prefix.'copy',
					'type'  => 'textarea'
				),
				array(
					'label' => __('Color', 'grill'),
					'id'    => $this->prefix.'color',
					'type'  => 'color',
					'width' => 50
				),
				array(
					'label' => __('Align', 'grill'),
					'id'    => $this->prefix.'align',
					'type'  => 'select',
					'options' => array(
						'left'	 => 'Left',
						'center' => 'Center',
						'right'  => 'Right'
					),
					'width' => 50
				),
				array(
					'label' => __('Font Size', 'grill'),
					'id'    => $this->prefix.'size',
					'type'  => 'select',
					'options' => array(
						'small'	 => 'Small',
						'medium' => 'Medium',
						'large'  => 'Large'
					),
					'width' => 50
				),
				array(
					'label' => __('Position', 'grill'),
					'id'    => $this->prefix.'position',
					'type'  => 'select',
					'options' => array(
						''	 	 => 'Above Featured Image',
						'image-right' => 'Left of Featured Image',
						'image-left'  => 'Right of Featured Image'
					),
					'width' => 50
				)
			)
		);
		
		// Initiate the meta box
		$this->init();		
	}	
}

/**
 * Class to extend the main Meta Box class with one specifically to add a simple text box.
 *
 * This class is called within the Grill_Post_Types class with predefined options.
 *
Icon select
Icon position - next to title - above 
Icon color
Icon background color
Icon size 
Icon Border
 *
 * @package		Grill
 * @since		1.0.0
 */
class Grill_FontAwesome_Meta_Box extends Grill_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var stinrg The prefix used on the id for the fields
	 */
	public $prefix = '_icon_';

	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		$fa_list = $this->get_fontawesome_icons_list();
		
		// Create the settings for this meta box 
		$this->_meta_box = array(
			'id'		 => 'fontawesome',
			'title'      => __('Icons', 'grill'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label'   => __('Select an icon', 'grill'),
					'id'      => '_fontawesome_font',
					'type'    => 'fontawesome',
					'options' => $fa_list,
					'width'   => 50
				),
				array(
					'label' => __('Icon Size', 'grill'),
					'id'    => '_fontawesome_size',
					'type'  => 'select',
					'width' => 50,
					'options' => array(
						'tiny'	 => __('Very Small', 'grill'),
						'small'	 => __('Small', 'grill'),
						'medium' => __('Medium', 'grill'),
						'large'	 => __('Large', 'grill'),
						'huge'	 => __('Very Large', 'grill'),
					)
				),
				array(
					'label' => __('Background Color', 'grill'),
					'id'    => '_fontawesome_bg_color',
					'type'  => 'color',
					'width' => 50,
				),
				array(
					'label' => __('Color', 'grill'),
					'id'    => '_fontawesome_color',
					'type'  => 'color',
					'width' => 50
				),
				
			)
		);
		
		// Initiate the meta box
		$this->init();		
	}

	/**
	 * Enqeue
	 * 
	 * Enqueue background iamge related scripts.
	 */
	function enqueue_scripts() {
	
		// Enqueue the media panel.
        wp_enqueue_media();
		
		// Enqueue the fontawesome
		wp_enqueue_script( 'grill-meta-fa-js', GRILL_URL . '/assets/js/meta.fa.js', array( 'jquery' ));	
        
        // Enqueue metabox styling
		wp_enqueue_style( 'grill-meta-css', GRILL_URL.'/assets/css/meta.css', false, '1.0.0' );

		// Enqueue the stylesheet for the color picker.
		wp_enqueue_style( 'wp-color-picker' );
		
		// Enqueue the JS to initiate the color picker if it is displayed.
        wp_enqueue_script( 'grill-meta-js', GRILL_URL . '/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );
        
        // Enqueue fontawesome to load on the backend.
    	wp_enqueue_style( 'fontawesome'	, GRILL_URL . '/assets/css/font-awesome.min.css', false, '4.2.1');        
	}
		
	/**
	 * FontAwesome 4.6.3 json list
	 *
	 * A helpful list FontAwesome fonts for use within the beautiful meta fields of this theme.
	 *
	 * @link https://github.com/FortAwesome/Font-Awesome/blob/master/src/icons.yml
	 * @link converted to json with help from http://yamltojson.com/
	 */
 	public function get_fontawesome_icons_list() {
 		
		$fa_json  = wp_remote_fopen( GRILL_URL . '/assets/fonts/fontawesome.json' );

		return $this->json_decode( $fa_json, true );
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
endif;