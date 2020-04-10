<?php

namespace HTMega_Builder\Elementor\Widget;
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Excerpt_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-excerpt';
    }

    public function get_title() {
        return __( 'BL: Post Excerpt', 'ht-builder' );
    }

    public function get_icon() {
        return 'eicon-post-excerpt';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function _register_controls() {


        // Style
        $this->start_controls_section(
            'post_excerpt_style_section',
            array(
                'label' => __( 'Post Excerpt', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_excerpt_color',
                [
                    'label'     => __( 'Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_excerpt_typography',
                    'label'     => __( 'Typography', 'ht-builder' ),
                    'selector'  => '{{WRAPPER}}',
                )
            );

            $this->add_responsive_control(
                'post_excerpt_align',
                [
                    'label'        => __( 'Alignment', 'ht-builder' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'ht-builder' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'ht-builder' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'ht-builder' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'ht-builder' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $post = get_post();
        
        if( Elementor::instance()->editor->is_edit_mode() ){
            echo '<h3>' . __('Post Excerpt', 'ht-builder' ). '</h3>';
        }else{
            if ( post_password_required( $post->ID ) ) {
                echo get_the_password_form( $post->ID );
                return;
            }
            echo apply_filters( 'the_excerpt', get_the_excerpt() );
        }

    }

}
