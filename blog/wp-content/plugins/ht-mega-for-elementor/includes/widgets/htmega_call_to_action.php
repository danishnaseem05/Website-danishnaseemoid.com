<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Call_To_Action extends Widget_Base {

    public function get_name() {
        return 'htmega-calltoaction-addons';
    }
    
    public function get_title() {
        return __( 'Call To Action', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-call-to-action';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'callto_action_content',
            [
                'label' => __( 'Call To Action', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'callto_action_style',
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
                'callto_action_title',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Type your title here...', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'callto_action_description',
                [
                    'label' => __( 'Description', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Type your description here...', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'callto_action_buttontxt',
                [
                    'label' => __( 'Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Button Text', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'callto_action_button_link',
                [
                    'label' => __( 'Button Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'condition'=>[
                        'callto_action_buttontxt!'=>'',
                    ]
                ]
            );

            $this->add_control(
                'callto_action_title_tag',
                [
                    'label' => __( 'Title Tag', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h1',
                    'condition'=>[
                        'callto_action_title!'=>'',
                    ]
                ]
            );

            $this->add_control(
                'callto_action_description_tag',
                [
                    'label' => __( 'Description Tag', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'p',
                    'condition'=>[
                        'callto_action_description!'=>'',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'callto_action_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'callto_section_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-call-to-action',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'callto_section_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-call-to-action',
                ]
            );

            $this->add_responsive_control(
                'callto_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'callto_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'callto_section_align',
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
                        '{{WRAPPER}} .htmega-call-to-action' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'prefix_class' => 'htmega-align%s-',
                ]
            );

        $this->end_controls_section();


        // Style Title tab section
        $this->start_controls_section(
            'callto_action_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'callto_action_title!'=>'',
                ]
            ]
        );

            $this->add_control(
                'callto_action_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#f7ca18',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-title' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'callto_action_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-title',
                ]
            );

            $this->add_responsive_control(
                'callto_action_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'callto_action_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Description tab section
        $this->start_controls_section(
            'callto_action_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'callto_action_description!'=>'',
                ]
            ]
        );

            $this->add_control(
                'callto_action_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#5D532BE6',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-description' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'callto_action_description_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-description',
                ]
            );

            $this->add_responsive_control(
                'callto_action_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'callto_action_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-call-to-action .htmega-content .htmega-callto-action-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Button tab section
        $this->start_controls_section(
            'callto_action_button_style_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'callto_action_buttontxt!'=>'',
                ]
            ]
        );

            $this->start_controls_tabs('button_style_tabs');

                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'button_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#000000',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'button_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'button_hover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#000000',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn:hover',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-call-to-action .htmega-content a.call_btn:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_callto_action_attr', 'class', 'htmega-call-to-action callto-action-style-'.$settings['callto_action_style'] );

        $this->add_render_attribute( 'callto_title_attr', 'class', 'htmega-callto-action-title' );
        $this->add_render_attribute( 'callto_description_attr', 'class', 'htmega-callto-action-description' );

        // URL Generate
        if ( ! empty( $settings['callto_action_button_link']['url'] ) ) {
            
            $this->add_render_attribute( 'url', 'class', 'call_btn' );
            $this->add_render_attribute( 'url', 'href', $settings['callto_action_button_link']['url'] );

            if ( $settings['callto_action_button_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['callto_action_button_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_callto_action_attr' ); ?>>
                <div class="htmega-content">

                    <?php if( $settings['callto_action_style'] == 2 ): ?>
                        <div class="htb-row htb-align-items-center">
                            <div class="htb-col-lg-9">
                                <div class="ht-call-to-action">
                                    <div class="content">
                                        <?php
                                            if( !empty( $settings['callto_action_title'] ) ){
                                                echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_title_tag'], $this->get_render_attribute_string( 'callto_title_attr' ), $settings['callto_action_title'] );
                                            }
                                            if( !empty( $settings['callto_action_description'] ) ){
                                                echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_description_tag'], $this->get_render_attribute_string( 'callto_description_attr' ), $settings['callto_action_description'] );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="htb-col-lg-3">
                                <div class="text-right">
                                    <?php
                                        if( !empty( $settings['callto_action_buttontxt'] ) ){
                                            echo sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['callto_action_buttontxt'] );
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php elseif( $settings['callto_action_style'] == 3 ): ?>
                        <div class="content">
                            <?php
                                if( !empty( $settings['callto_action_description'] ) ){
                                    echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_description_tag'], $this->get_render_attribute_string( 'callto_description_attr' ), $settings['callto_action_description'] );
                                }
                                if( !empty( $settings['callto_action_title'] ) ){
                                    echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_title_tag'], $this->get_render_attribute_string( 'callto_title_attr' ), $settings['callto_action_title'] );
                                }
                            ?>
                        </div>
                        <div class="action-btn">
                            <?php
                                if( !empty( $settings['callto_action_buttontxt'] ) ){
                                    echo sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['callto_action_buttontxt'] );
                                }
                            ?>
                        </div>

                    <?php elseif( $settings['callto_action_style'] == 4 || $settings['callto_action_style'] == 5 || $settings['callto_action_style'] == 6 ): ?>
                        <div class="content">
                            <?php
                                if( !empty( $settings['callto_action_title'] ) ){
                                    echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_title_tag'], $this->get_render_attribute_string( 'callto_title_attr' ), $settings['callto_action_title'] );
                                }
                                if( !empty( $settings['callto_action_description'] ) ){
                                    echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_description_tag'], $this->get_render_attribute_string( 'callto_description_attr' ), $settings['callto_action_description'] );
                                }
                            ?>
                        </div>
                        <div class="action-btn">
                            <?php
                                if( !empty( $settings['callto_action_buttontxt'] ) ){
                                    echo sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['callto_action_buttontxt'] );
                                }
                            ?>
                        </div>
                        
                    <?php elseif( $settings['callto_action_style'] == 7 ):?>
                        <div class="call-to-action-inner">
                            <div class="content">
                                <?php
                                    if( !empty( $settings['callto_action_title'] ) ){
                                        echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_title_tag'], $this->get_render_attribute_string( 'callto_title_attr' ), $settings['callto_action_title'] );
                                    }
                                    if( !empty( $settings['callto_action_description'] ) ){
                                        echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_description_tag'], $this->get_render_attribute_string( 'callto_description_attr' ), $settings['callto_action_description'] );
                                    }
                                ?>
                            </div>
                            <div class="action-btn">
                                <?php
                                    if( !empty( $settings['callto_action_buttontxt'] ) ){
                                        echo sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['callto_action_buttontxt'] );
                                    }
                                ?>
                            </div>
                        </div>

                    <?php else:?>
                        <?php
                            if( !empty( $settings['callto_action_description'] ) ){
                                echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_description_tag'], $this->get_render_attribute_string( 'callto_description_attr' ), $settings['callto_action_description'] );
                            }
                            if( !empty( $settings['callto_action_title'] ) ){
                                echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['callto_action_title_tag'], $this->get_render_attribute_string( 'callto_title_attr' ), $settings['callto_action_title'] );
                            }
                            if( !empty( $settings['callto_action_buttontxt'] ) ){
                                echo sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $settings['callto_action_buttontxt'] );
                            }
                        ?>
                    <?php endif;?>

                </div>
            </div>

        <?php
    }

}