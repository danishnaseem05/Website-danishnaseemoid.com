<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Blogger WPExplorer Theme
 * @since Blogger 1.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- Define Constants
/*-----------------------------------------------------------------------------------*/
define( 'WPEX_JS_DIR_URI', get_template_directory_uri().'/js' );

/*-----------------------------------------------------------------------------------*/
/*	- Theme Setup
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) ) {
	$content_width = 650;
}

// Theme setup - menus, theme support, etc
require_once( get_template_directory() .'/functions/theme-setup.php' );

// Recommend plugins for use with this theme
require_once ( get_template_directory() .'/functions/recommend-plugins.php' );

// Adds a feed metabox to the dashboard for the explorer network
require_once ( get_template_directory() .'/functions/dashboard-feed.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Theme Customizer
/*-----------------------------------------------------------------------------------*/

// Create social links array - needed before the customizer
require_once( get_template_directory() .'/functions/social-links.php' );

// General Options
require_once ( get_template_directory() .'/functions/theme-customizer/general.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Include Custom Functions
/*-----------------------------------------------------------------------------------*/

// Define widget areas
require_once( get_template_directory() .'/functions/widget-areas.php' );

// Admin only functions
if ( is_admin() ) {

	// Default meta options usage
	require_once( get_template_directory() .'/functions/meta/usage.php' );

	// Post editor tweaks
	require_once( get_template_directory() .'/functions/mce.php' );

	// Welcome Screen
	require_once ( get_template_directory() .'/functions/welcome.php' );

// Non admin functions
} else {

	// Outputs the main site logo
	require_once( get_template_directory() .'/functions/logo.php' );

	// Loads front end css and js
	require_once( get_template_directory() .'/functions/scripts.php' );

	// Image resizing script
	require_once( get_template_directory() .'/functions/aqua-resizer.php' );

	// Returns the correct image sizes for cropping
	require_once( get_template_directory() .'/functions/featured-image.php' );

	// Comments output
	require_once( get_template_directory() .'/functions/comments-callback.php' );

	// Pagination output
	require_once( get_template_directory() .'/functions/pagination.php' );

	// Custom excerpts
	require_once( get_template_directory() .'/functions/excerpts.php' );

	// Alter posts per page for various archives
	require_once( get_template_directory() .'/functions/posts-per-page.php' );

	// Outputs the footer copyright
	require_once( get_template_directory() .'/functions/copyright.php' );

	// Outputs post meta (date, cat, comment count)
	require_once( get_template_directory() .'/functions/post-meta.php' );

	// Used for next/previous links on single posts
	require_once( get_template_directory() .'/functions/next-prev.php' );

	// Outputs the post format video
	require_once( get_template_directory() .'/functions/post-video.php' );

	// Outputs post author bio
	require_once( get_template_directory() .'/functions/post-author.php' );

	// Outputs post slider
	require_once( get_template_directory() .'/functions/post-slider.php' );

	// Adds classes to entries
	require_once( get_template_directory() .'/functions/post-classes.php' );

	// Adds a mobile search to the sidr container
	require_once( get_template_directory() .'/functions/mobile-search.php' );

	// Header aside content
	require_once( get_template_directory() .'/functions/header-aside.php' );

}

/*-----------------------------------------------------------------------------------*/
/*	- Automatic Updates
/*-----------------------------------------------------------------------------------*/
require_once( get_template_directory() .'/wp-updates-theme.php') ;
new WPUpdatesThemeUpdater_921( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ) );