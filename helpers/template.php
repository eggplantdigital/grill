<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'grill_get_template_part' ) ) :
/**
 * Get Plugin Template Part
 *
 * It's possible to overwrite the template from theme.
 * Put your custom template in 'grill' folder
 *
 * @param string $slug
 * @param string $name
 *
 * @use   load_template()
 * @since 1.0
 * @return void
 */	
function grill_get_template_part( $slug, $name = '' ) {
	
	$template = '';

	// First time we need to check the grill folder inside the theme and child theme.
	$templates = array();
	$name = (string) $name;
	if ( '' !== $name )
		$templates[] = "grill/{$slug}-{$name}.php";
	
	$templates[] = "grill/{$slug}.php";
	
	// If not load from the plugin folder.
	$template = locate_template( $templates, false, false );
	
	// If not found in the child or parent theme, load from the plugin	
	if ( $template === '' ) {
		$templates = array();
		$template  = '';
		
		if ( '' !== $name )
			$templates[] = "{$slug}-{$name}.php";
	
		$templates[] = "{$slug}.php";
		
		foreach ( (array) $templates as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(GRILL_TEMPLATE_PATH . $template_name)) {
				$template = GRILL_TEMPLATE_PATH . $template_name;
				break;
			}
		}			
	}
	
	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'grill_get_template_part', $template, $slug, $name );	
	
	if ( $template != NULL ) {
		load_template( $template, false );
	}
}
endif;

function grill_get_post_template() {
	
	$post_type = get_post_type();
	
	if ( locate_template( array( "grill/content-{$post_type}.php" ) ) != '' ||
		 file_exists( GRILL_TEMPLATE_PATH . "content-{$post_type}.php" ) ) {		
		
		grill_get_template_part( "content", "{$post_type}" );
		
	} else {
		grill_get_template_part( 'content', 'article' );
	}
}

if ( ! function_exists( 'is_grill_post_type' ) ) :
/**
 * Is Grill Post Type
 *
 * Checks if the current post type is an activated Grill created post type.
 *
 * @use 	get_post_type()
 * @since 	1.0.1
 * @return 	boolean
 */	
function is_grill_post_type() {	
	if ( in_array( get_post_type(), Grill()->grill_post_types ) )
		return true;
	else
		return false;
}
endif;

if ( ! function_exists( 'is_grill_taxonomy' ) ) :
/**
 * Is Grill Taxonomy
 *
 * Checks if the current taxonomy is an activated Grill created taxonomy.
 *
 * @use 	is_grill_taxonomy()
 * @since 	1.0.1
 * @return 	boolean
 */	
function is_grill_taxonomy() {	
	if ( in_array( get_queried_object()->taxonomy, Grill()->grill_taxonomies ) )
		return true;
	else
		return false;
}
endif;
?>