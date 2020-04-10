<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Pricing_Table extends Widget_Base {

    public function get_name() {
        return 'htmega-pricing-table-addons';
    }
    
    public function get_title() {
        return __( 'Pricing Table', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-price-table';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

         // Layout Fields tab start
        $this->start_controls_section(
            'htmega_pricing_layout',
            [
                'label' => __( 'Layout', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'htmega_pricing_style',
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

        $this->end_controls_section(); // Layout Fields tab end

        // Header Fields tab start
        $this->start_controls_section(
            'htmega_pricing_header',
            [
                'label' => __( 'Header', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'pricing_title',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Standard', 'htmega-addons' ),
                    'default' => __( 'Standard', 'htmega-addons' ),
                    'title' => __( 'Enter your service title', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_ribon_pricing_table',
                [
                    'label'        => esc_html__( 'Ribon', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_table_ribon_background',
                    'label' => __( 'Ribon Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-pricing-panel',
                    'condition' => [
                        'htmega_ribon_pricing_table' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'pricing_table_ribon_image',
                [
                    'label' => __('Ribon image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => [
                        'url' => HTMEGA_ADDONS_PL_URL.'/assets/images/pricing/pricing-ribon.png',
                    ],
                    'condition' => [
                        'htmega_ribon_pricing_table' => 'yes'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-ribon::before' => 'content: url( {{URL}} )',
                    ]
                ]
            );


            $this->add_control(
                'htmega_header_icon_type',
                [
                    'label' => __('Image or Icon','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'options' =>[
                        'img' =>[
                            'title' =>__('Image','htmega-addons'),
                            'icon' =>'fa fa-picture-o',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'fa fa-info',
                        ]
                    ],
                    'default' => 'img',
                    'condition' => [
                        'htmega_pricing_style' => '2'
                    ]
                ]
            );

            $this->add_control(
                'headerimage',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    
                    'condition' => [
                        'htmega_pricing_style' => '2',
                        'htmega_header_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'headerimagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'htmega_pricing_style' => '2',
                        'htmega_header_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_control(
                'headericon',
                [
                    'label' =>esc_html__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-pencil',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'htmega_pricing_style' => '2',
                        'htmega_header_icon_type' => 'icon',
                    ]
                ]
            );

        $this->end_controls_section(); // Header Fields tab end

        // Pricing Fields tab start
        $this->start_controls_section(
            'htmega_pricing_price',
            [
                'label' => __( 'Pricing', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'htmega_currency_symbol',
                [
                    'label'   => __( 'Currency Symbol', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        ''             => esc_html__( 'None', 'htmega-addons' ),
                        'dollar'       => '&#36; ' . __( 'Dollar', 'htmega-addons' ),
                        'euro'         => '&#128; ' . __( 'Euro', 'htmega-addons' ),
                        'baht'         => '&#3647; ' . __( 'Baht', 'htmega-addons' ),
                        'franc'        => '&#8355; ' . __( 'Franc', 'htmega-addons' ),
                        'guilder'      => '&fnof; ' . __( 'Guilder', 'htmega-addons' ),
                        'krona'        => 'kr ' . __( 'Krona', 'htmega-addons' ),
                        'lira'         => '&#8356; ' . __( 'Lira', 'htmega-addons' ),
                        'peseta'       => '&#8359 ' . __( 'Peseta', 'htmega-addons' ),
                        'peso'         => '&#8369; ' . __( 'Peso', 'htmega-addons' ),
                        'pound'        => '&#163; ' . __( 'Pound Sterling', 'htmega-addons' ),
                        'real'         => 'R$ ' . __( 'Real', 'htmega-addons' ),
                        'ruble'        => '&#8381; ' . __( 'Ruble', 'htmega-addons' ),
                        'rupee'        => '&#8360; ' . __( 'Rupee', 'htmega-addons' ),
                        'indian_rupee' => '&#8377; ' . __( 'Rupee (Indian)', 'htmega-addons' ),
                        'shekel'       => '&#8362; ' . __( 'Shekel', 'htmega-addons' ),
                        'yen'          => '&#165; ' . __( 'Yen/Yuan', 'htmega-addons' ),
                        'won'          => '&#8361; ' . __( 'Won', 'htmega-addons' ),
                        'custom'       => __( 'Custom', 'htmega-addons' ),
                    ],
                    'default' => 'dollar',
                ]
            );

            $this->add_control(
                'htmega_currency_symbol_custom',
                [
                    'label'     => __( 'Custom Symbol', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'htmega_currency_symbol' => 'custom',
                    ],
                ]
            );

            $this->add_control(
                'htmega_price',
                [
                    'label'   => esc_html__( 'Price', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => '35.50',
                ]
            );

            $this->add_control(
                'htmega_offer_price',
                [
                    'label'        => esc_html__( 'Offer', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'htmega_original_price',
                [
                    'label'     => esc_html__( 'Original Price', 'htmega-addons' ),
                    'type'      => Controls_Manager::NUMBER,
                    'default'   => '49',
                    'condition' => [
                        'htmega_offer_price' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'htmega_period',
                [
                    'label'   => esc_html__( 'Period', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Monthly', 'htmega-addons' ),
                ]
            );

        $this->end_controls_section(); // Pricing Fields tab end

        // Features tab start
        $this->start_controls_section(
            'htmega_pricing_features',
            [
                'label' => __( 'Features', 'htmega-addons' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'htmega_features_title',
                [
                    'label'   => esc_html__( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Features Tilte', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'htmega_old_features',
                [
                    'label'        => esc_html__( 'Old Features', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $repeater->add_control(
                'htmega_features_icon',
                [
                    'label'   => esc_html__( 'Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-angle-double-right',
                        'library'=>'solid',
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_features_icon_color',
                [
                    'label'     => esc_html__( 'Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} svg' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'htmega_features_icon[value]!' => '',
                    ]
                ]
            );

            $this->add_control(
                'htmega_features_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'htmega_features_title' => esc_html__( 'Features Title One', 'htmega-addons' ),
                            'htmega_features_icon' => 'fas fa-angle-double-right',
                        ],

                        [
                            'htmega_features_title' => esc_html__( 'Features Title Two', 'htmega-addons' ),
                            'htmega_features_icon' => 'fas fa-angle-double-right',
                        ],

                        [
                            'htmega_features_title' => esc_html__( 'Features Title Three', 'htmega-addons' ),
                            'htmega_features_icon' => 'fas fa-angle-double-right',
                        ],
                    ],
                    'title_field' => '{{{ htmega_features_title }}}',
                ]
            );


        $this->end_controls_section(); // Features Fields tab end

        // Footer tab start
        $this->start_controls_section(
            'htmega_pricing_footer',
            [
                'label' => __( 'Footer', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'htmega_button_text',
                [
                    'label'   => esc_html__( 'Button Text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Sign Up', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_button_link',
                [
                    'label'       => __( 'Link', 'htmega-addons' ),
                    'type'        => Controls_Manager::URL,
                    'placeholder' => 'http://your-link.com',
                    'default'     => [
                        'url' => '#',
                    ],
                ]
            );

        $this->end_controls_section(); // Footer Fields tab end

        // Style tab section start
        $this->start_controls_section(
            'htmega_pricing_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'htmega_heighlight_pricing_table',
                [
                    'label'        => esc_html__( 'High Light Pricing Table', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_table_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-pricing-panel',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'pricing_table_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-pricing-panel',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'pricing_table_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-pricing-panel',
                ]
            );

            $this->add_responsive_control(
                'pricing_table_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'pricing_table_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'pricing_table_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style tab section end 

        // Header style tab start
        $this->start_controls_section(
            'htmega_header_style',
            [
                'label'     => __( 'Header', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'pricing_header_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'pricing_header_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_header_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-pricing-heading',
                ]
            );

            $this->add_control(
                'pricing_header_heading_title',
                [
                    'label'     => __( 'Title', 'htmega-addons' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'pricing_header_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-heading .title h2' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'pricing_header_title_typography',
                    'selector' => '{{WRAPPER}} .htmega-pricing-heading .title h2',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );

            $this->add_control(
                'pricing_header_heading_price',
                [
                    'label'     => __( 'Price', 'htmega-addons' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'pricing_header_price_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-heading .price h4' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'pricing_header_price_typography',
                    'selector' => '{{WRAPPER}} .htmega-pricing-heading .price h4',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_header_price_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-pricing-heading .price',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'pricing_header_price_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-pricing-heading .price',
                ]
            );

            $this->add_responsive_control(
                'pricing_header_price_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-heading .price' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Header style tab end


        // Features style tab start
        $this->start_controls_section(
            'htmega_features_style',
            [
                'label'     => __( 'Features', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_features_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-pricing-body',
                ]
            );

            $this->add_control(
                'pricing_features_item_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body ul li' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'pricing_features_item_typography',
                    'selector' => '{{WRAPPER}} .htmega-pricing-body ul li',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );

            $this->add_responsive_control(
                'pricing_features_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'pricing_features_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

        $this->end_controls_section(); // Features style tab end

        // Footer style tab start
        $this->start_controls_section(
            'htmega_pricing_footer_style',
            [
                'label'     => __( 'Footer', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'pricing_footer_style_tabs');

                // Pricing Normal tab start
                $this->start_controls_tab(
                    'style_pricing_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pricing_footer_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn',
                        ]
                    );

                    $this->add_control(
                        'pricing_footer_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'     => 'pricing_footer_typography',
                            'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn',
                            'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                        ]
                    );

                    $this->add_responsive_control(
                        'pricing_footer_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ]
                        ]
                    );

                    $this->add_responsive_control(
                        'pricing_footer_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pricing_footer_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'pricing_footer_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Pricing Normal tab end

                // Pricing Hover tab start
                $this->start_controls_tab(
                    'style_pricing_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pricing_footer_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover',
                        ]
                    );

                    $this->add_control(
                        'pricing_footer_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pricing_footer_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'pricing_footer_hover_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Pricing Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Footer style tab end

    }

    private function get_currency_symbol( $symbol_name ) {
        $symbols = [
            'dollar'       => '&#36;',
            'baht'         => '&#3647;',
            'euro'         => '&#128;',
            'franc'        => '&#8355;',
            'guilder'      => '&fnof;',
            'indian_rupee' => '&#8377;',
            'krona'        => 'kr',
            'lira'         => '&#8356;',
            'peseta'       => '&#8359',
            'peso'         => '&#8369;',
            'pound'        => '&#163;',
            'real'         => 'R$',
            'ruble'        => '&#8381;',
            'rupee'        => '&#8360;',
            'shekel'       => '&#8362;',
            'won'          => '&#8361;',
            'yen'          => '&#165;',
        ];
        return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if ( ! empty( $settings['htmega_button_link']['url'] ) ) {
            
            $this->add_render_attribute( 'url', 'class', 'price_btn' );
            $this->add_render_attribute( 'url', 'href', $settings['htmega_button_link']['url'] );

            if ( $settings['htmega_button_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['htmega_button_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }
        }

        // Currency symbol
        $currencysymbol = '';
        if ( ! empty( $settings['htmega_currency_symbol'] ) ) {
            if ( $settings['htmega_currency_symbol'] != 'custom' ) {
                $currencysymbol = '<sub>'.$this->get_currency_symbol( $settings['htmega_currency_symbol'] ).'</sub>';
            } else {
                $currencysymbol = '<sub>'.$settings['htmega_currency_symbol_custom'].'</sub>';
            }
        }

        $this->add_render_attribute( 'pricing_area_attr', 'class', 'htmega-pricing-panel' );
        $this->add_render_attribute( 'pricing_area_attr', 'class', 'htmega-pricing-style-'.$settings['htmega_pricing_style'] );

        if( $settings['htmega_heighlight_pricing_table'] == 'yes' ){
            $this->add_render_attribute( 'pricing_area_attr', 'class', 'htmega-pricing-heighlight' );
        }

        if( $settings['htmega_ribon_pricing_table'] == 'yes' ){
            $this->add_render_attribute( 'pricing_area_attr', 'class', 'htmega-pricing-ribon' );
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'pricing_area_attr' ); ?> >

                <?php if( $settings['htmega_pricing_style'] == 2 ):?>
                    <div class="htmega-pricing-heading">
                        <div class="icon">
                            <?php
                                if( $settings['htmega_header_icon_type'] == 'img' ){  
                                    echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'headerimagesize', 'headerimage' );
                                }else{
                                    echo HTMega_Icon_manager::render_icon( $settings['headericon'], [ 'aria-hidden' => 'true' ] );
                                }
                            ?>
                        </div>
                        <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span><span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span><span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" >
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>
                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php elseif( $settings['htmega_pricing_style'] == 3 ): ?>
                    <div class="htmega-pricing-heading">
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                         <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                    </div>

                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" > 
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>
                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php elseif( $settings['htmega_pricing_style'] == 4 ): ?>
                    <div class="htmega-pricing-heading">
                         <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                    </div>

                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" >
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>

                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php elseif( $settings['htmega_pricing_style'] == 5 ): ?>
                    <div class="htmega-pricing-heading">
                        <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="htmega-pricing-body">
                        <?php if( $settings['htmega_features_list'] ): ?>
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" >
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif;
                            if( !empty($settings['htmega_button_text']) ){
                                echo sprintf( '<a %1$s><span>%2$s</span></a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] );
                            }
                        ?>
                    </div>

                <?php elseif( $settings['htmega_pricing_style'] == 6 ): ?>
                    <div class="htmega-pricing-heading">
                        <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span class="period-txt">'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span class="period-txt">'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" > 
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>
                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php elseif( $settings['htmega_pricing_style'] == 7 ): ?>
                    <div class="htmega-pricing-heading">
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span class="period-txt">'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span class="period-txt">'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                        <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                    </div>
                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" > 
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>
                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php else:?>
                    <div class="htmega-pricing-heading">
                        <?php
                            if( !empty($settings['pricing_title']) ){
                                echo '<div class="title"><h2>'.esc_attr__( $settings['pricing_title'],'htmega-addons' ).'</h2></div>';
                            }
                        ?>
                        <div class="price">
                            <?php
                                if( $settings['htmega_offer_price'] == 'yes' && !empty( $settings['htmega_original_price'] ) ){
                                    echo '<h4><span class="pricing_old">'.$currencysymbol.'<del>'.esc_attr__( $settings['htmega_original_price'],'htmega-addons' ).'</del></span><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                }else{
                                    if( !empty($settings['htmega_price']) ){
                                        echo '<h4><span class="pricing_new">'.$currencysymbol.esc_attr__( $settings['htmega_price'],'htmega-addons' ).'</span> <span class="separator">/</span> <span>'.esc_attr__( $settings['htmega_period'],'htmega-addons' ).'</span></h4>';
                                    }
                                }
                            ?>
                        </div>
                    </div>

                    <?php if( $settings['htmega_features_list'] ): ?>
                        <div class="htmega-pricing-body">
                            <ul class="htmega-features">
                                <?php foreach ( $settings['htmega_features_list'] as $features ) :?>
                                    <li class="<?php if( $features['htmega_old_features'] == 'yes' ){ echo 'off'; }?> elementor-repeater-item-<?php echo $features['_id']; ?>" > 
                                        <?php
                                            if( !empty( $features['htmega_features_icon']['value'] ) ){
                                                echo HTMega_Icon_manager::render_icon( $features['htmega_features_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            echo esc_html__( $features['htmega_features_title'], 'htmega-addons' );
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;?>

                    <?php
                        if( !empty($settings['htmega_button_text']) ){
                            echo '<div class="htmega-pricing-footer">'.sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['htmega_button_text'] ).'</div>';
                        }
                    ?>

                <?php endif; ?>
            </div>
        <?php
    }
}

