<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Image_Grid extends Widget_Base {

    public function get_name() {
        return 'htmega-imagegrid-addons';
    }
    
    public function get_title() {
        return __( 'Image Grid', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'imagegrid_content',
            [
                'label' => __( 'Image Grid', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'imagegrid_style',
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
                'imagegrid_column',
                [
                    'label' => __( 'Column', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1'   => __( 'One', 'htmega-addons' ),
                        '2'   => __( 'Two', 'htmega-addons' ),
                        '3'   => __( 'Three', 'htmega-addons' ),
                        '4'   => __( 'Four', 'htmega-addons' ),
                        '5'   => __( 'Five', 'htmega-addons' ),
                        '6'   => __( 'Six', 'htmega-addons' ),
                    ],
                ]
            );
            
            $repeater = new Repeater();

            $repeater->add_control(
                'gridimage_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Image Grid Title.','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'gridimage_description',
                [
                    'label'   => __( 'Desciption', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'placeholder' => __('Image Grid Desciption.','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'gridimage_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'gridimage_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'gridimage_btntxt',
                [
                    'label'   => __( 'Read More Text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Read More','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'gridimage_btnlink',
                [
                    'label' => __( 'Read More Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => false,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );

            $this->add_control(
                'imagegrid_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [

                        [
                            'gridimage_title'        => __('Image Grid Title','htmega-addons'),
                            'gridimage_description'  => __( 'Image Grid Desciption','htmega-addons' ),
                            'gridimage_btntxt'       => __( 'Read More', 'htmega-addons' ),
                            'gridimage_btnlink'       => __( '#', 'htmega-addons' ),
                        ],

                    ],
                    'title_field' => '{{{ gridimage_title }}}',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'imagegrid_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'imagegrid_image_overlay_color',
                [
                    'label' => __( 'Overlay Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => 'rgba(0, 0, 0, 0.5)',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .thumb a::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-singleimage-gridstyle-5 .image-grid-content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'imagegrid_image_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab title section
        $this->start_controls_section(
            'imagegrid_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'imagegrid_title_align',
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
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'imagegrid_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'imagegrid_title_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'imagegrid_title_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2',
                ]
            );

            $this->add_responsive_control(
                'imagegrid_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab Desciption section
        $this->start_controls_section(
            'imagegrid_desciption_style_section',
            [
                'label' => __( 'Desciption', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'imagegrid_desciption_align',
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
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'imagegrid_desciption_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'imagegrid_desciption_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p',
                ]
            );

            $this->add_responsive_control(
                'imagegrid_desciption_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab read more button section
        $this->start_controls_section(
            'imagegrid_readmorebtn_style_section',
            [
                'label' => __( 'Read More Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'readmorebtn_style_tabs' );

                $this->start_controls_tab(
                    'readmorebtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'imagegrid_readmorebtn_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagegrid_readmorebtn_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagegrid_readmorebtn_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Read More button normal tab end

                $this->start_controls_tab(
                    'readmorebtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'imagegrid_readmorebtn_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => Scheme_Color::get_type(),
                                'value' => Scheme_Color::COLOR_1,
                            ],
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'imagegrid_readmorebtn_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagegrid_readmorebtn_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Read More button hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'imagegrid_attr', 'class', 'htmega-gridimage-area htmega-image-gridstyle-'.$settings['imagegrid_style'] );
        $this->add_render_attribute( 'imagegrid_item_attr', 'class', 'htmega-singleimage-grid htmega-singleimage-gridstyle-'.$settings['imagegrid_style'] );

        $columns = $settings['imagegrid_column'];
        $collumval = 'htb-col-md-4 htb-col-sm-6 htb-col-12';
        if( $columns != 5 ){
            $colwidth = round(12/$columns);
            $collumval = 'htb-col-md-'.$colwidth.' htb-col-sm-6 htb-col-12';
        }else{
            $collumval = 'custom-col-5';
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'imagegrid_attr' ); ?> >
                <div class="htb-row">
                    <?php
                        foreach ( $settings['imagegrid_list'] as $imagegrid ):
                        ?>
                            <div class="<?php echo esc_attr__( $collumval,'htmega-addons' );?>">
                                <div <?php echo $this->get_render_attribute_string( 'imagegrid_item_attr' ); ?> >
                                    <div class="thumb">
                                        <?php
                                            if( !empty( $imagegrid['gridimage_btnlink']['url'] ) ){
                                                echo '<a href="'.esc_url( $imagegrid['gridimage_btnlink']['url'] ).'">'.Group_Control_Image_Size::get_attachment_image_html( $imagegrid, 'gridimage_imagesize', 'gridimage_image' ).'</a>';
                                            }else{
                                                echo Group_Control_Image_Size::get_attachment_image_html( $imagegrid, 'gridimage_imagesize', 'gridimage_image' ); 
                                            }
                                        ?>
                                    </div>
                                    <?php if( !empty( $imagegrid['gridimage_title'] ) || !empty( $imagegrid['gridimage_description'] ) || ! empty( $imagegrid['gridimage_btntxt'] ) ): ?>
                                        <div class="image-grid-content">
                                            <div class="hover-action">
                                                <?php 
                                                    if( !empty( $imagegrid['gridimage_title'] )){
                                                        echo '<h2>'.esc_html__( $imagegrid['gridimage_title'], 'htmega-addons' ).'</h2>';
                                                    }

                                                    if( !empty( $imagegrid['gridimage_description'] )){
                                                        echo '<p>'.esc_html__( $imagegrid['gridimage_description'], 'htmega-addons' ).'</p>';
                                                    }

                                                    if ( ! empty( $imagegrid['gridimage_btnlink']['url'] ) ) {
                                                        echo sprintf( '<a class="read-btn" href="%1$s">%2$s</a>', $imagegrid['gridimage_btnlink']['url'], $imagegrid['gridimage_btntxt'] );
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>

                        <?php
                        endforeach;
                    ?>
                </div>
            </div>

        <?php

    }

}