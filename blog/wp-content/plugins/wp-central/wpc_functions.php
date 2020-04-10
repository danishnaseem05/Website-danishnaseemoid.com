<?php

if (!defined('ABSPATH')){
    exit;
}

/* function wpc_died(){
	print_r(error_get_last());
}
register_shutdown_function('wpc_died'); */

include_once('wpcentral_lang.php');
 
/*
 * Fetch the value of option name from options table
 *
 * @param		string $option_name option_name to retrieve from table.
 * @param		mixed $default_value Default value to return when the option does not exist.
 * @param		int $site_id Site ID to update. Used for multisite installations only.
 * @param		bool $use_cache Whether to use cache or not. Used for multisite installations only.
 * @returns		string The option value based on $option_name
 * @since		1.0
 *
 * @refer		get_option()
 * @link			https://developer.wordpress.org/reference/functions/get_option/
 */
function wpc_get_option($option_name, $default_value = false, $site_id = null, $use_cache = true){
	
    if($site_id !== null && is_multisite()){
        return get_site_option($option_name, $default_value, $use_cache);
    }
    return get_option($option_name, $default_value);
}

/*
 * Generate a random string for the given length
 *
 * @param		int $length The number of charactes that should be returned
 * @return		string Randomly geterated string of the given number of charaters
 * @since		1.0
 */
function wpc_srandstr($length){
	$randstr = "";	
	for($i = 0; $i < $length; $i++){	
		$randnum = mt_rand(0,61);		
		if($randnum < 10){		
			$randstr .= chr($randnum+48);			
		}elseif($randnum < 36){		
			$randstr .= chr($randnum+55);			
		}else{		
			$randstr .= chr($randnum+61);			
		}		
	}	
	return strtolower($randstr);
}

/*
 * A function to display preformatted array. Basically adds the <pre> before and after the print_r() output.
 *
 * @param        array $array
 * @return       string Best for HTML dump of an array.
 * @since     	 1.0
 */
