<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_settings{

	public function __construct(){
		
		add_action('admin_menu', array( $this, 'post_grid_menu_init' ));
		
		}


	public function settings(){
		include('menu/settings-new.php');
	}

	
	public function layout_editor(){
		include('menu/layout-editor.php');	
	}

    public function data_update(){
        include('menu/data-update.php');
    }


	
	
	public function post_grid_menu_init() {

        $post_grid_info = get_option('post_grid_info');


        $data_update_status = isset($post_grid_info['data_update_status']) ? $post_grid_info['data_update_status'] : 'pending';

		
		add_submenu_page('edit.php?post_type=post_grid', __('Layout Editor', 'post-grid'), __('Layout Editor', 'post-grid'), 'manage_options', 'layout_editor', array( $this, 'layout_editor' ));
		
		//add_submenu_page('edit.php?post_type=post_grid', __('Settings', 'post-grid'), __('Settings', 'post-grid'), 'manage_options', 'post-grid-settings', array( $this, 'settings' ));

        if($data_update_status == 'pending'):
            add_submenu_page('edit.php?post_type=post_grid', __('Data Update', 'post-grid'), __('Data Update', 'post-grid'), 'manage_options', 'data-update', array( $this, 'data_update' ));

        endif;




    }



	
}
	
new class_post_grid_settings();