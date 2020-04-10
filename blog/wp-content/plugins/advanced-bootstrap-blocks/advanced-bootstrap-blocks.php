<?php
/**
 * Plugin Name: Advanced Bootstrap Blocks
 * Plugin URI: https://www.wordpress.org/plugins/advanced-bootstrap-blocks/
 * Description: Flexible Bootstrap 4 blocks for building custom page layouts in the WordPress editor.
 * Author: helpful.dev
 * Author URI: https://helpful.dev/
 * Version: 1.2.6
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package advanced-bootstrap-blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
