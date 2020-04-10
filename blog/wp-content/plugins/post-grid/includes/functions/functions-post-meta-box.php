<?php

/*
* @Author 		PickPlugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('post_grid_post_settings_tabs_content_options', 'post_grid_post_settings_tabs_content_options',10, 2);

function post_grid_post_settings_tabs_content_options($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $class_post_grid_functions = new class_post_grid_functions();
    $post_grid_skins = $class_post_grid_functions->skins();

    $skin_list = array();

    foreach($post_grid_skins as $skin_key=>$skin_data){

        $skin_list[$skin_key] = $skin_data['name'];
    }


    $post_grid_post_settings = get_post_meta($post_id, 'post_grid_post_settings', true);

    $post_skin = !empty($post_grid_post_settings['post_skin']) ? $post_grid_post_settings['post_skin'] : 'flat';
    $custom_thumb_source = !empty($post_grid_post_settings['custom_thumb_source']) ? $post_grid_post_settings['custom_thumb_source'] : post_grid_plugin_url.'assets/frontend/css/images/placeholder.png';
    $thumb_custom_url = !empty($post_grid_post_settings['thumb_custom_url']) ? $post_grid_post_settings['thumb_custom_url'] : '';



    //var_dump($post_grid_skins);

    ?>
    <div class="section">
        <div class="section-title">Options</div>
        <p class="description section-description">Change post option here.</p>


        <?php

        $args = array(
            'id'		=> 'post_skin',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Choose post skin','post-grid'),
            'details'	=> __('Select your desired post skin from list.','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> false,
            'value'		=> $post_skin,
            'default'		=> 'flat',
            'args'		=> $skin_list,
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'custom_thumb_source',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom thumbnail image source','post-grid'),
            'details'	=> __('You can use custom thumbnail image source.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_thumb_source,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'thumb_custom_url',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom link to this post','post-grid'),
            'details'	=> __('You can use custom link to this post.','post-grid'),
            'type'		=> 'text',
            'value'		=> $thumb_custom_url,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);








        ?>
    </div>
    <?php
}



add_action('post_grid_post_settings_tabs_content_font_awesome', 'post_grid_post_settings_tabs_content_font_awesome',10, 2);

function post_grid_post_settings_tabs_content_font_awesome($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $post_grid_post_settings = get_post_meta($post_id, 'post_grid_post_settings', true);

    $font_awesome_icon = !empty($post_grid_post_settings['font_awesome_icon']) ? $post_grid_post_settings['font_awesome_icon'] : '';
    $font_awesome_icon_color = !empty($post_grid_post_settings['font_awesome_icon_color']) ? $post_grid_post_settings['font_awesome_icon_color'] : '';
    $font_awesome_icon_size = !empty($post_grid_post_settings['font_awesome_icon_size']) ? $post_grid_post_settings['font_awesome_icon_size'] : '';


    ?>
    <div class="section">
        <div class="section-title">Font Awesome</div>
        <p class="description section-description">Customize font awesome here.</p>


        <?php

        $args = array(
            'id'		=> 'font_awesome_icon',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Font awesome icon id','post-grid'),
            'details'	=> __('you can use font awesome id here, ex: <code>fa-share-alt</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $font_awesome_icon,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'font_awesome_icon_color',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Icon color','post-grid'),
            'details'	=> __('You can set custom color for icons.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $font_awesome_icon_color,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'font_awesome_icon_size',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Icon size','post-grid'),
            'details'	=> __('You can set custom size for icons.','post-grid'),
            'type'		=> 'text',
            'value'		=> $font_awesome_icon_size,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        ?>
    </div>
    <?php
}


add_action('post_grid_post_settings_tabs_content_custom_media', 'post_grid_post_settings_tabs_content_custom_media',10, 2);

function post_grid_post_settings_tabs_content_custom_media($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $post_grid_post_settings = get_post_meta($post_id, 'post_grid_post_settings', true);

    $custom_youtube_id = !empty($post_grid_post_settings['custom_youtube_id']) ? $post_grid_post_settings['custom_youtube_id'] : '';
    $custom_vimeo_id = !empty($post_grid_post_settings['custom_vimeo_id']) ? $post_grid_post_settings['custom_vimeo_id'] : '';
    $custom_dailymotion_id = !empty($post_grid_post_settings['custom_dailymotion_id']) ? $post_grid_post_settings['custom_dailymotion_id'] : '';
    $custom_mp3_url = !empty($post_grid_post_settings['custom_mp3_url']) ? $post_grid_post_settings['custom_mp3_url'] : '';
    $custom_soundcloud_id = !empty($post_grid_post_settings['custom_soundcloud_id']) ? $post_grid_post_settings['custom_soundcloud_id'] : '';
    $custom_video_MP4 = !empty($post_grid_post_settings['custom_video_MP4']) ? $post_grid_post_settings['custom_video_MP4'] : '';
    $custom_video_OGV = !empty($post_grid_post_settings['custom_video_OGV']) ? $post_grid_post_settings['custom_video_OGV'] : '';
    $custom_video_WEBM = !empty($post_grid_post_settings['custom_video_WEBM']) ? $post_grid_post_settings['custom_video_WEBM'] : '';



    ?>
    <div class="section">
        <div class="section-title">Custom media</div>
        <p class="description section-description">Customize media here.</p>


        <?php

        $args = array(
            'id'		=> 'custom_youtube_id',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom youtube id','post-grid'),
            'details'	=> __('Please use youtube video id only, ex: <code>S97MaG3kOMY</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_youtube_id,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'custom_vimeo_id',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom vimeo id','post-grid'),
            'details'	=> __('Please use vimeo video id only, ex: <code>152379391</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_vimeo_id,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'custom_dailymotion_id',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom dailymotion id','post-grid'),
            'details'	=> __('Please use dailymotion video id only, ex: <code>x4693dw</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_dailymotion_id,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'custom_mp3_url',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom mp3 URL','post-grid'),
            'details'	=> __('Please use mp3 file url, ex: <code>http://hello.com/media/hello.mp3</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_mp3_url,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'custom_soundcloud_id',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom soundcloud ID','post-grid'),
            'details'	=> __('Please use soundcloud audio id only, ex: <code>237668695</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_soundcloud_id,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'custom_video_MP4',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom MP4','post-grid'),
            'details'	=> __('Please use MP4 file url, ex: <code>http://hello.com/media/hello.mp4</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_video_MP4,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'custom_video_OGV',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom OGV','post-grid'),
            'details'	=> __('Please use OGV file url, ex: <code>http://hello.com/media/hello.ogv</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_video_OGV,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'custom_video_WEBM',
            'parent'		=> 'post_grid_post_settings',
            'title'		=> __('Custom WEBM','post-grid'),
            'details'	=> __('Please use WEBM file url, ex: <code>http://hello.com/media/hello.webm</code>.','post-grid'),
            'type'		=> 'text',
            'value'		=> $custom_video_WEBM,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        ?>
    </div>
    <?php
}
