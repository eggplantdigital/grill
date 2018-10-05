<?php
if ( ! class_exists( 'Grill_Post_Types_Init' ) ) :
/**
 * Grill Post Types Init.
 *
 * This class initiates the grill post types framework.
 *
 * @package		Grill
 * @since		1.6.0
 */
class Grill_Post_Types_Init {
	
	/**
	 * Constructor.
	 *
	 * Initiate the class
	 */
	function __construct() {
		
		// Include required files
		$this->includes();

		//add_action( 'init', array( &$this, 'load_post_types' ) );

		// Enqueue the required JS and CSS.
		add_action( 'admin_init', array( &$this, 'enqueue' ) );
		
	    $activated_post_types = get_option( 'grill_active_post_types' );

		if ( ! empty( $activated_post_types ) && is_array( $activated_post_types ) ) {
			foreach ( $activated_post_types as $file ) {
				$filepath = WP_PLUGIN_DIR.'/'.$file;
				if ( file_exists( $filepath ) ) 
					require_once $filepath;
			}
		}
	}

	/**
	 * Includes
	 * 
	 * Include required core files.
	 */
	function includes() {

		// Load the base class for new post types.
		include_once( "base.php" );
				
		// Loads the class to create and run shortcodes for each post type.
		include_once( "shortcodes.php" );
	}
	
	function load_post_types() {
		
	}

	/**
	 * Enqueue
	 *
	 * Enqueue the required JS and CSS.
	 */
	function enqueue() {
		
		wp_enqueue_script( 'grill-admin', GRILL_URL . '/assets/js/insert.js', array('jquery'), '1.0.0' );		
	}	
}
$grill_post_types = new Grill_Post_Types_Init();
endif;