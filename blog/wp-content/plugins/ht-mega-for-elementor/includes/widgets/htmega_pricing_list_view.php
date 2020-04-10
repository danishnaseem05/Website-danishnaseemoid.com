<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Pricing_List_View extends Widget_Base {

    public function get_name() {
        return 'htmega-pricinglistview-addons';
    }
    
    public function get_title() {
        return __( 'Pricing List View', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-table';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'datatable_layout',
            [
                'label' => __( 'Layout', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'datatable_style',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                        '3'   => __( 'Layout Three', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // List Pricing
        $this->start_controls_section(
            'list_content',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'condition'=>[
                    'datatable_style'=>'3'
                ]
            ]
        );

            $repeater_two = new Repeater();

            $repeater_two->add_control(
                'list_name',
                [
                    'label'   => __( 'Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'WordPress Plugin', 'htmega-addons' ),
                ]
            );

            $repeater_two->add_control(
                'list_price',
                [
                    'label'   => __( 'Price', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( '$56', 'htmega-addons' ),
                ]
            );

            $repeater_two->add_control(
                'list_cart_icon',
                [
                    'label'   => __( 'Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'default' =>[
                        'value'=>'fas fa-shopping-basket',
                        'library'=>'solid',
                    ],
                ]
            );

            $repeater_two->add_control(
                'list_cart_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '',
                        'is_external' => false,
                        'nofollow' => false,
                    ]
                ]
            );

            $this->add_control(
                'pricing_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater_two->get_controls() ),
                    'default' => [
                        [
                            'list_name' => __( 'WordPress Plugin', 'htmega-addons' ),
                            'list_price' => __( '$52', 'htmega-addons' ),
                            'list_cart_icon' => __( 'fas fa-shopping-basket', 'htmega-addons' ),
                        ],

                        [
                            'list_name' => __( 'PSD Template', 'htmega-addons' ),
                            'list_price' => __( '$48', 'htmega-addons' ),
                            'list_cart_icon' => __( 'fas fa-shopping-basket', 'htmega-addons' ),
                        ],

                        [
                            'list_name' => __( 'Joomla Template', 'htmega-addons' ),
                            'list_price' => __( '$46', 'htmega-addons' ),
                            'list_cart_icon' => __( 'fas fa-shopping-basket', 'htmega-addons' ),
                        ],

                        [
                            'list_name' => __( 'Html Template', 'htmega-addons' ),
                            'list_price' => __( '$42', 'htmega-addons' ),
                            'list_cart_icon' => __( 'fas fa-shopping-basket', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{ list_name }}}',
                ]
            );

        $this->end_controls_section();

        // Table Header
        $this->start_controls_section(
            'datatable_header',
            [
                'label' => __( 'Table Header', 'htmega-addons' ),
                'condition'=>[
                    'datatable_style!'=>'3'
                ]
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'column_name',
                [
                    'label'   => __( 'Column Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'No', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'header_column_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [
                        [
                            'column_name' => __( 'No', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Name', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Designation', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Email', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{ column_name }}}',
                ]
            );
            
        $this->end_controls_section();

        // Table Content
        $this->start_controls_section(
            'datatable_content',
            [
                'label' => __( 'Table Content', 'htmega-addons' ),
                'condition'=>[
                    'datatable_style!'=>'3'
                ]
            ]
        );

            $repeater_one = new Repeater();

            $repeater_one->add_control(
                'field_type',
                [
                    'label' => __( 'Fild Type', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'row',
                    'options' => [
                        'row'   => __( 'Row', 'htmega-addons' ),
                        'col'   => __( 'Column', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater_one->add_control(
                'content_type',
                [
                    'label' => esc_html__('Content Type','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'default'=>'text',
                    'options' =>[
                        'text' =>[
                            'title' =>__('Text','htmega-addons'),
                            'icon' =>'fa fa-text-width',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'fa fa-info',
                        ]
                    ],
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_text',
                [
                    'label'   => __( 'Cell Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Louis Hudson', 'htmega-addons' ),
                    'condition'=>[
                        'field_type'=>'col',
                        'content_type'=>'text',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_icon',
                [
                    'label' => __( 'Icons', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default'=> [
                        'value'=>'fas fa-facebook',
                        'library'=>'solid',
                    ],
                    'condition'=>[
                        'field_type'=>'col',
                        'content_type'=>'icon',
                    ]
                ]
            );

            $repeater_one->add_control(
                'row_colspan',
                [
                    'label' => __( 'Colspan', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'content_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $this->add_control(
                'content_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater_one->get_controls() ),
                    'default' => [
                        [
                            'field_type' => __( 'row', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( '1', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'Louis Hudson', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'Developer', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],


                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'louishudson@gmail.com', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{field_type}}}',
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_table_style_section',
            [
                'label' => __( 'Table', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style!'=>'3',
                ]
            ]
        );

            $this->add_control(
                'datatable_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_padding',
                [
                    'label' => esc_html__( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_margin',
                [
                    'label' => esc_html__( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style',
                    ]
            );

            $this->add_responsive_control(
                'datatable_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Table Header Style tab section
        $this->start_controls_section(
            'htmega_table_header_style_section',
            [
                'label' => __( 'Table Header', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style!'=>'3',
                ]
            ]
        );

            $this->add_control(
                'datatable_header_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'datatable_header_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_header_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-table-style thead tr th',
                ]
            );

            $this->add_responsive_control(
                'datatable_header_padding',
                [
                    'label' => esc_html__( 'Table Header Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_header_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style thead tr th',
                    ]
            );

            $this->add_responsive_control(
                'datatable_header_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_header_align',
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
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'text-align: {{VALUE}};',
                    ],
                    'default' => '',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Table Body Style tab section
        $this->start_controls_section(
            'htmega_table_body_style_section',
            [
                'label' => __( 'Table Body', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style!'=>'3',
                ]
            ]
        );

            $this->add_control(
                'datatable_body_bg_color',
                [
                    'label' => esc_html__( 'Background Color ( Event )', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'datatable_body_odd_bg_color',
                [
                    'label' => esc_html__( 'Background Color ( Odd )', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'datatable_body_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_body_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-table-style tbody tr td',
                ]
            );

            $this->add_responsive_control(
                'datatable_body_padding',
                [
                    'label' => esc_html__( 'Table Body Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_body_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style tbody tr td',
                    ]
            );

            $this->add_responsive_control(
                'datatable_body_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_body_align',
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
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'text-align: {{VALUE}};',
                    ],
                    'default' => '',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Price List Style tab section
        $this->start_controls_section(
            'price_list_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style'=>'3',
                ]
            ]
        );

            $this->add_control(
                'price_list_title_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-list-text span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_list_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-list-text span',
                ]
            );

        $this->end_controls_section();

        // Price List Style tab section
        $this->start_controls_section(
            'price_list_price_style_section',
            [
                'label' => __( 'Price', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style'=>'3',
                ]
            ]
        );

            $this->add_control(
                'price_list_price_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-text-right span.price' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'price_list_price_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-text-right span.price' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_list_price_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-text-right span.price',
                ]
            );

        $this->end_controls_section();

        // Price List Style tab section
        $this->start_controls_section(
            'price_list_icon_style_section',
            [
                'label' => __( 'Icon', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'datatable_style'=>'3',
                ]
            ]
        );

            $this->add_control(
                'price_list_price_icon_color',
                [
                    'label' => esc_html__( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-text-right span.basket' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'price_list_price_icon_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-table-style-3 ul li a .price-text-right span.basket' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();

        $this->add_render_attribute( 'datatable_attr', 'class', 'table-responsive htmega-pricing-list-view htmega-pricing-table-style-'.$settings['datatable_style'] );

        $table_tr = array();
        $table_td = array();

        if( isset( $settings['content_list'] ) ){
            foreach( $settings['content_list'] as $content_row ) {

                $row_id = rand(10, 1000);
                if( $content_row['field_type'] == 'row' ) {
                    $table_tr[] = [
                        'id' => $row_id,
                        'type' => $content_row['field_type'],
                    ];
                }
                if( $content_row['field_type'] == 'col' ) {

                    $target = $content_row['content_link']['is_external'] ? 'target="_blank"' : '';
                    $nofollow = $content_row['content_link']['nofollow'] ? 'rel="nofollow"' : '';

                    $table_tr_keys = array_keys( $table_tr );
                    $last_key = end( $table_tr_keys );

                    $table_td[] = [
                        'row_id' => $table_tr[$last_key]['id'],
                        'title' => $content_row['cell_text'],
                        'colspan' => $content_row['row_colspan'],
                        'contenttype' => $content_row['content_type'],
                        'icon' => isset( $content_row['cell_icon']['value'] ) ? HTMega_Icon_manager::render_icon( $content_row['cell_icon'], [ 'aria-hidden' => 'true' ] ) : '',
                        'link_url' => $content_row['content_link']['url'],
                        'link_target' => $target,
                        'nofollow' => $nofollow,
                    ];
                }

            }
        }
        
       
        ?>
        <div <?php echo $this->get_render_attribute_string( 'datatable_attr' ); ?>>

            <?php if( $settings['datatable_style'] == 3 ): ?>
                <ul>
                    <?php
                    if( isset( $settings['pricing_list'] ) ){ 
                        foreach ( $settings['pricing_list'] as $pricinglist ):
                            $target_one = $pricinglist['list_cart_link']['is_external'] ? 'target="_blank"' : '';
                            $nofollow_one = $pricinglist['list_cart_link']['nofollow'] ? 'rel="nofollow"' : '';
                    ?>
                        <li>
                            <a href="<?php echo esc_url( $pricinglist['list_cart_link']['url'] ); ?>" <?php echo $target_one; ?> <?php echo $nofollow_one; ?> >
                                <div class="price-list-text">
                                    <?php 
                                        if( !empty( $pricinglist['list_name'] ) ){
                                            echo '<span>'.esc_html__( $pricinglist['list_name'],'htmega-addons' ).'</span><span class="separator"></span>';
                                        }
                                    ?>
                                </div>
                                <div class="price-text-right">
                                    <?php
                                        if( !empty( $pricinglist['list_price'] ) ){
                                            echo '<span class="price">'.esc_html__( $pricinglist['list_price'], 'htmega-addons' ).'</span>';
                                        }
                                        if( !empty( $pricinglist['list_cart_icon']['value'] ) ){
                                            echo '<span class="basket">'.HTMega_Icon_manager::render_icon( $pricinglist['list_cart_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';
                                        }
                                    ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; } ?>
                </ul>
            <?php else:?>
                <table class="table">
                    <?php if( $settings['header_column_list'] ): ?>
                        <thead>
                            <tr>
                                <?php

                                    foreach ( $settings['header_column_list'] as $headeritem ) {
                                        if( $settings['datatable_style'] == 2 && !empty( $headeritem['column_name'] )){
                                            echo '<th><span>'.esc_html__( $headeritem['column_name'],'htmega-addons' ).'</span></th>';
                                        }else{
                                            echo '<th>'.esc_html__( $headeritem['column_name'],'htmega-addons' ).'</th>';
                                        }
                                    }
                                ?>
                            </tr>
                        </thead>
                    <?php endif;?>
                    <tbody>
                        <?php for( $i = 0; $i < count( $table_tr ); $i++ ) : ?>
                            <tr>
                                <?php
                                    for( $j = 0; $j < count( $table_td ); $j++ ):
                                        if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ):
                                ?>
                                    <td<?php echo $table_td[$j]['colspan'] > 1 ? ' colspan="'.$table_td[$j]['colspan'].'"' : ''; ?>>
                                        <?php
                                            if( $settings['datatable_style'] == 2 ){
                                                if( $table_td[$j]['contenttype'] == 'icon' && !empty($table_td[$j]['icon'])){
                                                    
                                                    if( !empty( $table_td[$j]['link_url'] ) ){
                                                        echo '<a href=" '.esc_url( $table_td[$j]['link_url'] ).' " '.$table_td[$j]['link_target'].$table_td[$j]['nofollow'].'><span>'.$table_td[$j]['icon'].'</span></a>';
                                                    }else{
                                                        echo '<span>'.$table_td[$j]['icon'].'</span>';
                                                    }

                                                }else{
                                                    if( !empty( $table_td[$j]['title'] ) ){
                                                        if( !empty( $table_td[$j]['link_url'] ) ){
                                                            echo '<a href=" '.esc_url( $table_td[$j]['link_url'] ).' " '.$table_td[$j]['link_target'].$table_td[$j]['nofollow'].'><span>'.$table_td[$j]['title'].'</span></a>';
                                                        }else{
                                                            echo '<span>'.$table_td[$j]['title'].'</span>';
                                                        }
                                                    }
                                                }
                                            }else{
                                                if( $table_td[$j]['contenttype'] == 'icon' ){
                                                    if( !empty( $table_td[$j]['link_url'] ) ){
                                                        echo '<a href=" '.esc_url( $table_td[$j]['link_url'] ).' " '.$table_td[$j]['link_target'].$table_td[$j]['nofollow'].'>'.$table_td[$j]['icon'].'</a>';
                                                    }else{
                                                        echo $table_td[$j]['icon'];
                                                    }
                                                }else{
                                                    if( !empty( $table_td[$j]['link_url'] ) ){
                                                        echo '<a href=" '.esc_url( $table_td[$j]['link_url'] ).' " '.$table_td[$j]['link_target'].$table_td[$j]['nofollow'].'>'.$table_td[$j]['title'].'</a>'; 
                                                    }else{
                                                        echo $table_td[$j]['title'];
                                                    }
                                                }
                                            }
                                            
                                        ?>
                                    </td>
                                <?php endif; endfor; ?>
                            </tr>
                        <?php endfor;?>
                    </tbody>
                </table>
            <?php endif;?>
        </div>

        <?php

    }

}

