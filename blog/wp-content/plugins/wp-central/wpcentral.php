<?php
/*
Plugin Name: wpCentral
Plugin URI: https://wpcentral.co
Description: wpCentral provides a centralized area where you can manage all your WordPress websites singularly, unitedly as well as efficiently.
Version: 1.5.3
Author: Softaculous Ltd.
Author URI: https://wpcentral.co
License: LGPL v2.1
License URI: https://www.gnu.org/licenses/old-licenses/lgpl-2.1.en.html
Text Domain: wpCentral
*/

/*
 * This file belongs to the wpcentral plugin.
 *
 * (c) wpCentral <sales@wpcentral.co>
 *
 * You can view the LICENSE file that was distributed with this source code
 * for copywright and license information.
 */

if (!defined('ABSPATH')){
    exit;
}

define('WPCENTRAL_VERSION', '1.5.3');
define('WPCENTRAL_BASE', 'wp-central/wpcentral.php');
define('WPCENTRAL_PANEL', 'panel.wpcentral.co');
define('WPCENTRAL_PANEL_IP', '192.200.108.100');
define('WPCENTRAL_WWW_URL', 'https://wpcentral.co/');

include_once(dirname(__FILE__).'/wpc_functions.php');

// WPC Activation Hook
register_activation_hook(__FILE__, 'wpc_activation_hook');

// WPC De-activation Hook
register_deactivation_hook(__FILE__, 'wpc_deactivation_hook');

add_action('plugins_loaded', 'wpc_load_plugin');
