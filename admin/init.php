<?php
if ( ! class_exists( 'Grill_Settings' ) ) :
/**
 * Grill Settings
 *
 * Includes the functions to add settings for each post type.
 *
 * @package		Grill
 * @since		1.0.0
 */

class Grill_Settings {
	
	/**
	 * Init
	 *
	 * Initiate the class.
	 *
	 * @param array $options Holds the options for this particular shortcode
	 * @param string $post_type The post type this shortcode will be used to display
	 */
	public function __construct() {
		
		include_once( 'post-type-settings.php' );
		
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
		add_action( 'admin_init', array( $this, 'post_types_init' ) );
		add_action( 'admin_init', array( $this, 'post_type_settings_init' ) );
		add_action( 'admin_init', array( $this, 'general_settings_init' ) );
	}

	/**
     * Create the settings pages.
	 */	
	function add_settings_menu() { 
				
		$hook = add_options_page( 
			__('Grill Post Types', 'grill'), 
			__('Grill', 'grill'), 
			'manage_options', 
			'grill-post-types', 
			array( $this, 'grill_settings')
		);
	
		add_action( 'load-'.$hook, array( $this, 'refresh_permalinks' ));

		add_options_page( 
			__('Settings', 'grill'), 
			__('Settings', 'grill'), 
			'manage_options', 
			'grill-post-type-settings',
			array( $this, 'grill_settings') 
		);
		
		add_options_page( 
			__('Options', 'grill'), 
			__('Options', 'grill'), 
			'manage_options', 
			'grill-settings',
			array( $this, 'grill_settings') 
		);
		
		add_action( 'admin_head', array( &$this, 'remove_menus') );
	}
	
	/**
     * Remove menu items, so there only 'Grill' on the main menu.
	 */
	function remove_menus() {
	    remove_submenu_page( 'options-general.php', 'grill-post-type-settings' );
	    remove_submenu_page( 'options-general.php', 'grill-settings' );
	}
	
	/**
     * Post type activate setup.
	 */
	function post_types_init() {
		
		register_setting( 'grill-post-types', 'grill_active_post_types', array( $this, 'validate_input' ) );
		
		add_settings_section(
			'grill_post_types_section', 		// String for use in the 'id' attribute of tags
			__('Post Types', 'grill'),			// Title of the section
			array( $this, 'section_callback' ), // Function that fills the section with the desired content
			'grill-post-types'					// The menu page on which to display this section
		);
		
		add_settings_field( 
			'grill_active_post_types',
			false, 
			array( $this, 'grill_post_types' ), 
			'grill-post-types', 
			'grill_post_types_section' 
		);
		
	}
	
	/**
     * Add the settings sections for the Post Type Settings page
	 */
	function post_type_settings_init() {
		
		add_settings_section(
			'grill_permalink_section',
			__('Permalinks', 'grill'),
			array($this,'section_callback'),
			'grill-settings'
		);

		add_settings_section(
			'grill_single_pages_section',
			__('Single Pages', 'grill'),
			array($this,'section_callback'),
			'grill-settings'
		);
		
		add_settings_section(
			'grill_parent_pages_section',
			__('Parent Pages', 'grill'),
			array($this,'section_callback'),
			'grill-settings'
		);
		
		add_settings_section(
			'grill_sidebar_section',
			__('Sidebars', 'grill'),
			array($this,'section_callback'),
			'grill-settings'
		);
	}
	
	/**
     * Section subtitle callback.
	 */
	function section_callback( $arg ) {
		if ( $arg['id'] == 'grill_permalink_section' )
			echo '<p>'.sprintf( __('Alter the permalink for the activated post types. Make sure you <a href="%s">resave Permalinks</a> after editing.', 'grill'), esc_url( admin_url( 'options-permalink.php' ) )).'</p>'; 
			
		if ( $arg['id'] == 'grill_single_pages_section' )
			echo '<p>'.__('Select whether to have single pages for your post types, or use a pop-up, or turn them off completely.', 'grill').'</p>';

		if ( $arg['id'] == 'grill_parent_pages_section' )
			echo '<p>'.__('Select the page your post types will be displayed on, this allows the correct menu item to be highlighted.', 'grill').'</p>';
			
		if ( $arg['id'] == 'grill_sidebar_section' )
			echo '<p>'.__('If you are using a single post then select how your the sidebar will be displayed.', 'grill').'</p>';
	}
	
