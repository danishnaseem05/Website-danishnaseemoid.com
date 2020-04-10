<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

function post_grid_posttype_register() {

    $labels = array(
        'name' => __('Post Grid', 'post-grid'),
        'singular_name' => __('Post Grid', 'post-grid'),
        'add_new' => __('New Post Grid', 'post-grid'),
        'add_new_item' => __('New Post Grid'),
        'edit_item' => __('Edit Post Grid'),
        'new_item' => __('New Post Grid'),
        'view_item' => __('View Post Grid'),
        'search_items' => __('Search Post Grid'),
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
        'menu_icon' => post_grid_plugin_url.'assets/admin/images/menu-grid-icon.png',

    );

    register_post_type( 'post_grid' , $args );

}

add_action('init', 'post_grid_posttype_register');





/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function meta_boxes_post_grid(){

    $screens = array( 'post_grid' );
    global $post;
    $post_id = $post->ID;




    foreach ( $screens as $screen ){
        add_meta_box('post_grid_metabox',__('Post Grid Options', 'post-grid'),'meta_boxes_post_grid_input', $screen);
        add_meta_box('post_grid_metabox_side',__('Post Grid Information', 'post-grid'),'meta_boxes_post_grid_side', $screen,'side');
    }



}
add_action( 'add_meta_boxes', 'meta_boxes_post_grid' );





