<?php
/*
Plugin Name: Post Grid by PickPlugins
Plugin URI: https://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/
Description: Awesome post grid for query post from any post type and display on grid.
Version: 2.0.44
Author: PickPlugins
Author URI: https://www.pickplugins.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

if( !class_exists( 'PostGrid' )){
    class PostGrid{


        public function __construct(){

            define('post_grid_plugin_url', plugins_url('/', __FILE__));
            define('post_grid_plugin_dir', plugin_dir_path(__FILE__));
            define('post_grid_plugin_basename', plugin_basename(__FILE__));

            define('post_grid_wp_url', 'https://wordpress.org/plugins/post-grid/');
            define('post_grid_pro_url', 'https://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/');
            define('post_grid_qa_url', 'https://www.pickplugins.com/forum/');
            define('post_grid_plugin_name', 'Post Grid');
            define('post_grid_version', '2.0.44');


            include('includes/classes/class-post-grid-support.php');
            include('includes/data-update/class-post-grid-data-update.php');
            include('includes/functions/functions-post-grid-settings.php');
            include('includes/class-functions.php');
            include('includes/class-shortcodes.php');
            include('includes/class-settings.php');
            include('includes/class-post-grid-meta-box.php');
            include('includes/class-settings-tabs.php');
            include('includes/functions/functions-post-grid-meta-box.php');
            include('includes/functions/functions-post-meta-box.php');
            include('includes/post-grid-meta-box.php');
            include('includes/functions/functions-post-grid.php');
            include('includes/post-meta-settings.php');
            include('includes/functions.php');
            include('includes/shortcodes/shortcode-current_user_id.php');


            add_action('wp_enqueue_scripts', array($this, 'post_grid_scripts_front'));
            add_action('admin_enqueue_scripts', array($this, 'post_grid_scripts_admin'));
            add_action('admin_enqueue_scripts', 'wp_enqueue_media');

            add_action('plugins_loaded', array($this, 'textdomain'));

            register_activation_hook(__FILE__, array($this, 'post_grid_install'));
            register_deactivation_hook(__FILE__, array($this, 'post_grid_deactivation'));


        }


        public function textdomain(){

            $locale = apply_filters('plugin_locale', get_locale(), 'post-grid');
            load_textdomain('post-grid', WP_LANG_DIR . '/post-grid/post-grid-' . $locale . '.mo');

            load_plugin_textdomain('post-grid', false, plugin_basename(dirname(__FILE__)) . '/languages/');

        }

        public function post_grid_install(){


            $class_post_grid_functions = new class_post_grid_functions();


            $post_grid_layout_content = get_option('post_grid_layout_content');
            if (empty($post_grid_layout_content)){
                $layout_content_list = $class_post_grid_functions->layout_content_list();
                update_option('post_grid_layout_content', $layout_content_list);
            }



            $post_grid_info = get_option('post_grid_info');
            $post_grid_info['current_version'] = post_grid_version;
            $post_grid_info['last_version'] = isset($post_grid_info['last_version']) ? $post_grid_info['last_version'] : '2.0.30';
            $post_grid_info['data_update_status'] = isset($post_grid_info['data_update_status']) ? $post_grid_info['data_update_status'] : 'pending';
            update_option('post_grid_info', $post_grid_info);


            /*
             * Custom action hook for plugin activation.
             * Action hook: post_grid_activation
             * */
            do_action('post_grid_activation');

        }

        public function post_grid_uninstall(){

            /*
             * Custom action hook for plugin uninstall/delete.
             * Action hook: post_grid_uninstall
             * */
            do_action('post_grid_uninstall');
        }

        public function post_grid_deactivation(){

            /*
             * Custom action hook for plugin deactivation.
             * Action hook: post_grid_deactivation
             * */
            do_action('post_grid_deactivation');
        }


        public function post_grid_scripts_front(){
            wp_enqueue_script('jquery');

            // Register Scripts & JS
            wp_register_script('post_grid_scripts', plugins_url('/assets/frontend/js/scripts.js', __FILE__), array('jquery'));
            wp_register_script('masonry.js', plugins_url('/assets/frontend/js/masonry.pkgd.min.js', __FILE__), array('jquery'));
            wp_register_script('imagesloaded.js', plugins_url('/assets/frontend/js/imagesloaded.pkgd.js', __FILE__), array('jquery'));

            // Register CSS & Styles
            wp_register_style(  'post-grid-style', post_grid_plugin_url . 'assets/frontend/css/style.css');
            wp_register_style(  'post-grid-skin', post_grid_plugin_url . 'assets/global/css/style.skins.css');
            //wp_register_style(  'font-awesome-5', post_grid_plugin_url . 'assets/global/css/font-awesome-5.css');


        }


        public function post_grid_scripts_admin(){

            $screen = get_current_screen();

            //echo '<pre>'.var_export($screen, true).'</pre>';

            //
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_script('post_grid_admin_js', plugins_url('assets/admin/js/scripts-new.js', __FILE__), array('jquery'));
            wp_localize_script('post_grid_admin_js', 'post_grid_ajax', array('post_grid_ajaxurl' => admin_url('admin-ajax.php')));

            wp_enqueue_script('post_grid_color_picker', plugins_url('/assets/admin/js/color-picker.js', __FILE__), array('wp-color-picker'), false, true);

            wp_register_script('settings-tabs', plugins_url('assets/admin/js/settings-tabs.js', __FILE__), array('jquery'));
            wp_register_script('select2', plugins_url('assets/admin/js/select2.full.js', __FILE__), array('jquery'));


            wp_enqueue_style(   'wp-color-picker');
            wp_enqueue_script('select2');
            // Register Style

            wp_register_style(  'select2', post_grid_plugin_url . 'assets/admin/css/select2.min.css');
            wp_register_style(  'settings-tabs', post_grid_plugin_url . 'assets/admin/css/settings-tabs.css');
            wp_register_style(  'post-grid-meta-box', post_grid_plugin_url . 'assets/admin/css/post-grid-meta-box.css');
            wp_register_style(  'codemirror', post_grid_plugin_url . 'assets/admin/css/codemirror.css');
            wp_register_style(  'simplescrollbars', post_grid_plugin_url . 'assets/admin/css/simplescrollbars.css');
            wp_register_style(  'font-awesome-5', post_grid_plugin_url . 'assets/global/css/font-awesome-5.css');



            wp_enqueue_style(   'post-skin', post_grid_plugin_url . 'assets/admin/css/post-skin.css');
            wp_enqueue_style(   'post_grid_admin_style', post_grid_plugin_url . 'assets/admin/css/style-new.css');

            //wp_register_style(  'font-awesome-5', post_grid_plugin_url . 'assets/global/css/fontawesome.min.css');



            if ($screen->id == 'post_grid'){

                wp_enqueue_script('post-grid-meta-box', plugins_url('assets/admin/js/post-grid-meta-box.js', __FILE__), array('jquery'));



                wp_enqueue_script('codemirror', plugins_url('assets/admin/js/codemirror.js', __FILE__), array('jquery'));
                wp_enqueue_script('simplescrollbars', plugins_url('assets/admin/js/simplescrollbars.js', __FILE__), array('jquery'));
                wp_enqueue_script('css', plugins_url('assets/admin/js/css.js', __FILE__), array('jquery'));
                wp_enqueue_script('javascript', plugins_url('assets/admin/js/javascript.js', __FILE__), array('jquery'));


                wp_enqueue_style('style.skins', post_grid_plugin_url . 'assets/global/css/style.skins.css');
                //wp_enqueue_style('style.animate', post_grid_plugin_url . 'assets/global/css/animate.css');



                wp_enqueue_style('font-awesome-5');
                wp_enqueue_style('post-grid-meta-box');
                wp_enqueue_style('codemirror');
                wp_enqueue_style('simplescrollbars');

                wp_enqueue_style('select2');
                wp_enqueue_script('select2');

                wp_enqueue_style('settings-tabs');
                wp_enqueue_script('settings-tabs');

            }


            if ($screen->id == 'post_grid_page_layout_editor'){
                wp_enqueue_style('font-awesome-5');



                wp_enqueue_style('settings-tabs');
                wp_enqueue_script('settings-tabs');
            }



        }


    }
}
new PostGrid();