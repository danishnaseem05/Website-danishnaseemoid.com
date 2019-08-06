<?php

if (!defined('ABSPATH')){
    exit;
}

function wpc_verify($auth_key = ''){
	global $l, $wpdb, $wp_version, $wp_config, $error;
	
	$return = array();

	$site_settings = array();
	$site_settings['ver'] = $wp_version;
	$site_settings['softpath'] = rtrim(get_home_path(), '/');
	$site_settings['siteurl'] = get_option('siteurl');
	$site_settings['adminurl'] = admin_url();
	$site_settings['softdb'] = $wp_config['softdb'];
	$site_settings['softdbuser'] = $wp_config['softdbuser'];
	$site_settings['softdbhost'] = $wp_config['softdbhost'];
	$site_settings['softdbpass'] = $wp_config['softdbpass'];
	$site_settings['dbprefix'] = $wp_config['dbprefix'];
	$site_settings['site_name'] = get_option('blogname');
	
	$site_settings['auth_key'] = (!empty($auth_key) ? $auth_key : '');

	//Fetch all the table names
	$sql = "SHOW TABLES FROM ".$wp_config['softdb'];
	$results = $wpdb->get_results($sql);

	$site_settings['softdbtables'] = array();
	foreach($results as $index => $value) {
		foreach($value as $tableName) {
			$site_settings['softdbtables'][] = $tableName;
		}
	}
	
	$site_settings['wpc_backupdir'] = $wp_config['plugins_root_dir'].'/wp-central/'.(!empty($auth_key) ? $auth_key : wpc_optREQ('auth_key'));
	
	$site_settings['createdir'] = wpc_can_createdir($site_settings['wpc_backupdir']);
	
	$site_settings['wpc_ver'] = wpc_fetch_version();

	$return['data'] = $site_settings;
	
	wpc_connectok();

	echo json_encode($return);

}