function wpc_print($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

/**
 * A function to return all the installed plugins and their description.
 *
 * @since     	 1.0
 */
function wpc_get_plugins(){
	return get_plugins();
}

/**
 * A function to check if the plugin is active or not.
 *
 * @param        string $pluginBasename slug of the plugin
 * @since     	 1.0
 */
function wpc_is_plugin_active($pluginBasename){
	return is_plugin_active($pluginBasename);
}

/**
 * A function to check if the plugin is installed.
 *
 * @param        string $plslug slug of the plugin
 * @since     	 1.0
 */
function wpc_is_plugin_installed($plslug){
	
	$all_installed_plugins = wpc_get_plugins();
	$slugs = array_keys($all_installed_plugins);

	$is_installed = 0;
	foreach($all_installed_plugins as $key => $val){
		if(strpos($key, $plslug) !== false){
			$is_installed = true;
			break;
		}
	}

	return $is_installed;
}

/**
 * Activates the plugins on the website
 *
 * @param        array $plugin_slug array of plugin's slug values
 * @since     	 1.0
 */
function wpc_activate_plugin($plugin_slug = array()){
    global $wp_config;
	
	$active_plugins = wpc_get_option('active_plugins');

	// Build final list of selected plugins and activated ones.
	foreach($plugin_slug as $k => $v){
		if(in_array($v, $active_plugins)){
	        continue;
	    }
		
		$active_plugins[] = $v;
	}
	
	$res = update_option('active_plugins', $active_plugins);
	
	return $res;
}

/**
 * Fetch the plugin's main file to generate the plugin slug
 *
 * @param		string $path path of the plugin directory
 * @param		string $slug directory name of the plugin
 * @since		1.3
 */
function wpc_get_plugin_path($path, $slug){
	global $__settings;
	
	$path = wpc_cleanpath($path);
	
	if(wpc_sfile_exists($__settings['softpath'].'/wp-content/plugins/'.$slug.'/'.$slug.'.php')){
		return $plugin_path = $slug.'/'.$slug.'.php';
	}
	
	$list = wpc_filelist($path, 0);
	$plugin_files = array();
	
	foreach($list as $lk => $lv){
		if(!is_dir($lk)){
			$plugin_files[basename($lk)] = $lk;
		}
	}
	
	foreach($plugin_files as $pk => $pv){
		$data = wpc_sfile($pv);
		if(preg_match('/\n(\s*?)(\*?)(\s*?)Plugin(\s*?)Name:(.*?)\n/is',$data)){
			return $plugin_path = $slug.'/'.$pk;
			break;
		}
	}
	
	return $plugin_path = $slug.'/'.$slug.'.php';
}

/**
 * Replaces '\\' with '/' and Truncates / at the end.
 * e.g. E:\\path is converted to E:/path
 *
 * @param		string $path
 * @returns		string The new path which works everywhere !
 * @since		1.3
 */
function wpc_cleanpath($path){
	
	$path = str_replace('\\\\', '/', $path);
	$path = str_replace('\\', '/', $path);
	return rtrim($path, '/');
}

/* The below function will list all folders and files within a directory
 *
 * @param		string $startdir specify the directory to start from; format: must end in a "/"
 * @param		bool $searchSubdirs True if you want to search subdirectories
 * @param		bool $directoriesonly True if you want to only return directories
 * @param		$maxlevel "all" or a number; specifies the number of directories down that you want to search
 * @param		integer $level directory level that the function is currently searching
 * @since		1.3
*/
function wpc_filelist($startdir="./", $searchSubdirs=1, $directoriesonly=0, $maxlevel="all", $level=1, $reset = 1) {
	//list the directory/file names that you want to ignore
	$ignoredDirectory = array();
	$ignoredDirectory[] = ".";
	$ignoredDirectory[] = "..";
	$ignoredDirectory[] = "_vti_cnf";
	global $directorylist;    //initialize global array

	if(substr($startdir, -1) != '/'){
		$startdir = $startdir.'/';
	}
   
	if (is_dir($startdir)) {
		if ($dh = opendir($startdir)) {
			while (($file = readdir($dh)) !== false) {
				if (!(array_search($file,$ignoredDirectory) > -1)) {
					if (@filetype($startdir . $file) == "dir") {

						//build your directory array however you choose;
						//add other file details that you want.

						$directorylist[$startdir . $file]['level'] = $level;
						$directorylist[$startdir . $file]['dir'] = 1;
						$directorylist[$startdir . $file]['name'] = $file;
						$directorylist[$startdir . $file]['path'] = $startdir;
						if ($searchSubdirs) {
							if ((($maxlevel) == "all") or ($maxlevel > $level)) {
								wpc_filelist($startdir . $file . "/", $searchSubdirs, $directoriesonly, $maxlevel, ($level + 1), 0);
							}
						}


					} else {
						if (!$directoriesonly) {

							//  echo substr(strrchr($file, "."), 1);
							//if you want to include files; build your file array 
							//however you choose; add other file details that you want.
							$directorylist[$startdir . $file]['level'] = $level;
							$directorylist[$startdir . $file]['dir'] = 0;
							$directorylist[$startdir . $file]['name'] = $file;
							$directorylist[$startdir . $file]['path'] = $startdir;
						}
					}
				}
			}
			closedir($dh);
		}
	}

	if(!empty($reset)){
		$r = $directorylist;
		$directorylist = array();
		return($r);
	}
}

/**
 * De-activates the plugins on the website
 *
 * @param        array $plugin_slug array of plugin's slug values
 * @since     	 1.0
 */
function wpc_deactivate_plugin($plugin_slug = array()){
    global $wp_config;	
	
	$active_plugins = wpc_get_option('active_plugins');

	// Build final list of selected plugins and activated ones.
	foreach($plugin_slug as $k => $v){
	    
	    if(in_array($v, $active_plugins)){
	        continue;
	    }
	    
		$active_plugins[] = $v;
	}
	
	foreach($active_plugins as $pk => $pv){
	    if(in_array($pv, $plugin_slug)){
	        unset($active_plugins[$pk]);
	    }
	}
	
	$res = update_option('active_plugins', $active_plugins);
	
	return $res;
}

/**
 * Fetch the list of outdated plugins on the website
 *
 * @since     	 1.0
 */
function wpc_get_outdated_plugins(){
	global $wp_config, $error;
	
	// Get the list of active plugins
	$squery = 'SELECT `option_value` FROM `'.$wp_config['dbprefix'].'options` WHERE `option_name` = "active_plugins";';	
	$sresult = wpc_sdb_query($squery, $wp_config['softdbhost'], $wp_config['softdbuser'], $wp_config['softdbpass'], $wp_config['softdb']);
	
	$active = array();
	$active = unserialize($sresult[0]['option_value']);

	foreach($active as $plugin_file){
		$plugin_data = array();
		if (!wpc_sfile_exists($wp_config['plugins_root_dir'].'/'.$plugin_file)){
			continue;
		}

		$plugin_data = wpc_get_plugin_data($wp_config['plugins_root_dir'].'/'.$plugin_file);

		if(empty($plugin_data['Plugin Name'])){
			continue;
		}else{
			$plugin_data['Name'] = $plugin_data['Plugin Name'];
		}

		$plugins[$plugin_file] = $plugin_data;
	}

	uasort($plugins, '_sort_uname_callback');
	
	$to_send = (object) compact('plugins', 'active');
	$options = array('plugins' => serialize($to_send));
	
	// Check the WordPress API to get the list of outdated plugins
	$raw_response = wp_remote_post('http://api.wordpress.org/plugins/update-check/1.0/', array('body' => $options));
	$body = wp_remote_retrieve_body($raw_response);
	$outdated_plugins = unserialize($body);
	
	// We need the Plugin name to send via email
	foreach($outdated_plugins as $plugin_file => $p_data){
		if(!empty($plugins[$plugin_file]['Name'])){
			$outdated_plugins[$plugin_file]->Name = $plugins[$plugin_file]['Name'];
		}
	}
	
	return $outdated_plugins;
}

/**
 * This is to extract the plugin details from the plugin file
 *
 * @param        string $pluginPath directory path of the installed plugin
 * @since     	 1.0
 */
function wpc_get_plugin_data($pluginPath = ''){
	global $plugin_details;
	
	$plugin_details = array();
	
	if(empty($pluginPath)){
		return false;
	}
	
	$tmp_data = array();
	$data = array();
	$data = wpc_sfile($pluginPath);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Plugin(\s*?)Name:(.*?)\n/is', 'wpc_plugin_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Plugin(\s*?)URI:(.*?)\n/is', 'wpc_plugin_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Description:(.*?)\n/is', 'wpc_plugin_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Version:(.*?)\n/is', 'wpc_plugin_callback', $data, 1);
	
	return $plugin_details;
}

// This is a callback function for preg_replace in wpc_get_plugin_data
function wpc_plugin_callback($matches){
    global $plugin_details;
	$tmp_data = explode(':', $matches[0], 2);
	$tmp_data[0] = str_replace('*', '', $tmp_data[0]);
	$key = trim($tmp_data[0]);
	$value = trim($tmp_data[1]);
	$plugin_details[$key] = $value;
}

/**
 * A function to check if the theme is installed.
 *
 * @param        string $thslug slug of the theme
 * @since     	 1.0
 */
function wpc_is_theme_installed($thslug){
	
	$all_installed_themes = wp_get_themes();
	$slugs = array_keys($all_installed_themes);
    
	if(isset($all_installed_themes[$thslug])){
		return true;
	}
	
	return false;
}

/**
 * Returns active theme for the website.
 *
 * @since     	 1.0
 */
function wpc_get_active_theme(){
	$raw_list = wp_get_theme();
	return wpc_get_themes_details(array($raw_list->stylesheet));
}

/**
 * A function to return all the installed themes and their description.
 *
 * @since     	 1.0
 */
function wpc_get_installed_themes(){
	$raw_list = wp_get_themes();
	$theme_slugs = array_keys($raw_list);

	return wpc_get_themes_details($theme_slugs);
}

/**
 * Returns details of the themes from wordPress.org.
 *
 * @param        array $themes array of themes
 * @since     	 1.0
 */
function wpc_get_themes_details($themes = array()){
	global $wp_config, $error;
	
	$apiurl ='http://api.wordpress.org/themes/info/1.0/';
	$theme_details = array();
	foreach($themes as $current_theme){
		$theme_data = wpc_get_theme_data($wp_config['themes_root_dir'].'/'.$current_theme.'/style.css');
	
		$post_data = array(
				'action' => 'theme_information',
				'request' => serialize( (object) array( 'slug' => $current_theme )));
		
		$raw_response = wp_remote_post($apiurl, array('body' => $post_data));
    		$body = wp_remote_retrieve_body($raw_response);
    		$api_data = unserialize($body);
		
		$theme_details[$current_theme] = $api_data;
		$theme_details[$current_theme]->installed_version = $theme_data['Version'];
		
		if(wpc_sversion_compare($theme_data['Version'], $api_data->version, '<')){
			$theme_details[$current_theme]->new_version = $api_data->version;
		}
	}
	return $theme_details;
}

/**
 * This is to extract the theme details from the theme file
 *
 * @param        string $themePath directory path of the installed theme
 * @since     	 1.0
 */
function wpc_get_theme_data($themePath = ''){

	global $theme_details;
	
	$theme_details = array();
	
	if(empty($themePath)){
		return false;
	}
	
	$tmp_data = array();
	$data = array();
	$data = wpc_sfile($themePath);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Theme(\s*?)Name:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Theme(\s*?)URI:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Description:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Version:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Author:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	preg_replace_callback('/\n(\s*?)(\*?)(\s*?)Author(\s*?)URI:(.*?)\n/is', 'wpc_theme_callback', $data, 1);
	
	return $theme_details;
}

// This is a callback function for preg_replace used in wpc_get_theme_data
function wpc_theme_callback($matches){
global $theme_details;
	$tmp_data = explode(':', $matches[0], 2);
	$tmp_data[0] = str_replace('*', '', $tmp_data[0]);
	$key = trim($tmp_data[0]);
	$value = trim($tmp_data[1]);
	$theme_details[$key] = $value;
}

/**
 * Activates the theme on the website
 *
 * @param        string $theme_slug slug of the theme
 * @since     	 1.0
 */
function wpc_activate_theme($theme_slug = array()){
	global $error, $l;
	
	$theme_root = $wp_config['themes_root_dir'].'/'.$theme_slug[0];

	$res = switch_theme($theme_root, $theme_slug[0]);

	if(is_wp_error($res)) {
		$error = $res->get_error_message();
	}elseif($res === false) {
		$error = $l['action_failed'];
	}

	if(!empty($error)){
		return false;
	}

	return true;
}

/**
 * Function to delete a theme
 *
 * @param        string $theme_slug slug of the theme
 * @since     	 1.0
 */
function wpc_delete_theme($theme_slug = array()){
	global $error, $l;
    
	foreach($theme_slug as $slug){
		$res = delete_theme($slug);
	}
    
	if(is_wp_error($res)) {
		$error = $res->get_error_message();
		
	}elseif($res === false) {
		$error = $l['action_failed'];
	}
    
	if(!empty($error)){
		return false;
	}
	
	return true;
}

/**
 * Takes care of Slashes
 *
 * @param		string $string The string that will be processed
 * @return		string A string that is safe to use for Database Queries, etc
 * @since		1.0
 */
function wpc_inputsec($string){

	if(!get_magic_quotes_gpc()){
	
		$string = addslashes($string);
	
	}else{
	
		$string = stripslashes($string);
		$string = addslashes($string);
	
	}
	
	// This is to replace ` which can cause the command to be executed in exec()
	$string = str_replace('`', '\`', $string);
	
	return $string;

}

/**
 * Converts Special characters to html entities
 *
 * @param        string $string The string containing special characters
 * @return       string A string containing special characters replaced by html entities of the format &#ASCIICODE;
 * @since     	 1.0
 */
function wpc_htmlizer($string){

global $globals;

	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
	
	preg_match_all('/(&amp;#(\d{1,7}|x[0-9a-fA-F]{1,6});)/', $string, $matches);
	
	foreach($matches[1] as $mk => $mv){		
		$tmp_m = wpc_entity_check($matches[2][$mk]);
		$string = str_replace($matches[1][$mk], $tmp_m, $string);
	}
	
	return $string;
	
}

/**
 * Used in function htmlizer()
 *
 * @param        string $string
 * @return       string
 * @since     	 1.0
 */
function wpc_entity_check($string){
	
	//Convert Hexadecimal to Decimal
	$num = ((substr($string, 0, 1) === 'x') ? hexdec(substr($string, 1)) : (int) $string);
	
	//Squares and Spaces - return nothing 
	$string = (($num > 0x10FFFF || ($num >= 0xD800 && $num <= 0xDFFF) || $num < 0x20) ? '' : '&#'.$num.';');
	
	return $string;
			
}

/**
 * OPTIONAL REQUEST of the given REQUEST Key
 *
 * @param        string $name The key of the $_REQUEST array i.e. the name of the input / textarea text 
 * @param        string $default The value to return if the $_REQUEST[$name] is NOT SET
 * @return       string Returns the string if the REQUEST is there otherwise the default value given.
 * @since     	 1.0
 */
function wpc_optREQ($name, $default = ''){

global $error;

	//Check the POSTED NAME was posted
	if(isset($_REQUEST[$name])){
	
		return wpc_inputsec(wpc_htmlizer(trim($_REQUEST[$name])));
		
	}else{
		
		return $default;
	
	}

}

/**
 * OPTIONAL POST of the given POST Key
 *
 * @param        string $name The key of the $_POST array i.e. the name of the input / textarea text 
 * @param        string $default The value to return if the $_POST[$name] is NOT SET
 * @return       string Returns the string if the POST is there otherwise the default value given.
 * @since		1.4.6
 */
function wpc_optPOST($name, $default = ''){

global $error;

	//Check the POSTED NAME was posted
	if(isset($_POST[$name])){
	
		return wpc_inputsec(wpc_htmlizer(trim($_POST[$name])));
		
	}else{
		
		return $default;
	
	}

}

/**
 * OPTIONAL GET of the given GET Key i.e. dont throw a error if not there
 *
 * @param        string $name The key of the $_GET array i.e. the name of the input / textarea text 
 * @param        string $default The value to return if the $_GET[$name] is NOT SET
 * @return       string Returns the string if the GET is there otherwise the default value given.
 * @since     	 1.0
 */
function wpc_optGET($name, $default = ''){

global $error;

	//Check the GETED NAME was GETed
	if(isset($_GET[$name])){
	
		return wpc_inputsec(wpc_htmlizer(trim($_GET[$name])));
		
	}else{
		
		return $default;
	
	}

}

/**
 * A function to load a file from the net
 *
 * @param        string $url The URL to read
 * @param        string $writefilename Instead of returning the data save it to the path given
 * @return       string The data fetched
 * @since     	 1.0
 */
function wpc_get_web_file($url, $writefilename = ''){

	$response = wp_remote_get($url);
	$file = wp_remote_retrieve_body($response);
	
	//Are we to store the file
	if(empty($writefilename)){
		return $file;
	
	//Store the file
	}else{
		$fp = @fopen($writefilename, "wb"); //This opens the file
		
		//If its opened then proceed
		if($fp){
			if(@fwrite($fp, $file) === FALSE){
				return false;
			//Wrote the file
			}else{
				@fclose($fp);
				return true;
			}
		}
	}	
	return false;
}

/**
 * A Function to unzip a ZIP file 
 *
 * @param        string $file The ZIP File
 * @param        string $destination The Final destination where the file will be unzipped
 * @param        int $overwrite Whether to Overwrite existing files
 * @param        array $include include files of the given pattern
 * @param        array $exclude exclude files of the given pattern
 * @return       boolean
 * @since     	 1.0
 */
function wpc_unzip($file, $destination, $overwrite = 0, $include = array(), $exclude = array()){

    include_once('wpc_soft_pclzip.php');
	$archive = new softpclzip($file);

	$result = $archive->_extract(PCLZIP_OPT_PATH, $destination, 
									PCLZIP_CB_PRE_EXTRACT, 'wpc_inc_exc', 
									PCLZIP_OPT_REPLACE_NEWER);
	
	if($result == 0){
		return false;
	}
	return true;
}

/**
 * Process includes and excludes of function unzip
 *
 * @param        $p_event
 * @param        $v
 * @return       Returns boolean
 * @since     	 1.0
 */
function wpc_inc_exc($p_event, &$v){
	return 1;
}

/**
 * Checks if a file is symlink or hardlink
 *
 * @returns 	 bool false if file is a symlink or a hardlink else true
 * @since     	 1.0
 */
function wpc_is_safe_file($path){

	// Is it a symlink ?
	if(is_link($path)) return false;
	
	// Is it a file and is a link ?
	$stat = @stat($path);
	if(!is_dir($path) && $stat['nlink'] > 1) return false;
	
	return true;
}

/**
 * Read file contents from the DESTINATION. Should be used when an installations file is to be fetched. 
 * For local package file, use the PHP file() function. The main usage of sfile is for import or upgrade !
 *
 * @package		files
 * @param		string $path The path of the file
 * @returns		bool
 * @since     	 1.0
 */
function wpc_sfile($path){
			
	// Is it safe to read this file ? 
	if(!wpc_is_safe_file($path)){
		return false;
	}
	
	return @implode('', file($path));
}

/**
 * Fetch website's configuration details from the config file
 *
 * @since     	 1.0
 */
function wpc_fetch_wp_config(){
	
	global $wpdb;
	
	$r = array();
	
	$r['softdbhost'] = $wpdb->dbhost;
	$r['softdbuser'] = $wpdb->dbuser;
	$r['softdbpass'] = $wpdb->dbpassword;
	$r['softdb'] = $wpdb->dbname;
	$r['dbprefix'] = $wpdb->prefix;
	
	$r['ver'] = wpc_version_wp();
	
	//No trailing slash
	$updir = wp_upload_dir();
	$r['uploads_dir'] = realpath($updir['basedir']);
	$r['themes_root_dir'] = realpath(get_theme_root());
	$r['plugins_root_dir'] = realpath(plugin_dir_path( __DIR__ ));
	
	return $r;	
}

/**
 * Fetch website's currently installed version
 *
 * @since     	 1.0
 */
function wpc_version_wp(){

	$file = wpc_sfile(get_home_path().'wp-includes/version.php');
	
	if(!empty($file)){
		wpc_soft_preg_replace('/\$wp_version(\s*?)=(\s*?)("|\')(.*?)("|\');/is', $file, $ver, 4);
	}
	
	return $ver;
}

/**
 * This function will preg_match the pattern and return the respective values in $var
 * 
 * @param		$pattern This should be the pattern to be matched
 * @param		$file This should have the data to search from
 * @param		$var This will be the variable which will have the preg matched data
 * @param		$valuenum This should be the no of regular expression to be returned in $var
 * @param		$stripslashes 0 or 1 depending upon whether the stripslashes function is to be applied (1) or not (0)
 * @return		string Will pass value by reference in $var
 * @since     	1.0
 */
function wpc_soft_preg_replace($pattern, $file, &$var, $valuenum){	
	preg_match($pattern, $file, $matches);
	$var = trim($matches[$valuenum]);
}

/**
 * Unserialize a string and also fixes any broken serialized string before unserializing
 *
 * @param        string $str
 * @return       array Returns an array if successful otherwise false 
 * @since     	 1.0
 */
function wpc_unserialize($str){

	$var = @unserialize($str);
	
	if(empty($var)){
		
		preg_match_all('!s:(\d+):"(.*?)";!s', $str, $matches);
		foreach($matches[2] as $mk => $mv){
			$tmp_str = 's:'.strlen($mv).':"'.$mv.'";';
			$str = str_replace($matches[0][$mk], $tmp_str, $str);
		}
		$var = @unserialize($str);
	}
	
	//If it is still empty false
	if($var === false){
		return false;
	}else{
		return $var;
	}

}

////////////////////////////////////////////
// Custom MySQL functions for WPCentral
///////////////////////////////////////////

/**
 * Connect to mysqli if exists else mysql
 * 
 * @param        string $host database host to be connected
 * @param        string $user db username to be used to connect
 * @param        string $pass db password to be used to connect
 * @param        string $newlink create a new link (mysql only)
 * @returns 	 string $conn returns resource link on success or FALSE on failure
 * @since     	1.0
 */
function wpc_mysql_connect($host, $user, $pass, $newlink = false){
	
	if(extension_loaded('mysqli')){
		//echo 'mysqli';
		//To handle connection if user passes a custom port along with the host as 127.0.0.1:6446.
		//For testing, use port 127.0.0.1 instead of localhost as 127.0.0.1:6446 http://php.net/manual/en/mysqli.construct.php#112328
		$exh = explode(':', $host);
		if(!empty($exh[1])){
			$sconn = @mysqli_connect($exh[0], $user, $pass, '', $exh[1]);
		}else{
			$sconn = @mysqli_connect($host, $user, $pass);
		}
	}else{
		//echo 'mysql';
		$sconn = @mysql_connect($host, $user, $pass, $newlink);
	}
	
	return $sconn;
}

/**
 * Set the database character set
 * 
 * @param        string $conn database connection string
 * @param        string $charset character set to convert to
 * @returns	    bool true if character set is set
 * @since     	1.0
 */
function wpc_mysql_set_charset($conn, $charset){
	
	if(extension_loaded('mysqli')){
		//echo 'mysqli';
		$return = @mysqli_set_charset($conn, $charset);
	}else{
		//echo 'mysql';
		$return = @mysql_set_charset($charset, $conn);
	}
	
	return $return;
}

/**
 * Selects database mysqli if exists else mysql
 * 
 * @param        string $db database to be selected
 * @param        string $conn Resource Link
 * @returns 	 bool TRUE on success or FALSE on failure
 * @since     	 1.0
 */
function wpc_mysql_select_db($db, $conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_select_db($conn, $db);
	}else{
		$return = @mysql_select_db($db, $conn);
	}
	
	return $return;
}

