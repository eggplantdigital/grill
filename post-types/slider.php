<?php 	
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Slider
 * Description: Tell the world about the different slides you have been working and inspire them to get involved with your organisation.
 * Text Domain: slide
 * Dashicon: dashicons-format-gallery 
 * Version: 1.0.0
 */
 
$args = array(
	'singular'			  => 'Slider',
	'plural'			  => 'Slides',
	'slug'				  => 'slide',
	'menu_icon' 		  => 'dashicons-format-gallery',
	'supports'			  => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt' ),
);

$slide = new Grill_Post_Types( 'slider', $args );

$slide->register_metabox( 'link' );

/*
$slide->register_taxonomy( 'slide_group', array(
	'plural'	=> 'Project Groups',
	'singular'	=> 'Group',
	'slug'		=> 'slide-group'
));
*/

$slide->register_template( 'fullwidth', array(
	'label'      => 'Full Width',
	'screenshot' => GRILL_URL . '/assets/img/view-slide-grid.png',
	'template'	 => array(	'slug' => 'slider/view', 'name' => 'fullwidth' )
));

$slide->register_shortcode( 'grill_slider', array(
	'title'  => 'Insert Slider',
	'fields' => array(
		array(
			'name'	=> __('Slides', 'grill'),
			'desc'	=> __('Number of slides to show (leave blank for all)', 'grill'),
			'std'	=> 5,
			'id'	=> 'num',
			'type'	=> 'text'
		),
		array(
			'name'	=> __('Height', 'grill'),
			'desc'	=> __('Set the desired height of the images you are uploading. Defaults to 450px.', 'grill'),
			'std'	=> '450px',
			'id'	=> 'height',
			'type'	=> 'text'
		),		
		array(
			'name'	  => __('Project Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'slide_group',
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

/*
add_filter( 'grill_slide_get_posts', 'grill_slide_get_posts', 10, 2 );

	function grill_slide_get_posts( $args, $atts ) {
		
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'slide_group';
			$args['slide_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}
*/
endif;