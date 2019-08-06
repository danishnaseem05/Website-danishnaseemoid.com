<?php
/**
 * Adds classes to entries
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */


add_filter('post_class', 'wpex_post_entry_classes');

if ( ! function_exists( 'wpex_post_entry_classes' ) ) {

	function wpex_post_entry_classes( $classes ) {
		
		// Post Data
		global $post;
		$post_id = $post->ID;
		$post_type = get_post_type($post_id);

		// Custom class for non standard post types
		if ( $post_type !== 'post' && !is_singular() ) {
			$classes[] = $post_type .'-entry';
		}

		// All other posts
		if ( !is_singular() ) {
			$classes[] = 'loop-entry clr boxed';
		}

		// Return classes
		return $classes;
		
	} // End function
	
} // End if