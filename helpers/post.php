<?php
/**
 * Post helper functions
 *
 * This file is used to display post elements, from meta to media, to galleries, to in-post pagination, 
 * all post-related functions sit in this file. 
 *
 * @package		WordPress
 * @subpack		Grill
 * @since		Grill 1.0.0
 */

if ( ! function_exists( 'grill_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @param string|array $size (Optional) Image size to use. Accepts any valid image size, 
 * 									       or an array of width and height values in pixels (in that order).
 * @param array $attr Holds the set arguments for the post thumbnail. See @link for more details. Optional.
 * 
 * @return string HTML string that can be used to display the image.
 * @link https://developer.wordpress.org/reference/functions/the_post_thumbnail/
 *
$default_attr = array(
'src'	=> $src,
'class'	=> "attachment-$size_class size-$size_class",
'alt'	=> trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
);
 * @since Grill 1.0.0
 */
function grill_post_thumbnail( $size = 'post-thumbnail', $args = '', $attr = '' ) {
	
	if ( post_password_required() || is_attachment() || ( ! has_post_thumbnail() && ! isset( $args['src'] )) )
		return;

	// Set defaults
	$defaults = array(
		'before' => '<div class="%1$s">',
		'after'  => '</div>',
		'class'  => 'post-thumbnail',
		'src'	 => get_the_post_thumbnail( null, $size, $attr ),
 		'echo'	 => true
	);

	$r = wp_parse_args( $args, $defaults );
	
	$thumbnail = sprintf( $r['before'], $r['class'] ) . $r['src'] . $r['after'];
	
	if ( $r['echo'] ) {
		echo $thumbnail;
	} else {
		return $thumbnail;
	}
}
endif;

if (!function_exists('grill_post_background_image')) :
/**
 * Grill Post Background Image
 *
 * Outputs the background image saved as meta with each post. Will be ouput inside a html tag as CSS.
 * 
 * @param array $args Holds the set arguments for the post background. Optional.
 *
 *     @type string  		$before Markup to prepend the.
 *     @type string  		$after  Markup to append to the title.
 * 	   @type string 		$color  Pass a background color, format #999999
 * 	   @type string 		$repeat  Should the image repeat. Default no-repeat
 * 	   @type string 		$stretch  Should the image be stretched to cover the entire background.
 * 	   @type string 		$position  What should the position of the background. Top, middle or bottom.
 *     @type bool   		$echo   Whether to echo or return the title. Default true for echo.
 * 
 * @return string CSS that can be used on a HTML tag to display the background.
 * @since Grill 1.5.0 
 */
function grill_post_background_image( $args='' ) {
	
	global $post;

	$m = get_post_custom( $post->ID );

	$defaults = array(
		'before'   => 'style="',
		'after'    => '"',
		'color'	   => ( isset( $m['_background_color'][0] ) ) ? $m['_background_color'][0] : '',
		'repeat'   => ( isset( $m['_background_repeat'][0] ) ) ? $m['_background_repeat'][0] : 'no-repeat',
		'stretch'  => ( isset( $m['_background_stretch'][0] ) ) ? $m['_background_stretch'][0] : '',
		'position' => ( isset( $m['_background_position'][0] ) ) ? $m['_background_position'][0] : '',
		'echo'	   => true
	);
	
	$r = wp_parse_args( $args, $defaults );	

	if ( isset( $m['_background_image'][0] ) || isset( $r['color'] ) ) {
		
		if ( isset( $m['_background_image'][0] ) ) 
			$image = wp_get_attachment_image_src( $m['_background_image'][0], 'large' );
		
		$css = $r['before'];
		
		if ( isset( $image[0] ) ) {
			$css .= 'background-image:url(' . $image[0] . ');';
		}
		
		if ( isset( $r['color'] ) && $r['color']!='' ) {
			$css .= 'background-color:' . $r['color'] . ';';
		}
		
		if ( isset( $r['repeat'] ) && $r['repeat']!='' ) {
			$css .= 'background-repeat:' . $r['repeat'] . ';';
		}
		
		if ( isset( $r['stretch'] ) && $r['stretch'] == 'on' ) {
			$css .= 'background-size:cover;';
		}
		
		if ( isset( $r['position'] ) && $r['position']!='' ) {
			$css .= 'background-position:' . $r['position'] . ';';
		}
				
		$css .= $r['after'];
	}
	
	if ( $r['echo'] ) {
		echo $css;
	} else {
		return $css;
	}
}
endif;

if (!function_exists('grill_post_link')) :
/**
 * Grill Post Title
 *
 * Get the post link from the post meta.
 * 
 * @param array $args Holds the user set arguments for post link.
 *     Title attribute arguments. Optional.
 *
 *     @type string  		$before Markup to prepend to the title.
 *     @type string  		$after  Markup to append to the title.
 * 	   @type string			$title  The text for the title tag on the anchor.
 * 	   @type string			$target  Whether the link should open in a new tab or curret tab.
 * 	   @type string			$link  The link URL.
 * 	   @type string			$class  Adds a classes to the anchor tag.
 *     @type bool   		$echo   Whether to echo or return the title. Default true for echo.
 * 
 * @return string The post link in the correct format.
 * @since Grill 1.5.2 
 */
function grill_post_link( $args='' ) {
	
	global $post;
	
	// Get the post meta to fill in the options
	$m = get_post_custom( $post->ID );

	// Set defaults
	$defaults = array(
		'before' => '<a class="%1$s" href="%2$s" title="%3$s" target="%4$s">',
		'after'  => '</a>',
		'title'  => ( isset( $m['_link_text'][0] ) ) ? $m['_link_text'][0] : '',
		'target' => ( isset( $m['_link_target'][0] ) && $m['_link_target'][0] == 'on' ) ? '_target' : '_self',
		'link'   => ( isset( $m['_link_url'][0] ) ) ? $m['_link_url'][0] : '',
		'class'  => ( isset( $m['_link_type'][0] ) ) ? 'post-link '.$m['_link_type'][0] : '',
		'echo'	 => true
	);
	
	$r = wp_parse_args( $args, $defaults );
	
	// If there is no link we can return empty
	if ( ! $r['link'] ) return;

	$link = sprintf( $r['before'], $r['class'], $r['link'], $r['title'], $r['target'] ) . $r['title'] . $r['after'];
	
	if ( $r['echo'] ) {
		echo $link;
	} else {
		return $link;
	}	
}
endif;

// As of WP 3.1.1 addition of classes for css styling to parents of custom post types doesn't exist.
// We want the correct classes added to the correct custom post type parent in the wp-nav-menu for css styling and highlighting, so we're modifying each individually...
// The id of each link is required for each one you want to modify
// Place this in your WordPress functions.php file

/**
 * Grill Post Title
 *
 * Get the post link from the post meta.
 * 
 * @param array $args Holds the user set arguments for post link.
 *     Title attribute arguments. Optional.
 *
 *     @type string  		$before Markup to prepend to the title.
 *     @type string  		$after  Markup to append to the title.
 * 	   @type string			$title  The text for the title tag on the anchor.
 * 	   @type string			$target  Whether the link should open in a new tab or curret tab.
 * 	   @type string			$link  The link URL.
 * 	   @type string			$class  Adds a classes to the anchor tag.
 *     @type bool   		$echo   Whether to echo or return the title. Default true for echo.
 * 
 * @return string The post link in the correct format.
 * @since Grill 1.5.2 
 */
 
function grill_remove_parent_classes( $class ) {
	return ( $class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor' || $class == 'current-menu-item' ) ? FALSE : TRUE;
}

function grill_add_current_nav_class( $classes, $item ) {
	
	$settings = get_option('grill_settings');
	$post_type = get_post_type();
	
	if ( isset( $settings["_parent_$post_type"] ) ) {
		
		$page_id = $settings["_parent_$post_type"];
	
		if ( isset( $page_id ) && $page_id != '' ) {
			
			// we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
			$classes = array_filter( $classes, "grill_remove_parent_classes" );
			
			// add the current page class to a specific menu item (replace ###).
			if ( $page_id == $item->object_id ) {
				$classes[] = 'current_page_parent';
	 		}				
		}
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'grill_add_current_nav_class', 10, 2 );

function grill_get_loop_class( $class ) {
	
	global $sc_atts, $post_type;
	
	$classes = array();

	if ( $sc_atts['display'] )
		$classes[] = $sc_atts['display'];
	if ( $post_type )
		$classes[] = "grill_{$post_type}_wrapper";

	/**
	 * Filters the list of CSS body classes for the current post or page.
	 *
	 * @since 1.0.1
	 *
	 * @param array $classes An array of body classes.
	 * @param array $class   An array of additional classes added to the body.
	 */
	$classes = apply_filters( 'grill_loop_class', $classes, $class );

	return array_unique( $classes );
}

function grill_loop_class( $class = '' ) {
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', grill_get_loop_class( $class ) ) . '"';
}