<?php

namespace HTMega_Builder\Elementor\Widget;
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Meta_Info_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-meta-info';
    }

    public function get_title() {
        return __( 'BL: Post Meta Info', 'ht-builder' );
    }

    public function get_icon() {
        return 'eicon-post-info';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'post_meta_info_section',
            [
                'label' => __( 'Meta Data', 'ht-builder' ),
            ]
        );

            $this->add_control(
                'layout',
                [
                    'label' => __( 'Layout', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'  => __( 'Default', 'ht-builder' ),
                        'inline'   => __( 'inline', 'ht-builder' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'metaname',
                [
                    'label' => __( 'Meta Name', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'author' => __( 'Author', 'ht-builder' ),
                        'date' => __( 'Date', 'ht-builder' ),
                        'time' => __( 'Time', 'ht-builder' ),
                        'comments' => __( 'Comments', 'ht-builder' ),
                        'terms' => __( 'Terms', 'ht-builder' ),
                        'custom' => __( 'Custom', 'ht-builder' ),
                    ],
                ]
            );

            $repeater->add_control(
                'date_format',
                [
                    'label' => __( 'Date Format', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => false,
                    'default' => 'default',
                    'options' => [
                        'default' => 'Default',
                        '0' => _x( 'March 6, 2018 (F j, Y)', 'Date Format', 'ht-builder' ),
                        '1' => '2018-03-06 (Y-m-d)',
                        '2' => '03/06/2018 (m/d/Y)',
                        '3' => '06/03/2018 (d/m/Y)',
                        'custom' => __( 'Custom', 'ht-builder' ),
                    ],
                    'condition' => [
                        'metaname' => 'date',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_date_format',
                [
                    'label' => __( 'Custom Date Format', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'F j, Y',
                    'label_block' => false,
                    'condition' => [
                        'metaname' => 'date',
                        'date_format' => 'custom',
                    ],
                    'description' => sprintf(
                        __( 'Use the letters: %s', 'ht-builder' ),
                        'l D d j S F m M n Y y'
                    ),
                ]
            );

            $repeater->add_control(
                'time_format',
                [
                    'label' => __( 'Time Format', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => false,
                    'default' => 'default',
                    'options' => [
                        'default' => 'Default',
                        '0' => '3:31 pm (g:i a)',
                        '1' => '3:31 PM (g:i A)',
                        '2' => '15:31 (H:i)',
                        'custom' => __( 'Custom', 'ht-builder' ),
                    ],
                    'condition' => [
                        'metaname' => 'time',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_time_format',
                [
                    'label' => __( 'Custom Time Format', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'g:i a',
                    'placeholder' => 'g:i a',
                    'label_block' => false,
                    'condition' => [
                        'metaname' => 'time',
                        'time_format' => 'custom',
                    ],
                    'description' => sprintf(
                        __( 'Use the letters: %s', 'ht-builder' ),
                        'g G H i a A'
                    ),
                ]
            );

            $repeater->add_control(
                'taxonomy',
                [
                    'label' => __( 'Taxonomy', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'default' => [],
                    'options' => $this->get_taxonomies(),
                    'condition' => [
                        'metaname' => 'terms',
                    ],
                ]
            );

            $repeater->add_control(
                'show_avatar',
                [
                    'label' => __( 'Avatar', 'ht-builder' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'metaname' => 'author',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'avatar_size',
                [
                    'label' => __( 'Size', 'ht-builder' ),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'show_avatar' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'comments_custom_strings',
                [
                    'label' => __( 'Custom Format', 'ht-builder' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => false,
                    'condition' => [
                        'metaname' => 'comments',
                    ],
                ]
            );

            $repeater->add_control(
                'string_no_comments',
                [
                    'label' => __( 'No Comments', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'placeholder' => __( 'No Comments', 'ht-builder' ),
                    'condition' => [
                        'comments_custom_strings' => 'yes',
                        'metaname' => 'comments',
                    ],
                ]
            );

            $repeater->add_control(
                'string_one_comment',
                [
                    'label' => __( 'One Comment', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'placeholder' => __( 'One Comment', 'ht-builder' ),
                    'condition' => [
                        'comments_custom_strings' => 'yes',
                        'metaname' => 'comments',
                    ],
                ]
            );

            $repeater->add_control(
                'string_comments',
                [
                    'label' => __( 'Comments', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'placeholder' => __( '%s Comments', 'ht-builder' ),
                    'condition' => [
                        'comments_custom_strings' => 'yes',
                        'metaname' => 'comments',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_text',
                [
                    'label' => __( 'Custom', 'ht-builder' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'metaname' => 'custom',
                    ],
                ]
            );

            $repeater->add_control(
                'link',
                [
                    'label' => __( 'Link', 'ht-builder' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'condition' => [
                        'metaname!' => 'time',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_url',
                [
                    'label' => __( 'Custom URL', 'ht-builder' ),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'metaname' => 'custom',
                    ],
                ]
            );

            $repeater->add_control(
                'show_icon',
                [
                    'label' => __( 'Icon', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => __( 'None', 'ht-builder' ),
                        'default' => __( 'Default', 'ht-builder' ),
                        'custom' => __( 'Custom', 'ht-builder' ),
                    ],
                    'default' => 'default',
                    'condition' => [
                        'show_avatar!' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'icon',
                [
                    'label' => __( 'Choose Icon', 'ht-builder' ),
                    'type' => Controls_Manager::ICON,
                    'condition' => [
                        'show_icon' => 'custom',
                        'show_avatar!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'icon_list',
                [
                    'label' => '',
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'metaname' => 'author',
                            'icon' => 'fa fa-user-circle-o',
                        ],
                        [
                            'metaname' => 'date',
                            'icon' => 'fa fa-calendar',
                        ],
                        [
                            'metaname' => 'time',
                            'icon' => 'fa fa-clock-o',
                        ],
                        [
                            'metaname' => 'comments',
                            'icon' => 'fa fa-commenting-o',
                        ],
                    ],
                    'title_field' => '<i class="{{ icon }}"></i> <span style="text-transform: capitalize;">{{{ metaname }}}</span>',
                ]
            );

        $this->end_controls_section();

        // Post Meta Style
        $this->start_controls_section(
            'meta_info_item_style_section',
            array(
                'label' => __( 'Item', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'meta_info_item_color',
                [
                    'label'     => __( 'Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} ul.htmeta-info li' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'meta_info_item_typography',
                    'label'     => __( 'Typography', 'ht-builder' ),
                    'selector'  => '{{WRAPPER}} ul.htmeta-info li',
                )
            );

        $this->end_controls_section();

        // Post Meta item link Style
        $this->start_controls_section(
            'meta_info_item_link_style_section',
            array(
                'label' => __( 'Item Link', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('style_tabs');

                $this->start_controls_tab(
                    'meta_info_item_link_normal_tab',
                    [
                        'label' => __( 'Normal', 'ht-builder' ),
                    ]
                );
                    
                    $this->add_control(
                        'meta_info_item_link_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} ul.htmeta-info li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'meta_info_item_link_typography',
                            'label'     => __( 'Typography', 'ht-builder' ),
                            'selector'  => '{{WRAPPER}} ul.htmeta-info li a',
                        )
                    );


                $this->end_controls_tab();

                // Link Hover
                $this->start_controls_tab(
                    'meta_info_item_link_hover_tab',
                    [
                        'label' => __( 'Hover', 'ht-builder' ),
                    ]
                );
                    $this->add_control(
                        'meta_info_item_link_hover_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} ul.htmeta-info li a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Post Meta icon Style
        $this->start_controls_section(
            'meta_info_icon_style_section',
            array(
                'label' => __( 'Icon', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'meta_info_icon_color',
                [
                    'label'     => __( 'Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} ul.htmeta-info li a i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} ul.htmeta-info li i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'meta_info_icon_size',
                [
                    'label' => __( 'Font Size', 'ht-builder' ),
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
                        'size' => 16,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} ul.htmeta-info li a i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} ul.htmeta-info li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'meta_info_icon_hover_color',
                [
                    'label'     => __( 'Hover Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} ul.htmeta-info li a:hover i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} ul.htmeta-info li:hover i' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function get_taxonomies() {
        $taxonomies = get_taxonomies( [
            'show_in_nav_menus' => true,
        ], 'objects' );
        $options = [
            '0' => __( 'Choose', 'ht-builder' ),
        ];
        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }
        return $options;
    }

    protected function get_meta_info( $repeater_item ) {

        $item_data = [];

        if( $repeater_item['metaname'] == 'author' ){
            $item_data['text'] = get_the_author_meta( 'display_name' );
            $item_data['icon'] = 'fa fa-user-circle';
            $item_data['itemprop'] = 'author';
            if ( 'yes' === $repeater_item['link'] ) {
                $item_data['url'] = [
                    'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
                ];
            }
            if ( 'yes' === $repeater_item['show_avatar'] ) {
                $item_data['image'] = get_avatar_url( get_the_author_meta( 'ID' ), 96 );
            }
        }
        elseif ( $repeater_item['metaname'] == 'date' ) {
            $custom_date_format = empty( $repeater_item['custom_date_format'] ) ? 'F j, Y' : $repeater_item['custom_date_format'];

                $format_options = [
                    'default' => 'F j, Y',
                    '0' => 'F j, Y',
                    '1' => 'Y-m-d',
                    '2' => 'm/d/Y',
                    '3' => 'd/m/Y',
                    'custom' => $custom_date_format,
                ];

                $item_data['text'] = get_the_time( $format_options[ $repeater_item['date_format'] ] );
                $item_data['icon'] = 'fa fa-calendar';
                $item_data['itemprop'] = 'datePublished';

                if ( 'yes' === $repeater_item['link'] ) {
                    $item_data['url'] = [
                        'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
                    ];
                }
        }
        elseif( $repeater_item['metaname'] == 'time' ){
            $custom_time_format = empty( $repeater_item['custom_time_format'] ) ? 'g:i a' : $repeater_item['custom_time_format'];

                $format_options = [
                    'default' => 'g:i a',
                    '0' => 'g:i a',
                    '1' => 'g:i A',
                    '2' => 'H:i',
                    'custom' => $custom_time_format,
                ];
                $item_data['text'] = get_the_time( $format_options[ $repeater_item['time_format'] ] );
                $item_data['icon'] = 'fa fa-clock-o';
        }

        elseif( $repeater_item['metaname'] == 'comments' ){
            if ( comments_open() ) {
                $default_strings = [
                    'string_no_comments' => __( 'No Comments', 'ht-builder' ),
                    'string_one_comment' => __( 'One Comment', 'ht-builder' ),
                    'string_comments' => __( '%s Comments', 'ht-builder' ),
                ];

                if ( 'yes' === $repeater_item['comments_custom_strings'] ) {
                    if ( ! empty( $repeater_item['string_no_comments'] ) ) {
                        $default_strings['string_no_comments'] = $repeater_item['string_no_comments'];
                    }

                    if ( ! empty( $repeater_item['string_one_comment'] ) ) {
                        $default_strings['string_one_comment'] = $repeater_item['string_one_comment'];
                    }

                    if ( ! empty( $repeater_item['string_comments'] ) ) {
                        $default_strings['string_comments'] = $repeater_item['string_comments'];
                    }
                }

                $num_comments = (int) get_comments_number();

                if ( 0 === $num_comments ) {
                    $item_data['text'] = $default_strings['string_no_comments'];
                } else {
                    $item_data['text'] = sprintf( _n( $default_strings['string_one_comment'], $default_strings['string_comments'], $num_comments, 'ht-builder' ), $num_comments );
                }

                if ( 'yes' === $repeater_item['link'] ) {
                    $item_data['url'] = [
                        'url' => get_comments_link(),
                    ];
                }
                $item_data['icon'] = 'fa fa-commenting';
                $item_data['itemprop'] = 'commentCount';
            }
        }

        elseif( $repeater_item['metaname'] == 'terms' ){
            $item_data['icon'] = 'fa fa-tags';
            $item_data['itemprop'] = 'about';
            $taxonomy = $repeater_item['taxonomy'];
            $terms = wp_get_post_terms( get_the_ID(), $taxonomy );
            foreach ( $terms as $term ) {
                $item_data['terms_list'][ $term->term_id ]['text'] = $term->name;
                if ( 'yes' === $repeater_item['link'] ) {
                    $item_data['terms_list'][ $term->term_id ]['url'] = get_term_link( $term );
                }
            }
        }

        elseif( $repeater_item['metaname'] == 'custom' ){
            $item_data['text'] = $repeater_item['custom_text'];
            $item_data['icon'] = 'fa fa-info-circle';
            if ( 'yes' === $repeater_item['link'] && ! empty( $repeater_item['custom_url'] ) ) {
                $item_data['url'] = $repeater_item['custom_url'];
            }
        }

        $item_data['type'] = $repeater_item['metaname'];
        return $item_data;
    }

    protected function render_item_icon_image( $item_data, $repeater_item ) {

        if ( 'custom' === $repeater_item['show_icon'] && ! empty( $repeater_item['icon'] ) ) {
            $item_data['icon'] = $repeater_item['icon'];
        } elseif ( 'none' === $repeater_item['show_icon'] ) {
            $item_data['icon'] = '';
        }

        if ( empty( $item_data['icon'] ) && empty( $item_data['image'] ) ) {
            return;
        }

        ?>
        <span class="htbuilder-meta-icon">
            <?php
                if ( ! empty( $item_data['image'] ) ) {
                    echo '<img src="'.esc_url( $item_data['image'] ).'" alt="'.esc_attr( $item_data['text'] ).'">';
                }else{
                    echo '<i class="'.esc_attr( $item_data['icon'] ).'"></i>';
                }
            ?>
        </span>
        <?php
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        
        ob_start();
        if ( ! empty( $settings['icon_list'] ) ) {
            foreach ( $settings['icon_list'] as $repeater_item ) {

                $item_data = $this->get_meta_info( $repeater_item );

                if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) { $item_data['text'] = ''; }

                if ( ! empty( $item_data['url']['url'] ) ) {

                    $url = $item_data['url'];
                    $this->add_render_attribute( 'link_'.$repeater_item['_id'], 'href', $url['url'] );

                    if ( ! empty( $url['is_external'] ) ) {
                        $this->add_render_attribute( 'link_'.$repeater_item['_id'], 'target', '_blank' );
                    }

                    if ( ! empty( $url['nofollow'] ) ) {
                        $this->add_render_attribute( 'link_'.$repeater_item['_id'], 'rel', 'nofollow' );
                    }
                }

                ?>
                <li class="elementor-repeater-item-<?php echo $repeater_item['_id']; ?>">
                    <?php if ( ! empty( $item_data['url']['url'] ) ) : ?>
                        <a <?php echo $this->get_render_attribute_string( 'link_'.$repeater_item['_id'] ); ?>>
                            <?php endif; ?>
                                <?php
                                    $this->render_item_icon_image( $item_data, $repeater_item );
                                    if ( ! empty( $item_data['terms_list'] ) ) {
                                        $terms_list = [];
                                        echo '<span class="htmeta_terms-list">';
                                            foreach ( $item_data['terms_list'] as $term ) {
                                                if ( ! empty( $term['url'] ) ) {
                                                    $terms_list[] = '<a href="' . esc_attr( $term['url'] ) . '">' . esc_html( $term['text'] ) . '</a>';
                                                }
                                                else {
                                                    $terms_list[] = '<span>' . esc_html( $term['text'] ) . '</span>';
                                                }
                                            }
                                            echo implode( ', ', $terms_list );
                                        echo '</span>';
                                    }
                                    else{
                                        echo wp_kses( $item_data['text'], [
                                            'a' => [
                                                'href' => [],
                                                'title' => [],
                                                'rel' => [],
                                            ],
                                        ] );
                                    }
                                ?>
                            <?php if ( ! empty( $item_data['url']['url'] ) ) : ?>
                        </a>
                    <?php endif; ?>
                </li>
                <?php
            }
        }
        $items_html = ob_get_clean();

        if ( empty( $items_html ) ) { return; }

        echo '<ul class="htmeta-info meta-layout-'.$settings['layout'].'">'.$items_html.'</ul>';

    }

}
