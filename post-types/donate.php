<?php 
if ( class_exists( 'Grill_Post_Types' ) ) :
/**
 * Name: Donate
 * Description: The Donate post type uses Paypal to get donations from your visitors. 
 * Text Domain: donate
 * Dashicon: dashicons-money 
 * Version: 1.0.0
 */
 
$args = array( 
	'singular'  => 'Donate',
	'plural'    => 'Donate',
	'slug'      => 'donate',
	'menu_icon' => 'dashicons-money',
	'supports'  => array('title', 'editor', 'page-attributes','thumbnail'),
	'public'	=> false
);

$donate = new Grill_Post_Types( 'donate', $args );

$donate->register_metabox( 'donate', array(
	'id'		 => 'text',
	'title'      => __('Donate Options', 'grill'),
	'context'    => 'normal',
	'priority'   => 'high',
	'fields'     => array(
		array(
			'label'  => __('Amount', 'grill'),
			'desc'	 => __('Enter a predefined amount that will be donated without currency symbol. Leave blank to allow the user to input the amount.', 'grill'),
			'id'     => '_donate_amount',
			'type'   => 'text'
		),
		array(
			'label'  => __('Item Name', 'grill'),
			'desc'	 => __('The item name appears in the shopping cart on the PAYPAL payment processing page.', 'grill'),
			'id'     => '_donate_item_name',
			'type'   => 'text'
		),
		array(
			'label'  => __('Symbol', 'grill'),
			'desc'	 => __('Enter a the currency symbol, e.g. $', 'grill'),
			'id'     => '_donate_symbol',
			'type'   => 'text'
		),
		array(
			'label' => __('Currency', 'grill'),
			'desc'	=> __('Make sure the currency entered here matches your PAYPAL account.', 'grill'),
			'id'    => '_donate_currency',
			'type'  => 'text',
		),
		array(
			'label' => __('PAYPAL ACC.', 'grill'),
			'desc'	=> __('Enter the email address associated with your PAYPAL account.', 'grill'),
			'id'    => '_donate_paypal',
			'type'  => 'text',
		),
	)
));

$donate->register_template( 'grid', array(
	'label'      => 'Grid',
	'screenshot' => GRILL_URL . '/assets/img/view-donate-grid.png',
	'template'	 => GRILL_DIR . '/templates/view-grid.php'
));

$donate->register_shortcode( 'grill_donate', array(
	'title'  => 'Insert Donate',
	'fields' => array(
		array(
			'name'	=> __('Donate', 'grill'),
			'desc'	=> __('Number of donate box\'s to show (leave blank for all)', 'grill'),
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
			'name'	  => __('Donate', 'grill'),
			'desc'	  => __('Only display one donate box by selecting it from the list', 'grill'),
			'id'	  => 'pid',
			'type'	  => 'post_select',
			'options' => array(
				'post_type' 	 => 'donate',
				'posts_per_page' => -1
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

function grill_donate_get_posts( $args, $atts ) {

	if ( isset( $atts['pid'] ) && $atts['pid'] != '' ) {
		$args['p'] = $atts['pid'];
	}

	return $args;
}

add_filter( 'grill_donate_get_posts', 'grill_donate_get_posts', 10, 2 );

endif;