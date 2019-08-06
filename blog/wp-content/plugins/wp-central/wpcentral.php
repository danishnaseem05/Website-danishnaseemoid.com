<?php
/*
Plugin Name: wpCentral
Plugin URI: https://wpcentral.co
Description: wpCentral provides a centralized area where you can manage all your WordPress websites singularly, unitedly as well as efficiently.
Version: 1.4.5
Author: Softaculous Ltd.
Author URI: https://wpcentral.co
License: GPL2
License URI: 
Text Domain: wpCentral
*/
/*
 * This file belongs to the wpcentral plugin.
 *
 * (c) wpcentral LLC <sales@wpcentral.co>
 *
 * You can view the LICENSE file that was distributed with this source code
 * for copywright and license information.
 */

if (!defined('ABSPATH')){
    exit;
}

global $wpc_slug;

$wpc_slug = 'wp-central/wpcentral.php';

include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once('wpc_functions.php');

add_filter('all_plugins', 'wpc_plugin_info_filter', 10, 1);
add_filter('all_plugins', 'wpc_plugins_list_filter', 10, 1);

//WPC Activation Hook
register_activation_hook(__FILE__, 'wpc_activation_hook');
//WPC De-activation Hook
register_deactivation_hook(__FILE__, 'wpc_deactivation_hook');

//Add link to show the Connection key once the plugin is activated
if(is_plugin_active($wpc_slug)){
	add_action('admin_init', 'wpc_enqueue_script');
	add_action('admin_init', 'wpc_enqueue_styles');
	add_filter('plugin_row_meta', 'wpc_add_connection_link', 10, 2);
	add_action('admin_head', 'wpc_modal_open_script');
	add_action('admin_footer', 'wpc_modal_dialog');
	
	add_action( 'plugins_loaded', 'wpc_load_plugin' );
    
	include_once(ABSPATH.'wp-includes/pluggable.php');
	
	if(wpc_is_display_notice()){
		add_action('admin_notices', 'wpc_admin_notice');
	}
	add_action('wp_ajax_wpc_dismissnotice', 'wpc_dismiss_notice');
	add_action('wp_ajax_nopriv_my_wpc_actions', 'my_wpc_actions_init');
    
	if(is_user_logged_in()){
		add_action('wp_ajax_my_wpc_signon', 'my_wpc_signon');
		add_action('wp_ajax_my_wpc_fetch_authkey', 'wpc_fetch_authkey');
	}else{
		add_action('wp_ajax_nopriv_my_wpc_signon', 'my_wpc_signon');
	}
	
	// Show wpCentral ratings notice
	wpc_maybe_promo([
		'after' => 1,// In days
		'interval' => 120,// In days
		'rating' => 'https://wordpress.org/plugins/wp-central/#reviews',
		'twitter' => 'https://twitter.com/the_wpcentral?status='.rawurlencode('I love #wpCentral by @the_wpcentral team for my #WordPress site - '.home_url()),
		'facebook' => 'https://www.facebook.com/wordpresscentral',
		'website' => 'https://wpcentral.co',
		'image' => 'https://wpcentral.co/images/icon_dark.png',
		'support' => 'https://softaculous.deskuss.com'
	]);
}