/**
 * Executes the query mysqli if exists else mysql
 * 
 * @param        string $db database to be selected
 * @param        string $conn Resource Link
 * @returns 	 bool TRUE on success or FALSE on failure
 * @since     	 1.0
 */
function wpc_mysql_query($query, $conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_query($conn, $query);
	}else{
		$return = @mysql_query($query, $conn);
	}
	
	return $return;
}

/**
 * Fetches the result from a result link mysqli if exists else mysql
 * 
 * @param        string $result result to fetch the data from
 * @returns 	 mixed Returns an array of strings that corresponds to the fetched row, or FALSE if there are no more rows
 * @since     	 1.0
 */
function wpc_mysql_fetch_array($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_fetch_array($result);
	}else{
		$return = @mysql_fetch_array($result);
	}
	
	return $return;
}

/**
 * Fetches the result into associative array from a result link mysqli if exists else mysql
 * 
 * @param        string $result result to fetch the data from
 * @returns 	 mixed Returns an associative array of strings that corresponds to the fetched row, or FALSE if there are no more rows
 * @since     	 1.0
 */
function wpc_mysql_fetch_assoc($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_fetch_assoc($result);
	}else{
		$return = @mysql_fetch_assoc($result);
	}
	
	return $return;
}

/**
 * Get a result row as an enumerated array mysqli if exists else mysql
 * 
 * @param        string $result result to fetch the data from
 * @returns 	 mixed returns an array of strings that corresponds to the fetched row or FALSE if there are no more rows
 * @since     	 1.0
 */
function wpc_mysql_fetch_row($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_fetch_row($result);
	}else{
		$return = @mysql_fetch_row($result);
	}
	
	return $return;
}

/**
 * Get column information from a result and return as an object
 * 
 * @param        string $result result to fetch the data from
 * @param        string $field The numerical field offset
 * @returns 	 object Returns the definition of one column of a result set as an object. 
 * @since     	 1.0
 */
function wpc_mysql_fetch_field($result, $field){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_fetch_field_direct($result, $field);
	}else{
		$return = @mysql_fetch_field($result, $field);
	}
	
	return $return;
}

/**
 * Gets the fields meta
 * 
 * @param        string $result result to fetch the data from
 * @returns 	 	object returns object of fields meta 
 * @since     	 1.0
 */
function wpc_getFieldsMeta($result){
	// Build an associative array for a type look up
	
	if(!defined('WPC_MYSQLI_TYPE_VARCHAR')){
		define('WPC_MYSQLI_TYPE_VARCHAR', 15);
	}
	
	$typeAr = array();
	$typeAr[MYSQLI_TYPE_DECIMAL]     = 'real';
	$typeAr[MYSQLI_TYPE_NEWDECIMAL]  = 'real';
	$typeAr[MYSQLI_TYPE_BIT]         = 'int';
	$typeAr[MYSQLI_TYPE_TINY]        = 'int';
	$typeAr[MYSQLI_TYPE_SHORT]       = 'int';
	$typeAr[MYSQLI_TYPE_LONG]        = 'int';
	$typeAr[MYSQLI_TYPE_FLOAT]       = 'real';
	$typeAr[MYSQLI_TYPE_DOUBLE]      = 'real';
	$typeAr[MYSQLI_TYPE_NULL]        = 'null';
	$typeAr[MYSQLI_TYPE_TIMESTAMP]   = 'timestamp';
	$typeAr[MYSQLI_TYPE_LONGLONG]    = 'int';
	$typeAr[MYSQLI_TYPE_INT24]       = 'int';
	$typeAr[MYSQLI_TYPE_DATE]        = 'date';
	$typeAr[MYSQLI_TYPE_TIME]        = 'time';
	$typeAr[MYSQLI_TYPE_DATETIME]    = 'datetime';
	$typeAr[MYSQLI_TYPE_YEAR]        = 'year';
	$typeAr[MYSQLI_TYPE_NEWDATE]     = 'date';
	$typeAr[MYSQLI_TYPE_ENUM]        = 'unknown';
	$typeAr[MYSQLI_TYPE_SET]         = 'unknown';
	$typeAr[MYSQLI_TYPE_TINY_BLOB]   = 'blob';
	$typeAr[MYSQLI_TYPE_MEDIUM_BLOB] = 'blob';
	$typeAr[MYSQLI_TYPE_LONG_BLOB]   = 'blob';
	$typeAr[MYSQLI_TYPE_BLOB]        = 'blob';
	$typeAr[MYSQLI_TYPE_VAR_STRING]  = 'string';
	$typeAr[MYSQLI_TYPE_STRING]      = 'string';
	$typeAr[WPC_MYSQLI_TYPE_VARCHAR]     = 'string'; // for Drizzle
	// MySQL returns MYSQLI_TYPE_STRING for CHAR
	// and MYSQLI_TYPE_CHAR === MYSQLI_TYPE_TINY
	// so this would override TINYINT and mark all TINYINT as string
	// https://sourceforge.net/p/phpmyadmin/bugs/2205/
	//$typeAr[MYSQLI_TYPE_CHAR]        = 'string';
	$typeAr[MYSQLI_TYPE_GEOMETRY]    = 'geometry';
	$typeAr[MYSQLI_TYPE_BIT]         = 'bit';

	$fields = mysqli_fetch_fields($result);

	// this happens sometimes (seen under MySQL 4.0.25)
	if (!is_array($fields)) {
		return false;
	}

	foreach ($fields as $k => $field) {
		$fields[$k]->_type = $field->type;
		$fields[$k]->type = $typeAr[$field->type];
		$fields[$k]->_flags = $field->flags;
		$fields[$k]->flags = wpc_mysql_field_flags($result, $k);

		// Enhance the field objects for mysql-extension compatibilty
		//$flags = explode(' ', $fields[$k]->flags);
		//array_unshift($flags, 'dummy');
		$fields[$k]->multiple_key
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_MULTIPLE_KEY_FLAG);
		$fields[$k]->primary_key
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_PRI_KEY_FLAG);
		$fields[$k]->unique_key
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_UNIQUE_KEY_FLAG);
		$fields[$k]->not_null
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_NOT_NULL_FLAG);
		$fields[$k]->unsigned
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_UNSIGNED_FLAG);
		$fields[$k]->zerofill
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_ZEROFILL_FLAG);
		$fields[$k]->numeric
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_NUM_FLAG);
		$fields[$k]->blob
			= (int) (bool) ($fields[$k]->_flags & MYSQLI_BLOB_FLAG);
	}
	return $fields;
}

