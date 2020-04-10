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

class Bl_Post_Title_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-single-blog-title';
    }

    public function get_title() {
        return __( 'BL: Post Title', 'ht-builder' );
    }

    public function get_icon() {
        return 'eicon-post-title';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function _register_controls() {


        // Post Title
        $this->start_controls_section(
            'blog_title_content',
            [
                'label' => __( 'Post Title', 'ht-builder' ),
            ]
        );
            $this->add_control(
                'blog_title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'ht-builder' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h1',
                ]
            );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'blog_title_style_section',
            array(
                'label' => __( 'Post Title', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'blog_title_color',
                [
                    'label'     => __( 'Title Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .entry-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'blog_title_typography',
                    'label'     => __( 'Typography', 'ht-builder' ),
                    'selector'  => '{{WRAPPER}} .entry-title',
                )
            );

            $this->add_responsive_control(
                'blog_title_margin',
                [
                    'label' => __( 'Margin', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'blog_title_align',
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
        $settings   = $this->get_settings_for_display();
        if( Elementor::instance()->editor->is_edit_mode() ){
            echo sprintf( '<%1$s class="entry-title">' . __('Blog Title', 'ht-builder' ). '</%1$s>', $settings['blog_title_html_tag'] );
        }else{
            echo sprintf( the_title( '<%1$s class="entry-title">', '</%1$s>', false ), $settings['blog_title_html_tag']  );
        }

    }

}
