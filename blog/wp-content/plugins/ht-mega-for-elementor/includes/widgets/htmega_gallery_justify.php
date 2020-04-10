<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Gallery_Justify extends Widget_Base {

    public function get_name() {
        return 'htmega-galleryjustify-addons';
    }
    
    public function get_title() {
        return __( 'Gallery Justify', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-gallery-justified';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_script_depends() {
        return [
            'justified-gallery',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'gallery_content',
            [
                'label' => __( 'Gallery Justify', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'gallery_images',
                [
                    'label' => __( 'Add Images', 'htmega-addons' ),
                    'type' => Controls_Manager::GALLERY,
                ]
            );

            $this->add_control(
                'row_height',
                [
                    'label' => __( 'Row Height', 'plugin-domain' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                ]
            );

            $this->add_control(
                'space_margin',
                [
                    'label' => __( 'Space', 'plugin-domain' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 20,
                ]
            );


            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id         = $this->get_id();
        $this->add_render_attribute( 'justify_gallery_attr', 'id', 'npgallery'.$id );


        // Remove Elementor Lightbox
        //$this->add_render_attribute( 'popup_content_attr', 'data-elementor-open-lightbox', 'no' );
    
        if( isset( $settings['gallery_images'] ) ):
            echo '<div '.$this->get_render_attribute_string( 'justify_gallery_attr' ).'>';
                foreach ( $settings['gallery_images'] as $image ) {
                    $image_src = wp_get_attachment_image_url( $image['id'], 'full' );
                    ?>
                        <div class="htmega-justify-single-image">
                            <div class="thumb">
                                <a <?php echo $this->get_render_attribute_string( 'popup_content_attr' ); ?> href="<?php echo esc_url( $image['url'] );?>" rel="npgallery">
                                    <img src="<?php echo esc_url( $image_src );?>" alt="<?php echo( esc_attr( get_post_meta( $image['id'], '_wp_attachment_image_alt', true) ) );?>">
                                </a>
                            </div>
                        </div>

                    <?php
                }
            echo '</div>';
        endif;
        ?>
        <script>
            jQuery(document).ready(function($) {

                'use strict';
                $('#npgallery<?php echo $id; ?>').justifiedGallery({
                    rowHeight: <?php echo $settings['row_height']; ?>,
                    maxRowHeight: null,
                    margins: <?php echo $settings['space_margin']; ?>,
                    border: 0,
                    rel: 'npgallery<?php echo $id; ?>',
                    lastRow: 'nojustify',
                    captions: true,
                    randomize: false,
                    sizeRangeSuffixes: {
                        lt100: '_t',
                        lt240: '_m',
                        lt320: '_n',
                        lt500: '',
                        lt640: '_z',
                        lt1024: '_b'
                    }
                });

            });
        </script>
        <?php

    }

}

