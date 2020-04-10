<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

	//global $post;
	$post_grid_meta_options = get_post_meta( $grid_id, 'post_grid_meta_options', true );


$post_types = isset($post_grid_meta_options['post_types']) ? $post_grid_meta_options['post_types'] : array('post');
$taxonomies = !empty($post_grid_meta_options['taxonomies']) ? $post_grid_meta_options['taxonomies'] : array();
$categories_relation = isset($post_grid_meta_options['categories_relation']) ? $post_grid_meta_options['categories_relation'] : 'OR';
$terms_relation = isset($post_grid_meta_options['terms_relation']) ? $post_grid_meta_options['terms_relation'] : 'IN';
$extra_query_parameter = isset($post_grid_meta_options['extra_query_parameter']) ? $post_grid_meta_options['extra_query_parameter'] : '';
$post_status = isset($post_grid_meta_options['post_status']) ? $post_grid_meta_options['post_status'] : 'publish';
$offset = isset($post_grid_meta_options['offset']) ? (int)$post_grid_meta_options['offset'] : '';
$posts_per_page = isset($post_grid_meta_options['posts_per_page']) ? $post_grid_meta_options['posts_per_page'] : '10';
$exclude_post_id = isset($post_grid_meta_options['exclude_post_id']) ? $post_grid_meta_options['exclude_post_id'] : '';
$query_order = isset($post_grid_meta_options['query_order']) ? $post_grid_meta_options['query_order'] : 'DESC';
$query_orderby = isset($post_grid_meta_options['query_orderby']) ? $post_grid_meta_options['query_orderby'] : 'date';
$query_orderby = implode(' ', $query_orderby);

$query_orderby_meta_key = isset($post_grid_meta_options['query_orderby_meta_key']) ? $post_grid_meta_options['query_orderby_meta_key'] : '';
$meta_query_relation = isset($post_grid_meta_options['meta_query_relation'])? $post_grid_meta_options['meta_query_relation'] : 'OR';

if(!empty($post_grid_meta_options['meta_query'])){
    $meta_query = $post_grid_meta_options['meta_query'];
    $meta_query_args = array();
    if(!empty($meta_query)):
        $i = 0;
        foreach ($meta_query as  $meta_queryIndex=>$meta_queryData):
            $arg_type = $meta_queryData['arg_type'];
            $relation = $meta_queryData['relation'];

            if($arg_type == 'single'):
                $meta_query_args[$meta_queryIndex]['key'] = $meta_queryData['key'];
                $meta_query_args[$meta_queryIndex]['value'] = $meta_queryData['value'];
                $meta_query_args[$meta_queryIndex]['compare'] = $meta_queryData['compare'];
                $meta_query_args[$meta_queryIndex]['type'] = $meta_queryData['type'];

            elseif($arg_type == 'group'):
                $args = isset($meta_queryData['args']) ? $meta_queryData['args'] : array();

                if(!empty($args)):
                    $meta_query_args[$meta_queryIndex]['relation'] = $relation;
                    foreach ($args as $argIndex=>$arg):
                        $meta_query_args[$meta_queryIndex][$argIndex]['key'] = $arg['key'];
                        $meta_query_args[$meta_queryIndex][$argIndex]['value'] = $arg['value'];
                        $meta_query_args[$meta_queryIndex][$argIndex]['compare'] = $arg['compare'];
                        $meta_query_args[$meta_queryIndex][$argIndex]['type'] = $arg['type'];
                    endforeach;
                endif;
            endif;
        endforeach;
    endif;

    $meta_query = $meta_query_args;
    if(!empty($meta_query)){
        $meta_query_relation = array('relation' => $meta_query_relation);
        $meta_query = array_merge($meta_query_relation, $meta_query );
    }
}
else{
    $meta_query = array();
}



