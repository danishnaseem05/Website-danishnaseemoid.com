<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_User_Register_Form extends Widget_Base {

    public function get_name() {
        return 'htmega-userregister-form-addons';
    }
    
    public function get_title() {
        return __( 'User Register Form', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-lock-user';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'user_register_form_content',
            [
                'label' => __( 'Register Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'register_form_style',
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
                'show_firstname',
                [
                    'label' => __( 'Show First Name', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'show_lastname',
                [
                    'label' => __( 'Show Last Name', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_nickname',
                [
                    'label' => __( 'Nick Name', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_website',
                [
                    'label' => __( 'Website', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_bio',
                [
                    'label' => __( 'Biographical Info', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_label',
                [
                    'label' => __( 'Show label', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'show_custom_label',
                [
                    'label' => __( 'Custom label', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                    'condition'=>[
                        'show_label'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'username_label',
                [
                    'label' => __( 'Username Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Username', 'htmega-addons' ),
                    'placeholder' => __( 'Username', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'password_label',
                [
                    'label' => __( 'Password Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Password', 'htmega-addons' ),
                    'placeholder' => __( 'Password', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'email_label',
                [
                    'label' => __( 'Mail Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Mail', 'htmega-addons' ),
                    'placeholder' => __( 'Mail', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'firstname_label',
                [
                    'label' => __( 'First Name Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'First Name', 'htmega-addons' ),
                    'placeholder' => __( 'First Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                        'show_firstname'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'lastname_label',
                [
                    'label' => __( 'Last Name Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'First Name', 'htmega-addons' ),
                    'placeholder' => __( 'First Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                        'show_lastname'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'nickname_label',
                [
                    'label' => __( 'Nick Name Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Nick Name', 'htmega-addons' ),
                    'placeholder' => __( 'Nick Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                        'show_nickname'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'website_label',
                [
                    'label' => __( 'Website Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Website', 'htmega-addons' ),
                    'placeholder' => __( 'Website', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                        'show_website'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'bio_label',
                [
                    'label' => __( 'Biographical Info Label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Biographical', 'htmega-addons' ),
                    'placeholder' => __( 'Biographical', 'htmega-addons' ),
                    'condition'=>[
                        'show_label'=>'yes',
                        'show_custom_label'=>'yes',
                        'show_bio'=>'yes',
                    ]
                ]
            );


            $this->add_control(
                'show_custom_placeholder',
                [
                    'label' => __( 'Custom Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'username_placeholder_label',
                [
                    'label' => __( 'Username Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Username', 'htmega-addons' ),
                    'placeholder' => __( 'Username', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'password_placeholder_label',
                [
                    'label' => __( 'Password Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Password', 'htmega-addons' ),
                    'placeholder' => __( 'Password', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'email_placeholder_label',
                [
                    'label' => __( 'Mail Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Mail', 'htmega-addons' ),
                    'placeholder' => __( 'Mail', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'firstname_placeholder_label',
                [
                    'label' => __( 'First Name Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'First Name', 'htmega-addons' ),
                    'placeholder' => __( 'First Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                        'show_firstname'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'lastname_placeholder_label',
                [
                    'label' => __( 'Last Name Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Last Name', 'htmega-addons' ),
                    'placeholder' => __( 'Last Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                        'show_lastname'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'nickname_placeholder_label',
                [
                    'label' => __( 'Nick Name Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Nick Name', 'htmega-addons' ),
                    'placeholder' => __( 'Nick Name', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                        'show_nickname'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'website_placeholder_label',
                [
                    'label' => __( 'Website Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Website', 'htmega-addons' ),
                    'placeholder' => __( 'Website', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                        'show_website'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'bio_placeholder_label',
                [
                    'label' => __( 'Biographical Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Bio', 'htmega-addons' ),
                    'placeholder' => __( 'Bio', 'htmega-addons' ),
                    'condition'=>[
                        'show_custom_placeholder'=>'yes',
                        'show_bio'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'submit_button_label',
                [
                    'label' => __( 'Submit Button label', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'REGISTER', 'htmega-addons' ),
                    'placeholder' => __( 'REGISTER', 'htmega-addons' ),
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'redirect_page',
                [
                    'label' => __( 'Redirect page after register', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'label_off' => __( 'No', 'htmega-addons' ),
                    'label_on' => __( 'Yes', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'redirect_page_url',
                [
                    'type'          => Controls_Manager::URL,
                    'show_label'    => false,
                    'show_external' => false,
                    'separator'     => false,
                    'placeholder'   => 'http://your-link.com/',
                    'condition'     => [
                        'redirect_page' => 'yes',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'register_form_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'register_form_style_align',
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
                        '{{WRAPPER}} .htmega-register-wrapper' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_form_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_form_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'register_form_style_input',
            [
                'label' => __( 'Input', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'register_form_input_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'register_form_input_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="text"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="text"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="text"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                         '{{WRAPPER}} .htmega-register-wrapper input[type*="password"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="password"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="password"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                         '{{WRAPPER}} .htmega-register-wrapper input[type*="email"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="email"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper input[type*="email"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'register_form_input_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper input',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'register_input_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper input',
                ]
            );

            $this->add_responsive_control(
                'register_input_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_input_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'register_input_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'register_input_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper input',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper input' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab Textarea section
        $this->start_controls_section(
            'register_form_style_textarea',
            [
                'label' => __( 'Textarea', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'show_bio' =>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'register_form_textarea_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'register_form_textarea_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper textarea::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-register-wrapper textarea:-ms-input-placeholder'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'register_form_textarea_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper textarea',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'register_textarea_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper textarea',
                ]
            );

            $this->add_responsive_control(
                'register_textarea_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_textarea_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'register_textarea_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 100,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'register_textarea_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper textarea',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_textarea_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper textarea' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'register_form_style_submit_button',
            [
                'label' => __( 'Submit Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            // Button Tabs Start
            $this->start_controls_tabs('register_form_style_submit_tabs');

                // Start Normal Submit button tab
                $this->start_controls_tab(
                    'register_form_style_submit_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'register_form_submitbutton_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'register_form_submitbutton_typography',
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'register_form_submitbutton_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'register_form_submitbutton_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'register_form_submitbutton_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'register_form_submitbutton_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 50,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'register_form_submitbutton_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'register_form_submitbutton_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal submit Button tab end

                // Start Hover Submit button tab
                $this->start_controls_tab(
                    'register_form_style_submit_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'register_form_submitbutton_hover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]:hover'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'register_form_submitbutton_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'register_form_submitbutton_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]:hover',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'register_form_submitbutton_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-register-wrapper input[type="submit"]:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Submit Button tab End

            $this->end_controls_tabs(); // Button Tabs End

        $this->end_controls_section();

        // Label Style Start
        $this->start_controls_section(
            'register_form_style_label',
            [
                'label' => __( 'Label', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_label'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'register_form_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper label'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'register_form_label_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper label',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'register_form_label_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper label',
                ]
            );

            $this->add_responsive_control(
                'register_form_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_form_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'register_form_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-register-wrapper label',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'register_form_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-register-wrapper label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'register_form_label_align',
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
                        '{{WRAPPER}} .htmega-register-wrapper label' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $current_url = remove_query_arg( 'fake_arg' );
        $id = $this->get_id();

        if ( $settings['redirect_page'] == 'yes' && ! empty( $settings['redirect_page_url']['url'] ) ) {
            $redirect_url = $settings['redirect_page_url']['url'];
        } else {
            $redirect_url = $current_url;
        }

        $this->add_render_attribute( 'register_area_attr', 'class', 'htmega-register-wrapper' );
        $this->add_render_attribute( 'register_area_attr', 'class', 'htmega-register-style-'.$settings['register_form_style'] );
       
        ?>
            <?php
                if ( is_user_logged_in() && ! Plugin::instance()->editor->is_edit_mode() ) {
                    $current_user = wp_get_current_user();
                    echo '<div class="htmega-user-login">' .
                        sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'htmega-addons' ), $current_user->display_name, wp_logout_url( $current_url ) ) .
                        '</div>';
                    return;
                }
            ?>

            <?php

                $this->add_render_attribute(
                    'username_input_attr', [
                        'name'  => 'reg_name',
                        'id'    => 'reg_name'.$id,
                        'type'  => 'text',
                        'value' => isset( $_REQUEST['reg_name'] ) ? $_REQUEST['reg_name'] : null,
                        'placeholder' => isset( $settings['username_placeholder_label'] ) ? $settings['username_placeholder_label'] : 'Username',
                    ]
                );

                $this->add_render_attribute(
                    'password_input_attr', [
                        'name'  => 'reg_password',
                        'id'    => 'reg_password'.$id,
                        'type'  => 'password',
                        'value' => isset( $_REQUEST['reg_password'] ) ? $_REQUEST['reg_password'] : null,
                        'placeholder' => isset( $settings['password_placeholder_label'] ) ? $settings['password_placeholder_label'] : 'Password',
                    ]
                );

                $this->add_render_attribute(
                    'email_input_attr', [
                        'name'  => 'reg_email',
                        'id'    => 'reg_email'.$id,
                        'type'  => 'email',
                        'value' => isset( $_REQUEST['reg_email'] ) ? $_REQUEST['reg_email'] : null,
                        'placeholder' => isset( $settings['email_placeholder_label'] ) ? $settings['email_placeholder_label'] : 'Email',
                    ]
                );

                $this->add_render_attribute(
                    'fname_input_attr', [
                        'name'  => 'reg_fname',
                        'id'    => 'reg_fname'.$id,
                        'type'  => 'text',
                        'value' => isset( $_REQUEST['reg_fname'] ) ? $_REQUEST['reg_fname'] : null,
                        'placeholder' => isset( $settings['firstname_placeholder_label'] ) ? $settings['firstname_placeholder_label'] : 'First Name',
                    ]
                );

                $this->add_render_attribute(
                    'lname_input_attr', [
                        'name'  => 'reg_lname',
                        'id'    => 'reg_lname'.$id,
                        'type'  => 'text',
                        'value' => isset( $_REQUEST['reg_lname'] ) ? $_REQUEST['reg_lname'] : null,
                        'placeholder' => isset( $settings['lastname_placeholder_label'] ) ? $settings['lastname_placeholder_label'] : 'Last Name',
                    ]
                );

                $this->add_render_attribute(
                    'nickname_input_attr', [
                        'name'  => 'reg_nickname',
                        'id'    => 'reg_nickname'.$id,
                        'type'  => 'text',
                        'value' => isset( $_REQUEST['reg_nickname'] ) ? $_REQUEST['reg_nickname'] : null,
                        'placeholder' => isset( $settings['nickname_placeholder_label'] ) ? $settings['nickname_placeholder_label'] : 'Nick Name',
                    ]
                );

                $this->add_render_attribute(
                    'website_input_attr', [
                        'name'  => 'reg_website',
                        'id'    => 'reg_website'.$id,
                        'type'  => 'text',
                        'value' => isset( $_REQUEST['reg_website'] ) ? $_REQUEST['reg_website'] : null,
                        'placeholder' => isset( $settings['website_placeholder_label'] ) ? $settings['website_placeholder_label'] : 'Website',
                    ]
                );

                $this->add_render_attribute(
                    'bio_textarea_attr', [
                        'name'  => 'reg_bio',
                        'id'    => 'reg_bio'.$id,
                        'placeholder' => isset( $settings['bio_placeholder_label'] ) ? $settings['bio_placeholder_label'] : 'Biographical Info',
                    ]
                );

                $this->add_render_attribute(
                    'submit_input_attr', [
                        'name'  => 'reg_submit'.$id,
                        'id'    => 'reg_submit'.$id,
                        'type'  => 'submit',
                        'value' => isset( $settings['submit_button_label'] ) ? $settings['submit_button_label'] : 'REGISTER',
                    ]
                );
            ?>

            <div id="htmega_message_<?php echo esc_attr( $id ); ?>" class="htmega_message">&nbsp;</div>
            <div <?php echo $this->get_render_attribute_string( 'register_area_attr' ); ?>>
                <div class="htmega-register-form">
                    <form id="htmega_register_form_<?php echo esc_attr( $id ); ?>" method="post" action="htmegaregisteraction">

                        <?php if( $settings['register_form_style'] == 2 ): ?>
                            <div class="htb-row">
                                <div class="htb-col-lg-6">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){
                                            echo sprintf('<label>%1$s</label>',isset( $settings['username_label'] ) ? $settings['username_label'] : 'Username' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'username_input_attr' ).' />';
                                    ?>
                                </div>

                                <div class="htb-col-lg-6">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['password_label'] ) ? $settings['password_label'] : 'Password' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'password_input_attr' ).' />';
                                    ?>
                                </div>

                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['email_label'] ) ? $settings['email_label'] : 'Email' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'email_input_attr' ).' />';
                                    ?>
                                </div>

                                <!-- Editional Fiedls -->
                                <?php
                                    if( $settings['show_firstname'] == 'yes' ){
                                        echo '<div class="htb-col-lg-12">';
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['firstname_label'] ) ? $settings['firstname_label'] : 'First Name' );
                                            }
                                            echo '<input '.$this->get_render_attribute_string( 'fname_input_attr' ).' />';
                                        echo '</div>';
                                    }
                                    if( $settings['show_lastname'] == 'yes' ){
                                        echo '<div class="htb-col-lg-12">';
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['lastname_label'] ) ? $settings['lastname_label'] : 'Last Name' );
                                            }
                                            echo '<input '.$this->get_render_attribute_string( 'lname_input_attr' ).' />';
                                        echo '</div>';
                                    }

                                    if( $settings['show_nickname'] == 'yes' ){
                                        echo '<div class="htb-col-lg-12">';
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['nickname_label'] ) ? $settings['nickname_label'] : 'Nick Name' );
                                            }
                                            echo '<input '.$this->get_render_attribute_string( 'nickname_input_attr' ).' />';
                                        echo '</div>';
                                    }

                                    if( $settings['show_website'] == 'yes' ){
                                        echo '<div class="htb-col-lg-12">';
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['website_label'] ) ? $settings['website_label'] : 'Website' );
                                            }
                                            echo '<input '.$this->get_render_attribute_string( 'website_input_attr' ).' />';
                                        echo '</div>';
                                    }

                                    if( $settings['show_bio'] == 'yes' ){
                                        echo '<div class="htb-col-lg-12">';
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>', isset( $settings['bio_label'] ) ? $settings['bio_label'] : 'Biographical Info' );
                                            }
                                            echo sprintf( '<textarea %1$s>%2$s</textarea>', $this->get_render_attribute_string( 'bio_textarea_attr' ), ( isset( $_REQUEST['reg_bio'] ) ? $_REQUEST['reg_bio'] : NULL ) );
                                        echo '</div>';

                                    }
                                ?>
                                <div class="htb-col-lg-12">
                                    <input <?php echo $this->get_render_attribute_string( 'submit_input_attr' ); ?> />
                                </div>
                            </div>
                        <?php elseif( $settings['register_form_style'] == 3 ): ?>

                            <div class="htb-row">

                                <div class="htb-col-lg-12">
                                    <div class="input_box">
                                        <?php
                                            if( $settings['show_label'] == 'yes' ){
                                                echo sprintf('<label>%1$s</label>',isset( $settings['username_label'] ) ? $settings['username_label'] : 'Username' );
                                            }
                                            echo '<i class="fa fa-user"></i><input '.$this->get_render_attribute_string( 'username_input_attr' ).' />';
                                        ?>
                                    </div>
                                </div>

                                <div class="htb-col-lg-12">
                                    <div class="input_box">
                                        <?php
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['password_label'] ) ? $settings['password_label'] : 'Password' );
                                            }
                                            echo '<i class="fa fa-lock"></i><input '.$this->get_render_attribute_string( 'password_input_attr' ).' />';
                                        ?>
                                    </div>
                                </div>

                                <div class="htb-col-lg-12">
                                    <div class="input_box">
                                        <?php
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['email_label'] ) ? $settings['email_label'] : 'Email' );
                                            }
                                            echo '<i class="fa fa-envelope"></i><input '.$this->get_render_attribute_string( 'email_input_attr' ).' />';
                                        ?>
                                    </div>
                                </div>

                                <div class="htb-col-lg-12">
                                    <input <?php echo $this->get_render_attribute_string( 'submit_input_attr' ); ?> />
                                </div>

                                <div class="htb-col-lg-12">
                                    <div class="separator">
                                        <span><?php echo esc_html__( 'or you can','htmega-addons' );?></span>
                                    </div>
                                </div>

                                <div class="htb-col-lg-12">
                                    <div class="login">
                                        <a href="<?php echo esc_url( wp_login_url() ); ?>"><?php echo esc_html__( 'LOGIN','htmega-addons' );?></a>
                                    </div>
                                </div>

                            </div>

                        <?php elseif( $settings['register_form_style'] == 4 ): ?>
                            <div class="htb-row">
                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){
                                            echo sprintf('<label>%1$s</label>',isset( $settings['username_label'] ) ? $settings['username_label'] : 'Username' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'username_input_attr' ).' />';
                                    ?>
                                </div>
                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['password_label'] ) ? $settings['password_label'] : 'Password' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'password_input_attr' ).' />';
                                    ?>
                                </div>
                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['email_label'] ) ? $settings['email_label'] : 'Email' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'email_input_attr' ).' />';
                                    ?>
                                </div>
                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_nickname'] == 'yes' ){
                                            if( $settings['show_label'] == 'yes' ){ 
                                                echo sprintf('<label>%1$s</label>',isset( $settings['nickname_label'] ) ? $settings['nickname_label'] : 'Nick Name' );
                                            }
                                            echo '<input '.$this->get_render_attribute_string( 'nickname_input_attr' ).' />';
                                        }
                                    ?>
                                </div>
                                <div class="htb-col-lg-12">
                                    <input <?php echo $this->get_render_attribute_string( 'submit_input_attr' ); ?> />
                                </div>
                            </div>

                        <?php elseif( $settings['register_form_style'] == 5 ): ?>
                            <div class="htb-row">
                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){
                                            echo sprintf('<label>%1$s</label>',isset( $settings['username_label'] ) ? $settings['username_label'] : 'Username' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'username_input_attr' ).' />';
                                    ?>
                                </div>

                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['password_label'] ) ? $settings['password_label'] : 'Password' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'password_input_attr' ).' />';
                                    ?>
                                </div>

                                <div class="htb-col-lg-12">
                                    <?php
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['email_label'] ) ? $settings['email_label'] : 'Email' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'email_input_attr' ).' />';
                                    ?>
                                </div>

                                <div class="htb-col-lg-12">
                                    <input <?php echo $this->get_render_attribute_string( 'submit_input_attr' ); ?> />
                                </div>
                            </div>

                        <?php else:?>
                            <div class="htmega-fields">
                                <?php
                                    // Default Field
                                    if( $settings['show_label'] == 'yes' ){
                                        echo sprintf('<label>%1$s</label>',isset( $settings['username_label'] ) ? $settings['username_label'] : 'Username' );
                                    }
                                    echo '<input '.$this->get_render_attribute_string( 'username_input_attr' ).' />';

                                    if( $settings['show_label'] == 'yes' ){ 
                                        echo sprintf('<label>%1$s</label>',isset( $settings['password_label'] ) ? $settings['password_label'] : 'Password' );
                                    }
                                    echo '<input '.$this->get_render_attribute_string( 'password_input_attr' ).' />';

                                    if( $settings['show_label'] == 'yes' ){ 
                                        echo sprintf('<label>%1$s</label>',isset( $settings['email_label'] ) ? $settings['email_label'] : 'Email' );
                                    }
                                    echo '<input '.$this->get_render_attribute_string( 'email_input_attr' ).' />';

                                    // Additionnal Field
                                    if( $settings['show_firstname'] == 'yes' ){
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['firstname_label'] ) ? $settings['firstname_label'] : 'First Name' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'fname_input_attr' ).' />';
                                    }
                                    if( $settings['show_lastname'] == 'yes' ){
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['lastname_label'] ) ? $settings['lastname_label'] : 'Last Name' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'lname_input_attr' ).' />';
                                    }
                                    if( $settings['show_nickname'] == 'yes' ){
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['nickname_label'] ) ? $settings['nickname_label'] : 'Nick Name' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'nickname_input_attr' ).' />';
                                    }
                                    if( $settings['show_website'] == 'yes' ){
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>',isset( $settings['website_label'] ) ? $settings['website_label'] : 'Website' );
                                        }
                                        echo '<input '.$this->get_render_attribute_string( 'website_input_attr' ).' />';
                                    }
                                    if( $settings['show_bio'] == 'yes' ){
                                        if( $settings['show_label'] == 'yes' ){ 
                                            echo sprintf('<label>%1$s</label>', isset( $settings['bio_label'] ) ? $settings['bio_label'] : 'Biographical Info' );
                                        }
                                        
                                        echo sprintf( '<textarea %1$s>%2$s</textarea>', $this->get_render_attribute_string( 'bio_textarea_attr' ), ( isset( $_REQUEST['reg_bio'] ) ? $_REQUEST['reg_bio'] : NULL ) );
                                    }

                                ?>
                                <input <?php echo $this->get_render_attribute_string( 'submit_input_attr' ); ?> />
                            </div>
                        <?php endif;?>
                    </form>
                </div>
            </div>

        <?php
        $this->htmega_register_request( $id, $settings['redirect_page'], $redirect_url );

    }

    public function htmega_register_request( $id, $reddirectstatus ,$redirect_url ){
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    "use strict";

                    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                    var loadingmessage = '<?php echo esc_html__('Please Wait...','htmega-addons'); ?>';
                    var form_id = 'form#htmega_register_form_<?php echo esc_attr( $id ); ?>';
                    var button_id = '#reg_submit<?php echo esc_attr( $id ); ?>';
                    var nonce = '<?php wp_create_nonce( 'htmega_register_nonce' ) ?>';
                    var redirect = '<?php echo $reddirectstatus; ?>';

                    $( button_id ).on('click', function(){

                        $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<span class="htmega_lodding_msg">'+ loadingmessage +'</span>').fadeIn();

                        var data = {
                            action:         "htmega_ajax_register",
                            nonce:          nonce,
                            reg_name:       $( form_id + ' #reg_name<?php echo esc_attr( $id ); ?>').val(),
                            reg_password:   $( form_id + ' #reg_password<?php echo esc_attr( $id ); ?>').val(),
                            reg_email:      $( form_id + ' #reg_email<?php echo esc_attr( $id ); ?>').val(),
                            reg_website:    $( form_id + ' #reg_website<?php echo esc_attr( $id ); ?>').val(),
                            reg_fname:      $( form_id + ' #reg_fname<?php echo esc_attr( $id ); ?>').val(),
                            reg_lname:      $( form_id + ' #reg_lname<?php echo esc_attr( $id ); ?>').val(),
                            reg_nickname:   $( form_id + ' #reg_nickname<?php echo esc_attr( $id ); ?>').val(),
                            reg_bio:        $( form_id + ' #reg_bio<?php echo esc_attr( $id ); ?>').val(),
                        };

                        $.ajax({  
                            type: 'POST',
                            dataType: 'json',  
                            url:  ajaxurl,
                            data: data,
                            success: function( msg ){
                                if ( msg.registerauth == true ){
                                    $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<div class="htmega_success_msg alert alert-success">'+ msg.message +'</div>').fadeIn();
                                    if( redirect === 'yes' ){
                                        document.location.href = '<?php echo esc_url( $redirect_url ); ?>';
                                    }
                                }else{
                                    $('#htmega_message_<?php echo esc_attr( $id ); ?>').html('<div class="htmega_invalid_msg alert alert-danger">'+ msg.message +'</div>').fadeIn();
                                }
                            }  
                        });
                        return false;
                      
                    });

                });

            </script>
        <?php
    }


}