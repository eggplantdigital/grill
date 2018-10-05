<?php
if ( ! class_exists( 'Grill_Post_Types' ) ) :
/**
 * Base class to create a Post Type for Grill or a Child Theme 
 *
 * this class should be extended in a new class,
 * and certain method must be overrided to apply your custom post types
 *
 * @package		Grill
 * @since		1.6.0
 */
class Grill_Post_Types {

	/**
	 * Textdomain used for translation. Use the set_textdomain() method to set a custom textdomain.
	 *
	 * @var string $textdomain Used for internationalising. Defaults to "grill" without quotes.
	 */
	public $textdomain = 'grill';
	
	/**
	 * Post type. (max. 20 characters, cannot contain capital letters or spaces).
	 *
	 * @var string $post_type
	 */
	public $post_type;
	
	/**
	 * An array of arguments used to register your post type.
	 *
	 * @var array $post_args
	 */
	public $post_args;
	
	/**
	 * The taxonomies that need to be registered.
	 *
	 * @var array $taxonomies
	 */
	public $taxonomies;
	
	/**
	 * The meta boxes that need to be registered to this post type.
	 *
	 * @var array $metaboxes
	 */
	public $metaboxes;
	
	/**
	 * Settings controlled by the user in Settings > Post Types screen.
	 *
	 * @var array $settings
	 */
	public $settings;
	
	/**
	 * Constructor
	 *
	 * Register a custom post type.
	 *
	 * @param mixed $post_type The name of the post type.
	 * @param array $args User submitted arguments for the post type.
	 */
	public function __construct( $post_type, $post_args = array() ) {
		
		$this->includes();
		
		// Set the post type to the object.
		$this->post_type = $post_type;
		
		// Save the options to the settings variable.
		$this->settings = get_option('grill_settings');

		if ( is_array( $post_args ) ) :
			
			// Args we need to check for (required).
			$names = array(
				'singular',
				'plural',
				'slug'
			);
			
			// Cycle through the names we need applied.
			foreach ( $names as $name ) {
				
				// Check if is already set up the user.
				if ( ! isset( $post_args[ $name ] ) ) {
					
					// Prepare the method.
					$method = 'get_' . $name;
					
					// Generate the name.
					$post_args[ $name ] = $this->$method();		
				}
			}
		
		endif;
		
		// Set the user submitted options to the argument.
		$this->post_args = $post_args;

		// Register the post type
		add_action( 'init', array( &$this, 'register_post_type' ) );

		// Register taxonomies
		add_action( 'init', array( &$this, 'register_taxonomies' ) );

		// Register shortcode options
		add_action('init', array(&$this, 'register_shortcodes') );			
		
		// Register scripts and styles options
		add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue') );
		
		// Alter the layout of the single page according to the Theme Settings
		add_filter( 'salt_layout', array( $this, 'sidebar_layout' ), 1 );
		
		// Add settings for this particular post type
		if ( is_admin() ) {
			if ( class_exists( 'Grill_Post_Type_Settings' ) )
				$grill_post_type_settings = new Grill_Post_Type_Settings( $post_type, $post_args );

			// Register the post meta options to show on the edit post page
			add_action('init', array(&$this, 'register_metaboxes') );
		}
	}
	
	function includes() {
		//include_once GRILL_DIR . 'core/presets/repeatable.php';
	}

	/**
	 * Get slug
	 *
	 * Creates an url friendly slug.
	 *
	 * @param  string $name Name to slugify.
	 * @return string $name Returns the slug.
	 */
	function get_slug( $name = null ) {
		
		// If no name set use the post type name.
		if ( ! isset( $name ) ) {
			$name = $this->post_type;
		}
		
		// Name to lower case.
		$name = strtolower( $name );
		
		// Replace spaces with hyphen.
		$name = str_replace( " ", "-", $name );
		
		// Replace underscore with hyphen.
		$name = str_replace( "_", "-", $name );
		
		return $name;
	}
	
