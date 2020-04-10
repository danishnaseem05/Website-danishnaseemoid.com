<?php

/*
* @Author 		PickPlugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('post_grid_settings_tabs_content_shortcode', 'post_grid_settings_tabs_content_shortcode',10, 2);

function post_grid_settings_tabs_content_shortcode($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();


    ?>
    <div class="section">
        <div class="section-title">Shortcodes</div>
        <p class="description section-description">Simply copy these shortcode and user under content</p>


        <?php


        ob_start();

        ?>

        <div class="copy-to-clipboard">
            <input type="text" value="[post_grid id='<?php echo $post_id;  ?>']"> <span class="copied">Copied</span>
            <p class="description">You can use this shortcode under post content</p>
        </div>

        <div class="copy-to-clipboard">
            To avoid conflict:<br>
            <input type="text" value="[post_grid_pickplugins id='<?php echo $post_id;  ?>']"> <span class="copied">Copied</span>
            <p class="description">To avoid conflict with 3rd party shortcode also used same <code>[post_grid]</code>You can use this shortcode under post content</p>
        </div>

        <div class="copy-to-clipboard">
            <textarea cols="50" rows="1" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea> <span class="copied">Copied</span>
            <p class="description">PHP Code, you can use under theme .php files.</p>
        </div>

        <div class="copy-to-clipboard">
            <textarea cols="50" rows="1" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid_pickplugins id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea> <span class="copied">Copied</span>
            <p class="description">To avoid conflict, PHP code you can use under theme .php files.</p>
        </div>

        <style type="text/css">
            .copy-to-clipboard{}
            .copy-to-clipboard .copied{
                display: none;
                background: #e5e5e5;
                padding: 4px 10px;
                line-height: normal;
            }
        </style>

        <script>
            jQuery(document).ready(function($){
                $(document).on('click', '.copy-to-clipboard input, .copy-to-clipboard textarea', function () {
                    $(this).focus();
                    $(this).select();
                    document.execCommand('copy');
                    $(this).parent().children('.copied').fadeIn().fadeOut(2000);
                })
            })
        </script>
        <?php
        $html = ob_get_clean();
        $args = array(
            'id'		=> 'post_grid_shortcodes',
            'title'		=> __('Post Grid Shortcode','post-grid'),
            'details'	=> '',
            'type'		=> 'custom_html',
            'html'		=> $html,
        );
        $settings_tabs_field->generate_field($args, $post_id);


        ?>
    </div>
    <?php
}




add_action('post_grid_settings_tabs_content_query_post', 'post_grid_settings_tabs_content_query_post', 10, 2);

function post_grid_settings_tabs_content_query_post($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_meta_box = new class_post_grid_meta_box();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);


    //var_dump($post_grid_categories_array);



    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);



    $post_types = !empty($post_grid_meta_options['post_types']) ? $post_grid_meta_options['post_types'] : array('post');
    $categories = !empty($post_grid_meta_options['categories']) ? $post_grid_meta_options['categories'] : array();
    $categories_relation = !empty($post_grid_meta_options['categories_relation']) ? $post_grid_meta_options['categories_relation'] : 'OR';
    $taxonomies = !empty($post_grid_meta_options['taxonomies']) ? $post_grid_meta_options['taxonomies'] : array();

    $meta_query = !empty($post_grid_meta_options['meta_query']) ? $post_grid_meta_options['meta_query'] : array();
    $meta_query_relation = !empty($post_grid_meta_options['meta_query_relation']) ? $post_grid_meta_options['meta_query_relation'] : 'OR';

    $extra_query_parameter = !empty($post_grid_meta_options['extra_query_parameter']) ? $post_grid_meta_options['extra_query_parameter'] : '';

    $post_status = !empty($post_grid_meta_options['post_status']) ? $post_grid_meta_options['post_status'] : array();
    $query_order = !empty($post_grid_meta_options['query_order']) ? $post_grid_meta_options['query_order'] : '';
    $query_orderby = !empty($post_grid_meta_options['query_orderby']) ? $post_grid_meta_options['query_orderby'] : '';
    $query_orderby_meta_key = !empty($post_grid_meta_options['query_orderby_meta_key']) ? $post_grid_meta_options['query_orderby_meta_key'] : '';

    $posts_per_page = !empty($post_grid_meta_options['posts_per_page']) ? $post_grid_meta_options['posts_per_page'] : 10;
    $offset = isset($post_grid_meta_options['offset']) ? $post_grid_meta_options['offset'] : '0';
    $exclude_post_id = isset($post_grid_meta_options['exclude_post_id']) ? $post_grid_meta_options['exclude_post_id'] : '';

    $keyword = !empty($post_grid_meta_options['keyword']) ? $post_grid_meta_options['keyword'] :'';
    $permission_query = !empty($post_grid_meta_options['permission_query']) ? $post_grid_meta_options['permission_query'] :'';
    $ignore_archive = !empty($post_grid_meta_options['ignore_archive']) ? $post_grid_meta_options['ignore_archive'] :'';
    $sticky_post_query_type = !empty($post_grid_meta_options['sticky_post_query']['type']) ? $post_grid_meta_options['sticky_post_query']['type'] :'none';
    $ignore_sticky_posts = !empty($post_grid_meta_options['sticky_post_query']['ignore_sticky_posts']) ? $post_grid_meta_options['sticky_post_query']['ignore_sticky_posts'] :0;
    $date_query_type = !empty($post_grid_meta_options['date_query']['type']) ? $post_grid_meta_options['date_query']['type'] : 'none';

    $extact_date_year = !empty($post_grid_meta_options['date_query']['extact_date']['year']) ? $post_grid_meta_options['date_query']['extact_date']['year'] : '';
    $extact_date_month = !empty($post_grid_meta_options['date_query']['extact_date']['month']) ? $post_grid_meta_options['date_query']['extact_date']['month'] : '';
    $extact_date_day = !empty($post_grid_meta_options['date_query']['extact_date']['day']) ? $post_grid_meta_options['date_query']['extact_date']['day'] : '';
    $between_two_date_after_year = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['year'] : '';
    $between_two_date_after_month = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['month'] : '';
    $between_two_date_after_day = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['day'] : '';
    $between_two_date_before_year = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['year'] : '';
    $between_two_date_before_month = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['month'] : '';
    $between_two_date_before_day = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['day'] : '';
    $between_two_date_inclusive = !empty($post_grid_meta_options['date_query']['between_two_date']['inclusive']) ? $post_grid_meta_options['date_query']['between_two_date']['inclusive'] : 'true';


    $author_query_type = !empty($post_grid_meta_options['author_query']['type']) ? $post_grid_meta_options['author_query']['type'] : 'none';
    $author__in = !empty($post_grid_meta_options['author_query']['author__in']) ? $post_grid_meta_options['author_query']['author__in'] : '';
    $author__not_in = !empty($post_grid_meta_options['author_query']['author__not_in']) ? $post_grid_meta_options['author_query']['author__not_in'] : '';

    $password_query_type = !empty($post_grid_meta_options['password_query']['type']) ? $post_grid_meta_options['password_query']['type'] : 'none';
    $password_query_has_password = !empty($post_grid_meta_options['password_query']['has_password']) ? $post_grid_meta_options['password_query']['has_password'] : 'null';
    $password_query_post_password = !empty($post_grid_meta_options['password_query']['post_password']) ? $post_grid_meta_options['password_query']['post_password'] : '';




    $post_taxonomies_arr = post_grid_get_taxonomies($post_types)



    ?>



    <div class="section">
        <div class="section-title">Query Post</div>
        <p class="description section-description">Set the option for display and query posts.</p>


        <?php
        $args = array(
            'id'		=> 'post_types',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post types','post-grid'),
            'details'	=> __('Select your desired post types here you want to display post from, you can choose multiple post type.','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $post_types,
            'default'		=> array('post'),
            'attributes'		=> array('grid_id'=>$post_id),
            'args'		=> $post_grid_posttypes_array,
        );

        $settings_tabs_field->generate_field($args, $post_id);




        ?>
        <div class="setting-field">
            <div class="field-lable">Post Taxonomies & terms</div>

            <div class="field-input">


                <?php
               // echo post_grid_get_categories($post_id);
                ?>
                <div class="expandable" id="taxonomies-terms">

                    <?php
                    if(!empty($post_taxonomies_arr)):
                    foreach ($post_taxonomies_arr as $taxonomyIndex => $taxonomy){

                        $taxonomy_term_arr = array();
                        $the_taxonomy = get_taxonomy($taxonomy);

                        $terms_relation = isset($taxonomies[$taxonomy]['terms_relation']) ? $taxonomies[$taxonomy]['terms_relation'] : 'IN';
                        $terms = isset($taxonomies[$taxonomy]['terms']) ? $taxonomies[$taxonomy]['terms'] : array();
                        $checked = isset($taxonomies[$taxonomy]['checked']) ? $taxonomies[$taxonomy]['checked'] : '';
                        //var_dump($terms_relation);
                        $taxonomy_terms = get_terms( $taxonomy, array(
                            'hide_empty' => false,
                        ) );


                        //var_dump($taxonomy_terms);

                        if(!empty($taxonomy_terms))
                        foreach ($taxonomy_terms as $taxonomy_term){


                            $taxonomy_term_arr[$taxonomy_term->term_id] =$taxonomy_term->name.'('.$taxonomy_term->count.')';
                        }

                        $taxonomy_term_arr = !empty($taxonomy_term_arr) ? $taxonomy_term_arr : array();

                        ?>




                        <div class="item">
                            <div class="header">
                                <span class="expand  ">
                                    <i class="fas fa-expand"></i>
                                    <i class="fas fa-compress"></i>
                                </span>
                                <label><input type="checkbox" <?php if(!empty($checked)) echo 'checked'; ?>  name="post_grid_meta_options[taxonomies][<?php echo $taxonomy; ?>][checked]" value="<?php echo $taxonomy; ?>" /> <?php echo $the_taxonomy->labels->name; ?>(<?php echo $taxonomy; ?>)</label>
                            </div>
                            <div class="options">
                                <pre><?php //echo var_export($taxonomy, true); ?></pre>


                                <?php






                                $args = array(
                                    'id'		=> 'terms',
                                    'css_id'		=> 'terms-'.$taxonomyIndex,
                                    'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                                    'title'		=> __('Categories or Terms','post-grid'),
                                    'details'	=> __('Select post terms or categories','post-grid'),
                                    'type'		=> 'select2',
                                    'multiple'		=> true,
                                    'value'		=> $terms,
                                    'default'		=> array(),
                                    'args'		=> $taxonomy_term_arr,
                                );

                                $settings_tabs_field->generate_field($args, $post_id);





                                $args = array(
                                    'id'		=> 'terms_relation',
                                    'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                                    'title'		=> __('Terms relation','post-grid'),
                                    'details'	=> __('Choose term relation. some option only available in pro','post-grid'),
                                    'type'		=> 'radio',
                                    'for'		=> $taxonomy,
                                    'multiple'		=> true,
                                    'value'		=> $terms_relation,
                                    'default'		=> 'IN',
                                    'args'		=> array(
                                        'IN'=>__('IN','post-grid'),
                                        'NOT IN'=>__('NOT IN','post-grid'),
                                        'AND'=>__('AND','post-grid'),
                                        'EXISTS'=>__('EXISTS','post-grid'),
                                        'NOT EXISTS'=>__('NOT EXISTS','post-grid'),
                                    ),
                                );

                                $settings_tabs_field->generate_field($args, $post_id);

                                ?>

                            </div>
                        </div>
                        <?php




                    }else:
                        echo __('Please choose at least one post types. save/update post grid','post-grid');

                    endif;


                    ?>





                </div>



                <p class="description">Select post categories & terms.</p>
            </div>




        </div>

        <?php
        $args = array(
            'id'		=> 'categories_relation',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Taxonomies relation','post-grid'),
            'details'	=> __('Choose Taxonomies relation.','post-grid'),
            'type'		=> 'radio',
            //'for'		=> $taxonomy,
            'multiple'		=> true,
            'value'		=> $categories_relation,
            'default'		=> 'IN',
            'args'		=> array(
                'OR'=>__('OR','post-grid'),
                'AND'=>__('AND','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'post_status',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post status','post-grid'),
            'details'	=> __('Display post from following post status.','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $post_status,
            'default'		=> array('publish'),
            'args'		=> $class_post_grid_meta_box->get_post_status(),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_order',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post query order','post-grid'),
            'details'	=> __('Query order ascending or descending.','post-grid'),
            'type'		=> 'select',
            //'for'		=> $taxonomy,
            //'multiple'		=> true,
            'value'		=> $query_order,
            'default'		=> 'DESC',
            'args'		=> array(
                'ASC'=>__('Ascending','post-grid'),
                'DESC'=>__('Descending','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_orderby',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post query orderby','post-grid'),
            'details'	=> __('Select post query orderby','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $query_orderby,
            'default'		=> array('date'),
            'args'		=> $class_post_grid_meta_box->get_query_orderby(),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_orderby_meta_key',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Query orderby meta key','post-grid'),
            'details'	=> __('You can use custom meta field key for orderby meta key','post-grid'),
            'type'		=> 'text',
            'value'		=> $query_orderby_meta_key,
            'default'		=> '',
            'placeholder'		=> 'my_meta_key',
        );

        $settings_tabs_field->generate_field($args, $post_id);




        $args = array(
            'id'		=> 'posts_per_page',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Posts per page','post-grid'),
            'details'	=> __('Number of post each pagination. -1 to display all. default is 10 if you left empty.','post-grid'),
            'type'		=> 'text',
            'value'		=> $posts_per_page,
            'default'		=> '',
            'placeholder'		=> '10',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'offset',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Offset','post-grid'),
            'details'	=> __('Display posts from the n\'th, if you set Posts per page to -1 will not work offset.','post-grid'),
            'type'		=> 'text',
            'value'		=> $offset,
            'default'		=> '',
            'placeholder'		=> '3',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'exclude_post_id',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Exclude by post ID','post-grid'),
            'details'	=> __('You can exclude any post by ids here, use comma separate post id value, ex: 45,48','post-grid'),
            'type'		=> 'text',
            'value'		=> $exclude_post_id,
            'default'		=> '',
            'placeholder'		=> '45,48,50',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'keyword',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Search parameter','post-grid'),
            'details'	=> __('Query post by search keyword, please follow the reference https://codex.wordpress.org/Class_Reference/WP_Query#Search_Parameter','post-grid'),
            'type'		=> 'text',
            'value'		=> $keyword,
            'default'		=> '',
            'placeholder'		=> 'Keyword',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        ?>










    </div>

    <?php


}







add_action('post_grid_settings_tabs_content_skin_layout', 'post_grid_settings_tabs_content_skin_layout', 10, 2);

function post_grid_settings_tabs_content_skin_layout($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);
    $layout_content = !empty($post_grid_meta_options['layout']['content']) ? $post_grid_meta_options['layout']['content'] : 'OR';

    $enable_multi_skin = !empty($post_grid_meta_options['enable_multi_skin']) ? $post_grid_meta_options['enable_multi_skin'] : 'no';
    $skin = !empty($post_grid_meta_options['skin']) ? $post_grid_meta_options['skin'] : 'flat';

?>
        <div class="section">
            <div class="section-title">Slin & Layout</div>
            <p class="description section-description">Choose skin and customize layout.</p>
            <?php



            $class_post_grid_functions = new class_post_grid_functions();
            ob_start();

            ?>
            <div class="layout-list">
                <div class="idle  ">
                    <div class="name">
                        <select class="select-layout-content" name="post_grid_meta_options[layout][content]" >
                            <?php

                            $post_grid_layout_content = get_option('post_grid_layout_content');
                            if(empty($post_grid_layout_content)){

                                $layout_content_list = $class_post_grid_functions->layout_content_list();
                            }
                            else{

                                $layout_content_list = $post_grid_layout_content;

                            }





                            foreach($layout_content_list as $layout_key=>$layout_info){
                                ?>
                                <option <?php if($layout_content==$layout_key) echo 'selected'; ?>  value="<?php echo $layout_key; ?>"><?php echo $layout_key; ?></option>
                                <?php

                            }
                            ?>
                        </select>
                        <a target="_blank" class="edit-layout button" href="<?php echo admin_url().'edit.php?post_type=post_grid&page=layout_editor&layout_content='.$layout_content;?>" ><?php echo __('Edit' , 'post-grid'); ?></a>
                    </div>

                    <script>
                        jQuery(document).ready(function($)
                        {
                            $(document).on('change', '.select-layout-content', function()
                            {


                                var layout = $(this).val();

                                $('.edit-layout').attr('href', '<?php echo admin_url().'edit.php?post_type=post_grid&page=layout_editor&layout_content='; ?>'+layout);
                            })

                        })
                    </script>







                    <?php

                    if(empty($layout_content)){
                        $layout_content = 'flat-left';
                    }


                    ?>


                    <div class="layer-content">
                        <div class="<?php echo $layout_content; ?>">
                            <?php
                            $post_grid_layout_content = get_option( 'post_grid_layout_content' );

                            if(empty($post_grid_layout_content)){
                                $layout = $class_post_grid_functions->layout_content($layout_content);
                            }
                            else{
                                if(!empty($post_grid_layout_content[$layout_content])){
                                    $layout = $post_grid_layout_content[$layout_content];
                                }
                                else{
                                    $layout = array();
                                }


                            }

                            //  $layout = $class_post_grid_functions->layout_content($layout_content);

                            //var_dump($layout);

                            foreach($layout as $item_key=>$item_info){

                                $item_key = $item_info['key'];



                                ?>


                                <div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">

                                    <?php

                                    if($item_key=='thumb'){

                                        ?>
                                        <img style="width:100%; height:auto;" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
                                        <?php
                                    }

                                    elseif($item_key=='title'){

                                        ?>
                                        Lorem Ipsum is simply

                                        <?php
                                    }

                                    elseif($item_key=='excerpt'){

                                        ?>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text
                                        <?php
                                    }



                                    else{

                                        echo $item_info['name'];

                                    }

                                    ?>



                                </div>
                                <?php
                            }


                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <?php


            $html = ob_get_clean();

            $args = array(
                'id'		=> 'password_query',
                'title'		=> __('Content Layout','post-grid'),
                'details'	=> 'Choose Content Layout',
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);



            $skins = $class_post_grid_functions->skins();

           // ob_start();

            ?>

            <div class="setting-field">
                <div class="field-lable">Skins</div>
                <p class="description">Select grid skins</p>
                <div class="field-input">





                </div>
            </div>



            <div class="skin-list">
                <?php

                if(!empty($skins))
                foreach($skins as $skin_slug=>$skin_info){
                    ?>
                    <div class="skin-container">
                        <?php

                        if($skin==$skin_slug){
                            $checked = 'checked';
                            $selected_skin = 'selected';
                        }
                        else{
                            $checked = '';
                            $selected_skin = '';
                        }
                        ?>
                        <div class="header <?php echo $selected_skin; ?>">
<!--                            <span class="edit-link"><a href="#">Edit</a></span>-->
                            <label><input <?php echo $checked; ?> type="radio" name="post_grid_meta_options[skin]" value="<?php echo $skin_slug; ?>" ><?php echo $skin_info['name']; ?></label>
                        </div>
                        <div class="skin <?php echo $skin_slug; ?>">
                            <?php
                            include post_grid_plugin_dir.'skins/index.php';
                            ?>
                        </div>
                    </div>
                    <?php

                }

                ?>



            </div>
            <?php

//            $html = ob_get_clean();
//
//            $args = array(
//                'id'		=> 'skins',
//                'title'		=> __('Skins','post-grid'),
//                'details'	=> 'Select grid Skins',
//                'type'		=> 'custom_html',
//                'html'		=> $html,
//
//
//            );
//
//            $settings_tabs_field->generate_field($args, $post_id);




            ?>
        </div>
    <?php

}



add_action('post_grid_settings_tabs_content_layout_settings', 'post_grid_settings_tabs_content_layout_settings', 10, 2);

function post_grid_settings_tabs_content_layout_settings($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $items_width_desktop = !empty($post_grid_meta_options['width']['desktop']) ? $post_grid_meta_options['width']['desktop'] : '280px';
    $items_width_tablet = !empty($post_grid_meta_options['width']['tablet']) ? $post_grid_meta_options['width']['tablet'] : '280px';
    $items_width_mobile = !empty($post_grid_meta_options['width']['mobile']) ? $post_grid_meta_options['width']['mobile'] : '90%';

    $items_height_style = !empty($post_grid_meta_options['item_height']['style']) ? $post_grid_meta_options['item_height']['style'] : 'auto_height';
    $items_height_style_tablet = !empty($post_grid_meta_options['item_height']['style_tablet']) ? $post_grid_meta_options['item_height']['style_tablet'] : 'auto_height';
    $items_height_style_mobile = !empty($post_grid_meta_options['item_height']['style_mobile']) ? $post_grid_meta_options['item_height']['style_mobile'] : 'auto_height';

    $items_fixed_height = !empty($post_grid_meta_options['item_height']['fixed_height']) ? $post_grid_meta_options['item_height']['fixed_height'] : '220px';
    $items_fixed_height_tablet = !empty($post_grid_meta_options['item_height']['fixed_height_tablet']) ? $post_grid_meta_options['item_height']['fixed_height_tablet'] : '220px';
    $items_fixed_height_mobile = !empty($post_grid_meta_options['item_height']['fixed_height_mobile']) ? $post_grid_meta_options['item_height']['fixed_height_mobile'] : '220px';

    $items_media_height_style = !empty($post_grid_meta_options['media_height']['style']) ? $post_grid_meta_options['media_height']['style'] : 'auto_height';
    $items_media_fixed_height = !empty($post_grid_meta_options['media_height']['fixed_height']) ? $post_grid_meta_options['media_height']['fixed_height'] : '220px';


    $lazy_load_enable = !empty($post_grid_meta_options['lazy_load_enable']) ? $post_grid_meta_options['lazy_load_enable'] : 'yes';
    $lazy_load_image_src = !empty($post_grid_meta_options['lazy_load_image_src']) ? $post_grid_meta_options['lazy_load_image_src'] : '';

    $items_bg_color_type = !empty($post_grid_meta_options['items_bg_color_type']) ? $post_grid_meta_options['items_bg_color_type'] : 'fixed';
    $items_bg_color = !empty($post_grid_meta_options['items_bg_color']) ? $post_grid_meta_options['items_bg_color'] : '#fff';

    $items_margin = !empty($post_grid_meta_options['margin']) ? $post_grid_meta_options['margin'] : '10px';
    $item_padding = !empty($post_grid_meta_options['item_padding']) ? $post_grid_meta_options['item_padding'] : '0px';

    $featured_img_size = !empty($post_grid_meta_options['featured_img_size']) ? $post_grid_meta_options['featured_img_size'] : '';
    $thumb_linked = !empty($post_grid_meta_options['thumb_linked']) ? $post_grid_meta_options['thumb_linked'] : 'yes';
    $media_source = !empty($post_grid_meta_options['media_source']) ? $post_grid_meta_options['media_source'] : array();


    $container_padding = !empty($post_grid_meta_options['container']['padding']) ? $post_grid_meta_options['container']['padding'] : '10px';
    $container_bg_color = !empty($post_grid_meta_options['container']['bg_color']) ? $post_grid_meta_options['container']['bg_color'] : '';
    $container_bg_image = !empty($post_grid_meta_options['container']['bg_image']) ? $post_grid_meta_options['container']['bg_image'] : '';




    ?>
        <div class="section">
            <div class="section-title">Layout settings</div>
            <p class="description section-description">Customize the layout</p>

            <?php

            ob_start();

            ?>
            <div class="">
                Desktop:(min-width:1024px)<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][desktop]" value="<?php echo $items_width_desktop; ?>" />
            </div>
            <br>
            <div class="">
                Tablet:( min-width:768px )<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][tablet]" value="<?php echo $items_width_tablet; ?>" />
            </div>
            <br>
            <div class="">
                Mobile:( min-width : 320px, )<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][mobile]" value="<?php echo $items_width_mobile; ?>" />
            </div>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Grid items width','post-grid'),
                'details'	=> __('Grid item width for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);




            ob_start();

            ?>
            <table>
                <tr>
                    <td style="padding: 0 20px 0  0">

                        <div class="">
                            <p><b>Desktop:</b>(min-width:1024px)</p>
                            <label><input <?php if($items_height_style=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height]" value="<?php echo $items_fixed_height; ?>" />

                        </div>


                    </td>
                </tr>
                <tr>
                    <td style="padding:  0 20px 0  0">
                        <div class="">
                            <p><b>Tablet:</b>( min-width:768px )</p>
                            <label><input <?php if($items_height_style_tablet=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_tablet=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_tablet=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height_tablet]" value="<?php echo $items_fixed_height_tablet; ?>" />

                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 20px 0  0">
                        <div class="">
                            <p><b>Mobile:</b>( min-width : 320px, )</p>
                            <label><input <?php if($items_height_style_mobile=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_mobile=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_mobile=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height_mobile]" value="<?php echo $items_fixed_height_mobile; ?>" />

                        </div>
                    </td>
                </tr>

            </table>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Grid items height','post-grid'),
                'details'	=> __('Grid item height for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);





            ob_start();

            ?>
            <label><input <?php if($items_media_height_style=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="auto_height" /><?php _e('Auto height', 'post-grid'); ?></label><br />
            <label><input <?php if($items_media_height_style=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="fixed_height" /><?php _e('Fixed height', 'post-grid'); ?></label><br />
            <label><input <?php if($items_media_height_style=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="max_height" /><?php _e('Max height', 'post-grid'); ?></label><br />

            <div class="">

                <input type="text" name="post_grid_meta_options[media_height][fixed_height]" value="<?php echo $items_media_fixed_height; ?>" />
            </div>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Media height','post-grid'),
                'details'	=> __('Grid item media height for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);








            ob_start();

            ?>



            <label><input <?php if($lazy_load_enable=='yes') echo 'checked'; ?> type="radio" name="post_grid_meta_options[lazy_load_enable]" value="yes" /><?php _e('Yes', 'post-grid'); ?></label><br />
            <label><input <?php if($lazy_load_enable=='no') echo 'checked'; ?> type="radio" name="post_grid_meta_options[lazy_load_enable]" value="no" /><?php _e('No', 'post-grid'); ?></label><br />




            <p class="option-info"><?php _e('Gif image source:', 'post-grid'); ?></p>
            <img class="lazy_load_image" onClick="lazy_load_image_src_hello(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-1.gif" />
            <img class="lazy_load_image" onClick="lazy_load_image_src_hello(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-2.gif" />
            <img class="lazy_load_image" onClick="lazy_load_image_src_hello(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-3.gif" />

            <br>

            <input type="text" id="lazy_load_image_src" class="lazy_load_image_src" name="post_grid_meta_options[lazy_load_image_src]" value="<?php echo $lazy_load_image_src; ?>" /> <div onClick="clear_lazy_load_src()" class="button clear-lazy-load-src"> <?php echo __('Clear', 'post-grid'); ?></div>

            <script>
                function lazy_load_image_src_hello(img){
                    src =img.src;
                    document.getElementById('lazy_load_image_src').value  = src;
                }

                function clear_lazy_load_src(){
                    document.getElementById('lazy_load_image_src').value  = '';
                }
            </script>

            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'lazy_load_enable',
                'title'		=> __('Lazy load','post-grid'),
                'details'	=> __('Gif animation when loading grid','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);







            ob_start();

            ?>
            Background color type:<br>
            <label><input <?php if($items_bg_color_type=='fixed') echo 'checked'; ?> type="radio" name="post_grid_meta_options[items_bg_color_type]" value="fixed" /><?php _e('Fixed', 'post-grid'); ?></label><br />

            <br>
            <?php _e('Fixed Background color:', 'post-grid'); ?> <br>
            <input type="text" class="post-grid-color" name="post_grid_meta_options[items_bg_color]" value="<?php echo $items_bg_color; ?>" />

            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'items_bg_color_type',
                'title'		=> __('Grid items background color','post-grid'),
                'details'	=> __('Set custom background color for grid item wrapper.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);




            $args = array(
                'id'		=> 'margin',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Grid items margin','post-grid'),
                'details'	=> __('Grid item wrapper margin, you can use top right bottom left style, ex: 10px 15px 10px 15px','post-grid'),
                'type'		=> 'text',
                'value'		=> $items_margin,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args, $post_id);


            $args = array(
                'id'		=> 'item_padding',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Grid items padding','post-grid'),
                'details'	=> __('Grid item wrapper padding, you can use top right bottom left style, ex: 10px 15px 10px 15px','post-grid'),
                'type'		=> 'text',
                'value'		=> $item_padding,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args, $post_id);


            ob_start();

            ?>
            <div class="">
                Padding: <br>
                <input type="text" name="post_grid_meta_options[container][padding]" value="<?php echo $container_padding; ?>" />
            </div>
            <br>
            <div class="">
                Background color: <br>
                <input type="text" class="post-grid-color" name="post_grid_meta_options[container][bg_color]" value="<?php echo $container_bg_color; ?>" />
            </div>
            <br>
            <div class="">
                Background image: <br>
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dark_embroidery.png" />
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dimension.png" />
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/eight_horns.png" />

                <br>

                <input type="text" id="container_bg_image" class="container_bg_image" name="post_grid_meta_options[container][bg_image]" value="<?php echo $container_bg_image; ?>" /> <div onClick="clear_container_bg_image()" class="button clear-container-bg-image"><?php echo __('Clear', 'post-grid'); ?></div>

                <script>

                    function bg_img_src(img){

                        src =img.src;

                        document.getElementById('container_bg_image').value  = src;

                    }

                    function clear_container_bg_image(){

                        document.getElementById('container_bg_image').value  = '';

                    }


                </script>




            </div>
            <?php

            $html = ob_get_clean();



            $args = array(
                'id'		=> 'items_bg_color_type',
                'title'		=> __('Grid container options','post-grid'),
                'details'	=> __('Set custom background color for grid container wrapper.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);


            $args = array(
                'id'		=> 'featured_img_size',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Featured image size','post-grid'),
                'details'	=> __('Select media image size','post-grid'),
                'type'		=> 'select',
                'value'		=> $featured_img_size,
                'default'		=> 'large',
                'args'		=> post_grid_image_sizes(),
            );

            $settings_tabs_field->generate_field($args, $post_id);



            $args = array(
                'id'		=> 'thumb_linked',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Featured image linked to post','post-grid'),
                'details'	=> __('Select if you want to link to post with featured image.','post-grid'),
                'type'		=> 'radio',
                'multiple'		=> true,
                'value'		=> $thumb_linked,
                'default'		=> 'yes',
                'args'		=> array(
                    'yes'=>__('Yes','post-grid'),
                    'no'=>__('No','post-grid'),
                ),
            );

            $settings_tabs_field->generate_field($args, $post_id);





            ob_start();


            ?>
            <?php
            if(empty($media_source)){

                $media_source = $class_post_grid_functions->media_source();
            }
            else{
                //$media_source_main = $class_post_grid_functions->media_source();
                $media_source = $media_source;

            }


            ?>

            <div class="media-source-list expandable">
                <?php
                foreach($media_source as $source_key=>$source_info){
                    ?>
                    <div class="item">
                        <div class="header">
                            <span class="move" title="<?php echo __('Move', 'post-grid'); ?>"><i class="fas fa-bars"></i></span>
                            <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][id]" value="<?php echo $source_info['id']; ?>" />
                            <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][title]" value="<?php echo $source_info['title']; ?>" />
                            <label>
                                <input <?php if(!empty($source_info['checked'])) echo 'checked'; ?> type="checkbox" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][checked]" value="yes" /><?php echo $source_info['title']; ?>
                            </label>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <script>
                jQuery(document).ready(function($)
                {
                    $( ".media-source-list" ).sortable({revert: "invalid", handle: '.move'});

                })
            </script>
            <?php




            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Media source','post-grid'),
                'details'	=> __('Choose media source you want to display from.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);



            ?>



        </div>

    <?php

}




add_action('post_grid_settings_tabs_content_grid', 'post_grid_settings_tabs_content_grid', 10, 2);

function post_grid_settings_tabs_content_grid($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $grid_layout_name = !empty($post_grid_meta_options['grid_layout']['name']) ? $post_grid_meta_options['grid_layout']['name'] : 'layout_grid';
    $grid_layout_col_multi = !empty($post_grid_meta_options['grid_layout']['col_multi']) ? $post_grid_meta_options['grid_layout']['col_multi'] : '2';

    ?>
    <div class="section">
        <div class="section-title">Grid Settings</div>
        <p class="description section-description">Customize the Grid.</p>


        <?php
        ob_start();

        ?>

        <label>
            <input  type="radio" <?php if($grid_layout_name=='layout_grid') echo 'checked' ?> name="post_grid_meta_options[grid_layout][name]" value="layout_grid"><img title="N - N" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/layout_grid.png" />
        </label>













        <?php

        $html = ob_get_clean();

        $args = array(
            'id'		=> 'grid_layout',
            'title'		=> __('Grid layout','post-grid'),
            'details'	=> __('Choose grid layout','post-grid'),
            'type'		=> 'custom_html',
            'html'		=> $html,


        );

        $settings_tabs_field->generate_field($args, $post_id);

        ?>


    </div>

    <?php

}



add_action('post_grid_settings_tabs_content_pagination', 'post_grid_settings_tabs_content_pagination', 10, 2);

function post_grid_settings_tabs_content_pagination($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $pagination_type = !empty($post_grid_meta_options['nav_bottom']['pagination_type']) ? $post_grid_meta_options['nav_bottom']['pagination_type'] : 'normal';
    $max_num_pages = !empty($post_grid_meta_options['pagination']['max_num_pages']) ? $post_grid_meta_options['pagination']['max_num_pages'] : '0';
    $prev_text = !empty($post_grid_meta_options['pagination']['prev_text']) ? $post_grid_meta_options['pagination']['prev_text'] : __('« Previous','post-grid');
    $next_text = !empty($post_grid_meta_options['pagination']['next_text']) ? $post_grid_meta_options['pagination']['next_text'] : __('Next »','post-grid');
    $font_size = !empty($post_grid_meta_options['pagination']['font_size']) ? $post_grid_meta_options['pagination']['font_size'] : '16px';
    $font_color = !empty($post_grid_meta_options['pagination']['font_color']) ? $post_grid_meta_options['pagination']['font_color'] : '#fff';
    $bg_color = !empty($post_grid_meta_options['pagination']['bg_color']) ? $post_grid_meta_options['pagination']['bg_color'] : '#646464';
    $active_bg_color = !empty($post_grid_meta_options['pagination']['active_bg_color']) ? $post_grid_meta_options['pagination']['active_bg_color'] : '#4b4b4b';




    ?>
    <div class="section">
        <div class="section-title">Pagination Settings</div>
        <p class="description section-description">Customize the pagination.</p>

        <?php
        $args = array(
            'id'		=> 'pagination_type',
            'parent'		=> 'post_grid_meta_options[nav_bottom]',
            'title'		=> __('Pagination type','post-grid'),
            'details'	=> __('Select pagination you want to display.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $pagination_type,
            'default'		=> 'inline',
            'args'		=> array(
                'none'=>__('None','post-grid'),
                'normal'=>__('Normal Pagination','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'max_num_pages',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Max number of pagination','post-grid'),
            'details'	=> __('keep 0 (zero) for auto','post-grid'),
            'type'		=> 'text',
            'value'		=> $max_num_pages,
            'default'		=> '0',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'prev_text',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination Previous text','post-grid'),
            'details'	=> __('Custom text for previous page','post-grid'),
            'type'		=> 'text',
            'value'		=> $prev_text,
            'default'		=> '',
            'placeholder'		=> '« Previous',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'next_text',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination Next text','post-grid'),
            'details'	=> __('Custom text for next page','post-grid'),
            'type'		=> 'text',
            'value'		=> $next_text,
            'default'		=> '',
            'placeholder'		=> 'Next »',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'font_size',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination font size','post-grid'),
            'details'	=> __('Custom font size for pagination','post-grid'),
            'type'		=> 'text',
            'value'		=> $font_size,
            'default'		=> '16px',
            'placeholder'		=> '16px',
        );

        $settings_tabs_field->generate_field($args, $post_id);




        $args = array(
            'id'		=> 'font_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination font color','post-grid'),
            'details'	=> __('Set custom color for pagination text.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $font_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'bg_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination default background color','post-grid'),
            'details'	=> __('Set custom value for pagination background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'active_bg_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination active/hover background color','post-grid'),
            'details'	=> __('Set custom value filterable pagination item active background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $active_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);





        ?>



    </div>

    <?php

}






add_action('post_grid_settings_tabs_content_search', 'post_grid_settings_tabs_content_search', 10, 2);

function post_grid_settings_tabs_content_search($tab, $post_id){


    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $nav_top_search = !empty($post_grid_meta_options['nav_top']['search']) ? $post_grid_meta_options['nav_top']['search'] : 'no';
    $nav_top_search_placeholder = !empty($post_grid_meta_options['nav_top']['search_placeholder']) ? $post_grid_meta_options['nav_top']['search_placeholder'] : __('Start typing', 'post-grid');
    $nav_top_search_icon = !empty($post_grid_meta_options['nav_top']['search_icon']) ? $post_grid_meta_options['nav_top']['search_icon'] : '<i class="fas fa-search"></i>';

    ?>
    <div class="section">
        <div class="section-title">Search Settings</div>
        <p class="description section-description">Choose option for search.</p>

        <?php

        $args = array(
            'id'		=> 'search',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Display search input','post-grid'),
            'details'	=> __('Display or hide search input field at top.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $nav_top_search,
            'default'		=> 'no',
            'args'		=> array(
                'yes'=>__('Yes','post-grid'),
                'no'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'search_placeholder',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Search input placeholder text','post-grid'),
            'details'	=> __('Custom text for search input field','post-grid'),
            'type'		=> 'text',
            'value'		=> $nav_top_search_placeholder,
            'default'		=> __('Start typing', 'post-grid'),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'search_icon',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Search icon','post-grid'),
            'details'	=> __('Custom icon for search input field, you can use <a target="_blank" href="https://fontawesome.com/icons">fontawesome</a> icons.','post-grid'),
            'type'		=> 'text',
            'value'		=> $nav_top_search_icon,
            'default'		=> '<i class="fas fa-search"></i>',
        );

        $settings_tabs_field->generate_field($args, $post_id);












        ?>

    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_masonry', 'post_grid_settings_tabs_content_masonry', 10, 2);

function post_grid_settings_tabs_content_masonry($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $masonry_enable = !empty($post_grid_meta_options['masonry_enable']) ? $post_grid_meta_options['masonry_enable'] : 'no';

    ?>
    <div class="section">
        <div class="section-title">Masonry Settings</div>
        <p class="description section-description">Customize the masonry.</p>



        <?php
        $args = array(
            'id'		=> 'masonry_enable',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Masonry enable','post-grid'),
            'details'	=> __('Enable or disable masonry style grid.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $masonry_enable,
            'default'		=> 'inline',
            'args'		=> array(
                'yes'=>__('Yes','post-grid'),
                'no'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>


    </div>

    <?php

}















add_action('post_grid_settings_tabs_content_custom_scripts', 'post_grid_settings_tabs_content_custom_scripts', 10, 2);

function post_grid_settings_tabs_content_custom_scripts($tab, $post_id){


    $settings_tabs_field = new settings_tabs_field();

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $custom_js = !empty($post_grid_meta_options['custom_js']) ? $post_grid_meta_options['custom_js'] : '';
    $custom_css = !empty($post_grid_meta_options['custom_css']) ? $post_grid_meta_options['custom_css'] : '';

    ?>
    <div class="section">
        <div class="section-title">Custom Scripts & CSS</div>
        <p class="description section-description">Write your custom Scripts and CSS here.</p>





        <?php
        $args = array(
            'id'		=> 'custom_js',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Custom Js.','post-grid'),
            'details'	=> __('You can add custom scripts here, do not use <code>&lt;script&gt; &lt;/script&gt;</code> tag','post-grid'),
            'type'		=> 'scripts_js',
            'default'		=> '',
            'value'		=> $custom_js,

        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>

        <?php
        $args = array(
            'id'		=> 'custom_css',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Custom CSS.','post-grid'),
            'details'	=> __('You can add custom css here, do not use <code>  &lt;style&gt; &lt;/style&gt;</code> tag','post-grid'),
            'type'		=> 'scripts_css',
            'value'		=> $custom_css,
            'default'		=> '',

        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>

    </div>
    <?php


}







function post_grid_update_taxonomies_terms_by_posttypes(){

    $settings_tabs_field = new settings_tabs_field();
    $response = array();
    //$taxonomies = array();
    //if(current_user_can('manage_options')){


    $post_types = isset($_POST['post_types']) ? $_POST['post_types']: array();
    $grid_id = isset($_POST['grid_id']) ? $_POST['grid_id']: '';


    $post_grid_meta_options = get_post_meta($grid_id, 'post_grid_meta_options', true);

    $taxonomies = !empty($post_grid_meta_options['taxonomies']) ? $post_grid_meta_options['taxonomies'] : array();

    $response['post_types'] = $post_types;
    $post_taxonomies_arr = post_grid_get_taxonomies($post_types);

    ob_start();

    if(!empty($post_taxonomies_arr)):
        foreach ($post_taxonomies_arr as $taxonomyIndex=>$taxonomy){

            $taxonomy_term_arr = array();
            $the_taxonomy = get_taxonomy($taxonomy);

            $terms_relation = isset($taxonomies[$taxonomy]['terms_relation']) ? $taxonomies[$taxonomy]['terms_relation'] : 'IN';
            $terms = isset($taxonomies[$taxonomy]['terms']) ? $taxonomies[$taxonomy]['terms'] : array();
            $checked = isset($taxonomies[$taxonomy]['checked']) ? $taxonomies[$taxonomy]['checked'] : '';
            //var_dump($terms_relation);
            $taxonomy_terms = get_terms( $taxonomy, array(
                'hide_empty' => false,
            ) );


            //var_dump($taxonomy_terms);

            if(!empty($taxonomy_terms))
                foreach ($taxonomy_terms as $taxonomy_term){


                    $taxonomy_term_arr[$taxonomy_term->term_id] =$taxonomy_term->name.'('.$taxonomy_term->count.')';
                }

            $taxonomy_term_arr = !empty($taxonomy_term_arr) ? $taxonomy_term_arr : array();

            ?>
            <div class="item">
                <div class="header">
                    <span class="expand">
                        <i class="fas fa-expand"></i>
                        <i class="fas fa-compress"></i>
                    </span>
                    <label><input type="checkbox" <?php if(!empty($checked)) echo 'checked'; ?>  name="post_grid_meta_options[taxonomies][<?php echo $taxonomy; ?>][checked]" value="<?php echo $taxonomy; ?>" /> <?php echo $the_taxonomy->labels->name; ?>(<?php echo $taxonomy; ?>)</label>
                </div>
                <div class="options">
                    <?php

                    $args = array(
                        'id'		=> 'terms',
                        'css_id'		=> 'terms-'.$taxonomyIndex,
                        'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                        'title'		=> __('Categories or Terms','post-grid'),
                        'details'	=> __('Select post terms or categories','post-grid'),
                        'type'		=> 'select2',
                        'multiple'		=> true,
                        'value'		=> $terms,
                        'default'		=> array(),
                        'args'		=> $taxonomy_term_arr,
                    );

                    $settings_tabs_field->generate_field($args, $grid_id);





                    $args = array(
                        'id'		=> 'terms_relation',
                        'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                        'title'		=> __('Terms relation','post-grid'),
                        'details'	=> __('Choose term relation.','post-grid'),
                        'type'		=> 'radio',
                        'for'		=> $taxonomy,
                        'multiple'		=> true,
                        'value'		=> $terms_relation,
                        'default'		=> 'IN',
                        'args'		=> array(
                            'IN'=>__('IN','post-grid'),
                            'NOT IN'=>__('NOT IN','post-grid'),
                            'AND'=>__('AND','post-grid'),
                            'EXISTS'=>__('EXISTS','post-grid'),
                            'NOT EXISTS'=>__('NOT EXISTS','post-grid'),


                        ),
                    );

                    $settings_tabs_field->generate_field($args, $grid_id);

                    ?>

                </div>
            </div>
            <?php

        }
    else:
        echo __('Please choose at least one post types. save/update post grid','post-grid');

    endif;

    $response['html'] = ob_get_clean();



    echo json_encode( $response );

    //}

    die();

}

add_action('wp_ajax_post_grid_update_taxonomies_terms_by_posttypes', 'post_grid_update_taxonomies_terms_by_posttypes');





