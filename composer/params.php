<?php
	
function grill_term_select( $settings, $value ) {

    $css_option = vc_get_dropdown_option( $settings, $value );
    $value = explode( ',', $value );
 	
 	$terms = get_terms( $settings['value'][0], $settings['value'][1] );

 	if ( !empty( $terms ) && !is_wp_error( $terms ) ){

		$output  = '<select name="'. $settings['param_name'] .'" class="wpb_vc_param_value wpb_chosen chosen wpb-input '. $settings['param_name'] .' '. $settings['type'] .' '. $css_option .'" data-option="'. $css_option .'">';
		
		$output .= '<option value="">'.__('--- Show All ---', 'grill').'</option>';
	
		foreach ( $terms as $term ) {
			$selected = ( in_array( $term->slug, $value ) ) ? ' selected="selected"' : '';
			$output .= '<option value="'. $term->slug .'"'.$selected.'>'.$term->name.'</option>';
		}
	
	$output .= '</select>';

	} else {
		
		$output .= '<span>'.__('Oops! Nothing Found', 'grill').'</span>';
		
	}
		
	return $output;
}

vc_add_shortcode_param( 'grill_term_select', 'grill_term_select' );

	
function grill_hidden( $settings, $value ) {
	return '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />';
}
vc_add_shortcode_param( 'grill_hidden', 'grill_hidden' );