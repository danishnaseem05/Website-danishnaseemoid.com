<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Testimonial_Grid extends Widget_Base {

    public function get_name() {
        return 'htmega-testimonialgrid-addons';
    }
    
    public function get_title() {
        return __( 'Testimonial Grid', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-testimonial';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'testimonial_content',
            [
                'label' => __( 'Testimonial Grid', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'testimonial_style',
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
                'testimonial_column',
                [
                    'label' => __( 'Column', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1'   => __( 'One', 'htmega-addons' ),
                        '2'   => __( 'Two', 'htmega-addons' ),
                        '3'   => __( 'Three', 'htmega-addons' ),
                        '4'   => __( 'Four', 'htmega-addons' ),
                        '5'   => __( 'Five', 'htmega-addons' ),
                        '6'   => __( 'Six', 'htmega-addons' ),
                    ],
                ]
            );


            $repeater = new Repeater();

            $repeater->add_control(
                'client_name',
                [
                    'label'   => __( 'Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Carolina Monntoya','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'client_designation',
                [
                    'label'   => __( 'Designation', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Managing Director','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'client_rating',
                [
                    'label' => __( 'Client Rating', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ]
            );

            $repeater->add_control(
                'client_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'client_say',
                [
                    'label'   => __( 'Client Say', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','htmega-addons'),
                ]
            );

            $this->add_control(
                'htmega_testimonial_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => array_values( $repeater->get_controls() ),
                    'default' => [

                        [
                            'client_name'           => __('Carolina Monntoya','htmega-addons'),
                            'client_designation'    => __( 'Managing Director','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Peter Rose','htmega-addons'),
                            'client_designation'    => __( 'Manager','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Gerald Gilbert','htmega-addons'),
                            'client_designation'    => __( 'Developer','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ client_name }}}',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'testimonial_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
        $this->end_controls_section();

        // Style Testimonial image style start
        $this->start_controls_section(
            'htmega_testimonial_image_style',
            [
                'label'     => __( 'Image', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_testimonial_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal img',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Testimonial image style end

        // Style Testimonial name style start
        $this->start_controls_section(
            'htmega_testimonial_name_style',
            [
                'label'     => __( 'Name', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'htmega_testimonial_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#383838',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info h4' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_name_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content h4, {{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info h4',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial name style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_designation_style',
            [
                'label'     => __( 'Designation', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
            $this->add_control(
                'htmega_testimonial_designation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#1834a6',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content span' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_designation_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content span, {{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info span',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .clint-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end


        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_clientsay_style',
            [
                'label'     => __( 'Client say', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_clientsay_align',
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
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content p' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal p' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_clientsay_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#5b5b5b',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_clientsay_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content p, {{WRAPPER}} .htmega-testimonialgrid-area .testimonal p',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_clientsay_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_clientsay_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonialgrid-area .testimonal .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_clientrating_style',
            [
                'label'     => __( 'Rating', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'htmega_testimonial_clientrating_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default' => '#ffcf0e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonialgrid-area .clint-info .rating' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'testimonial_grid_attr', 'class', 'htmega-testimonialgrid-area htmega-testimonialgrid-style-'.$settings['testimonial_style'] );


        $columns = $settings['testimonial_column'];
        $collumval = 'htb-col-md-4 htb-col-sm-6 htb-col-12';
        if( $columns != 5 ){
            $colwidth = round(12/$columns);
            $collumval = 'htb-col-md-'.$colwidth.' htb-col-sm-6 htb-col-12';
        }else{
            $collumval = 'custom-col-5';
        }

       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'testimonial_grid_attr' ); ?>>

                <div class="htb-row">
                    <?php foreach ( $settings['htmega_testimonial_list'] as $testimonial ): ?>

                        <div class="<?php echo $collumval; ?>">

                        <?php if( $settings['testimonial_style'] == 2 || $settings['testimonial_style'] == 3 ): ?>
                            <div class="testimonal">
                                <?php
                                    echo Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' );
                                ?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                        }
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                            }

                                            // Rating
                                            if( !empty( $testimonial['client_rating'] ) ){
                                                $rating = $testimonial['client_rating'];
                                                $rating_whole = floor( $testimonial['client_rating'] );
                                                $rating_fraction = $rating - $rating_whole;
                                                echo '<ul class="rating">';
                                                    for($i = 1; $i <= 5; $i++){
                                                        if( $i <= $rating_whole ){
                                                            echo '<li><i class="fa fa-star"></i></li>';
                                                        } else {
                                                            if( $rating_fraction != 0 ){
                                                                echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                                $rating_fraction = 0;
                                                            } else {
                                                                echo '<li><i class="fa fa-star-o"></i></li>';
                                                            }
                                                        }
                                                    }
                                                echo '</ul>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        <?php elseif( $settings['testimonial_style'] == 4 ): ?>
                            <div class="testimonal">
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                        }
                                    ?>
                                    <div class="triangle"></div>
                                </div>
                                <div class="clint-info">
                                    <?php
                                        echo Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' );
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                        }

                                        // Rating
                                        if( !empty( $testimonial['client_rating'] ) ){
                                            $rating = $testimonial['client_rating'];
                                            $rating_whole = floor( $testimonial['client_rating'] );
                                            $rating_fraction = $rating - $rating_whole;
                                            echo '<ul class="rating">';
                                                for($i = 1; $i <= 5; $i++){
                                                    if( $i <= $rating_whole ){
                                                        echo '<li><i class="fa fa-star"></i></li>';
                                                    } else {
                                                        if( $rating_fraction != 0 ){
                                                            echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                            $rating_fraction = 0;
                                                        } else {
                                                            echo '<li><i class="fa fa-star-o"></i></li>';
                                                        }
                                                    }
                                                }
                                            echo '</ul>';
                                        }
                                    ?>
                                </div>
                            </div>

                        <?php elseif( $settings['testimonial_style'] == 5 ): ?>
                            <div class="testimonal">
                                <div class="content">
                                    <?php
                                        echo Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' );
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                            }
                                            
                                            // Rating
                                            if( !empty( $testimonial['client_rating'] ) ){
                                                $rating = $testimonial['client_rating'];
                                                $rating_whole = floor( $testimonial['client_rating'] );
                                                $rating_fraction = $rating - $rating_whole;
                                                echo '<ul class="rating">';
                                                    for($i = 1; $i <= 5; $i++){
                                                        if( $i <= $rating_whole ){
                                                            echo '<li><i class="fa fa-star"></i></li>';
                                                        } else {
                                                            if( $rating_fraction != 0 ){
                                                                echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                                $rating_fraction = 0;
                                                            } else {
                                                                echo '<li><i class="fa fa-star-o"></i></li>';
                                                            }
                                                        }
                                                    }
                                                echo '</ul>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                    }
                                ?>
                            </div>

                        <?php else:?>
                            <div class="testimonal">
                                <div class="content">
                                    <?php
                                        echo Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' );
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.esc_html__( $testimonial['client_name'],'htmega-addons' ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html__( $testimonial['client_designation'],'htmega-addons' ).'</span>';
                                            }
                                            
                                            // Rating
                                            if( !empty( $testimonial['client_rating'] ) ){
                                                $rating = $testimonial['client_rating'];
                                                $rating_whole = floor( $testimonial['client_rating'] );
                                                $rating_fraction = $rating - $rating_whole;
                                                echo '<ul class="rating">';
                                                    for($i = 1; $i <= 5; $i++){
                                                        if( $i <= $rating_whole ){
                                                            echo '<li><i class="fa fa-star"></i></li>';
                                                        } else {
                                                            if( $rating_fraction != 0 ){
                                                                echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                                $rating_fraction = 0;
                                                            } else {
                                                                echo '<li><i class="fa fa-star-o"></i></li>';
                                                            }
                                                        }
                                                    }
                                                echo '</ul>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<p>'.esc_html__( $testimonial['client_say'],'htmega-addons' ).'</p>';
                                    }
                                ?>
                            </div>
                        <?php endif;?>

                        </div>

                    <?php endforeach; ?>
                </div>

            </div>
        <?php
        
    }

}

