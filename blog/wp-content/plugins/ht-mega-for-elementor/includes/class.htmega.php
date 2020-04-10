<?php

class HTMega_Addons_Elementor {
    
    const MINIMUM_ELEMENTOR_VERSION = '2.5.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        if ( ! function_exists('is_plugin_active') ){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        // Register Plugin Active Hook
        register_activation_hook( HTMEGA_ADDONS_PL_ROOT, [ $this, 'plugin_activate_hook'] );
    }

    public function i18n() {
        load_plugin_textdomain( 'htmega-addons', false, dirname( plugin_basename( HTMEGA_ADDONS_PL_ROOT ) ) . '/languages/' );
    }

    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }
        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Plugins Required File
        $this->includes();

        // After Active Plugin then redirect to setting page
        $this->plugin_redirect_option_page();

        // Plugins Setting Page
        add_filter('plugin_action_links_'.HTMEGA_ADDONS_PLUGIN_BASE, [ $this, 'plugins_setting_links' ] );
        
    }

    public function is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }

    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $elementor = 'elementor/elementor.php';
        if( $this->is_plugins_active( $elementor ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) { return; }

            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );

            $message = '<p>' . __( 'HTMEGA Addons not working because you need to activate the Elementor plugin.', 'htmega-addons' ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Elementor Activate Now', 'htmega-addons' ) ) . '</p>';
        } else {
            if ( ! current_user_can( 'install_plugins' ) ) { return; }

            $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

            $message = '<p>' . __( 'HTMEGA Addons not working because you need to install the Elementor plugin', 'htmega-addons' ) . '</p>';

            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Elementor Install Now', 'htmega-addons' ) ) . '</p>';
        }
        echo '<div class="error"><p>' . $message . '</p></div>';
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            __( '"%1$s" requires "%2$s" version %3$s or greater.', 'htmega-addons' ),
            '<strong>' . __( 'HTMega Addons', 'htmega-addons' ) . '</strong>',
            '<strong>' . __( 'Elementor', 'htmega-addons' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            __( '"%1$s" requires "%2$s" version %3$s or greater.', 'htmega-addons' ),
            '<strong>' . __( 'HTMega Addons', 'htmega-addons' ) . '</strong>',
            '<strong>' . __( 'PHP', 'htmega-addons' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    // Add settings link on plugin page.
    public function plugins_setting_links( $links ) {
        $htmega_settings_link = '<a href="admin.php?page=htmega_addons_options">'.esc_html__( 'Settings', 'htmega-addons' ).'</a>';
        array_unshift( $links, $htmega_settings_link );
        if( !is_plugin_active('htmega-pro/htmega_pro.php') ){
            $links['htmegago_pro'] = sprintf('<a href="https://hasthemes.com/plugins/ht-mega-pro/" target="_blank" style="color: #39b54a; font-weight: bold;">' . esc_html__('Go Pro','htmega-addons') . '</a>');
        }
        return $links; 
    }

    /* 
    * Plugins After Install
    * Redirect Setting page
    */
    public function plugin_activate_hook() {
        add_option('htmega_do_activation_redirect', true);
    }
    public function plugin_redirect_option_page() {
        if ( get_option( 'htmega_do_activation_redirect', false ) ) {
            delete_option('htmega_do_activation_redirect');
            if( !isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url("admin.php?page=htmega_addons_options") );
            }
        }
    }

    // Include files
    public function includes() {
        require_once ( HTMEGA_ADDONS_PL_PATH.'includes/helper-function.php' );
        require_once ( HTMEGA_ADDONS_PL_PATH.'admin/admin-init.php' );
        require_once ( HTMEGA_ADDONS_PL_PATH.'includes/init.php' );
        require_once ( HTMEGA_ADDONS_PL_PATH . 'includes/widgets_control.php' );
        require_once ( HTMEGA_ADDONS_PL_PATH.'includes/class.htmega-icon-manager.php' );

        // Extension Assest Management
        require_once( HTMEGA_ADDONS_PL_PATH.'extensions/class.enqueue_scripts.php' );

        // HT Builder
        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-builder/init.php' );
        }

        // WC Sales Notification
        if( htmega_get_option( 'salenotification', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                if( htmega_get_option( 'notification_content_type', 'htmegawcsales_setting_tabs', 'actual' ) == 'fakes' ){
                    require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/wc-sales-notification/classes/class.sale_notification_fake.php' );
                }else{
                    require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/wc-sales-notification/classes/class.sale_notification.php' );
                }
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/wc-sales-notification/classes/class.sale_notification.php' );
            }
        }

        // HT Menu
        if( htmega_get_option( 'megamenubuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                require_once( HTMEGA_ADDONS_PL_PATH_PRO.'extensions/ht-menu/classes/class.mega-menu.php' );
            }else{
                require_once( HTMEGA_ADDONS_PL_PATH.'extensions/ht-menu/classes/class.mega-menu.php' );
            }
        }

        
    }

}

HTMega_Addons_Elementor::instance();