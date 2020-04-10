<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMega_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new HTMega_Settings_API();

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 220 );

        add_action( 'wsa_form_top_htmega_element_tabs', array( $this, 'html_element_toogle_button' ) );
        add_action( 'wsa_form_top_htmega_thirdparty_element_tabs', array( $this, 'html_element_toogle_button' ) );
        add_action( 'wsa_form_bottom_htmega_pro_vs_free_tabs', array( $this, 'popup_box' ) );
        add_action( 'wsa_form_bottom_htmega_pro_vs_free_tabs', array( $this, 'pro_vs_free_html_tabs' ) );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->htmega_admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->htmega_admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }
    
    // Plugins menu Register
    function admin_menu() {

        $menu = 'add_menu_' . 'page';
        $menu(
            'htmega_panel',
            esc_html__( 'HTMega Addons', 'htmega-addons' ),
            esc_html__( 'HTMega Addons', 'htmega-addons' ),
            'htmega_addons_option_page',
            NULL,
            HTMEGA_ADDONS_PL_URL.'admin/assets/images/menu-icon.png',
            59
        );
        
        add_submenu_page(
            'htmega_addons_option_page', 
            esc_html__( 'Settings', 'htmega-addons' ),
            esc_html__( 'Settings', 'htmega-addons' ), 
            'manage_options', 
            'htmega_addons_options', 
            array ( $this, 'plugin_page' ) 
        );

    }

    // Options page Section register
    function htmega_admin_get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'htmega_pro_vs_free_tabs',
                'title' => esc_html__( 'General', 'htmega-addons' )
            ),

            array(
                'id'    => 'htmega_element_tabs',
                'title' => esc_html__( 'Elements', 'htmega-addons' )
            ),
            
            array(
                'id'    => 'htmega_thirdparty_element_tabs',
                'title' => esc_html__( 'Third Party', 'htmega-addons' )
            ),
            
            array(
                'id'    => 'htmega_general_tabs',
                'title' => esc_html__( 'Other options', 'htmega-addons' )
            ),

            array(
                'id'    => 'htmega_advance_element_tabs',
                'title' => esc_html__( 'Advance Addons', 'htmega-addons' )
            ),

        );

        $advance_element = array();
        if( htmega_get_option( 'themebuilder', 'htmega_advance_element_tabs', 'off' ) === 'on' ){
            $advance_element[] = array(
                'id'    => 'htmega_themebuilder_element_tabs',
                'title' => esc_html__( 'Theme Builder', 'htmega-addons' )
            );
        }

        return array_merge( $sections, $advance_element );
    }

    // Options page field register
    protected function htmega_admin_fields_settings() {

        $settings_fields = array(

            'htmega_pro_vs_free_tabs' => array(),

            'htmega_element_tabs'=>array(

                array(
                    'name'  => 'accordion',
                    'label'  => __( 'Accordion', 'htmega-addons' ),
                    'desc'  => __( 'Accordion', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'animatesectiontitle',
                    'label'  => __( 'Animate Heading', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'addbanner',
                    'label'  => __( 'Ads Banner', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'specialadsbanner',
                    'label'  => __( 'Special Day Offer', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'blockquote',
                    'label'  => __( 'Blockquote', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'brandlogo',
                    'label'  => __( 'Brands', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'businesshours',
                    'label'  => __( 'Business Hours', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'button',
                    'label'  => __( 'Button', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'calltoaction',
                    'label'  => __( 'Call To Action', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'carousel',
                    'label'  => __( 'Carousel', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'countdown',
                    'label'  => __( 'Countdown', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'counter',
                    'label'  => __( 'Counter', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'customevent',
                    'label'  => __( 'Custom Event', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'dualbutton',
                    'label'  => __( 'Double Button', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'dropcaps',
                    'label'  => __( 'Dropcaps', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'flipbox',
                    'label'  => __( 'Flip Box', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'galleryjustify',
                    'label'  => __( 'Gallery Justify', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'googlemap',
                    'label'  => __( 'Google Map', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'imagecomparison',
                    'label'  => __( 'Image Comparison', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'imagegrid',
                    'label'  => __( 'Image Grid', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'imagemagnifier',
                    'label'  => __( 'Image Magnifier', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'imagemarker',
                    'label'  => __( 'Image Marker', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'imagemasonry',
                    'label'  => __( 'Image Masonry', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'inlinemenu',
                    'label'  => __( 'Inline Navigation', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'instagram',
                    'label'  => __( 'Instagram', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'lightbox',
                    'label'  => __( 'Light Box', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'modal',
                    'label'  => __( 'Modal', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'newtsicker',
                    'label'  => __( 'News Ticker', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'notify',
                    'label'  => __( 'Notify', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'offcanvas',
                    'label'  => __( 'Offcanvas', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'panelslider',
                    'label'  => __( 'Panel Slider', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'popover',
                    'label'  => __( 'Popover', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'postcarousel',
                    'label'  => __( 'Post carousel', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'postgrid',
                    'label'  => __( 'Post Grid', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'postgridtab',
                    'label'  => __( 'Post Grid Tab', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'postslider',
                    'label'  => __( 'Post Slider', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'pricinglistview',
                    'label'  => __( 'Pricing List View', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'pricingtable',
                    'label'  => __( 'Pricing Table', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'progressbar',
                    'label'  => __( 'Progress Bar', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'scrollimage',
                    'label'  => __( 'Scroll Image', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'scrollnavigation',
                    'label'  => __( 'Scroll Navigation', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'search',
                    'label'  => __( 'Search', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'sectiontitle',
                    'label'  => __( 'Section Title', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'service',
                    'label'  => __( 'Service', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'singlepost',
                    'label'  => __( 'Single Post', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'thumbgallery',
                    'label'  => __( 'Slider Thumbnail Gallery', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'socialshere',
                    'label'  => __( 'Social Share', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'switcher',
                    'label'  => __( 'Switcher', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'tabs',
                    'label'  => __( 'Tabs', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'datatable',
                    'label'  => __( 'Data Table', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'teammember',
                    'label'  => __( 'Team Member', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'testimonial',
                    'label'  => __( 'Testimonial', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'testimonialgrid',
                    'label'  => __( 'Testimonial Grid', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'toggle',
                    'label'  => __( 'Toggle', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'tooltip',
                    'label'  => __( 'Tooltip', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'twitterfeed',
                    'label'  => __( 'Twitter Feed', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'userloginform',
                    'label'  => __( 'User Login Form', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'userregisterform',
                    'label'  => __( 'User Register Form', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'verticletimeline',
                    'label'  => __( 'Verticle Timeline', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'videoplayer',
                    'label'  => __( 'Video Player', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'workingprocess',
                    'label'  => __( 'Working Process', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'errorcontent',
                    'label'  => __( '404 Content', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

            ),

            'htmega_general_tabs'=>array(

                array(
                    'name'  => 'google_map_api_key',
                    'label' => __( 'Google Map Api Key', 'htmega-addons' ),
                    'desc'  => __( 'Go to <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">https://developers.google.com</a> and generate the API key.', 'htmega-addons' ),
                    'placeholder' => __( 'Google Map Api key', 'htmega-addons' ),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),

                array(
                    'name'    => 'errorpage',
                    'label'   => __( 'Select 404 Page.', 'htmega-addons' ),
                    'desc'    => __( 'You can select 404 page from here.', 'htmega-addons' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htmega_post_name( 'page', -1 )
                ),

                array(
                    'name'  => 'loadpostlimit',
                    'label' => __( 'Load Post in Elementor Addons', 'htmega-addons' ),
                    'desc'  => wp_kses_post( 'Load Post in Elementor Addons', 'htmega-addons' ),
                    'min'               => 1,
                    'max'               => 1000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '20',
                    'sanitize_callback' => 'floatval',
                ),

            ),

            'htmega_advance_element_tabs'=>array(

                array(
                    'name'  => 'themebuilder',
                    'label'  => __( 'Theme Builder', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'salenotification',
                    'label'  => __( 'Sales Notification', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'megamenubuilder',
                    'label'  => __( 'Menu Builder', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default'=>'off',
                    'class'=>'htmega_table_row',
                ),
                

            ),

            'htmega_themebuilder_element_tabs'=>array(

                array(
                    'name'  => 'bl_post_title',
                    'label'  => __( 'Post Title', 'htmega-addons' ),
                    'desc'  => __( 'Post Title', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_featured_image',
                    'label'  => __( 'Post Featured Image', 'htmega-addons' ),
                    'desc'  => __( 'Post Featured Image', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_meta_info',
                    'label'  => __( 'Post Meta Info', 'htmega-addons' ),
                    'desc'  => __( 'Post Meta Info', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_excerpt',
                    'label'  => __( 'Post Excerpt', 'htmega-addons' ),
                    'desc'  => __( 'Post Excerpt', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_content',
                    'label'  => __( 'Post Content', 'htmega-addons' ),
                    'desc'  => __( 'Post Content', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_comments',
                    'label'  => __( 'Post Comments', 'htmega-addons' ),
                    'desc'  => __( 'Post Comments', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_search_form',
                    'label'  => __( 'Post Search Form', 'htmega-addons' ),
                    'desc'  => __( 'Post Search Form', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_archive',
                    'label'  => __( 'Archive Posts', 'htmega-addons' ),
                    'desc'  => __( 'Archive Posts', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_archive_title',
                    'label'  => __( 'Archive Title', 'htmega-addons' ),
                    'desc'  => __( 'Archive Title', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),
                
                array(
                    'name'  => 'bl_page_title',
                    'label'  => __( 'Page Title', 'htmega-addons' ),
                    'desc'  => __( 'Page Title', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_site_title',
                    'label'  => __( 'Site Title', 'htmega-addons' ),
                    'desc'  => __( 'Site Title', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_site_logo',
                    'label'  => __( 'Site Logo', 'htmega-addons' ),
                    'desc'  => __( 'Site Logo', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_nav_menu',
                    'label'  => __( 'Nav Menu', 'htmega-addons' ),
                    'desc'  => __( 'Nav Menu', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_post_author_info',
                    'label'  => __( 'Author Info', 'htmega-addons' ),
                    'desc'  => __( 'Author Info', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmega_table_row',
                ),

                array(
                    'name'  => 'bl_social_sharep',
                    'label'  => __( 'Social Share <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'Social share', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),

                array(
                    'name'  => 'bl_print_pagep',
                    'label'  => __( 'Print Page <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'Print Page', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),

                array(
                    'name'  => 'bl_view_counterp',
                    'label'  => __( 'View Counter <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'View Counter', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),

                array(
                    'name'  => 'bl_post_navigationp',
                    'label'  => __( 'Post Navigation <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'Post Navigation', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),

                array(
                    'name'  => 'bl_related_postp',
                    'label'  => __( 'Related Post <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'Related Post', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),

                array(
                    'name'  => 'bl_popular_postp',
                    'label'  => __( 'Popular Post <span>( Pro )</span>', 'htmega-addons' ),
                    'desc'  => __( 'Popular Post', 'htmega-addons' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htmega_table_row pro',
                ),


            ),

        );
        
        // Third Party Addons
        $third_party_element = array();
        if( is_plugin_active('bbpress/bbpress.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'bbpress',
                'label'    => __( 'bbPress', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('booked/booked.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'bookedcalender',
                'label'    => __( 'Booked Calender', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('buddypress/bp-loader.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'buddypress',
                'label'    => __( 'BuddyPress', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('caldera-forms/caldera-core.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'calderaform',
                'label'    => __( 'Caldera Form', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'contactform',
                'label'    => __( 'Contact form 7', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('download-monitor/download-monitor.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'downloadmonitor',
                'label'    => __( 'Download Monitor', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('easy-digital-downloads/easy-digital-downloads.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'easydigitaldownload',
                'label'    => __( 'Easy Digital Downloads', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('gravityforms/gravityforms.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'gravityforms',
                'label'    => __( 'Gravity Forms', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('instagram-feed/instagram-feed.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'instragramfeed',
                'label'    => __( 'Instragram Feed', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('wp-job-manager/wp-job-manager.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'jobmanager',
                'label'    => __( 'Job Manager', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('LayerSlider/layerslider.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'layerslider',
                'label'    => __( 'Job Manager', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'mailchimpwp',
                'label'    => __( 'Mailchimp for wp', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('ninja-forms/ninja-forms.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'ninjaform',
                'label'    => __( 'Ninja Form', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('quform/quform.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'quforms',
                'label'    => __( 'QU Form', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('wpforms-lite/wpforms.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'wpforms',
                'label'    => __( 'WP Form', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('revslider/revslider.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'revolution',
                'label'    => __( 'Revolution Slider', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('tablepress/tablepress.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'tablepress',
                'label'    => __( 'TablePress', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('awesome-weather/awesome-weather.php') ) {
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'  => 'weather',
                'label'  => __( 'Weather', 'htmega-addons' ),
                'type'  => 'checkbox',
                'default'=>'on',
                'class'=>'htmega_table_row',
            ];
        }

        if( is_plugin_active('woocommerce/woocommerce.php') ) {
           
            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'wcaddtocart',
                'label'    => __( 'WC : Add To cart', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];

            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'categories',
                'label'    => __( 'WC : Categories', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];

            $third_party_element['htmega_thirdparty_element_tabs'][] = [
                'name'    => 'wcpages',
                'label'    => __( 'WC : Pages', 'htmega-addons' ),
                'type'    => 'checkbox',
                'default' => "on",
                'class'=>'htmega_table_row',
            ];

        }

        return array_merge($settings_fields, $third_party_element);
    }


    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HTMega Addons Settings','htmega-addons' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'htmega-addons') ?></strong></p>
            </div>
            
            <?php
        }
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = [];
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }

    // General tab
    function pro_vs_free_html_tabs(){
        ob_start();
        ?>
            <div class="htmega-general-tabs">

                <div class="htmega-document-section">
                    <div class="htmega-column">
                        <a href="https://hasthemes.com/blog-category/ht-mega/" target="_blank">
                            <img src="<?php echo HTMEGA_ADDONS_PL_URL; ?>/admin/assets/images/video-tutorial.jpg" alt="<?php esc_attr_e( 'Video Tutorial', 'htmega-addons' ); ?>">
                        </a>
                    </div>
                    <div class="htmega-column">
                        <a href="https://demo.hasthemes.com/doc/htmega/index.html" target="_blank">
                            <img src="<?php echo HTMEGA_ADDONS_PL_URL; ?>/admin/assets/images/online-documentation.jpg" alt="<?php esc_attr_e( 'Online Documentation', 'htmega-addons' ); ?>">
                        </a>
                    </div>
                    <div class="htmega-column">
                        <a href="https://hasthemes.com/contact-us/" target="_blank">
                            <img src="<?php echo HTMEGA_ADDONS_PL_URL; ?>/admin/assets/images/genral-contact-us.jpg" alt="<?php esc_attr_e( 'Contact Us', 'htmega-addons' ); ?>">
                        </a>
                    </div>
                </div>

                <div class="different-pro-free">
                    <h3 class="htmega-section-title"><?php echo esc_html__( 'HTMega Free Vs HTMega Pro.', 'htmega-addons' ); ?></h3>

                    <div class="htmega-admin-row">
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'HTMega Free', 'htmega-addons' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( '84 Elements', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '15 Categories / Template set', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '15 Templates', 'htmega-addons' ); ?></li>
                                <li class="fedel"><del><?php echo esc_html__( '360 Blocks / Sections', 'htmega-addons' ); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Blog Search Page Builder', 'htmega-addons'); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( '404 Error Page Builder', 'htmega-addons'); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Coming soon Page Builder', 'htmega-addons'); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Blog Archive Category Wise Individual layout', 'htmega-addons'); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Blog Archive Tag Wise Individual layout', 'htmega-addons'); ?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Fakes notification', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Notification showing position', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Notification image position', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Time interval each notification', 'htmega-addons');?></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Sales upto date option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Incoming animation option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Outgoing animation option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Background color option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Heading color option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Content color option', 'htmega-addons');?></del></li>
                                <li class="fedel"><del><?php echo esc_html__( 'Cross icon color option', 'htmega-addons');?></del></li>
                            </ul>
                            <a class="button button-primary" href="<?php echo esc_url( admin_url() ); ?>/plugin-install.php" target="_blank"><?php echo esc_html__( 'Install Now', 'htmega-addons' ); ?></a>
                        </div>
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'HTMega Pro', 'htmega-addons' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( '84 Elements', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '325 Blocks / Sections', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '35 Categories / Template set', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '524 Templates', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( 'Blog Search Page Builder', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( '404 Error Page Builder', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( 'Coming soon Page Builder', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( 'Blog Archive Category Wise Individual layout', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( 'Blog Archive Tag Wise Individual layout', 'htmega-addons' ); ?></li>
                                <li><?php echo esc_html__( 'Fakes notification', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Notification showing position', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Notification image position', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Time interval each notification', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Sales upto date option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Incoming animation option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Outgoing animation option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Background color option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Heading color option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Content color option', 'htmega-addons');?></li>
                                <li><?php echo esc_html__( 'Cross icon color option', 'htmega-addons');?></li>
                            </ul>
                            <a class="button button-primary" href="https://hasthemes.com/plugins/ht-mega-pro/" target="_blank"><?php echo esc_html__( 'Buy Now', 'htmega-addons' ); ?></a>
                        </div>
                    </div>

                </div>

            </div>
        <?php
        echo ob_get_clean();
    }

    // Pop up Box
    function popup_box(){
        ob_start();
        ?>
            <div id="htmega-dialog" title="<?php esc_html_e( 'Go Premium', 'htmega-addons' ); ?>" style="display: none;">
                <div class="htmega-content">
                    <span><i class="dashicons dashicons-warning"></i></span>
                    <p>
                        <?php
                            echo __('Purchase our','htmega-addons').' <strong><a href="'.esc_url( 'https://hasthemes.com/plugins/ht-mega-pro/' ).'" target="_blank" rel="nofollow">'.__( 'premium version', 'htmega-addons' ).'</a></strong> '.__('to unlock these pro elements!','htmega-addons');
                        ?>
                    </p>
                </div>
            </div>
            <script type="text/javascript">
                ( function( $ ) {
                    
                    $(function() {
                        $( '.htmega_table_row.pro,.htmegapro label' ).click(function() {
                            $( "#htmega-dialog" ).dialog({
                                modal: true,
                                minWidth: 500,
                                buttons: {
                                    Ok: function() {
                                      $( this ).dialog( "close" );
                                    }
                                }
                            });
                        });
                        $(".htmega_table_row.pro input[type='checkbox'],.htmegapro select").attr("disabled", true);
                    });

                } )( jQuery );
            </script>
        <?php
        echo ob_get_clean();
    }

    // Element Toogle Button
    function html_element_toogle_button(){
        ob_start();
        ?>
            <span class="htmega-open-element-toggle"><?php esc_html_e( 'Toggle All', 'htmega-pro' );?></span>
        <?php
        echo ob_get_clean();
    }


}

new HTMega_Admin_Settings();