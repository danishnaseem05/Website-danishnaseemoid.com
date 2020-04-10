<?php 

class HTMegaMenu_Elementor {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_action( 'init', [ $this, 'init' ] );

        add_action( 'wp_ajax_HT_Mega_Menu_Panels_ajax_requests', [ $this, 'panel_ajax_requests' ] );
    }

    protected function setMode() {
        if ( is_admin() ) {
            $this->mode = 'admin';
        } else {
            $this->mode = 'frontend';
        }
    }

    public function init() {

        // Set current mode
        $this->setMode();

        if( $this->mode === 'admin' ) {
            // If the user can manage options, let the fun begin!
            if ( current_user_can( 'manage_options' ) ) {
                add_action( 'admin_init', array( $this, 'register_nav_meta_box' ), 9 );
            }
        }

        // Plugins Required File
        $this->includes();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

        // Admin Scripts
        add_action('admin_enqueue_scripts', array( $this, 'htmega_megamenu_admin_scripts_method' ) );

        add_action( 'admin_footer', array( $this, 'htmega_menu_pop_up_content' ) );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'htmega_menu_styles_inline' ) );

    }

    // Add custom category.
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'htmegamenu-addons',
            [
                'title' => __( 'HTMega Menu', 'htmega-addons' ),
                'icon' => 'fa fa-snowflake-o',
            ]
        );
    }

    // Register Widgets
    public function init_widgets() {
       // Include files
        require_once ( HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/widgets/inline-mega-menu.php' );
        require_once ( HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/widgets/verticle-mega-menu.php' );
    }

    // Meta Box Field render
    public function register_nav_meta_box() {
        global $pagenow;
        if ( 'nav-menus.php' == $pagenow ) {
            add_meta_box(
                'HT_Mega_Menu_meta_box',
                __("Mega menu Settings", ""),
                array( $this, 'metabox_contents' ),
                'nav-menus',
                'side',
                'core'
            );
        }
    }

    public function metabox_contents(){
        // Get recently edited nav menu.
        $recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
        $nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? absint( $_REQUEST['menu'] ) : 0;
        if ( empty( $recently_edited ) && is_nav_menu( $nav_menu_selected_id ) )
            $recently_edited = $nav_menu_selected_id;
        
        // Use $recently_edited if none are selected.
        if ( empty( $nav_menu_selected_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) )
            $nav_menu_selected_id = $recently_edited;
        
        $options = get_option( "ht_menu_options_" . $nav_menu_selected_id );

    ?>
        <div id="htmegamenu-menu-metabox">

            <?php wp_nonce_field( basename( __FILE__ ), 'htmegamenu_menu_metabox_noce' ); ?>
            <input type="hidden" value="<?php echo esc_attr( $nav_menu_selected_id ); ?>" id="htmegamenu-metabox-input-menu-id" />
            <p>
                <label><strong><?php esc_html_e( "Enable megamenu?", 'htmega-menu' ); ?></strong></label>
                <input type="checkbox" class="alignright pull-right-input" id="htmegamenu-menu-metabox-input-is-enabled" <?php echo isset($options['enable_menu']) && $options['enable_menu'] == 'on' ? 'checked="true"' : '' ?>>
            </p>
            <p>
                <?php echo get_submit_button( esc_html__('Save', 'htmega-menu'), 'htmegamenu-menu-settings-save button-primary alignright','', false); ?>
                <span class='spinner'></span>
            </p>

        </div>

    <?php
    }

    public function panel_ajax_requests(){

        $action = isset( $_REQUEST['sub_action'] ) ? $_REQUEST['sub_action'] : '';
        
        if( $action === 'save_menu_settings' ){
            $this->menu_item_id = absint( $_REQUEST['menu_item_id'] );
            update_post_meta( $this->menu_item_id, 'ht_menu_settings', array(
                'type' => isset( $_REQUEST['menu_type'] ) ? sanitize_text_field( $_REQUEST['menu_type'] ) : ''
            ) );
            die('valid');
        }
        
        if( $action === 'save_menu_options' ){
            $settings = isset($_REQUEST['settings']) ? $_REQUEST['settings'] : array();
            $menu_id = absint( $_REQUEST['menu_id'] );
            update_option( 'ht_menu_options_' . $menu_id, $settings );
            die('valid');
        } 
    }

    public function includes() {
        // Include files
        require_once ( HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/htmenu_menu.php' );
    }

    // enqueue frontend scripts
    public function enqueue_frontend_scripts(){
        
        // CSS File
        wp_enqueue_style(  'htmega-menu',  HTMEGA_ADDONS_PL_URL . 'assets/extensions/ht-menu/css/mega-menu-style.css', array(), HTMEGA_VERSION );

        // JS File
        wp_enqueue_script( 'htmegamenu-main', HTMEGA_ADDONS_PL_URL . 'assets/extensions/ht-menu/js/htmegamenu-main.js', array('jquery') );

    }

    public function htmega_megamenu_admin_scripts_method($hook){

        wp_enqueue_script('fonticonpicker.js', HTMEGA_ADDONS_PL_URL . 'admin/assets/extensions/ht-menu/js/jquery.fonticonpicker.min.js',
            array('jquery'));

        wp_enqueue_script( 'htmegamenu-admin', HTMEGA_ADDONS_PL_URL . 'admin/assets/extensions/ht-menu/js/admin_scripts.js', array('jquery'), HTMEGA_VERSION, TRUE );

        wp_enqueue_style( 'fonticonpicker', HTMEGA_ADDONS_PL_URL . 'admin/assets/extensions/ht-menu/css/jquery.fonticonpicker.min.css' );
        
        wp_enqueue_style( 'fonticonpicker-bootstrap', HTMEGA_ADDONS_PL_URL . 'admin/assets/extensions/ht-menu/css/jquery.fonticonpicker.bootstrap.min.css');

        $icons = $this->htmega_menu_get_icon_sets();
        wp_add_inline_script('htmegamenu-admin', $icons);

    }

    public function htmega_menu_get_icon_sets(){

        $icon_set = array();
        $icon_set['FontAwesome'][] = 'Pro';
           
        ob_start(); ?>
        <script type="text/javascript">
            var htmegaIconsSet = <?php echo json_encode($icon_set); ?>;

            ( function( $ ) {
                    
                $(function() {
                    $( '.htmegamenu-pro' ).click(function() {
                        $( "#htmegapro-dialog" ).dialog({
                            modal: true,
                            minWidth: 500,
                            buttons: {
                                Ok: function() {
                                  $( this ).dialog( "close" );
                                }
                            }
                        });
                    });
                    $(".htmegamenu-pro .wp-picker-container .wp-color-result,.htmegamenu-pro input").attr("disabled", true);
                });

            } )( jQuery );
        </script>
        <?php
        $r = ob_get_clean();
        $remove = array('<script type="text/javascript">', '</script>');
        $r = str_replace($remove, '', $r);
        return $r;
    }

    public function htmega_menu_pop_up_content(){
        ob_start();
        ?>
            <div id="htmegapro-dialog" title="<?php esc_html_e( 'Go Premium', 'htmega-menu' ); ?>" style="display: none;">
                <div class="htmega-dialog-content">
                    <span><i class="dashicons dashicons-warning"></i></span>
                    <p>
                        <?php
                            echo __('Purchase our','htmega-menu').' <strong><a href="'.esc_url( 'https://hasthemes.com/ht-mega-menu-for-elementor-page-builder/' ).'" target="_blank" rel="nofollow">'.__( 'premium version', 'htmega-menu' ).'</a></strong> '.__('to unlock these pro options!','htmega-menu');
                        ?>
                    </p>
                </div>
            </div>
        <?php
        echo ob_get_clean();
    }

    /**
    * Add Inline CSS.
    */
    public function htmega_menu_styles_inline() {

        $menu_item_color = $menu_item_hover_color = $sub_menu_width = $sub_menu_bg = $sub_menu_itemcolor = $sub_menu_itemhover_color = $mega_menu_width = $mega_menu_bg = '';

        $menuitemscolor         = htmega_get_option( 'menu_items_color', 'htmegamenu_setting_tabs' );
        $menuitemshovercolor    = htmega_get_option( 'menu_items_hover_color', 'htmegamenu_setting_tabs' );
        $submenuwidth           = htmega_get_option( 'sub_menu_width', 'htmegamenu_setting_tabs' );
        $submenubg              = htmega_get_option( 'sub_menu_bg_color', 'htmegamenu_setting_tabs' );
        $submenuitemcolor       = htmega_get_option( 'sub_menu_items_color', 'htmegamenu_setting_tabs' );
        $submenuitemhovercolor  = htmega_get_option( 'sub_menu_items_hover_color', 'htmegamenu_setting_tabs' );
        $megamenuwidth          = htmega_get_option( 'mega_menu_width', 'htmegamenu_setting_tabs' );
        $megamenubg             = htmega_get_option( 'mega_menu_bg_color', 'htmegamenu_setting_tabs' );

        if( !empty($menuitemscolor) ){
            $menu_item_color = "
                .htmega-menu-container ul > li > a{
                    color: {$menuitemscolor};
                }
            ";
        }

        if( !empty($menuitemshovercolor) ){
            $menu_item_hover_color = "
                .htmega-menu-container ul > li > a:hover{
                    color: {$menuitemshovercolor};
                }
            ";
        }

        if( !empty($submenuwidth) ){
            $sub_menu_width = "
                .htmega-menu-container .sub-menu{
                    width: {$submenuwidth}px;
                }
            ";
        }

        if( !empty($submenubg) ){
            $sub_menu_bg = "
                .htmega-menu-container .sub-menu{
                    background-color: {$submenubg};
                }
            ";
        }

        if( !empty($submenuitemcolor) ){
            $sub_menu_itemcolor = "
                .htmega-menu-container .sub-menu li a{
                    color: {$submenuitemcolor};
                }
            ";
        }

        if( !empty($submenuitemhovercolor) ){
            $sub_menu_itemhover_color = "
                .htmega-menu-container .sub-menu li a:hover{
                    color: {$submenuitemhovercolor};
                }
            ";
        }

        if( !empty($megamenuwidth) ){
            $mega_menu_width = "
                .htmega-menu-container .htmegamenu-content-wrapper{
                    width: {$megamenuwidth}px;
                }
            ";
        }

        if( !empty($megamenubg) ){
            $mega_menu_bg = "
                .htmega-menu-container .htmegamenu-content-wrapper{
                    background-color: {$megamenubg};
                }
            ";
        }

        $custom_css = "
            $menu_item_color
            $menu_item_hover_color
            $sub_menu_width
            $sub_menu_bg
            $sub_menu_itemcolor
            $sub_menu_itemhover_color
            $mega_menu_width
            $mega_menu_bg
            ";
        wp_add_inline_style( 'htmega-menu', $custom_css );
    }


}

HTMegaMenu_Elementor::instance();