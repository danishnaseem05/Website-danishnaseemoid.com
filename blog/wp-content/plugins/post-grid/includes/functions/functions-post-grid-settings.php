<?php

/*
* @Author 		PickPlugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('post_grid_settings_general', 'post_grid_settings_general',10, 2);

function post_grid_settings_general($tab){

    //$settings_tabs_field = new settings_tabs_field();

    $post_grid_license = get_option('post_grid_license');
    $settings_tabs_field = new settings_tabs_field();


    $license_key = isset($post_grid_license['license_key']) ? $post_grid_license['license_key'] : '';

    //var_dump($check_license_on_server);

//    $date_expiry = isset($check_license_on_server['date_expiry']) ? $check_license_on_server['date_expiry'] : 'Not set yet';
//    $license_status = isset($check_license_on_server['license_status']) ? $check_license_on_server['license_status'] : 'Not set yet';
//    $mgs = isset($check_license_on_server['mgs']) ? $check_license_on_server['mgs'] : '';

    ?>
    <div class="section">
        <div class="section-title">General</div>
        <p class="description section-description">Put license key to get automatic update.</p>



        <?php
        ob_start();
        ?>
        <input type="text" name="license_key" value="<?php echo $license_key; ?>">

        <?php
        $html = ob_get_clean();
        $args = array(
            'id'		=> 'license_key',
            'title'		=> __('License key','post-grid'),
            'details'	=> 'To get automatic plugin update plugin put lincese key here.',
            'type'		=> 'custom_html',
            'html'		=> $html,
        );
        $settings_tabs_field->generate_field($args);


        ?>

    </div>
    <?php
}



add_action('post_grid_settings_our_plugins', 'post_grid_settings_our_plugins',10, 2);

function post_grid_settings_our_plugins($tab){

    //$settings_tabs_field = new settings_tabs_field();


    ?>
    <div class="section">
        <div class="section-title">Our Plugins</div>
        <p class="description section-description">Simply copy these shortcode and user under content</p>

    </div>
    <?php
}


