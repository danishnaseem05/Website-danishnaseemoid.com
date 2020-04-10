<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Data_Table extends Widget_Base {

    public function get_name() {
        return 'htmega-datatable-addons';
    }
    
    public function get_title() {
        return __( 'Data Table', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-table';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [ 'datatables' ];
    }

    public function get_style_depends() {
        return [ 'datatables' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'datatable_layout',
            [
                'label' => __( 'Table Layout', 'htmega-addons' ),
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

            $this->add_control(
                'show_datatable_sorting',
                [
                    'label' => __( 'Show Sorting Options', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        // Sorting Options
        $this->start_controls_section(
            'datatable_sorting_options',
            [
                'label' => __( 'Sorting Options', 'htmega-addons' ),
                'condition'=>[
                    'show_datatable_sorting'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'show_datatable_paging',
                [
                    'label' => __( 'Pagination', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_datatable_searching',
                [
                    'label' => __( 'Searching', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_datatable_ordering',
                [
                    'label' => __( 'Ordering', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_datatable_info',
                [
                    'label' => __( 'Footer Info', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        // Table Header
        $this->start_controls_section(
            'datatable_header',
            [
                'label' => __( 'Table Header', 'htmega-addons' ),
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
                'cell_text',
                [
                    'label'   => __( 'Cell Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Louis Hudson', 'htmega-addons' ),
                    'condition'=>[
                        'field_type'=>'col',
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
                            'cell_text' => __( 'jondoy@gmail.com', 'htmega-addons' ),
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

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();

        $this->add_render_attribute( 'datatable_attr', 'class', 'htmega-table-style htmega-table-style-'.$settings['datatable_style'] );

        if( $settings['show_datatable_sorting'] != 'yes' ){
            $this->add_render_attribute( 'datatable_attr', 'class', 'htb-table-responsive' );
        }

        $table_tr = array();
        $table_td = array();

        foreach( $settings['content_list'] as $content_row ) {

            $row_id = rand(0, 1000);
            if( $content_row['field_type'] == 'row' ) {
                $table_tr[] = [
                    'id' => $row_id,
                    'type' => $content_row['field_type'],
                ];
            }
            if( $content_row['field_type'] == 'col' ) {

                $table_tr_keys = array_keys( $table_tr );
                $last_key = end( $table_tr_keys );

                $table_td[] = [
                    'row_id' => $table_tr[$last_key]['id'],
                    'title' => $content_row['cell_text'],
                    'colspan' => $content_row['row_colspan'],
                ];
            }

        }
       
        ?>
        <div <?php echo $this->get_render_attribute_string( 'datatable_attr' ); ?>>
            <table class="htb-table <?php if( $settings['show_datatable_sorting'] == 'yes' ){ echo 'htmega-datatable-'.$id; } ?>">
                <?php if( $settings['header_column_list'] ): ?>
                    <thead>
                        <tr>
                            <?php
                                foreach ( $settings['header_column_list'] as $headeritem ) {
                                    echo '<th>'.esc_html__( $headeritem['column_name'],'htmega-addons' ).'</th>';
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
                                <td<?php echo $table_td[$j]['colspan'] > 1 ? ' colspan="'.$table_td[$j]['colspan'].'"' : ''; ?>><?php echo $table_td[$j]['title']; ?></td>
                            <?php endif; endfor; ?>
                        </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>
        <?php if( $settings['show_datatable_sorting'] == 'yes' ): ?>
            <script>
                jQuery(document).ready(function($) {
                    'use strict';
                    $('.htmega-datatable-<?php echo $id; ?>').DataTable({
                        paging: <?php echo $settings['show_datatable_paging'] == 'yes' ? 'true' : 'false'; ?>,
                        searching: <?php echo $settings['show_datatable_searching'] == 'yes' ? 'true' : 'false'; ?>,
                        ordering:  <?php echo $settings['show_datatable_ordering'] == 'yes' ? 'true' : 'false'; ?>,
                        "info": <?php echo $settings['show_datatable_info'] == 'yes' ? 'true' : 'false'; ?>,
                    });
                 });
            </script>
        <?php endif;?>
        <?php

    }

}

