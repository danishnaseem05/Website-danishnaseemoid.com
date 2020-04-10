<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function post_grid_post_settings(){
		
		$post_types = get_post_types();
		unset($post_types['post_grid']);
		
		$screens = $post_types;
		foreach ( $screens as $screen )
			{
				add_meta_box('post_grid_post_settings',__( 'Post Grid - Post Options', 'post-grid'),'post_grid_post_settings_input', $screen);
			}
	}
	
add_action( 'add_meta_boxes', 'post_grid_post_settings' );


function post_grid_post_settings_input( $post ) {
	
	global $post;
	wp_nonce_field( 'post_grid_post_settings_input', 'post_grid_post_settings_input_nonce' );

    $post_id = $post->ID;




    wp_enqueue_style('settings-tabs');
    wp_enqueue_script('settings-tabs');
    wp_enqueue_style('select2');
    wp_enqueue_script('select2');



    $post_grid_settings_tab = array();


    $post_grid_settings_tab[] = array(
        'id' => 'options',
        'title' => __('<i class="fas fas fa-tools"></i> Options','post-grid'),
        'priority' => 1,
        'active' => true,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'font_awesome',
        'title' => __('<i class="fas fa-laptop-code"></i> Font Awesome','post-grid'),
        'priority' => 2,
        'active' => false,
    );

    $post_grid_settings_tab[] = array(
        'id' => 'custom_media',
        'title' => __('<i class="fa fa-magic"></i> Custom Media','post-grid'),
        'priority' => 3,
        'active' => false,
    );



    $post_grid_settings_tabs = apply_filters('post_grid_post_settings_tabs', $post_grid_settings_tab);


    $tabs_sorted = array();
    foreach ($post_grid_settings_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
    array_multisort($tabs_sorted, SORT_ASC, $post_grid_settings_tabs);



    ?>

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
                do_action('post_grid_post_settings_tabs_content_'.$id, $tab, $post_id);
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="clear clearfix"></div>


<?php
	
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function post_grid_post_settings_save( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['post_grid_post_settings_input_nonce'] ) )
    return $post_id;

  $nonce = $_POST['post_grid_post_settings_input_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'post_grid_post_settings_input' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;

	/* OK, its safe for us to save the data now. */
	
	// Sanitize user input.
	$post_grid_post_settings = stripslashes_deep( $_POST['post_grid_post_settings'] );
	update_post_meta( $post_id, 'post_grid_post_settings', $post_grid_post_settings );	
	
		
}
add_action( 'save_post', 'post_grid_post_settings_save' );










function post_grid_post_settings_attachment_meta(){
    global $post;
    if( isset( $_POST['post_grid_post_settings'] ) ){
        update_post_meta( $post->ID, 'post_grid_post_settings', $_POST['post_grid_post_settings'] );
    }
}

add_action('edit_attachment', 'post_grid_post_settings_attachment_meta');



