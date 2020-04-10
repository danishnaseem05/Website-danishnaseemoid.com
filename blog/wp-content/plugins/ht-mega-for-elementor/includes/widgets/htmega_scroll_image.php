<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Scroll_Image extends Widget_Base {

    public function get_name() {
        return 'htmega-scrollimage-addons';
    }
    
    public function get_title() {
        return __( 'Scroll Image', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-exchange';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'button_content',
            [
                'label' => __( 'Scroll Image', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'scroll_image',
                [
                    'label' => __( 'Choose Image', 'plugin-domain' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'scroll_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_height',
                [
                    'label' => __( 'Minimum Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'step' => 1,
                            'max'=>5000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 600,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image .thumb' => 'min-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'scroll_image_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'scroll_image_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'scroll_image_margin',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'scroll_image_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $scroll_image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['scroll_image']['id'], 'scroll_image_size', $settings);
        if ( !$scroll_image_url ) {
            $scroll_image_url = $settings['scroll_image']['url'];
        }
        $this->add_render_attribute('scrollimage', 'class', 'thumb');
        $this->add_render_attribute('scrollimage', 'style', 'background-image: url('.esc_url( $scroll_image_url ).');');
        ?>

            <div class="ht-scroll-image">
                <div <?php echo $this->get_render_attribute_string( 'scrollimage' ); ?> ></div>
            </div>

        <?php

    }

}