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

class Bl_Post_Search_Form_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-search-form';
    }

    public function get_title() {
        return __( 'BL: Post Search Form', 'ht-builder' );
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'post_search_form_section',
            [
                'label' => __( 'Search Form', 'ht-builder' ),
            ]
        );

            $this->add_control(
                'placeholdertxt',
                [
                    'label' => __( 'Placeholder', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search ...', 'ht-builder' ),
                ]
            );

            $this->add_control(
                'button_type',
                [
                    'label' => __( 'Button Type', 'ht-builder' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text' => [
                            'title' => __( 'Text', 'ht-builder' ),
                            'icon' => 'eicon-type-tool',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'ht-builder' ),
                            'icon' => 'eicon-editor-italic',
                        ],
                    ],
                    'default' => 'icon',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' => __( 'Icon', 'ht-builder' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-search',
                    'condition' => [
                        'button_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __( 'Button Text', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search', 'ht-builder' ),
                    'placeholder' => __( 'Enter you text', 'ht-builder' ),
                    'condition' => [
                        'button_type' => 'text',
                    ]
                ]
            );

        $this->end_controls_section();

        // Input Box Style
        $this->start_controls_section(
            'post_search_inputbox_style_section',
            array(
                'label' => __( 'Input Box', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_search_inputbox_color',
                [
                    'label'     => __( 'Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_search_inputbox_typography',
                    'label'     => __( 'Typography', 'ht-builder' ),
                    'selector'  => '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_search_inputbox_border',
                    'label' => __( 'Border', 'ht-builder' ),
                    'selector' => '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input',
                ]
            );

            $this->add_responsive_control(
                'post_search_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_search_inputbox_padding',
                [
                    'label' => __( 'Padding', 'ht-builder' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'post_search_button_style_section',
            array(
                'label' => __( 'Button', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('search_button_style_tabs');

                // Submit Button Normal
                $this->start_controls_tab(
                    'search_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'ht-builder' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_search_button_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_search_button_bg_color',
                        [
                            'label'     => __( 'Background Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_search_button_typography',
                            'label'     => __( 'Typography', 'ht-builder' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_search_button_border',
                            'label' => __( 'Border', 'ht-builder' ),
                            'selector' => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_search_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'ht-builder' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_search_button_padding',
                        [
                            'label' => __( 'Padding', 'ht-builder' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Submit Button Hover
                $this->start_controls_tab(
                    'search_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'ht-builder' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_search_button_hover_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_search_button_hover_bg_color',
                        [
                            'label'     => __( 'Background Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_search_button_hover_border',
                            'label' => __( 'Border', 'ht-builder' ),
                            'selector' => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute(
            'inputattr', [
                'placeholder' => $settings['placeholdertxt'],
                'class' => 'htbuilder-search-form-input',
                'type' => 'search',
                'name' => 's',
                'title' => __( 'Search', 'ht-builder' ),
                'value' => get_search_query(),
            ]
        );

        
        ?>
            <form class="htbuilder-search-form" role="search" action="<?php echo home_url(); ?>" method="get">
                <input <?php echo $this->get_render_attribute_string( 'inputattr' ); ?>>
                <button class="htbuilder-submit" type="submit">
                    <?php
                        if( $settings['button_type'] == 'text' ){
                            echo esc_html__( $settings['button_text'], 'ht-builder' );
                        }else{
                            echo '<i class="'.$settings['button_icon'].'"></i>';
                        }
                    ?>
                </button>
            </form>
        <?php

    }


}
