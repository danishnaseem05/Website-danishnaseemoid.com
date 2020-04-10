<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_VideoPlayer extends Widget_Base {

    public function get_name() {
        return 'htmega-videoplayer-addons';
    }
    
    public function get_title() {
        return __( 'Video Player', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-play';
    }

    public function get_style_depends() {
        return [
            'ytplayer',
            'magnific-popup',
        ];
    }

    public function get_script_depends() {
        return [
            'ytplayer',
            'magnific-popup',
            'htmega-widgets-scripts',
        ];
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'videoplayer_content',
            [
                'label' => __( 'Video Player', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'videocontainer',
                [
                    'label' => __( 'Video Container', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'self',
                    'options' => [
                        'self'         => __( 'Self', 'htmega-addons' ),
                        'popup'         => __( 'Pop Up', 'htmega-addons' ),
                    ],
                ]
            );

            
            $this->add_control(
                'video_url',
                [
                    'label'     => __( 'Video Url', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'https://www.youtube.com/watch?v=CDilI6jcpP4', 'htmega-addons' ),
                    'placeholder' => __( 'https://www.youtube.com/watch?v=CDilI6jcpP4', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'buttontext',
                [
                    'label'     => __( 'Button Text', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Pop Up Button', 'htmega-addons' ),
                    'condition' =>[
                        'videocontainer' =>'popup',
                    ],
                ]
            );

            $this->add_control(
                'buttonicon',
                [
                    'label' => __( 'Button Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'video_image',
                [
                    'label' => __( 'Video Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' =>[
                        'videocontainer' =>'self',
                    ],
                ]
            );

        $this->end_controls_section();

        // Video Options
        $this->start_controls_section(
            'videoplayer_options',
            [
                'label' => __( 'Video Options', 'htmega-addons' ),
                'condition' =>[
                    'videocontainer' =>'self',
                ],
            ]
        );
            $this->add_control(
                'autoplay',
                [
                    'label' => __( 'Auto Play', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'soundmute',
                [
                    'label' => __( 'Sound Mute', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'repeatvideo',
                [
                    'label' => __( 'Repeat Video', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'controlerbutton',
                [
                    'label' => __( 'Show Controller Button', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'videosourselogo',
                [
                    'label' => __( 'Show video sourse Logo', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'videostarttime',
                [
                    'label' => __( 'Video Start Time', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_video_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'video_style_align',
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
                        '{{WRAPPER}} .htmega-player-container' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style Button section
        $this->start_controls_section(
            'video_button_style',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'videocontainer' =>'popup',
                ],
            ]
        );
            
            $this->start_controls_tabs('video_button_style_tabs');

                $this->start_controls_tab(
                    'video_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                $this->add_control(
                    'video_button_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'default' => '#18012c',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'video_button_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active',
                    ]
                );

                $this->add_control(
                    'video_button_fontsize',
                    [
                        'label' => __( 'Font Size', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'default' => [
                            'unit' => 'px',
                            'size' => 40,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'font-size: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active svg' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'video_button_margin',
                    [
                        'label' => __( 'Margin', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_responsive_control(
                    'video_button_padding',
                    [
                        'label' => __( 'Padding', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'video_button_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active',
                    ]
                );

                $this->add_responsive_control(
                    'video_button_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );

            $this->end_controls_tab();// Normal Tab

            // Hover Tab
            $this->start_controls_tab(
                'video_button_style_hover_tab',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );
                
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'video_button_hover_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover',
                    ]
                );

                $this->add_responsive_control(
                    'video_button_border_hover_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );

                $this->add_control(
                    'video_button_hover_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'video_button_hover_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-player-container .magnify-video-active:hover',
                    ]
                );

                

            $this->end_controls_tabs(); // Hover tab end

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_button', 'class', 'htmega-button' );

        if( $settings['videocontainer'] == 'self' ){
            $player_options_settings = [
                'videoURL'          => !empty( $settings['video_url'] ) ? $settings['video_url'] : 'https://www.youtube.com/watch?v=CDilI6jcpP4',
                'coverImage'        => !empty( $settings['video_image']['url'] ) ? $settings['video_image']['url'] : '',
                'autoPlay'          => ( $settings['autoplay'] == 'yes' ) ? true : false,
                'mute'              => ( $settings['soundmute'] == 'yes' ) ? true : false,
                'loop'              => ( $settings['repeatvideo'] == 'yes' ) ? true : false,
                'showControls'      => ( $settings['controlerbutton'] == 'yes' ) ? true : false,
                'showYTLogo'        => ( $settings['videosourselogo'] == 'yes' ) ? true : false,
                'startAt'           => $settings['videostarttime'],
                'containment'       => 'self',
                'opacity'           => 1,
                'optimizeDisplay'   => true,
                'realfullscreen'    => true,
            ];
        }
        $videocontainer = [
            'videocontainer' => isset( $settings['videocontainer'] ) ? $settings['videocontainer'] : '',
        ];
        
        
        ?>
            <div class="htmega-player-container" data-videotype=<?php echo wp_json_encode( $videocontainer ); ?>>
                <?php if($settings['videocontainer'] == 'self'): ?>
                    <div class="htmega-video-player" data-property=<?php echo wp_json_encode( $player_options_settings );?> ></div>
                <?php else:
                    if( $settings['buttonicon']['value'] != '' ){
                        echo sprintf('<a class="magnify-video-active" href="%1$s">%2$s %3$s</a>',$settings['video_url'],HTMega_Icon_manager::render_icon( $settings['buttonicon'], [ 'aria-hidden' => 'true' ] ),$settings['buttontext']);
                    }else{
                        echo sprintf('<a class="magnify-video-active" href="%1$s">%2$s</a>',$settings['video_url'],$settings['buttontext']);
                    }
                ?>
                <?php endif;?>
            </div>  

        <?php
    }

}

