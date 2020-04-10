<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_ImageMarker extends Widget_Base {

    public function get_name() {
        return 'htmega-imagemarker-addons';
    }
    
    public function get_title() {
        return __( 'Image Marker', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-post';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'image_marker_image_section',
            [
                'label' => __( 'Image', 'htmega-addons' ),
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'marker_bg_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient', 'video' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper',
                ]
            );

            $this->add_control(
                'marker_bg_opacity_color',
                [
                    'label' => __( 'Opacity Color', 'plugin-domain' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper:before' => 'background-color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'marker_bg_background_image[id]!'=>'',
                    ]
                ]
            );

        $this->end_controls_section(); // Marker Image Content section

        // Marker Content section
        $this->start_controls_section(
            'image_marker_content_section',
            [
                'label' => __( 'Marker', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'marker_style',
                [
                    'label'   => __( 'Style', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'marker_title',
                [
                    'label'   => __( 'Marker Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Marker #1', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'marker_content',
                [
                    'label'   => __( 'Marker Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'marker_x_position',
                [
                    'label' => __( 'X Postion', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 66,
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $repeater->add_control(
                'marker_y_position',
                [
                    'label' => __( 'Y Postion', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 15,
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'image_marker_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'marker_title' => __( 'Marker #1', 'htmega-addons' ),
                            'marker_content' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.','htmega-addons' ),
                            'marker_x_position' => [
                                'size' => 66,
                                'unit' => '%',
                            ],
                            'marker_y_position' => [
                                'size' => 15,
                                'unit' => '%',
                            ]
                        ]
                    ],
                    'title_field' => '{{{ marker_title }}}',
                ]
            );

        $this->end_controls_section();

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_style_section',
            [
                'label' => __( 'Marker', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'image_marker_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer::before' => 'color: {{VALUE}};',
                    ],
                    'default'=>'#ed552d',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer',
                ]
            );

            $this->add_responsive_control(
                'image_marker_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_marker_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // End Marker style tab

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_content_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_content_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box',
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_border_radius',
                [
                    'label' => esc_html__( 'Content area border radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_padding',
                [
                    'label' => __( 'Content area padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('image_marker_content_style_tabs');
                
                // Style Title Tab start
                $this->start_controls_tab(
                    'style_title_tab',
                    [
                        'label' => __( 'Title', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'image_marker_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#18012c',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_title_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'image_marker_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Title Tab end
                
                // Style Description Tab start
                $this->start_controls_tab(
                    'style_description_tab',
                    [
                        'label' => __( 'Description', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'image_marker_description_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#18012c',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_description_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_description_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Description Tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // End Content style tab

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_image_marker_attr', 'class', 'htmega-marker-wrapper' );
        $this->add_render_attribute( 'htmega_image_marker_attr', 'class', 'htmega-marker-style-'.$settings['marker_style'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string('htmega_image_marker_attr'); ?> >

                <?php
                    foreach ( $settings['image_marker_list'] as $item ):
                    ?>
                        <div class="htmega_image_pointer elementor-repeater-item-<?php echo esc_attr( $item['_id'] );?>">
                            <div class="htmega_pointer_box">
                                <?php
                                    if( !empty( $item['marker_title'] ) ){
                                        echo '<h4>'.esc_html__( $item['marker_title'], 'htmega-addons' ).'</h4>';
                                    }
                                    if( !empty( $item['marker_content'] ) ){
                                        echo '<p>'.esc_html__( $item['marker_content'], 'htmega-addons' ).'</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                ?>
                    
            </div>
        <?php

    }

}

