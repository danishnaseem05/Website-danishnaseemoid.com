<?php
/**
 * Array of social links
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */


if ( ! function_exists( 'wpex_social_links()' ) ) {
	function wpex_social_links() {
		return apply_filters( 'wpex_social_array', array( 'twitter', 'facebook', 'googleplus', 'linkedin', 'pinterest', 'vk', 'instagram', 'rss' ) );
	} // End function
} // End if