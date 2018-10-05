<?php 	
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Projects
 * Description: Tell the world about the different projects you have been working and inspire them to get involved with your organisation.
 * Text Domain: project
 * Dashicon: dashicons-heart 
 * Version: 1.0.0
 */
 
$args = array(
	'singular'			  => 'Project',
	'plural'			  => 'Projects',
	'slug'				  => 'project',
	'menu_icon' 		  => 'dashicons-heart',
	'supports'			  => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt' ),
);

$project = new Grill_Post_Types( 'project', $args );

$project->register_metabox( 'fontawesome' );
$project->register_metabox( 'link' );

$project->register_taxonomy( 'project_group', array(
	'plural'	=> 'Project Groups',
	'singular'	=> 'Group',
	'slug'		=> 'project-group'
));

$project->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-project-grid.png',
	'template'	 => array( 'slug' => 'view', 'name' => 'grid' ) //GRILL_DIR . '/templates/view-grid.php'
));

$project->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-project-list.png',
	'template'	 => GRILL_DIR . '/templates/view-list.php'
));

$project->register_shortcode( 'grill_project', array(
	'title'  => 'Insert Projects',
	'fields' => array(
		array(
			'name'	=> __('Projects', 'grill'),
			'desc'	=> __('Number of project to show (leave blank for all)', 'grill'),
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
			'name'	  => __('Project', 'grill'),
			'desc'	  => __('Only display one project by selecting one from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'project',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Project Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'project_group',
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

add_filter( 'grill_project_get_posts', 'grill_project_get_posts', 10, 2 );

	function grill_project_get_posts( $args, $atts ) {
		
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'project_group';
			$args['project_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}
endif;