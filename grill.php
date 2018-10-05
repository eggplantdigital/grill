<?php
/**
 * Plugin Name: Grill
 * Plugin URI: https://wordpress.org/plugins/grill
 * Description: Grill let's you install a bunch of content types to use within your WordPress site
 * Version: 1.0.1
 * Author: Eggplant Digital
 * Author URI: https://eggplantdigital.cn
 * License: A "Slug" license name e.g. GPL2
 */

/**
 * Main Grill Class
 *
 * Contains the main functions for the Grill Apps, and handles error messages
 *
 * @class		Grill_Core
 * @since		File available since Release 1.0.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Grill_Core' ) ) :

class Grill_Core {

	/**
	 * Grill version.
	 *
	 * @var string
	 */
	public $version = '1.0.1';

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.1
	 */
	protected static $_instance = null;

	/**
	 * An array of the registered post types.
	 *
	 * @var array
	 * @since 1.0.1
	 */
	public $grill_post_types = array();

	/**
	 * An array of the registered taxonomies.
	 *
	 * @var array
	 * @since 1.0.1
	 */
	public $grill_taxonomies = array();
	
	/**
	 * Main Grill Instance.
	 *
	 * Ensures only one instance of Grill is loaded or can be loaded.
	 *
	 * @since 1.0.1
	 * @static
	 * @see Grill()
	 * @return Grill - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Grill_Core Constructor.
	 *
	 * @param void
	 * @return void
	 *
	 * @access public
	 */
	 
	/**
	 * Grill Constructor.
	 */
	public function __construct() {
/*
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
*/

// 	public function init() {
		define('GRILL_VERSION', '1.0.1');
		define('GRILL_MIN_WP_VERSION', '3.5');

		// TODO MOVE THIS
		$grill_options = array();
		
		if ( get_option( 'grill_options' ) != NULL ) {
			$grill_options = get_option( 'grill_options' );
		}

		$api_key = ( $grill_options != NULL ) ? $grill_options['_gmaps_api_key'] : '';
		
		define('GOOGLE_API_KEY', $api_key );
		define('GRILL_DIR', plugin_dir_path( __FILE__) );
		define('GRILL_TEMPLATE_PATH', GRILL_DIR.'templates/');
		define('GRILL_URL', plugins_url('/' . basename(GRILL_DIR)));

		// Initiate Grill
		add_action( 'init', array( $this, 'grill_init' ) );
		
		// Load VC mapping
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		// Load stylesheets & scripts
		add_action( 'init', array( $this, 'register_styles' ) );
		
		// Load stylesheets & scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles' ) );

		/*
		 * Load Helper functions
		 *
		 * @since Grill 1.0.0
		 */			
		require_once 'helpers/grid.php';
		require_once 'helpers/post.php';
		require_once 'helpers/template.php';
		require_once 'helpers/template-loader.php';
		
		/*
		 * Load Templates & Hooks
		 *
		 * @since Grill 1.0.1
		 */
		//require_once 'includes/template-functions.php';
		require_once 'includes/template-hooks.php';		
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		
		/*
		 * Load Core Post Type & Post Meta Classes
		 *
		 * @since Grill 1.0.0
		 */
		require_once 'meta/init.php';
		require_once 'meta/field.php';
		require_once 'meta/group.php';
		require_once 'admin/init.php';
		require_once 'core/init.php';
		
        // Plugin activation/deactivation hooks
        register_activation_hook( __FILE__, array( &$this, 'activate' ) );
        
		do_action( 'grill_loaded' );
	}

	/**
	 * Function used to Init WooCommerce Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		include_once( 'includes/template-functions.php' );
	}
	
	/* Register Plugin Frontend Styles & Scripts */
	function register_frontend_styles() {
		
		$grill_options = get_option( 'grill_options' );

 		if ( ! isset( $grill_options['_exclude_bootstrap'] ) ) {
			wp_enqueue_style( 'bootstrap' 	, GRILL_URL . '/assets/css/bootstrap.min.css', false, '3.2.0');
		}
		
		if ( ! isset( $grill_options['_exclude_fontawesome'] ) ) {
			wp_enqueue_style( 'fontawesome'	, GRILL_URL . '/assets/css/font-awesome.min.css', false, '4.2.0');
		}

		if ( ! isset( $grill_options['_exclude_gmaps'] ) && GOOGLE_API_KEY != NULL ) {
			wp_enqueue_script( 'gmap', 'https://maps.google.com/maps/api/js?sensor=false&key='.GOOGLE_API_KEY, array( 'jquery' ) );
		}
		
		wp_enqueue_style( 'bxslider', GRILL_URL . '/assets/css/bxslider.min.css', false, '1.1.0');
		wp_enqueue_style( 'mfp', GRILL_URL . '/assets/css/magnific-popup.min.css', false, '1.1.0');
		wp_enqueue_style( 'social', GRILL_URL . '/assets/css/social.css', false, '1.0');
		wp_enqueue_style( 'grill-main', GRILL_URL . '/assets/css/grill.css', false, '1.0.0');
		
		wp_enqueue_script( 'bxslider', GRILL_URL . '/assets/js/bxslider.min.js', array('jquery'), '1.1.0', true );
		wp_enqueue_script( 'mfp', GRILL_URL . '/assets/js/magnific-popup.min.js', array('jquery'), '1.1.0', true );
		wp_enqueue_script( 'grill_global', GRILL_URL . '/assets/js/global.js', array('jquery'), '1.0.0', true );
	}

	/* Register Plugin Styles & Scripts */
	function register_styles() {
	
/*
		$grill_options = get_option( 'grill_options' );
		if ( ! isset( $grill_options['_exclude_gmaps'] ) ) {
			wp_enqueue_script( 'gmap', 'https://maps.google.com/maps/api/js?sensor=false&key='.GOOGLE_API_KEY, array( 'jquery' ) );
		}
*/
	}
		
    /* Plugin activation */
	function activate() {
		        
        // Flush rewrite rules (so the post-type permalink structure works)
        flush_rewrite_rules();
    }
    
    function load_plugin_textdomain() {
	}
	
	function grill_init() {
		require_once( GRILL_DIR .'/composer/params.php' );
		require_once( GRILL_DIR .'/composer/shortcodes.php' );
		require_once( GRILL_DIR .'/composer/map.php' );
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Get the template path.
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'grill_template_path', 'grill/' );
	}	
}

function grill_get_post_types() {
	$grill_post_types = array(
 		'grill/post-types/donate.php',
 		'grill/post-types/faq.php',
		'grill/post-types/project.php',
 		'grill/post-types/outreach.php',
 		'grill/post-types/team.php',
 		'grill/post-types/slider.php'
	);
	
	return apply_filters( 'grill_init_post_type', $grill_post_types );
} 

/**
 * Main instance of Grill.
 *
 * Returns the main instance of Grill to prevent the need to use globals.
 *
 * @since  1.0.1
 * @return Grill
 */
function Grill() {
	return Grill_Core::instance();
}
Grill();
endif;