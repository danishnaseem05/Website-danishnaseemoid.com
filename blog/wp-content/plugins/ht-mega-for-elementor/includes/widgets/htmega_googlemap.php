<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_GoogleMap extends Widget_Base {

    public function get_name() {
        return 'htmega-google-map-addons';
    }
    
    public function get_title() {
        return __( 'Google Map', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-google-maps';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'google-map-api',
            'mapmarker',
            'htmega-widgets-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'google_map_content',
            [
                'label' => __( 'Google Map', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'zoom_control',
                [
                    'label' => __( 'Zoom Control', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'htmega_map_default_zoom',
                [
                    'label' => __( 'Default Zoom', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 24,
                        ],
                    ],
                    'condition' => [
                        'zoom_control' => 'yes',
                    ]
                ]
            );

            $this->add_responsive_control(
                'htmega_google_map_height',
                [
                    'label' => __( 'Map Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 500,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-google-map'  => 'min-height: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'htmega_center_address',
                [
                    'label' => __( 'Center Address', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your center address.', 'htmega-addons' ),
                    'default' => __( 'Bangladesh', 'htmega-addons' ),
                ]
            );


            $this->add_control(
                'htmega_style_address',
                [
                    'label' => __( 'Map Style', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter Map Style Json Code.', 'htmega-addons' ),
                    'description'   => __( 'Go to <a href="https://snazzymaps.com/" target=_blank>Snazzy Maps</a> and Choose/Customize your Map Style. Click on your demo and copy JavaScript Style Array', 'htmega-addons' )
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'google_map_marker',
            [
                'label' => __( 'Map Marker', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'htmega_map_marker_list',
                [
                    'label' => __( 'Marker', 'htmega-addons' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                        [
                            'marker_title'   => __('This is <strong>Dhaka</strong>','htmega-addons'),
                            'marker_lat'   => __('23.8103','htmega-addons'),
                            'marker_lng'   => __('90.4125','htmega-addons'),
                            'custom_marker'   => __('90.4125','htmega-addons'),
                        ]

                    ],

                    'fields' => [
                        
                        [
                            'name'        => 'marker_lat',
                            'label'       => __( 'Latitude', 'htmega-addons' ),
                            'type'        => Controls_Manager::TEXT,
                            'default'     => '31.42866311735861',
                        ],

                        [
                            'name'        => 'marker_lng',
                            'label'       => __( 'Longitude', 'htmega-addons' ),
                            'type'        => Controls_Manager::TEXT,
                            'default'     => '-98.61328125',
                        ],

                        [
                            'name' => 'marker_title',
                            'label' => esc_html__( 'Title', 'htmega-addons' ),
                            'type'  => Controls_Manager::TEXTAREA,
                            'default' => __('Another Place','htmega-addons'),
                        ],

                        [
                            'name'        => 'custom_marker',
                            'label'       => esc_html__( 'Custom marker', 'htmega-addon' ),
                            'description' => esc_html__('Use max 32x32 px size.', 'htmega-addons'),
                            'type'        => Controls_Manager::MEDIA,
                        ],

                    ],
                    'title_field' => '{{{ marker_title }}}',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $map_options     = [];
        $marker_opts     = [];
        $all_markerslist = [];
        foreach ( $settings['htmega_map_marker_list'] as $marker_item ) {
            $marker_opts['latitude'] = ( $marker_item['marker_lat'] ) ? $marker_item['marker_lat'] : '';
            $marker_opts['longitude'] = ( $marker_item['marker_lng'] ) ? $marker_item['marker_lng'] : '';
            $marker_opts['baloon_text'] = ( $marker_item['marker_title'] ) ? $marker_item['marker_title'] : '';
            $marker_opts['icon'] = ( $marker_item['custom_marker']['url'] ) ? $marker_item['custom_marker']['url'] : '';
            $all_markerslist[] = $marker_opts;
        };
        $map_options['zoom'] = !empty( $settings['htmega_map_default_zoom']['size'] ) ? $settings['htmega_map_default_zoom']['size'] : 5;
        $map_options['center'] = !empty( $settings['htmega_center_address'] ) ? $settings['htmega_center_address'] : 'Bangladesh';

        $this->add_render_attribute( 'googlemaps_attr', 'class', 'htmega-google-map' );
        $this->add_render_attribute( 'googlemaps_attr', 'id', 'htmega-google-map' );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapmarkers', wp_json_encode( $all_markerslist ) );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapoptions', wp_json_encode( $map_options ) );
        $this->add_render_attribute( 'googlemaps_attr', 'data-mapstyle', $settings['htmega_style_address'] );

        ?>
            <div <?php echo $this->get_render_attribute_string('googlemaps_attr'); ?> >&nbsp;</div>
        <?php

    }

}