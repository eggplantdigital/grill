<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Template Loader
 *
 * @class 		Grill_Template
 * @version		1.0.1
 * @package		Grill/Classes
 * @category	Class
 * @author 		Based on Woocommerce Template Loader
 */
class Grill_Template_Loader {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
		//add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. grill looks for theme.
	 * overrides in /theme/grill/ by default.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
		//$find = array( 'grill.php' );
		$file = '';
		
		if ( is_embed() ) {
			return $template;
		}

		if ( is_single() &&
 			is_grill_post_type()
			){

			$file 	= 'single.php';
			$find[] = Grill()->template_path() . $file;

		} elseif ( is_archive() &&
			   is_grill_taxonomy()		
			   ) {

			$term   = get_queried_object();

			$file = 'archive.php';

			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = Grill()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = Grill()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
//			$find[] = $file; // Do we need this fallback?
			$find[] = Grill()->template_path() . $file;
		}
		
		if ( $file ) {
			$template = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = Grill()->plugin_path() . '/templates/' . $file;
			}
		}

		return $template;
	}
}

Grill_Template_Loader::init();