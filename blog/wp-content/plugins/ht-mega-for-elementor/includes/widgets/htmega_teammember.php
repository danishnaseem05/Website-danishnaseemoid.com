<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_TeamMember extends Widget_Base {

    public function get_name() {
        return 'htmega-team-member-addons';
    }
    
    public function get_title() {
        return __( 'Team Member', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-person';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        // Team Content tab Start
        $this->start_controls_section(
            'htmega_teammember_content',
            [
                'label' => __( 'Team Member', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_team_style',
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
                'htmega_team_image_hover_style',
                [
                    'label' => __( 'Image Hover Animate', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top',
                    'options' => [
                        'none'      => __( 'None', 'htmega-addons' ),
                        'left'      => __( 'Left', 'htmega-addons' ),
                        'right'     => __( 'Right', 'htmega-addons' ),
                        'top'       => __( 'Top', 'htmega-addons' ),
                        'bottom'    => __( 'Bottom', 'htmega-addons' ),
                    ],
                    'condition' =>[
                        'htmega_team_style' =>'4',
                    ],
                ]
            );

            $this->add_control(
                'htmega_member_image',
                [
                    'label' => __( 'Member image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'htmega_member_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'htmega_member_name',
                [
                    'label' => __( 'Name', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Sams Roy', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_member_designation',
                [
                    'label' => __( 'Designation', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Managing director', 'htmega-addons' ),
                    'condition' =>[
                        'htmega_team_style' => array('1','3','5','6','7'),
                    ],
                ]
            );

            $this->add_control(
                'htmega_member_bioinfo',
                [
                    'label' => __( 'Bio Info', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'I am web developer.', 'htmega-addons' ),
                    'condition' =>[
                        'htmega_team_style' => array('1','5','6'),
                    ],
                ]
            );
            
        $this->end_controls_section(); // End Team Content tab

        // Social Media tab
        $this->start_controls_section(
            'htmega_team_member_social_link',
            [
                'label' => __( 'Social Media', 'htmega-addons' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'htmega_social_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'Facebook',
                ]
            );

            $repeater->add_control(
                'htmega_social_link',
                [
                    'label'   => __( 'Link', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'https://www.facebook.com/hastech.company/', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'htmega_social_icon',
                [
                    'label'   => __( 'Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fab fa-facebook-f',
                        'library'=>'solid',
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_icon_color',
                [
                    'label'     => __( 'Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_background',
                [
                    'label'     => __( 'Icon Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_icon_hover_color',
                [
                    'label'     => __( 'Icon Hover Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_hover_background',
                [
                    'label'     => __( 'Icon Hover Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'htmeha_icon_hover_border_color',
                [
                    'label'     => __( 'Icon Hover border color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-social-network {{CURRENT_ITEM}} a:hover' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'htmega_team_member_social_link_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [

                        [
                            'htmega_social_title'      => 'Facebook',
                            'htmega_social_icon'       => 'fab fa-facebook-f',
                            'htmega_social_link'       => __( 'https://www.facebook.com/hastech.company/', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ htmega_social_title }}}',
                ]
            );

        $this->end_controls_section(); // End Social Member tab

        // Member Item Style tab section
        $this->start_controls_section(
            'htmega_team_member_style',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'team_member_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_member_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'team_member_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-team,{{WRAPPER}} .htmega-team-style-6 .htmega-team-info',
                ]
            );

            $this->add_control(
                'team_member_hover_content_bg',
                [
                    'label' => __( 'Hover Content background color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team:hover .htmega-team-hover-action' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-team-style-6:hover .htmega-team-info' => 'background-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => array( '1','5','6' ),
                    ],
                ]
            );

            $this->add_control(
                'team_member_hover_content_bg_2',
                [
                    'label' => __( 'Hover Content background color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team-style-2 .htmega-team-hover-action .htmega-hover-action' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-team-click-action' => 'background-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_team_style' => array('2','3'),
                    ],
                ]
            );

        $this->end_controls_section();

        // Team Member Name style tab start
        $this->start_controls_section(
            'htmega_team_member_name_style',
            [
                'label'     => __( 'Name', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_name!' => '',
                ],
            ]
        );

            $this->add_control(
                'team_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#343434',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_name_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-name',
                ]
            );

            $this->add_responsive_control(
                'team_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_name_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_name_align',
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
                        '{{WRAPPER}} .htmega-team .htmega-team-name' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Name style tab end

        // Team Member Designation style tab start
        $this->start_controls_section(
            'htmega_team_member_designation_style',
            [
                'label'     => __( 'Designation', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_designation!' => '',
                    'htmega_team_style' =>array('1','3','5','6','7'),
                ],
            ]
        );

            $this->add_control(
                'team_designation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#343434',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_designation_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-designation',
                ]
            );

            $this->add_responsive_control(
                'team_designation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_designation_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_designation_align',
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
                        '{{WRAPPER}} .htmega-team .htmega-team-designation' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Designation style tab end

        // Team Member Bio Info style tab start
        $this->start_controls_section(
            'htmega_team_member_bioinfo_style',
            [
                'label'     => __( 'Bio info', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_member_bioinfo!' => '',
                    'htmega_team_style' => array('1','5','6'),
                ],
            ]
        );

            $this->add_control(
                'team_bioinfo_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'team_bioinfo_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-team .htmega-team-bio-info',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_bioinfo_align',
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
                        '{{WRAPPER}} .htmega-team .htmega-team-bio-info' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Team Member Designation style tab end

        // Team Member Social Media style tab start
        $this->start_controls_section(
            'htmega_team_member_socialmedia_style',
            [
                'label'     => __( 'Social Media', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'team_socialmedia_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_align',
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
                        '{{WRAPPER}} .htmega-team ul.htmega-social-network' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'team_socialmedia_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-network li a',
                ]
            );

            $this->add_responsive_control(
                'team_socialmedia_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-network li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Team Member Designation style tab end

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-team' );
        $this->add_render_attribute( 'team_area_attr', 'class', 'htmega-team-style-'.$settings['htmega_team_style'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'team_area_attr' ); ?> >

                <?php if( $settings['htmega_team_style'] == 2 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                                    }
                                ?>
                                <ul class="htmega-social-network">
                                    <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                        <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 3 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">

                            <div class="htmega-team-click-action">
                                <div class="plus_click"></div>
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                                    }
                                    if( !empty($settings['htmega_member_designation']) ){
                                        echo '<span class="htmega-team-designation">'.esc_attr__( $settings['htmega_member_designation'],'htmega-addons' ).'</span>';
                                    }
                                ?>
                                <ul class="htmega-social-network">
                                    <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                        <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div>
                    </div>

                <?php 
                    elseif( $settings['htmega_team_style'] == 4 ):
                    $this->add_render_attribute( 'team_thumb_attr', 'class', 'htmega-thumb' );
                    $this->add_render_attribute( 'team_thumb_attr', 'class', 'htmega-team-image-hover-'.$settings['htmega_team_image_hover_style'] );
                ?>
                    <div <?php echo $this->get_render_attribute_string( 'team_thumb_attr' ); ?>>
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                                    } 
                                    if( $settings['htmega_team_member_social_link_list'] ): 
                                ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 5 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php
                                    if( !empty($settings['htmega_member_name']) ){
                                        echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                                    }
                                    if( !empty($settings['htmega_member_designation']) ){
                                        echo '<span class="htmega-team-designation">'.esc_attr__( $settings['htmega_member_designation'],'htmega-addons' ).'</span>';
                                    }
                                    if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.esc_attr__( $settings['htmega_member_bioinfo'],'htmega-addons' ).'</p>'; }
                                ?>
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 6 ):?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                    </div>
                    <div class="htmega-team-info">
                        <div class="htmega-team-content">
                            <?php
                                if( !empty($settings['htmega_member_name']) ){
                                    echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                                }
                                if( !empty($settings['htmega_member_designation']) ){
                                    echo '<span class="htmega-team-designation">'.esc_attr__( $settings['htmega_member_designation'],'htmega-addons' ).'</span>';
                                }
                                if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.esc_attr__( $settings['htmega_member_bioinfo'],'htmega-addons' ).'</p>'; }
                            ?>
                        </div>
                        <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                            <ul class="htmega-social-network">
                                <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                    <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif;?>
                    </div>

                <?php elseif( $settings['htmega_team_style'] == 7 ):?>

                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-hover-action">
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<span class="htmega-team-designation">'.esc_attr__( $settings['htmega_member_designation'],'htmega-addons' ).'</span>';
                            }
                        ?>
                    </div>

                <?php else:?>
                    <div class="htmega-thumb">
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'htmega_member_imagesize', 'htmega_member_image' );?>
                        <div class="htmega-team-hover-action">
                            <div class="htmega-team-hover">
                                <?php if( $settings['htmega_team_member_social_link_list'] ): ?>
                                    <ul class="htmega-social-network">
                                        <?php foreach ( $settings['htmega_team_member_social_link_list'] as $socialprofile ) :?>
                                            <li class="elementor-repeater-item-<?php echo $socialprofile['_id']; ?>" ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><?php echo HTMega_Icon_manager::render_icon( $socialprofile['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                                <?php if( !empty($settings['htmega_member_bioinfo']) ){ echo '<p class="htmega-team-bio-info">'.esc_attr__( $settings['htmega_member_bioinfo'],'htmega-addons' ).'</p>'; }?>
                            </div>
                        </div>
                    </div>
                    <div class="htmega-team-content">
                        <?php
                            if( !empty($settings['htmega_member_name']) ){
                                echo '<h4 class="htmega-team-name">'.esc_attr__( $settings['htmega_member_name'],'htmega-addons' ).'</h4>';
                            }
                            if( !empty($settings['htmega_member_designation']) ){
                                echo '<p class="htmega-team-designation">'.esc_attr__( $settings['htmega_member_designation'],'htmega-addons' ).'</p>';
                            }
                        ?>
                    </div>
                <?php endif;?>
            </div>
        <?php
    }
}


