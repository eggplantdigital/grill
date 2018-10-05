<?php 
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Team
 * Description: Use this post type to display the team members who work tirelessly to keep your oganisation running smoothly.
 * Text Domain: team
 * Dashicon: dashicons-groups
 * Version: 1.0.0
 */

$args = array( 
	'singular'  => 'Team',
	'plural'    => 'Teams',
	'slug'      => 'team-member',
	'menu_icon' => 'dashicons-groups',
	'supports'  => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes')
);

$team = new Grill_Post_Types( 'team', $args );

$team->register_metabox( 'team_members_details', array(
	'title'      => __('Team Members Details', 'grill'),
	'context'    => 'normal',
	'priority'   => 'high',
	'fields'     => array(
	array(
		'label' => __('Gravatar Email', 'grill'),
		'desc' => sprintf( __( 'Enter an e-mail address, to use a %sGravatar%s, instead of using the "Featured Image".', 'grill' ), '<a href="' . esc_url( 'http://gravatar.com/' ) . '" target="_blank">', '</a>' ),
		'id'   => '_team_gravatar',
		'type' => 'text',
		'width'=> 50
	),
	array(
		'label' => __('Position', 'grill'),
		'desc' => __('Enter the position for the team member (eg. "Managing Director")', 'grill'),
		'id'   => '_team_position',
		'type' => 'text',
		'width'=> 50
	),
	array(
		'label' => __('Facebook', 'grill'),
		'desc' => __('URL to Facebook page', 'grill'),
		'id'   => '_team_facebook',
		'type' => 'text',
		'width'=> 25
	),
	array(
		'label' => __('Twitter', 'grill'),
		'desc' => __('URL to Twitter account', 'grill'),
		'id'   => '_team_twitter',
		'type' => 'text',
		'width'=> 25
	),
	array(
		'label' => __('Linkedin', 'grill'),
		'desc' => __('URL to Linkedin page', 'grill'),
		'id'   => '_team_linkedin',
		'type' => 'text',
		'width'=> 25
	),
	array(
		'label' => __('Instagram', 'grill'),
		'desc' => __('URL to Instagram account', 'grill'),
		'id'   => '_team_instagram',
		'type' => 'text',
		'width'=> 25
	)	
) ) );

$team->register_taxonomy( 'team_group', array(
	'plural'	=> 'Team Groups',
	'singular'	=> 'Group',
	'slug'		=> 'team-group'
));

$team->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-team-grid.png',
	'template'	 => array( 'slug' => 'view', 'name' => 'grid' )
));

$team->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-team-list.png',
	'template'	 => array( 'slug' => 'view', 'name' => 'list' )
));

$team->register_shortcode( 'grill_team', array(
	'title'  => 'Insert Team',
	'fields' => array(
		array(
			'name'	=> __('Team Members', 'grill'),
			'desc'	=> __('Number of team members to show (leave blank for all)', 'grill'),
			'std'	=> '',
			'id'	=> 'num',
			'type'	=> 'text',
			'views'	=> array('grid','list')
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
				'5' => '5',
				'6' => '6'
			)
		),
		array(
			'name'	  => __('Columns', 'grill'),
			'desc'	  => __('Select the number of columns you would like to display', 'grill'),
			'std'	  => 1,
			'id'	  => 'cols',
			'type'	  => 'select',
			'views'	  => array('list'),
			'options' => array(
				'1' => '1',
				'2' => '2',
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
			'name'	=> __('Rounded Images', 'grill'),
			'id'	=> 'circleimg',
			'type'	=> 'checkbox',
			'views'	=> array('grid','list')
		),
		array(
			'name'	  => __('Team Member', 'grill'),
			'desc'	  => __('Only display one team member by selecting them from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'team',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('Team Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'team_group',
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

add_filter( 'grill_team_get_posts', 'grill_team_get_posts', 10, 2 );

	function grill_team_get_posts( $args, $atts ) {
	
		if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
			$args['taxonomy'] = 'team_group';
			$args['team_group'] = $atts['gid'];
		}
	
		if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
			$args['p'] = $atts['pid'];
		}
	
		return $args;
	}

endif;