/**
 * Returns the field flags of the field in text format
 * 
 * @param        string $result result to fetch the data from
 * @param        string $field The numerical field offset
 * @returns 	 string Returns the field flags of the field in text format
 * @since     	1.0
 */
function wpc_mysql_field_flags($result, $i){
	
	if(!extension_loaded('mysqli')){
		return mysql_field_flags($result, $i);
	}
	
	$f = mysqli_fetch_field_direct($result, $i);
	$type = $f->type;
	$charsetnr = $f->charsetnr;
	$f = $f->flags;
	$flags = '';
	if ($f & MYSQLI_UNIQUE_KEY_FLAG) {
		$flags .= 'unique ';
	}
	if ($f & MYSQLI_NUM_FLAG) {
		$flags .= 'num ';
	}
	if ($f & MYSQLI_PART_KEY_FLAG) {
		$flags .= 'part_key ';
	}
	if ($f & MYSQLI_SET_FLAG) {
		$flags .= 'set ';
	}
	if ($f & MYSQLI_TIMESTAMP_FLAG) {
		$flags .= 'timestamp ';
	}
	if ($f & MYSQLI_AUTO_INCREMENT_FLAG) {
		$flags .= 'auto_increment ';
	}
	if ($f & MYSQLI_ENUM_FLAG) {
		$flags .= 'enum ';
	}
	// See http://dev.mysql.com/doc/refman/6.0/en/c-api-datatypes.html:
	// to determine if a string is binary, we should not use MYSQLI_BINARY_FLAG
	// but instead the charsetnr member of the MYSQL_FIELD
	// structure. Watch out: some types like DATE returns 63 in charsetnr
	// so we have to check also the type.
	// Unfortunately there is no equivalent in the mysql extension.
	if (($type == MYSQLI_TYPE_TINY_BLOB || $type == MYSQLI_TYPE_BLOB
		|| $type == MYSQLI_TYPE_MEDIUM_BLOB || $type == MYSQLI_TYPE_LONG_BLOB
		|| $type == MYSQLI_TYPE_VAR_STRING || $type == MYSQLI_TYPE_STRING)
		&& 63 == $charsetnr
	) {
		$flags .= 'binary ';
	}
	if ($f & MYSQLI_ZEROFILL_FLAG) {
		$flags .= 'zerofill ';
	}
	if ($f & MYSQLI_UNSIGNED_FLAG) {
		$flags .= 'unsigned ';
	}
	if ($f & MYSQLI_BLOB_FLAG) {
		$flags .= 'blob ';
	}
	if ($f & MYSQLI_MULTIPLE_KEY_FLAG) {
		$flags .= 'multiple_key ';
	}
	if ($f & MYSQLI_UNIQUE_KEY_FLAG) {
		$flags .= 'unique_key ';
	}
	if ($f & MYSQLI_PRI_KEY_FLAG) {
		$flags .= 'primary_key ';
	}
	if ($f & MYSQLI_NOT_NULL_FLAG) {
		$flags .= 'not_null ';
	}
	return trim($flags);
}

/**
 * Returns the text of the error message from previous MySQL/MySQLi operation
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 string Returns the error text from the last MySQL function
 * @since     	1.0
 */
function wpc_mysql_error($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_error($conn);
		
		// In mysqli if connection  is not made then we will get connection error using the following function.
		if(empty($conn)){
			$return = @mysqli_connect_error();
		}
		
	}else{
		$return = @mysql_error($conn);
	}
	
	return $return;
}

/**
 * Returns the numerical value of the error message from previous MySQL operation
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 int Returns the error number from the last MySQL function
 * @since     	1.0
 */
function wpc_mysql_errno($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_errno($conn);
	}else{
		$return = @mysql_errno($conn);
	}
	
	return $return;
}

/**
 * Retrieves the number of rows from a result set
 * 
 * @param        string $result result resource that is being evaluated
 * @returns 	 string The number of rows in a result set on success or FALSE on failure
 * @since     	1.0
 */
function wpc_mysql_num_rows($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_num_rows($result);
	}else{
		$return = @mysql_num_rows($result);
	}
	
	return $return;
}

/**
 * Get number of affected rows in previous MySQL/MySQLi operation
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 int Returns the number of affected rows on success, Zero indicates that no records were updated and -1 if the last query failed.
 * @since     	1.0
 */
function wpc_mysql_affected_rows($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_affected_rows($conn);
	}else{
		$return = @mysql_affected_rows($conn);
	}
	
	return $return;
}

/**
 * Get the ID generated in the last query
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 int The ID generated for an AUTO_INCREMENT column by the previous query on success, 0 if the previous query does not generate an AUTO_INCREMENT value, or FALSE if * 				 no MySQL connection was established. 
 * @since     	1.0
 */
function wpc_mysql_insert_id($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_insert_id($conn);
	}else{
		$return = @mysql_insert_id($conn);
	}
	
	return $return;
}

/**
 * Get number of fields in result
 * 
 * @param        string $result result resource that is being evaluated (Required by MySQL)
 * @returns 	 string Returns the number of fields in the result set on success or FALSE on failure
 * @since     	1.0
 */
function wpc_mysql_num_fields($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_num_fields($result);
	}else{
		$return = @mysql_num_fields($result);
	}
	
	return $return;
}

/**
 * Will free all memory associated with the result identifier 
 * 
 * @param        string $result result resource that is being evaluated
 * @returns 	 bool Returns TRUE on success or FALSE on failure
 * @since     	1.0
 */
function wpc_mysql_free_result($result){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_free_result($result);
	}else{
		$return = @mysql_free_result($result);
	}
	
	return $return;
}

/**
 * Close MySQL/MySQLi connection 
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 bool Returns TRUE on success or FALSE on failure
 * @since     	1.0
 */
function wpc_mysql_close($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_close($conn);
	}else{
		$return = @mysql_close($conn);
	}
	
	return $return;
}

/**
 * Get MySQL/MySQLi client info
 * 
 * @returns 	 string Returns a string that represents the MySQL client library version
 * @since     	1.0
 */
function wpc_mysql_get_client_info(){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_get_client_info();
	}else{
		$return = @mysql_get_client_info();
	}
	
	return $return;
}

/**
 * Get MySQL/MySQLi server info
 * 
 * @param        string $conn MySQL/MySQLi connection
 * @returns 	 string Returns a string that represents the MySQL server version
 * @since     	1.0
 */
function wpc_mysql_get_server_info($conn){
	
	if(extension_loaded('mysqli')){
		$return = @mysqli_get_server_info($conn);
	}else{
		$return = @mysql_get_server_info($conn);
	}
	
	return $return;
}

/**
 * Execute Database queries
 *
 * @param        string $queries The Database Queries seperated by a SEMI COLON (;) 
 * @param        string $host The Database HOST
 * @param        string $db The Database Name
 * @param        string $user The Database User Name
 * @param        string $pass The Database User Password
 * @param        string $conn The Database Connection
 * @returns		bool
 * @since     	1.0
 */
function wpc_sdb_query($queries, $host, $user, $pass, $db, $conn = '', $no_strict = 0){	
	return wpc_softmysqlfile($queries, $host, $user, $pass, $db, $conn);
	
}

/**
 * Dump SQL Data ($raw) into the given database.
 *
 * @param        string $raw The RAW SQL Data
 * @param        string $host The MySQL Host
 * @param        string $user The MySQL User
 * @param        string $pass The MySQL User password
 * @param        string $db The Database Name
 * @param        string $__conn Connection link to the database
 * @return       bool If there is an error $error is filled with the error
 * @since     	 1.0
 */
function wpc_softmysqlfile($raw, $host, $user, $pass, $db, $__conn = ""){

global $error, $l;
	
	$queries = wpc_sqlsplit($raw);
	
	//Make the Connection
	if(empty($__conn)){
		$__conn = @wpc_mysql_connect($host, $user, $pass, true);
	}
	
	//CHECK Errors and SELECT DATABASE
	if(!empty($__conn)){	
		if(!(@wpc_mysql_select_db($db, $__conn))){
			$error[] = $l['err_selectmy'].(!empty($_GET['debug']) ? wpc_mysql_error($__conn) : '');
			return false;
		}
	}else{
		$error[] = $l['err_myconn'].(!empty($_GET['debug']) ? wpc_mysql_error($__conn) : '');
		return false;
	}
	
	$num = count($queries);
	
	//Start the Queries
	foreach($queries as $k => $q){	
		
		//PARSE the String and make the QUERY
		$result = wpc_mysql_query($q, $__conn);
		
		//Looks like there was an error
		if(!$result){			
			$error[] = $l['err_makequery'].' : '.$k.'<br />'.$l['err_mynum'].' : '.wpc_mysql_errno($__conn).'<br />'.$l['err_myerr'].' : '.wpc_mysql_error($__conn).(wpc_sdebug('errquery') ? ' Query : '.$q : '');			
			return false;				
		}
		
		// Is there only one query ?
		if($num == 1){
			
			// Is it a SELECT query ?
			if(preg_match('/^(SELECT|SHOW|DESCRIBE)/is', $q)){ // CHECKSUM|OPTIMIZE|ANALYZE|CHECK|EXPLAIN
				
				// Accumulate the data !
				for($i = 0; $i < wpc_mysql_num_rows($result); $i++){
					$return[] = wpc_mysql_fetch_assoc($result);
				}
				
			}
	
			// Is it a INSERT query ? Then we will have to return insert id
			if(preg_match('/^INSERT/is', $q)){
				$return[] = wpc_mysql_insert_id($__conn);
			}	
		}
	
	}
	
	// Are we to return the data ?
	if(!empty($return)){
		return $return;
	}
	
	return true;
	
}

/**
 * Is debugging ON for the given key ?
 *
 * @param        string $key The Key to search for debugging
 * @return       int True on success
 * @since     	1.0
 */
function wpc_sdebug($key){
	if(@in_array($key, @$_GET['debug']) || @$_GET['debug'] == $key){
		return true;
	}
}

/**
 * phpMyAdmin SPLIT SQL function which splits the SQL data into seperate chunks that can be passed as QUERIES.
 *
 * @param        string $data The SQL RAW data
 * @returns 	 array The chunks of SQL Queries
 * @since     	 1.0
 */
