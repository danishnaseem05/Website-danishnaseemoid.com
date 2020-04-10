<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Accordion extends Widget_Base {

    public function get_name() {
        return 'htmega-accordion-addons';
    }
    
    public function get_title() {
        return __( 'Accordion', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-accordion';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'jquery-easing',
            'jquery-mousewheel',
            'vaccordion',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'accordion_content',
            [
                'label' => __( 'Accordion', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'accordiantstyle',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'one',
                    'options' => [
                        'one'   => __( 'Style One', 'htmega-addons' ),
                        'two'   => __( 'Style Two', 'htmega-addons' ),
                        'three' => __( 'Style Three', 'htmega-addons' ),
                        'four'  => __( 'Style Four', 'htmega-addons' ),
                    ],
                ]
            );

            // Accordion One Repeater
            $this->add_control(
                'htmega_accordion_list',
                [
                    'label' => __( 'Accordion Items', 'htmega-addons' ),
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                        [
                            'accordion_title'   => __('Accordion Title One','htmega-addons'),
                            'accordion_content' => __('Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.','htmega-addons'),
                        ],
                        [
                            'accordion_title'   => __('Accordion Title Two','htmega-addons'),
                            'accordion_content' => __('Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.','htmega-addons'),
                        ],
                        [
                            'accordion_title'   => __('Accordion Title Three','htmega-addons'),
                            'accordion_content' => __('Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.','htmega-addons'),
                        ],
                    ],
                    'fields' => [
                        [
                            'name'        => 'accordion_title',
                            'label'       => __( 'Title', 'htmega-addons' ),
                            'type'        => Controls_Manager::TEXT,
                            'default'     => __( 'Accordion Title' , 'htmega-addons' ),
                        ],

                        [
                            'name'    => 'content_source',
                            'label'   => __( 'Select Conten Source', 'htmega-addons' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'custom',
                            'options' => [
                                'custom'    => __( 'Custom', 'htmega-addons' ),
                                "elementor" => __( 'Elementor Template', 'htmega-addons' ),
                            ],
                        ],

                        [
                            'name'       => 'accordion_content',
                            'label'      => __( 'Accordion Content', 'htmega-addons' ),
                            'type'       => Controls_Manager::WYSIWYG,
                            'default'    => __( 'Accordion Content', 'htmega-addons' ),
                            'condition' => [
                                'content_source' =>'custom',
                            ],
                        ],

                        [
                            'name'        => 'template_id',
                            'label'       => __( 'Accordion Content', 'htmega-addons' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => '0',
                            'options'     => htmega_elementor_template(),
                            'condition'   => [
                                'content_source' => "elementor"
                            ],
                        ],


                    ],
                    'title_field' => '{{{ accordion_title }}}',
                ]
            );


            // Accordion Two Repeater
            $this->add_control(
                'htmega_accordion_list_two',
                [
                    'label' => __( 'Accordion Items', 'htmega-addons' ),
                    'condition' => [
                        'accordiantstyle' =>'two',
                    ],
                    'type' => Controls_Manager::REPEATER,

                    'default' => [
                        [
                            'accordion_title'   => __('Accordion Title One','htmega-addons'),
                        ],
                    ],

                    'fields' => [
                        [
                            'name'        => 'accordion_title',
                            'label'       => __( 'Title', 'htmega-addons' ),
                            'type'        => Controls_Manager::TEXT,
                            'default'     => __( 'Accordion Title' , 'htmega-addons' ),
                        ],

                        [
                            'name'        => 'accordion_image',
                            'label'       => __( 'Image', 'htmega-addons' ),
                            'type'        => Controls_Manager::MEDIA,
                            'default'     => [
                                'url' => Utils::get_placeholder_image_src(),
                            ],
                        ],

                    ],
                    'title_field' => '{{{ accordion_title }}}',
                ]
            );


            // Accordion Three Repeater
            $this->add_control(
                'htmega_accordion_list_three',
                [
                    'label' => __( 'Accordion Items', 'htmega-addons' ),
                    'condition' => [
                        'accordiantstyle' =>array( 'three','four' ),
                    ],
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                        [
                            'accordion_title'   => __('Accordion Title One','htmega-addons'),
                            'accordion_content' => __('Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.','htmega-addons'),
                        ],

                    ],
                    'fields' => [
                        [
                            'name'        => 'accordion_title',
                            'label'       => __( 'Title', 'htmega-addons' ),
                            'type'        => Controls_Manager::TEXT,
                            'default'     => __( 'Accordion Title' , 'htmega-addons' ),
                        ],

                        [
                            'name'        => 'accordion_image',
                            'label'       => __( 'Image', 'htmega-addons' ),
                            'type'        => Controls_Manager::MEDIA,
                            'default'     => [
                                'url' => Utils::get_placeholder_image_src(),
                            ],
                        ],

                        [
                            'name'    => 'content_source',
                            'label'   => __( 'Select Conten Source', 'htmega-addons' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'custom',
                            'options' => [
                                'custom'    => __( 'Custom', 'htmega-addons' ),
                                "elementor" => __( 'Elementor Template', 'htmega-addons' ),
                            ],
                        ],

                        [
                            'name'       => 'accordion_content',
                            'label'      => __( 'Accordion Content', 'htmega-addons' ),
                            'type'       => Controls_Manager::WYSIWYG,
                            'default'    => __( 'Accordion Content', 'htmega-addons' ),
                            'condition' => [
                                'content_source' =>'custom',
                            ],
                        ],

                        [
                            'name'        => 'template_id',
                            'label'       => __( 'Accordion Content', 'htmega-addons' ),
                            'type'        => Controls_Manager::SELECT,
                            'default'     => '0',
                            'options'     => htmega_elementor_template(),
                            'condition'   => [
                                'content_source' => "elementor"
                            ],
                        ],


                    ],
                    'title_field' => '{{{ accordion_title }}}',
                ]
            ); // End Repeater Three

            $this->add_control(
                'accourdion_title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h2',
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                ]
            );

            $this->add_control(
                'accordion_open_icon',
                [
                    'label'       => __( 'Item Collapse Icon', 'htmega-addons' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-plus',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                ]
            );

            $this->add_control(
                'accordion_close_icon',
                [
                    'label'       => __( 'Open Item Icon', 'htmega-addons' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-minus',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                ]
            );


            $this->add_control(
                'accordion_close_all',
                [
                    'label'   => __( 'Close All Item', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'return_value' => 'yes',
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                ]
            );

            $this->add_control(
                'accordion_multiple',
                [
                    'label' => __( 'Multiple Item Open', 'htmega-addons' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition' => [
                        'accordiantstyle' =>'one',
                    ],
                ]
            );

            $this->add_control(
                'current_item',
                [
                    'label' => __( 'Current Item No', 'htmega-addons' ),
                    'type'  => Controls_Manager::NUMBER,
                    'min'   => 1,
                    'max'   => 50,
                    'condition' => [
                        'accordion_close_all!' =>'yes',
                    ],
                ]
            );

        $this->end_controls_section();

        // Additional Options
        $this->start_controls_section(
            'accordion_additional_option',
            [
                'label' => __( 'Additional Options', 'htmega-addons' ),
                'condition' => [
                    'accordiantstyle' =>'four',
                ],
            ]
        );
           
            $this->add_control(
                'accordion_visible_items',
                [
                    'label' => __( 'Visible Item', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'default' => [
                        'size' => 3,
                    ],
                ]
            );

             $this->add_control(
                'accordion_display_height',
                [
                    'label' => __( 'Accordion Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'size' => 450,
                    ],
                ]
            );

            $this->add_control(
                'accordion_expand_items_height',
                [
                    'label' => __( 'Expand Item Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'size' => 450,
                    ],
                ]
            );

        $this->end_controls_section(); // Additional Options End


        // Style tab section
        $this->start_controls_section(
            'htmega_button_style_section',
            [
                'label' => __( 'Accordion Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' =>'one',
                ],
            ]
        );
            $this->add_control(
                'accordion_item_spacing',
                [
                    'label' => __( 'Accordion Item Spacing', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 15,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single_accourdion' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title style tab start
        $this->start_controls_section(
            'htmega_accordion_title_style',
            [
                'label'     => __( 'Accordion Title', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' =>'one',
                ],
            ]
        );

            $this->start_controls_tabs('htmega_accordion_title_style_tabs');

                // Accordion Title Normal tab Start
                $this->start_controls_tab(
                    'accordion_title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_responsive_control(
                        'titlealign',
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
                                '{{WRAPPER}} .htmega-accourdion-title'   => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-items-hedding' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htb-collapsed.htmega-items-hedding' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'title_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'accordion_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htb-collapsed.htmega-items-hedding' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'title_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Title Normal tab End

                // Accordion Title Active tab Start
                $this->start_controls_tab(
                    'accordion_title_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'activebackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                        ]
                    );

                    $this->add_control(
                        'accordion_title_active_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-items-hedding' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_active_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-items-hedding' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'title_active_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Title Active tab End

            $this->end_controls_tabs();
           
        $this->end_controls_section(); // Title style tab end


        // Title Three style tab start
        $this->start_controls_section(
            'htmega_accordion_title_three_style',
            [
                'label'     => __( 'Accordion Title', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'three','four' ),
                ],
            ]
        );

            $this->add_responsive_control(
                'titlethreealign',
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
                        '{{WRAPPER}} h2.heading-three'   => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tithethreebackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                ]
            );

            $this->add_responsive_control(
                'accordion_titlethree_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_titlethree_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                ]
            );

            $this->add_responsive_control(
                'accordion_titlethree_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'titlethree_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'accordion_titlethree_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'titlethree_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} h2.heading-three, {{WRAPPER}} .accordion--5 .single_accordion .va-title',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section(); // Title three tab end


        // Icon style tab start
        $this->start_controls_section(
            'htmega_accordion_icon_style',
            [
                'label'     => __( 'Accordion Icon', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' =>'one',
                ],
            ]
        );

            // Accordion Icon tabs Start
            $this->start_controls_tabs('htmega_accordion_icon_style_tabs');

                // Accordion Icon normal tab Start
                $this->start_controls_tab(
                    'accordion_icon_style_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'accordion_icon_align',
                        [
                            'label'   => __( 'Alignment', 'htmega-addons' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => __( 'Start', 'htmega-addons' ),
                                    'icon'  => 'eicon-h-align-left',
                                ],
                                'right' => [
                                    'title' => __( 'End', 'htmega-addons' ),
                                    'icon'  => 'eicon-h-align-right',
                                ],
                            ],
                            'default'     => is_rtl() ? 'left' : 'right',
                            'toggle'      => false,
                            'label_block' => false,
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconbackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                        ]
                    );

                    $this->add_control(
                        'accordion_icon_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_lineheight',
                        [
                            'label' => __( 'Icon Line Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_width',
                        [
                            'label' => __( 'Icon Width', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                ],
                            ],
                            'default' => [
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon normal tab End

                // Accordion Icon Active tab Start
                $this->start_controls_tab(
                    'accordion_active_icon_style_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconactivebackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                        ]
                    );

                    $this->add_control(
                        'accordion_active_icon_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_active_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_active_icon_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_active_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'accordion_active_icon_lineheight',
                        [
                            'label' => __( 'Icon Line Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon Active tab End

            $this->end_controls_tabs();

        $this->end_controls_section(); // Icon style tabs end


        // Content style tab start
        $this->start_controls_section(
            'htmega_accordion_content_style',
            [
                'label'     => __( 'Accordion Content', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'one','three','four' ),
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .accordion-content',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'accordion_content_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'contentbackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .accordion-content',
                ]
            );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .accordion-content',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .accordion-content',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section(); // Content style tabs end

    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $accordion_list     = $settings['htmega_accordion_list'];
        $accordion_list_two = $settings['htmega_accordion_list_two'];
        $accordion_list_three = $settings['htmega_accordion_list_three'];
        $accordion_id       = $this->get_id();
        $this->add_render_attribute( 'accordion_heading', 'data-toggle', 'htbcollapse' );

        // Accordiant Style Two
        if( $settings['accordiantstyle'] == 'two' ){
            if( $accordion_list_two ){
                echo '<div class="gallery-wrap">';
                    foreach ( $accordion_list_two as $itemtwo ) {
                        ?>
                            <div class="item" <?php if( !empty($itemtwo['accordion_image']['url']) ){ echo 'style="background-image:url('.$itemtwo['accordion_image']['url'].')"'; } ?>></div>
                        <?php
                    }
                echo '</div>';
            }

        } elseif( $settings['accordiantstyle'] == 'three' ){
            if( $accordion_list_three ){
                echo '<ul class="accordion--4" id="accordion-4">';
                    foreach ( $accordion_list_three as $itemthree ) {
                        ?>
                            <li <?php if( !empty($itemthree['accordion_image']['url']) ){ echo 'style="background-image:url('.$itemthree['accordion_image']['url'].')"'; } ?>>
                                <div class="heading"><?php echo esc_attr__( $itemthree['accordion_title'] ); ?></div>
                                <div class="bgDescription" style="background: transparent url(<?php echo HTMEGA_ADDONS_PL_URL.'/assets/images/bg/bgDescription.png';?>) repeat-x top left;"></div>
                                <div class="description">
                                    <h2 class="heading-three"><?php echo esc_attr__( $itemthree['accordion_title'] ); ?></h2>
                                    <div class="accordion-content">
                                       <?php 
                                            if ( $itemthree['content_source'] == 'custom' && !empty( $itemthree['accordion_content'] ) ) {
                                                echo wp_kses_post( $itemthree['accordion_content'] );
                                            } elseif ( $itemthree['content_source'] == "elementor" && !empty( $itemthree['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $itemthree['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </li>
                        <?php
                    }
                echo '</ul>';
            }
        }elseif( $settings['accordiantstyle'] == 'four' ){

            $accordian_options = [];
            $accordian_options['visibleitem'] = ( $settings['accordion_visible_items']['size'] ) ? $settings['accordion_visible_items']['size'] : 3;
            $accordian_options['expandedheight'] = ( $settings['accordion_expand_items_height']['size'] ) ? $settings['accordion_expand_items_height']['size'] : 450;
            $accordian_options['accordionheight'] = ( $settings['accordion_display_height']['size'] ) ? $settings['accordion_display_height']['size'] : 450;
            
            if( $accordion_list_three ){
                echo '<div id="va-accordion" class="accordion--5" data-accordionoptions=\'' . wp_json_encode( $accordian_options ) . '\' ><div class="accor_wrapper" >';
                    foreach ( $accordion_list_three as $itemthree ) {
                        ?>
                            <div class="single_accordion" <?php if( !empty($itemthree['accordion_image']['url']) ){ echo 'style="background-image:url('.$itemthree['accordion_image']['url'].')"'; } ?>>
                                <h3 class="va-title"><?php echo esc_attr__( $itemthree['accordion_title'] ); ?></h3>
                                <div class="va-content">
                                    <div class="accordion-content">
                                       <?php 
                                            if ( $itemthree['content_source'] == 'custom' && !empty( $itemthree['accordion_content'] ) ) {
                                                echo wp_kses_post( $itemthree['accordion_content'] );
                                            } elseif ( $itemthree['content_source'] == "elementor" && !empty( $itemthree['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $itemthree['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        <?php
                    }
                echo '</div></div>';
            }

        }else{
            $buttonicon = '<span class="accourdion-icon close-accourdion">'.HTMega_Icon_manager::render_icon( $settings['accordion_close_icon'], [ 'aria-hidden' => 'true' ] ).'</span><span class="accourdion-icon open-accourdion">'.HTMega_Icon_manager::render_icon( $settings['accordion_open_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';

            $count_items = count($accordion_list);
            if ( $accordion_list ) {
                echo '<div class="accordion" id="accordionExample'.$accordion_id.'">';
                    if( !empty( $settings['current_item'] ) && $count_items >= $settings['current_item'] ){
                        $current_item = $settings['current_item'];
                    }else{
                        $current_item = 1;
                    }
                    $i = 0;
                    $j = 0;
                    foreach ( $accordion_list as $item ) {
                        $i++;
                        $j = $i.$accordion_id;
                        ?>
                            <div class="single_accourdion htmega-icon-align-<?php echo esc_attr( $settings['accordion_icon_align'] ); ?>">

                                <div class="htmega-accourdion-title">
                                    <?php
                                        if( ( $current_item == $i ) && ( $settings['accordion_close_all'] != 'yes' ) ){
                                            echo sprintf('<%1$s %2$s data-target="#htmega-collapse%3$s" class="htmega-items-hedding">%4$s %5$s</%1$s>', $settings['accourdion_title_html_tag'], $this->get_render_attribute_string( 'accordion_heading' ), $j, $item['accordion_title'], $buttonicon );
                                        }else{
                                            echo sprintf('<%1$s %2$s data-target="#htmega-collapse%3$s" class="htb-collapsed htmega-items-hedding">%4$s %5$s</%1$s>', $settings['accourdion_title_html_tag'], $this->get_render_attribute_string( 'accordion_heading' ), $j, $item['accordion_title'], $buttonicon );
                                        }
                                    ?>
                                </div>

                                <div id="htmega-collapse<?php echo $j;?>" class="htb-collapse <?php if( ( $current_item == $i ) && ( $settings['accordion_close_all'] != 'yes' ) ){ echo 'htb-show'; }?>" <?php if( $settings['accordion_multiple'] != 'yes' ){ echo 'data-parent="#accordionExample'.$accordion_id.'"'; } ?> >
                                    <div class="accordion-content">
                                        <?php 
                                            if ( $item['content_source'] == 'custom' && !empty( $item['accordion_content'] ) ) {
                                                echo wp_kses_post( $item['accordion_content'] );
                                            } elseif ( $item['content_source'] == "elementor" && !empty( $item['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>

                            </div>

                        <?php
                    }
                echo '</div>';
            }
        }
    }
}

