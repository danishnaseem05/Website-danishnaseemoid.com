<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMega_Template_Library{

    const TRANSIENT_KEY = 'htmega_template_info';
    public static $buylink = null;

    public static $endpoint     = HTMEGA_ADDONS_PL_URL.'admin/json/layoutinfofree.json';
    public static $templateapi  = 'https://api.hasthemes.com/api/htmega/v1/layouts-free/%s.json';

    public static $api_args = [];

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        self::$buylink = isset($this->get_templates_info()['pro_link'][0]['url']) ? $this->get_templates_info()['pro_link'][0]['url'] : '#';
        if ( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'admin_menu' ], 225 );
            add_action( 'wp_ajax_htmega_ajax_request', [ $this, 'templates_ajax_request' ] );
            add_action( 'wp_ajax_nopriv_htmega_ajax_request', [ $this, 'templates_ajax_request' ] );

            add_action( 'wp_ajax_htmega_ajax_get_required_plugin', [ $this, 'ajax_plugin_data' ] );
            add_action( 'wp_ajax_htmega_ajax_plugin_activation', [ $this, 'ajax_plugin_activation' ] );
            add_action( 'wp_ajax_htmega_ajax_theme_activation', [ $this, 'ajax_theme_activation' ] );
        }
        add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );

        self::$api_args = [
            'plugin_version' => HTMEGA_VERSION,
            'url'            => home_url(),
        ];

    }

    // Setter Endpoint
    function set_api_endpoint( $endpoint ){
        self::$endpoint = $endpoint;
    }
    
    // Setter Template API
    function set_api_templateapi( $templateapi ){
        self::$templateapi = $templateapi;
    }

    // Plugins Library Register
    function admin_menu() {
        add_submenu_page(
            'htmega_addons_option_page', 
            esc_html__( 'Templates Library', 'htmega-addons' ),
            esc_html__( 'Templates Library', 'htmega-addons' ), 
            'manage_options', 
            'htmega_addons_templates_library', 
            [ $this, 'library_render_html' ] 
        );
    }

    function library_render_html(){
        require_once HTMEGA_ADDONS_PL_PATH . 'admin/include/templates_list.php';
    }

    // Get Buy Now link
    function get_pro_link(){
        return self::$buylink;
    }

    public static function request_remote_templates_info( $force_update ) {
        global $wp_version;
        $body_args = apply_filters( 'htmegatemplates/api/get_templates/body_args', self::$api_args );
        $request = wp_remote_get(
            self::$endpoint,
            [
                'timeout'    => $force_update ? 25 : 10,
                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
                'body'       => $body_args,
                'sslverify'  => false,
            ]
        );
        $response = json_decode( wp_remote_retrieve_body( $request ), true );
        return $response;
    }

    /**
     * Retrieve template library and save as a transient.
     */
    public static function set_templates_info( $force_update = false ) {
        $transient = get_transient( self::TRANSIENT_KEY );

        if ( ! $transient || $force_update ) {
            $info = self::request_remote_templates_info( $force_update );
            set_transient( self::TRANSIENT_KEY, $info, DAY_IN_SECONDS );
        }
    }

    /**
     * Get template info.
     */
    public function get_templates_info( $force_update = false ) {
        if ( ! get_transient( self::TRANSIENT_KEY ) || $force_update ) {
            self::set_templates_info( true );
        }
        return get_transient( self::TRANSIENT_KEY );
    }

    /**
     * Admin Scripts.
     */
    public function scripts( $hook ) {

        // wp core styles
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        // wp core scripts
        wp_enqueue_script( 'jquery-ui-dialog' );
        
        wp_enqueue_script(
            'htmega-admin',
            HTMEGA_ADDONS_PL_URL . 'admin/assets/js/admin.js',
            [ 'jquery'],
            true
        );
        
        if( $hook === 'htmega-addons_page_htmega_addons_templates_library' ){

            // wp core styles
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            // wp core scripts
            wp_enqueue_script( 'jquery-ui-dialog' );
            
            // CSS
            wp_enqueue_style(
                'htmega-selectric',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/css/selectric.css',
                NULL,
                HTMEGA_VERSION
            );
            
            wp_enqueue_style(
                'htmega-temlibray-style',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/css/tmp-style.css',
                NULL,
                HTMEGA_VERSION
            );

            // JS
            wp_enqueue_script(
                'htmega-modernizr',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/modernizr.custom.63321.js',
                [ 'jquery'],
                FALSE
            );
            wp_enqueue_script(
                'jquery-selectric',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/jquery.selectric.min.js',
                [ 'jquery'],
                HTMEGA_VERSION,
                TRUE
            );
            wp_enqueue_script(
                'jquery-ScrollMagic',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/ScrollMagic.min.js',
                [ 'jquery'],
                HTMEGA_VERSION,
                TRUE
            );
            wp_enqueue_script(
                'babel-min',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/babel.min.js',
                [ 'jquery'],
                HTMEGA_VERSION,
                TRUE
            );

            wp_enqueue_script(
                'htmega-templates',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/js/admin_scripts.js',
                [ 'jquery'],
                HTMEGA_VERSION,
                TRUE
            );

            wp_enqueue_script(
                'htmega-admin-install-manager',
                HTMEGA_ADDONS_PL_URL . 'admin/assets/js/admin_install_manager.js',
                ['htmega-templates', 'wp-util', 'updates'],
                HTMEGA_VERSION,
                TRUE
            );

            // Localize Script
            $current_user = wp_get_current_user();
            wp_localize_script(
                'htmega-templates',
                'HTTM',
                [
                    'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                    'adminURL'         => admin_url(),
                    'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
                    'version'          => HTMEGA_VERSION,
                    'pluginURL'        => plugin_dir_url( __FILE__ ),
                    'alldata'          => !empty( $this->get_templates_info()['templates'] ) ? $this->get_templates_info()['templates']:array(),
                    'prolink'          => !empty( $this->get_pro_link() ) ? $this->get_pro_link() : '#',
                    'prolabel'         => esc_html__( 'Pro', 'htmega-addons' ),
                    'loadingimg'       => HTMEGA_ADDONS_PL_URL . 'admin/assets/images/loading.gif',
                    'message'          =>[
                        'packagedesc'=> esc_html__( 'in this package', 'htmega-addons' ),
                        'allload'    => esc_html__( 'All Items have been Loaded', 'htmega-addons' ),
                        'notfound'   => esc_html__( 'Nothing Found', 'htmega-addons' ),
                    ],
                    'buttontxt'        =>[
                        'tmplibrary' => esc_html__( 'Import to Library', 'htmega-addons' ),
                        'tmppage'    => esc_html__( 'Import to Page', 'htmega-addons' ),
                        'import'     => esc_html__( 'Import', 'htmega-addons' ),
                        'buynow'     => esc_html__( 'Buy Now', 'htmega-addons' ),
                        'preview'    => esc_html__( 'Preview', 'htmega-addons' ),
                        'installing' => esc_html__( 'Installing..', 'htmega-addons' ),
                        'activating' => esc_html__( 'Activating..', 'htmega-addons' ),
                        'active'     => esc_html__( 'Active', 'htmega-addons' ),
                    ],
                    'user'             => [
                        'email' => $current_user->user_email,
                    ],

                ]
            );


        }


    }

    /**
     * Ajax request.
     */
    function templates_ajax_request(){

        if ( isset( $_REQUEST ) ) {

            $template_id        = $_REQUEST['httemplateid'];
            $template_parentid  = $_REQUEST['htparentid'];
            $template_title     = $_REQUEST['httitle'];
            $page_title         = $_REQUEST['pagetitle'];

            $templateurl    = sprintf( self::$templateapi, $template_id );
            $response_data  = $this->templates_get_content_remote_request( $templateurl );
            $defaulttitle   = ucfirst( $template_parentid ) .' -> '.$template_title;


            $args = [
                'post_type'    => !empty( $page_title ) ? 'page' : 'elementor_library',
                'post_status'  => !empty( $page_title ) ? 'draft' : 'publish',
                'post_title'   => !empty( $page_title ) ? $page_title : $defaulttitle,
                'post_content' => '',
            ];
            $new_post_id = wp_insert_post( $args );

            update_post_meta( $new_post_id, '_elementor_data', $response_data['content'] );
            update_post_meta( $new_post_id, '_elementor_page_settings', $response_data['page_settings'] );
            update_post_meta( $new_post_id, '_elementor_template_type', $response_data['type'] );
            update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

            if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                update_post_meta( $new_post_id, '_wp_page_template', !empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_canvas' );
            }

            echo json_encode(
                array( 
                    'id'      => $new_post_id,
                    'edittxt' => !empty( $page_title ) ? esc_html__( 'Edit Page', 'htmega-addons' ) : esc_html__( 'Edit Template', 'htmega-addons' )
                )
            );
        }

        wp_die();
    }

    /*
    * Remote data
    */
    function templates_get_content_remote_request( $templateurl ){
        $url = $templateurl;
        $response = wp_remote_get( $url, array(
            'timeout'   => 60,
            'sslverify' => false
        ) );
        $result = json_decode( wp_remote_retrieve_body( $response ), true );
        return $result;
    }

    /*
    * Ajax response required data
    */
    public function ajax_plugin_data(){
        if ( isset( $_POST ) ) {
            $freeplugins = explode( ',', $_POST['freeplugins'] );
            $proplugins = explode( ',', $_POST['proplugins'] );
            $themeinfo = explode( ',', $_POST['requiredtheme'] );
            if(!empty($_POST['freeplugins'])){$this->required_plugins( $freeplugins, 'free' );}
            if(!empty($_POST['proplugins'])){ $this->required_plugins( $proplugins, 'pro' );}
            if(!empty($_POST['requiredtheme'])){ $this->required_theme( $themeinfo, 'free' );}
        }
        wp_die();
    }

    /*
    * Required Plugins
    */
    public function required_plugins( $plugins, $type ) {
        foreach ( $plugins as $key => $plugin ) {

            $plugindata = explode( '//', $plugin );
            $data = array(
                'slug'      => isset( $plugindata[0] ) ? $plugindata[0] : '',
                'location'  => isset( $plugindata[1] ) ? $plugindata[0].'/'.$plugindata[1] : '',
                'name'      => isset( $plugindata[2] ) ? $plugindata[2] : '',
                'pllink'    => isset( $plugindata[3] ) ? 'https://'.$plugindata[3] : '#',
            );

            if ( ! is_wp_error( $data ) ) {

                // Installed but Inactive.
                if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

                    $button_classes = 'button activate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'htmega-addons' );

                // Not Installed.
                } elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

                    $button_classes = 'button install-now';
                    $button_text    = esc_html__( 'Install Now', 'htmega-addons' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'htmega-addons' );
                }

                ?>
                    <li class="htwptemplata-plugin-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( $type == 'pro' && ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['pllink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'htmega-addons' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php

            }

        }
    }

    /*
    * Required Theme
    */
    public function required_theme( $themes, $type ){
        foreach ( $themes as $key => $theme ) {
            $themedata = explode( '//', $theme );
            $data = array(
                'slug'      => isset( $themedata[0] ) ? $themedata[0] : '',
                'name'      => isset( $themedata[1] ) ? $themedata[1] : '',
                'prolink'   => isset( $themedata[2] ) ? $themedata[2] : '',
            );

            if ( ! is_wp_error( $data ) ) {

                $theme = wp_get_theme();

                // Installed but Inactive.
                if ( file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) && ( $theme->stylesheet != $data['slug'] ) ) {

                    $button_classes = 'button themeactivate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'htmega-addons' );

                // Not Installed.
                } elseif ( ! file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) ) {

                    $button_classes = 'button themeinstall-now';
                    $button_text    = esc_html__( 'Install Now', 'htmega-addons' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'htmega-addons' );
                }

                ?>
                    <li class="htwptemplata-theme-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( !empty( $data['prolink'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['prolink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'htmega-addons' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-themeopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php
            }


        }

    }

    /**
     * Ajax plugins activation request
     */
    public function ajax_plugin_activation() {

        if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['location'] ) || ! $_POST['location'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Plugin Not Found', 'htmega-addons' ),
                )
            );
        }

        $plugin_location = ( isset( $_POST['location'] ) ) ? esc_attr( $_POST['location'] ) : '';
        $activate    = activate_plugin( $plugin_location, '', false, true );

        if ( is_wp_error( $activate ) ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => $activate->get_error_message(),
                )
            );
        }

        wp_send_json_success(
            array(
                'success' => true,
                'message' => esc_html__( 'Plugin Successfully Activated', 'htmega-addons' ),
            )
        );

    }

    /*
    * Required Theme Activation Request
    */
    function ajax_theme_activation() {

        if ( ! current_user_can( 'install_themes' ) || ! isset( $_POST['themeslug'] ) || ! $_POST['themeslug'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Sorry, you are not allowed to install themes on this site.', 'htmega-addons' ),
                )
            );
        }

        $theme_slug = ( isset( $_POST['themeslug'] ) ) ? esc_attr( $_POST['themeslug'] ) : '';
        switch_theme( $theme_slug );

        wp_send_json_success(
            array(
                'success' => true,
                'message' => __( 'Theme Activated', 'htmega-addons' ),
            )
        );
    }


}

HTMega_Template_Library::instance();