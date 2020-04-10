<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_PostGrid extends Widget_Base {

    public function get_name() {
        return 'htmega-postgrid-addons';
    }
    
    public function get_title() {
        return __( 'Post Grid', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-grid';
    }
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'post_grid_content',
            [
                'label' => __( 'Post Grid', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'post_grid_style',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                        '3'   => __( 'Layout Three', 'htmega-addons' ),
                        '4'   => __( 'Layout Four', 'htmega-addons' ),
                        '5'   => __( 'Layout Five', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Option Start
        $this->start_controls_section(
            'post_content_option',
            [
                'label' => __( 'Post Option', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'grid_post_type',
                [
                    'label' => esc_html__( 'Content Sourse', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => false,
                    'options' => htmega_get_post_types(),
                ]
            );

            $this->add_control(
                'grid_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies(),
                    'condition' =>[
                        'grid_post_type' => 'post',
                    ]
                ]
            );

            $this->add_control(
                'grid_prod_categories',
                [
                    'label' => esc_html__( 'Categories', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => htmega_get_taxonomies('product_cat'),
                    'condition' =>[
                        'grid_post_type' => 'product',
                    ]
                ]
            );

            $this->add_control(
                'post_limit',
                [
                    'label' => __('Limit', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'custom_order',
                [
                    'label' => esc_html__( 'Custom order', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'postorder',
                [
                    'label' => esc_html__( 'Order', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','htmega-addons'),
                        'ASC'   => esc_html__('Ascending','htmega-addons'),
                    ],
                    'condition' => [
                        'custom_order!' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Orderby', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','htmega-addons'),
                        'ID'            => esc_html__('ID','htmega-addons'),
                        'date'          => esc_html__('Date','htmega-addons'),
                        'name'          => esc_html__('Name','htmega-addons'),
                        'title'         => esc_html__('Title','htmega-addons'),
                        'comment_count' => esc_html__('Comment count','htmega-addons'),
                        'rand'          => esc_html__('Random','htmega-addons'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => esc_html__( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'title_length',
                [
                    'label' => __( 'Title Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'default' => 5,
                    'condition' => [
                        'show_title' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_category',
                [
                    'label' => esc_html__( 'Category', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_date',
                [
                    'label' => esc_html__( 'Date', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section(); // Content Option End

        // Style Title tab section
        $this->start_controls_section(
            'post_grid_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_title'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content h2 a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .ht-post .post-content .content h4 a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-post .post-content .content h4, {{WRAPPER}} .ht-post .post-content .content h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-post .post-content .content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-post .post-content .content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
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
                        '{{WRAPPER}} .ht-post .post-content .content h2' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .ht-post .post-content .content h4' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                ]
            );

        $this->end_controls_section();

        // Style Date tab section
        $this->start_controls_section(
            'post_grid_date_style_section',
            [
                'label' => __( 'Date', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_date'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'date_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content .meta' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'date_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-post .post-content .content .meta',
                ]
            );

            $this->add_responsive_control(
                'date_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content .meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'date_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post .post-content .content .meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'date_align',
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
                        '{{WRAPPER}} .ht-post .post-content .content .meta' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                ]
            );

        $this->end_controls_section();

        // Style Category tab section
        $this->start_controls_section(
            'post_grid_category_style_section',
            [
                'label' => __( 'Category', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_category'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'category_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'default'=>'#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .ht-post a.post-category' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-post a.post-category',
                ]
            );

            $this->add_responsive_control(
                'category_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post a.post-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'category_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-post a.post-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'category_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-post a.post-category',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $postorder          = $this->get_settings_for_display('postorder');

        $this->add_render_attribute( 'htmega_post_grid', 'class', 'htmega-post-grid-area htmega-post-grid-layout-'.$settings['post_grid_style'] );

        // Query
        $args = array(
            'post_type'             => !empty( $settings['grid_post_type'] ) ? $settings['grid_post_type'] : 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['post_limit'] ) ? $settings['post_limit'] : 3,
            'order'                 => $postorder
        );

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby']    = $orderby;
        }

        if( !empty($settings['grid_prod_categories']) ){
            $get_categories = $settings['grid_prod_categories'];
        }else{
            $get_categories = $settings['grid_categories'];
        }

        $grid_cats = str_replace(' ', '', $get_categories);

        if (  !empty( $get_categories ) ) {
            if( is_array($grid_cats) && count($grid_cats) > 0 ){
                $field_name = is_numeric( $grid_cats[0] ) ? 'term_id' : 'slug';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => ( $settings['grid_post_type'] == 'product' ) ? 'product_cat' : 'category',
                        'terms' => $grid_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }

        $grid_post = new \WP_Query( $args );
       
        ?>
            
        <div <?php echo $this->get_render_attribute_string( 'htmega_post_grid' ); ?>>
            <div class="htb-col">
                <div class="<?php if( $settings['post_grid_style'] == 1 || $settings['post_grid_style'] == 2 || $settings['post_grid_style'] == 3 ) { echo 'row-1'; }else{ echo 'row--10' ;}?> htb-row">
                    <?php
                        $countrow = $gdc = $rowcount = 0;
                        $roclass = 'htb-col-lg-4 htb-col-md-4';
                        while( $grid_post->have_posts() ) : $grid_post->the_post();
                        $countrow++;
                        $gdc++;
                        if( $gdc > 6){ $gdc = 1; }
                        if( $countrow > 3){ $roclass = 'htb-col-lg-6 htb-col-md-6'; }else{ $roclass = $roclass; }
                    ?>

                        <?php if( $settings['post_grid_style'] == 2 ): ?>

                            <?php if ( $countrow == 1 ) : ?>
                                <div class="htb-col-lg-3 htb-col-sm-6 htb-col-12">
                            <?php endif;?>
                                 <?php if ( $countrow == 1 || $countrow == 2) : ?>
                                    <div class="ht-post">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                    <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                <div class="meta">
                                                    <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                        <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                    </span>
                                                </div>
                                            <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php if ( $countrow == 2 ) : ?>
                                </div>
                            <?php endif;?>

                            <?php if ( $countrow == 3 ) : ?>
                                <div class="htb-col-lg-6 htb-col-sm-6 htb-col-12">
                                    <div class="ht-post">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                            <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                        </span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if ( $countrow == 4 ) : ?>
                                <div class="htb-col-lg-3 htb-col-sm-6 htb-col-12">
                            <?php endif;?>
                                 <?php if ( $countrow == 4 || $countrow == 5 ) : ?>
                                    <div class="ht-post">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                            <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                        </span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php if ( $countrow == 5 ) : ?>
                                </div>
                            <?php endif;?>


                        <?php elseif( $settings['post_grid_style'] == 3 ): ?>
                            <?php if( $countrow == 1): ?>
                                <div class="htb-col-lg-6 htb-col-sm-6 htb-col-12">
                                    <div class="ht-post">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                    <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h2>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <i class="fa fa-clock-o"></i>
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                            <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                        </span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="htb-col-lg-6 htb-col-sm-6 htb-col-12">
                                    <div class="htb-row row-1">
                            <?php endif; ?>

                                    <?php if ( $countrow == 2) : ?>
                                        <div class="htb-col-lg-12">
                                            <div class="ht-post">
                                                <div class="thumb">
                                                    <a href="<?php the_permalink();?>">
                                                        <?php 
                                                            if ( has_post_thumbnail() ){
                                                                the_post_thumbnail('htmega_size_585x295'); 
                                                            }else{
                                                                echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                            }
                                                        ?>
                                                    </a>
                                                </div>
                                                <?php
                                                    if( $settings['show_category'] == 'yes' ){
                                                        $i=0;
                                                        foreach ( get_the_category() as $category ) {
                                                            $i++;
                                                            $term_link = get_term_link( $category );
                                                            ?>
                                                                <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                            <?php
                                                            if($i==1){break;}
                                                        }
                                                    }
                                                ?>
                                                <div class="post-content">
                                                    <div class="content">
                                                        <?php if( $settings['show_title'] == 'yes' ): ?>
                                                            <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                        <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                            <div class="meta">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                                    <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                                </span>
                                                            </div>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif;?>

                                    <?php if ( $countrow == 3 || $countrow == 4) : ?>
                                        <div class="htb-col-lg-6">
                                            <div class="ht-post">
                                                <div class="thumb">
                                                    <a href="<?php the_permalink();?>">
                                                        <?php 
                                                            if ( has_post_thumbnail() ){
                                                                the_post_thumbnail(); 
                                                            }else{
                                                                echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                            }
                                                        ?>
                                                    </a>
                                                </div>
                                                <?php
                                                    if( $settings['show_category'] == 'yes' ){
                                                        $i=0;
                                                        foreach ( get_the_category() as $category ) {
                                                            $i++;
                                                            $term_link = get_term_link( $category );
                                                            ?>
                                                                <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                            <?php
                                                            if($i==1){break;}
                                                        }
                                                    }
                                                ?>
                                                <div class="post-content">
                                                    <div class="content">
                                                        <?php if( $settings['show_title'] == 'yes' ): ?>
                                                            <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                        <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                            <div class="meta">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                                    <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                                </span>
                                                            </div>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                            <?php if ( $countrow == 5 ) : ?>
                                </div>
                            </div>
                        <?php endif;?>

                        <?php elseif( $settings['post_grid_style'] == 4 ): ?>
                            <div class="htb-col-lg-4 htb-col-sm-6 htb-col-12">
                                <div class="ht-post black-overlay mt--30">
                                    <div class="thumb">
                                        <a href="<?php the_permalink();?>">
                                            <?php 
                                                if ( has_post_thumbnail() ){
                                                    the_post_thumbnail(); 
                                                }else{
                                                    echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                }
                                            ?>
                                        </a>
                                    </div>
                                    <?php
                                        if( $settings['show_category'] == 'yes' ){
                                            $i=0;
                                            foreach ( get_the_category() as $category ) {
                                                $i++;
                                                $term_link = get_term_link( $category );
                                                ?>
                                                    <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                <?php
                                                if($i==1){break;}
                                            }
                                        }
                                    ?>
                                    <div class="post-content">
                                        <div class="content">
                                            <?php if( $settings['show_title'] == 'yes' ): ?>
                                                <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                            <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                <div class="meta">
                                                    <span class="meta-item date"><i class="fa fa-clock-o"></i>
                                                        <?php the_time(esc_html__('d F Y','htmega-addons')); ?>
                                                    </span>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php elseif( $settings['post_grid_style'] == 5 ): ?>
                            <?php if( $countrow == 1): ?>
                                <div class="htb-col-lg-8 htb-col-sm-6 htb-col-12">
                                    <div class="ht-post gradient-overlay gradient-overlay-<?php echo esc_attr($gdc);?> mt--20">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail('htmega_size_585x295'); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                    <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if( $countrow == 2): ?>
                                <div class="htb-col-lg-4 htb-col-sm-6 htb-col-12">
                                    <div class="ht-post gradient-overlay gradient-overlay-<?php echo esc_attr($gdc);?> mt--20">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                    <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if( $countrow > 2 ): ?>
                                <div class="htb-col-lg-4 htb-col-sm-6 htb-col-12">
                                    <div class="ht-post gradient-overlay gradient-overlay-<?php echo esc_attr($gdc);?> mt--20">
                                        <div class="thumb">
                                            <a href="<?php the_permalink();?>">
                                                <?php 
                                                    if ( has_post_thumbnail() ){
                                                        the_post_thumbnail(); 
                                                    }else{
                                                        echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                            if( $settings['show_category'] == 'yes' ){
                                                $i=0;
                                                foreach ( get_the_category() as $category ) {
                                                    $i++;
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                    <?php
                                                    if($i==1){break;}
                                                }
                                            }
                                        ?>
                                        <div class="post-content">
                                            <div class="content">
                                                <?php if( $settings['show_title'] == 'yes' ): ?>
                                                    <h4><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                                <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                    <div class="meta">
                                                        <span class="meta-item date"><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></span>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endif;?>

                        <?php else:?>
                            <div class="<?php echo esc_attr( $roclass ); ?> htb-col-12">
                                <div class="ht-post gradient-overlay gradient-overlay-<?php echo esc_attr($gdc);?> hero-post">
                                    
                                    <div class="thumb">
                                        <a href="<?php the_permalink();?>">
                                            <?php 
                                                if ( has_post_thumbnail() ){
                                                    the_post_thumbnail(); 
                                                }else{
                                                    echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                                }
                                            ?>
                                        </a>
                                    </div>
                                    <?php
                                        if( $settings['show_category'] == 'yes' ){
                                            $i=0;
                                            foreach ( get_the_category() as $category ) {
                                                $i++;
                                                $term_link = get_term_link( $category );
                                                ?>
                                                    <a class="post-category post-position-top-left" href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name );?></a>
                                                <?php
                                                if($i==1){break;}
                                            }
                                        }
                                    ?>
                                    <div class="post-content">
                                        <div class="content">
                                            <?php if( $settings['show_title'] == 'yes' ): ?>
                                                <h4 class="title"><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h4>
                                            <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                                <div class="meta">
                                                    <span class="meta-item date"><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y','htmega-addons'));?></span>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        <?php endif;?>

                    <?php endwhile; wp_reset_postdata(); wp_reset_query(); ?>
                </div>
            </div>
        </div>

        <?php

    }

}