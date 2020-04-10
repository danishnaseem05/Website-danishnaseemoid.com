<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Post_Grid_Tab extends Widget_Base {

    public function get_name() {
        return 'htmega-postgridtab-addons';
    }
    
    public function get_title() {
        return __( 'Post Grid Tab', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'post_gridtab_content',
            [
                'label' => __( 'Post Grid Tab', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'post_grid_style',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                        '3'   => __( 'Layout Three', 'htmega-addons' ),
                        '4'   => __( 'Layout Four', 'htmega-addons' ),
                        '5'   => __( 'Layout Five', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Option Start
        $this->start_controls_section(
            'post_content_option',
            [
                'label' => __( 'Post Option', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'grid_post_type',
                [
                    'label' => esc_html__( 'Content Sourse', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => false,
                    'options' => htmega_get_post_types(),
                ]
            );

            $this->add_control(
                'grid_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies(),
                    'condition' =>[
                        'grid_post_type' => 'post',
                    ]
                ]
            );

            $this->add_control(
                'grid_prod_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies('product_cat'),
                    'condition' =>[
                        'grid_post_type' => 'product',
                    ]
                ]
            );

            $this->add_control(
                'post_limit',
                [
                    'label' => __('Limit', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'custom_order',
                [
                    'label' => esc_html__( 'Custom order', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'postorder',
                [
                    'label' => esc_html__( 'Order', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','htmega-addons'),
                        'ASC'   => esc_html__('Ascending','htmega-addons'),
                    ],
                    'condition' => [
                        'custom_order!' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Orderby', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','htmega-addons'),
                        'ID'            => esc_html__('ID','htmega-addons'),
                        'date'          => esc_html__('Date','htmega-addons'),
                        'name'          => esc_html__('Name','htmega-addons'),
                        'title'         => esc_html__('Title','htmega-addons'),
                        'comment_count' => esc_html__('Comment count','htmega-addons'),
                        'rand'          => esc_html__('Random','htmega-addons'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => esc_html__( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_category',
                [
                    'label' => esc_html__( 'Category', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_date',
                [
                    'label' => esc_html__( 'Date', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_read_more_btn',
                [
                    'label' => esc_html__( 'Read More', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'read_more_txt',
                [
                    'label' => __( 'Read More button text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Read More', 'htmega-addons' ),
                    'placeholder' => __( 'Read More', 'htmega-addons' ),
                    'condition'=>[
                        'show_read_more_btn'=>'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Content Option End

        // Style tab section
        $this->start_controls_section(
            'post_gridtab_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_gridtab_item_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .single-post-grid-tab',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_gridtab_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-post-grid-tab',
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();


        // Style Title tab section
        $this->start_controls_section(
            'post_slider_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_title'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#494849',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Content tab section
        $this->start_controls_section(
            'post_slider_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#494849',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner p',
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_align',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Category tab section
        $this->start_controls_section(
            'post_slider_category_style_section',
            [
                'label' => __( 'Category', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_category'=>'yes',
                ]
            ]
        );
            
            $this->start_controls_tabs('category_style_tabs');

                $this->start_controls_tab(
                    'category_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'category_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'category_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'category_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'category_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a',
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'category_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'category_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a:hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Meta tab section
        $this->start_controls_section(
            'post_meta_style_section',
            [
                'label' => __( 'Meta', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'meta_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#464545',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'meta_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li',
                ]
            );

            $this->add_responsive_control(
                'meta_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'meta_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'meta_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'end' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Read More button tab section
        $this->start_controls_section(
            'post_slider_readmore_style_section',
            [
                'label' => __( 'Read More', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_read_more_btn'=>'yes',
                    'read_more_txt!'=>'',
                ]
            ]
        );
            
            $this->start_controls_tabs('readmore_style_tabs');

                $this->start_controls_tab(
                    'readmore_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'readmore_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#494849',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'readmore_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'readmore_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'readmore_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#494849',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Style Read More button tab section
        $this->start_controls_section(
            'post_slider_close_style_section',
            [
                'label' => __( 'Close', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('post_slider_close_style_tabs');

                $this->start_controls_tab(
                    'post_slider_close_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_slider_close_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'post_slider_close_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button',
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab

                $this->start_controls_tab(
                    'post_slider_close_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_slider_close_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'post_slider_close_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button:hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $postorder          = $this->get_settings_for_display('postorder');
        $id = $this->get_id();

        $this->add_render_attribute( 'htmega_post_gridtab', 'class', 'ht-post-grid-tab htmega-post-gridtab-layout-'.$settings['post_grid_style'] );

        // Query
        $args = array(
            'post_type'             => !empty( $settings['grid_post_type'] ) ? $settings['grid_post_type'] : 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['post_limit'] ) ? $settings['post_limit'] : 3,
            'order'                 => $postorder
        );

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby']    = $orderby;
        }

        if( !empty($settings['grid_prod_categories']) ){
            $get_categories = $settings['grid_prod_categories'];
        }else{
            $get_categories = $settings['grid_categories'];
        }

        $grid_cats = str_replace(' ', '', $get_categories);

        if (  !empty( $get_categories ) ) {
            if( is_array($grid_cats) && count($grid_cats) > 0 ){
                $field_name = is_numeric( $grid_cats[0] ) ? 'term_id' : 'slug';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => ( $settings['grid_post_type'] == 'product' ) ? 'product_cat' : 'category',
                        'terms' => $grid_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }

        $grid_post = new \WP_Query( $args );

        $tabs_options = [];
        $tabs_options['wrapid'] = $id;
        $this->add_render_attribute( 'htmega_post_gridtab', 'data-postgridtab', wp_json_encode( $tabs_options ) );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_post_gridtab' ); ?>>

                <?php
                    $countrow = $gdc = $rowcount = $item_class = 0;
                    while( $grid_post->have_posts() ) : $grid_post->the_post();
                        $countrow++;
                        $gdc++;
                        if( $gdc > 6){ $gdc = 1; }
                ?>
                    
                    <?php 
                        if( $settings['post_grid_style'] == 2 ):

                            if( $countrow <= 4 ){
                                $order_content = 5;
                            }else{
                                $order_content = 10;
                            }
                    ?>
                        <div class="post-gridthumb-<?php echo $id; ?> post-grid post-grid-four htb-order-<?php echo esc_attr__( $countrow );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                        <!-- Grid Content -->
                        <div class="post-gridcontent-<?php echo $id; ?> post-content htb-order-<?php echo esc_attr__( $order_content ); if( $countrow == 1 ){ echo ' is-visible'; } ?>">
                            <?php $this->render_post_content(0, $id); ?>
                        </div>

                    <?php 
                        elseif( $settings['post_grid_style'] == 3 ):
                            $image_size = 'full';
                            if( $countrow <= 3 ){
                                $order_content = 4;
                            }else{ $order_content = 7; }

                            // Item Class
                            if( $countrow % 3 == 0 ){
                                $item_class = 'post-grid-half';
                                $image_size = 'htmega_size_585x295';
                            }else{
                                $item_class = 'post-grid-four';
                            }
                    ?>

                        <div class="post-gridthumb-<?php echo $id; ?> post-grid htb-order-<?php echo esc_attr__( $countrow ); echo ' '.esc_attr__( $item_class ); ?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail( $image_size ); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo $id; ?> post-content htb-order-<?php echo esc_attr__( $order_content );  ?>">
                            <?php $this->render_post_content(0, $id); ?>
                        </div>

                    <?php 
                        elseif( $settings['post_grid_style'] == 4 ):

                            if( $countrow <= 3 ){
                                $order_content = 4;
                            }else{ $order_content = 7; }

                            // Item Class
                            $item_class = 'post-grid-one-third';
                    ?>

                        <div class="post-gridthumb-<?php echo $id; ?> post-grid htb-order-<?php echo esc_attr__( $countrow ); echo ' '.esc_attr__( $item_class ); ?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo $id; ?> post-content htb-order-<?php echo esc_attr__( $order_content );  ?>">
                            <?php $this->render_post_content(0, $id); ?>
                        </div>

                    <?php 
                        elseif( $settings['post_grid_style'] == 5 ):

                            if( $countrow <= 2 ){
                                $order_content = 3;
                            }else{ $order_content = 6; }

                            // Item Class
                            if( $countrow <= 2 ){
                                $item_class = 'post-grid-half';
                            }else{
                                $item_class = 'post-grid-one-third';
                            }
                    ?>

                        <div class="post-gridthumb-<?php echo $id; ?> post-grid htb-order-<?php echo esc_attr__( $countrow ); echo ' '.esc_attr__( $item_class ); ?> gradient-overlay gradient-overlay-<?php echo esc_attr__( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo $id; ?> post-content htb-order-<?php echo esc_attr__( $order_content );  ?>">
                            <?php $this->render_post_content($gdc, $id); ?>
                        </div>

                    <?php else:

                        if( $countrow <= 3 ){
                            $item_class = 'post-grid-one-third';
                        }
                        if( $countrow >= 4 ){
                            $item_class = 'post-grid-half';
                        }
                        // Content Class
                        $order_content = 4;
                        if( $countrow > 3 ){
                            $order_content = 6;
                        }
                    ?>
                        <div class="post-gridthumb-<?php echo $id; ?> post-grid htb-order-<?php echo esc_attr__( $countrow ); echo ' '.esc_attr__( $item_class );?> gradient-overlay gradient-overlay-<?php echo esc_attr__( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                        <!-- Grid Content -->
                        <div class="post-gridcontent-<?php echo $id; ?> post-content htb-order-<?php echo esc_attr__( $order_content ); if( $countrow == 1 ){ echo ' is-visible'; } ?>">
                            <?php $this->render_post_content( $gdc, $id ); ?>
                        </div>
                    <?php endif;?>

                <?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>

            </div>

        <?php

    }

    public function render_post_content( $gdc = NULL, $id = NULL){

        $settings   = $this->get_settings_for_display();

        ?>
            <!-- Start Post Slider -->
            <div class="single-post-grid-tab">
                <div class="htb-row htb-align-items-center">
                    <div class="htb-col-lg-6">
                        <div class="gradient-overlay gradient-overlay-<?php echo esc_attr__( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="htb-col-lg-6">
                        <div class="content">
                            <div class="post-inner">
                                <?php
                                    if( $settings['show_category'] == 'yes' ){
                                        echo '<ul class="post-category">';
                                            foreach ( get_the_category() as $category ) {
                                                $term_link = get_term_link( $category );
                                                ?>
                                                    <li><a href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a></li>
                                                <?php
                                            }
                                        echo '</ul>';
                                    }
                                ?>
                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                    <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), 8, '' ); ?></a></h2>
                                    <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                    <ul class="meta">
                                        <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                        <li><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons')); ?></li>
                                    </ul>
                                <?php endif;?>
                                <p><?php echo wp_trim_words( get_the_content(), 50, '' ); ?></p>
                                <?php if( $settings['show_read_more_btn'] == 'yes' ): ?>
                                    <div class="post-btn">
                                        <a class="readmore-btn" href="<?php the_permalink();?>"><?php echo esc_html__( $settings['read_more_txt'], 'htmega-addons' );?></a>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Post Slider -->
            <!-- Close Btn -->
            <div class="post-gridclose-<?php echo $id; ?> close__wrap">
                <button><i class="fa fa-times"></i></button>
            </div>

        <?php
    }

}

