<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Flip_Box extends Widget_Base {

    public function get_name() {
        return 'htmega-flipbox-addons';
    }
    
    public function get_title() {
        return __( 'Flip Box', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-flip-box';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        // Layout Content area Start
        $this->start_controls_section(
            'flipbox_content_layout',
            [
                'label' => __( 'Layout', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'flipbox_layout',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Front Content area Start
        $this->start_controls_section(
            'flipbox_content_front',
            [
                'label' => __( 'Front', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'flipbox_content_type',
                [
                    'label'   => __( 'Content Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'htmega-addons' ),
                            'icon'  => 'fa fa-ban',
                        ],
                        'number' => [
                            'title' => __( 'Number', 'htmega-addons' ),
                            'icon'  => 'fa fa-sort-numeric-asc',
                        ],
                        'image' => [
                            'title' => __( 'Image', 'htmega-addons' ),
                            'icon'  => 'fa fa-picture-o',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'htmega-addons' ),
                            'icon'  => 'fa fa-info-circle',
                        ],
                    ],
                    'default' => 'number',
                ]
            );

            $this->add_control(
                'flipbox_front_title',
                [
                    'label'         => __( 'Title', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Flip Box Heading', 'htmega-addons' ),
                    'placeholder'   => __( 'Type your title here', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'flipbox_front_number',
                [
                    'label'         => __( 'Number', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( '01', 'htmega-addons' ),
                    'condition'=>[
                        'flipbox_content_type'=>'number',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_front_icon',
                [
                    'label'         => __( 'Icon', 'htmega-addons' ),
                    'type'          => Controls_Manager::ICONS,
                    'condition'=>[
                        'flipbox_content_type'=>'icon',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_front_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'flipbox_content_type' => 'image',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'flipboximagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'flipbox_content_type' => 'image',
                    ]
                ]
            );

        $this->end_controls_section(); // Front Content area end

        // Back Content area Start
        $this->start_controls_section(
            'flipbox_content_back',
            [
                'label' => __( 'Back', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'flipbox_back_content_type',
                [
                    'label'   => __( 'Content Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'htmega-addons' ),
                            'icon'  => 'fa fa-ban',
                        ],
                        'number' => [
                            'title' => __( 'Number', 'htmega-addons' ),
                            'icon'  => 'fa fa-sort-numeric-asc',
                        ],
                        'image' => [
                            'title' => __( 'Image', 'htmega-addons' ),
                            'icon'  => 'fa fa-picture-o',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'htmega-addons' ),
                            'icon'  => 'fa fa-info-circle',
                        ],
                    ],
                    'default' => 'number',
                ]
            );

            $this->add_control(
                'flipbox_back_title',
                [
                    'label'         => __( 'Title', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'Flip Box Back Heading', 'htmega-addons' ),
                    'placeholder'   => __( 'Type your title here', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'flipbox_back_number',
                [
                    'label'         => __( 'Number', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( '01', 'htmega-addons' ),
                    'condition'=>[
                        'flipbox_back_content_type'=>'number',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_back_icon',
                [
                    'label'         => __( 'Icon', 'htmega-addons' ),
                    'type'          => Controls_Manager::ICONS,
                    'condition'=>[
                        'flipbox_back_content_type'=>'icon',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_back_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'flipbox_back_content_type' => 'image',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'flipboxbackimagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'flipbox_back_content_type' => 'image',
                    ]
                ]
            );

            $this->add_control(
                'flipbox_back_description',
                [
                    'label'         => __( 'Description', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXTAREA,
                    'default'       => __( 'There are many variations of passages Lorem Ipsum available, but the majority hav suffered alteration in.', 'htmega-addons' ),
                    'placeholder'   => __( 'Description', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'flipbox_button',
                [
                    'label' => __( 'Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                ]
            );

            $this->add_control(
                'flipbox_button_link',
                [
                    'label' => __( 'Button Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'condition'=>[
                        'flipbox_button!'=>'',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style Content area Start
        $this->start_controls_section(
            'flipbox_options',
            [
                'label' => __( 'Aditional Options', 'htmega-addons' ),
            ]
        );

            $this->add_responsive_control(
                'flipbox_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1000,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'size_units' => [ 'px', 'vh' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-area' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_animation',
                [
                    'label' => __( 'Animation', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'right',
                    'options' => [
                        'top'   => __( 'Top', 'htmega-addons' ),
                        'bottom'   => __( 'Bottom', 'htmega-addons' ),
                        'left'   => __( 'Left', 'htmega-addons' ),
                        'right'   => __( 'Right', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'flipbox_front_style_section',
            [
                'label' => __( 'Front', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'flipbox_front_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-front .front-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'flipbox_front_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flip-box-front .front-container',
                ]
            );

            $this->add_control(
                'flipbox_front_background_overlay',
                [
                    'label'     => __( 'Background Overlay', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-front .htmega-flip-overlay' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                    'condition' => [
                        'flipbox_front_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_front_background_opacity',
                [
                    'label'   => __( 'Opacity (%)', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'max'  => 1,
                            'min'  => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-front .htmega-flip-overlay' => 'opacity: {{SIZE}};',
                    ],
                    'condition' => [
                        'flipbox_front_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'flipbox_front_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-flip-box-front',
                ]
            );

            $this->add_responsive_control(
                'flipbox_front_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-front .front-container' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            // Content Style tab
            $this->add_control(
                'flipbox_style_tab_heading',
                [
                    'label' => __( 'Content Style Tabs', 'plugin-name' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs('flipbox_front_style_tabs');
                
                // Title style start
                $this->start_controls_tab(
                    'flipbox_front_style_title_tab',
                    [
                        'label' => __( 'Title', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'flipbox_front_title_color',
                        [
                            'label' => __( 'Title Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#4a4a4a',
                            'selectors' => [
                                '{{WRAPPER}} .front-container h2' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'flipbox_front_title_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .front-container h2',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_title_padding',
                        [
                            'label' => __( 'Title Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_title_margin',
                        [
                            'label' => __( 'Title Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_front_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .front-container h2',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .front-container h2' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Title style end

                 // Icon style tab start
                $this->start_controls_tab(
                    'flipbox_front_style_icon_tab',
                    [
                        'label' => __( 'Icon', 'htmega-addons' ),
                        'condition'=>[
                            'flipbox_content_type'=>'icon',
                            'flipbox_front_icon[value]!'=>'',
                        ]
                    ]
                );
                    
                    $this->add_control(
                        'flipbox_front_icon_color',
                        [
                            'label' => __( 'Icon Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#4a4a4a',
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .front-container span.flipbox-icon svg' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_front_icon_fontsize',
                        [
                            'label' => __( 'Icon Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 70,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container span i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_front_icon_background_color',
                        [
                            'label' => __( 'Icon Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ff7a5a',
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_front_icon_width',
                        [
                            'label' => __( 'Icon Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 500,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator'=>'before',
                        ]
                    );

                    $this->add_control(
                        'flipbox_front_icon_height',
                        [
                            'label' => __( 'Icon Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 500,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_icon_padding',
                        [
                            'label' => __( 'Icon Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_icon_margin',
                        [
                            'label' => __( 'Icon Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_front_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .front-container span.flipbox-icon',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .front-container span.flipbox-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Icon style tab end

                 // Number style tab start
                $this->start_controls_tab(
                    'flipbox_front_style_number_tab',
                    [
                        'label' => __( 'Number', 'htmega-addons' ),
                        'condition'=>[
                            'flipbox_content_type'=>'number',
                            'flipbox_front_number!'=>'',
                        ]
                    ]
                );
                    
                    $this->add_control(
                        'flipbox_front_number_color',
                        [
                            'label' => __( 'Number Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#4a4a4a',
                            'selectors' => [
                                '{{WRAPPER}} .front-container .flipbox-number' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'flipbox_front_number_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .front-container .flipbox-number',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_number_padding',
                        [
                            'label' => __( 'Number Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container .flipbox-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_number_margin',
                        [
                            'label' => __( 'Number Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .front-container .flipbox-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_front_number_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .front-container .flipbox-number',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_front_number_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .front-container .flipbox-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Number style tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'flipbox_back_style_section',
            [
                'label' => __( 'Back', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'flipbox_back_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-back .back-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'flipbox_back_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flip-box-back',
                ]
            );

            $this->add_control(
                'flipbox_back_background_overlay',
                [
                    'label'     => __( 'Background Overlay', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-back .htmega-flip-overlay' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                    'condition' => [
                        'flipbox_back_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'flipbox_back_background_opacity',
                [
                    'label'   => __( 'Opacity (%)', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'max'  => 1,
                            'min'  => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-back .htmega-flip-overlay' => 'opacity: {{SIZE}};',
                    ],
                    'condition' => [
                        'flipbox_back_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'flipbox_back_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-flip-box-back',
                ]
            );

            $this->add_responsive_control(
                'flipbox_back_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-flip-box-back .back-container' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );


            // Content Style tab
            $this->add_control(
                'flipbox_back_style_tab_heading',
                [
                    'label' => __( 'Content Style Tabs', 'plugin-name' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs('flipbox_back_style_tabs');
                
                // Title style start
                $this->start_controls_tab(
                    'flipbox_back_style_title_tab',
                    [
                        'label' => __( 'Title', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'flipbox_back_title_color',
                        [
                            'label' => __( 'Title Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .back-container h2' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'flipbox_back_title_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .back-container h2',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_title_padding',
                        [
                            'label' => __( 'Title Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_title_margin',
                        [
                            'label' => __( 'Title Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_back_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .back-container h2',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .back-container h2' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Title style end

                // Description style start
                $this->start_controls_tab(
                    'flipbox_back_style_description_tab',
                    [
                        'label' => __( 'Description', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'flipbox_back_description_color',
                        [
                            'label' => __( 'Description Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .back-container p' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'flipbox_back_description_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .back-container p',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_description_padding',
                        [
                            'label' => __( 'Description Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_description_margin',
                        [
                            'label' => __( 'Description Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    

                $this->end_controls_tab(); // Title style end

                 // Icon style tab start
                $this->start_controls_tab(
                    'flipbox_back_style_icon_tab',
                    [
                        'label' => __( 'Icon', 'htmega-addons' ),
                        'condition'=>[
                            'flipbox_content_type'=>'icon',
                            'flipbox_back_icon[value]!'=>'',
                        ]
                    ]
                );
                    
                    $this->add_control(
                        'flipbox_back_icon_color',
                        [
                            'label' => __( 'Icon Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#4a4a4a',
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .back-container span.flipbox-icon svg' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_back_icon_fontsize',
                        [
                            'label' => __( 'Icon Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 70,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_back_icon_background_color',
                        [
                            'label' => __( 'Icon Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ff7a5a',
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'flipbox_back_icon_width',
                        [
                            'label' => __( 'Icon Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 500,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator'=>'before',
                        ]
                    );

                    $this->add_control(
                        'flipbox_back_icon_height',
                        [
                            'label' => __( 'Icon Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 500,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_icon_padding',
                        [
                            'label' => __( 'Icon Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_icon_margin',
                        [
                            'label' => __( 'Icon Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'after',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_back_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .back-container span.flipbox-icon',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .back-container span.flipbox-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Icon style tab end

                 // Number style tab start
                $this->start_controls_tab(
                    'flipbox_back_style_number_tab',
                    [
                        'label' => __( 'Number', 'htmega-addons' ),
                        'condition'=>[
                            'flipbox_content_type'=>'number',
                            'flipbox_back_number!'=>'',
                        ]
                    ]
                );
                    
                    $this->add_control(
                        'flipbox_back_number_color',
                        [
                            'label' => __( 'Number Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .back-container .flipbox-number' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'flipbox_back_number_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .back-container .flipbox-number',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_number_padding',
                        [
                            'label' => __( 'Number Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container .flipbox-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_number_margin',
                        [
                            'label' => __( 'Number Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .back-container .flipbox-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'flipbox_back_number_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .back-container .flipbox-number',
                        ]
                    );

                    $this->add_responsive_control(
                        'flipbox_back_number_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .back-container .flipbox-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Number style tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_flipbox_attr', 'class', 'htmega-flip-box-area' );
        $this->add_render_attribute( 'htmega_flipbox_attr', 'class', 'htmega-flip-box-style-'.$settings['flipbox_layout'] );
        $this->add_render_attribute( 'htmega_flipbox_attr', 'class', 'htmega-flip-box-animation-'.$settings['flipbox_animation'] );

        if ( isset(  $settings['flipbox_button_link']['url'] ) && ! empty( $settings['flipbox_button_link']['url'] ) ) {
            $this->add_render_attribute( 'url', 'href', $settings['flipbox_button_link']['url'] );

            if ( $settings['flipbox_button_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['flipbox_button_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }

            $flbbutton = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['flipbox_button'] );
        }
       
        ?>
            
            <div <?php echo $this->get_render_attribute_string( 'htmega_flipbox_attr' ); ?>>

                <div class='htmega-flip-box-front'>
                    <div class="front-container">
                        <?php
                            if( !empty( $settings['flipbox_front_number'] ) ){
                                echo '<span class="flipbox-number">'.esc_html__( $settings['flipbox_front_number'], 'htmega-addons' ).'</span>';
                            }
                            if( !empty( $settings['flipbox_front_icon']['value'] ) ){
                                echo '<span class="flipbox-icon">'.HTMega_Icon_manager::render_icon( $settings['flipbox_front_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';
                            }
                            if( !empty( $settings['flipbox_front_image']['url'] ) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'flipboximagesize', 'flipbox_front_image' );
                            }
                            if( !empty( $settings['flipbox_front_title'] ) ){
                                echo '<h2>'.esc_html__( $settings['flipbox_front_title'], 'htmega-addons' ).'</h2>';
                            }
                        ?>
                    </div>
                    <div class="htmega-flip-overlay"></div>
                </div>

                <div class='htmega-flip-box-back'>
                    <div class="back-container">
                        <?php
                            if( !empty( $settings['flipbox_back_number'] ) ){
                                echo '<span class="flipbox-number">'.esc_html__( $settings['flipbox_back_number'], 'htmega-addons' ).'</span>';
                            }
                            if( !empty( $settings['flipbox_back_icon']['value'] ) ){
                                echo '<span class="flipbox-icon">'.HTMega_Icon_manager::render_icon( $settings['flipbox_back_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';
                            }
                            if( !empty( $settings['flipbox_back_image']['url'] ) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'flipboxbackimagesize', 'flipbox_back_image' );
                            }
                            if( !empty( $settings['flipbox_back_title'] ) ){
                                echo '<h2>'.esc_html__( $settings['flipbox_back_title'], 'htmega-addons' ).'</h2>';
                            }
                            if( !empty( $settings['flipbox_back_description'] ) ){
                                echo '<p>'.esc_html__( $settings['flipbox_back_description'], 'htmega-addons' ).'</p>';
                            }
                            if( !empty( $settings['flipbox_button'] ) ){
                                echo '<div class="flp-btn">'.$flbbutton.'</div>';
                            }
                        ?>
                    </div>
                    <div class="htmega-flip-overlay"></div>
                </div>

            </div>

        <?php

    }

}