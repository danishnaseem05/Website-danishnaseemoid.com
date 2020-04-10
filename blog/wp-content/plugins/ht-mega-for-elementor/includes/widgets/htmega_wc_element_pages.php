<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_WC_Element_Pages extends Widget_Base {

    public function get_name() {
        return 'htmega-wcpages-addons';
    }
    
    public function get_title() {
        return __( 'WC : Pages', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-product-pages';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function on_export( $element ) {
        unset( $element['settings']['product_id'] );

        return $element;
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'wcpages_content',
            [
                'label' => __( 'Element', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'element',
                [
                    'label' => __( 'Page', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => '— ' . __( 'Select', 'htmega-addons' ) . ' —',
                        'woocommerce_cart' => __( 'Cart Page', 'htmega-addons' ),
                        'product_page' => __( 'Single Product Page', 'htmega-addons' ),
                        'woocommerce_checkout' => __( 'Checkout Page', 'htmega-addons' ),
                        'woocommerce_order_tracking' => __( 'Order Tracking Form', 'htmega-addons' ),
                        'woocommerce_my_account' => __( 'My Account', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'product_id',
                [
                    'label'       => esc_html__( 'Choose Product', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => htmega_post_name('product'),
                    'label_block' => true,
                    'filter_type' => 'by_id',
                    'object_type' => [ 'product' ],
                    'condition'   => [
                        'element' => [ 'product_page' ],
                    ],
                ]
            );
        $this->end_controls_section();

        // Cart page custom style support
        $this->start_controls_section(
            'cart_page_style_header',
            [
                'label' => esc_html__( 'Table Heading', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_cart' ],
                ],
            ]
        );
            $this->add_control(
                'cart_table_header_color',
                [
                    'label'     => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce table.shop_table th' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_table_header_background',
                [
                    'label'     => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce table.shop_table th' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_table_body_style',
            [
                'label' => esc_html__( 'Table Body', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_cart' ],
                ],
            ]
        );

            $this->add_control(
                'cart_table_body_color',
                [
                    'label'     => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce table.shop_table td *' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_table_body_background',
                [
                    'label'     => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce table.shop_table' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_table_body_padding',
                [
                    'label' => esc_html__( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_table_body_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .woocommerce table.shop_table, {{WRAPPER}} .woocommerce table.shop_table td',
                ]
            );

            $this->add_responsive_control(
                'cart_table_body_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text, 
                         {{WRAPPER}} .select2-container--default .select2-selection--single, 
                         {{WRAPPER}} .woocommerce select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Cart table button
        $this->start_controls_section(
            'section_style_cart_button',
            [
                'label' => esc_html__( 'Coupon / Update Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_cart' ],
                ],
            ]
        );


            $this->start_controls_tabs( 'tabs_cart_button_style' );

                $this->start_controls_tab(
                    'tab_cart_button_normal',
                    [
                        'label' => esc_html__( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'cart_button_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_button_background_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_button_border',
                            'label' => esc_html__( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'cart_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'cart_button_box_shadow',
                            'selector' => '{{WRAPPER}} woocommerce button.button',
                        ]
                    );

                    $this->add_control(
                        'cart_button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'cart_button_typography',
                            'label' => esc_html__( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'tab_cart_button_hover',
                    [
                        'label' => esc_html__( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'cart_button_hover_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_button_background_hover_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_button_hover_border_color',
                        [
                            'label' => esc_html__( 'Border Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'condition' => [
                                'cart_button_border!' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Cart table checkout button
        $this->start_controls_section(
            'section_style_cart_checkout_button',
            [
                'label' => esc_html__( 'Checkout Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_cart' ],
                ],
            ]
        );

            $this->start_controls_tabs('cart_checkout_btn_style_tabs');

                // Normal Button style
                $this->start_controls_tab(
                    'cart_checkout_btn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'cart_checkout_btn_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'cart_checkout_btn_typography',
                            'label' => esc_html__( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'cart_checkout_btn_background_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_checkout_btn_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button',
                        ]
                    );

                    $this->add_control(
                        'cart_checkout_btnn_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_checkout_btn_padding',
                        [
                            'label' => esc_html__( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab();

                // Hover Button style
                $this->start_controls_tab(
                    'cart_checkout_btn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'cart_checkout_btn_hover_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_checkout_btn_hover_background_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_checkout_btn_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button:hover',
                        ]
                    );

                    $this->add_control(
                        'cart_checkout_btn_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout a.checkout-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Checkout page Style
        $this->start_controls_section(
            'page_checkout_style_label',
            [
                'label' => esc_html__( 'Label', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_checkout' ],
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'checkout_label_typography',
                    'label' => esc_html__( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .woocommerce form .form-row label',
                ]
            );

            $this->add_control(
                'checkout_label_color',
                [
                    'label'     => esc_html__( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce form .form-row label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'checkout_required_color',
                [
                    'label'     => esc_html__( 'Required Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce form .form-row .required' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'page_checkout_style_input',
            [
                'label' => esc_html__( 'Input', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_checkout' ],
                ],
            ]
        );

            $this->add_control(
                'page_checkout_input_text_color',
                [
                    'label'     => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce select' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce textarea.input-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'page_checkout_input_text_background',
                [
                    'label'     => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce select' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce textarea.input-text' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'page_checkout_textarea_height',
                [
                    'label' => esc_html__( 'Textarea Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 120,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 30,
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce textarea.input-text' => 'height: {{SIZE}}{{UNIT}}; display: block;',
                    ],
                    'separator' => 'before',

                ]
            );

            $this->add_control(
                'page_checkout_input_padding',
                [
                    'label' => esc_html__( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text, 
                         {{WRAPPER}} .woocommerce textarea.input-text, 
                         {{WRAPPER}} .select2-container--default .select2-selection--single,
                         {{WRAPPER}} .woocommerce select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .select2-container--default .select2-selection--single' => 'height: auto; min-height: 37px;',
                        '{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: initial;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'page_checkout_input_space',
                [
                    'label' => esc_html__( 'Element Space', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 20,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce form .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(), [
                    'name'        => 'page_checkout_input_border',
                    'label'       => esc_html__( 'Border', 'htmega-addons' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '
                        {{WRAPPER}} .woocommerce .input-text, 
                        {{WRAPPER}} .woocommerce select, 
                        {{WRAPPER}} .select2-container--default .select2-selection--single',
                ]
            );

            $this->add_control(
                'page_checkout_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text, 
                         {{WRAPPER}} .select2-container--default .select2-selection--single, 
                         {{WRAPPER}} .woocommerce select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'page_checkout_payment',
            [
                'label' => esc_html__( 'Payment', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_checkout' ],
                ],
            ]
        );

            $this->add_control(
                'page_checkout_payment_color',
                [
                    'label'     => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout #payment, {{WRAPPER}} .woocommerce-checkout #payment div.payment_box' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'page_checkout_payment_background',
                [
                    'label'     => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout #payment' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce-checkout #payment div.payment_box' => 'opacity:0.5;',
                        '{{WRAPPER}} .woocommerce-checkout #payment div.payment_box::before' => 'opacity:0.5;',
                    ],
                ]
            );

            $this->add_control(
                'page_checkout_payment_button_heading',
                [
                    'label' => esc_html__( 'Button Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs( 'tabs_payment_button_style' );

                $this->start_controls_tab(
                    'tab_payment_button_normal',
                    [
                        'label' => esc_html__( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'payment_button_typography',
                            'label' => esc_html__( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'payment_button_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'payment_button_background_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'payment_button_border',
                            'label' => esc_html__( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default' => '1px',
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'payment_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'payment_button_text_padding',
                        [
                            'label' => esc_html__( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'tab_payment_button_hover',
                    [
                        'label' => esc_html__( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'payment_button_hover_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'payment_button_background_hover_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'payment_button_hover_border_color',
                        [
                            'label' => esc_html__( 'Border Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'condition' => [
                                'border_border!' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Order Tracking Style 
        $this->start_controls_section(
            'page_order_tracking_style_label',
            [
                'label' => esc_html__( 'Label', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_order_tracking' ],
                ],
            ]
        );

            $this->add_control(
                'page_order_tracking_label_color',
                [
                    'label'     => esc_html__( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce form .form-row label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'page_order_tracking_label_typography',
                    'label' => esc_html__( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .woocommerce form .form-row label',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'page_order_tracking_style_input',
            [
                'label' => esc_html__( 'Input', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_order_tracking' ],
                ],
            ]
        );

            $this->add_control(
                'page_order_tracking_input_text_color',
                [
                    'label'     => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce select' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce textarea.input-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'page_order_tracking_input_text_background',
                [
                    'label'     => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce select' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce textarea.input-text' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'page_order_tracking_input_padding',
                [
                    'label' => esc_html__( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text, 
                         {{WRAPPER}} .woocommerce textarea.input-text, 
                         {{WRAPPER}} .select2-container--default .select2-selection--single,
                         {{WRAPPER}} .woocommerce select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .select2-container--default .select2-selection--single' => 'height: auto; min-height: 37px;',
                        '{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: initial;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'page_order_tracking_input_space',
                [
                    'label' => esc_html__( 'Element Space', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 25,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce form .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(), [
                    'name'        => 'page_order_tracking_input_border',
                    'label'       => esc_html__( 'Border', 'htmega-addons' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '
                        {{WRAPPER}} .woocommerce .input-text, 
                        {{WRAPPER}} .woocommerce select, 
                        {{WRAPPER}} .select2-container--default .select2-selection--single',
                ]
            );

            $this->add_control(
                'page_order_tracking_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .input-text, 
                         {{WRAPPER}} .select2-container--default .select2-selection--single, 
                         {{WRAPPER}} .woocommerce select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Button style
        $this->start_controls_section(
            'page_order_button_style_tracking',
            [
                'label' => esc_html__( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_order_tracking' ],
                ],
            ]
        );

            $this->start_controls_tabs( 'tabs_tracking_button_style' );

                // Button Normal
                $this->start_controls_tab(
                    'tab_tracking_button_normal',
                    [
                        'label' => esc_html__( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'tracking_button_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tracking_button_background_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tracking_button_border',
                            'label' => esc_html__( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default' => '1px',
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'tracking_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tracking_button_text_padding',
                        [
                            'label' => esc_html__( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'tracking_button_typography',
                            'label' => esc_html__( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}} .woocommerce button.button',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover
                $this->start_controls_tab(
                    'tab_tracking_button_hover',
                    [
                        'label' => esc_html__( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'tracking_button_hover_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tracking_button_background_hover_color',
                        [
                            'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tracking_button_hover_border_color',
                        [
                            'label' => esc_html__( 'Border Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'condition' => [
                                'border_border!' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce button.button:hover' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // My Account page
        $this->start_controls_section(
            'page_myaccount_style',
            [
                'label' => esc_html__( 'Menu Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_my_account' ],
                ],
            ]
        );

            $this->start_controls_tabs('page_myaccount_menu_style_tabs');

                $this->start_controls_tab(
                    'page_myaccount_menu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'page_myaccount_menu_color',
                        [
                            'label' => esc_html__( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'page_myaccount_menu_typography',
                            'label' => esc_html__( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'page_myaccount_menu_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'page_myaccount_menu_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'page_myaccount_menu_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'page_myaccount_menu_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'page_myaccount_menu_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'page_myaccount_content_style',
            [
                'label' => esc_html__( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'element' => [ 'woocommerce_my_account' ],
                ],
            ]
        );

            $this->add_control(
                'page_myaccount_content_color',
                [
                    'label' => esc_html__( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-MyAccount-content' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'page_myaccount_content_typography',
                    'label' => esc_html__( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content',
                ]
            );

        $this->end_controls_section();

    }

    private function get_shortcode() {
        $settings = $this->get_settings();

        switch ( $settings['element'] ) {
            case '':
                return '';
                break;

            case 'product_page':

                if ( ! empty( $settings['product_id'] ) ) {
                    $product_data = get_post( $settings['product_id'] );
                    $product = ! empty( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ) ) ? wc_setup_product_data( $product_data ) : false;
                }

                if ( empty( $product ) && current_user_can( 'manage_options' ) ) {
                    return __( 'Please set a valid product', 'htmega-addons' );
                }

                $this->add_render_attribute( 'shortcode', 'id', $settings['product_id'] );
                break;

            case 'woocommerce_cart':
            case 'woocommerce_checkout':
            case 'woocommerce_order_tracking':
                break;
        }

        $shortcode = sprintf( '[%s %s]', $settings['element'], $this->get_render_attribute_string( 'shortcode' ) );

        return $shortcode;
    }

    protected function render() {
        $shortcode = $this->get_shortcode();

        if ( empty( $shortcode ) ) {
            return;
        }

            $html = do_shortcode( $shortcode );

            if ( 'woocommerce_checkout' === $this->get_settings( 'element' ) && '<div class="woocommerce"></div>' === $html ) {
                $html = '<div class="woocommerce">' . __( 'Your cart is currently empty.', 'htmega-addons' ) . '</div>';
            }

            echo $html;
    }

    public function render_plain_content() {
        echo $this->get_shortcode();
    }

}

