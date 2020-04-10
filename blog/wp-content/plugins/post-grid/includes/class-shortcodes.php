<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_shortcodes{
	
	
    public function __construct(){
		
		add_shortcode( 'post_grid', array( $this, 'post_grid_new_display' ) );

    }

	
	public function post_grid_new_display($atts, $content = null ){

        $atts = shortcode_atts(
            array(
                'id' => "",
                ), $atts);

        $grid_id = $atts['id'];



        wp_reset_postdata();
        //var_dump(get_the_ID());

        ob_start();

        if(empty($grid_id)){
            echo 'Please provide valid post grid id, ex: <code>[post_grid id="123"]</code>';
            return;
        }

        include( post_grid_plugin_dir . 'templates/post-grid.php');

        wp_enqueue_script(   'post_grid_scripts');
        wp_localize_script('post_grid_scripts', 'post_grid_ajax', array('post_grid_ajaxurl' => admin_url('admin-ajax.php')));

        wp_enqueue_script( 'masonry.js' );
        wp_enqueue_script( 'imagesloaded.js' );
        wp_enqueue_style( 'post-grid-style' );
        wp_enqueue_style( 'post-grid-skin' );
        //wp_enqueue_style('font-awesome-5' );

        return ob_get_clean();
				
		
		}	

	}

new class_post_grid_shortcodes();