	/**
     * Creates the main page template.
	 */
	function grill_settings() {
		?>
		<div class="wrap">
			<h1><?php _e('Grill Settings', 'grill'); ?></h1>

			<h2 class="nav-tab-wrapper">
				<a href="options-general.php?page=grill-post-types" class="nav-tab <?php echo ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-post-types' ) ? 'nav-tab-active':''; ?>">
					<?php _e('Post Types', 'grill'); ?>
				</a>
				<a href="options-general.php?page=grill-post-type-settings" class="nav-tab <?php echo ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-post-type-settings' ) ? 'nav-tab-active':''; ?>">
					<?php _e('Post Type Settings', 'grill'); ?>
				</a>
				<a href="options-general.php?page=grill-settings" class="nav-tab <?php echo ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-settings' ) ? 'nav-tab-active':''; ?>">
					<?php _e('Options', 'grill'); ?>
				</a>
			</h2>

			<form action='options.php' method='post'>
				<?php
				if ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-settings' ) {
					
					settings_fields( 'grill-general-settings' );
					do_settings_sections( 'grill-general-settings' );
					submit_button();			
					
				} elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-post-types' ) {
					
					settings_fields( 'grill-post-types' );
					do_settings_sections( 'grill-post-types' );
					submit_button();			
					
				} elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'grill-post-type-settings' ) {
									
					settings_fields( 'grill-settings' );
					do_settings_sections( 'grill-settings' );
					submit_button();			
				}
				?>
			</form>
		</div>
		<?php
	}

	/**
     * Options to select which post types to turn activate.
	 */		
	function grill_post_types() { 
		?>
		<p><?php echo sprintf(__('WordPress can hold and display many different types of content. A single item of such a content is generally called a Post. Grill includes a number of these Post Types that can be activated below. To learn more about what these do, please check our guide on <a href="%s" target="_blank">Post Type Settings</a>.', 'grill'), 'http://charitythemes.org'); ?></p>
		
		<?php
		$activated = get_option( 'grill_active_post_types' );
		$available = $this->get_available_post_types();

		if ( empty( $activated ) )
			$activated = array();
			
		if ( ! $available )
			$available = array();

		// Get the total count of all plugins
		$all_count = count( $available );
		$active_count = count( $activated );
		$inactive_count  = $all_count - $active_count;
		
		$filters = array(
			'all' 	   => sprintf( __('All <span class="count">(%s)</span>', 'grill'), number_format_i18n( $all_count )),	
			'active'   => sprintf( __('Active <span class="count">(%s)</span>', 'grill'), number_format_i18n( $active_count )),
			'inactive' => sprintf( __('Inactive <span class="count">(%s)</span>', 'grill'), number_format_i18n( $inactive_count ))
		);
		
		$filter = !empty( $_GET['filter'] ) ? $_GET['filter'] : 'all';
		?>						

		<ul class="subsubsub">
			<?php $count=0;
			foreach ( $filters as $type => $text ) { $count++;
				$query_args = add_query_arg( array( 'page' => 'grill-post-types', 'filter' => $type ), 
											 get_admin_url( false, 'options-general.php' ) );
				
				$current = ( $filter == $type ) ? 'class="current"' : '';
				?>
				<li><a href="<?php echo $query_args; ?>" <?php echo $current; ?>><?php echo $text; ?></a>
				<?php echo ( $count < sizeof( $filters ) ) ? '|' : ''; ?></li>
			<?php } ?>
		</ul>
			
		<table class="wp-list-table widefat plugins" cellspacing="0">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox" disabled=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e('Bulk selection is disabled', 'grill'); ?></label></td>
					<td scope="col" id="name" class="manage-column column-name"><?php _e('Post Type', 'grill'); ?></td>
					<td scope="col" id="description" class="manage-column column-description"><?php _e('Description', 'grill'); ?></td>
				</tr>
			</thead>
		
			<tfoot>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox" disabled=""><label class="screen-reader-text" for="cb-select-all-1"><?php _e('Bulk selection is disabled', 'grill'); ?></label></td>
					<td scope="col" class="manage-column column-name"><?php _e('Post Type', 'grill'); ?></td>
					<td scope="col" class="manage-column column-description"><?php _e('Description', 'grill'); ?></td>
				</tr>
			</tfoot>
		
			<tbody id="the-list">

			<?php
			if ( $available ) {
				foreach( $available as $file => $data ) {

					if ( ! empty ( $activated ) && in_array( $file, $activated ) ) 
						$status = 'active';
					else
						$status = 'inactive';
					
					$checked = ( $status == 'active' ) ? 'checked="checked"' : '';
					$active  = ( $status == 'active' ) ? 'active' : 'inactive';
					
						if ( $filter == 'all' ||
							 $filter == 'active' && $status == 'active' ||
							 $filter == 'inactive' && $status == 'inactive' ) {

						echo '<tr id="" class="'.$active.'">';
						echo '<th scope="row" class="check-column">
								  <input type="checkbox" id="' . $data['TextDomain'] . '" name="grill_active_post_types[]" value="'. $file .'" '. $checked .' />
								  <label for="' . $data['TextDomain'] . '"></label>
							  </th>';
						echo '<td class="plugin-title" style="width: 190px;">
								  <span class="dashicons-before ' . $data['Dashicon'] . '"></span>
								  <strong>'.$data['Name'].'</strong>
							  </td>';
						echo '<td class="column-description desc">
							  	<div class="plugin-description">
									<p>'.$data['Description'].'</p>
								</div>
							  </td>';
						echo '</tr>';
					
					} 
				}
			}
			
			if ( $filter == 'all' && $all_count == 0 ||
				 $filter == 'inactive' && $inactive_count == 0 ||
				 $filter == 'active' && $active_count == 0 ) {
				echo '<tr><td colspan="3">'.__('No post types found.', 'grill').'</td></tr>';
			}
			?>
				
			</tbody>
		</table>
		<style>
			.settings_page_grill-post-types .wrap table.form-table th:first-child { display: none; }
			.settings_page_grill-post-types .wrap table.form-table th.check-column { display: table-cell; }
			.settings_page_grill-post-types .wrap table.form-table td:last-child { padding-left: 0; }
			.plugin-title .dashicons-before { display: inline-block; float: left; margin-right: 5px; }
		</style>
		<?php
	}
	
	/**
     * Returns the available post types.
	 */	
	function get_available_post_types() {
				
		// Include post types
		$post_types = grill_get_post_types();

		if ( empty( $post_types ) )
			return false;
		
		// Include post types added to the global array.
		foreach ( $post_types as $file ) {
		
			$filepath = WP_PLUGIN_DIR.'/'.$file;
			
			if ( !is_readable( "$filepath" ) )
				continue;
	
			$post_type_data = $this->get_post_type_data( "$filepath" );
	
			if ( empty ( $post_type_data['Name'] ) )
				continue;
	
			$grill_post_types[ $file ] = $post_type_data;

		}
	
		return $grill_post_types;	
	}
	
	/**
	 * Parses the post type contents to retrieve post types metadata.
	 *
	 * Based on the get_plugin_data function in WordPress core.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type_file Path to the post type file
	 * @return array {
	 *     Post type data. Values will be empty if not supplied by the post type.
	 *
	 *     @type string $Name        Name of the post type. Should be unique.
	 *     @type string $Description Post type description.
	 *	   @type string $TextDomain  Post type textdomain.	 
	 *	   @type string $Dashicon	 Post type dashicon
	 *     @type string $Version     Post type version.
	 * }
	 */
	function get_post_type_data( $post_type_file ) {
		
		$default_headers = array(
			'Name' 		  => 'Name',
			'Description' => 'Description',
			'TextDomain'  => 'Text Domain',
			'Dashicon' 	  => 'Dashicon',
			'Version' 	  => 'Version',
		);
	
		$post_type_data = get_file_data( $post_type_file, $default_headers, 'post_type' );
		
		return $post_type_data;
	}
	
	/**
     * Register the general settings page
	 */	
	function general_settings_init() {

		register_setting( 'grill-general-settings', 'grill_options' );
		
		add_settings_section(
			'grill_general_settings_section',
			__('Main Settings', 'grill'),
			array($this,'section_callback'),
			'grill-general-settings'
		);
		
		add_settings_field( 
			'exclude_bootstrap',
			__( 'Exclude Bootstrap', 'grill' ), 
			array( $this, 'exclude_bootstrap_render' ), 
			'grill-general-settings', 
			'grill_general_settings_section' 
		);
		
		add_settings_field( 
			'exclude_fontawesome',
			__( 'Exclude FontAwesome', 'grill' ), 
			array( $this, 'exclude_fontawesome_render' ), 
			'grill-general-settings', 
			'grill_general_settings_section' 
		);
		
		add_settings_field( 
			'exclude_gmaps',
			__( 'Exclude Google Maps', 'grill' ), 
			array( $this, 'exclude_gmaps_render' ), 
			'grill-general-settings', 
			'grill_general_settings_section' 
		);
		
		add_settings_field( 
			'gmaps_api_key',
			__( 'Google Maps API key', 'grill' ), 
			array( $this, 'gmaps_api_key' ), 
			'grill-general-settings', 
			'grill_general_settings_section' 
		);		
	}

	function validate_input( $input ) {

		if ( $input == NULL )
			return false;

	    // Create our array for storing the validated options
	    $output = array();
	     
	    // Loop through each of the incoming options
	    foreach( $input as $key => $value ) {

	        // Check to see if the current option has a value. If so, process it.
	        if( isset( $input[$key] ) ) {

	            // Strip all HTML and PHP tags and properly handle quoted strings.
	            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
				
	        } // end if
	         
	    } // end foreach

	    // Return the array processing any additional functions filtered by this action
	    return $output;
	}	

	function refresh_permalinks()
	{
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] && $_GET['page'] == 'grill-post-types' ) {
			//refresh the permalinks when the post type is turned on or off
			flush_rewrite_rules();
		}
	}			
	
	/**
     * Checkbox to exclude bootstrap
	 */	
	function exclude_bootstrap_render() {
		
		$options = get_option( 'grill_options' );
		$field = '_exclude_bootstrap';

		$checked = ( isset( $options[$field] ) && $options[$field] != NULL ) ? $options[$field] : '';		
		?>
		<fieldset>
			<label>
				<input type='checkbox' name='grill_options[<?php echo $field; ?>]' <?php checked( $checked, 1 ); ?> value='1'>
				<span class=""><?php _e('Grill loads Bootstrap CSS to create responsive layouts for active post types. Check this box to exclude Bootstrap from the frontend.', 'grill'); ?></span>
			</label><br>
		</fieldset>
		<?php
	}
	
	/**
     * Checkbox to exclude fontawesome
	 */	
	function exclude_fontawesome_render() {
		$options = get_option( 'grill_options' );
		$field = '_exclude_fontawesome';

		$checked = ( isset( $options[$field] ) && $options[$field] != NULL ) ? $options[$field] : '';		
		?>
		<fieldset>
			<label>
				<input type='checkbox' name='grill_options[<?php echo $field; ?>]' <?php checked( $checked, 1 ); ?> value='1'>
				<span class=""><?php _e('Grill loads Font Awesome to add beautiful icons. Check this box to exclude Font Awesome from the frontend.', 'grill'); ?></span>
			</label><br>
		</fieldset>
		<?php		
	}

	/**
     * Checkbox to exclude google maps
	 */	
	function exclude_gmaps_render() {
		$options = get_option( 'grill_options' );
		$field = '_exclude_gmaps';

		$checked = ( isset( $options[$field] ) && $options[$field] != NULL ) ? $options[$field] : '';		
		?>
		<fieldset>
			<label>
				<input type='checkbox' name='grill_options[<?php echo $field; ?>]' <?php checked( $checked, 1 ); ?> value='1'>
				<span class=""><?php _e('Grill loads Google Maps scripts to use maps with certain post types. Check this box to exclude Google Maps from the frontend.', 'grill'); ?></span>
			</label><br>
		</fieldset>
		<?php		
	}
	
	/**
     * Checkbox to exclude google maps
	 */
	function gmaps_api_key() { 
	
		$options = get_option( 'grill_options' );
		$field   = '_gmaps_api_key';
		?>
		<input type='text' name='grill_options[<?php echo $field; ?>]' value='<?php echo ( isset( $options[$field] ) && $options[$field]!='' ) ? esc_attr( $options[$field] ) : ''; ?>'>
		
		<p><?php echo sprintf( __('Visit the <a href="%s" target="_blank">Google APIs Console</a> to create an API key.', 'grill'), 'https://code.google.com/apis/console' ); ?></p>
		<?php
	}	
}
$grill_settings = new Grill_Settings();
endif;
?>