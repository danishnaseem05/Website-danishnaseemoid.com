<?php
/**
 * This file is used for all excerpt related functions
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
*/


/**
 * Custom excerpts based on wp_trim_words
 * Created for child-theming purposes
 * 
 * Learn more at http://codex.wordpress.org/Function_Reference/wp_trim_words
 *
 * @since Blogger 1.0
 */
if ( !function_exists( 'wpex_excerpt' ) ) {
	function wpex_excerpt( $length=30, $readmore=false ) {
		global $post;
		$id = $post->ID;
		if ( has_excerpt( $id ) ) {
			$output = $post->post_excerpt;
		} else {
			$output = get_the_excerpt();
			if ( $readmore == true ) {
				$readmore_link = '<span class="wpex-readmore"><a href="'. get_permalink( $id ) .'" title="'. __('continue reading', 'wpex' ) .'" rel="bookmark">'. __( 'Read more', 'wpex' ) .'</a></span>';
				$output .= apply_filters( 'wpex_readmore_link', $readmore_link );
			}
		}
		echo $output;
	}
}


/**
 * Change default excerpt read more style
 *
 * @since Blogger 1.0
 */
if ( !function_exists( 'wpex_excerpt_more' ) ) {
	function wpex_excerpt_more($more) {
		global $post;
		return '...';
	}
	add_filter( 'excerpt_more', 'wpex_excerpt_more' );
}

/**
 * Change default excerpt read more style
 *
 * Learn more: http://codex.wordpress.org/Function_Reference/the_excerpt#Control_Excerpt_Length_using_Filters
 * @since Blogger 1.1
 */
if ( !function_exists( 'wpex_custom_excerpt_length' ) ) {
	function wpex_custom_excerpt_length( $length ) {
		return get_theme_mod( 'wpex_excerpt_length', '50' );
	}
}
add_filter( 'excerpt_length', 'wpex_custom_excerpt_length', 999 );