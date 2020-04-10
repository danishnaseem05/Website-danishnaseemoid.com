<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Animated_Heading extends Widget_Base {

    public function get_name() {
        return 'htmega-animatedheading-addons';
    }
    
    public function get_title() {
        return __( 'Animated Heading', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-animated-headline';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'animated-heading',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'animatedheading_content',
            [
                'label' => __( 'Animated Heading', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'layout_style',
                [
                    'label'   => __( 'Layout', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style One', 'htmega-addons' ),
                        '2' => __( 'Style Two', 'htmega-addons' ),
                        '3' => __( 'Style Three', 'htmega-addons' ),
                        '4' => __( 'Style Four', 'htmega-addons' ),
                        '5' => __( 'Style Five', 'htmega-addons' ),
                        '6' => __( 'Style Six', 'htmega-addons' ),
                        '7' => __( 'Style Seven', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'animation_type',
                [
                    'label'   => __( 'Animation Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'slide',
                    'options' => [
                        'type'          => __( 'Type', 'htmega-addons' ),
                        'loading-bar'   => __( 'Loading bar', 'htmega-addons' ),
                        'slide'         => __( 'Slide', 'htmega-addons' ),
                        'clip'          => __( 'Clip', 'htmega-addons' ),
                        'zoom'          => __( 'Zoom', 'htmega-addons' ),
                        'scale'         => __( 'Scale', 'htmega-addons' ),
                        'push'          => __( 'Push', 'htmega-addons' ),
                        'rotate-1'      => __( 'Rotate Style One', 'htmega-addons' ),
                        'rotate-2'      => __( 'Rotate Style Two', 'htmega-addons' ),
                        'rotate-3'      => __( 'Rotate Style Three', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'layout_style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'animated_before_text',
                [
                    'label' => __( 'Heading Before Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Honesty is the best policy', 'htmega-addons' ),
                    'label_block' => true,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'animated_heading_text',
                [
                    'label'       => __( 'Animated Heading Text', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXTAREA,
                    'default'     => __( "Purpose,policy,Company", 'htmega-addons' ),
                    'condition'=>[
                        'layout_style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'visible_items',
                [
                    'label' => __( 'Visible Item Number', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'condition'=>[
                        'layout_style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'animated_after_text',
                [
                    'label' => __( 'Heading After Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'separator' => 'before',
                ]
            );
            
        $this->end_controls_section();

        // Before Style tab section
        $this->start_controls_section(
            'animated_heading_beforetext_style',
            [
                'label' => __( 'Before Text Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'animated_before_text!'=>'',
                ]
            ]
        );
            $this->add_control(
                'heading_before_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_before_text_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_before_text_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext',
                ]
            );

            $this->add_responsive_control(
                'heading_before_text_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_before_text_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'heading_before_text_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext',
                ]
            );

            $this->add_responsive_control(
                'heading_before_text_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.beforetext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // After Style tab section
        $this->start_controls_section(
            'animated_heading_aftertext_style',
            [
                'label' => __( 'After Text Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'animated_after_text!'=>'',
                ]
            ]
        );
            $this->add_control(
                'heading_after_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_after_text_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_after_text_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext',
                ]
            );

            $this->add_responsive_control(
                'heading_after_text_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_after_text_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'heading_after_text_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext',
                ]
            );

            $this->add_responsive_control(
                'heading_after_text_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading h4 span.aftertext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Animated text Style tab section
        $this->start_controls_section(
            'animated_heading_text_style',
            [
                'label' => __( 'Animated Text Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'layout_style!'=>'2',
                ]
            ]
        );
            $this->add_control(
                'heading_animated_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_animated_text_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_animated_text_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b',
                ]
            );

            $this->add_responsive_control(
                'heading_animated_text_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_animated_text_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'heading_animated_text_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b',
                ]
            );

            $this->add_responsive_control(
                'heading_animated_text_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-animated-heading .cd-words-wrapper b' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'heading_area_attr', 'class', 'htmega-animated-heading htmega-style-'.$settings['layout_style'] );
        $this->add_render_attribute( 'heading_attr', 'class', 'cd-headline '.$settings['animation_type'] );
        $animatedheading_text = explode(",", esc_html( $settings['animated_heading_text'] ) );
       
        ?>

            <div <?php echo $this->get_render_attribute_string( 'heading_area_attr' ); ?> >
                <h4 <?php echo $this->get_render_attribute_string( 'heading_attr' ); ?>>
                    <?php
                        if( !empty( $settings['animated_before_text'] ) ){
                            echo '<span class="beforetext">'.esc_html__( $settings['animated_before_text'], 'htmega-addons' ).'</span>';
                        }

                        if( is_array( $animatedheading_text ) && count( $animatedheading_text ) > 0 ):
                    ?>
                    <span class="cd-words-wrapper">
                        <?php
                            $i = 0; 
                            foreach ( $animatedheading_text as $animatedheadintext ) {
                                $i++;
                                if( $i == $settings['visible_items'] ){
                                    echo '<b class="is-visible" >'.esc_html__( $animatedheadintext, 'htmega-addons' ).'</b>';
                                }else{
                                    echo '<b>'.esc_html__( $animatedheadintext, 'htmega-addons' ).'</b>';
                                }
                            }
                        ?>
                    </span>
                    <?php
                        endif;
                        if( !empty( $settings['animated_after_text'] ) ){
                            echo '<span class="aftertext">'.esc_html__( $settings['animated_after_text'], 'htmega-addons' ).'</span>';
                        }
                    ?>
                </h4>
            </div>

        <?php

    }

}

