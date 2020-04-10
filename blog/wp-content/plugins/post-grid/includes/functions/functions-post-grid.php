<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


function post_grid_get_glossary_index($wp_query){

    $glossary_index = array();

    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
            $post_title = get_the_title();

            $glossary_index[] = isset($post_title[0]) ? $post_title[0] : '';

        endwhile;
    endif;

    return $glossary_index;

}


function post_grid_image_sizes(){

    $get_intermediate_image_sizes =  get_intermediate_image_sizes();
    $image_sizes = array();

    foreach($get_intermediate_image_sizes as $size_key){
        $size_key_name = str_replace('-', ' ',$size_key);
        $size_key_name = str_replace('_', ' ',$size_key);
        $size_key_name = ucfirst($size_key);
        $image_sizes[$size_key] = $size_key_name;

    }



    return $image_sizes;
}






function post_grid_posttypes_array(){

    $post_types_array = array();
    global $wp_post_types;

    $post_types_all = get_post_types( '', 'names' );
    foreach ( $post_types_all as $post_type ) {


        $obj = $wp_post_types[$post_type];
        $post_types_array[$post_type] = $obj->labels->singular_name;
    }


    return $post_types_array;
}

function post_grid_get_taxonomies($post_types){
    //$taxonomies = get_taxonomies();
    $taxonomies = get_object_taxonomies( $post_types );
    return $taxonomies;
    //var_dump($taxonomies);
}



function post_grid_categories_array($post_id){

    if(current_user_can('manage_options')){
        if(isset($_POST['post_types'])){

            //var_dump($_POST['post_types']);

            $post_types = stripslashes_deep($_POST['post_types']);
            //var_dump($post_types);

            $post_id = sanitize_text_field($_POST['post_id']);
            $post_grid_meta_options = get_post_meta( $post_id, 'post_grid_meta_options', true );
            //$categories = $post_grid_meta_options['categories'];

            if(!empty($post_grid_meta_options['categories'])){
                $categories = $post_grid_meta_options['categories'];
            }
            else{
                $categories = array();
            }



        }
        else{
            $post_grid_meta_options = get_post_meta( $post_id, 'post_grid_meta_options', true );

            if(!empty($post_grid_meta_options['post_types'])){
                $post_types = $post_grid_meta_options['post_types'];
            }
            else{
                $post_types = array();
            }

            //$post_types = $post_grid_meta_options['post_types'];

            if(!empty($post_grid_meta_options['categories'])){
                $categories = $post_grid_meta_options['categories'];
            }
            else{
                $categories = array();
            }

            //$categories = $post_grid_meta_options['categories'];


        }


        if(isset($_POST['post_id'])){
            $post_id = sanitize_text_field($_POST['post_id']);
        }


        $taxonomies = get_object_taxonomies( $post_types );

        if(!empty($taxonomies)){

            echo '<select  class="categories select2" name="post_grid_meta_options[categories][]" multiple="multiple" size="10">';

            foreach ($taxonomies as $taxonomy ) {

                $the_taxonomy = get_taxonomy($taxonomy);

                $args=array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                );

                $categories_all = get_categories($args);

                if(!empty($categories_all)){

                    ?>
                    <option disabled value="<?php echo $taxonomy; ?>" > - - - <?php echo $the_taxonomy->labels->name; ?> - - -</option>

                    <?php
                    foreach($categories_all as $category_info){

                        if(in_array($taxonomy.','.$category_info->cat_ID, $categories)){
                            $selected = 'selected';
                        }
                        else{
                            $selected = '';
                        }

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo $taxonomy.','.$category_info->cat_ID; ?>" ><?php echo $category_info->cat_name; echo ' (Total Post: '.$category_info->count.')'; ?></option>
                        <?php


                    }

                }


            }

            echo '</select>';

        }
        else{
            echo __('No categories found.', 'post-grid');
        }

    }




    if(isset($_POST['post_types'])){
        die();
    }


}










