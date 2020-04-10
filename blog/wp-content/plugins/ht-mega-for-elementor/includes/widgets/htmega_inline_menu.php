<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_InlineMenu extends Widget_Base {

    public function get_name() {
        return 'htmega-inlinemenu-addons';
    }
    
    public function get_title() {
        return __( 'Inline Navigation', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-nav-menu';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    private function get_available_menus() {

        $menus = wp_get_nav_menus();
        $menulists = [];
        foreach ( $menus as $menu ) {
            $menulists[ $menu->slug ] = $menu->name;
        }
        return $menulists;

    }

    protected function _register_controls() {

        $this->start_controls_section(
            'inline_menu_content',
            [
                'label' => __( 'Inline Navigation', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'inline_menu_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'  => __( 'Style One', 'htmega-addons' ),
                        '2'  => __( 'Style Two', 'htmega-addons' ),
                        '3'  => __( 'Style Three', 'htmega-addons' ),
                        '4'  => __( 'Style Four', 'htmega-addons' ),
                        '5'  => __( 'Style Five', 'htmega-addons' ),
                        '6'  => __( 'Style Six', 'htmega-addons' ),
                        '7'  => __( 'Style Seven', 'htmega-addons' ),
                        '8'  => __( 'Style Eight', 'htmega-addons' ),
                        '9'  => __( 'Style Nine', 'htmega-addons' ),
                        '10'  => __( 'Style Ten', 'htmega-addons' ),
                    ],
                ]
            );

            if ( ! empty( $this->get_available_menus() ) ) {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'label'   => __( 'Menu', 'htmega-addons' ),
                        'type'    => Controls_Manager::SELECT,
                        'options' => $this->get_available_menus(),
                        'default' => array_keys( $this->get_available_menus() )[0],
                        'save_default' => true,
                        'separator' => 'after',
                        'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus Option</a> to manage your menus.', 'htmega-addons' ), admin_url( 'nav-menus.php' ) ),
                    ]
                );
            } else {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Option</a> to create one.', 'htmega-addons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                        'separator' => 'after',
                    ]
                );
            }


        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'inline_menu_style_section',
            [
                'label' => __( 'Main Menu', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'menu_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a',
                ]
            );

            $this->add_responsive_control(
                'inline_menu_alignment',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu'   => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

            // Menu Style Normal Tabs Start
            $this->start_controls_tabs( 'menu_style_tabs' );

                // Menu Style Normal Tab Start
                $this->start_controls_tab(
                    'menu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'menu_normal_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#636363',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_normal_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_normal_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_normal_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_normal_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'menu_normal_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'name' => 'menu_normal_text_shadow',
                            'label' => __( 'Text Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li a',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Normal Tab end

                // Menu Style Hover Tab Start
                $this->start_controls_tab(
                    'menu_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu > li:hover > a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#d94f5c',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu > li:hover > a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu > li:hover > a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu > li:hover > a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Hover Tab End

                // Menu Style Active Tab Start
                $this->start_controls_tab(
                    'menu_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_active_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li.current-menu-item a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#d94f5c',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_active_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li.current-menu-item a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li.current-menu-item a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_active_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .linemenu-nav ul.htmega-mainmenu li.current-menu-item a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Active Tab End

            $this->end_controls_tabs(); // Menu Style Normal Tabs End

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();

        $this->add_render_attribute( 'htmega_inlinemenu_attr', 'class', 'htmega-inlinemenu-area htmega-inlinemenu-style-'.$settings['inline_menu_style'] );

        $menuargs = [
            'echo' => false,
            'menu' => $settings['inline_menu_id'],
            'menu_class' => 'htmega-mainmenu',
            'menu_id' => 'menu-'. $id,
            'fallback_cb' => '__return_empty_string',
            'container' => '',
        ];

        // General Menu.
        $menu_html = wp_nav_menu( $menuargs );

        ?>
            <div <?php echo $this->get_render_attribute_string('htmega_inlinemenu_attr'); ?> >
                <nav class="linemenu-nav">
                    <?php
                        if( !empty( $menu_html ) ){
                            echo $menu_html;
                        }
                    ?>
                </nav>
            </div>
        <?php
    }

}