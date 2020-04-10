<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_User_Login_Form extends Widget_Base {

    public function get_name() {
        return 'htmega-userlogin-form-addons';
    }
    
    public function get_title() {
        return __( 'User Login Form', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-lock-user';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'user_login_form_content',
            [
                'label' => __( 'Login Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_loginform_style',
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

            $this->add_control(
                'htmega_form_show_label',
                [
                    'label' => esc_html__( 'Label', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_off' => esc_html__( 'Hide', 'htmega-addons' ),
                    'label_on' => esc_html__( 'Show', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_form_show_customlabel',
                [
                    'label' => esc_html__( 'Custom label', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'label_off' => esc_html__( 'Hide', 'htmega-addons' ),
                    'label_on' => esc_html__( 'Show', 'htmega-addons' ),
                    'condition' =>[
                        'htmega_form_show_label' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'htmega_user_label',
                    [
                    'label'     => esc_html__( 'Username Label', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( 'Username or Email', 'htmega-addons' ),
                    'condition' => [
                        'htmega_form_show_label'   => 'yes',
                        'htmega_form_show_customlabel' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'htmega_user_placeholder',
                [
                    'label'     => esc_html__( 'Username Placeholder', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( 'Username or Email', 'htmega-addons' ),
                    'condition' => [
                        'htmega_form_show_label'   => 'yes',
                        'htmega_form_show_customlabel' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'htmega_password_label',
                [
                    'label'     => esc_html__( 'Password Label', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => esc_html__( 'Password', 'htmega-addons' ),
                    'condition' => [
                        'htmega_form_show_label'   => 'yes',
                        'htmega_form_show_customlabel' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'htmega_password_placeholder',
                [
                    'label'     => __( 'Password Placeholder', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Password', 'htmega-addons' ),
                    'condition' => [
                        'htmega_form_show_label'   => 'yes',
                        'htmega_form_show_customlabel' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'redirect_page',
                [
                    'label' => __( 'Redirect page after Login', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'redirect_page_url',
                [
                    'type'          => Controls_Manager::URL,
                    'show_label'    => false,
                    'show_external' => false,
                    'separator'     => false,
                    'placeholder'   => 'http://your-link.com/',
                    'condition'     => [
                        'redirect_page' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'lost_password',
                [
                    'label'     => esc_html__( 'Lost your password?', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'label_off' => esc_html__( 'Hide', 'htmega-addons' ),
                    'label_on'  => esc_html__( 'Show', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'remember_me',
                [
                    'label'     => esc_html__( 'Remember Me', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'label_off' => esc_html__( 'Hide', 'htmega-addons' ),
                    'label_on'  => esc_html__( 'Show', 'htmega-addons' ),
                ]
            );

            if ( get_option( 'users_can_register' ) ) {
                $this->add_control(
                    'register_link',
                    [
                        'label'     => esc_html__( 'Register', 'htmega-addons' ),
                        'type'      => Controls_Manager::SWITCHER,
                        'default'   => 'no',
                        'label_off' => esc_html__( 'Hide', 'htmega-addons' ),
                        'label_on'  => esc_html__( 'Show', 'htmega-addons' ),
                    ]
                );

                $this->add_control(
                    'register_link_text',
                    [
                        'label' => __( 'Register Link Text', 'htmega-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Register', 'htmega-addons' ),
                        'condition'     => [
                            'register_link' => 'yes',
                        ],
                    ]
                );
            }

            $this->add_control(
                'login_button_heading',
                [
                    'label' => __( 'Login Button', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'login_button_text',
                [
                    'label' => __( 'Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Login', 'htmega-addons' ),
                ]
            );

            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_login_form_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'login_form_style_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'login_form_section_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'login_form_section_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'login_form_style_input',
            [
                'label' => __( 'Input', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'login_form_input_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'login_form_input_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="text"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="text"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="text"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                         '{{WRAPPER}} .htmega-login-form-wrapper input[type*="password"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="password"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="password"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                         '{{WRAPPER}} .htmega-login-form-wrapper input[type*="email"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="email"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper input[type*="email"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'login_form_input_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'login_form_input_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input',
                ]
            );

            $this->add_responsive_control(
                'login_form_input_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_input_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'login_form_input_height',
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
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'login_form_input_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper input' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'login_form_style_submit_button',
            [
                'label' => __( 'Submit Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            // Button Tabs Start
            $this->start_controls_tabs('login_form_style_submit_tabs');

                // Start Normal Submit button tab
                $this->start_controls_tab(
                    'login_form_style_submit_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'login_form_submitbutton_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'login_form_submitbutton_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'login_form_submitbutton_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'login_form_submitbutton_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'login_form_submitbutton_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'login_form_submitbutton_height',
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
                                'size' => 50,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'login_form_submitbutton_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'login_form_submitbutton_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal submit Button tab end

                // Start Hover Submit button tab
                $this->start_controls_tab(
                    'login_form_style_submit_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'login_form_submitbutton_hover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]:hover'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'login_form_submitbutton_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'login_form_submitbutton_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]:hover',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'login_form_submitbutton_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-login-form-wrapper input[type="submit"]:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Submit Button tab End

            $this->end_controls_tabs(); // Button Tabs End

        $this->end_controls_section();

        // Label Style Start
        $this->start_controls_section(
            'login_form_style_label',
            [
                'label' => __( 'Label', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'login_form_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper label'   => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-login-form-wrapper .login_register_text'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'login_form_label_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper label,{{WRAPPER}} .htmega-login-form-wrapper .login_register_text',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'login_form_label_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper label',
                ]
            );

            $this->add_responsive_control(
                'login_form_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'login_form_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-login-form-wrapper label',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'login_form_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'login_form_label_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-login-form-wrapper label' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $current_url = remove_query_arg( 'fake_arg' );
        $id = $this->get_id();

        if ( $settings['redirect_page'] == 'yes' && ! empty( $settings['redirect_page_url']['url'] ) ) {
            $redirect_url = $settings['redirect_page_url']['url'];
        } else {
            $redirect_url = $current_url;
        }

        $this->add_render_attribute( 'loginform_area_attr', 'class', 'htmega-login-form-wrapper' );
        $this->add_render_attribute( 'loginform_area_attr', 'class', 'htmega-login-form-style-'.$settings['htmega_loginform_style'] );

        // Label Value
        $user_label = isset( $settings['htmega_user_label'] ) ? $settings['htmega_user_label'] : __('Username','htmega-addons');
        $user_placeholder = isset( $settings['htmega_user_placeholder'] ) ? $settings['htmega_user_placeholder'] : __('Username','htmega-addons');
        $pass_label = isset( $settings['htmega_password_label'] ) ? $settings['htmega_password_label'] : __('Password','htmega-addons');
        $pass_placeholder = isset( $settings['htmega_password_placeholder'] ) ? $settings['htmega_password_placeholder'] : __('Password','htmega-addons');
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'loginform_area_attr' ); ?> >

                <div id="htmega_message_<?php echo esc_attr( $id ); ?>" class="htmega_message">&nbsp;</div>

                <?php
                    if ( is_user_logged_in() && !Plugin::instance()->editor->is_edit_mode() ) {
                        $current_user = wp_get_current_user();
                        echo '<div class="htmega-user-login">' .
                            sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'htmega-addons' ), $current_user->display_name, wp_logout_url( $current_url ) ) .
                            '</div>';
                        return;
                    }
                ?>

                <form id="htmega_login_form_<?php echo esc_attr( $id ); ?>" action="formloginaction" method="post">

                    <div class="htb-row">

                        <div class="htb-col-lg-12">
                            <?php
                                if( $settings['htmega_form_show_label'] == 'yes'){
                                    echo sprintf('<label for="%1$s">%1$s</label>', $user_label );
                                }
                            ?>
                            <input 
                                type="text"  
                                id="login_username<?php echo esc_attr( $id ); ?>" 
                                name="login_username" 
                                placeholder="<?php echo esc_attr__( $user_placeholder,'htmega-addons' );?>">
                        </div>

                        <div class="htb-col-lg-12">
                            <?php
                                if( $settings['htmega_form_show_label'] == 'yes'){
                                    echo sprintf('<label for="%1$s">%1$s</label>', $pass_label );
                                }
                            ?>
                            <input 
                                type="password" 
                                id="login_password<?php echo esc_attr( $id ); ?>" 
                                name="login_password" 
                                placeholder="<?php echo esc_attr__( $pass_placeholder,'htmega-addons' );?>">
                        </div>

                        <div class="htb-col-lg-12">
                            <div class="log-remember">
                                <?php if( $settings['remember_me'] == 'yes' ): ?>
                                    <label class="lable-content"><?php esc_html_e('Remember Me','htmega-addons'); ?>
                                        <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                                        <span class="checkmark"></span>
                                    </label>
                                <?php endif; if( $settings['lost_password'] == 'yes' ): ?>
                                    <a href="<?php echo wp_lostpassword_url( $current_url ); ?>" class="fright"><?php esc_html_e('Forgot Password?','htmega-addons'); ?></a>
                                <?php endif;?>
                            </div>
                        </div>

                        <div class="htb-col-lg-12">
                            <input 
                                type="submit" 
                                id="login_form_submit_<?php echo esc_attr__( $id, 'htmega-addons'); ?>" 
                                name="login_form_submit<?php echo $id; ?>" 
                                value="<?php if( !empty( $settings['login_button_text'] ) ){ echo esc_attr__( $settings['login_button_text'], 'htmega-addons'); } else { esc_html_e( 'Login', 'htmega-addons' ); } ?>">

                            <?php if( get_option( 'users_can_register' ) && $settings['register_link'] == 'yes' ): ?>
                                <a href="<?php echo wp_registration_url(); ?>" class="login_register_text">
                                    <?php if( !empty( $settings['register_link_text'] ) ){ echo esc_attr__( $settings['register_link_text'], 'htmega-addons'); } else { esc_html_e( 'Register', 'htmega-addons' ); } ?>
                                </a>
                            <?php endif;?>
                        </div>

                    </div>

                    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>

                </form>

            </div>

        <?php

        $this->htmega_login_check( $settings['redirect_page'], $redirect_url, $id );

    }

    public function htmega_login_check( $reddirectstatus, $redirect_url, $id ) {

        ?>

        <script type="text/javascript">

            jQuery(document).ready(function($) {
                "use strict";

                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                var loadingmessage = '<?php echo esc_html__('Please wait...','htmega-addons'); ?>';
                var login_form_id = 'form#htmega_login_form_<?php echo esc_attr( $id ); ?>';
                var login_button_id = '#login_form_submit_<?php echo esc_attr( $id ); ?>';
                var redirect = '<?php echo $reddirectstatus; ?>';

                $( login_button_id ).on('click', function(){

                    $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<span class="htmega_lodding_msg">'+ loadingmessage +'</span>').fadeIn();

                    $.ajax({  
                        type: 'POST',
                        dataType: 'json',  
                        url:  ajaxurl,  
                        data: { 
                            'action': 'htmega_ajax_login',
                            'username': $( login_form_id + ' #login_username<?php echo esc_attr( $id ); ?>').val(), 
                            'password': $( login_form_id + ' #login_password<?php echo esc_attr( $id ); ?>').val(), 
                            'security': $( login_form_id + ' #security').val()
                        },
                        success: function(msg){
                            if ( msg.loggeauth == true ){
                                $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<div class="htmega_success_msg alert alert-success">'+ msg.message +'</div>').fadeIn();
                                if( redirect === 'yes' ){
                                    document.location.href = '<?php echo esc_url( $redirect_url ); ?>';
                                }
                            }else{
                                $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<div class="htmega_invalid_msg alert alert-danger">'+ msg.message +'</div>').fadeIn();
                            }
                        }  
                    });

                    return false;
                  
                });

            });

        </script>

        <?php

    }
    
}