function wpc_sqlsplit($data){

	$ret = array();
	$buffer = '';
	// Defaults for parser
	$sql = '';
	$start_pos = 0;
	$i = 0;
	$len= 0;
	$big_value = 200000000;
	$sql_delimiter = ';';
	
	$finished = false;
	
	while (!($finished && $i >= $len)) {
	
		if ($data === FALSE) {
			// subtract data we didn't handle yet and stop processing
			//$offset -= strlen($buffer);
			break;
		} elseif ($data === TRUE) {
			// Handle rest of buffer
		} else {
			// Append new data to buffer
			$buffer .= $data;
			// free memory
			$data = false;
			// Do not parse string when we're not at the end and don't have ; inside
			if ((strpos($buffer, $sql_delimiter, $i) === FALSE) && !$finished)  {
				continue;
			}
		}
		// Current length of our buffer
		$len = strlen($buffer);
		
		// Grab some SQL queries out of it
		while ($i < $len) {
			$found_delimiter = false;
			// Find first interesting character
			$old_i = $i;
			// this is about 7 times faster that looking for each sequence i
			// one by one with strpos()
			if (preg_match('/(\'|"|#|-- |\/\*|`|(?i)DELIMITER)/', $buffer, $matches, PREG_OFFSET_CAPTURE, $i)) {
				// in $matches, index 0 contains the match for the complete 
				// expression but we don't use it
				$first_position = $matches[1][1];
			} else {
				$first_position = $big_value;
			}
			/**
			 * @todo we should not look for a delimiter that might be
			 *       inside quotes (or even double-quotes)
			 */
			// the cost of doing this one with preg_match() would be too high
			$first_sql_delimiter = strpos($buffer, $sql_delimiter, $i);
			if ($first_sql_delimiter === FALSE) {
				$first_sql_delimiter = $big_value;
			} else {
				$found_delimiter = true;
			}
	
			// set $i to the position of the first quote, comment.start or delimiter found
			$i = min($first_position, $first_sql_delimiter);
	
			if ($i == $big_value) {
				// none of the above was found in the string
	
				$i = $old_i;
				if (!$finished) {
					break;
				}
				// at the end there might be some whitespace...
				if (trim($buffer) == '') {
					$buffer = '';
					$len = 0;
					break;
				}
				// We hit end of query, go there!
				$i = strlen($buffer) - 1;
			}
	
			// Grab current character
			$ch = $buffer[$i];
	
			// Quotes
			if (strpos('\'"`', $ch) !== FALSE) {
				$quote = $ch;
				$endq = FALSE;
				while (!$endq) {
					// Find next quote
					$pos = strpos($buffer, $quote, $i + 1);
					// No quote? Too short string
					if ($pos === FALSE) {
						// We hit end of string => unclosed quote, but we handle it as end of query
						if ($finished) {
							$endq = TRUE;
							$i = $len - 1;
						}
						$found_delimiter = false;
						break;
					}
					// Was not the quote escaped?
					$j = $pos - 1;
					while ($buffer[$j] == '\\') $j--;
					// Even count means it was not escaped
					$endq = (((($pos - 1) - $j) % 2) == 0);
					// Skip the string
					$i = $pos;
	
					if ($first_sql_delimiter < $pos) {
						$found_delimiter = false;
					}
				}
				if (!$endq) {
					break;
				}
				$i++;
				// Aren't we at the end?
				if ($finished && $i == $len) {
					$i--;
				} else {
					continue;
				}
			}
	
			// Not enough data to decide
			if ((($i == ($len - 1) && ($ch == '-' || $ch == '/'))
			  || ($i == ($len - 2) && (($ch == '-' && $buffer[$i + 1] == '-')
				|| ($ch == '/' && $buffer[$i + 1] == '*')))) && !$finished) {
				break;
			}
	
			// Comments
			if ($ch == '#'
			 || ($i < ($len - 1) && $ch == '-' && $buffer[$i + 1] == '-'
			  && (($i < ($len - 2) && $buffer[$i + 2] <= ' ')
			   || ($i == ($len - 1)  && $finished)))
			 || ($i < ($len - 1) && $ch == '/' && $buffer[$i + 1] == '*')
					) {
				// Copy current string to SQL
				if ($start_pos != $i) {
					$sql .= substr($buffer, $start_pos, $i - $start_pos);
				}
				// Skip the rest
				$j = $i;
				$i = strpos($buffer, $ch == '/' ? '*/' : "\n", $i);
				// didn't we hit end of string?
				if ($i === FALSE) {
					if ($finished) {
						$i = $len - 1;
					} else {
						break;
					}
				}
				// Skip *
				if ($ch == '/') {
					// Check for MySQL conditional comments and include them as-is
					if ($buffer[$j + 2] == '!') {
						$comment = substr($buffer, $j + 3, $i - $j - 3);
						if (preg_match('/^[0-9]{5}/', $comment, $version)) {
							if ($version[0] <= 50000000) {
								$sql .= substr($comment, 5);
							}
						} else {
							$sql .= $comment;
						}
					}
					$i++;
				}
				// Skip last char
				$i++;
				// Next query part will start here
				$start_pos = $i;
				// Aren't we at the end?
				if ($i == $len) {
					$i--;
				} else {
					continue;
				}
			}
			// Change delimiter, if redefined, and skip it (don't send to server!)
			if (strtoupper(substr($buffer, $i, 9)) == "DELIMITER"
			 && ($buffer[$i + 9] <= ' ')
			 && ($i < $len - 11)
			 && strpos($buffer, "\n", $i + 11) !== FALSE) {
			   $new_line_pos = strpos($buffer, "\n", $i + 10);
			   $sql_delimiter = substr($buffer, $i + 10, $new_line_pos - $i - 10);
			   $i = $new_line_pos + 1;
			   // Next query part will start here
			   $start_pos = $i;
			   continue;
			}
	
			// End of SQL
			if ($found_delimiter || ($finished && ($i == $len - 1))) {
				$tmp_sql = $sql;
				if ($start_pos < $len) {
					$length_to_grab = $i - $start_pos;
	
					if (! $found_delimiter) {
						$length_to_grab++;
					}
					$tmp_sql .= substr($buffer, $start_pos, $length_to_grab);
					unset($length_to_grab);
				}
				// Do not try to execute empty SQL
				if (! preg_match('/^([\s]*;)*$/', trim($tmp_sql))) {
					$sql = $tmp_sql;
					$ret[] = $sql;
					
					$buffer = substr($buffer, $i + strlen($sql_delimiter));
					// Reset parser:
					$len = strlen($buffer);
					$sql = '';
					$i = 0;
					$start_pos = 0;
					// Any chance we will get a complete query?
					//if ((strpos($buffer, ';') === FALSE) && !$finished) {
					if ((strpos($buffer, $sql_delimiter) === FALSE) && !$finished) {
						break;
					}
				} else {
					$i++;
					$start_pos = $i;
				}
			}
		} // End of parser loop
	} // End of import loop

	return $ret;

}


/**
 * Read the SQL file in parts and Dump the data into the given database
 *
 * @param        string $file The Path to SQL file
 * @param        string $host The MySQL Host
 * @param        string $user The MySQL User
 * @param        string $pass The MySQL User password
 * @param        string $db The Database Name
 * @param        string $__conn Connection link to the database
 * @return       bool If there is an error $error is filled with the error
 * @since		1.0
 */
function wpc_soft_mysql_parts($file, $host, $user, $pass, $db, $__conn = "", $delimiter = ';', $replace_data = array()){

global $__settings, $error, $software, $globals, $l, $softpanel;
	
	if(is_file($file) === true){
	
		//Make the Connection
		if(empty($__conn)){
			$__conn = @wpc_mysql_connect($host, $user, $pass, true);
		}
		
		//CHECK Errors and SELECT DATABASE
		if(!empty($__conn)){
			if(!(@wpc_mysql_select_db($db, $__conn))){
				$error[] = $l['err_selectmy'].(!empty($_GET['debug']) ? wpc_mysql_error($__conn) : '');
				return false;
			}
		}else{
			$error[] = $l['err_myconn'].(!empty($_GET['debug']) ? wpc_mysql_error($__conn) : '');
			return false;
		}
		
		$file = fopen($file, 'r');

		if(is_resource($file) === true){
			$query = array();
			$num = 0;
			while(feof($file) === false){

				if(is_string($query) === true){
					$query = array();
				}
				
				$query[] = fgets($file);

				if(preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1){
					$query = trim(implode('', $query));
					
					if(!empty($replace_data)){
						$query = strtr($query, $replace_data);
					}
					
					$result = wpc_mysql_query($query, $__conn);
					if(!$result){
						$error[] = $l['err_makequery'].' <br />'.$l['err_mynum'].' : '.wpc_mysql_errno($__conn).'<br />'.$l['err_myerr'].' : '.wpc_mysql_error($__conn).(wpc_sdebug('errquery') ? ' Query : '.$query : '');			
						return false;	
					}else{
						$num++;
					}
				}
			}
			
			fclose($file);
			
			// Is there only one query ?
			if($num == 1){
				
				// Is it a SELECT query ?
				if(preg_match('/^(SELECT|SHOW|DESCRIBE)/is', $query)){ // CHECKSUM|OPTIMIZE|ANALYZE|CHECK|EXPLAIN
					
					// Accumulate the data !
					for($i = 0; $i < wpc_mysql_num_rows($result); $i++){
						$return[] = wpc_mysql_fetch_assoc($result);
					}
					
				}
		
				// Is it a INSERT query ? Then we will have to return insert id
				if(preg_match('/^INSERT/is', $query)){
					$return[] = wpc_mysql_insert_id($__conn);
				}	
			}
	
			// Are we to return the data ?
			if(!empty($return)){
				return $return;
			}
		}else{
			$error['err_no_open_db_file'] = $l['err_no_open_db_file'];
			return false;
		}
	}else{
		$error['err_no_db_file'] = $l['err_no_db_file'];
		return false;
	}
	
	return true;
}

/**
 * Check whether a file or directory exists at the INSTALLATION level ONLY, not in the PACKAGE
 *
 * @param        string $path The path of the file or directory
 * @returns 	 	bool
 * @since		1.0
 */ 
function wpc_sfile_exists($path){	
	return file_exists($path);
}

/**
 * Softaculous Version Compare, fixes a bug with '+' as the last character
 *
 * @param        string $ver1 The first version
 * @param        string $ver2 The second version
 * @param        string $oper By default NULL or operators of the original version_compare() function
 * @param        string $vr By default Empty Array or An array which will contain alphabetic version and to be replace version (For handling alphabatic versions)
 * @return       int values as per the original version_compare() function
 * @since     	 1.0
 */
