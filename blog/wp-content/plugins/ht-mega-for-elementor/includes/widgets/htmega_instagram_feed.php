<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Instragram_Feed extends Widget_Base {

    public function get_name() {
        return 'htmega-instragramfeed-addons';
    }
    
    public function get_title() {
        return __( 'Instragram Feed', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-photo-library';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'instragram_feed_content',
            [
                'label' => __( 'Instragram Feed', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'feed_limit',
                [
                    'label' => esc_html__( 'Feed Limit', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 8,
                ]
            );

            $this->add_control(
                'feed_cols',
                [
                    'label' => esc_html__( 'Number of Column', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 4,
                ]
            );

            $this->add_control(
                'feed_imageres_size',
                [
                    'label'   => esc_html__( 'Image Size', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'full',
                    'options' => [
                        'auto'   => esc_html__( 'Auto', 'htmega-addons' ),
                        'full'   => esc_html__( 'Full', 'htmega-addons' ),
                        'medium' => esc_html__( 'Medium', 'htmega-addons' ),
                        'thumb'  => esc_html__( 'Thumb', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'show_feed_header',
                [
                    'label'     => esc_html__( 'Show Header', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                    'label_off' => esc_html__( 'no', 'htmega-addons' ),
                    'label_on'  => esc_html__( 'yes', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'show_feed_button',
                [
                    'label'     => esc_html__( 'Loar More Button', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                    'label_off' => esc_html__( 'no', 'htmega-addons' ),
                    'label_on'  => esc_html__( 'yes', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'feed_button_text',
                [
                    'label'       => esc_html__( 'Button Text', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Load More', 'htmega-addons' ),
                    'label_block' => true,
                    'condition' => [
                        'show_feed_button' =>'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_feed_follow',
                [
                    'label'     => esc_html__( 'Show Follow Text', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                    'label_off' => esc_html__( 'no', 'htmega-addons' ),
                    'label_on'  => esc_html__( 'yes', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'follow_text',
                [
                    'label'       => esc_html__( 'Follow Text', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Follow on Instagram', 'htmega-addons' ),
                    'default'     => esc_html__( 'Follow on Instagram', 'htmega-addons' ),
                    'label_block' => true,
                    'condition' => [
                        'show_feed_follow' =>'yes',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'instragram_feed_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'imagepadding',
                [
                    'label' => esc_html__( 'Image Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 8,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 300,
                        ],
                    ],
                ]
            );

            $this->add_control(
                'headercolor',
                [
                    'label' => esc_html__( 'Header Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'condition' => [
                        'show_feed_header' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'buttoncolor',
                [
                    'label' => esc_html__( 'Button Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'condition' => [
                        'show_feed_button' =>'yes',
                    ],
                ]
            );

            $this->add_control(
                'buttontextcolor',
                [
                    'label' => esc_html__( 'Button Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'condition' => [
                        'show_feed_button' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'followcolor',
                [
                    'label' => esc_html__( 'Follow Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'condition' => [
                        'show_feed_follow' =>'yes',
                    ],
                ]
            );

            $this->add_control(
                'followtextcolor',
                [
                    'label' => esc_html__( 'Follow Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'condition' => [
                        'show_feed_follow' =>'yes',
                    ],
                ]
            );
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $instagram_attributes = [
            'num'              => $settings['feed_limit'],
            'cols'             => $settings['feed_cols'],
            'imageres'         => $settings['feed_imageres_size'],
            'imagepadding'     => $settings['imagepadding']['size'],
            'imagepaddingunit' =>'px',
            'showheader'       => ($settings['show_feed_header'] =='yes') ? 'true' : 'false',
            'showbutton'       => ($settings['show_feed_button'] =='yes') ? 'true' : 'false',
            'showfollow'       => ($settings['show_feed_follow'] =='yes') ? 'true' : 'false',
            'headercolor'      => $settings['headercolor'],
            'buttoncolor'      => $settings['buttoncolor'],
            'buttontextcolor'  => $settings['buttontextcolor'],
            'buttontext'       => $settings['feed_button_text'],
            'followcolor'      => $settings['followcolor'],
            'followtextcolor'  => $settings['followtextcolor'],
            'followtext'       => $settings['follow_text'],
        ];

        $this->add_render_attribute( 'shortcode', $instagram_attributes );

        echo do_shortcode( sprintf( '[instagram-feed %s]', $this->get_render_attribute_string( 'shortcode' ) ) );

    }

}

