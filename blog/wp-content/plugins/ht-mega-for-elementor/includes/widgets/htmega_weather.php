<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Weather extends Widget_Base {

    public function get_name() {
        return 'htmega-weather-addons';
    }
    
    public function get_title() {
        return __( 'Weather', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-captcha';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'weather_content',
            [
                'label' => __( 'Weather', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'location',
                [
                    'label'   => __( 'Location', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'Dhaka',
                ]
            );

            $this->add_control(
                'overridetitle',
                [
                    'label'   => __( 'Override Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => '',
                ]
            );

            $this->add_control(
                'units',
                [
                    'label'   => __( 'Units', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'C',
                    'options' => [
                        'F'   => __( 'F', 'htmega-addons' ),
                        'C'   => __( 'C', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'sizelayout',
                [
                    'label'   => __( 'Size', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'tall',
                    'options' => [
                        'tall'  => __( 'Tall', 'htmega-addons' ),
                        'wide'  => __( 'Wide', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'forecast',
                [
                    'label'   => __( 'Forecast', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '4' => __( '4 Days', 'htmega-addons' ),
                        '3' => __( '3 Days', 'htmega-addons' ),
                        '2' => __( '2 Days', 'htmega-addons' ),
                        '1' => __( '1 Days', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'hidestate',
                [
                    'label'   => __( 'Hide Current Condition Stats', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_link',
                [
                    'label'   => __( 'Link to Extended Forecast', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'hide_attribution',
                [
                    'label'   => __( 'Hide Weather Attribution', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'background_by_weather',
                [
                    'label'   => __( 'Background By Weather', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'custom_bg_color',
                [
                    'label' => __( 'Custom Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ]
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => __( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ]
                ]
            );
            
        $this->end_controls_section();

        
        // Title Style
        $this->start_controls_section(
            'weather_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'weather_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#fff',
                    'selectors' => [
                        '{{WRAPPER}} .awesome-weather-wrap.darken .awesome-weather-header' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'weather_title_typography',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .awesome-weather-wrap.darken .awesome-weather-header',
                ]
            );

            $this->add_responsive_control(
                'weather_title_padding',
                [
                    'label'      => __( 'Padding', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .awesome-weather-wrap.darken .awesome-weather-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'weather_title_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .awesome-weather-wrap.darken .awesome-weather-header',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'weather_title_shadow',
                    'label'     => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .awesome-weather-wrap.darken .awesome-weather-header',
                ]
            );

        $this->end_controls_section();

        // Day style
        $this->start_controls_section(
            'weather_days_style_section',
            [
                'label' => __( 'Days', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'weather_days_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .awesome-weather-forecast-day' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'weather_days_typography',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .awesome-weather-forecast-day',
                ]
            );

        $this->end_controls_section();

        // Unit style
        $this->start_controls_section(
            'weather_unit_style_section',
            [
                'label' => __( 'Unit', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'weather_unit_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#fff',
                    'selectors' => [
                        '{{WRAPPER}} .awesome-weather-current-temp strong' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'weather_unit_typography',
                    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .awesome-weather-current-temp strong',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $hide_stats = $settings['hidestate'] == 'yes' ? 1 : 0;
        $show_link = $settings['show_link'] == 'yes' ? 1 : 0;
        $hide_attribution = $settings['hide_attribution'] == 'yes' ? 1 : 0;
        $background_by_weather = $settings['background_by_weather'] == 'yes' ? 1 : 0;
       
        echo awesome_weather_logic( array(
            'location' => $settings['location'], 
            'override_title' => $settings['overridetitle'], 
            'units' => $settings['units'],
            'size' => $settings['sizelayout'], 
            'forecast_days' => $settings['forecast'], 
            'hide_stats' => $hide_stats, 
            'show_link' => $show_link, 
            'hide_attribution' => $hide_attribution,
            'background_by_weather' => $background_by_weather,
            'custom_bg_color' => $settings['custom_bg_color'],
            'text_color' => $settings['text_color'],
            'background' => ' ',
        ));

    }

}