function wpc_sversion_compare($ver1, $ver2, $oper = NULL, $vr = array()){
	
	if(!empty($vr)){
		$ver2 = str_replace($vr['search'], $vr['replace'], $ver2);
	}
	
	$last1 = substr($ver1, -1);
	$last2 = substr($ver2, -1);
	
	if(!preg_match('/[0-9a-zA-Z]/is', $last1)){
		$ver1 = substr($ver1, 0, -1).'.0'.$last1.'0';
	}
	
	if(!preg_match('/[0-9a-zA-Z]/is', $last2)){
		$ver2 = substr($ver2, 0, -1).'.0'.$last2.'0';
	}
	
	if(is_null($oper)){
		return version_compare($ver1, $ver2);
	}else{
		return version_compare($ver1, $ver2, $oper);
	}
}

/**
 * Deletes a file.
 *
 * @param		string $path
 * @returns		bool
 * @since		1.0
 */
function wpc_sunlink($path){	
	return @unlink($path);
}

/**
 * Check whether the path is a directory
 *
 * @param		string $path The path of the file or directory
 * @returns		bool
 * @since		1.0
 */
function wpc_is_dir($path){
    return is_dir($path);
}

/**
 * Creates a directory. This is meant to be used by install.php, upgrade.php, etc. 
 * NOTE : Folder permissions cannot exceed 0755 in MKDIR. You must use schmod if necessary to give 0777
 *
 * @param        string $path
 * @param        octal $mode
 * @param        bool $rec Recurse into the directory and apply the changes 
 * @returns 	 bool
 * @since     	 1.0
 */
function wpc_mkdir($path, $mode = 0755, $rec = 0){
	if(!is_dir($path)){
		$ret = mkdir($path);
		return $ret;
	}
    return true;
}

/**
 * Changes the permissions (chmod) of the given path. This is meant to be used by install.php, upgrade.php, etc.
 *
 * @param		string $path The path of the file or directory
 * @param		octal $oct The new permission e.g. 0644
 * @returns		bool
 * @since		1.0
 */
function wpc_chmod($path, $mode){
    return chmod($path, $mode);
}

/**
 * Writes data to a file
 *
 * @param		string $file The path of the file or directory
 * @param		string $data Data to write to the file
 * @returns		bool
 * @since		1.0
 */
function wpc_put($file, $data){
	
	$fp = @fopen($file, "wb");
	if($fp){
		if(@fwrite($fp, $data) === FALSE){
			return false;
		}else{
			@fclose($fp);
			return true;
		}
	}
	return false;
}

/**
 * Deletes a file.
 *
 * @param		string $filename
 * @returns		bool
 * @since		1.0
 */
function wpc_delete($filename){
	return wpc_sunlink($filename);
}

/**
 * Removes a directory
 *
 * @param		string $directory path
 * @returns		bool
 * @since		1.4.3
 */
function wpc_srm($path){
	
	if(is_dir($path)){
		return wpc_rmdir_recursive($path);
	}	
	// So its a file !
	return wpc_sunlink($path);
}

/**
 * Used in file_functions to perform backup
 *
 * @param		string $filename
 * @returns		bool
 * @since		1.0
 */
function wpc_chdir($dir){
	return wpc_is_dir($dir);
}

/**
 * Check whether a file or directory exists
 *
 * @param		string $file The path of the file or directory
 * @returns		bool
 * @since		1.0
 */ 
function wpc_file_exists($file){
	return file_exists($file);
}

/**
 * Renames a file/folder.
 *
 * @param		string $from oldname
 * @param		string $to newname
 * @returns		bool
 * @since		1.4.2
 */ 
function wpc_rename($from, $to){
	return rename($from, $to);
}

/**
 * Fetch the connection key for the WPC plugin to add the website in WPC panel
 *
 * @param		bool $force Whether to generate a new key forcefully or fetch the prev generated key
 * @returns		string $conn_key
 * @since		1.0
 */
function wpc_get_connection_key($force = ''){

	$conn_key = wpc_get_option('wpcentral_auth_key');

	if(empty($conn_key) || (!empty($conn_key) && strlen($conn_key) != 128) || $force){
		$conn_key = wpc_srandstr(128);
		update_option('wpcentral_auth_key', $conn_key, true);
	}

	return $conn_key;
}

/**
 * Generate the connection key on plugin activation
 *
 * @returns		string $conn_key
 * @since		1.0
 */
function wpc_activation_hook(){
	return wpc_get_connection_key(1);
}

/**
 * Generate the connection key when the plugin loads after its activation.
 *
 * @returns		string $conn_key
 * @since		1.2
 */
function wpc_load_plugin(){
	
	// Show key details in Plugins data
	add_filter('plugin_row_meta', 'wpc_add_connection_link', 10, 2);
	
	wpc_update_check();
	
	// Set the key if not already set
	wpc_get_connection_key();
	
	// All non-privilige actions including those called by panel.wpcentral.co
	// We must check with wpc_authorize in these actions
	add_action('wp_ajax_nopriv_my_wpc_actions', 'my_wpc_actions_init');
	
	// If we are to login the user
	// We must authorize with the wpc_signonkey_authorization() function in these actions
	add_action('wp_ajax_nopriv_wpcentral_login_and_act', 'wpcentral_login_and_act');
	
	// Are you the Admin ?
	if(current_user_can('administrator')){

		// If we are to login the user
		// We must authorize with the wpc_signonkey_authorization() function in these actions
		add_action('wp_ajax_wpcentral_login_and_act', 'wpcentral_login_and_act');
	
		if(wpc_is_display_notice()){
			add_action('admin_notices', 'wpc_admin_notice');
		}
		
		add_action('wp_ajax_wpc_dismissnotice', 'wpc_dismiss_notice');
		
		// This adds the left menu in WordPress Admin page
		add_action('admin_menu', 'wpc_admin_menu', 5);
		
		add_action('wp_ajax_my_wpc_fetch_authkey', 'wpc_fetch_authkey');
	
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
	
	return true;
}

// Shows the admin menu of Wpcentral
function wpc_admin_menu() {
	
	$capability = 'activate_plugins';// TODO : Capability for accessing this page

	// Add the menu page
	add_menu_page(__('wpCentral Manager'), __('wpCentral'), $capability, 'wpcentral', 'wpcentral_page_handler', plugins_url('', __FILE__).'/images/wpc_icon_light_19.png');
}

// The wpCentral Settings Page
function wpcentral_page_handler(){
	 
	if(!current_user_can('manage_options')){
		wp_die('Sorry, but you do not have permissions to change settings.');
	}
	
	include_once(dirname(__FILE__).'/settings.php');
}

/**
 * Fetch the connection key and the site details when the plugin is installed and activated from wpcentral panel.
 *
 * @since		1.2
 */
function wpc_fetch_authkey(){
    global $l, $error, $wp_config, $wpdb;
    
    //Fetch WP Configuration details
	$wp_config = wpc_fetch_wp_config();
    
    $wpc_authkey = wpc_get_connection_key();
    
    include_once('verify.php');
	wpc_verify($wpc_authkey);
}

/**
 * Deletes the connection key on plugin deactivation
 *
 * @returns		bool
 * @since		1.0
 */
function wpc_deactivation_hook(){
	delete_option('wpcentral_auth_key');
	delete_option('wpcentral_connected');
	delete_option('wpc_dismiss_notice_date');
	delete_option('wpcentral_promo_time');
	return true;
}

/**
 * Add plugin's metadata in the plugin list table
 *
 * @param		string $links
 * @param		string $slug plugin's slug value
 * @returns		array $links
 * @since		1.0
 */
function wpc_add_connection_link($links, $slug) {

	if(is_multisite() && is_network_admin()){
		return $links;
	}

	if ($slug !== WPCENTRAL_BASE) {
		return $links;
	}

	if(!current_user_can('activate_plugins')){
		return $links;
	}
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_style('wp-jquery-ui');
	wp_enqueue_style('wp-jquery-ui-dialog');

	$mscript = '<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			//View connection key script
			var wpc_conn_key_dialog = $("#wpc_connection_key_dialog");
			$("#wpc_connection_key").click(function(e) {
				e.preventDefault();
				wpc_conn_key_dialog.dialog({
					draggable: false,
					resizable: false,
					modal: true,
					width: "1070px",
					height: "auto",
					title: "wpCentral Connection Key",
					close: function() {
						$(this).dialog("destroy");
					}
				});
			});
		});
	</script>';
	
	$mdialog = '
	<div id="wpc_connection_key_dialog" style="display: none;">
		<p>Follow the steps here to connect your website to wpcentral dashboard:</p>
		<ol>
			<li>Copy the connection key below</li>
			<li>Log into your <a href="https://panel.wpcentral.co/" target="_blank">wpcentral</a> account</li>
			<li>Click on Add website to add your website to wpcentral.</li>
			<li>Enter this website\'s URL and paste the Connection key given below.</li>
			<li>You can also follow our guide for the same <a href="https://wpcentral.co/docs/getting-started/adding-website-in-wpcentral/" target="_blank">here</a>.</li>
		</ol>
		
		<p style="font-weight:bold;">Note: Contact wpCentral Team at support@wpcentral.co for any issues</p>

		<div style="text-align:center; font-weight:bold;"><p style="margin-bottom: 4px;margin-top: 20px;">wpCentral Connection Key</p></div>
		<div class="display_connection_key" style="padding: 10px;background-color: #fafafa;border: 1px solid black;border-radius: 10px;font-weight: bold;font-size: 14px;text-align: center;">'.wpc_get_connection_key().'</div>
	</div>';
	
	$new_links = array('doc' => '<a href="#" id="wpc_connection_key">View Connection Key</a>'.$mdialog.$mscript);

	$links = array_merge($links, $new_links);

	return $links;
}

/**
 * Deletes the connection key on plugin deactivation
 *
 * @returns		bool
 * @since		1.0
 */
function wpc_deactivate(){
	delete_option('wpcentral_auth_key');
	delete_option('wpcentral_connected');
	delete_option('wpc_dismiss_notice_date');
}

// Get the client IP
function _wpc_getip(){
	if(isset($_SERVER["REMOTE_ADDR"])){
		return $_SERVER["REMOTE_ADDR"];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
		return $_SERVER["HTTP_CLIENT_IP"];
	}
}

/**
 * Check for the authorization of the request using the auth key
 *
 * @returns		bool
 * @since		1.0
 */
