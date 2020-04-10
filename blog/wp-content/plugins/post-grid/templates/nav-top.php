<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



if($grid_type=='grid'){
	if($nav_top_search=='yes'){
        if(isset($_GET['keyword'])){
            $keyword = sanitize_text_field($_GET['keyword']);
        }

        ?>
        <div class="nav-search">
            <span class="search-icon"><?php echo $nav_top_search_icon; ?></span>
            <input grid_id="<?php echo $grid_id; ?>" title="<?php echo __('Press enter to reset', 'post-grid'); ?>" class="search" type="text"  placeholder="<?php echo $nav_top_search_placeholder; ?>" value="<?php echo $keyword; ?>">
        </div>
        <?php
	}
}
elseif($grid_type=='filterable'){
    ?>
    <div class="nav-filter">
        <?php
        if($nav_top_filter_style=='inline'){
            foreach($taxonomies as $taxonomy => $taxonomyData){
                $terms = !empty($taxonomyData['terms']) ? $taxonomyData['terms'] : array();
                $terms_relation = !empty($taxonomyData['terms_relation']) ? $taxonomyData['terms_relation'] : 'OR';
                $checked = !empty($taxonomyData['checked']) ? $taxonomyData['checked'] : '';
                if(!empty($terms) && !empty($checked)){
                    $terms_ids[$taxonomy] = $terms;
                }
            }

            if(!empty($terms_ids)){
                ?>
                <div class="filter filter-<?php echo $grid_id; ?>" data-filter="all"><?php echo $filterable_filter_all_text; ?></div>
                <?php
                foreach($terms_ids as $tax_name => $ids){
                    foreach($ids as  $id){
                        $term = get_term( $id, $tax_name );
                        $term_slug = $term->slug;
                        $term_name = $term->name;
                        ?>
                        <div class="filter filter-<?php echo $grid_id; ?>" terms-id="<?php echo $id; ?>" data-filter=".<?php echo $term_slug; ?>" ><?php echo $term_name; ?></div>
                    <?php
                    }
                }
            }
        }
        elseif($nav_top_filter_style=='dropdown'){
            ?>
            <select id="FilterSelect">
                <?php
                if(!empty($taxonomies)){
                    foreach($taxonomies as $taxonomy => $taxonomyData){
                        $terms = !empty($taxonomyData['terms']) ? $taxonomyData['terms'] : array();
                        $terms_relation = !empty($taxonomyData['terms_relation']) ? $taxonomyData['terms_relation'] : 'OR';
                        $checked = !empty($taxonomyData['checked']) ? $taxonomyData['checked'] : '';
                        if(!empty($terms) && !empty($checked)){
                            $terms_ids[$taxonomy] = $terms;
                        }
                    }
                    ?>
                    <option value="all"><?php echo $filterable_filter_all_text; ?></option>
                    <?php
                    foreach($terms_ids as $tax_name => $ids){
                        foreach($ids as  $id){
                            $term = get_term( $id, $tax_name );
                            $term_slug = $term->slug;
                            $term_name = $term->name;
                            ?>
                            <option terms-id="<?php echo $term_info[0]; ?>" value=".<?php echo $term_slug; ?>" ><?php echo $term_name; ?></option>
                            <?php
                        }
                    }
                }
                ?>
            </select>


            <script>
                jQuery(function($){
                    var $filterSelect = $('#FilterSelect'),
                    $container = $('<?php echo '#post-grid-'.$grid_id; ?>');
                    $container.mixItUp({
                        pagination: {
                            limit: <?php echo $filterable_post_per_page; ?>,
                            prevButtonHTML: "<?php echo $pagination_prev_text; ?>",
                            nextButtonHTML: "<?php echo $pagination_next_text; ?>",
                        },
                        selectors: {
                            pagersWrapper: ".pager-list-<?php echo $grid_id; ?>",
                            filter: ".filter-<?php echo $grid_id; ?>",
                        },
                        <?php
                        if(!empty($active_filter) && $active_filter!= 'all'){
                        ?>
                        load: {
                            filter: document.location.hash == "" ? ".<?php echo $active_filter; ?>" :("." + document.location.hash.substring(1))
                        },
                        <?php
                        }
                        ?>
                        controls: {
                            enable: true
                        }
                    });
                    $filterSelect.on('change', function(){
                        $container.mixItUp('filter', this.value);
                    });
                });
            </script>

            <?php
        }
    ?>
    </div> <!-- .nav-filter -->
    <script>
        jQuery(document).ready(function($){
            $(function(){
                $("#post-grid-<?php echo $grid_id; ?>").mixItUp({
                    pagination: {
                        limit: <?php echo $filterable_post_per_page; ?>,
                        prevButtonHTML: "<?php echo $pagination_prev_text; ?>",
                        nextButtonHTML: "<?php echo $pagination_next_text; ?>",
                    },
                    selectors: {
                        pagersWrapper: ".pager-list-<?php echo $grid_id; ?>",
                        filter: ".filter-<?php echo $grid_id; ?>",
                    },
                    <?php if(!empty($active_filter) && $active_filter!= 'all') { ?>
                    load: {
                        filter: document.location.hash == "" ? ".<?php echo $active_filter; ?>" :
                            ("." + document.location.hash.substring(1))
                    },
                    <?php
                    }
                    ?>
                controls: {
                enable: true
                },

                callbacks: {
                    onMixEnd: function(state){

                        //var $container = $('#post-grid-<?php //echo $grid_id; ?>// .grid-items');
                        //
                        //
                        //
                        //$container.masonry({
                        //    itemSelector: '.item',
                        //    columnWidth: '.item', //as you wish , you can use numeric
                        //    isAnimated: true,
                        //    horizontalOrder: true,
                        //    isFitWidth: true,
                        //});
                        //$container.masonry('reloadItems');
                        //$container.masonry('layout');



                    }
                }
                });
            });
        });
    </script>
    <style type="text/css">
        #post-grid-<?php echo $grid_id; ?> .grid-items .mix{
            display: none;
        }
    </style>
    <?php
}





