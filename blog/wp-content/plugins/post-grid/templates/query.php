<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


global $wp_query;


$default_query_args = array();




/* ################################ Tax query ######################################*/

$tax_query = array();

foreach($taxonomies as $taxonomy => $taxonomyData){

    $terms = !empty($taxonomyData['terms']) ? $taxonomyData['terms'] : array();
    $terms_relation = !empty($taxonomyData['terms_relation']) ? $taxonomyData['terms_relation'] : 'OR';
    $checked = !empty($taxonomyData['checked']) ? $taxonomyData['checked'] : '';

    if(!empty($terms) && !empty($checked)){
        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $terms,
            'operator'    => $terms_relation,
        );
    }
}


$tax_query_relation = array( 'relation' => $categories_relation );

$tax_query = array_merge($tax_query_relation, $tax_query );





/* ################################ Keyword query ######################################*/
	
	if(isset($_GET['keyword'])){
		
		$keyword = sanitize_text_field($_GET['keyword']);
		
		}




/* ################################ Single pages ######################################*/


if(is_singular()):
    $current_post_id = get_the_ID();
    $default_query_args['post__not_in'] = array($current_post_id);
endif;









if(!empty($post_types))
$default_query_args['post_type'] = $post_types;

if(!empty($post_status))
$default_query_args['post_status'] = $post_status;

if(!empty($keyword))
$default_query_args['s'] = $keyword;


if(!empty($exclude_post_id))
$default_query_args['post__not_in'] = $exclude_post_id;

if(!empty($query_order))
$default_query_args['order'] = $query_order;

if(!empty($query_orderby))
$default_query_args['orderby'] = $query_orderby;

if(!empty($query_orderby_meta_key))
$default_query_args['meta_key'] = $query_orderby_meta_key;

if(!empty($posts_per_page))
$default_query_args['posts_per_page'] = (int)$posts_per_page;

if(!empty($paged))
$default_query_args['paged'] = $paged;

if(!empty($offset))
$default_query_args['offset'] = $offset + ( ($paged-1) * $posts_per_page );


if(!empty($tax_query))
$default_query_args['tax_query'] = $tax_query;

if(!empty($meta_query))
$default_query_args['meta_query'] = $meta_query;

$query_merge = apply_filters('post_grid_filter_query_args', $default_query_args, $grid_id);


$post_grid_wp_query = new WP_Query($query_merge);

// for global use
$wp_query = $post_grid_wp_query;