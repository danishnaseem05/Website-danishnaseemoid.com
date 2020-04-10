<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

if ( !class_exists( 'HTMega_Elementor_Addons_Init' ) ) {

    class HTMega_Elementor_Addons_Init{

        private static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            add_action( 'init', [ $this, 'after_setup_theme' ] );
            // All Scripts
            add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );
            add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_frontend_styles' ], 10 );
            add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_fronted_scripts' ], 10 );
            add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ], 10 );
            add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ], 10 );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        }

        // Enqueue Scripts
        public function enqueue_scripts(){
            wp_enqueue_style( 'htbbootstrap' );
            wp_enqueue_style('htmega-widgets', HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-widgets.css');
        }

        // Add Image size
        public function after_setup_theme() {
            add_image_size( 'htmega_size_585x295', 585, 295, true );
            add_image_size( 'htmega_size_1170x536', 1170, 536, true );
            add_image_size( 'htmega_size_396x360', 396, 360, true );
        }

        // Editor Scripts
        public function editor_scripts() {
            wp_enqueue_style('htmega-element-editor', HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-elementor-editor.css', '', HTMEGA_VERSION );
        }

        // Register frontend style
        public function register_frontend_styles(){
            
            wp_register_style(
                'htbbootstrap',
                HTMEGA_ADDONS_PL_URL . 'assets/css/htbbootstrap.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'htmega-widgets',
                HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-widgets.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'htmega-animation',
                HTMEGA_ADDONS_PL_URL . 'assets/css/animation.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'slick',
                HTMEGA_ADDONS_PL_URL . 'assets/css/slick.min.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'magnific-popup',
                HTMEGA_ADDONS_PL_URL . 'assets/css/magnific-popup.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'ytplayer',
                HTMEGA_ADDONS_PL_URL . 'assets/css/jquery.mb.YTPlayer.min.css',
                array(),
                HTMEGA_VERSION
            );

            wp_register_style(
                'swiper',
                HTMEGA_ADDONS_PL_URL . 'assets/css/swiper.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'compare-image',
                HTMEGA_ADDONS_PL_URL . 'assets/css/compare-image.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'justify-gallery',
                HTMEGA_ADDONS_PL_URL . 'assets/css/justify-gallery.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'datatables',
                HTMEGA_ADDONS_PL_URL . 'assets/css/datatables.min.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'magnifier',
                HTMEGA_ADDONS_PL_URL . 'assets/css/magnifier.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'animated-heading',
                HTMEGA_ADDONS_PL_URL . 'assets/css/animated-text.css',
                array('htmega-widgets'),
                HTMEGA_VERSION
            );

            wp_register_style(
                'htmega-keyframes',
                HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-keyframes.css',
                array(),
                HTMEGA_VERSION
            );

        }

        // Register frontend script
        public function register_fronted_scripts(){

            $google_map_api_key = htmega_get_option('google_map_api_key','htmega_general_tabs');
            
            wp_register_script(
                'htbbootstrap',
                HTMEGA_ADDONS_PL_URL . 'assets/js/htbbootstrap.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );

            wp_register_script(
                'htmega-popper',
                HTMEGA_ADDONS_PL_URL . 'assets/js/popper.min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
            
            wp_register_script(
                'htmega-widgets-scripts',
                HTMEGA_ADDONS_PL_URL . 'assets/js/htmega-widgets-active.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );

            wp_register_script(
                'slick',
                HTMEGA_ADDONS_PL_URL . 'assets/js/slick.min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
            
            wp_register_script(
                'magnific-popup',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.magnific-popup.min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
            
            wp_register_script(
                'beerslider',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-beerslider-min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );
            
            wp_register_script(
                'ytplayer',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mb.YTPlayer.min.js',
                array('jquery'),
                HTMEGA_VERSION,
                TRUE
            );

            wp_register_script(
                'mapmarker',
                HTMEGA_ADDONS_PL_URL . 'assets/js/mapmarker.jquery.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'jquery-easing',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.easing.1.3.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'jquery-mousewheel',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mousewheel.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'vaccordion',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.vaccordion.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'easy-pie-chart',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-easy-pie-chart.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'htmega-countdown',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-countdown.min.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'htmega-newsticker',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-newsticker-min.js',
                array('jquery'),
                NULL,
                TRUE
            );
            
            wp_register_script(
                'htmega-goodshare',
                HTMEGA_ADDONS_PL_URL . 'assets/js/goodshare.min.js',
                array('jquery'),
                NULL,
                TRUE
            );
            
            wp_register_script(
                'htmega-notify',
                HTMEGA_ADDONS_PL_URL . 'assets/js/notify.min.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'counterup',
                HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.counterup.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'isotope',
                HTMEGA_ADDONS_PL_URL . 'assets/js/isotope.pkgd.min.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'swiper',
                HTMEGA_ADDONS_PL_URL . 'assets/js/swiper.min.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'justified-gallery',
                HTMEGA_ADDONS_PL_URL . 'assets/js/justified-gallery.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'datatables',
                HTMEGA_ADDONS_PL_URL . 'assets/js/datatables.min.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'magnifier',
                HTMEGA_ADDONS_PL_URL . 'assets/js/magnifier.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'animated-heading',
                HTMEGA_ADDONS_PL_URL . 'assets/js/animated-heading.js',
                array('jquery'),
                NULL,
                TRUE
            );

            wp_register_script(
                'waypoints',
                HTMEGA_ADDONS_PL_URL . 'assets/js/waypoints.js',
                array('jquery'),
                '4.0.1',
                TRUE
            );

            if( !empty( $google_map_api_key ) ){
                wp_register_script(
                    'google-map-api',
                    'https://maps.googleapis.com/maps/api/js?key='.$google_map_api_key,
                    array('jquery'),
                    NULL,
                    TRUE
                );
            }else{
                wp_register_script(
                    'google-map-api',
                    'http://maps.googleapis.com/maps/api/js?sensor=false',
                    array('jquery'),
                    NULL,
                    TRUE
                );
            }
            
        }

        // enqueue frontend style
        public function enqueue_frontend_styles(){

            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'htmega-animation' );
            wp_enqueue_style( 'slick' );
            wp_enqueue_style( 'compare-image' );
            wp_enqueue_style( 'justify-gallery' );
            wp_enqueue_style( 'htmega-keyframes' );
            wp_enqueue_style( 'animated-heading' );
            wp_enqueue_style( 'htmega-widgets' );

        }

        // enqueue frontend scripts
        public function enqueue_frontend_scripts(){
            
            wp_enqueue_script( 'htmega-popper' );
            wp_enqueue_script( 'htbbootstrap' );
            wp_enqueue_script( 'waypoints' );

        }

    }

    HTMega_Elementor_Addons_Init::instance();

}