elseif($grid_type=='glossary'){
    ?>
    <div class="nav-filter">
        <?php
        if($nav_top_filter_style=='inline'){

            global $wp_query;
            $get_glossary_index = post_grid_get_glossary_index($wp_query);
            $count_glossary_index = array_count_values($get_glossary_index);

            //var_dump($count_glossary_index);
            ksort($count_glossary_index);

            //var_dump($count_glossary_index);


            if(!empty($count_glossary_index)){
                ?>
                <div class="filter filter-<?php echo $grid_id; ?>" data-filter="all"><?php echo $filterable_filter_all_text; ?></div>
                <?php
                foreach($count_glossary_index as $index => $count){

                    if(!empty($index)):
                        ?>
                        <div class="filter filter-<?php echo $grid_id; ?>" terms-id="<?php echo $index; ?>" data-filter=".<?php echo $index; ?>" ><?php echo $index; ?></div>
                    <?php

                    endif;



                }

            }

        }
               ?>
    </div> <!-- .nav-filter -->
    <script>
        jQuery(document).ready(function($){
            $(function(){
                $("#post-grid-<?php echo $grid_id; ?>").mixItUp({
                    pagination: {
                        limit: <?php echo $filterable_post_per_page; ?>,
                        prevButtonHTML: "<?php echo $pagination_prev_text; ?>",
                        nextButtonHTML: "<?php echo $pagination_next_text; ?>",
                    },
                    selectors: {
                        pagersWrapper: ".pager-list-<?php echo $grid_id; ?>",
                        filter: ".filter-<?php echo $grid_id; ?>",
                    },
                    <?php if(!empty($active_filter) && $active_filter!= 'all') { ?>
                    load: {
                        filter: document.location.hash == "" ? ".<?php echo $active_filter; ?>" :
                            ("." + document.location.hash.substring(1))
                    },
                    <?php
                    }
                    ?>
                    controls: {
                        enable: true
                    }
                });
            });
        });
    </script>
    <style type="text/css">
        #post-grid-<?php echo $grid_id; ?> .grid-items .mix{
            display: none;
        }
    </style>
    <?php
}









elseif($grid_type=='slider'){
        ?>
        <script>
            jQuery(document).ready(function($){
                $("#post-grid-<?php echo $grid_id; ?> .grid-items").owlCarousel({
                    items : 3,
                    responsiveClass:true,
                    responsive:{
                        320:{
                            items:<?php echo $slider_column_mobile; ?>,
                        },
                        768:{
                            items:<?php echo $slider_column_tablet; ?>,
                        },
                        1024:{
                            items:<?php echo $slider_column_desktop; ?>,
                        }
                    },
                    loop: <?php echo $slider_loop; ?>,
                    rewind: <?php echo $slider_rewind; ?>,
                    center: <?php echo $slider_center; ?>,
                    autoplay: <?php echo $slider_auto_play; ?>,
                    autoplaySpeed: <?php echo $slider_auto_play_speed; ?>,
                    autoplayTimeout: <?php echo $slider_auto_play_timeout; ?>,
                    autoplayHoverPause: <?php echo $slider_autoplayHoverPause; ?>,
                    nav: <?php echo $slider_navs; ?>,
                    navText : ["",""],
                    dots: <?php echo $slider_dots; ?>,
                    navSpeed: <?php echo $slider_navSpeed; ?>,
                    dotsSpeed: <?php echo $slider_dotsSpeed; ?>,
                    touchDrag : <?php echo $slider_touchDrag; ?>,
                    mouseDrag  : <?php echo $slider_mouseDrag; ?>,
                    autoHeight: true,
                });
                    $("#post-grid-<?php echo $grid_id; ?> .owl-nav").addClass("<?php echo $slider_navs_positon; ?>");
                    $("#post-grid-<?php echo $grid_id; ?> .owl-nav").addClass("<?php echo $slider_navs_style; ?>");
                    $("#post-grid-<?php echo $grid_id; ?> .owl-dots").addClass("<?php echo $slider_dots_style; ?>");
            });
        </script>
        <?php
}


//var_dump($grid_type);