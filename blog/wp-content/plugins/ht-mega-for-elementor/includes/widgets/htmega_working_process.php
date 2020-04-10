<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Working_Process extends Widget_Base {

    public function get_name() {
        return 'htmega-working-process-addons';
    }
    
    public function get_title() {
        return __( 'Working Process', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-import-export';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'process_content',
            [
                'label' => __( 'Working Process', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'process_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style One', 'htmega-addons' ),
                        '2' => __( 'Style Two', 'htmega-addons' ),
                        '3' => __( 'Style Three', 'htmega-addons' ),
                        '4' => __( 'Style Four', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'process_column',
                [
                    'label' => __( 'Column', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '1' => __( 'Column One', 'htmega-addons' ),
                        '2' => __( 'Column Two', 'htmega-addons' ),
                        '3' => __( 'Column Three', 'htmega-addons' ),
                        '4' => __( 'Column Four', 'htmega-addons' ),
                        '5' => __( 'Column Five', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'process_style'=> array(  '1','2' ),
                    ]
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'process_title',
                [
                    'label'   => esc_html__( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Process #1', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'process_number',
                [
                    'label'   => esc_html__( 'Process Number', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                ]
            );

            $repeater->add_control(
                'process_description',
                [
                    'label'   => esc_html__( 'Description', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud exerci ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in repre in voluptate.','htmega-addons', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'process_icon_type',
                [
                    'label' => esc_html__('Icon Type','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'options' =>[
                        'img' =>[
                            'title' =>__('Image','htmega-addons'),
                            'icon' =>'fa fa-picture-o',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'fa fa-info',
                        ]
                    ],
                    'default' =>'img',
                ]
            );

            $repeater->add_control(
                'process_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'process_icon_type' => 'img',
                    ]
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'process_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'process_icon_type' => 'img',
                    ]
                ]
            );

            $repeater->add_control(
                'process_icon',
                [
                    'label' =>__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fa fa-pencil-alt',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'process_icon_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'htmega_process_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'process_title' => esc_html__( 'Process #1', 'htmega-addons' ),
                            'process_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore','htmega-addons' ),
                        ],
                        [
                            'process_title' => esc_html__( 'Process #2', 'htmega-addons' ),
                            'process_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore.','htmega-addons' ),
                        ],
                        [
                            'process_title' => esc_html__( 'Process #3', 'htmega-addons' ),
                            'process_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore.','htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ process_title }}}',
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'process_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'working_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-process-area',
                ]
            );

            $this->add_responsive_control(
                'working_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'working_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Process Item tab section
        $this->start_controls_section(
            'process_item_style_section',
            [
                'label' => __( 'Item style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'working_item_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-process',
                    'separator'=> 'before',
                    'condition'=>[
                        'process_style!' =>'4',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'working_item_4_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-process-area',
                    'separator'=> 'before',
                    'condition'=>[
                        'process_style' =>'4',
                    ]
                ]
            );

            $this->add_responsive_control(
                'working_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-process' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'working_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-process' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'working_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-process',
                ]
            );

            $this->add_responsive_control(
                'working_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-process' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();


        // Process Title tab section
        $this->start_controls_section(
            'process_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#555555',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content h4' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-content h4',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-content h4',
                ]
            );

            $this->add_responsive_control(
                'title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content h4' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Process Description tab section
        $this->start_controls_section(
            'process_content_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
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
                    'default' => '#494849',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-content p',
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
                        '{{WRAPPER}} .htmega-process-area .htmega-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-content p',
                ]
            );

            $this->add_responsive_control(
                'content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-content p' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Process Description tab section
        $this->start_controls_section(
            'process_number_style_section',
            [
                'label' => __( 'Number', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'number_shape_background',
                    'label' => __( 'Item Number Shape', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-number::before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'number_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-number span',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'number_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-number span',
                ]
            );

            $this->add_control(
                'number_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#5a5a5a',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-number span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'number_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-number span',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'number_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-number span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'number_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-number span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'number_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-process-area .htmega-number span',
                ]
            );

            $this->add_responsive_control(
                'number_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-process-area .htmega-number span' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_process_attr', 'class', 'htmega-process-area htmega-process-style-'.$settings['process_style'] );
        if( isset( $settings['process_column'] ) ){
            $this->add_render_attribute( 'htmega_process_attr', 'class', 'htmega-column htmega-process-column-'.$settings['process_column'] );
        }

        $active_process_class = '';
        ?>
        <div <?php echo $this->get_render_attribute_string( 'htmega_process_attr' ); ?>>

            <?php 
                $i = 0; 
                foreach ( $settings['htmega_process_list'] as $item ) : 
                    $i++;
                    if( $settings['process_style'] == 4 && $i == 1 ){
                        $active_process_class = 'open';
                    }else{
                        $active_process_class = '';
                    }
            ?>
                <div class="htmega-single-process-area">
                    <div class="htmega-single-process <?php echo esc_attr( $active_process_class ); if( $i%2 == 0 ){ echo 'process-reverse'; }?>">
                        <?php
                            if( $item['process_icon_type'] == 'img' && !empty( $item['process_image']['url'] ) ) {
                                echo '<div class="icon">'.Group_Control_Image_Size::get_attachment_image_html( $item, 'process_imagesize', 'process_image' ).'</div>';
                            }else{
                                if( $item['process_icon_type'] == 'icon' && !empty( $item['process_icon']['value'] ) ){
                                    echo '<div class="icon">'.HTMega_Icon_manager::render_icon( $item['process_icon'], [ 'aria-hidden' => 'true' ] ).'</div>';
                                }
                            }
                        ?>
                        <?php 
                            if( !empty( $item['process_number'] ) ){
                                echo '<div class="htmega-number"><span>'.esc_html__( $item['process_number'],'htmega-addons' ).'</span></div>';
                            }
                        ?>
                        <div class="htmega-content">
                            <?php
                                if( !empty( $item['process_title'] ) ){
                                    echo '<h4>'.esc_html__( $item['process_title'],'htmega-addons' ).'</h4>';
                                }
                                if( !empty( $item['process_description'] ) ){
                                    echo '<p>'.esc_html__( $item['process_description'],'htmega-addons' ).'</p>';
                                }
                            ?>
                        </div>
                    </div>          
                </div>
            <?php endforeach;?>

        </div>
        <?php
    }

}

