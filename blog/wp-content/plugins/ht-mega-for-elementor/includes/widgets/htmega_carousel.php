<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Carousel extends Widget_Base {

    public function get_name() {
        return 'htmega-carousel-addons';
    }
    
    public function get_title() {
        return __( 'Carousel', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-slider-push';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'carosul_content',
            [
                'label' => __( 'Carousel', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'image_carosul_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                        '6'   => __( 'Style Six', 'htmega-addons' ),
                        '7'   => __( 'Style Seven', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'carosul_image_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Image Grid Title.','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'carosul_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'carosul_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'carosul_image_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [

                        [
                            'carosul_image_title'        => __('Image Grid Title','htmega-addons'),
                        ],

                    ],
                    'title_field' => '{{{ carosul_image_title }}}',
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label'         => __( 'Slider', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'On', 'htmega-addons' ),
                    'label_off'     => __( 'Off', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );
            
        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'carosul_slider_option',
            [
                'label' => __( 'Slider Option', 'htmega-addons' ),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => __( 'Slider Items', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 3,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => __( 'Slider Arrow', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slprevicon',
                [
                    'label' => __( 'Previous icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fa fa-angle-left',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slnexticon',
                [
                    'label' => __( 'Next icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fa fa-angle-right',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => __( 'Slider dots', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'htmega-addons'),
                    'label_on' => __('Yes', 'htmega-addons'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'htmega-addons'),
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcentermode',
                [
                    'label' => __( 'Center Mode', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcenterpadding',
                [
                    'label' => __( 'Center padding', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'default' => 50,
                    'condition' => [
                        'slider_on' => 'yes',
                        'slcentermode' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => __( 'Slider auto play', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'htmega-addons'),
                    'description' => __('The resolution to tablet.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'htmega-addons'),
                    'description' => __('The resolution to mobile.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Slider Option end
        
        // Style instagram arrow style start
        $this->start_controls_section(
            'htmega_carousel_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'carousel_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'carousel_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_carousel_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_carousel_arrow_fontsize',
                        [
                            'label' => __( 'Font Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'carousel_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_carousel_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_carousel_arrow_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_carousel_arrow_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_carousel_arrow_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_carousel_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'carousel_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_carousel_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'carousel_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_carousel_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_carousel_arrow_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram arrow style end


        // Style instagram Dots style start
        $this->start_controls_section(
            'htmega_carousel_dots_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'carousel_dots_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'carousel_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'carousel_dots_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_carousel_dots_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_carousel_dots_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_carousel_dots_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_carousel_dots_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'width: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'carousel_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'carousel_dots_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_carousel_dots_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_carousel_dots_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram dots style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();


        // Carousel Attribute
        $this->add_render_attribute( 'htmega_carosul_attr', 'class', 'ht-custom-carousel' );
        if( $settings['slider_on'] == 'yes' ){
            $this->add_render_attribute( 'htmega_carosul_attr', 'class', 'htmega-carousel-activation htmega-carousel-style-'.$settings['image_carosul_style'] );

            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
                'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'center_mode' => ( 'yes' === $settings['slcentermode']),
                'center_padding' => $settings['slcenterpadding'].'px',
            ];

            if( $settings['image_carosul_style'] == 6 ){
                $slider_settings['carousel_style_ck'] = 4;
            }

            $slider_responsive_settings = [
                'display_columns' => $settings['slitems'],
                'scroll_columns' => $settings['slscroll_columns'],
                'tablet_width' => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
                'mobile_width' => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

            ];

            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );

            $this->add_render_attribute( 'htmega_carosul_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }
       
        ?>

       <div <?php echo $this->get_render_attribute_string('htmega_carosul_attr'); ?> >

            <?php foreach ( $settings['carosul_image_list'] as $imagecarosul ): ?>
                <div class="single-custom-carousel htmega-data-title" data-title="<?php echo esc_attr__( $imagecarosul['carosul_image_title'],'htmega-addons' ); ?>">
                    <?php 
                        echo Group_Control_Image_Size::get_attachment_image_html( $imagecarosul, 'carosul_imagesize', 'carosul_image' );
                    ?>
                </div>
            <?php endforeach;?>

        </div>

        <?php

    }

}

