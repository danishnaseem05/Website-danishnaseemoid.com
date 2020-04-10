<?php

//////////////////////////////////////////////////////////////
//===========================================================
// template_import.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

include_once(PAGELAYER_DIR.'/main/settings.php');

function pagelayer_import(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	$pagelayer_theme = wp_get_theme();
	$pagelayer_theme_url = get_stylesheet_directory_uri();
	$pagelayer_theme_path = get_stylesheet_directory();
	
	// Get the pages
	$pagelayer_templates = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer.conf'), true);
	$pagelayer_pages = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf'), true);
	
	if(isset($_POST['theme'])){
		$GLOBALS['pl_saved'] = pagelayer_import_theme($pagelayer_theme);
	}
	
	// Have we already imported ?
	$imported = get_option('pagelayer_theme_'.get_template().'_imported');
	if(!empty($imported)){
		$GLOBALS['pl_warn'] = __('You have already imported the content of this theme. You can re-import the same, but it will over-write existing pages / pagelayer templates which have the same name.', 'pagelayer');
	}
	
	// Call the theme
	pagelayer_import_T();
	
}

function pagelayer_import_T(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	pagelayer_page_header('Pagelayer - Import Template');
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The theme content was successfully imported', 'pagelayer'). '</p></div>';

	// Warn ?
	}elseif(!empty($GLOBALS['pl_warn'])){
		echo '<div class="notice notice-warning"><p>'.$GLOBALS['pl_warn'].'</p></div>';
	}
	
	// Is it a pagelayer theme ?
	if(!file_exists($pagelayer_theme_path.'/pagelayer.conf')){
		echo 'This utility is for importing content of the current active theme if its a Pagelayer Theme. Your current theme is <b>not</b> a Pagelayer exported theme ! If you want to export your content and make it into a distributable theme, please refer to the guide <a href="">here</a>.';
		die();
	}
	
	echo '
<style>
.pagelayer_img_screen{
width: 120px;
margin: 0px 15px 10px 15px;
display: inline-block;
border: 1px solid transparent;
border-radius: 3px;
}

.pagelayer_img_selected{
border: 1px solid #1A9CDB;
}

.pagelayer_img_div{
overflow: hidden;
height: 160px;
}

.pagelayer_img_name{
text-align: center;
background: #fff;
padding: 5px 10px;
border-top: 1px solid #ccc;
}

.button-pagelayer{
padding: 12px 25px !important;
font-size: 15px !important;
font-weight: bold;
background: #7444fd !important;
color: #fff !important;
border: 1px solid #7444fd !important;
transition: all .3s linear;
pointer: cursor;
}

.button-pagelayer:hover{
background: #fff !important;
color: #7444fd !important;
}
</style>

<script>

jQuery(document).ready(function(){
	var $ = jQuery;

	var choose_image = function(jEle){		
		$("#pagelayer_display_image").attr("src", jEle.find("img").attr("src"));
		
		$(".pagelayer_img_screen").removeClass("pagelayer_img_selected");
		jEle.addClass("pagelayer_img_selected");
	}
	
	var first = $(".pagelayer_img_screen:first");
	var home = $(".pagelayer_img_screen[page=home]");
	
	if(home.length > 0){
		first = home;
	}
	
	choose_image(first);
	
	$(".pagelayer_img_screen").on("click", function(){
		choose_image($(this));
	});
	
	$("#pagelayer_import_submit").on("click", function(){
		if(confirm("This will overwrite any pages / pagelayer templates which have the same post name. Should we proceed ?")){
			return true;
		}else{
			return false;
		}
		
	});
	
});
</script>

<div><h1 style="margin-bottom: 10px; padding-top: 0px;">'.$pagelayer_theme->name.'</h1></div>
<div style="margin: 0px -10px; vertical-align: top;">
	<div style="width: 52%; display: inline-block; text-align: center;">
		<div style="width: 100%; max-height: 400px; overflow: auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<img id="pagelayer_display_image" src="'.$pagelayer_theme_url.'/screenshots/home.jpg" width="100%">
		</div>
	</div>
	<div style="width: 45%; display: inline-block; padding: 0px 10px; vertical-align: top;">';
	
	foreach($pagelayer_pages['page'] as $k => $v){
		echo '<div class="pagelayer_img_screen" page="'.$k.'">
			<div class="pagelayer_img_div"><img src="'.$pagelayer_theme_url.'/screenshots/'.$k.'.jpg" width="100%" /></div>
			<div class="pagelayer_img_name">'.$v['post_title'].'</div>
		</div>';
	}
	
	echo '</div>
</div>

<div style="position:fixed; bottom: 30px; right: 30px;">
	<form action="" method="post" enctype="multipart/form-data">
		<input name="theme" value="'.get_template().'" type="hidden" />
		<input id="pagelayer_import_submit" name="import_theme" class="button button-pagelayer" value="Import Theme Content" type="submit" />
	</form>
</div>';
	
}

