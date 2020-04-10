<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function plugOPB_check_installation_date() {
 
    $nobug = "";
    $userId = get_current_user_id();
    $nobug = get_option('plugOPB_hide_bugs1One_'.$userId);
    if (!$nobug) {

        $install_date = get_option( 'plugOps_activation_date' );
        $past_date = strtotime( '-7 days' );
 
        if ( (int)$past_date > (int)$install_date ) {
 
            add_action( 'admin_notices', 'plugOPB_display_admin_NewestNotice' );
 
        }

    }

    $noWelcomeNotice = get_option('plugOPB_hide_welcomeNotice_'.$userId);
    if ($noWelcomeNotice != 'hideNotice') {
        add_action( 'admin_notices', 'plugOPB_display_admin_welcomeNotice' );
    }
 
}
add_action( 'admin_init', 'plugOPB_check_installation_date' );

function plugOPB_display_admin_NewestNotice() {
 
    $reviewurl = 'https://wordpress.org/support/plugin/page-builder-add/reviews/?rate=5#new-post';
    
    global $wp;
    $nobugurl = home_url( $wp->request ) . '?plugOPB_hide_bugs=1';

    if(strpos($nobugurl, 'wp-admin') == false){
        $nobugurl = get_admin_url() . '?plugOPB_hide_bugs=1';
    }


    $thisAdminURL = get_admin_url();
    $thisDefaultUrlProtocol =  'http://';
    if (strpos($thisAdminURL, 'https') !== false ) {
        $thisDefaultUrlProtocol =  'https://';
    }

    $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($actual_link, '?') == false) {
        $nobugurl = $actual_link . '?plugOPB_hide_bugs=1';
    }else{
        $nobugurl = $actual_link . '&plugOPB_hide_bugs=1';
    }

    $nobugurl = $thisDefaultUrlProtocol.$nobugurl;

    $install_date = get_option( 'plugOPB_activation_date' );

    echo "<div class='notice notice-success  pluginopsWriteReview'>
        
        <p style='display:inline-block;'>Do you like PluginOps Landing Page Builder ? Please write a review for us here : <b> <a href=".$reviewurl." target='_blank'> Click here </a> </b> </p>

        <a href=".$nobugurl."><button type='button' class='notice-dismiss' style='display:inline-block; position:relative; float:right;'><span class='screen-reader-text'>Dismiss this notice.</span></button></a>
    </div>";

}

function plugOPB_display_admin_welcomeNotice() {
 
    
    global $wp;


    $thisAdminURL = get_admin_url();
    $thisDefaultUrlProtocol =  'http://';
    if (strpos($thisAdminURL, 'https') !== false ) {
        $thisDefaultUrlProtocol =  'https://';
    }

    $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($actual_link, '?') === false) {
        $nobugurl = $actual_link . '?plugOPB_hide_welcome_notice=hidewelcomenotice';
    }else{
        $nobugurl = $actual_link . '&plugOPB_hide_welcome_notice=hidewelcomenotice';
    }

    $nobugurl = $thisDefaultUrlProtocol.$nobugurl;


    $install_date = get_option( 'plugOPB_activation_date' );

    echo "<div class='notice notice-success  pluginopsWelcomeNotice'>
        
        <p style='display:inline-block;'>Welcome  😀  - Thanks for installing PluginOps Landing Page Builder   | <b> <a href='https://pluginops.com/docs/home/' target='_blank'> Documentation </a> </b> </p>

        <a href=".$nobugurl."><button type='button' class='notice-dismiss' style='display:inline-block; position:relative; float:right;'><span class='screen-reader-text'>Dismiss this notice.</span></button></a>
    </div>";

}


function plugOPB_set_no_bug() {
 
    $nobug = "";
 
    if ( isset( $_GET['plugOPB_hide_bugs'] ) ) {
        $nobug = esc_attr( $_GET['plugOPB_hide_bugs'] );
    }

    if ( isset( $_GET['plugOPB_hide_welcome_notice'] ) ) {
        $nobug = esc_attr( $_GET['plugOPB_hide_welcome_notice'] );
    }

    if ( 1 == $nobug ) {
        $userId = get_current_user_id();
        add_option( 'plugOPB_hide_bugs1One_'.$userId, TRUE );
    }
    //delete_option( 'plugOPB_hide_bugs1One_'.get_current_user_id() );

    if ($nobug == 'hidewelcomenotice') {
        $userId = get_current_user_id();
        update_option( 'plugOPB_hide_welcomeNotice_'.$userId, 'hideNotice' );
    }
    //delete_option( 'plugOPB_hide_welcomeNotice_'.get_current_user_id() );
 
 
} add_action( 'admin_init', 'plugOPB_set_no_bug', 5 );

?>