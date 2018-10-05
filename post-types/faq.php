<?php 
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: FAQ
 * Description: FAQ or Frequently Asked Questions is useful to provide the answers common questions your website visitors may have.
 * Text Domain: faq
 * Dashicon: dashicons-format-status 
 * Version: 1.0.0
 */
 
$args = array( 
	'singular'  => 'FAQ',
	'plural'    => 'FAQs',
	'slug'      => 'faq',
	'menu_icon' => 'dashicons-format-status',
	'supports'  => array('title', 'editor', 'page-attributes')
);

$faq = new Grill_Post_Types( 'faq', $args );

$faq->register_taxonomy( 'faq_group', array(
	'plural'	=> 'FAQ Groups',
	'singular'	=> 'Group',
	'slug'		=> 'faq-group',
));

$faq->register_template( 'list', array(
	'label'      => 'List',
	'screenshot' => GRILL_URL . '/assets/img/view-faq-list.png',
	'template'	 => GRILL_DIR . '/templates/view-list.php'
));

$faq->register_shortcode( 'grill_faq', array(
	'title'  => 'Insert FAQ',
	'fields' => array(
		array(
			'name'	=> __('FAQ', 'grill'),
			'desc'	=> __('Number of faq\'s to show (leave blank for all)', 'grill'),
			'std'	=> '',
			'id'	=> 'num',
			'type'	=> 'text',
			'views'	=> array('grid','list')
		),
		array(
			'name'	  => __('FAQ', 'grill'),
			'desc'	  => __('Only display one faq by selecting them from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'faq',
				'posts_per_page' => -1
			),
			'views'	  => array('grid','list')
		),
		array(
			'name'	  => __('FAQ Group', 'grill'),
			'desc'	  => __('Only display one group by selecting it from the list', 'grill'),
			'id'	  => 'gid',
			'type'	  => 'term_select',
			'options' => array(
				'faq_group',
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
				'date'		 => 'By Date',
				'title' 	 => 'Alphabetically',
				'menu_order' => 'By Page Order',
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
				'DESC' => 'Descending',
				'ASC'  => 'Ascending'
			)
		)		
	)
));

function grill_faq_get_posts( $args, $atts ) {

	if ( isset( $atts['gid'] ) && $atts['gid'] != '' ) {
		$args['taxonomy'] = 'faq_group';
		$args['faq_group'] = $atts['gid'];
	}

	if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
		$args['p'] = $atts['pid'];
	}

	return $args;
}

add_filter( 'grill_faq_get_posts', 'grill_faq_get_posts', 10, 2 );

endif;