// The actual function to import the theme
function pagelayer_import_theme($new_theme){
	
global $wpdb, $wp_rewrite;	
global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	$pagelayer_theme_path = get_stylesheet_directory();
	//die($pagelayer_theme_path);
	
	/////////////////////////
	// Handle PAGELAYER DATA
	/////////////////////////
	
	// Load the PGL conf
	$pgl = file_get_contents($pagelayer_theme_path.'/pagelayer.conf');
	$pgl = @json_decode($pgl, true);
	
	if(empty($pgl['header'])){
		die('Header list not found. Report to Website Builder Team');
	}
	
	// Check the theme files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		//print_r($path);
		
		// Does the page exist ?
		if(!file_exists($path) || pagelayer_cleanpath(realpath($path)) != $path){
			die('Something is fishy with this theme as the template '.$k.' of type '.$v['type'].' was not found');
		}
		
	}
	
	// Check the theme files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		
		$new_post = array();
	
		// Is the page there ?
		$template = get_page_by_path($k, OBJECT, $pagelayer->builder['name']);
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($template)){
			
			$rev = wp_save_post_revision($template->ID);
			
			// Did we save the rev ?
			if(empty($rev)){
				// TODO : Throw error
			}
			
			$new_post['ID'] = $template->ID;
			
		}
		
		// Make an array
		$new_post['post_content'] = file_get_contents($path);
		$new_post['post_title'] = $v['title'];
		$new_post['post_name'] = $k;
		$new_post['post_type'] = $pagelayer->builder['name'];
		$new_post['post_status'] = 'publish';
		$new_post['comment_status'] = 'closed';
		$new_post['ping_status'] = 'closed';		
		//r_print($new_post);die();
		
		// Lets replace the variables for social icons
		//$new_post['post_content'] = preg_replace_callback('/\[pl_social ([^\]]*)\]/is', 'sitepad_handle_social_urls', $new_post['post_content']);
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		$post_id = $ret;
		
		// Did we save the rev ?
		if(empty($ret)){
			die('Could not update the Pagelayer Template '.$k);
		}
		
		// Save our template type
		update_post_meta($post_id, 'pagelayer_template_type', $v['type']);
		update_post_meta($post_id, 'pagelayer_template_conditions', $v['conditions']);
		
		// Any conditions having Page IDs that need to be updated ?
		if(!empty($v['conditions'])){
			
			foreach($v['conditions'] as $ck => $cv){
				if(!empty($cv['id'])){
					$conditions[$post_id][$ck] = $cv['id'];
				}
			}
			
		}
		
	}
	
	/////////////////////////
	// Handle the PAGES Data
	/////////////////////////
	
	// Load the new themes pages array
	$data = file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf');
	$data = @json_decode($data, true);
	//r_print($data);die();
	
	if(empty($data['page'])){
		die('Pages list not found. Report to Website Builder Team');
	}
	
	// Check the theme files
	foreach($data['page'] as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Does it have the title and slug ?
		if(empty($v['post_title']) || empty($v['post_name'])){
			die('Something is fishy with this theme as there is no title or slug for '.$k);
		}
		
		// Does the page exist ?
		if(!file_exists($path) || pagelayer_cleanpath(realpath($path)) != $path){
			die('Something is fishy with this theme');
		}
		
	}
	
	// Now check the pages if it exist in this installation ?
	foreach($data['page'] as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Is the page there ?
		$page = get_page_by_path($v['post_name']);
		//r_print($page);
		
		// Are we to insert the page ?
		if(empty($page)){
			
			$new_post = array();
			
			// Make an array
			$new_post['post_content'] = file_get_contents($path);
			$new_post['post_title'] = $v['post_title'];
			$new_post['post_name'] = $v['post_name'];
			$new_post['post_type'] = 'page';
			$new_post['post_status'] = 'publish';			
			//r_print($new_post);die();
			
			// Lets replace the variables for social icons
			//$new_post['post_content'] = preg_replace_callback('/\[pl_social ([^\]]*)\]/is', 'sitepad_handle_social_urls', $new_post['post_content']);
			
			// Now insert / update the post
			$ret = pagelayer_insert_content($new_post, $err);
			
			// Did we save the post ?
			if(empty($ret)){
				die('Could not update the page '.$v['post_name']);
			}
			
			$pages_id_map[$v['ID']] = $ret;
			
			// Does the screenshot exist ?
			$screenshot_file = $pagelayer_theme_path.'/screenshots/'.$v['post_name'].'.jpg';
			if(file_exists($screenshot_file)){
				
			}
		
		}
		
	}
	
	// Update Post for import
	if(!empty($conditions)){
		
		foreach($conditions as $post_ID => $v){
			
			$cond = get_post_meta($post_ID, 'pagelayer_template_conditions', 1);
			
			foreach($v as $ck => $cv){
			
				if(!empty($pages_id_map[$cv])){
					$cond[$ck]['id'] = $pages_id_map[$cv];
				}
			
			}
			
			update_post_meta($post_id, 'pagelayer_template_conditions', $cond);
			
		}
		
	}
	
	// Save that we have imported the theme
	update_option('pagelayer_theme_'.get_template().'_imported', time(), true);
	
	// Call a function for the theme if they want to execute something
	$ret = apply_filters('pagelayer_theme_imported', get_template());
	
	return true;

}