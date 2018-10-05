<?php 	
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Testimonials
 * Description: Use testimonials from your customers or partners to make sure everyone knows how great you are.
 * Text Domain: testimonial
 * Dashicon: dashicons-testimonial 
 * Version: 1.0.0
 */
 
$args = array(
	'singular'			  => 'Testimonial',
	'plural'			  => 'Testimonials',
	'slug'				  => 'testimonial',
	'menu_icon' 		  => 'dashicons-testimonial',
	'supports'			  => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt' ),
);

$testimonial = new Grill_Post_Types( 'testimonial', $args );

$testimonial->register_metabox( 'testimonial', array(
	'id'		 => 'testimonial_options',
	'title'      => __('Testimonial Options', 'grill'),
	'context'    => 'normal',
	'priority'   => 'high',
	'fields'     => array(
		array(
			'label'  => __('Position', 'grill'),
			'desc'	 => __('Enter the position of the person providing the testimonial', 'grill'),
			'id'     => '_testimonial_position',
			'type'   => 'text'
		),
		array(
			'label'  => __('Company', 'grill'),
			'desc'	 => __('Enter the company of the person providing the testimonial', 'grill'),
			'id'     => '_testimonial_company',
			'type'   => 'text'
		),		
	)
));

$testimonial->register_taxonomy( 'testimonial_group', array(
	'plural'	=> 'Testimonial Groups',
	'singular'	=> 'Group',
	'slug'		=> 'testimonial-group'
));

$testimonial->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-testimonial-grid.png',
	'template'	 => GRILL_DIR . '/templates/view-grid.php'
));

$testimonial->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-testimonial-list.png',
	'template'	 => GRILL_DIR . '/templates/view-list.php'
));

$testimonial->register_shortcode( 'grill_testimonial', array(
	'title'  => 'Insert Testimonials',
	'fields' => array(
		array(
			'name'	=> __('Testimonials', 'grill'),
			'desc'	=> __('Number of testimonial to show (leave blank for all)', 'grill'),
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
			'name'	  => __('Testimonial', 'grill'),
			'desc'	  => __('Only display one testimonial by selecting one from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'testimonial',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Testimonial Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'testimonial_group',
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

add_filter( 'grill_testimonial_get_posts', 'grill_testimonial_get_posts', 10, 2 );

	function grill_testimonial_get_posts( $args, $atts ) {
		
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'testimonial_group';
			$args['testimonial_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}
endif;