<?php
/**
 * Create a Google Map field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 * @link https://gist.github.com/bjornbjorn/7428321
 */
class Grill_Field_Gmap extends Grill_Field {

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {
		
		if ( empty( GOOGLE_API_KEY ) ) {
			$key = admin_url( 'options-general.php?page=grill-settings' );
			printf( __( 'API Key Missing. Go to <a href="%s">Grill Settings</a> to add a Google Maps API key.', 'grill'), $key );
		} else {
			echo '<input type="text" id="search_location" name="search_location" class="grill-field-gmap_search" placeholder="Search Location" /><a href="#" id="grill_gmaps_search" class="button button-primary grill-field-gmap_search-button">'. __('Search', 'grill').'</a><div style="width:100%;height:250px" id="grill-field-gmail_map" class="grill-field-gmail_map"></div>';
		}
	}

	/**
	 * Load JS scripts in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_scripts
	 * @since 1.0.2
	 */
	public function enqueue_scripts() {
		
		if ( empty( GOOGLE_API_KEY ) )
			return false;
		
		$lat = ( ! empty( $this->args['options']['latitude'] ) ) ? $this->args['options']['latitude'] : '';
		$long = ( ! empty( $this->args['options']['longitude'] ) ) ? $this->args['options']['longitude'] : '';

		wp_enqueue_script( 'grill-maps', 'https://maps.google.com/maps/api/js?sensor=false&key='.GOOGLE_API_KEY, array( 'jquery' ) );	
        wp_enqueue_script( 'grill-meta-gmap-js', GRILL_URL . '/assets/js/meta.gmap.js', array( 'jquery' ) );
        wp_localize_script( 'grill-meta-gmap-js', 'grill_gmap', array(
			'latitude'  => $lat,
			'longitude' => $long,
			'map_error' => __('Could not find this address, please write one nearby and drag the pin on the map as close as possible to the correct position.', 'grill')
		));
	}	
}