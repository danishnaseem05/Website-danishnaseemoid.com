<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Dropcaps extends Widget_Base {

    public function get_name() {
        return 'htmega-dropcaps-addons';
    }
    
    public function get_title() {
        return __( 'Dropcaps', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-editor-paragraph';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'dropcaps_content',
            [
                'label' => __( 'Dropcaps', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'dropcaps_style',
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
                    ],
                ]
            );

            $this->add_control(
                'dropcaps_text',
                [
                    'label'         => __( 'Content', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXTAREA,
                    'default'       => __( 'Lorem ipsum dolor sit amet, consec adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exl Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.', 'htmega-addons' ),
                    'placeholder'   => __( 'Enter Your Dropcaps Content.', 'htmega-addons' ),
                    'separator'=>'before',
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_dropcaps_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
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
                    'default' => '#434343',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p,{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'content_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner',
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner',
                ]
            );

            $this->add_responsive_control(
                'content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style dropcaps latter tab section
        $this->start_controls_section(
            'htmega_dropcaps_latter_style_section',
            [
                'label' => __( 'Dropcap Latter', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'content_dropcaps_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#d6d6d6',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_dropcaps_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter,{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'content_dropcaps_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter,{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter',
                ]
            );

            $this->add_responsive_control(
                'content_dropcaps_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'content_dropcaps_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_dropcaps_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter',
                    'selector' => '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter',
                ]
            );

            $this->add_responsive_control(
                'content_dropcaps_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner:first-of-type:first-letter' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-dropcaps-area .htmega-dropcaps-inner p:first-of-type:first-letter' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_dropcaps_attr', 'class', 'htmega-dropcaps-area' );
        $this->add_render_attribute( 'htmega_dropcaps_attr', 'class', 'htmega-dropcaps-style-'.$settings['dropcaps_style'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_dropcaps_attr' ); ?>>
                <?php
                    if( !empty( $settings['dropcaps_text'] ) ){
                        echo '<div class="htmega-dropcaps-inner">'.wpautop( $settings['dropcaps_text'] ).'</div>';
                    }
                ?>
            </div>

        <?php
    }

}

