<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Business_Hours extends Widget_Base {

    public function get_name() {
        return 'htmega-businesshours-addons';
    }
    
    public function get_title() {
        return __( 'Business Hours', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-countdown';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'businesshours_content',
            [
                'label' => __( 'Business Hours', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'business_hours_layout',
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
                    'separator'=>'after',
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'business_day',
                [
                    'label'   => __( 'Day', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Saturday', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'business_time',
                [
                    'label'   => __( 'Time', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __( '9:00 AM - 6:00 PM', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'highlight_this_day',
                [
                    'label'        => esc_html__( 'Hight Light this day', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'no',
                    'separator'    => 'before',
                ]
            );

            $repeater->add_control(
                'single_business_day_color',
                [
                    'label'     => __( 'Day Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'scheme'    => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'default'   => '#fa2d2d',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-hrs{{CURRENT_ITEM}}.htmega-single-hrs.closed-day span.day' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'highlight_this_day' => 'yes',
                    ],
                    'separator' => 'before',
                ]
            );

            $repeater->add_control(
                'single_business_time_color',
                [
                    'label'     => __( 'Time Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'scheme'    => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_4,
                    ],
                    'default'   => '#fa2d2d',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-hrs{{CURRENT_ITEM}}.htmega-single-hrs.closed-day span.time' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'highlight_this_day' => 'yes',
                    ],
                    'separator' => 'before',
                ]
            );

            $repeater->add_control(
                'single_business_background_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-hrs{{CURRENT_ITEM}}.htmega-single-hrs.closed-day' => 'background-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'highlight_this_day' => 'yes',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'business_openday_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'business_day' => __( 'Saturday', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:00 PM','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Sunday', 'htmega-addons' ),
                            'business_time' => __( 'Close','htmega-addons' ),
                            'highlight_this_day' => __( 'yes','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Monday', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:00 PM','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Tues Day', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:00 PM','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Wednesday', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:00 PM','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Thursday', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:00 PM','htmega-addons' ),
                        ],

                        [
                            'business_day' => __( 'Friday', 'htmega-addons' ),
                            'business_time' => __( '9:00 AM to 6:30 PM','htmega-addons' ),
                        ]
                    ],
                    'title_field' => '{{{ business_day }}}',
                ]
            );
            
        $this->end_controls_section();


        // Style Area section
        $this->start_controls_section(
            'business_item_area_style_section',
            [
                'label' => __( 'Item Area', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'business_item_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-business-hours .business-hrs-inner',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'business_item_area_box_shadow',
                    'label' => __( 'Box Shadow', 'plugin-domain' ),
                    'selector' => '{{WRAPPER}} .htmega-business-hours .business-hrs-inner',
                ]
            );

            $this->add_responsive_control(
                'business_item_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .business-hrs-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'business_item_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .business-hrs-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'business_item_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-business-hours .business-hrs-inner',
                ]
            );

            $this->add_responsive_control(
                'business_item_area_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .business-hrs-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Item section
        $this->start_controls_section(
            'business_item_style_section',
            [
                'label' => __( 'Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'business_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'business_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'business_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs',
                ]
            );

            $this->add_responsive_control(
                'business_item_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Business day section
        $this->start_controls_section(
            'business_day_style_section',
            [
                'label' => __( 'Day', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'business_day_color',
                [
                    'label'     => __( 'color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.day' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'business_day_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.day',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'business_day_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.day',
                ]
            );
            
        $this->end_controls_section();

        // Style Business Time section
        $this->start_controls_section(
            'business_time_style_section',
            [
                'label' => __( 'Time', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'business_time_color',
                [
                    'label'     => __( 'color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.time' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'business_time_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.time',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'business_time_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-business-hours .htmega-single-hrs span.time',
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_business_hours', 'class', 'htmega-business-hours htmega-business-horurs-'.$settings['business_hours_layout'] );
       
        ?>

        <div <?php echo $this->get_render_attribute_string( 'htmega_business_hours' ); ?>>
            <div class="business-hrs-inner">
                <?php
                    foreach ( $settings['business_openday_list'] as $item ):
                ?>

                    <div class="htmega-single-hrs elementor-repeater-item-<?php echo $item['_id']; ?> <?php if( $item['highlight_this_day'] == 'yes' ){ echo esc_attr( 'closed-day','htmega-addons'); }?>">
                        <?php
                            if( !empty( $item['business_day'] ) ){
                                echo '<span class="day">'.esc_html__( $item['business_day'],'htmega-addons' ).'</span>';
                            }
                            if( !empty( $item['business_time'] ) ){
                                echo '<span class="time">'.esc_html__( $item['business_time'],'htmega-addons' ).'</span>';
                            }
                        ?>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>

        <?php

    }

}

