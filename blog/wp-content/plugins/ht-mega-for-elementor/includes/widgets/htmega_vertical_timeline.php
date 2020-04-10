<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Verticle_Time_Line extends Widget_Base {

    public function get_name() {
        return 'htmega-verticletimeline-addons';
    }
    
    public function get_title() {
        return __( 'Verticle Timeline', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-time-line';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'button_content',
            [
                'label' => __( 'Verticle Timeline', 'htmega-addons' ),
            ]
        );

            $this->add_control(
              'verticle_timeline_layout',
                [
                'label'         => esc_html__( 'Layout', 'htmega-addons' ),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => '1',
                    'label_block'   => false,
                    'options'       => [
                        '1'    => esc_html__( 'Layout One', 'htmega-addons' ),
                        '2'   => esc_html__( 'Layout Two', 'htmega-addons' ),
                        '3'   => esc_html__( 'Layout Three', 'htmega-addons' ),
                    ],
                ]
            );
            
        $this->end_controls_section();

         // Timeline Content
        $this->start_controls_section(
            'verticle_timeline_content',
            [
                'label' => __( 'Content', 'htmega-addons' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'content_date',
                [
                    'label'   => __( 'Content Date', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Sep<br/>2018', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'content_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                ]
            );

            $repeater->add_control(
                'content_text',
                [
                    'label' => __( 'Content', 'htmega-addons' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'custom_content_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'content_date' => __( 'Sep<br/>2018', 'htmega-addons' ),
                            'content_text' => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmega-addons' ),
                        ],
                        [
                            'content_date' => __( 'Oct<br/>2018', 'htmega-addons' ),
                            'content_text' => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmega-addons' ),
                        ],
                        [
                            'content_date' => __( 'Aug<br/>2018', 'htmega-addons' ),
                            'content_text' => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{ content_date }}}',
                ]
            );

        $this->end_controls_section();

        // Title Style tab section
        $this->start_controls_section(
            'verticle_timeline_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'content_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper > div .timeline-content h6.time_line_title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htc-verctimeline-wrapper > div .timeline-content h6.time_line_title',
                ]
            );
            
        $this->end_controls_section();

        // Content Style tab section
        $this->start_controls_section(
            'verticle_timeline_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'content_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper > div .timeline-content' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_text_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htc-verctimeline-wrapper > div .timeline-content',
                ]
            );
            
        $this->end_controls_section();

        // Date Style tab section
        $this->start_controls_section(
            'verticle_timeline_date_style_section',
            [
                'label' => __( 'Date', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'content_date_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper > div .vertical-date span.month' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_date_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htc-verctimeline-wrapper > div .vertical-date span.month',
                ]
            );

            $this->add_control(
                'timeline_border_color',
                [
                    'label'     => __( 'Timeline Primary Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline .vertical-time .vertical-date' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline .vertical-time .vertical-date::before' => 'border-color: transparent transparent transparent {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline.vertical-reverse .vertical-time .vertical-date::before' => 'border-color: transparent {{VALUE}} transparent transparent;',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline .timeline-content::before' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'timeline_line_color',
                [
                    'label'     => __( 'Timeline Line Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline .vertical-time::before' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper.htmega-verticletimeline-style-2::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline--2 .vertical-time::before' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline--3::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline--3 .vertical-time .vertical-date span' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'timeline_line_hover_color',
                [
                    'label'     => __( 'Timeline Hover Line Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'',
                    'selectors' => [
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline--2::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htc-verctimeline-wrapper .ht-ver-timeline--2:hover .vertical-time::before' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'verticle_timeline_layout' =>'2',
                    ],
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'verticle_timeline_attr', 'class', 'htc-verctimeline-wrapper htmega-verticletimeline-style-'.$settings['verticle_timeline_layout'] );

        $item_class = 'ht-ver-timeline';
        if( $settings['verticle_timeline_layout'] > 1 ){
            $item_class = 'ht-ver-timeline--'.$settings['verticle_timeline_layout'];
        }else{
            $item_class = $item_class;
        }
       
        ?>
        <div <?php echo $this->get_render_attribute_string( 'verticle_timeline_attr' ); ?>>

            <?php
                $i = 0;
                if( isset( $settings['custom_content_list'] ) ):
                    foreach ( $settings['custom_content_list'] as $items ):
                        $i++;
            ?>
               
                <?php if( $i%2 == 0 ): ?>
                    <div class="<?php echo esc_attr( $item_class ); ?> vertical-reverse">
                        <?php if( !empty( $items['content_date'] ) ): ?>
                            <div class="vertical-time">
                                <div class="vertical-date">
                                    <span class="month"><?php echo $items['content_date']; ?></span>
                                </div>
                            </div>
                        <?php endif; if( !empty( $items['content_text'] ) || !empty( $items['content_title'] ) ):?>
                            <div class="timeline-content">
                                <?php
                                    if( $settings['verticle_timeline_layout'] == 3 ){
                                        echo '<div class="content">';
                                    }
                                    if( !empty( $items['content_title'] ) ){
                                        echo '<h6 class="time_line_title">'.esc_attr__( $items['content_title'],'htmega-addons' ).'</h6>'; 
                                    }
                                    echo wp_kses_post( $items['content_text'] );
                                    if( $settings['verticle_timeline_layout'] == 3 ){
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        <?php endif;?>
                    </div>

                <?php else:?>
                    <div class="<?php echo esc_attr( $item_class ); ?>">
                        <?php if( !empty( $items['content_date'] ) ): ?>
                            <div class="vertical-time">
                                <div class="vertical-date">
                                    <span class="month"><?php echo $items['content_date']; ?></span>
                                </div>
                            </div>
                        <?php endif; if( !empty( $items['content_text'] ) || !empty( $items['content_title'] ) ):?>
                            <div class="timeline-content">
                                <?php
                                    if( $settings['verticle_timeline_layout'] == 3 ){
                                        echo '<div class="content">';
                                    }
                                    if( !empty( $items['content_title'] ) ){
                                        echo '<h6 class="time_line_title">'.esc_attr__( $items['content_title'],'htmega-addons' ).'</h6>'; 
                                    }
                                    echo wp_kses_post( $items['content_text'] );
                                    if( $settings['verticle_timeline_layout'] == 3 ){
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>

            <?php endforeach; endif; ?>

        </div>

        <?php

    }

}