$sticky_post_query_type = isset($post_grid_meta_options['sticky_post_query']['type']) ? $post_grid_meta_options['sticky_post_query']['type'] : 'none';
$ignore_sticky_posts = isset($post_grid_meta_options['sticky_post_query']['ignore_sticky_posts']) ? $post_grid_meta_options['sticky_post_query']['ignore_sticky_posts'] : 0;
$date_query_type = isset($post_grid_meta_options['date_query']['type']) ? $post_grid_meta_options['date_query']['type'] : 'none';
$extact_date_year = isset($post_grid_meta_options['date_query']['extact_date']['year']) ? $post_grid_meta_options['date_query']['extact_date']['year'] : '';

$extact_date_month = isset($post_grid_meta_options['date_query']['extact_date']['month']) ? $post_grid_meta_options['date_query']['extact_date']['month'] : '';
$extact_date_day = isset($post_grid_meta_options['date_query']['extact_date']['day']) ? $post_grid_meta_options['date_query']['extact_date']['day'] : '';
$between_two_date_after_year = isset($post_grid_meta_options['date_query']['between_two_date']['after']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['year'] :'';

$between_two_date_after_month = isset($post_grid_meta_options['date_query']['between_two_date']['after']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['month'] : '';
$between_two_date_after_day = isset($post_grid_meta_options['date_query']['between_two_date']['after']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['day'] : '';
$between_two_date_before_year = isset($post_grid_meta_options['date_query']['between_two_date']['before']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['year'] : '';
$between_two_date_before_month = isset($post_grid_meta_options['date_query']['between_two_date']['before']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['month'] : '';
$between_two_date_before_day = isset($post_grid_meta_options['date_query']['between_two_date']['before']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['day'] : '';
$between_two_date_inclusive = isset($post_grid_meta_options['date_query']['between_two_date']['inclusive']) ? $post_grid_meta_options['date_query']['between_two_date']['inclusive'] : '';
$author_query_type = isset($post_grid_meta_options['author_query']['type']) ? $post_grid_meta_options['author_query']['type'] : '';

$author__in = isset($post_grid_meta_options['author_query']['author__in']) ? $post_grid_meta_options['author_query']['author__in'] : '';
$author__not_in = isset($post_grid_meta_options['author_query']['author__not_in']) ? $post_grid_meta_options['author_query']['author__not_in'] : '';

$password_query_type = isset($post_grid_meta_options['password_query']['type']) ? $post_grid_meta_options['password_query']['type'] : 'none';

$password_query_has_password = isset( $post_grid_meta_options['password_query']['has_password']) ?  $post_grid_meta_options['password_query']['has_password'] : 'null';

$password_query_post_password = isset($post_grid_meta_options['password_query']['post_password']) ? $post_grid_meta_options['password_query']['post_password'] : '';

$permission_query = isset($post_grid_meta_options['permission_query']) ? $post_grid_meta_options['permission_query'] : 'disable';
$ignore_archive = isset($post_grid_meta_options['ignore_archive']) ? $post_grid_meta_options['ignore_archive'] : 'no';
$keyword = isset($post_grid_meta_options['keyword']) ? $post_grid_meta_options['keyword'] : '';


$grid_layout_name = isset($post_grid_meta_options['grid_layout']['name']) ? $post_grid_meta_options['grid_layout']['name'] : 'layout_grid';


$grid_layout_col_multi = isset($post_grid_meta_options['grid_layout']['col_multi']) ? $post_grid_meta_options['grid_layout']['col_multi'] : '2';

$layout_content = isset($post_grid_meta_options['layout']['content']) ? $post_grid_meta_options['layout']['content'] : 'flat';

$layout_hover = isset($post_grid_meta_options['layout']['hover']) ? $post_grid_meta_options['layout']['hover'] : 'flat';


$enable_multi_skin = isset($post_grid_meta_options['enable_multi_skin']) ? $post_grid_meta_options['enable_multi_skin'] : 'no';

$skin = isset($post_grid_meta_options['skin']) ? $post_grid_meta_options['skin'] : 'flat';

$custom_js = isset($post_grid_meta_options['custom_js']) ? $post_grid_meta_options['custom_js'] : '';

$custom_css = isset($post_grid_meta_options['custom_css']) ? $post_grid_meta_options['custom_css'] : '';




$masonry_enable = isset($post_grid_meta_options['masonry_enable']) ? $post_grid_meta_options['masonry_enable'] : 'no';


$lazy_load_enable = isset($post_grid_meta_options['lazy_load_enable']) ? $post_grid_meta_options['lazy_load_enable'] : 'no';


$lazy_load_image_src = isset($post_grid_meta_options['lazy_load_image_src']) ? $post_grid_meta_options['lazy_load_image_src'] : post_grid_plugin_url.'assets/admin/gif/ajax-loader-1.gif';




$items_width_desktop = isset($post_grid_meta_options['width']['desktop']) ? $post_grid_meta_options['width']['desktop'] : '';

$items_width_tablet = isset($post_grid_meta_options['width']['tablet']) ? $post_grid_meta_options['width']['tablet'] : '';
$items_width_mobile = isset($post_grid_meta_options['width']['mobile']) ? $post_grid_meta_options['width']['mobile'] : '';


$items_bg_color_type = isset($post_grid_meta_options['items_bg_color_type']) ? $post_grid_meta_options['items_bg_color_type'] : '';
$items_bg_color = isset($post_grid_meta_options['items_bg_color']) ? $post_grid_meta_options['items_bg_color'] : '#fff';



		
		


		
		
	if(!empty($post_grid_meta_options['item_height']['style'])){
		
		$items_height_style = $post_grid_meta_options['item_height']['style'];
		}
	else{
		$items_height_style = 'auto_height';
		
		}


	if(!empty($post_grid_meta_options['item_height']['style_tablet'])){

		$items_height_style_tablet = $post_grid_meta_options['item_height']['style_tablet'];
	}
	else{
		$items_height_style_tablet = 'auto_height';

	}


	if(!empty($post_grid_meta_options['item_height']['style_mobile'])){

		$items_height_style_mobile = $post_grid_meta_options['item_height']['style_mobile'];
	}
	else{
		$items_height_style_mobile = 'auto_height';

	}








			
	if(!empty($post_grid_meta_options['item_height']['fixed_height'])){
		
		$items_fixed_height = $post_grid_meta_options['item_height']['fixed_height'];
		}
	else{
		$items_fixed_height = '220px';
		
		}
		
		
	if(!empty($post_grid_meta_options['item_height']['fixed_height_tablet'])){
		
		$items_fixed_height_tablet = $post_grid_meta_options['item_height']['fixed_height_tablet'];
		}
	else{
		$items_fixed_height_tablet = '220px';
		
		}		
		
		
	if(!empty($post_grid_meta_options['item_height']['fixed_height_mobile'])){
		
		$items_fixed_height_mobile = $post_grid_meta_options['item_height']['fixed_height_mobile'];
		}
	else{
		$items_fixed_height_mobile = '220px';
		
		}
		
		
		
		
		
		
		
		
	if(!empty($post_grid_meta_options['media_height']['style'])){
		
		$items_media_height_style = $post_grid_meta_options['media_height']['style'];
		}
	else{
		$items_media_height_style = 'auto_height';
		
		}				
			
	if(!empty($post_grid_meta_options['media_height']['fixed_height'])){
		
		$items_media_fixed_height = $post_grid_meta_options['media_height']['fixed_height'];
		}
	else{
		$items_media_fixed_height = '';
		
		}
		
		
	if(!empty($post_grid_meta_options['media_source'])){
		
		$media_source = $post_grid_meta_options['media_source'];
		}
	else{
		$media_source = array();
		
		}
		
	if(!empty($post_grid_meta_options['featured_img_size'])){
		
		$featured_img_size = $post_grid_meta_options['featured_img_size'];
		}
	else{
		$featured_img_size = 'full';
		
		}		
		
		
	if(!empty($post_grid_meta_options['thumb_linked'])){
		
		$thumb_linked = $post_grid_meta_options['thumb_linked'];
		}
	else{
		$thumb_linked = 'yes';
		
		}


$items_margin = isset($post_grid_meta_options['margin']) ? $post_grid_meta_options['margin'] : '';
$item_padding = isset($post_grid_meta_options['item_padding']) ? $post_grid_meta_options['item_padding'] : '';
$container_padding = isset($post_grid_meta_options['container']['padding']) ? $post_grid_meta_options['container']['padding'] : '';
$container_bg_color = isset($post_grid_meta_options['container']['bg_color']) ? $post_grid_meta_options['container']['bg_color'] : '';

$container_bg_image = isset($post_grid_meta_options['container']['bg_image']) ? $post_grid_meta_options['container']['bg_image'] : '';
$grid_type = isset($post_grid_meta_options['grid_type']) ? $post_grid_meta_options['grid_type'] : 'grid';

$nav_top_filter = isset($post_grid_meta_options['nav_top']['filter']) ? $post_grid_meta_options['nav_top']['filter'] : 'none';

$nav_top_filter_style = isset($post_grid_meta_options['nav_top']['filter_style']) ? $post_grid_meta_options['nav_top']['filter_style'] : 'inline';


$filterable_post_per_page = isset($post_grid_meta_options['nav_top']['filterable_post_per_page']) ? $post_grid_meta_options['nav_top']['filterable_post_per_page'] : '3';

		
		


		
		
	if(!empty($post_grid_meta_options['nav_top']['filter_all_text'])){
		
		$filterable_filter_all_text = $post_grid_meta_options['nav_top']['filter_all_text'];
		}
	else{
		$filterable_filter_all_text = __('All', 'post-grid');
		
		}	
		
		
		
	if(!empty($post_grid_meta_options['nav_top']['filterable_font_size'])){
		
		$filterable_font_size = $post_grid_meta_options['nav_top']['filterable_font_size'];
		}
	else{
		$filterable_font_size = '17px';
		
		}


$filterable_navs_margin = isset($post_grid_meta_options['nav_top']['filterable_navs_margin']) ? $post_grid_meta_options['nav_top']['filterable_navs_margin'] : '5px';


		
		
	if(!empty($post_grid_meta_options['nav_top']['filterable_font_color'])){
		
		$filterable_font_color = $post_grid_meta_options['nav_top']['filterable_font_color'];
		}
	else{
		$filterable_font_color = '#fff';
		
		}		
		
		
	if(!empty($post_grid_meta_options['nav_top']['filterable_bg_color'])){
		
		$filterable_bg_color = $post_grid_meta_options['nav_top']['filterable_bg_color'];
		}
	else{
		$filterable_bg_color = '#646464';
		
		}		
		
		
	if(!empty($post_grid_meta_options['nav_top']['filterable_active_bg_color'])){
		
		$filterable_active_bg_color = $post_grid_meta_options['nav_top']['filterable_active_bg_color'];
		}
	else{
		$filterable_active_bg_color = '#4b4b4b';
		
		}		
		
				
		
		
		
		
		
	if(!empty($post_grid_meta_options['nav_top']['active_filter'])){
		
		$active_filter = $post_grid_meta_options['nav_top']['active_filter'];

			if(isset($_GET['filter'])){

				$active_filter = sanitize_text_field($_GET['filter']);
				}
			else{
				$active_filter = $active_filter;
			}
		}
	else{
		$active_filter = 'all';
		
		}	


		//var_dump($active_filter);



$nav_top_search = isset($post_grid_meta_options['nav_top']['search']) ? $post_grid_meta_options['nav_top']['search'] : 'no';
$nav_top_search_placeholder = isset($post_grid_meta_options['nav_top']['search_placeholder']) ? $post_grid_meta_options['nav_top']['search_placeholder'] : __('Start typing', 'post-grid');
$nav_top_search_icon = isset($post_grid_meta_options['nav_top']['search_icon']) ? $post_grid_meta_options['nav_top']['search_icon'] : '<i class="fas fa-search"></i>';


		
		
	if(!empty($post_grid_meta_options['slider_navs'])){
		
		$slider_navs = $post_grid_meta_options['slider_navs'];
		}
	else{
		$slider_navs = 'true';
		
		}			
		
		
	if(!empty($post_grid_meta_options['slider_navs_positon'])){
		
		$slider_navs_positon = $post_grid_meta_options['slider_navs_positon'];
		}
	else{
		$slider_navs_positon = 'middle';
		
		}			
		
	if(!empty($post_grid_meta_options['slider_navs_style'])){
		
		$slider_navs_style = $post_grid_meta_options['slider_navs_style'];
		}
	else{
		$slider_navs_style = 'round';
		
		}		
		
		
		
	if(!empty($post_grid_meta_options['slider_dots'])){
		
		$slider_dots = $post_grid_meta_options['slider_dots'];
		}
	else{
		$slider_dots = 'true';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_dots_style'])){
		
		$slider_dots_style = $post_grid_meta_options['slider_dots_style'];
		}
	else{
		$slider_dots_style = 'round';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_dots_bg_color'])){
		
		$slider_dots_bg_color = $post_grid_meta_options['slider_dots_bg_color'];
		}
	else{
		$slider_dots_bg_color = '#e6e6e6';
		
		}		
		
		
		
	if(!empty($post_grid_meta_options['slider_auto_play'])){
		
		$slider_auto_play = $post_grid_meta_options['slider_auto_play'];
		}
	else{
		$slider_auto_play = 'true';
		
		}	
		
	if(!empty($post_grid_meta_options['slider_auto_play_timeout'])){
		
		$slider_auto_play_timeout = $post_grid_meta_options['slider_auto_play_timeout'];
		}
	else{
		$slider_auto_play_timeout = '3000';
		
		}		
		
		
		
		
		
		
		
	if(!empty($post_grid_meta_options['slider_auto_play_speed'])){
		
		$slider_auto_play_speed = $post_grid_meta_options['slider_auto_play_speed'];
		}
	else{
		$slider_auto_play_speed = 2000;
		
		}		
		
		
		
		
		
		
	if(!empty($post_grid_meta_options['slider_rewind'])){
		
		$slider_rewind = $post_grid_meta_options['slider_rewind'];
		}
	else{
		$slider_rewind = 'false';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_loop'])){
		
		$slider_loop = $post_grid_meta_options['slider_loop'];
		}
	else{
		$slider_loop = 'true';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_center'])){
		
		$slider_center = $post_grid_meta_options['slider_center'];
		}
	else{
		$slider_center = 'false';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_autoplayHoverPause'])){
		
		$slider_autoplayHoverPause = $post_grid_meta_options['slider_autoplayHoverPause'];
		}
	else{
		$slider_autoplayHoverPause = 'true';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_dotsSpeed'])){
		
		$slider_dotsSpeed = $post_grid_meta_options['slider_dotsSpeed'];
		}
	else{
		$slider_dotsSpeed = '1000';
		
		}		
		
		
	if(!empty($post_grid_meta_options['slider_navSpeed'])){
		
		$slider_navSpeed = $post_grid_meta_options['slider_navSpeed'];
		}
	else{
		$slider_navSpeed = '1000';
		
		}		
		
	if(!empty($post_grid_meta_options['slider_mouseDrag'])){
		
		$slider_mouseDrag = $post_grid_meta_options['slider_mouseDrag'];
		}
	else{
		$slider_mouseDrag = 'true';
		
		}		
		
	if(!empty($post_grid_meta_options['slider_touchDrag'])){
		
		$slider_touchDrag = $post_grid_meta_options['slider_touchDrag'];
		}
	else{
		$slider_touchDrag = 'true';
		
		}		
			

	if(!empty($post_grid_meta_options['slider_column_desktop'])){
		
		$slider_column_desktop = $post_grid_meta_options['slider_column_desktop'];
		}
	else{
		$slider_column_desktop = 4;
		
		}			
		
		
	if(!empty($post_grid_meta_options['slider_column_tablet'])){
		
		$slider_column_tablet = $post_grid_meta_options['slider_column_tablet'];
		}
	else{
		$slider_column_tablet = 2;
		
		}			
		
	if(!empty($post_grid_meta_options['slider_column_mobile'])){
		
		$slider_column_mobile = $post_grid_meta_options['slider_column_mobile'];
		}
	else{
		$slider_column_mobile = 1;
		
		}	


	
		
		
	if(!empty($post_grid_meta_options['nav_bottom']['pagination_type'])){
		
		$pagination_type = $post_grid_meta_options['nav_bottom']['pagination_type'];
		}
	else{
		$pagination_type = 'normal';
		
		}		
		
		
	if(!empty($post_grid_meta_options['pagination']['max_num_pages'])){
		
		$max_num_pages = $post_grid_meta_options['pagination']['max_num_pages'];
		}
	else{
		$max_num_pages = 0;
		
		}
		
		
	if(!empty($post_grid_meta_options['pagination']['prev_text'])){
		
		$pagination_prev_text = $post_grid_meta_options['pagination']['prev_text'];
		}
	else{
		$pagination_prev_text = __('« Previous', 'post-grid');
		
		}		
		
		
	if(!empty($post_grid_meta_options['pagination']['next_text'])){
		
		$pagination_next_text = $post_grid_meta_options['pagination']['next_text'];
		}
	else{
		$pagination_next_text = __('Next »', 'post-grid');
		
		}
		
	if(!empty($post_grid_meta_options['pagination']['font_size'])){
		
		$pagination_font_size = $post_grid_meta_options['pagination']['font_size'];
		}
	else{
		$pagination_font_size = '17px';
		
		}		
		
		
	if(!empty($post_grid_meta_options['pagination']['font_color'])){
		
		$pagination_font_color = $post_grid_meta_options['pagination']['font_color'];
		}
	else{
		$pagination_font_color = '#646464';
		
		}		
		
	if(!empty($post_grid_meta_options['pagination']['bg_color'])){
		
		$pagination_bg_color = $post_grid_meta_options['pagination']['bg_color'];
		}
	else{
		$pagination_bg_color = '#646464';
		
		}		
		
		
	if(!empty($post_grid_meta_options['pagination']['active_bg_color'])){
		
		$pagination_active_bg_color = $post_grid_meta_options['pagination']['active_bg_color'];
		}
	else{
		$pagination_active_bg_color = '#4b4b4b';
		
		}



//$glossary_load_type = isset($post_grid_meta_options['glossary']['load_type']) ? $post_grid_meta_options['glossary']['load_type'] : 'refresh';
$timeline_arrow_bg_color = !empty($post_grid_meta_options['timeline']['arrow_bg_color']) ? $post_grid_meta_options['timeline']['arrow_bg_color'] : '#eee';
$timeline_arrow_size = !empty($post_grid_meta_options['timeline']['arrow_size']) ? $post_grid_meta_options['timeline']['arrow_size'] : '13px';
$timeline_bg_color = !empty($post_grid_meta_options['timeline']['timeline_bg_color']) ? $post_grid_meta_options['timeline']['timeline_bg_color'] : '#eee';


$timeline_bubble_bg_color = !empty($post_grid_meta_options['timeline']['bubble_bg_color']) ? $post_grid_meta_options['timeline']['bubble_bg_color'] : '#ddd';
$timeline_bubble_border_color = !empty($post_grid_meta_options['timeline']['bubble_border_color']) ? $post_grid_meta_options['timeline']['bubble_border_color'] : '#fff';

		
		if(empty($exclude_post_id))
			{
				$exclude_post_id = array();
			}
		else
			{
				$exclude_post_id = array_map('intval',explode(',',$exclude_post_id));
			}
		

		
		if ( get_query_var('paged') ) {
		
			$paged = get_query_var('paged');
		
		} elseif ( get_query_var('page') ) {
		
			$paged = get_query_var('page');
		
		} else {
		
			$paged = 1;
		
		}






