<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTMega_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'htmega_enqueue_admin_scripts' ) );
        $this->HTMega_Admin_Settings_page();
    }

    /*
    *  Setting Page
    */
    public function HTMega_Admin_Settings_page() {
        require_once('include/class.settings-api.php');
        require_once('include/template-library.php');
        if( is_plugin_active('htmega-pro/htmega_pro.php') ){
            require_once HTMEGA_ADDONS_PL_PATH_PRO.'includes/admin/admin-setting.php';
        }else{
            require_once( 'include/admin-setting.php' );
        }

        // HT Builder
        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/ht-builder/admin/setting.php' );
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-builder/admin/setting.php' );
            }
        }

        // Sale Notification
        if( htmega_get_option( 'salenotification', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/wc-sales-notification/admin/setting.php' );
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/wc-sales-notification/admin/setting.php' );
            }
        }

        // HT Mega Menu
        if( htmega_get_option( 'megamenubuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-menu/admin/setting.php' );
        }

    }

    /*
    *   Enqueue admin scripts
    */
    public function htmega_enqueue_admin_scripts(){

        // wp core styles
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_style( 'htmega-admin', HTMEGA_ADDONS_PL_URL . 'admin/assets/css/htmega_admin.css', false, HTMEGA_VERSION );
        
        // wp core scripts
        wp_enqueue_script( 'jquery-ui-dialog' );

    }

}

new HTMega_Admin_Setting();