<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMega_Widgets_Control{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }

    // Add custom category.
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'htmega-addons',
            [
                'title' => __( 'HTMega Addons', 'htmega-addons' ),
                'icon' => 'fa fa-snowflake',
            ]
        );
    }

    // Controll Widgets
    public function init_widgets(){

        // On off check
        $accordion = htmega_get_option( 'accordion', 'htmega_element_tabs', 'on' );
        $animatesectiontitle = htmega_get_option( 'animatesectiontitle', 'htmega_element_tabs', 'on' );
        $addbanner = htmega_get_option( 'addbanner', 'htmega_element_tabs', 'on' );
        $specialadsbanner = htmega_get_option( 'specialadsbanner', 'htmega_element_tabs', 'on' );
        $blockquote = htmega_get_option( 'blockquote', 'htmega_element_tabs', 'on' );
        $brandlogo = htmega_get_option( 'brandlogo', 'htmega_element_tabs', 'on' );
        $businesshours = htmega_get_option( 'businesshours', 'htmega_element_tabs', 'on' );
        $button = htmega_get_option( 'button', 'htmega_element_tabs', 'on' );
        $calltoaction = htmega_get_option( 'calltoaction', 'htmega_element_tabs', 'on' );
        $carousel = htmega_get_option( 'carousel', 'htmega_element_tabs', 'on' );
        $countdown = htmega_get_option( 'countdown', 'htmega_element_tabs', 'on' );
        $counter = htmega_get_option( 'counter', 'htmega_element_tabs', 'on' );
        $customevent = htmega_get_option( 'customevent', 'htmega_element_tabs', 'on' );
        $dualbutton = htmega_get_option( 'dualbutton', 'htmega_element_tabs', 'on' );
        $dropcaps = htmega_get_option( 'dropcaps', 'htmega_element_tabs', 'on' );
        $flipbox = htmega_get_option( 'flipbox', 'htmega_element_tabs', 'on' );
        $galleryjustify = htmega_get_option( 'galleryjustify', 'htmega_element_tabs', 'on' );
        $googlemap = htmega_get_option( 'googlemap', 'htmega_element_tabs', 'on' );
        $imagecomparison = htmega_get_option( 'imagecomparison', 'htmega_element_tabs', 'on' );
        $imagegrid = htmega_get_option( 'imagegrid', 'htmega_element_tabs', 'on' );
        $imagemagnifier = htmega_get_option( 'imagemagnifier', 'htmega_element_tabs', 'on' );
        $imagemarker = htmega_get_option( 'imagemarker', 'htmega_element_tabs', 'on' );
        $imagemasonry = htmega_get_option( 'imagemasonry', 'htmega_element_tabs', 'on' );
        $inlinemenu = htmega_get_option( 'inlinemenu', 'htmega_element_tabs', 'on' );
        $instagram = htmega_get_option( 'instagram', 'htmega_element_tabs', 'on' );
        $lightbox = htmega_get_option( 'lightbox', 'htmega_element_tabs', 'on' );
        $modal = htmega_get_option( 'modal', 'htmega_element_tabs', 'on' );
        $newtsicker = htmega_get_option( 'newtsicker', 'htmega_element_tabs', 'on' );
        $notify = htmega_get_option( 'notify', 'htmega_element_tabs', 'on' );
        $offcanvas = htmega_get_option( 'offcanvas', 'htmega_element_tabs', 'on' );
        $panelslider = htmega_get_option( 'panelslider', 'htmega_element_tabs', 'on' );
        $popover = htmega_get_option( 'popover', 'htmega_element_tabs', 'on' );
        $postcarousel = htmega_get_option( 'postcarousel', 'htmega_element_tabs', 'on' );
        $postgrid = htmega_get_option( 'postgrid', 'htmega_element_tabs', 'on' );
        $postgridtab = htmega_get_option( 'postgridtab', 'htmega_element_tabs', 'on' );
        $postslider = htmega_get_option( 'postslider', 'htmega_element_tabs', 'on' );
        $pricinglistview = htmega_get_option( 'pricinglistview', 'htmega_element_tabs', 'on' );
        $pricingtable = htmega_get_option( 'pricingtable', 'htmega_element_tabs', 'on' );
        $progressbar = htmega_get_option( 'progressbar', 'htmega_element_tabs', 'on' );
        $scrollimage = htmega_get_option( 'scrollimage', 'htmega_element_tabs', 'on' );
        $scrollnavigation = htmega_get_option( 'scrollnavigation', 'htmega_element_tabs', 'on' );
        $search = htmega_get_option( 'search', 'htmega_element_tabs', 'on' );
        $sectiontitle = htmega_get_option( 'sectiontitle', 'htmega_element_tabs', 'on' );
        $service = htmega_get_option( 'service', 'htmega_element_tabs', 'on' );
        $singlepost = htmega_get_option( 'singlepost', 'htmega_element_tabs', 'on' );
        $thumbgallery = htmega_get_option( 'thumbgallery', 'htmega_element_tabs', 'on' );
        $socialshere = htmega_get_option( 'socialshere', 'htmega_element_tabs', 'on' );
        $switcher = htmega_get_option( 'switcher', 'htmega_element_tabs', 'on' );
        $tabs = htmega_get_option( 'tabs', 'htmega_element_tabs', 'on' );
        $datatable = htmega_get_option( 'datatable', 'htmega_element_tabs', 'on' );
        $teammember = htmega_get_option( 'teammember', 'htmega_element_tabs', 'on' );
        $testimonial = htmega_get_option( 'testimonial', 'htmega_element_tabs', 'on' );
        $testimonialgrid = htmega_get_option( 'testimonialgrid', 'htmega_element_tabs', 'on' );
        $toggle = htmega_get_option( 'toggle', 'htmega_element_tabs', 'on' );
        $tooltip = htmega_get_option( 'tooltip', 'htmega_element_tabs', 'on' );
        $twitterfeed = htmega_get_option( 'twitterfeed', 'htmega_element_tabs', 'on' );
        $userloginform = htmega_get_option( 'userloginform', 'htmega_element_tabs', 'on' );
        $userregisterform = htmega_get_option( 'userregisterform', 'htmega_element_tabs', 'on' );
        $verticletimeline = htmega_get_option( 'verticletimeline', 'htmega_element_tabs', 'on' );
        $videoplayer = htmega_get_option( 'videoplayer', 'htmega_element_tabs', 'on' );
        $workingprocess = htmega_get_option( 'workingprocess', 'htmega_element_tabs', 'on' );
        $errorcontent = htmega_get_option( 'errorcontent', 'htmega_element_tabs', 'on' );

        // Third Party
        $weather = htmega_get_option( 'weather', 'htmega_thirdparty_element_tabs', 'on' );
        $bbpress = htmega_get_option( 'bbpress', 'htmega_thirdparty_element_tabs', 'on' );
        $bookedcalender = htmega_get_option( 'bookedcalender', 'htmega_thirdparty_element_tabs', 'on' );
        $buddypress = htmega_get_option( 'buddypress', 'htmega_thirdparty_element_tabs', 'on' );
        $calderaform = htmega_get_option( 'calderaform', 'htmega_thirdparty_element_tabs', 'on' );
        $contactform = htmega_get_option( 'contactform', 'htmega_thirdparty_element_tabs', 'on' );
        $downloadmonitor = htmega_get_option( 'downloadmonitor', 'htmega_thirdparty_element_tabs', 'on' );
        $easydigitaldownload = htmega_get_option( 'easydigitaldownload', 'htmega_thirdparty_element_tabs', 'on' );
        $gravityforms = htmega_get_option( 'gravityforms', 'htmega_thirdparty_element_tabs', 'on' );
        $instragramfeed = htmega_get_option( 'instragramfeed', 'htmega_thirdparty_element_tabs', 'on' );
        $jobmanager = htmega_get_option( 'jobmanager', 'htmega_thirdparty_element_tabs', 'on' );
        $layerslider = htmega_get_option( 'layerslider', 'htmega_thirdparty_element_tabs', 'on' );
        $mailchimpwp = htmega_get_option( 'mailchimpwp', 'htmega_thirdparty_element_tabs', 'on' );
        $ninjaform = htmega_get_option( 'ninjaform', 'htmega_thirdparty_element_tabs', 'on' );
        $quforms = htmega_get_option( 'quforms', 'htmega_thirdparty_element_tabs', 'on' );
        $wpforms = htmega_get_option( 'wpforms', 'htmega_thirdparty_element_tabs', 'on' );
        $revolution = htmega_get_option( 'revolution', 'htmega_thirdparty_element_tabs', 'on' );
        $tablepress = htmega_get_option( 'tablepress', 'htmega_thirdparty_element_tabs', 'on' );
        $wcaddtocart = htmega_get_option( 'wcaddtocart', 'htmega_thirdparty_element_tabs', 'on' );
        $categories = htmega_get_option( 'categories', 'htmega_thirdparty_element_tabs', 'on' );
        $wcpages = htmega_get_option( 'wcpages', 'htmega_thirdparty_element_tabs', 'on' );

        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_section_title.php' ) && $sectiontitle === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_section_title.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Section_Title() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_button.php' ) && $button === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_button.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Button() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_accordion.php' ) && $accordion === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_accordion.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Accordion() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_googlemap.php' ) && $googlemap === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_googlemap.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_GoogleMap() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_progressbar.php' ) && $progressbar === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_progressbar.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Progress_Bar() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_countdown.php' ) && $countdown === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_countdown.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Countdown() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_teammember.php' ) && $teammember === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_teammember.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_TeamMember() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_pricing_table.php' ) && $pricingtable === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_pricing_table.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Pricing_Table() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_brand.php' ) && $brandlogo === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_brand.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Brand() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_testimonial.php' ) && $testimonial === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_testimonial.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Testimonial() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_user_login_form.php' ) && $userloginform === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_user_login_form.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_User_Login_Form() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_user_register_form.php' ) && $userregisterform == 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_user_register_form.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_User_Register_Form() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_services.php' ) && $service === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_services.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Service() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_news_ticker.php' ) && $newtsicker === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_news_ticker.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Newsticker() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_socialshere.php' ) && $socialshere === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_socialshere.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_SocialShere() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_lightbox.php' ) && $lightbox === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_lightbox.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Lightbox() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_video_player.php' ) && $videoplayer === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_video_player.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_VideoPlayer() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_search.php' ) && $search === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_search.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Search() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_blockquote.php' ) && $blockquote === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_blockquote.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Blockquote() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_instagram.php' ) && $instagram === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_instagram.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Instagram() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_switcher.php' ) && $switcher === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_switcher.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Switcher() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tab.php' ) && $tabs === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tab.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Tabs() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_working_process.php' ) && $workingprocess === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_working_process.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Working_Process() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_modal.php' ) && $modal === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_modal.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Modal() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_flip-box.php' ) && $flipbox === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_flip-box.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Flip_Box() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tooltip.php' ) && $tooltip === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tooltip.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Tooltip() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_popover.php' ) && $popover === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_popover.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Popover() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_toggle.php' ) && $toggle === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_toggle.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Toggle() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_dropcaps.php' ) && $dropcaps === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_dropcaps.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Dropcaps() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_notify.php' ) && $notify === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_notify.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Notify() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_marker.php' ) && $imagemarker === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_marker.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_ImageMarker() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_inline_menu.php' ) && $inlinemenu === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_inline_menu.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_InlineMenu() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_404_content.php' ) && $errorcontent === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_404_content.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_ErrorContent() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_counter.php' ) && $counter === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_counter.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Counter() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_single_post.php' ) && $singlepost === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_single_post.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_SinglePost() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_grid.php' ) && $postgrid === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_grid.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_PostGrid() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_slider.php' ) && $postslider === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_slider.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Post_Slider() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_grid_tab.php' ) && $postgridtab === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_grid_tab.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Post_Grid_Tab() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_carousel.php' ) && $postcarousel === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_post_carousel.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Post_Carousel() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_grid.php' ) && $imagegrid === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_grid.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Image_Grid() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_masonry.php' ) && $imagemasonry === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_masonry.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Image_Masonry() );
        }

        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_gallery_justify.php' ) && $galleryjustify === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_gallery_justify.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Gallery_Justify() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_slider_thumb_gallery.php' ) && $thumbgallery === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_slider_thumb_gallery.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Slider_Thumb_Gallery() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_testimonial_grid.php' ) && $testimonialgrid === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_testimonial_grid.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Testimonial_Grid() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_scroll_navigation.php' ) && $scrollnavigation === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_scroll_navigation.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Scroll_Navigation() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_call_to_action.php' ) && $calltoaction === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_call_to_action.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Call_To_Action() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_twitter_feed.php' ) && $twitterfeed === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_twitter_feed.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Twitter_Feed() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_carousel.php' ) && $carousel === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_carousel.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Carousel() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_double_button.php' ) && $dualbutton === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_double_button.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Double_Button() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_add_banner.php' ) && $addbanner === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_add_banner.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Add_Banner() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_special_banner.php' ) && $specialadsbanner === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_special_banner.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Special_day_Banner() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_comparison.php' ) && $imagecomparison === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_comparison.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Image_Comparison() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_business_hours.php' ) && $businesshours === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_business_hours.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Business_Hours() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_table.php' ) && $datatable === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_table.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Data_Table() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_pricing_list_view.php' ) && $pricinglistview === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_pricing_list_view.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Pricing_List_View() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_vertical_timeline.php' ) && $verticletimeline === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_vertical_timeline.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Verticle_Time_Line() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_offcanvas.php' ) && $offcanvas === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_offcanvas.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Offcanvas() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_animated_heading.php' ) && $animatesectiontitle === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_animated_heading.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Animated_Heading() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_magnifier.php' ) && $imagemagnifier === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_image_magnifier.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Image_Magnifier() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_panel_slider.php' ) && $panelslider === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_panel_slider.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Panel_Slider() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_scroll_image.php' ) && $scrollimage === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_scroll_image.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Scroll_Image() );
        }
        
        if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_cuctom_event.php' ) && $customevent === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_cuctom_event.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Custom_Event() );
        }
        
        // Thirdparty plugins Addons
        if ( is_plugin_active('awesome-weather/awesome-weather.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_weather.php' ) && $weather === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_weather.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Weather() );
        }

        if ( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_mailchimp_for_wp.php' ) && $mailchimpwp === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_mailchimp_for_wp.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Mailchimp_Wp() );
        }
       
        if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_contact_form_seven.php' ) && $contactform === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_contact_form_seven.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Contact_Form_Seven() );
        }
       
        if ( is_plugin_active('booked/booked.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_booked_calender.php' ) && $bookedcalender === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_booked_calender.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Booked_Calender() );
        }
       
        if ( is_plugin_active('caldera-forms/caldera-core.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_caldera_forms.php' ) && $calderaform === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_caldera_forms.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Caldera_Form() );
        }
       
        if ( is_plugin_active('download-monitor/download-monitor.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_download_monitor.php' ) && $downloadmonitor === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_download_monitor.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Download_Monitor() );
        }
       
        if ( is_plugin_active('instagram-feed/instagram-feed.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_instagram_feed.php' ) && $instragramfeed === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_instagram_feed.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Instragram_Feed() );
        }
       
        if ( is_plugin_active('revslider/revslider.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_revolution_slider.php' ) && $revolution === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_revolution_slider.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Revolution_Slider() );
        }
       
        if ( is_plugin_active('bbpress/bbpress.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_bbpress.php' ) && $bbpress === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_bbpress.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Bbpress() );
        }
       
        if ( is_plugin_active('easy-digital-downloads/easy-digital-downloads.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_easy_digital_download.php' ) && $easydigitaldownload === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_easy_digital_download.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Easy_Digital_Download() );
        }
       
        if ( is_plugin_active('gravityforms/gravityforms.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_gravity_forms.php' ) && $gravityforms === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_gravity_forms.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Gravity_Forms() );
        }
       
        if ( is_plugin_active('tablepress/tablepress.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tablepress.php' ) && $tablepress === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_tablepress.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Tablepress() );
        }
       
        if ( is_plugin_active('LayerSlider/layerslider.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_layer_slider.php' ) && $layerslider === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_layer_slider.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Layer_Slider() );
        }
       
        if ( is_plugin_active('wpforms-lite/wpforms.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wpforms.php' ) && $wpforms === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wpforms.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_WPforms() );
        }
       
        if ( is_plugin_active('quform/quform.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_qu_forms.php' ) && $quforms === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_qu_forms.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_QUforms() );
        }
       
        if ( is_plugin_active('ninja-forms/ninja-forms.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_ninja_forms.php' ) && $ninjaform === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_ninja_forms.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Ninja_Form() );
        }
       
        if ( is_plugin_active('buddypress/bp-loader.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_buddy_press.php' ) && $buddypress === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_buddy_press.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Buddy_Press() );
        }
       
        if ( is_plugin_active('wp-job-manager/wp-job-manager.php') && file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_job_manager.php' ) && $jobmanager === 'on' ) {
            require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_job_manager.php';
            $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_Job_Manager() );
        }
       
        if( is_plugin_active('woocommerce/woocommerce.php') ) {

            if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_add_to_cart.php' ) && $wcaddtocart === 'on' ) {
                require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_add_to_cart.php';
                $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_WC_Add_to_Cart() );
            }

            if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_element_pages.php' ) && $wcpages === 'on' ) {
                require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_element_pages.php';
                $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_WC_Element_Pages() );
            }

            if ( file_exists( HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_categories.php' ) && $categories === 'on' ) {
                require_once HTMEGA_ADDONS_PL_PATH.'includes/widgets/htmega_wc_categories.php';
                $widgets_manager->register_widget_type( new \Elementor\HTMega_Elementor_Widget_WC_Categories() );
            }

        }
    }

}
HTMega_Widgets_Control::instance();