    /**
	 * Get plural
	 *
	 * Returns the friendly plural name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of _ to space
	 *
	 * @param  string $name The slug name you want to pluralize.
	 * @return string the friendly pluralized name.
	 */
	function get_plural( $name = null ) {
		
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type;
		}
				
		// Return the plural name. Add 's' to the end.
		return $this->get_human_friendly( $name ) . 's';
    }
    
	/**
	 * Get singular
	 *
	 * Returns the friendly singular name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of _ to space
	 *
	 * @param string $name The slug name you want to unpluralize.
	 * @return string The friendly singular name.
	 */
	function get_singular( $name = null ) {
		
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type;
		}
		
		// Return the string.
		return $this->get_human_friendly( $name );
    }
 
    /**
	 * Get human friendly
	 *
	 * Returns the human friendly name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of hyphens and underscores to spaces
	 *
	 * @param string $name The name you want to make friendly.
	 * @return string The human friendly name.
	 */
	function get_human_friendly( $name = null ) {
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type_name;
		}
		// Return human friendly name.
		return ucwords( strtolower( str_replace( "-", " ", str_replace( "_", " ", $name ) ) ) );
	}
    
	/**
	 * Register Post Type
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */	
	function register_post_type() {

		$post_args = $this->post_args;

		// Friendly post type names.
		$plural   = $post_args['plural'];
		$singular = $post_args['singular'];
		
		if ( isset( $this->settings[ '_slug_'. $this->post_type ] ) )
			$slug_setting = $this->settings[ '_slug_'. $this->post_type ];
	
		if ( isset( $this->settings[ '_type_'. $this->post_type ] ) )
			$type_setting = $this->settings[ '_type_'. $this->post_type ];
		
		if ( isset( $slug_setting ) && $slug_setting != '' ) {
			$slug = $slug_setting;
		} else {
			$slug = $post_args['slug'];
		}

		if ( isset( $type_setting ) && $type_setting != '' ) {
			if ( $type_setting == 3 ) {
				$post_args['public'] = false;
			} elseif ( $type_setting == 1 || $type_setting == 2 ) {
				$post_args['public'] = true;
			}
		}
		
		// Default labels.
		$labels = array(
			'name'               => sprintf( __( '%s', 'grill' ), $plural ),
			'singular_name'      => sprintf( __( '%s', 'grill' ), $singular ),
			'menu_name'          => sprintf( __( '%s', 'grill' ), $plural ),
			'all_items'          => sprintf( __( '%s', 'grill' ), $plural ),
			'add_new'            => __( 'Add New', 'grill' ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'grill' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'grill' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'grill' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'grill' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'grill' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found', 'grill' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'grill' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'grill' ), $singular )
		);

		// Default options.
		$default_args = array(
			'labels'  => $labels,
			'public'  => true,
			'show_ui' => true,
			'rewrite' => array(
				'slug' => $slug,
			)
		);
		
		// Merge user submitted options with defaults.
		$args = wp_parse_args( $post_args, $default_args);

		// Check that the post type doesn't already exist.
        if ( ! post_type_exists( $this->post_type ) ) {
		
			// Register the post type.
			register_post_type( $this->post_type, $args );
			
			// Set the variable to include the post types
			Grill()->grill_post_types[] = $this->post_type;
		}
	}

	/**
	 * Register taxonomy
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 *
	 * @param string $taxonomy The name for the taxonomy.
	 * @param array  $tax_args Taxonomy options.
     */	
	function register_taxonomy( $taxonomy, $tax_args=array() ) {

		if ( is_array( $tax_args ) ) :
			
			// Args we need to check for (required).
			$names = array(
				'singular',
				'plural',
				'slug'
			);
			
			// Cycle through the names we need applied.
			foreach ( $names as $name ) {
				
				// Check if is already set up the user.
				if ( ! isset( $tax_args[ $name ] ) ) {
					
					// Prepare the method.
					$method = 'get_' . $name;
					
					// Generate the name.
					$tax_args[ $name ] = $this->$method( $taxonomy );		
				}
			}
		
		endif;
		
		// Friendly taxonomy names.
		$singular = $tax_args['singular'];
		$plural   = $tax_args['plural'];
		$slug 	  = $tax_args['slug'];
		
		// Default labels.
		$labels = array(
			'name'                       => sprintf( __( '%s', 'grill' ), $plural ),
			'singular_name'              => sprintf( __( '%s', 'grill' ), $singular ),
			'menu_name'                  => sprintf( __( '%s', 'grill' ), $plural ),
			'all_items'                  => sprintf( __( 'All %s', 'grill' ), $plural ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'grill' ), $singular ),
			'view_item'                  => sprintf( __( 'View %s', 'grill' ), $singular ),
			'update_item'                => sprintf( __( 'Update %s', 'grill' ), $singular ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'grill' ), $singular ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'grill' ), $singular ),
			'parent_item'                => sprintf( __( 'Parent %s', 'grill' ), $plural ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'grill' ), $plural ),
			'search_items'               => sprintf( __( 'Search %s', 'grill' ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'grill' ), $plural ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'grill' ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'grill' ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from most used %s', 'grill' ), $plural ),
			'not_found'                  => sprintf( __( 'No %s found', 'grill' ), $plural ),
		);
		
		// Default options.
		$default_args = array(
			'labels' 			=> $labels,
			'hierarchical'		=> true,
			'rewrite'			=> array( 'slug' => $slug ),
			'show_admin_column' => true
		);
		
		// Merge default options with user submitted options.
		$args = wp_parse_args($tax_args, $default_args);
		
		// Add the taxonomy to an array.
		$this->taxonomies[ $taxonomy ] = $args;
	}

	/**
	 * Register taxonomies
	 *
	 * Cycles through taxonomies added with the class and registers them.
	 */
	function register_taxonomies() {
		
		if ( is_array( $this->taxonomies ) ) {
			
			// Cycle through the taxonomies that will be registered.
			foreach ( $this->taxonomies as $taxonomy => $args ) {
				
				// Check if the taxonomy exists.
				if ( ! taxonomy_exists( $taxonomy ) ) {
					
					// Register the taxonomy.
					register_taxonomy( $taxonomy, $this->post_type, $args );
					
					// Set the variable to include the post types
					Grill()->grill_taxonomies[] = $taxonomy;
				} else {
					
					// If taxonomy exists, attach exisiting taxonomy to post type.
					register_taxonomy_for_object_type( $taxonomy, $this->post_type );
				}
			}
			
			// Include a filter for taxonomy in the backend
			add_action( 'restrict_manage_posts', array( $this, 'add_tax_filters') );
		}
	}

	/**
	 * Backend filter by taxonomy
	 *
	 * The filter dropdown allows sorting taxonomy on the Sections list table.
	 * @link https://developer.wordpress.org/reference/hooks/restrict_manage_posts/
	 */
	function add_tax_filters() {
		global $typenow;
	  
		if( $typenow == $this->post_type ) {
			
			foreach ( $this->taxonomies as $taxonomy => $args ) {
				
				$terms = get_terms( $taxonomy );
					
				if ( count( $terms ) > 0 ) {
		
					echo '<select name="'.$taxonomy.'" class="postform">';
					echo '<option value="">'.__('All', 'grill').' '.$args["labels"]["name"].'</option>';
		
					foreach ($terms as $term)
						echo '<option value='. $term->slug, isset( $_GET[ $taxonomy ] ) && $_GET[ $taxonomy ] == $term->slug ? ' selected="selected"' : '','>' . $term->name . '</option>'; 
					
					echo '</select>';
				}
			}
		}
	}

	/**
	 * Register Metabox
	 *
	 * Register a metabox with this custom post type.
	 *
	 * @param string $metabox   The ID of the metabox.
	 * @param array  $meta_args The arguments for the metabox.
     */	
    function register_metabox( $metabox, $meta_args=array() ) {
	    
	    $this->metaboxes[ $metabox ] = $meta_args;
    }
      	
	/**
	 * Register Metabox Options 
	 *
     */	
	function register_metaboxes() {
		
		// if an array of meta box args is passed.
		if ( is_array($this->metaboxes) ) {
			
			// Cycle thourgh the meta box array.
			foreach ( $this->metaboxes as $id => $settings ) {

				// 'background' is a preset type, so add its own particular structure.
				if ( $id=='background' ) {
					
					// Use the predefined background meta box class.
					$post_metabox = new Grill_Background_Meta_Box( $this->post_type );
				
				// 'links' is a preset type, so add its own particular structure.
				} elseif ( $id=='link' ) {
					
					// Use the predefined background meta box class.
					$post_metabox = new Grill_Link_Meta_Box( $this->post_type );
				
				// 'text' is a preset type, so add its own particular structure.
				} elseif ( $id=='text' ) {
					
					// Use the predefined background meta box class.
					$post_metabox = new Grill_Text_Meta_Box( $this->post_type );
				
				} elseif ( $id=='fontawesome' ) {
					
					// Use the predefined background meta box class.
					$post_metabox = new Grill_FontAwesome_Meta_Box( $this->post_type );
				
				}  elseif ( $id=='group' ) {
					
					// Use the predefined background meta box class.
					$post_metabox = new Grill_Group_Meta_Box( $this->post_type, $settings );
				
				} else {
					
					// Set the ID
					$settings['id'] = $id;
					
					// Use the settings defined in the metabox_options array.
					$post_metabox = new Grill_Meta_Box( $this->post_type, $settings );
				}
			}
		}		
	}
	
	/**
	 * Register Shortcode
	 *
	 * Creates the options and registers the shortcode.
	 */
	function register_shortcode( $shortcode_id, $shortcode_args=array() ) {
		
		$this->shortcode_id   = $shortcode_id;
		$this->shortcode_args = $shortcode_args;
	}
	
	/**
	 * Register Shortcodes
	 *
	 * Create a new shortcode for the passed id and arguments.
	 */
	function register_shortcodes() {

		$this->shortcode_args['post_type'] = $this->post_type;
		$this->shortcode_args['menu_icon'] = $this->post_args['menu_icon'];
		$this->shortcode_args['plural']    = $this->post_args['plural'];
		
		if ( is_array( $this->templates ) ) {
			$this->shortcode_args['views'] = $this->templates;
		}
		
		// Create a new shortcode options for this post new type.
		if ( class_exists( 'Grill_Post_Type_Shortcodes' ) )
			$sc = new Grill_Post_Type_Shortcodes( $this->shortcode_id, $this->shortcode_args );
		
		// Register the shortcode with WordPress so it will display.
		add_shortcode( $this->shortcode_id, array( &$this, 'display' ) );		
	}
	
	/**
	 * Get Posts
 	 *
	 * Using the attributes from the shortcode query the custom post.
	 *
	 * @return array Holds the results of the query.
	 */
	function get_posts( $atts ) {
		
		global $wp_query;
		
		$limit = ( !empty( $atts['num'] ) && is_numeric( $atts['num'] ) ) ? intval( $atts['num'] ) : -1;
		$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;

		$args = array( 
			'post_type' 	 => $this->post_type,
			'posts_per_page' => $limit, 
			'paged' 		 => $paged
		);
	
		$args['orderby'] = ( isset($atts["orderby"]) ) ? $atts["orderby"] : 'menu_order';		
		$args['order'] =  ( isset($atts["order"]) ) ? $atts["order"] : 'DESC'; 
		
		$wp_query = new WP_Query( apply_filters( 'grill_'.$this->post_type.'_get_posts', $args, $atts ) );
		
		return $wp_query;
	}
	
	function register_template( $template_name, $template_args=array() ) {
		
		// 
		$this->templates[ $template_name ] = $template_args;
	}
	
	/**
	 * Display
 	 *
	 * Create the shortcode using the template view provided 
	 *
	 * @return string The final HTML that is passed and echoed in wp_content
	 */
	function display( $atts, $content = null, $code="" ) {
		
		global $post_type, $sc_atts, $grill_query;
		
		// Pass the post type through to the display template.
		$post_type = $this->post_type;

		// By the default we will use the grid layout.
		$shortcode_atts = array( 'display' => '' );
		
		// Check if any attributes are being passed with the shortcode.
		if ( $atts ) {
			// Cycle through the available attributes.
			foreach ( $atts as $key => $value ) {
				// Cycle through each of the presets for the fields.
				if ( $this->shortcode_args['fields'] ) foreach ( $this->shortcode_args['fields'] as $field ) {
					// Compare the key to the field id to see if it matches.
					if ( $key == strtolower( $field['id'] ) ) {
						// If the field default is set, add it to the shortcode_atts array.
						if ( isset( $field['std'] ) ) {
							$shortcode_atts[$key] = $field['std'];
						} else {
							$shortcode_atts[$key] = '';
						}
					}
				} 
			} 
			
			// Combines user shortcode attributes with known attributes and fills in defaults when needed.
			if ( ! empty( $shortcode_atts ) ) { 
				$atts = shortcode_atts( $shortcode_atts, $atts );
			
			} 
		}
	
		// Get the directory containing the post type and files.
		if ( isset( $atts['display'] ) ) {
			$display = $atts['display'];
		} 

		// Pass the shortcode atts through to the template file
		$sc_atts = $atts;

		// Get the posts for this post type and passed shortcode attributes.
		$grill_query = $this->get_posts( $atts );

		// Get the view that has been chosen by the user.
		if ( isset( $display ) && $display != '' )
			$view_file = $this->templates[$display]["template"];
		else
			$view_file = reset($this->templates)["template"]; 

		$output = '';

		// Check the view file exists.
		if ( is_array( $view_file ) ) {
			// Start the output buffering.
			ob_start();
			// Include the template file.
			grill_get_template_part( $view_file['slug'], $view_file['name'] );
			// Put file contents in an output variable ... And stops buffering.
			$output = ob_get_clean();
		} elseif (file_exists($view_file)) {
			// Start the output buffering.
			ob_start();
			// Include the template file.
			include($view_file);
			// Put file contents in an output variable ... And stops buffering.
			$output = ob_get_clean();
		}
		
		// Reset the query		
		wp_reset_query();
		
		// Return the outputted html as string.
		return $output;
	}

	/**
	 * Sidebar Layout
 	 *
	 * Alter the layout of the single page according to the Theme Settings 
	 *
	 * @return string $layout taken from the settings
	 */	
	function sidebar_layout( $layout ) {
		
		$options = get_option( 'grill_settings' );
		$field   = '_sidebar_' . $this->post_type;

		if ( is_single() && get_post_type() == $this->post_type ) :

			return $options[ $field ];
		
		endif;
		
		return $layout;
	}
	
	/**
	 * Enqueue Script
	 *
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 *
	 */
	public function enqueue_script( $handle='', $src='', $deps=array(), $ver='', $in_footer=false ) {
		
		$this->scripts[ $handle ] = array( $src, $deps, $ver, $in_footer );
	}
	
	/**
	 * Enqueue Style
	 *
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	 *
	 */
	public function enqueue_style( $handle='', $src='', $deps=array(), $ver=false, $media='all' ) {
	
		$this->styles[ $handle ] = array( $src, $deps, $ver, $media );
	}
	
	/**
	 * Enqueue
	 *
	 *
	 */
	function enqueue() {
		
		if ( ! empty( $this->scripts ) ) {
			
			foreach ( $this->scripts as $handle => $args ) {
				
				wp_enqueue_script( $handle, $args[0], $args[1], $args[2], $args[3] );
			}
		}

		if ( ! empty( $this->styles ) ) {
			
			foreach ( $this->styles as $handle => $args ) {
				
				wp_enqueue_style( $handle, $args[0], $args[1], $args[2], $args[3] );
			}
		}		
	}
}
endif;