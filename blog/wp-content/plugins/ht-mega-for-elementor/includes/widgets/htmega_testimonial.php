<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Testimonial extends Widget_Base {

    public function get_name() {
        return 'htmega-testimonial-addons';
    }
    
    public function get_title() {
        return __( 'Testimonial', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-testimonial';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'htmega_testimonial_content_section',
            [
                'label' => __( 'Testimonial', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_testimonial_style',
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
                        '8'   => __( 'Style Eight', 'htmega-addons' ),
                        '9'   => __( 'Style Nine', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label' => esc_html__( 'Slider', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator'=>'before',
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'client_name',
                [
                    'label'   => __( 'Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Carolina Monntoya','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'client_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'client_designation',
                [
                    'label'   => __( 'Designation', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Managing Director','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'client_say',
                [
                    'label'   => __( 'Client Say', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','htmega-addons'),
                ]
            );

            $this->add_control(
                'htmega_testimonial_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [

                        [
                            'client_name'           => __('Carolina Monntoya','htmega-addons'),
                            'client_designation'    => __( 'Managing Director','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Peter Rose','htmega-addons'),
                            'client_designation'    => __( 'Manager','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Gerald Gilbert','htmega-addons'),
                            'client_designation'    => __( 'Developer','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ client_name }}}',
                ]
            );

            $this->add_control(
                'client_image_divider',
                [
                    'label' => __( 'Divider image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'separator' => 'before',
                    'condition' =>[
                        'htmega_testimonial_style!' =>'4'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_image_divider_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );
        

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'testimonial-slider-option',
            [
                'label' => esc_html__( 'Slider Option', 'htmega-addons' ),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slprevicon',
                [
                    'label' => __( 'Previous icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-angle-left',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slnexticon',
                [
                    'label' => __( 'Next icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-angle-right',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'htmega-addons'),
                    'label_on' => __('Yes', 'htmega-addons'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'htmega-addons'),
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcentermode',
                [
                    'label' => esc_html__( 'Center Mode', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcenterpadding',
                [
                    'label' => esc_html__( 'Center padding', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'default' => 50,
                    'condition' => [
                        'slider_on' => 'yes',
                        'slcentermode' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider auto play', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'htmega-addons'),
                    'description' => __('The resolution to tablet.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'htmega-addons'),
                    'description' => __('The resolution to mobile.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Slider Option end

        // Style Testimonial area tab section
        $this->start_controls_section(
            'htmega_testimonial_style_area',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style Testimonial image style start
        $this->start_controls_section(
            'htmega_testimonial_image_style',
            [
                'label'     => __( 'Image', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_testimonial_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal-image img',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .testimonal-image img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Testimonial image style end

        // Style Testimonial name style start
        $this->start_controls_section(
            'htmega_testimonial_name_style',
            [
                'label'     => __( 'Name', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_name_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_name_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4, {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial name style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_designation_style',
            [
                'label'     => __( 'Designation', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_designation_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_designation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_designation_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span, {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end


        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_clientsay_style',
            [
                'label'     => __( 'Client say', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_clientsay_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_clientsay_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_clientsay_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p, {{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_clientsay_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_clientsay_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end


        // Style Testimonial arrow style start
        $this->start_controls_section(
            'htmega_testimonial_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'testimonial_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'testimonial_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_testimonial_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#7d7d7d',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_arrow_fontsize',
                        [
                            'label' => __( 'Font Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_arrow_height',
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
                                'size' => 36,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_arrow_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
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
                                'size' => 36,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'testimonial_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_testimonial_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Testimonial arrow style end


        // Style Testimonial Dots style start
        $this->start_controls_section(
            'htmega_testimonial_dots_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'testimonial_dots_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'testimonial_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_dots_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_dots_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_dots_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_dots_height',
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
                                'size' => 12,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_dots_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
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
                                'size' => 12,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'testimonial_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_dots_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_dots_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_dots_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Testimonial dots style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $slider_settings = [
            'arrows' => ('yes' === $settings['slarrows']),
            'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
            'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
            'dots' => ('yes' === $settings['sldots']),
            'autoplay' => ('yes' === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplay_speed']),
            'animation_speed' => absint($settings['slanimation_speed']),
            'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
            'center_mode' => ( 'yes' === $settings['slcentermode']),
            'center_padding' => absint($settings['slcenterpadding']),
            'testimonial_style_ck' => absint( $settings['htmega_testimonial_style'] ),
        ];

        $slider_responsive_settings = [
            'display_columns' => $settings['slitems'],
            'scroll_columns' => $settings['slscroll_columns'],
            'tablet_width' => $settings['sltablet_width'],
            'tablet_display_columns' => $settings['sltablet_display_columns'],
            'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
            'mobile_width' => $settings['slmobile_width'],
            'mobile_display_columns' => $settings['slmobile_display_columns'],
            'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

        ];

        $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );


        $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-area' );
        $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-style-'.$settings['htmega_testimonial_style'] );

        if( $settings['slider_on'] == 'yes'){
            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-activation' );   
            $this->add_render_attribute( 'testimonial_area_attr', 'data-settings', wp_json_encode( $slider_settings ) );   
        }

        if( ( $settings['htmega_testimonial_style'] == 3 || $settings['htmega_testimonial_style'] == 9 ) && $settings['slider_on'] != 'yes' ){
            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htb-row' );
        }

        ?>
            <div <?php echo $this->get_render_attribute_string( 'testimonial_area_attr' ); ?>>

                <?php if( $settings['htmega_testimonial_style'] == 5 ): ?>

                    <div class="htmega-testimonial-for">
                        <?php 
                            foreach ( $settings['htmega_testimonial_list'] as $testimonial ){
                                
                                if( !empty($testimonial['client_say']) ){
                                    echo '<div class="testimonial-desc"><p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p></div>';
                                }
                                    
                            }
                        ?>
                    </div>

                    <!-- Start Testimonial Nav -->
                    <div class="htmega-testimonal-nav">
                        <?php foreach ( $settings['htmega_testimonial_list'] as $testimonial ) :?>
                            <div class="testimonal-img testimonal">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 
                                ?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- End Testimonial Nav -->
                    <div class="testimonial-shape">
                        <?php
                            if( !empty($settings['client_image_divider']['url']) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' );
                            }
                        ?>
                    </div>

                <?php
                    else: 
                        foreach ( $settings['htmega_testimonial_list'] as $testimonial ) :
                            if( ($settings['htmega_testimonial_style'] == 3) && $settings['slider_on'] != 'yes'){ echo '<div class="htb-col-lg-6 htb-col-xl-6 htb-col-sm-12 htb-col-12">';}
                ?>
                    <?php if( $settings['htmega_testimonial_style'] == 6 ): ?>
                        <div class="testimonal">
                            <div class="content">
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                    }
                                ?>
                                <div class="triangle"></div>
                            </div>
                            <div class="clint-info">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 

                                    if( !empty($settings['client_image_divider']['url']) ){
                                        echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                    }

                                    if( !empty($testimonial['client_name']) ){
                                        echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                    }
                                    if( !empty($testimonial['client_designation']) ){
                                        echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( $settings['htmega_testimonial_style'] == 7 ): ?>
                        <div class="testimonal">
                            <?php
                                if( !empty($testimonial['client_image']['url']) ){
                                    echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                } 

                                if( !empty($settings['client_image_divider']['url']) ){
                                    echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                }
                                if( !empty($testimonial['client_say']) ){
                                    echo ' <div class="content"><p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p></div>';
                                }
                            ?>
                            <div class="clint-info">
                                <?php
                                    if( !empty($testimonial['client_name']) ){
                                        echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                    }
                                    if( !empty($testimonial['client_designation']) ){
                                        echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( $settings['htmega_testimonial_style'] == 8 ): ?>
                        <div class="testimonal">
                            <div class="content">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 

                                    if( !empty($settings['client_image_divider']['url']) ){
                                        echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                    }
                                ?>
                                <div class="clint-info">
                                    <?php
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                if( !empty($testimonial['client_say']) ){
                                    echo '<div class="content"><p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p></div>';
                                }
                            ?>
                        </div>

                    <?php elseif( ( $settings['htmega_testimonial_style'] == 9 ) && $settings['slider_on'] != 'yes' ): ?>
                        <div class="htb-col-xl-4 htb-col-lg-4 htb-col-sm-6 htb-col-12">
                            <div class="testimonal">
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_image']['url']) ){
                                            echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                        } 

                                        if( !empty($settings['client_image_divider']['url']) ){
                                            echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                        }
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<div class="content"><p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p></div>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php else:?>
                        <div class="testimonal">
                            <?php
                                if( !empty($testimonial['client_image']['url']) ){
                                    echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                } 

                                if( !empty($settings['client_image_divider']['url']) ){
                                    echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                }
                            ?>

                            <?php if( $settings['htmega_testimonial_style'] == 3 ):?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                        }
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                        }
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                        }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    <?php endif;?>

                    <?php
                        if( ( $settings['htmega_testimonial_style'] == 3 ) && $settings['slider_on'] != 'yes' ){ echo '</div>'; } 
                        endforeach;
                endif;
                ?>
            </div>
        <?php
    }
}

