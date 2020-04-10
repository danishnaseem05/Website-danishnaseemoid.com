<?php

namespace HTMega_Builder\Elementor\Widget;
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Site_Logo_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-site-logo';
    }

    public function get_title() {
        return __( 'BL: Site Logo', 'ht-builder' );
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function _register_controls() {

        // Title
        $this->start_controls_section(
            'title_content',
            [
                'label' => __( 'Site Title', 'ht-builder' ),
            ]
        );
            
            $this->add_control(
                'logo_type',
                [
                    'label' => __( 'Logo', 'ht-builder' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'default' => [
                            'title' => __( 'Default', 'ht-builder' ),
                            'icon' => 'eicon-site-logo',
                        ],
                        'custom' => [
                            'title' => __( 'Custom', 'ht-builder' ),
                            'icon' => 'eicon-image-rollover',
                        ]
                        
                    ],
                    'default' => 'default',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'sitelogo_image',
                [
                    'label' => __( 'Site Logo', 'ht-builder' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' =>[
                        'logo_type' => 'custom',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'logosize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' =>[
                        'logo_type' => 'custom',
                    ],
                ]
            );

        $this->end_controls_section();


        // Style
        $this->start_controls_section(
            'logo_style_section',
            array(
                'label' => __( 'Style', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'logo_border',
                    'label' => __( 'Border', 'ht-builder' ),
                    'selector' => '{{WRAPPER}}',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'logo_border_radius',
                [
                    'label' => __( 'Border Radius', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'logo_padding',
                [
                    'label' => __( 'Padding', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'logo_margin',
                [
                    'label' => __( 'Margin', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'logo_align',
                [
                    'label'        => __( 'Alignment', 'ht-builder' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'ht-builder' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'ht-builder' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'ht-builder' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'ht-builder' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        if( $settings['logo_type'] == 'default' ){
            if( has_custom_logo() ){ the_custom_logo(); }
        }else{
            echo '<a href="'.esc_url( home_url( '/' ) ).'">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'logosize', 'sitelogo_image' ).'</a>';
        }
    }

    

}
