<?php
	
if ( function_exists('vc_map') ) :

  // Default Shortcode Options
// ----------------------------------------------------------------------------------
	
$post_params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => __('Select a layout', 'grill'),
		'param_name'  => 'display',
		'value'	  => array(
			'Grid'	  => 'grid',
			'List'	  => 'list'
		)
	),
	array(
		'type'	  	  => 'dropdown',
		'heading'	  => __('Columns', 'grill'),
		'description' => __('Select the number of columns you would like to display', 'grill'),
		'param_name'  => 'cols',
		'dependency'  => array( 'element' => 'display', 'value' => array('grid'), 'not_empty' => false ),
		'value' => array(
			'3' 	  => '3',
			'1'		  => '1',
			'2'		  => '2',
			'4' 	  => '4',
			'6' 	  => '6',
		)
	),
	array(
		'type'        => 'dropdown',
		'heading'	  => __('Order By', 'grill'),
		'description' => __('Select the order you would like the list to display', 'grill'),
		'param_name'  => 'orderby',
		'value' => array(
			'By Date'		 => 'date',
			'By Page Order'  => 'menu_order',
			'Alphabetically' => 'title',
			'Randomly'       =>'rand' 
		)
	),
	array(
		'type' 	      => 'dropdown',
		'heading'	  => __('Order', 'grill'),
		'description' => __('Select if you like the list to be ascending or descending.', 'grill'),
		'param_name'  => 'order',
		'value' => array(
			'Descending' => 'DESC',
			'Ascending'  => 'ASC'
		)
	),
	array(
		'type'	  	  => 'dropdown',
		'heading'	  => __('Content', 'grill'),
		'description' => __('Use the main content or show a shorter excerpt.', 'grill'),
		'param_name'  => 'content_body',
		'value' => array(
			'Excerpt'   => 'excerpt',				
			'Full Text' => 'fulltext',
			'None'      => 'none'
		)
	),
);

  // Project Shortcode Options
// ----------------------------------------------------------------------------------

vc_map( array(
	'name'          => 'Projects',
	'base'          => 'grill_project',
	'icon'          => 'dashicons-testimonial',
	'description'   => 'Add a your project posts to your page',
	'params'        => $post_params
) );

  // Team Shortcode Options
// ----------------------------------------------------------------------------------

vc_map( array(
	'name'          => 'Team',
	'base'          => 'grill_team',
	'icon'          => 'dashicons-team',
	'description'   => 'Insert your team',
	'params'        => $post_params
) );

  // FAQ Shortcode Options
// ----------------------------------------------------------------------------------

$post_params[] = array(
	'type'	  	  => 'grill_term_select',
	'heading'	  => __('Group', 'grill'),
	'description' => __('Only display one group by selecting it from the list', 'grill'),
	'param_name'  => 'gid',
	'value' => array(
		'faq_group',
		array(
			'hide_empty' => false
		)			
	)
);

vc_map( array(
	'name'          => 'FAQ',
	'base'          => 'grill_faq',
	'icon'          => 'dashicons-faq',
	'description'   => 'Insert your FAQs',
	'params'        => $post_params
) );

  // Slider Shortcode Options
// ----------------------------------------------------------------------------------

$slider_params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => __('Select a layout', 'grill'),
		'param_name'  => 'display',
		'value'	  => array(
			'Full Width'    => 'fullwidth',
		)
	),
	array(
		'type'        => 'textfield',
		'heading'	  => __('Slider Height', 'grill'),
		'description' => __('Set the desired height of the images you are uploading. Defaults to 450px.', 'grill'),
		'value'		  => '450px',
		'param_name'  => 'height',
	),
	array(
		'type'        => 'dropdown',
		'heading'	  => __('Order By', 'grill'),
		'description' => __('Select the order you would like the list to display.', 'grill'),
		'param_name'  => 'orderby',
		'value' => array(
			'By Date'		 => 'date',
			'By Page Order'  => 'menu_order',
			'Alphabetically' => 'title',
			'Randomly'       => 'rand' 
		)
	),
	array(
		'type' 	      => 'dropdown',
		'heading'	  => __('Order', 'grill'),
		'description' => __('Select if you like the list to be ascending or descending.', 'grill'),
		'param_name'  => 'order',
		'value' => array(
			'Descending' => 'DESC',
			'Ascending'  => 'ASC'
		)
	),
);

vc_map( array(
	'name'          => 'Slider',
	'base'          => 'grill_slider',
	'icon'          => 'dashicons-format-gallery',
	'description'   => 'Insert your slider',
	'params'        => $slider_params
) );

endif;