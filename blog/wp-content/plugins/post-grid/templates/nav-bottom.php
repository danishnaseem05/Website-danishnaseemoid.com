<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access
	
	
$class_post_grid_functions = new class_post_grid_functions();
$load_more_text = $class_post_grid_functions->load_more_text();

?>
<div class="pagination">
<?php

//var_dump($max_num_pages);

if($max_num_pages==0){
    $max_num_pages = $post_grid_wp_query->max_num_pages;
}


if($grid_type=='grid'){
	if($pagination_type=='normal'){
	    ?>
        <div class="paginate">
        <?php

        $big = 999999999; // need an unlikely integer

        echo paginate_links(
            array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, $paged ),
                'total' => $max_num_pages,
                'prev_text'          => $pagination_prev_text,
                'next_text'          => $pagination_next_text,

            )
        );

        ?>
        </div >
        <?php
	}
	elseif($pagination_type=='ajax_pagination'){

        ?>

        <div grid-id="<?php echo $grid_id; ?>" id="paginate-ajax-<?php echo $grid_id; ?>" class="paginate-ajax">
            <?php
            $big = 999999999; // need an unlikely integer
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, $paged ),
                'total' => $max_num_pages,
                'prev_text'          => $pagination_prev_text,
                'next_text'          => $pagination_next_text,
                ) );
            ?>
        </div >

        <?php
	}
	elseif($pagination_type=='next_previous'){

	    global $wp_query;
        $post_grid_meta_options = get_post_meta($grid_id, 'post_grid_meta_options', true);
        $prev_text = !empty($post_grid_meta_options['pagination']['prev_text']) ? $post_grid_meta_options['pagination']['prev_text'] : __('« Previous','post-grid');
        $next_text = !empty($post_grid_meta_options['pagination']['next_text']) ? $post_grid_meta_options['pagination']['next_text'] : __('Next »','post-grid');

        ?>
        <div class="paginate next-previous">

            <?php
            next_posts_link( $prev_text );
            previous_posts_link( $next_text );
            ?>
        </div >
        <?php
	}
	elseif($pagination_type=='jquery'){



	    ?>
        <div class="pager-list pager-list-<?php echo $grid_id; ?>"></div >
        <script>
            jQuery(document).ready(function($) {
                $(function(){
                    $("#post-grid-<?php echo $grid_id; ?>").mixItUp({
                        pagination: {
                            limit: <?php echo $filterable_post_per_page; ?>,
                            prevButtonHTML: "<?php echo $pagination_prev_text; ?>",
                            nextButtonHTML: "<?php echo $pagination_next_text; ?>",
                        },
                        selectors: {
                            filter: ".filter",
                            pagersWrapper: ".pager-list-<?php echo $grid_id; ?>",
                        },
                        <?php
                        if(!empty($active_filter) && $active_filter!= 'all'){
                        ?>
                            load: {
                            filter: ".<?php echo $active_filter; ?>"
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
	elseif($pagination_type=='loadmore'){
        if(!empty($paged)){
                $paged = $paged+1;
        }
        $load_more = "load-more-".$grid_id;
        ?>
        <div id="load-more-<?php echo $grid_id; ?>" grid_id="<?php echo $grid_id; ?>" class="load-more" paged="<?php echo $paged; ?>" per_page="<?php echo $posts_per_page; ?>" >
            <span class="load-more-text"><?php echo $load_more_text; ?></span>
            <span class="load-more-spinner"><i class="fas fa-spinner fa-pulse"></i></span>
        </div >
        <?php
	}
	elseif($pagination_type=='infinite'){
        ?>
        <div grid_id="<?php echo $grid_id; ?>" class="infinite-scroll" paged="<?php echo $paged; ?>" per_page="<?php echo $posts_per_page; ?>" ><i class="fa fa-arrow-down"></i></div >
        <?php
	}
}
	
elseif($grid_type=='timeline'){
	
	if($pagination_type=='loadmore'){
		
			if(!empty($paged))
				{
					$paged = $paged+1;
				}
			
			?>
			<div grid_id="<?php echo $grid_id; ?>" id="load-more-<?php echo $grid_id; ?>" class="load-more" paged="<?php echo $paged; ?>" per_page="<?php echo $posts_per_page; ?>" ><?php echo $load_more_text; ?></div >
            <?php
		
		}
	
	
	
	}	
	
elseif($grid_type=='filterable' || $grid_type=='glossary'){

    //var_dump($pagination_type);

    $pagination_type = ($pagination_type != 'jquery') ? 'jquery' : $pagination_type;

    if($pagination_type=='jquery'){

        ?>
        <div class="pager-list pager-list-<?php echo $grid_id; ?>"></div >
        <script>
            jQuery(document).ready(function($) {
                $(function(){
                    $("#post-grid-<?php echo $grid_id; ?>").mixItUp({
                            pagination: {
                                limit: <?php echo $filterable_post_per_page; ?>,
                                prevButtonHTML: "<?php echo $pagination_prev_text; ?>",
                                nextButtonHTML: "<?php echo $pagination_next_text; ?>",
                            },
                            selectors: {
                                filter: ".filter",
                                pagersWrapper: ".pager-list-<?php echo $grid_id; ?>",
                            },
                            <?php
                            if(!empty($active_filter) && $active_filter!= 'all'){
                                ?>
                            load: {
                                filter: ".<?php echo $active_filter; ?>"
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
	
}
	

	
	?>
</div >
