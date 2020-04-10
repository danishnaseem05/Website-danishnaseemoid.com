<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


global $wp_query;


$default_query_args = array();

$ignore_sticky_posts = (int) $ignore_sticky_posts;


/* ################################ Sticky Post query ######################################*/

if($sticky_post_query_type=='include'){

    $default_query_args['post__in'] = get_option( 'sticky_posts' );

}

elseif($sticky_post_query_type=='exclude'){

    if(!empty($exclude_post_id)){
        $exclude_post_id = array_merge(get_option( 'sticky_posts' ), $exclude_post_id);
    }
    else{
        $exclude_post_id = get_option( 'sticky_posts' );
    }
}


/* ################################ Date query ######################################*/

if($date_query_type=='extact_date'){
    $default_query_args['date_query'] = array(
        'year'  => $extact_date_year,
        'month' => $extact_date_month,
        'day'   => $extact_date_day,
        );
	}

elseif($date_query_type=='between_two_date'){

    $default_query_args['date_query'] = array(

        array(
            'after'    => array(
                'year'  => $between_two_date_after_year,
                'month' => $between_two_date_after_month,
                'day'   => $between_two_date_after_day,
            ),
            'before'    => array(
                'year'  => $between_two_date_before_year,
                'month' => $between_two_date_before_month,
                'day'   => $between_two_date_before_day,
            ),
            'inclusive' => $between_two_date_inclusive,
        )
    );
}



/* ################################ Permission query ######################################*/

if($permission_query=='enable'){
    $default_query_args['perm'] = 'readable';
}



/* ################################ Password query ######################################*/

if($password_query_type=='has_password'){
	$default_query_args['has_password'] = $password_query_has_password;
}
elseif($password_query_type=='post_password'){
	$default_query_args['post_password'] = $password_query_post_password;
}




/* ################################ Author query ######################################*/

$author__in = explode(',', $author__in);
$author__not_in = explode(',', $author__not_in);

$author_query = array();

if($author_query_type=='author__in'){
	$default_query_args['author__in'] = $author__in;
}

elseif($author_query_type=='author__not_in'){
	$default_query_args['author__not_in'] = $author__not_in;
}
	
//echo '<pre>'.var_export($author_query, true).'</pre>';	
	
	//$author_query = $author_query[0];
//$author_query = array_shift($author_query);
	
/* ################################ Tax query ######################################*/
//
//	foreach($categories as $category){
//
//		$tax_cat = explode(',',$category);
//
//		$tax_terms[$tax_cat[0]][] = $tax_cat[1];
//
//		}
//
//	if(empty($tax_terms)){
//
//		$tax_terms = array();
//		}
//
//  $tax_query_terms = array();
//
//
//	foreach($tax_terms as $taxonomy=>$terms){
//
//    $tax_query_terms[] = array(
//								'taxonomy' => $taxonomy,
//								'field'    => 'term_id',
//								'terms'    => $terms,
//								'operator'    => $terms_relation,
//								);
//
//
//		}
//
//	if(empty($tax_query)){
//
//		$tax_query = array();
//
//		}
//
//	$tax_query_relation = array( 'relation' => $categories_relation );
//
//  $tax_query[] = array_merge($tax_query_relation, $tax_query_terms );




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







	//$default_query_args['tax_query'] = $tax_query;

	//echo '<pre>'.var_export($tax_query, true).'</pre>';

/* ################################ Meta query ######################################*/










//$meta_query = empty($meta_query) ? $meta_query : array();
//
//
//if(!empty($meta_query)){
//    $meta_query_relation = array('relation' => $meta_query_relation);
//    $meta_query = array_merge($meta_query_relation, $meta_query );
//
//}











/* ################################ Keyword query ######################################*/
	
	if(isset($_GET['keyword'])){
		
		$keyword = sanitize_text_field($_GET['keyword']);
		
		}



/*More Query parameter string to array*/
if(!empty($extra_query_parameter)){

    $extra_query_parameter = explode('&', $extra_query_parameter);

    foreach($extra_query_parameter as $parameter){

        $parameter = do_shortcode($parameter);
        $parameter = explode('=', $parameter);

        if (strpos($parameter[1], ',') !== false) {
            $parameter_args = explode(',', do_shortcode($parameter[1]));
            $query_parameter[$parameter[0]] = $parameter_args;
        }
        else{
            $query_parameter[$parameter[0]] = ($parameter[1]);
        }
    }
}
else{
    $query_parameter = array();
}




/* ################################ Archive pages ######################################*/


if($ignore_archive == 'no'):
    if (is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();
        $taxonomy = $term->taxonomy;
        $terms = $term->term_id;

        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'id',
            'terms'    => $terms,
        );
    }

    /* ################################ Search pages ######################################*/

    if(is_search()){
        $keyword = get_search_query();
    }

endif;



if(!empty($post_types))
$default_query_args['post_type'] = $post_types;

if(!empty($post_status))
$default_query_args['post_status'] = $post_status;

if(!empty($keyword))
$default_query_args['s'] = $keyword;

if(!empty($ignore_sticky_posts))
$default_query_args['ignore_sticky_posts'] = $ignore_sticky_posts;

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
$default_query_args['offset'] = $offset;

if(!empty($tax_query))
$default_query_args['tax_query'] = $tax_query;

if(!empty($meta_query))
$default_query_args['meta_query'] = $meta_query;

$query_merge = array_merge($default_query_args, $query_parameter);
$query_merge = apply_filters('post_grid_filter_query_args', $query_merge, $grid_id);


$post_grid_wp_query = new WP_Query($query_merge);

// for global use
$wp_query = $post_grid_wp_query;