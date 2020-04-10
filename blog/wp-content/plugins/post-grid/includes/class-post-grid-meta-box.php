<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

if( ! class_exists( 'class_post_grid_meta_box' )){
    class class_post_grid_meta_box{


        function get_query_orderby(){

            $args['ID'] = __('ID','post-grid');
            $args['author'] = __('Author','post-grid');
            $args['title'] = __('Title','post-grid');
            $args['name'] = __('Name','post-grid');
            $args['type'] = __('Type','post-grid');
            $args['date'] = __('Date','post-grid');
            $args['post_date'] = __('post_date','post-grid');
            $args['modified'] = __('modified','post-grid');
            $args['parent'] = __('Parent','post-grid');
            $args['rand'] = __('Random','post-grid');
            $args['comment_count'] = __('Comment count','post-grid');
            $args['menu_order'] = __('Menu order','post-grid');
            $args['meta_value'] = __('Meta value','post-grid');
            $args['meta_value_num'] = __('Meta Value(number)','post-grid');
            $args['post__in'] = __('post__in','post-grid');
            $args['post_name__in'] = __('post_name__in','post-grid');

            return apply_filters('post_grid_query_orderby_args', $args);
        }

        function get_post_status(){

            $args['publish'] = __('Publish','post-grid');
            $args['pending'] = __('Pending','post-grid');
            $args['draft'] = __('Draft','post-grid');
            $args['auto-draft'] = __('Auto draft','post-grid');
            $args['future'] = __('Future','post-grid');
            $args['private'] = __('Private','post-grid');
            $args['inherit'] = __('Inherit','post-grid');
            $args['trash'] = __('Trash','post-grid');
            $args['any'] = __('Any','post-grid');


            return apply_filters('post_grid_query_orderby_args', $args);
        }








    }
}