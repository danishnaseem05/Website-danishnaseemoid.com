<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

function post_skin_posttype_register() {

    $labels = array(
        'name' => __('Post Skin', 'post-grid'),
        'singular_name' => __('Post Skin', 'post-grid'),
        'add_new' => __('New Post Skin', 'post-grid'),
        'add_new_item' => __('New Post Skin'),
        'edit_item' => __('Edit Post Skin'),
        'new_item' => __('New Post Skin'),
        'view_item' => __('View Post Skin'),
        'search_items' => __('Search Post Skin'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => null,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'show_in_menu' 			=> 'edit.php?post_type=post_grid',
        'menu_icon' => post_grid_plugin_url.'assets/admin/images/menu-grid-icon.png',

    );

    register_post_type( 'post_skin' , $args );

}

add_action('init', 'post_skin_posttype_register');





/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function meta_boxes_post_skin(){

    $screens = array( 'post_skin' );
    global $post;
    $post_id = $post->ID;




    foreach ( $screens as $screen ){
        add_meta_box('post_skin_metabox',__('Create Post Skin', 'post-grid'),'meta_boxes_post_skin_input', $screen);
    }



}
add_action( 'add_meta_boxes', 'meta_boxes_post_skin' );





function meta_boxes_post_skin_input( $post ) {

    global $post;
    wp_nonce_field( 'meta_boxes_post_skin_input', 'meta_boxes_post_skin_input_nonce' );

    $post_id = $post->ID;
    $post_skin_data = get_post_meta($post_id, 'post_skin_data', true);

    $hello = $post_skin_data['hello'];


    $class_post_grid_functions = new class_post_grid_functions();


    $skin_elements = $class_post_grid_functions->skin_elements();


    //var_dump($skin_elements);


    ?>

    <div class="post-skin-metabox container-fluid settings-tabs">
        <div class="row">
            <div class="skin-elements expandable col-lg-3">

                <?php foreach ($skin_elements as $elementGroup):
                    $groupName = $elementGroup['name'];
                    $groupDescription = $elementGroup['description'];
                    $items = $elementGroup['items'];
                    ?>
                    <div class="item">
                        <div class="header">

                            <span class="expand  ">
                                <i class="fas fa-expand"></i>
                                <i class="fas fa-compress"></i>
                            </span>
                            <span><?php echo $groupName; ?></span>

                        </div>
                        <div class="options">
                            <p><?php echo $groupDescription; ?></p>
                            <div class="groupItems">
                                <?php
                                foreach ($items as $itemIndex=>$item):
                                    $name = $item['name'];
                                    ?>
                                    <div itemid="<?php echo $itemIndex; ?>" class="item"><?php echo $name; ?></div>
                                    <?php
                                endforeach;
                                ?>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>



            </div>
            <div class="skin-preview  col-lg-5">
                dfg

            </div>

            <div class="skin-layers col-lg-3">
                <div class="expandable">
                    <div class="item">
                        <div class="header">

                            <span class="expand-collapse  ">
                                <i class="fas fa-expand"></i>
                                <i class="fas fa-compress"></i>
                            </span>
                            <span>Media</span>

                        </div>
                        <div class="options">

                            <div class="groupItems">

                                dfgdfg
                            </div>

                        </div>
                    </div>


                    <div class="item">
                        <div class="header">

                            <span class="expand-collapse  ">
                                <i class="fas fa-expand"></i>
                                <i class="fas fa-compress"></i>
                            </span>
                            <span>Content</span>

                        </div>
                        <div class="options">

                            <div class="groupItems">

                                dfgdfg
                            </div>

                        </div>
                    </div>

                    <div class="item">
                        <div class="header">

                            <span class="expand-collapse  ">
                                <i class="fas fa-expand"></i>
                                <i class="fas fa-compress"></i>
                            </span>
                            <span>Cover</span>

                        </div>
                        <div class="options">

                            <div class="groupItems">

                                dfgdfg
                            </div>

                        </div>
                    </div>
                </div>




            </div>

        </div>


    </div>







    <?php



}








/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */



function meta_boxes_post_skin_save( $post_id ) {

    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['meta_boxes_post_skin_input_nonce'] ) )
        return $post_id;

    $nonce = $_POST['meta_boxes_post_skin_input_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'meta_boxes_post_skin_input' ) )
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;



    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    //$post_skin_collapsible = sanitize_text_field( $_POST['post_skin_collapsible'] );


    $post_skin_data = stripslashes_deep( $_POST['post_skin_data'] );
    update_post_meta( $post_id, 'post_skin_data', $post_skin_data );





}
add_action( 'save_post', 'meta_boxes_post_skin_save' );