function meta_boxes_post_grid_input( $post ) {

    global $post;
    wp_nonce_field( 'meta_boxes_post_grid_input', 'meta_boxes_post_grid_input_nonce' );

    $post_id = $post->ID;
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $grid_type =     $post_types = !empty($post_grid_meta_options['grid_type']) ? $post_grid_meta_options['grid_type'] : 'grid';

    $post_grid_settings_tab = array();


    $post_grid_settings_tab[] = array(
        'id' => 'shortcode',
        'title' => __('<i class="fas fa-laptop-code"></i> Shortcode','post-grid'),
        'priority' => 1,
        'active' => false,
    );


    $post_grid_settings_tab[] = array(
        'id' => 'query_post',
        'title' => __('<i class="fas fa-cubes"></i> Query Post','post-grid'),
        'priority' => 2,
        'active' => true,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'skin_layout',
        'title' => __('<i class="fas fa-magic"></i> Skin & Layout','post-grid'),
        'priority' => 3,
        'active' => false,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'layout_settings',
        'title' => __('<i class="fas fa-tools"></i> Layout Settings','post-grid'),
        'priority' => 4,
        'active' => false,
    );


    $post_grid_settings_tab[] = array(
        'id' => 'grid',
        'title' => __('<i class="fas fa-th"></i> Grid','post-grid'),
        'priority' => 5,
        'active' => false,
        'data_visible' => 'grid',
        'hidden' => ($grid_type == 'grid')? false : true ,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'masonry',
        'title' => __('<i class="fas fa-th-large"></i> Masonry','post-grid'),
        'priority' => 5,
        'active' => false,
        'data_visible' => 'masonry grid glossary timeline filterable',
        'hidden' => ($grid_type == 'slider')? true : false ,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'pagination',
        'title' => __('<i class="fas fa-pager"></i> Pagination','post-grid'),
        'priority' => 5,
        'active' => false,
        'data_visible' => 'masonry grid glossary timeline filterable isotope',
        'hidden' => ($grid_type == 'slider')? true : false ,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'custom_scripts',
        'title' => __('<i class="fas fa-code"></i> Custom Scripts','post-grid'),
        'priority' => 6,
        'active' => false,
    );
    $post_grid_settings_tab[] = array(
        'id' => 'search',
        'title' => __('<i class="fas fa-search"></i> Search','post-grid'),
        'priority' => 5,
        'active' => false,
    );

    $post_grid_settings_tabs = apply_filters('post_grid_settings_tabs', $post_grid_settings_tab);


    $tabs_sorted = array();
    foreach ($post_grid_settings_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
    array_multisort($tabs_sorted, SORT_ASC, $post_grid_settings_tabs);







    ?>

    <div class="post-grid-meta-box">

        <div class="grid-type-wrap">
            <span>Grid Type: </span>
            <label>
                <input name="post_grid_meta_options[grid_type]" <?php if($grid_type == 'grid') echo 'checked'; ?>  type="radio" value="grid">Normal grid
            </label>

            <label title="Only in pro">
                <input  disabled name="post_grid_meta_options[grid_type]" <?php if($grid_type == 'filterable') echo 'checked'; ?> type="radio" value="filterable">Filterable (In Pro)
            </label>

<!---->
<!---->
<!--            <label>-->
<!--                <input name="post_grid_meta_options[grid_type]" --><?php //if($grid_type == 'isotope') echo 'checked'; ?><!-- type="radio" value="isotope">Isotope-->
<!--            </label>-->

            <label title="Only in pro">
                <input  disabled name="post_grid_meta_options[grid_type]" <?php if($grid_type == 'slider') echo 'checked'; ?> type="radio" value="slider">Slider (In Pro)
            </label>
<!---->
<!--            <label>-->
<!--                <input name="post_grid_meta_options[grid_type]" --><?php //if($grid_type == 'timeline') echo 'checked'; ?><!-- type="radio" value="timeline">Timeline-->
<!--            </label>-->

            <label title="Only in pro">
                <input  disabled name="post_grid_meta_options[grid_type]" <?php if($grid_type == 'glossary') echo 'checked'; ?> type="radio" value="glossary">Glossary (In Pro)
            </label>

<!--            <label>-->
<!--                <input name="post_grid_meta_options[grid_type]" --><?php //if($grid_type == 'masonry') echo 'checked'; ?><!-- type="radio" value="masonry">Masonry-->
<!--            </label>-->

        </div>

        <div class="settings-tabs vertical">
            <ul class="tab-navs">
                <?php
                foreach ($post_grid_settings_tabs as $tab){
                    $id = $tab['id'];
                    $title = $tab['title'];
                    $active = $tab['active'];
                    $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                    $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                    ?>
                    <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>"><?php echo $title; ?></li>
                    <?php
                }
                ?>
            </ul>
            <?php
            foreach ($post_grid_settings_tabs as $tab){
                $id = $tab['id'];
                $title = $tab['title'];
                $active = $tab['active'];


                ?>

                <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                    <?php
                    do_action('post_grid_settings_tabs_content_'.$id, $tab, $post_id);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="clear clearfix"></div>

    </div>










    <?php



}



function meta_boxes_post_grid_side( $post ) {

    ?>
    <div class="post-grid-meta-box">

        <ul>
            <li>Post Grid Version: <?php echo post_grid_version; ?></li>
            <li>Tested WP: 5.2</li>

        </ul>

        <h3>Try Pro</h3>
        <a class="button" href="https://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/?ref=dashboard" target="_blank">Buy Pro</a><p class="description">If you are looking some extra feature you may try our premium version.</p>

        <h3>Documentation</h3>
        <a class="button" href="https://www.pickplugins.com/documentation/post-grid/?ref=dashboard" target="_blank">Documentation</a><p class="description">Before asking, submitting reviews please take a look on our documentation, may help your issue fast.</p>

        <h3>Looking for support?</h3>
        <a class="button" href="https://www.pickplugins.com/forum/?ref=dashboard" target="_blank">Create Support Ticket</a><p class="description">Its free and you can ask any question about our plugins and get support fast.</p>

        <h3>Provide your feedback</h3>

        <a class="button" href="https://wordpress.org/support/plugin/post-grid/reviews/?filter=5" target="_blank">Submit Reviews</a> <a class="button" href="https://wordpress.org/support/plugin/post-grid/#new-topic-0" target="_blank">Ask wordpress.org</a><p>We spent thousand+ hours to development on this plugin, please submit your reviews wisely.</p><p>If you have any issue with this plugin please submit our forums or contact our support first.</p><p class="description">Your feedback and reviews are most important things to keep our development on track. If you have time please submit us five star <a href="https://wordpress.org/support/plugin/post-grid/reviews/?filter=5"><span style="color: orange"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span></a> reviews.</p>

        <h3>FAQ</h3>
        <div class="faq">
            <?php
            $class_post_grid_support = new class_post_grid_support();
            $faqs =  $class_post_grid_support->faq();

            echo '<ul>';

                foreach($faqs as $item){

                    echo '<li class="item">';
                    echo '<a target="_blank" href="'.$item['answer_url'].'"><i class="far fa-dot-circle"></i> '.$item['question'].'</a>';


                    echo '</li>';
                }

            echo '</ul>';
            ?>

        </div>



        <h3>Video Tutorials</h3>
        <p class="description">Some tutorial contain pro features.</p>
        <div class="faq">
            <?php

            $video_tutorials =  $class_post_grid_support->video_tutorials();

            echo '<ul>';
                foreach($video_tutorials as $item){

                    echo '<li class="item">';
                    echo '<a target="_blank" href="'.$item['answer_url'].'"><i class="far fa-dot-circle"></i> '.$item['question'].'</a>';


                    echo '</li>';

                }
                echo '</ul>';

            ?>

        </div>










    </div>
    <?php

}








/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */



function meta_boxes_post_grid_save( $post_id ) {

    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['meta_boxes_post_grid_input_nonce'] ) )
        return $post_id;

    $nonce = $_POST['meta_boxes_post_grid_input_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'meta_boxes_post_grid_input' ) )
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;



    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    //$post_grid_collapsible = sanitize_text_field( $_POST['post_grid_collapsible'] );


    $post_grid_meta_options = stripslashes_deep( $_POST['post_grid_meta_options'] );
    update_post_meta( $post_id, 'post_grid_meta_options', $post_grid_meta_options );





}
add_action( 'save_post', 'meta_boxes_post_grid_save' );



