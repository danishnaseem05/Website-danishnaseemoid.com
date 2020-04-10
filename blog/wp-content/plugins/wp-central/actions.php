<?php

if (!defined('ABSPATH')){
    exit;
}

function wpc_site_actions(){
	global $l, $error, $wp_config;

	$return = array();

	$request = wpc_optREQ('request');

	if(empty($request)){
		$return['error'] = $l['no_req_post'];
		echo json_encode($return);
		die();
	}
	
	if($request == 'update_website'){
		$source = urldecode(wpc_optREQ('source'));

		include_once(ABSPATH.'wp-admin/includes/class-wp-upgrader.php');
		include_once(ABSPATH.'wp-admin/includes/update.php');
		include_once(ABSPATH.'wp-admin/includes/misc.php');

		global $wp_filesystem;

		$upgrade_error = array();

		$wp_upgrader_skin = new WP_Upgrader_Skin();
		$wp_upgrader_skin->done_header = true;

		$wp_upgrader = new WP_Upgrader($wp_upgrader_skin);

		$res = $wp_upgrader->fs_connect(array(get_home_path(), WP_CONTENT_DIR));
		if (!$res || is_wp_error($res)){
			$upgrade_error[] = $res;
		}

		$download = $wp_upgrader->download_package($source);
		if (is_wp_error($download)){
			$upgrade_error[] = $download;
		}

		$working_dir = $wp_upgrader->unpack_package($download);
		if (is_wp_error($working_dir)){
			$upgrade_error[] = $working_dir;
		}

		$wp_dir = trailingslashit($wp_filesystem->abspath());

		if (!$wp_filesystem->copy($working_dir.'/wordpress/wp-admin/includes/update-core.php', $wp_dir.'wp-admin/includes/update-core.php', true)){
			$wp_filesystem->delete($working_dir, true);
			
			$upgrade_error[] = $l['copy_fail'];
		}

		$wp_filesystem->chmod($wp_dir.'wp-admin/includes/update-core.php', FS_CHMOD_FILE);
		include_once(get_home_path().'wp-admin/includes/update-core.php');

		if(!function_exists('update_core')){
			$upgrade_error[] = $l['call_update_fail'];
		}

		$result = update_core($working_dir, $wp_dir);
		if(is_wp_error($result)){
			$upgrade_error[] = $result->get_error_code();
		}

		if(!empty($upgrade_error)){
			$return['error'] = 'error: '.implode("\n", $upgrade_error);
		}
		
		$return['updatedto'] = wpc_version_wp();
	}
	
	if($request == 'create_post'){
		// Create post object
		$my_post = array(
			'post_title'    => wp_strip_all_tags($_POST['post_title']),
			'post_content'  => $_POST['post_content'],
			'post_status'   => 'publish',
			'post_author'   => 1
		);

		// Insert the post into the database
		$create_post_response = wp_insert_post($my_post);
		
		$post_featured_image = wpc_optPOST('featured_image');
		
		if(!empty($create_post_response) && !empty($post_featured_image)){		    
		    
			$image_url        = $post_featured_image; // Define the image URL here
			$image_name       = basename($image_url);
			$upload_dir       = wp_upload_dir(); // Set upload folder
			$image_data       = file_get_contents($image_url); // Get image data
			$unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
			$filename         = basename($unique_file_name); // Create image file name

			// Check folder permission and define file location
			if(wp_mkdir_p($upload_dir['path'])){
				$file = $upload_dir['path'].'/'.$filename;
			}else{
				$file = $upload_dir['basedir'].'/'.$filename;
			}

			// Create the image file on the server
			file_put_contents($file, $image_data);

			// Check image file type
			$wp_filetype = wp_check_filetype($filename, null);

			// Set attachment data
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name($filename),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$post_id = $create_post_response;

			// Create the attachment
			$attach_id = wp_insert_attachment($attachment, $file, $post_id);

			// Include image.php
			require_once(ABSPATH.'wp-admin/includes/image.php');

			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata($attach_id, $file);

			// Assign metadata to attachment
			wp_update_attachment_metadata($attach_id, $attach_data);

			// And finally assign featured image to post
			set_post_thumbnail($post_id, $attach_id);		    
		}
		
		$return['create_post_response'] = $create_post_response;
	}
	
	if($request == 'delete_post'){
		
		$post_id = wpc_optREQ('del_post');

		// Delete the post from the database
		$return['delete_post_response'] = wp_delete_post($post_id);
	}
	
	if($request == 'publish_post'){
		
		$post_id = wpc_optREQ('post_id');
		
		$post_data = array('ID' => $post_id, 'post_status' => 'publish');

		// Delete the post from the database
		$return['publish_post_response'] = wp_update_post($post_data);
	}
		
	if(wpc_optGET('plugins') || wpc_optGET('plugin')){
		$plugins = urldecode(wpc_optREQ('plugins'));
		$arr_plugins = explode(',', $plugins);

		if($request == 'activate'){//Activate

			$res = wpc_activate_plugin($arr_plugins);
			if(!$res){
				$return['error'] = $l['err_activating_pl'];
			}        
		}elseif($request == 'deactivate'){//Deactivate

			$res = wpc_deactivate_plugin($arr_plugins);
			if(!$res){
				$return['error'] = $l['err_deactivating_pl'];
			}        
		}elseif($request == 'delete'){//Deactivate and then Delete

			$act_res = wpc_deactivate_plugin($arr_plugins);        
			if(!$act_res){
				$return['error'] = $l['err_deactivating_del_pl'];
			}
			
			$result = delete_plugins($arr_plugins);
			if(is_wp_error($result)) {
				$return['error'] = $result->get_error_message();
			}elseif($result === false) {
				$return['error'] = $l['err_deleting_pl'];
			}
		}elseif($request == 'install'){//Install Plugins
			
			$sources = urldecode(wpc_optREQ('sources'));
			$arr_sources = explode(',', $sources);
			
			$all_installed_plugins = array();
			
			foreach($arr_plugins as $plk => $plval){
				
				//Skip if the plugin is already installed
				if(wpc_is_plugin_installed($plval)){
					continue;
				}
				
				$filename = basename(parse_url($arr_sources[$plk], PHP_URL_PATH));

				$download_dest = $wp_config['uploads_dir'].'/'.$filename;
				$unzip_dest = $wp_config['plugins_root_dir'];

				wpc_get_web_file($arr_sources[$plk], $download_dest);

				if(wpc_sfile_exists($download_dest)){
					$res = wpc_unzip($download_dest, $unzip_dest);
				}

				@wpc_sunlink($download_dest);

				//Activate the installed plugin(s)
				$pl_slug = $plval;
				if(preg_match('/(.*?)\/(.*?)\.php/is', $plval)){
				    wpc_soft_preg_replace('/(.*?)\/(.*?)\.php/is', $plval, $pl_slug, 1, 1);
				}
				
				if(empty($pl_slug)){//This is the case for the default Hello Dolly plugin that comes installed with the initial WP package
					continue;
				}
				
				$all_installed_plugins[] = wpc_get_plugin_path(ABSPATH.'wp-content/plugins/'.$pl_slug, $pl_slug);
			}
			
			//Activate the installed plugins
			wpc_activate_plugin($all_installed_plugins);

			if(!empty($error)){
				$return['error'] = $error;
			}
		}elseif($request == 'update'){
			
			$plugin_name = urldecode(wpc_optREQ('plugin'));
			$download_link = urldecode(wpc_optREQ('source'));
			
			//For backward compatibility
			if(!is_array($plugin_name)) $plugin_name = array($plugin_name);
			if(!is_array($download_link)) $download_link = array($download_link);
			
			$sources = urldecode(wpc_optREQ('sources'));
			$arr_sources = explode(',', $sources);
			
			$arr_plugins = array_merge($plugin_name, $arr_plugins);
			$arr_sources = array_merge($download_link, $arr_sources);
			
			$site_url = urldecode(wpc_optREQ('siteurl'));
			
			foreach($arr_plugins as $plk => $plval){			
				$filename = basename(parse_url($arr_sources[$plk], PHP_URL_PATH));
				
				$download_dest = $wp_config['uploads_dir'].'/'.$filename;
				$unzip_dest = $wp_config['plugins_root_dir'];
				
				wpc_get_web_file($arr_sources[$plk], $download_dest);
				
				if(wpc_sfile_exists($download_dest)){
					$res = wpc_unzip($download_dest, $unzip_dest);
				}
				
				@wpc_sunlink($download_dest);
			}
			
			// Lets visit the installation once to make the changes in the database
			$resp = wp_remote_get($site_url);
			
			if(!empty($error)){
				$return['error'] = $error;
			}
		}
	}elseif(wpc_optGET('themes') || wpc_optGET('theme')){
		
		$themes = urldecode(wpc_optREQ('themes'));
		$arr_themes = explode(',', $themes);

		$active_theme = array_keys(wpc_get_active_theme());		
		
		if($request == 'activate' && count($arr_themes) == 1){//Activate
			
			//Do not activate/delete the theme if it is active
			if($active_theme[0] != $arr_themes[0]){
				$res = wpc_activate_theme($arr_themes);
				if(!empty($error)){
					$return['error'] = $error;
				}
				if(!$res){
					$return['error'] = $l['err_activating_theme'];
				}
			}
			
		}elseif($request == 'delete'){//Delete
			
			//Do not delete the theme if it is active
			foreach($arr_themes as $tk => $tv){
				if($active_theme[0] == $tv){
					unset($arr_themes[$tk]);
				}
			}
			
			$res = wpc_delete_theme($arr_themes);
			if(!empty($error)){
				$return['error'] = $error;
			}
			if(!$res){
				$return['error'] = $l['err_deleting_theme'];
			}
			
		}elseif($request == 'install'){//Install Themes
			
			$sources = urldecode(wpc_optREQ('sources'));
			$arr_sources = explode(',', $sources);
			
			foreach($arr_themes as $thk => $thval){
				
				//Skip if the theme is already installed
				if(wpc_is_theme_installed($thval)){
					continue;
				}
			
				$filename = basename(parse_url($arr_sources[$thk], PHP_URL_PATH));
				
				$download_dest = $wp_config['uploads_dir'].'/'.$filename;
				$unzip_dest = $wp_config['themes_root_dir'].'/';
				
				wpc_get_web_file($arr_sources[$thk], $download_dest);
				
				if(wpc_sfile_exists($download_dest)){
					$res = wpc_unzip($download_dest, $unzip_dest);
				}
				
				@wpc_sunlink($download_dest);
			}
			
			if(!empty($error)){
				$return['error'] = $error;
			}
		}elseif($request == 'update'){//Update Theme
		
			$theme_name = urldecode(wpc_optREQ('theme'));
			$download_link = urldecode(wpc_optREQ('source'));
			
			//For backward compatibility
			if(!is_array($theme_name)) $theme_name = array($theme_name);
			if(!is_array($download_link)) $download_link = array($download_link);
			
			$sources = urldecode(wpc_optREQ('sources'));
			$arr_sources = explode(',', $sources);
			
			$arr_themes = array_merge($theme_name, $arr_themes);
			$arr_sources = array_merge($download_link, $arr_sources);
			
			$site_url = urldecode(wpc_optREQ('siteurl'));
			
			foreach($arr_themes as $thk => $thval){			
				$filename = basename(parse_url($arr_sources[$thk], PHP_URL_PATH));
				
				$download_dest = $wp_config['uploads_dir'].'/'.$filename;
				$unzip_dest = $wp_config['themes_root_dir'];
				
				wpc_get_web_file($arr_sources[$thk], $download_dest);
				
				if(wpc_sfile_exists($download_dest)){
					$res = wpc_unzip($download_dest, $unzip_dest);
				}
				
				@wpc_sunlink($download_dest);
			}
			
			// Lets visit the installation once to make the changes in the database
			$resp = wp_remote_get($site_url);
			
			if(!empty($error)){
				$return['error'] = $error;
			}
		}
	}

	if(empty($return['error'])){
		$return['result'] = 'done';
	}

	//Using serialize here as all_plugins contains class object which are not json_decoded in Softaculous.
	echo json_encode($return);

}