function wpc_authorize(){
    global $l, $error;
	
	$return = array(); 
    
    $auth_key = wpc_optREQ('auth_key');
	if(empty($auth_key)){
		$return['error'] = 'Unauthorized Access!!';
		echo json_encode($return);
		die();
	}
	
	$verify_authkey = wpc_get_option('wpcentral_auth_key');
	if($auth_key != $verify_authkey){
		$return['error'] = $l['invalid_auth_key'];
		echo json_encode($return);
		die();
	}
	
	$allowed_ips = wpc_get_allowed_ips();
	$remote_ip = _wpc_getip();
	
	// We allow requests from allowed panels only
	if(!in_array($remote_ip, $allowed_ips)){
		$return['error'] = 'Unauthorized Access from an unknown IP '.$remote_ip.'. Allowed IPs - '.implode(',', $allowed_ips).'!!';
		echo json_encode($return);
		die();
	}
}

// Gets the list of allowed IPs
function wpc_get_allowed_ips(){
	
	$ips = get_option('wpcentral_allowed_ips');
	
	if(empty($ips)){
		update_option('wpcentral_allowed_ips', array(WPCENTRAL_PANEL_IP));
		$ips = get_option('wpcentral_allowed_ips');
	}
	
	foreach($ips as $k => $ip){
		if(empty($ip)){
			unset($ips[$k]);
		}
	}
	
	return $ips;
	
}

/**
 * Update the database if the website is added in WPCentral panel
 *
 * @returns		bool
 * @since		1.1
 */
function wpc_connectok(){
    $connected = wpc_get_option('wpcentral_connected');

	if(empty($connected)){
		update_option('wpcentral_connected', '1', true);
	}

	return true;
}

/**
 * Deactivate wpentral and delete the database entries if the website is removed in WPCentral panel
 *
 * @returns		bool
 * @since		1.1
 */
function wpc_self_deactivate(){
    
    wpc_deactivate_plugin(array(WPCENTRAL_BASE));
    wpc_deactivate();
    
    return true;
}

/**
 * Test if we are able to create a directory
 *
 * @returns		bool
 * @since		1.4
 */
function wpc_test_createdir($path){
	
	wpc_mkdir($path);
	
	if(wpc_is_dir($path)){
		return true;
	}
	
	return true;
}

/**
 * WPCentral action handler when we need to LOGIN and then redirect
 *
 * @since		1.0
 */
function wpcentral_login_and_act(){
	global $l, $error;
	
	// Authorize with a TEMP KEY or DIE !
	wpc_signonkey_authorization();
	
	// At this point we are authorized and we can login
	my_wpc_signon();
	
	//Execute the Request
	$wpc_act = wpc_optREQ('wpc_act');
	
	switch($wpc_act){
		
		// Edit or Preview Post
		case 'edit_post':
		case 'preview_post':
			wpc_post_redirect($wpc_act);
			break;
		
		// Default case to login
		default:
		case '':
			$redirect_to = user_admin_url();
			wp_safe_redirect($redirect_to);
			exit();
			break;
	}
	
}
	
/**
 * WPCentral action handler
 *
 * @since		1.0
 */
function my_wpc_actions_init(){
	global $l, $error, $wp_config;
	
	//Authorize
	wpc_authorize();
	
	//Fetch WP Configuration details
	$wp_config = wpc_fetch_wp_config();
	
	//Execute the Request
	$wpc_act = wpc_optREQ('wpc_act');
	
	switch($wpc_act){
		
		case 'verifyconnect':
			include_once('verify.php');
			wpc_verify();
			break;
		
		case 'wpcdeactivate':
			wpc_self_deactivate();
			break;
			
		case 'getsitedata':
			include_once('get_site_data.php');
			wpc_get_site_data();
			break;
			
		case 'siteactions':
			include_once('actions.php');
			wpc_site_actions();
			break;
			
		case 'fileactions':
			include_once('file_actions.php');
			wpc_file_actions();
			break;
			
		case 'connectok':
			wpc_connectok();
			break;
			
		case 'can_createdir':
			wpc_can_createdir();
			break;
			
		case 'wpcentral_version':
			wpc_fetch_version();
			break;
		
		case 'getsignonkey':
			wpc_getsignonkey();
			break;
	
		//The DEFAULT Page
		default:
			// Nothing to do
			break;
	}
}

/**
 * Directs to the edit/preview post page of the specified post
 *
 * @since		1.4.6
 */
function wpc_post_redirect($post_act){
    global $l, $error;
	
	$post_id = wpc_optREQ('post_id');
		
	if($post_act == 'edit_post'){
		$redirect_to = get_edit_post_link($post_id, '');
		
	}elseif($post_act == 'preview_post'){
		$redirect_to = get_preview_post_link($post_id);
	}
	
	wp_safe_redirect($redirect_to);

	exit();
}

/**
 * Check if we have the permission to create a directory on the user server
 *
 * @returns		bool
 * @since		1.4.1
 */
function wpc_can_createdir($path = ''){
	global $wp_config;
	
	if(empty($path)){
		$path = $wp_config['plugins_root_dir'].'/wp-central/'.wpc_optREQ('testpath');
	}
	
	if(wpc_is_dir($path)){
		$path = $wp_config['plugins_root_dir'].'/wp-central/'.wpc_srandstr(16);
	}
	
	wpc_mkdir($path);
	
	$resp = 0;
	if(wpc_is_dir($path)){
		$resp = 1;
	}
	
	@rmdir($path);
	
	if(isset($_GET['testpath'])){
		$return = array();
		$return['can_create'] = $resp;
		
		echo json_encode($return);
	}
	
	return $resp;
}

/**
 * Fetch the installed version of wpcentral plugin in the website
 *
 * @returns		string wpcentral version number
 * @since		1.4.3
 */
function wpc_fetch_version(){
	global $wp_config;
	
	$plugin_data = wpc_get_plugin_data($wp_config['plugins_root_dir'].'/wp-central/wpcentral.php');
	
	$wpc_version = $plugin_data['Version'];
	
	if(isset($_GET['callfetch'])){
		$return = array();
		$return['wpc_ver'] = $wpc_version;
		
		echo json_encode($return);
	}
	
	return $wpc_version;	
}

/**
 * Provides access to the website's admin panel
 *
 * @returns		bool
 * @since		1.0
 */
function my_wpc_signon(){
	
    global $l, $error;
	
	// Query the users
	$users_query = new WP_User_Query( array(
		'role' => 'administrator',
		'orderby' => 'ID',
		'number' => 1
	) );
	
	// Get the results and the row
	$results = $users_query->get_results();
	$tmp = current($results);
	
	$user_info = get_userdata($tmp->ID);
		
	// Automatic login //
	$username = $user_info->user_login;
	$user = get_user_by('login', $username );
	
	// Redirect URL //
	if (!is_wp_error($user)){
		wp_clear_auth_cookie();
		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);
	}
}

/**
 * Return the role of the current user.
 *
 * @since		1.1
 */
function wpc_get_curr_user_role(){
    return wp_get_current_user()->roles[0];
}

/**
 * Dismiss wpCentral notice and save the dismiss date.
 *
 * @since		1.4.3
 */
function wpc_dismiss_notice(){
	
	$wpc_dismissable_notice_date = get_option('wpc_dismiss_notice_date');
	
	if(empty($wpc_dismissable_notice_date)){
		add_option('wpc_dismiss_notice_date', date('Y-m-d'));
	}else{
		update_option('wpc_dismiss_notice_date', date('Y-m-d'));
	}
}

/**
 * Display wpCentral notice on the basis of last dismiss date. When user manually dismisses the notice, it remains for 1 month
 *
 * @since		1.4.3
 */
function wpc_is_display_notice(){
	
	$wpc_dismissable_notice_date = get_option('wpc_dismiss_notice_date');
	
	if(empty($wpc_dismissable_notice_date)){
		return true;
	}
	
	$wpc_dismissable_notice_date2 = new DateTime($wpc_dismissable_notice_date);
	$current_date = new DateTime(date('Y-m-d'));
	$date_diff_month = $wpc_dismissable_notice_date2->diff($current_date);
	
	//Do not display notice again for a month
	if($date_diff_month->m < 1){
		return false;
	}
	
	return true;
}
/**
 * Display wpCentral notice in dashboard
 *
 * @since		1.0
 */
function wpc_admin_notice(){
    
    $role = wpc_get_curr_user_role();
    
    if($role == 'administrator' && !wpc_get_option('wpcentral_connected')){

	echo '
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(document).on("click", ".my-wpc-dismiss-notice .notice-dismiss", function(){
				var data = {
					action: "wpc_dismissnotice"
				};
				var site_url = jQuery(".wpc_siteurl").text()+"/wp-admin/admin-ajax.php";
				jQuery.post(site_url, data, function(response){
				});
			});
		});
	</script>
	<style>
		.wpc_sub_head{
			font-size: 20px !important;
			color: #676a6c !important;
			font-weight: bold !important;
			font-family: OpenSans, sans-serif !important;
			text-align: center;
		}
		
		.wpc_main_head{
			font-size: 20px;
			color: #676a6c;
			font-family: OpenSans, sans-serif;
			text-align: center;
		}
		
		.wpc_flat-butt{
			text-decoration: none !important;
			display: inline-block !important;
			border-radius: 3px !important;
			font-family: OpenSans, sans-serif !important;
			font-size: 15px !important;
			padding: 10px !important;
			text-align: center !important;
			border: 0 !important;
			background: #00a0d2 !important;
			color: #FFF !important;
			cursor: pointer !important;
			outline: 0 !important;
			font-weight: 400 !important;
			line-height: 1.42857 !important;
			text-align: center !important;
			white-space: nowrap !important;
			vertical-align: middle !important;
			-moz-user-select: none !important;
			border: 1px solid transparent !important;
		}
		
		.wpc_flat-butt:hover{
			background: #46B8DA !important;
		}
		
		.wpc_head{
			color: #676a6c;
			font-family: OpenSans, sans-serif;
			font-size: 15px;
		}
		
		.wpc_notice{
			background-color: #fff;
			height: 335px;
			align-items: left;
			display: flex;
			text-align: left;
			border-radius: 6px;
			box-shadow: 0px 10px 12px -7px #777;
			border-left-color: unset !important;
			border-left: unset !important;
		}
		
		.wpc_row {
			margin-right: -15px;
			margin-left: -15px;
		}

		.wpc_col-sm-3, .wpc_col-sm-8 {
			position: relative;
			min-height: 1px;
			padding-right: 15px;
			padding-left: 15px;
		}

		.wpc_col-sm-3, .wpc_col-sm-8 {
			float: left;
		}
		.wpc_col-sm-8 {
			width: 66.66666667%;
		}
		.wpc_col-sm-3 {
			width: 25%;
		}
	</style>
        
    	<div class="updated wpc_notice notice notice-success my-wpc-dismiss-notice is-dismissible">
    		<div style="width:100%; padding-top:10px;">
    			<div class="wpc_main_head">
    				<img src="https://wpcentral.co/images/icon_dark.png" style="height:40px; padding:5px 0;">
    				<img src="https://wpcentral.co/images/logo_dark.png" alt="wpcentral" title="wpcentral" style="height:40px; padding:5px 0;">
    			</div><br />
    			<div class="wpc_row">
    			    <div class="wpc_head wpc_col-sm-3"><b>Website URL:</b></div>
    			    <div class="wpc_col-sm-8 wpc_siteurl">'.get_option('siteurl').'</div>
    			</div><br /><br />
    			<div class="wpc_row">
    			    <div class="wpc_head wpc_col-sm-3"><b>wpCentral Connection Key:</b></div>
    			    <div class="wpc_col-sm-8 display_connection_key" style="word-wrap:break-word;">'.get_option('wpcentral_auth_key').'</div>
    			</div><br /><br /><br />
    			
    			<div class="wpc_sub_head"><br /><b>Click <a href="https://panel.wpcentral.co/index.php?act=wpc_addsite&siteurl='.get_option('siteurl').'&conn_key='.get_option('wpcentral_auth_key').'" target="_blank">here</a> to connect your website directly to wpCentral panel.</b></div>
    			
    			<div style="text-align: center;"><br />
				<p style="display:inline-block; padding:0 20px;"><a class="wpc_flat-butt wpc_sub_head" href="https://panel.wpcentral.co/index.php?act=wpc_addsite&siteurl='.get_option('siteurl').'&conn_key='.get_option('wpcentral_auth_key').'" target="_blank">1-Click Connect</a></p>
				
    				<p style="display:inline-block; padding:0 20px;"><a class="wpc_flat-butt wpc_sub_head" href="https://wpcentral.co" target="_blank">Visit Website</a></p>
    				
    				<p style="display:inline-block; padding:0 20px;"><a class="wpc_flat-butt wpc_sub_head" href="https://wpcentral.co/docs/getting-started/adding-website-in-wpcentral/" target="_blank">Manual Connection Guide</a></p>
    				
    				<p style="display:inline-block; padding:0 20px;"><a class="wpc_flat-butt wpc_sub_head" href="https://wpcentral.co/docs/getting-started/1-click-website-connection/" target="_blank">1-Click Connection Guide</a></p>
    			</div><br />
			
			<div class="wpc_head" style="text-align:center;">
    				<b>Note:</b> Contact wpCentral Team for any issues at <a href="mailto:support@wpcentral.co" style="text-decoration:none;">support@wpcentral.co</a>
    			</div><br />
    		</div>
    	</div>';
    }
}

