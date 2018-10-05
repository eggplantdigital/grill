<?php 	
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Locations
 * Description: Tell the world about the different locations you have been working and inspire them to get involved with your organisation.
 * Text Domain: location
 * Dashicon: dashicons-location 
 * Version: 1.0.0
 */
 
$args = array(
	'singular'			  => 'Location',
	'plural'			  => 'Locations',
	'slug'				  => 'location',
	'menu_icon' 		  => 'dashicons-location',
	'supports'			  => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt' ),
);

$location = new Grill_Post_Types( 'location', $args );

$location->register_metabox( 'location_details', array(
	'title'      => __('Location Details', 'grill'),
	'context'    => 'normal',
	'priority'   => 'high',
	'fields'     => array(
		array(
			'label' => __('Address', 'grill'),
			'desc' => __( 'Enter an address for this location.', 'grill' ),
			'id'   => '_location_address',
			'type' => 'textarea',
			'width'=> 100
		),
		array(
			'label' => __('Contact Number', 'grill'),
			'desc' => __( 'Enter a contact number for this location.', 'grill' ),
			'id'   => '_location_content_phone',
			'type' => 'text',
			'width'=> 50
		),
		array(
			'label' => __('Contact Email', 'grill'),
			'desc' => __( 'Enter a contact email address for this location.', 'grill' ),
			'id'   => '_location_contact_email',
			'type' => 'text',
			'width'=> 50
		),
		array(
			'label' => __('Contact Website', 'grill'),
			'desc' => __( 'Enter a website address address for this location.', 'grill' ),
			'id'   => '_location_contact_web',
			'type' => 'text',
			'width'=> 100
		),
	)
) );

$location->register_taxonomy( 'location_group', array(
	'plural'	=> 'Location Groups',
	'singular'	=> 'Group',
	'slug'		=> 'location-group'
));

$location->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-location-grid.png',
	'template'	 => array( 'slug' => 'view', 'name' => 'grid' ) //GRILL_DIR . '/templates/view-grid.php'
));

$location->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-location-list.png',
	'template'	 => GRILL_DIR . '/templates/view-list.php'
));

$location->register_shortcode( 'grill_location', array(
	'title'  => 'Insert Locations',
	'fields' => array(
		array(
			'name'	=> __('Locations', 'grill'),
			'desc'	=> __('Number of location to show (leave blank for all)', 'grill'),
			'std'	=> 5,
			'id'	=> 'num',
			'type'	=> 'text',
			'views' => array('grid', 'list')
		),
		array(
			'name'	  => __('Columns', 'grill'),
			'desc'	  => __('Select the number of columns you would like to display', 'grill'),
			'std'	  => 4,
			'id'	  => 'cols',
			'type'	  => 'select',
			'views'	  => array('grid'),
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'6' => '6'
			)
		),
		array(
			'name'	  => __('Content', 'grill'),
			'desc'	  => __('Use the main content or show a shorter excerpt.', 'grill'),
			'std'	  => 'excerpt',
			'type'	  => 'select',
			'id'	  => 'content_body',
			'views'	  => array('grid', 'list'),
			'options' => array(
				'fulltext' => 'Full Text',
				'excerpt'  => 'Excerpt',
				'none'     => 'None'
			)
		),
		array(
			'name'	  => __('Location', 'grill'),
			'desc'	  => __('Only display one location by selecting one from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'location',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Location Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'location_group',
				array(
					'hide_empty' => false
				)
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Order By', 'grill'),
			'desc'	  => __('Select the order you would like the list to display', 'grill'),
			'std'	  => 'menu_order',
			'id'	  => 'orderby',
			'type'	  => 'select',
			'views'	  => array('grid', 'list'),
			'options' => array(
				'title' 	 => 'Alphabetically',
				'menu_order' => 'By Page Order',
				'date'		 => 'By Date',
				'rand'		 => 'Randomly'
			)
		),
		array(
			'name'	  => __('Order', 'grill'),
			'desc'	  => __('Select if you like the list to be ascending or descending.', 'grill'),
			'std'	  => 'ASC',
			'id'	  => 'order',
			'type'	  => 'select',
			'views'	  => array('grid', 'list'),
			'options' => array(
				'ASC'  => 'Ascending',
				'DESC' => 'Descending',
			)
		)
	)
));

add_filter( 'grill_location_get_posts', 'grill_location_get_posts', 10, 2 );

	function grill_location_get_posts( $args, $atts ) {
		
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'location_group';
			$args['location_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}
endif;