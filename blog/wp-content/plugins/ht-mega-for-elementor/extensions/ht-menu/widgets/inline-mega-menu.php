<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMegaMenu_Inline_Menu extends Widget_Base {

    public function get_name() {
        return 'htmega-menu-inline-menu';
    }

    public function get_title() {
        return __( 'Inline Mega Menu', 'htmega-menu' );
    }

    public function get_icon() {
        return 'eicon-menu-bar';
    }

    public function get_categories() {
        return array( 'htmegamenu-addons' );
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_menu_options',
            array(
                'label' => __( 'Menu', 'htmega-menu' ),
            )
        );

            $this->add_control(
                'menu',
                array(
                    'label'   => __( 'Select Menu', 'htmega-menu' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => htmega_get_all_create_menus(),
                )
            );

            $this->add_control(
                'dropdown_icon',
                array(
                    'label'       => __( 'Dropdown Icon', 'htmega-menu' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default'     => [
                        'value' => 'fa fa-angle-down',
                        'library' => 'solid',
                    ],
                )
            );

        $this->end_controls_section();

        // Menu Style
        $this->start_controls_section(
            'section_main_menu_style',
            array(
                'label'      => __( 'Main Menu', 'htmega-menu' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'menu_wrap_width',
                array(
                    'label' => __( 'Main Menu Width', 'htmega-menu' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 200,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => '%',
                        'size' => 100,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area' => 'width: {{SIZE}}{{UNIT}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'main_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-menu-area ul',
                )
            );

            $this->add_responsive_control(
                'main_menu_margin',
                array(
                    'label'      => __( 'Margin', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area'=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'main_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'main_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'main_menu_border',
                    'label'       => __( 'Border', 'htmega-menu' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} .htmega-menu-area ul',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'main_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-menu-area ul',
                )
            );

            $this->add_responsive_control(
                'main_menu_alignment',
                array(
                    'label'   => __( 'Alignment', 'htmega-menu' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => array(
                        'left'    => array(
                            'title' => __( 'Left', 'htmega-menu' ),
                            'icon'  => 'eicon-h-align-left',
                        ),
                        'center' => array(
                            'title' => __( 'Center', 'htmega-menu' ),
                            'icon'  => 'eicon-h-align-center',
                        ),
                        'right' => array(
                            'title' => __( 'Right', 'htmega-menu' ),
                            'icon'  => 'eicon-h-align-right',
                        ),
                    ),
                    'selectors_dictionary' => array(
                        'left'   => 'justify-content: start;',
                        'center' => 'justify-content: center;',
                        'right'  => 'justify-content: end;',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-container ul' => '{{VALUE}}',
                    ),
                )
            );

        $this->end_controls_section();

        // Sub Menu Style
        $this->start_controls_section(
            'section_sub_menu_style',
            array(
                'label'      => __( 'Sub Menu', 'htmega-menu' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'sub_menu_width',
                array(
                    'label' => __( 'Sub Menu Width', 'htmega-menu' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 100,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => 'px',
                        'size' => 250,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .sub-menu' => 'min-width: {{SIZE}}{{UNIT}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'sub_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-menu-area .sub-menu',
                )
            );

            $this->add_responsive_control(
                'sub_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'sub_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'sub_menu_border',
                    'label'       => __( 'Border', 'htmega-menu' ),
                    'selector'    => '{{WRAPPER}} .htmega-menu-area .sub-menu',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'sub_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-menu-area .sub-menu',
                )
            );

            $this->add_responsive_control(
                'sub_menu_items_padding',
                array(
                    'label'      => __( 'Item Padding', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area ul > li > ul.sub-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_control(
                'sub_menu_items_color',
                array(
                    'label'     => __( 'Text Color', 'htmega-menu' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} .htmega-menu-area ul > li > ul.sub-menu li a' => 'color: {{VALUE}}',
                    ),
                    'separator' =>'before'
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'sub_menu_items_typography',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .htmega-menu-area ul > li > ul.sub-menu li a',
                )
            );

            $this->add_control(
                'sub_menu_items_hover_color',
                array(
                    'label'     => __( 'Hover Color', 'htmega-menu' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} .htmega-menu-area ul > li > ul.sub-menu li a:hover' => 'color: {{VALUE}}',
                    ),
                )
            );

        $this->end_controls_section();

        // Mega Menu Style
        $this->start_controls_section(
            'section_mega_menu_style',
            array(
                'label'      => __( 'Mega Menu', 'htmega-menu' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_responsive_control(
                'mega_menu_width',
                array(
                    'label' => __( 'Mega Menu Width', 'htmega-menu' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 100,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => 'px',
                        'size' => 750,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'mega_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper',
                )
            );

            $this->add_responsive_control(
                'mega_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'mega_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-menu' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'mega_menu_border',
                    'label'       => __( 'Border', 'htmega-menu' ),
                    'selector'    => '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'mega_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-menu-area .htmegamenu-content-wrapper',
                )
            );

        $this->end_controls_section();

        // Main Menu Items Style
        $this->start_controls_section(
            'section_main_menu_items_style',
            array(
                'label'      => __( 'Main Menu Items', 'htmega-menu' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs( 'main_menu_item_style_tabs' );
                
                // Items Normal Tabs
                $this->start_controls_tab(
                    'main_menu_item_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-menu' ),
                    ]
                );
                    
                    $this->add_control(
                        'main_menu_items_color',
                        array(
                            'label'     => __( 'Text Color', 'htmega-menu' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => array(
                                '{{WRAPPER}} .htmega-menu-area ul > li > a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-menu-area ul > li > a > span.htmenu-icon' => 'color: {{VALUE}}',
                            ),
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'     => 'main_menu_items_typography',
                            'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                            'selector' => '{{WRAPPER}}  .htmega-menu-area ul > li > a',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'main_menu_items_border',
                            'label' => __( 'Border', 'htmega-menu' ),
                            'selector' => '{{WRAPPER}} .htmega-menu-area ul > li',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        array(
                            'name'     => 'main_menu_items_bg',
                            'selector' => '{{WRAPPER}} .htmega-menu-area ul > li > a',
                            'fields_options' => array(
                                'background' => array(
                                    'default' => 'classic',
                                )
                            ),
                            'exclude' => array(
                                'image',
                                'position',
                                'attachment',
                                'attachment_alert',
                                'repeat',
                                'size',
                            ),
                        )
                    );

                    $this->add_responsive_control(
                        'main_menu_items_padding',
                        array(
                            'label'      => __( 'Padding', 'htmega-menu' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => array( 'px', '%' ),
                            'selectors'  => array(
                                '{{WRAPPER}} .htmega-menu-area ul > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ),
                        )
                    );

                $this->end_controls_tab();
                
                // Items Hover Tabs
                $this->start_controls_tab(
                    'main_menu_item_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-menu' ),
                    ]
                );
                    
                    $this->add_control(
                        'main_menu_items_hover_color',
                        array(
                            'label'     => __( 'Text Color', 'htmega-menu' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => array(
                                '{{WRAPPER}} .htmega-menu-area ul > li > a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-menu-area ul > li > a:hover > span.htmenu-icon' => 'color: {{VALUE}}',
                            ),
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'main_menu_items_hover_border',
                            'label' => __( 'Border', 'htmega-menu' ),
                            'selector' => '{{WRAPPER}} .htmega-menu-area ul > li:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        array(
                            'name'     => 'main_menu_items_hover_bg',
                            'selector' => '{{WRAPPER}} .htmega-menu-area ul > li > a:hover',
                            'fields_options' => array(
                                'background' => array(
                                    'default' => 'classic',
                                )
                            ),
                            'exclude' => array(
                                'image',
                                'position',
                                'attachment',
                                'attachment_alert',
                                'repeat',
                                'size',
                            ),
                        )
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


    }
    
    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        if ( ! $settings['menu'] ) {
            return;
        }

        $htmega_on_mobile = '<a href="#" class="htmobile-aside-button"><i class="fa fa-bars"></i></a>';
            $htmega_on_mobile_menu = '<div class="htmobile-menu-wrap"><a class="htmobile-aside-close"><i class="fa fa-times"></i></a><div class="htmobile-navigation"><ul id="%1$s" class="%2$s">%3$s</ul></div></div>';

        $items_wrap = '<div class="htmega-menu-area"><ul id="%1$s" class="%2$s">%3$s</ul>'.$htmega_on_mobile.'</div>'.$htmega_on_mobile_menu;

        $args = array(
            'menu'            => $settings['menu'],
            'fallback_cb'     => '',
            'container'       => 'div',
            'container_class' => 'htmega-menu-container',
            'menu_class'      => 'htmega-megamenu',
            'items_wrap'      => $items_wrap,
            'walker'          => new \HTMega_Menu_Nav_Walker(),
            'extra_menu_settings' => array(
                'dropdown_icon' => HTMega_Icon_manager::render_icon( $settings['dropdown_icon'] ),
           ),
        );

        wp_nav_menu( $args );

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new HTMegaMenu_Inline_Menu() );
