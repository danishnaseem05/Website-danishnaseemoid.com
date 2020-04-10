<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Tabs extends Widget_Base {

    public function get_name() {
        return 'htmega-tab-addons';
    }
    
    public function get_title() {
        return __( 'Tabs', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-tabs';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'tab_content',
            [
                'label' => __( 'Tabs', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'tab_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style One', 'htmega-addons' ),
                        '2' => __( 'Style Two', 'htmega-addons' ),
                        '3' => __( 'Style Three', 'htmega-addons' ),
                        '4' => __( 'Style Four', 'htmega-addons' ),
                        '5' => __( 'Style Five', 'htmega-addons' ),
                    ],
                ]
            );


            $repeater = new Repeater();

            $repeater->start_controls_tabs('tab_content_item_area_tabs');

                $repeater->start_controls_tab(
                    'tab_content_item_area',
                    [
                        'label' => __( 'Content', 'htmega-addons' ),
                    ]
                );
                    
                    $repeater->add_control(
                        'tab_title',
                        [
                            'label'   => esc_html__( 'Title', 'htmega-addons' ),
                            'type'    => Controls_Manager::TEXT,
                            'default' => esc_html__( 'Tab #1', 'htmega-addons' ),
                        ]
                    );

                    $repeater->add_control(
                        'tab_icon',
                        [
                            'label'   => esc_html__( 'Icon', 'htmega-addons' ),
                            'type'    => Controls_Manager::ICONS,
                        ]
                    );

                    $repeater->add_control(
                        'content_source',
                        [
                            'label'   => esc_html__( 'Select Content Source', 'htmega-addons' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'custom',
                            'options' => [
                                'custom'    => esc_html__( 'Custom', 'htmega-addons' ),
                                "elementor" => esc_html__( 'Elementor Template', 'htmega-addons' ),
                            ],
                        ]
                    );

                     $repeater->add_control(
                        'template_id',
                        [
                            'label'       => __( 'Content', 'htmega-addons' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => '0',
                            'options'     => htmega_elementor_template(),
                            'condition'   => [
                                'content_source' => "elementor"
                            ],
                        ]
                    );

                     $repeater->add_control(
                        'custom_content',
                        [
                            'label' => __( 'Content', 'htmega-addons' ),
                            'type' => Controls_Manager::WYSIWYG,
                            'title' => __( 'Content', 'htmega-addons' ),
                            'show_label' => false,
                            'condition' => [
                                'content_source' =>'custom',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();// Tab Content area end

                // Style area start
                $repeater->start_controls_tab(
                    'tab_item_style_area',
                    [
                        'label' => __( 'Style', 'htmega-addons' ),
                    ]
                );
                    
                    $repeater->add_control(
                        'tab_title_color',
                        [
                            'label'     => esc_html__( 'Title Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a{{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    
                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'title_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a{{CURRENT_ITEM}}',
                        ]
                    );

                    $repeater->add_control(
                        'tab_title_active_color',
                        [
                            'label'     => esc_html__( 'Title Active Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a.htb-active{{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'title_active_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a.htb-active{{CURRENT_ITEM}}',
                        ]
                    );

                    $repeater->add_control(
                        'tab_icon_color',
                        [
                            'label'     => esc_html__( 'Icon Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a{{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-tab-nav a{{CURRENT_ITEM}} svg' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'tab_icon[value]!' => '',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_control(
                        'tab_icon_size',
                        [
                            'label' => __( 'Icon Size', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'size' => 14,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a{{CURRENT_ITEM}} i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'tab_icon_active_color',
                        [
                            'label'     => esc_html__( 'Active Icon Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a.htb-active{{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-tab-nav a.htb-active{{CURRENT_ITEM}} svg' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'tab_icon[value]!' => '',
                            ]
                        ]
                    );

                $repeater->end_controls_tab(); // Style area end

            $repeater->end_controls_tabs();

            $this->add_control(
                'htmega_tabs_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'tab_title' => esc_html__( 'Title #1', 'htmega-addons' ),
                            'custom_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud exerci ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in repre in voluptate.','htmega-addons' ),
                        ],
                        [
                            'tab_title' => esc_html__( 'Title #2', 'htmega-addons' ),
                            'custom_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud exerci ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in repre in voluptate.','htmega-addons' ),
                        ],
                        [
                            'tab_title' => esc_html__( 'Title #3', 'htmega-addons' ),
                            'custom_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud exerci ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in repre in voluptate.','htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ tab_title }}}',
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'tab_menu_style_section',
            [
                'label' => __( 'Tab Menu', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tab_menu_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-tab-nav',
                ]
            );

            $this->add_responsive_control(
                'tab_menu_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tab-nav' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'tab_menu_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tab-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'tab_menu_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tab-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('tab_menu_style_tabs');

                $this->start_controls_tab(
                    'tab_menu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_responsive_control(
                        'tab_menu_align',
                        [
                            'label'   => __( 'Alignment', 'htmega-addons' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'start'    => [
                                    'title' => __( 'Left', 'htmega-addons' ),
                                    'icon'  => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => __( 'Center', 'htmega-addons' ),
                                    'icon'  => 'fa fa-align-center',
                                ],
                                'end' => [
                                    'title' => __( 'Right', 'htmega-addons' ),
                                    'icon'  => 'fa fa-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav'   => 'justify-content: {{VALUE}} !important;',
                            ],
                            'default' =>'center',
                            'separator' => 'after',
                        ]
                    );

                    $this->add_control(
                        'tab_menu_color',
                        [
                            'label'     => esc_html__( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'tab_menu_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a:not(i)',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'tab_menu_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a',
                        ]
                    );

                    $this->add_responsive_control(
                        'tab_menu_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'tab_menu_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tab_menu_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a',
                        ]
                    );

                    $this->add_responsive_control(
                        'tab_menu_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal style 

                // Active tab style
                $this->start_controls_tab(
                    'tab_menu_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'tab_menu_active_color',
                        [
                            'label'     => esc_html__( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tab-nav a.htb-active' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'tab_menu_active_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a.htb-active',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tab_menu_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-tab-nav a.htb-active',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'tab_style_content_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tab_content_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-tab',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tab_content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-tab',
                ]
            );

            $this->add_responsive_control(
                'tab_content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-tab' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'tab_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'tab_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_tab_attr', 'class', 'htmega-tab-area' );
        $this->add_render_attribute( 'htmega_tab_attr', 'class', 'htmega-tab-style-'.$settings['tab_style'] );

        $this->add_render_attribute( 'htmega_tab_menu_attr', 'class', 'htmega-tab-nav htb-nav');
        $this->add_render_attribute( 'htmega_tab_menu_attr', 'role', 'tablist');
        $this->add_render_attribute( 'htmega_tab_menu_attr', 'class', 'htmega-tab-menu-style-'.$settings['tab_style'] );
        $id = $this->get_id();
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_tab_attr' ); ?>>

                <div <?php echo $this->get_render_attribute_string( 'htmega_tab_menu_attr' ); ?>>
                    <?php
                        $i=0;
                        foreach ( $settings['htmega_tabs_list'] as $item ) {
                            $i++;
                            $tabbuttontxt = $item['tab_title'];
                            if( $i == 1 ){ $active_tab = 'htb-active htb-show'; } else{ $active_tab = ''; }
                            if( !empty( $item['tab_icon']['value'] ) ){ $tabbuttontxt = HTMega_Icon_manager::render_icon( $item['tab_icon'], [ 'aria-hidden' => 'true' ] ).$item['tab_title']; }
                            echo sprintf( '<a class="htb-nav-link %1$s %4$s" href="#htmegatab-%2$s" data-toggle="htbtab" role="tab">%3$s</a>',$active_tab, $id.$i, $tabbuttontxt, 'elementor-repeater-item-'.$item['_id']);
                        }
                    ?>
                </div>

                <div class="htmega-tab-content-area htb-tab-content">
                    <?php
                        $i=0;
                        foreach ( $settings['htmega_tabs_list'] as $item ) {
                            $i++;
                            if( $i == 1 ){ $active_tab = 'htb-active htb-show'; } else{ $active_tab = ''; }

                            if ( $item['content_source'] == 'custom' && !empty( $item['custom_content'] ) ) {
                                $tab_content =  wp_kses_post( $item['custom_content'] );
                            } elseif ( $item['content_source'] == "elementor" && !empty( $item['template_id'] )) {
                                $tab_content =  Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] );
                            }
                            echo sprintf('<div class="htmega-single-tab htb-tab-pane htb-fade %1$s %4$s" id="htmegatab-%2$s" role="tabpanel"><div class="htmega-tab-content">%3$s</div></div>', $active_tab, $id.$i, $tab_content,'elementor-repeater-item-'.$item['_id'] );
                        }
                    ?>
                </div>

            </div>

        <?php

    }

}