/* Removes a Directory Recursively
 *
 * @param        string $path The path of the folder to be removed
 * @return       boolean
 * @since		1.4.3
*/
function wpc_rmdir_recursive($path){
	if(!wpc_is_safe_file($path)) return false;
	
	$path = (substr($path, -1) == '/' || substr($path, -1) == '\\' ? $path : $path.'/');
	
	wpc_resetfilelist();
	
	$files = wpc_filelist($path, 1, 0, 'all');
	$files = (!is_array($files) ? array() : $files);
	
	//First delete the files only
	foreach($files as $k => $v){
		if(wpc_is_safe_file($k)){
			@chmod($k, 0777);
		}
		if(file_exists($k) && is_file($k) && @filetype($k) == "file"){
			@unlink($k);
		}
	}
	
	@clearstatcache();
	
	$folders = wpc_filelist($path, 1, 1, 'all');
	$folders = (!is_array($folders) ? array() : $folders);
	@krsort($folders);

	//Now Delete the FOLDERS
	foreach($folders as $k => $v){
		if(wpc_is_safe_file($k)){
			@chmod($k, 0777);
		}
		if(is_dir($k)){
			@rmdir($k);
		}
	}
	
	@rmdir($path);
	
	@clearstatcache();
	
	return true;
}

/* A Function that reset lists files
 *
 * @since		1.4.3
*/
function wpc_resetfilelist(){
global $directorylist;
	$directorylist = array();
}

/* Ratings Notice HTML
 *
 * @since		1.4.4
*/
function wpc_show_promo(){	
	global $wpc_promo_opts;
	$opts = $wpc_promo_opts;
	
	echo '
	<style>
		.wpc_promo_button {
		background-color: #4CAF50; /* Green */
		border: none;
		color: white;
		padding: 6px 10px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 13px;
		margin: 4px 2px;
		-webkit-transition-duration: 0.4s; /* Safari */
		transition-duration: 0.4s;
		cursor: pointer;
	}

	.wpc_promo_button:focus{
		border: none;
		color: white;
	}

	.wpc_promo_button1 {
		color: white;
		background-color: #4CAF50;
		border:3px solid #4CAF50;
	}

	.wpc_promo_button1:hover {
		box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
		color: white;
		border:3px solid #4CAF50;
	}

	.wpc_promo_button2 {
		color: white;
		background-color: #0085ba;
	}

	.wpc_promo_button2:hover {
		box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
		color: white;
	}

	.wpc_promo_button3 {
		color: white;
		background-color: #365899;
	}

	.wpc_promo_button3:hover {
		box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
		color: white;
	}

	.wpc_promo_button4 {
		color: white;
		background-color: rgb(66, 184, 221);
	}

	.wpc_promo_button4:hover {
		box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
		color: white;
	}

	.wpc_promo-close{
		float:right;
		text-decoration:none;
		margin: 5px 10px 0px 0px;
	}

	.wpc_promo-close:hover{
		color: red;
	}
	</style>	

	<script>
	jQuery(document).ready( function() {
		(function($) {
			$("#wpc_promo .wpc_promo-close").click(function(){
				var data;
				
				// Hide it
				$("#wpc_promo").hide();
				
				// Save this preference
				$.post("'.admin_url('?wpcentral_promo=0').'", data, function(response) {
					//alert(response);
				});
			});
		})(jQuery);
	});
	</script>

	<div class="notice notice-success" id="wpc_promo" style="min-height:90px">
		<a class="wpc_promo-close" href="javascript:" aria-label="Dismiss this Notice">
			<span class="dashicons dashicons-dismiss"></span> Dismiss
		</a>';
		
		if(!empty($opts['image'])){
			echo '<a href="'.$opts['website'].'"><img src="'.$opts['image'].'" style="float:left; margin:10px 20px 10px 10px" width="67" /></a>';
		}
		
		echo '
		<p style="font-size:13px">We are glad you like <a href="'.$opts['website'].'"><b>wpCentral</b></a> and have been using it since the past few days. It is time to take the next step !</p>
		<p>
			'.(empty($opts['rating']) ? '' : '<a class="wpc_promo_button wpc_promo_button2" target="_blank" href="'.$opts['rating'].'">Rate it 5★\'s</a>').'
			'.(empty($opts['facebook']) ? '' : '<a class="wpc_promo_button wpc_promo_button3" target="_blank" href="'.$opts['facebook'].'"><span class="dashicons dashicons-thumbs-up"></span> Facebook</a>').'
			'.(empty($opts['twitter']) ? '' : '<a class="wpc_promo_button wpc_promo_button4" target="_blank" href="'.$opts['twitter'].'"><span class="dashicons dashicons-twitter"></span> Tweet</a>').'
			'.(empty($opts['website']) ? '' : '<a class="wpc_promo_button wpc_promo_button4" target="_blank" href="'.$opts['website'].'">Visit our website</a>').'
			'.(empty($opts['support']) ? '' : '<a class="wpc_promo_button wpc_promo_button4" target="_blank" href="'.$opts['support'].'">wpCentral Support</a>').'
		</p>
	</div>';
}

/* Show Ratings Notice
 *
 * @since		1.4.4
*/
function wpc_maybe_promo($opts){
	
	global $wpc_promo_opts;
	
	// There must be an interval after which the notice will appear again
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show a promo	
	$opt_name = 'wpcentral_promo_time';
	$promo_time = wpc_get_option($opt_name);
	
	//Check if it is connected to wpCentral panel
	$connected = wpc_get_option('wpcentral_connected');
	
	//Display only if the website is connected to wpCentral panel and a day has passed since the connection or 3 months have passed since the last dismissal
	if(!empty($connected)){
		if(empty($promo_time)){
			update_option($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
			$promo_time = wpc_get_option($opt_name);
		}
		
		// Is there interval elapsed
		if(time() > $promo_time){
			$wpc_promo_opts = $opts;
			add_action('admin_notices', 'wpc_show_promo');
		}
	}
	
	// Are we to disable the promo
	if(isset($_GET['wpcentral_promo']) && (int)$_GET['wpcentral_promo'] == 0){
		update_option($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
} 

// Update check
function wpc_update_check(){
	
	$current_version = get_option('wpcentral_version');
	$version = (int) str_replace('.', '', $current_version);
	
	if($current_version == WPCENTRAL_VERSION){
		return true;
	}
	
	$connected = wpc_get_option('wpcentral_connected');
	if($version < 151 && empty($connected)){	
		$conn_key = wpc_get_connection_key(1);
	}

	// Save the new Version
	update_option('wpcentral_version', WPCENTRAL_VERSION);
}

function wpc_reset_conn_key(){
	$return['conn_key'] = wpc_get_connection_key(1);
	delete_option('wpcentral_connected');
	return $return;
}


/**
 * Generate validation key to allow user to sign in 
 *
 * @returns		bool
 * @since		1.1
 */
function wpc_getsignonkey(){

	$signon_key = wpc_srandstr(64);
	update_option('wpcentral_signonkey', $signon_key);
	update_option('wpcentral_signonkey_time', time());
	
	if(isset($_GET['get_wpcsignonkey'])){
		$return = array();
		$return['wpc_signonkey'] = $signon_key;
		echo json_encode($return);
	}

	return $signon_key;
}

/**
 * Check for the authorization of the request using the temporary validation key
 *
 * @returns		bool
 * @since		1.0
 */
function wpc_signonkey_authorization(){
	global $l, $error;
	
	$return = array(); 
    
	$validate_key = wpc_optREQ('wpc_signonkey');
	if(empty($validate_key)){
		$return['error'] = 'Unauthorized Access!!';
		echo json_encode($return);
		die();
	}
	
	$verify_authkey = wpc_get_option('wpcentral_signonkey');
	if($validate_key !== $verify_authkey){
		$return['error'] = $l['invalid_auth_key'];
		echo json_encode($return);
		die();
	}
	
	// Is the key within 5 minutes of creation ?
	if((time() - wpc_get_option('wpcentral_signonkey_time')) > 300){
		$return['error'] = 'The Authorization Key has expired';
		echo json_encode($return);
		die();
	}
	
	delete_option('wpcentral_signonkey');
	delete_option('wpcentral_signonkey_time');
	
}