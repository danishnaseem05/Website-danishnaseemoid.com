<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Newsticker extends Widget_Base {

    public function get_name() {
        return 'htmega-newtsicker-addons';
    }
    
    public function get_title() {
        return __( 'News Ticker', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-ticker';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'htmega-newsticker',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'news_ticker',
            [
                'label' => __( 'News Ticker', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'news_ticker_style',
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
                    ],
                ]
            );

            $this->add_control(
                'ticker_label',
                [
                    'label' => __( 'Ticker Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Breaking News', 'htmega-addons' ),
                    'separator'=>'after',
                ]
            );

            $this->add_control(
                'label_icon',
                [
                    'label' => __( 'Label Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'rowheight',
                [
                    'label' => __('Row Height', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 50,
                ]
            );

            $this->add_control(
                'maxrow',
                [
                    'label' => __('Maxium Row', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'animationspeed',
                [
                    'label' => __('Animation Speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 600,
                ]
            );

            $this->add_control(
                'animateduration',
                [
                    'label' => __('Animatied duration', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5000,
                ]
            );

            $this->add_control(
                'direction',
                [
                    'label' => __( 'Direction', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'up',
                    'options' => [
                        'up'   => __( 'Up', 'htmega-addons' ),
                        'down'   => __( 'Down', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'news_ticker_date',
                [
                    'label' => esc_html__( 'Date', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'autostart',
                [
                    'label' => esc_html__( 'Auto Start', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'pauseonhover',
                [
                    'label' => esc_html__( 'Pause on hover', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'news_ticker_content',
            [
                'label' => __( 'Content Option', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'news_post_type',
                [
                    'label' => esc_html__( 'Content Sourse', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => false,
                    'options' => htmega_get_post_types(),
                ]
            );

            $this->add_control(
                'news_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies(),
                    'condition' =>[
                        'news_post_type' => 'post',
                    ]
                ]
            );

            $this->add_control(
                'news_prod_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies('product_cat'),
                    'condition' =>[
                        'news_post_type' => 'product',
                    ]
                ]
            );

            $this->add_control(
                'newslimit',
                [
                    'label' => __('News Limit', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3,
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
                'order',
                [
                    'label' => esc_html__( 'order', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','htmega-addons'),
                        'ASC'   => esc_html__('Ascending','htmega-addons'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Navigation Button
        $this->start_controls_section(
            'news_navigation',
            [
                'label' => __( 'Navigation Button', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'navigation_show',
                [
                    'label' => esc_html__( 'Show', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'button_prev_icon',
                [
                    'label' => __( 'Previous Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default'=>[
                        'value'=>'fas fa-angle-left',
                        'library' => 'solid',
                    ],
                    'condition' =>[
                        'navigation_show' =>'yes',
                    ],
                ]
            );

            $this->add_control(
                'button_next_icon',
                [
                    'label' => __( 'Next Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default'=>[
                        'value'=>'fas fa-angle-right',
                        'library' => 'solid',
                    ],
                    'condition' =>[
                        'navigation_show' =>'yes',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_newsticker_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_newsticker_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .breaking-news-default::before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_newsticker_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .breaking-news-default',
                ]
            );

            $this->add_responsive_control(
                'htmega_newsticker_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'htmega_newsticker_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_newsticker_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style content tab section
        $this->start_controls_section(
            'htmega_newsticker_contnet_style',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'htmega_newsticker_contnet_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-ticker li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_newsticker_contnet_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .breaking-news-ticker li a',
                ]
            );

        $this->end_controls_section();

        // Style Label tab section
        $this->start_controls_section(
            'htmega_newsticker_label_style',
            [
                'label' => __( 'Label', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'ticker_label!' =>'',
                ],
            ]
        );
            
            $this->add_control(
                'htmega_newsticker_label_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-title h5' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_newsticker_label_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-title h5',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_newsticker_label_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-title h5::before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_newsticker_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-title h5',
                ]
            );

            $this->add_responsive_control(
                'htmega_newsticker_border_label_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-title h5' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'htmega_newsticker_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-title h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style navigation tab section
        $this->start_controls_section(
            'htmega_newsticker_navigation_style',
            [
                'label' => __( 'Navigation', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'navigation_show' =>'yes',
                ],
            ]
        );
            $this->start_controls_tabs('button_style_tabs');

                // Button Normal
                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'htmega_newsticker_button_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .breaking-news-default .breaking-news-nav button i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'htmega_newsticker_button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-nav button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_newsticker_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-nav button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_newsticker_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .breaking-news-default .breaking-news-nav button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'htmega_newsticker_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .breaking-news-default .breaking-news-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_newsticker_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .breaking-news-default .breaking-news-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'htmega_newsticker_button_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .breaking-news-default .breaking-news-nav button:hover i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'htmega_newsticker_button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-nav button:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_newsticker_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-nav button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Style Label tab section
        $this->start_controls_section(
            'htmega_newsticker_date_style',
            [
                'label' => __( 'Date', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'news_ticker_date' =>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'htmega_newsticker_date_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_newsticker_date_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_newsticker_date_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_newsticker_date_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date',
                ]
            );

            $this->add_responsive_control(
                'htmega_newsticker_border_date_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'htmega_newsticker_date_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .breaking-news-default .breaking-news-ticker li span.news_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');

        // Section Attr
        $this->add_render_attribute( 'htmega_newsticker_section_attr', 'class', 'htmega-newsticker breaking-news-default' );
        $this->add_render_attribute( 'htmega_newsticker_section_attr', 'class', 'htmega-newsticker-style-'.$settings['news_ticker_style'] );

        $newsticker_slider_settings = [
            'rowheight'     => absint( $settings['rowheight'] ),
            'maxrows'       => absint( $settings['maxrow'] ),
            'speed'         => absint( $settings['animationspeed'] ),
            'duration'      => absint( $settings['animateduration'] ),
            'autostart'     => ( $settings['autostart'] == 'yes' ? 1 : 0 ),
            'pauseonhover'  => ( $settings['pauseonhover'] == 'yes' ? 1 : 0 ),
            'direction'     => $settings['direction'],
            'navbutton'     => $settings['navigation_show'],
        ];

        // List UL attr
        $this->add_render_attribute('htmega_newsticker_options_attr', 'data-newstrickeropt', wp_json_encode( $newsticker_slider_settings ));
        $this->add_render_attribute( 'htmega_newsticker_options_attr', 'class', 'breaking-news-ticker float-left htmega-newstricker' );

        $args = array(
            'post_type'             => !empty( $settings['news_post_type'] ) ? $settings['news_post_type'] : 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['newslimit'] ) ? $settings['newslimit'] : 3,
        );

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby']    = $orderby;
            $args['order']      = $order;
        }

        if( !empty($settings['news_prod_categories']) ){
            $get_categories = $settings['news_prod_categories'];
        }else{
            $get_categories = $settings['news_categories'];
        }
        $news_cats = str_replace(' ', '', $get_categories);
        if (  !empty( $get_categories ) ) {
            if( is_array($news_cats) && count($news_cats) > 0 ){
                $field_name = is_numeric( $news_cats[0] ) ? 'term_id' : 'slug';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => ( $settings['news_post_type'] == 'product' ) ? 'product_cat' : 'category',
                        'terms' => $news_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }
        $news_ticker = new \WP_Query( $args );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_newsticker_section_attr' ); ?>>
                <div class="breaking-news-title float-left">
                    <?php
                        if( !empty( $settings['ticker_label'] ) ){
                            if( !empty($settings['label_icon']['value']) ){
                                echo '<h5>'.esc_html__($settings['ticker_label'],'htmega-addons').HTMega_Icon_manager::render_icon( $settings['label_icon'], [ 'aria-hidden' => 'true' ] ).'</h5>';
                            }else{
                                echo '<h5>'.esc_html__( $settings['ticker_label'],'htmega-addons' ).'</h5>';
                            }
                        }
                    ?>
                </div>
                <ul <?php echo $this->get_render_attribute_string( 'htmega_newsticker_options_attr' ); ?> >
                    <?php
                        if( $news_ticker->have_posts() ){
                            while ( $news_ticker->have_posts() ) {
                                $news_ticker->the_post();
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_permalink()); ?>">
                                            <?php 
                                                the_title();
                                                if( $settings['news_ticker_date'] == 'yes' ){
                                                    echo '<span class="news_date">'.get_the_time(esc_html__('d M','htmega-addons')).'</span>';
                                                }
                                            ?>
                                        </a>
                                    </li>
                                <?php
                            }
                        }else{
                            ?>
                                <li><a href="#"><?php esc_html_e('Content Not Found','htmega-addons') ?></a></li>
                            <?php
                        }
                        wp_reset_postdata();
                    ?>
                </ul>
                <?php if( $settings['navigation_show'] == 'yes' ): ?>
                    <div class="breaking-news-nav">
                        <button class="news-ticker-prev"><?php echo HTMega_Icon_manager::render_icon( $settings['button_prev_icon'], [ 'aria-hidden' => 'true' ] ); ?></button>
                        <button class="news-ticker-next"><?php echo HTMega_Icon_manager::render_icon( $settings['button_next_icon'], [ 'aria-hidden' => 'true' ] ); ?></button>
                    </div>
                <?php endif; ?>

            </div>

        <?php

    }

}

