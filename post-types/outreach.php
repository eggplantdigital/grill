<?php 	
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Outreach
 * Description: Use the Outreach post type to add the different ways people can engage or get involved with your organisation, for example volunteering, becoming a partner or being a sponsor. 
 * Text Domain: outreach
 * Dashicon: dashicons-megaphone
 * Version: 1.0.0
 */
 
$args = array( 
	'singular'			  => 'Outreach',
	'plural'			  => 'Outreach',
	'slug'				  => 'outreach',
	'menu_icon' 		  => 'dashicons-megaphone',
	'supports'			  => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt' ),
);

$outreach = new Grill_Post_Types( 'outreach', $args );

$outreach->register_metabox( 'fontawesome' );

$outreach->register_taxonomy( 'outreach_group', array(
	'plural'	=> 'Outreach Groups',
	'singular'	=> 'Group',
	'slug'		=> 'outreach-group'
));

$outreach->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-outreach-grid.png',
	'template'	 => GRILL_DIR . '/templates/view-grid.php'
));

$outreach->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-outreach-list.png',
	'template'	 => GRILL_DIR . '/templates/view-list.php'
));

$outreach->register_shortcode( 'grill_outreach', array(
	'title'  => 'Insert Outreach',
	'fields' => array(
		array(
			'name'	=> __('Outreach', 'grill'),
			'desc'	=> __('Number of outreach to show (leave blank for all)', 'grill'),
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
			'name'	  => __('Outreach', 'grill'),
			'desc'	  => __('Only display one outreach by selecting one from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'outreach',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Outreach Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'outreach_group',
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

add_filter( 'grill_outreach_get_posts', 'grill_outreach_get_posts', 10, 2 );

	function grill_outreach_get_posts( $args, $atts ) {
	
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'outreach_group';
			$args['outreach_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}

endif;