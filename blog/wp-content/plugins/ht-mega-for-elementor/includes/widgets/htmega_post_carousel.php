<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Post_Carousel extends Widget_Base {

    public function get_name() {
        return 'htmega-postcarousel-addons';
    }
    
    public function get_title() {
        return __( 'Post carousel', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-carousel';
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
            'post_carousel_content',
            [
                'label' => __( 'Post carousel', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'post_carousel_style',
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

            $this->add_control(
                'slider_on',
                [
                    'label'         => __( 'Carousel', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'On', 'htmega-addons' ),
                    'label_off'     => __( 'Off', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
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
                'carousel_post_type',
                [
                    'label' => esc_html__( 'Content Sourse', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => false,
                    'options' => htmega_get_post_types(),
                ]
            );

            $this->add_control(
                'carousel_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies(),
                    'condition' =>[
                        'carousel_post_type' => 'post',
                    ]
                ]
            );

            $this->add_control(
                'carousel_prod_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies('product_cat'),
                    'condition' =>[
                        'carousel_post_type' => 'product',
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
                'show_title',
                [
                    'label' => esc_html__( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'title_length',
                [
                    'label' => __( 'Title Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 5,
                    'condition'=>[
                        'show_title'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_content',
                [
                    'label' => esc_html__( 'Content', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'content_length',
                [
                    'label' => __( 'Content Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 20,
                    'condition'=>[
                        'show_content'=>'yes',
                    ]
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
                    'label_block'=>true,
                    'condition'=>[
                        'show_read_more_btn'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_author',
                [
                    'label' => esc_html__( 'Author', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section(); // Content Option End

        // Carousel setting
        $this->start_controls_section(
            'slider_option',
            [
                'label' => esc_html__( 'Carousel Option', 'htmega-addons' ),
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
                    'max' => 20,
                    'step' => 1,
                    'default' => 3,
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
                        'library' => 'solid',
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
                    'default' => 'fas fa-angle-right',
                    'default' => [
                        'value'=>'fas fa-angle-right',
                        'library' => 'solid',
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

        $this->start_controls_section(
            'post_slider_content_border',
            [
                'label' => __( 'Content Border', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'post_carousel_style'=>'2',
                ]
            ]
        );
            $this->add_control(
                'post_slider_content_border_color',
                [
                    'label' => __( 'Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#b5b5b5',
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'post_slider_content_hover_border_color',
                [
                    'label' => __( 'Hover Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#0056ff',
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2:hover .content .post-inner' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section(); // Slider Option end

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
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'text-align: {{VALUE}};',
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
                'condition'=>[
                    'show_content'=>'yes',
                ]
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
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p',
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'text-align: {{VALUE}};',
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'category_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'category_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a',
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a:hover',
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
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide ul.meta' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner ul.meta li a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'meta_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide ul.meta li',
                ]
            );

            $this->add_responsive_control(
                'meta_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide ul.meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide ul.meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide ul.meta' => 'justify-content: {{VALUE}};',
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
                            'default'=>'#464545',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'readmore_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
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
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .post-btn a.readmore-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Style Slider arrow style start
        $this->start_controls_section(
            'slider_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
        
            $this->start_controls_tabs( 'slider_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'slider_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'slider_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#00282a',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_fontsize',
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
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'slider_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'slider_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_height',
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
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_width',
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
                                'size' => 46,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'slider_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'slider_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#00282a',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'slider_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'slider_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Slider arrow style end

        // Style Pagination button tab section
        $this->start_controls_section(
            'post_slider_pagination_style_section',
            [
                'label' => __( 'Pagination', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'slider_on' => 'yes',
                    'sldots'=>'yes',
                    'post_slider_layout!'=>'4',
                ]
            ]
        );
            
            $this->start_controls_tabs('pagination_style_tabs');

                $this->start_controls_tab(
                    'pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_responsive_control(
                        'slider_pagination_height',
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
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_pagination_width',
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
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pagination_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pagination_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'pagination_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pagination_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover, {{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pagination_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover, {{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $postorder          = $this->get_settings_for_display('postorder');

        $this->add_render_attribute( 'htmega_post_carousel', 'class', 'post-carousel-wrapper htmega-postcarousel-layout-'.$settings['post_carousel_style'] );

        $this->add_render_attribute( 'htmega_post_slider_item_attr', 'class', 'htmega-single-post-slide htmega-postslider-layout-'.$settings['post_carousel_style'] );


        // Slider options
        if( $settings['slider_on'] == 'yes' ){

            $this->add_render_attribute( 'htmega_post_slider_attr', 'class', 'htmega-carousel-activation' );

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

            $this->add_render_attribute( 'htmega_post_slider_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }

        // Query
        $args = array(
            'post_type'             => !empty( $settings['carousel_post_type'] ) ? $settings['carousel_post_type'] : 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['post_limit'] ) ? $settings['post_limit'] : 3,
            'order'                 => $postorder
        );

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby']    = $orderby;
        }

        if( !empty($settings['carousel_prod_categories']) ){
            $get_categories = $settings['carousel_prod_categories'];
        }else{
            $get_categories = $settings['carousel_categories'];
        }

        $carousel_cats = str_replace(' ', '', $get_categories);

        if (  !empty( $get_categories ) ) {
            if( is_array($carousel_cats) && count($carousel_cats) > 0 ){
                $field_name = is_numeric( $carousel_cats[0] ) ? 'term_id' : 'slug';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => ( $settings['carousel_post_type'] == 'product' ) ? 'product_cat' : 'category',
                        'terms' => $carousel_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }

        $carousel_post = new \WP_Query( $args );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_post_carousel' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_attr' ); ?>>

                    <?php
                        if( $carousel_post->have_posts() ):
                        while( $carousel_post->have_posts() ): $carousel_post->the_post();
                    ?>

                        <?php if( $settings['post_carousel_style'] == 2 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( 'large');?></a>
                                </div>
                                <?php $this->htmega_render_loop_content( 2 ); ?>
                            </div>

                        <?php elseif( $settings['post_carousel_style'] == 4 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="post-carousel-flex">
                                    <div class="thumb">
                                        <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail('htmega_size_396x360');?></a>
                                    </div>
                                    <?php $this->htmega_render_loop_content(); ?>
                                </div>
                            </div>

                        <?php elseif( $settings['post_carousel_style'] == 5 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( 'large');?></a>
                                </div>
                                <?php $this->htmega_render_loop_content( 3 ); ?>
                            </div>

                        <?php else:?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( 'full' ); ?></a>
                                </div>
                                <?php $this->htmega_render_loop_content(); ?>
                            </div>
                        <?php endif;?>

                    <?php endwhile; wp_reset_postdata(); wp_reset_query(); endif; ?>

                </div>
            </div>

        <?php

    }

    // Loop Content
    public function htmega_render_loop_content( $contetntstyle = NULL ){
        $settings   = $this->get_settings_for_display();
        ?>
            <div class="content">
                <div class="post-inner">

                    <?php if( $settings['show_category'] == 'yes' ): ?>
                        <ul class="post-category">
                            <?php
                                foreach ( get_the_category() as $category ) {
                                    $term_link = get_term_link( $category );
                                    ?>
                                        <li><a href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a></li>
                                    <?php
                                }
                            ?>
                        </ul>
                    <?php endif;?>

                    <?php
                        if( $contetntstyle == 3 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                            <ul class="meta">
                                <?php if( $settings['show_author'] == 'yes' ): ?>
                                    <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                    <li><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></li>
                                <?php endif; ?>
                            </ul>
                                
                    <?php endif; endif;?>

                    <?php if( $settings['show_title'] == 'yes' ):?>
                        <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h2>
                    <?php endif;?>

                    <?php
                        if( $contetntstyle != 2 ):
                            if( $contetntstyle != 3 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                                <ul class="meta">
                                    <?php if( $settings['show_author'] == 'yes' ): ?>
                                        <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                    <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                        <li><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></li>
                                    <?php endif; ?>
                                </ul>

                    <?php endif; endif;endif;?>

                    <?php
                        if( $settings['show_content'] == 'yes' ){
                            echo '<p>'.wp_trim_words( get_the_content(), $settings['content_length'], '' ).'</p>'; 
                        }
                    ?>

                    <?php
                        if( $contetntstyle == 2 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                            <ul class="meta">
                                <?php if( $settings['show_author'] == 'yes' ): ?>
                                    <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                    <li><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></li>
                                <?php endif; ?>
                            </ul>
                                
                    <?php endif; endif;?>

                    <?php if( $settings['show_read_more_btn'] == 'yes' ): ?>
                        <div class="post-btn">
                            <a class="readmore-btn" href="<?php the_permalink();?>"><?php echo esc_html__( $settings['read_more_txt'], 'htmega-addons' );?></a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php
    }

    // Loop Thumbnails
    public function htmega_render_loop_thumbnail( $thumbnails_size = 'full' ){
        if ( has_post_thumbnail() ){
            the_post_thumbnail( $thumbnails_size ); 
        }else{
            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
        }
    }

}

