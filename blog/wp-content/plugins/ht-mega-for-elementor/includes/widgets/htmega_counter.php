<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Counter extends Widget_Base {

    public function get_name() {
        return 'htmega-counter-addons';
    }
    
    public function get_title() {
        return __( 'Counter', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-counter';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'counterup',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'counter_content',
            [
                'label' => __( 'Counter', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'counter_layout_style',
                [
                    'label' => __( 'Style', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default'=>'1',
                    'options' => [
                        '1' => __( 'Style One', 'elementor' ),
                        '2' => __( 'Style Two', 'elementor' ),
                        '3' => __( 'Style Three', 'elementor' ),
                        '4' => __( 'Style Four', 'elementor' ),
                        '5' => __( 'Style Five', 'elementor' ),
                        '6' => __( 'Style Six', 'elementor' ),
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'counter_icon_type',
                [
                    'label'   => __( 'Icon Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'label_block'=>false,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'htmega-addons' ),
                            'icon'  => 'fa fa-ban',
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
                    'default' => 'image',
                ]
            );

            $this->add_control(
                'counter_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'condition'=>[
                        'counter_icon_type'=>'icon',
                    ],
                ]
            );

            $this->add_control(
                'counter_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'counter_icon_type' => 'image',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'counter_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'counter_icon_type' => 'image',
                    ]
                ]
            );

            $this->add_control(
                'counter_title',
                [
                    'label' => __( 'Counter Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Happy Clients', 'htmega-addons' ),
                    'placeholder' => __( 'Type your title here', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'terget_number',
                [
                    'label' => __( 'Terget Number', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 100,
                ]
            );

            $this->add_control(
                'counter_number_prefix',
                [
                    'label' => __( 'Number Prefix', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( '$', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'counter_number_suffix',
                [
                    'label' => __( 'Number Suffix', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( '+', 'htmega-addons' ),
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area',
                ]
            );

            $this->add_control(
                'counter_area_background_overlay',
                [
                    'label'     => __( 'Background Overlay', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area::before' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                    'default'=>'#52b6bc',
                    'condition' => [
                        'counter_area_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'counter_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area',
                ]
            );

            $this->add_responsive_control(
                'counter_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-counter-area::before' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'counter_area_align',
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
                        '{{WRAPPER}} .htmega-counter-area' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                ]
            );

        $this->end_controls_section();

        // Style Number tab section
        $this->start_controls_section(
            'counter_number_style_section',
            [
                'label' => __( 'Number', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'terget_number!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_number_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_number_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_number_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_responsive_control(
                'counter_number_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_number_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_number_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_responsive_control(
                'counter_number_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'counter_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_title!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#898989',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_title_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_title_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_responsive_control(
                'counter_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_responsive_control(
                'counter_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'counter_title_after_color',
                [
                    'label' => __( 'Title After Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-content h2::before' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Icon tab section
        $this->start_controls_section(
            'counter_icon_style_section',
            [
                'label' => __( 'Icon', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_icon!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_icon_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ed552d',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon svg' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'counter_icon_size',
                [
                    'label' => __( 'Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 36,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Prefix tab section
        $this->start_controls_section(
            'counter_prefix_style_section',
            [
                'label' => __( 'Prefix', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_number_prefix!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_prefix_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_prefix_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_prefix_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_prefix_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Suffix tab section
        $this->start_controls_section(
            'counter_suffix_style_section',
            [
                'label' => __( 'Suffix', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_number_suffix!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_suffix_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_suffix_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_suffix_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_suffix_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_counter_attr', 'class', 'htmega-counter-area htmega-counter-style-'.$settings['counter_layout_style'] );

        $prefix = $suffix = '';
        if( !empty($settings['counter_number_prefix']) ){
            $prefix = '<span class="htmega-prefix">'.$settings['counter_number_prefix'].'</span>';
        }
        if( !empty($settings['counter_number_suffix']) ){ 
            $suffix = '<span class="htmega-suffix">'.$settings['counter_number_suffix'].'</span>';
        }
    
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_counter_attr' ); ?>>
                <?php
                    if( $settings['counter_layout_style'] == 6 ){
                        echo '<div class="htmega-counter-icon">';
                            if( isset( $settings['counter_icon']['value'] ) ){
                                echo '<span>'.HTMega_Icon_manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';
                            }
                            if( isset( $settings['counter_image']['url'] ) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'counter_image_size', 'counter_image' );
                            }
                            if( !empty( $settings['terget_number'] ) ){
                                echo $prefix.'<span class="htmega-counter-number">'.esc_attr__( $settings['terget_number'] ).'</span>'.$suffix;
                            }
                        echo '</div>';
                    }else{
                        if( isset( $settings['counter_icon']['value'] ) ){
                            echo '<div class="htmega-counter-icon"><span>'.HTMega_Icon_manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] ).'</span></div>';
                        }
                        if( isset( $settings['counter_image']['url'] ) ){
                            echo '<div class="htmega-counter-img">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'counter_image_size', 'counter_image' ).'</div>';
                        }
                    }                    
                ?>
                <div class="htmega-counter-content">
                    <?php
                        if($settings['counter_layout_style'] == 4 ){
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html__($settings['counter_title'],'htmega-addons').'</h2>';
                            }
                            if( !empty( $settings['terget_number'] ) ){
                                echo $prefix.'<span class="htmega-counter-number">'.esc_attr__( $settings['terget_number'] ).'</span>'.$suffix;
                            }
                        }elseif($settings['counter_layout_style'] == 6 ){
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html__($settings['counter_title'],'htmega-addons').'</h2>';
                            }
                        }else{
                            if( !empty( $settings['terget_number'] ) ){
                                echo $prefix.'<span class="htmega-counter-number">'.esc_attr__( $settings['terget_number'] ).'</span>'.$suffix;
                            }
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html__($settings['counter_title'],'htmega-addons').'</h2>';
                            }
                        }
                    ?>
                </div>
            </div>

        <?php

    }

}

