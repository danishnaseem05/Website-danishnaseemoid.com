<?php
/*
 * Plugisn Options value
 * return on/off
 */
if( !function_exists('htmega_get_option') ){
    function htmega_get_option( $option, $section, $default = '' ){
        $options = get_option( $section );
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
        return $default;
    }
}

/*
 * Elementor Templates List
 * return array
 */
if( !function_exists('htmega_elementor_template') ){
    function htmega_elementor_template() {
        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $types = array();
        if ( empty( $templates ) ) {
            $template_lists = [ '0' => __( 'Do not Saved Templates.', 'htmega-addons' ) ];
        } else {
            $template_lists = [ '0' => __( 'Select Template', 'htmega-addons' ) ];
            foreach ( $templates as $template ) {
                $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }
        return $template_lists;
    }
}

/*
 * Elementor Setting page value
 * return $elget_value
 */
if( !function_exists('htmega_get_elementor_setting') ){
    function htmega_get_elementor_setting( $key, $post_id ){
        // Get the page settings manager
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

        // Get the settings model for current post
        $page_settings_model = $page_settings_manager->get_model( $post_id );

        // Retrieve value
        $elget_value = $page_settings_model->get_settings( $key );
        return $elget_value;
    }
}


/*
 * Sidebar Widgets List
 * return array
 */
if( !function_exists('htmega_sidebar_options') ){
    function htmega_sidebar_options() {
        global $wp_registered_sidebars;
        $sidebar_options = array();

        if ( ! $wp_registered_sidebars ) {
            $sidebar_options['0'] = __( 'No sidebars were found', 'htmega-addons' );
        } else {
            $sidebar_options['0'] = __( 'Select Sidebar', 'htmega-addons' );
            foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
                $sidebar_options[ $sidebar_id ] = $sidebar['name'];
            }
        }
        return $sidebar_options;
    }
}

/*
 * Get Taxonomy
 * return array
 */
if( !function_exists('htmega_get_taxonomies') ){
    function htmega_get_taxonomies( $htmega_texonomy = 'category' ){
        $terms = get_terms( array(
            'taxonomy' => $htmega_texonomy,
            'hide_empty' => true,
        ));
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
            return $options;
        }
    }
}

/*
 * Get Post Type
 * return array
 */
if( !function_exists('htmega_get_post_types') ){
    function htmega_get_post_types( $args = [] ) {
        $post_type_args = [
            'show_in_nav_menus' => true,
        ];
        if ( ! empty( $args['post_type'] ) ) {
            $post_type_args['name'] = $args['post_type'];
        }
        $_post_types = get_post_types( $post_type_args , 'objects' );

        $post_types  = [];
        foreach ( $_post_types as $post_type => $object ) {
            $post_types[ $post_type ] = $object->label;
        }
        return $post_types;
    }
}

/*
 * HTML Tag list
 * return array
 */
if( !function_exists('htmega_html_tag_lists') ){
    function htmega_html_tag_lists() {
        $html_tag_list = [
            'h1'   => __( 'H1', 'htmega-addons' ),
            'h2'   => __( 'H2', 'htmega-addons' ),
            'h3'   => __( 'H3', 'htmega-addons' ),
            'h4'   => __( 'H4', 'htmega-addons' ),
            'h5'   => __( 'H5', 'htmega-addons' ),
            'h6'   => __( 'H6', 'htmega-addons' ),
            'p'    => __( 'p', 'htmega-addons' ),
            'div'  => __( 'div', 'htmega-addons' ),
            'span' => __( 'span', 'htmega-addons' ),
        ];
        return $html_tag_list;
    }
}

/*
 * Custom Pagination
 */
if( !function_exists('htmega_custom_pagination') ){
    function htmega_custom_pagination( $totalpage ){
        $big = 999999999;
        echo '<div class="htbuilder-pagination">';
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $totalpage,
                'prev_text' => '&larr;', 
                'next_text' => '&rarr;', 
                'type'      => 'list', 
                'end_size'  => 3, 
                'mid_size'  => 3
            ) );
        echo '</div>';
    }
}

/*
 * Contact form list
 * return array
 */
if( !function_exists('htmega_contact_form_seven') ){
    function htmega_contact_form_seven(){
        $countactform = array();
        $htmega_forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
        $htmega_forms = get_posts( $htmega_forms_args );

        if( $htmega_forms ){
            foreach ( $htmega_forms as $htmega_form ){
                $countactform[$htmega_form->ID] = $htmega_form->post_title;
            }
        }else{
            $countactform[ esc_html__( 'No contact form found', 'htmega-addons' ) ] = 0;
        }
        return $countactform;
    }
}


/*
 * All Post Name
 * return array
 */
if( !function_exists('htmega_post_name') ){
    function htmega_post_name ( $post_type = 'post', $limit = 'default' ){
        if( $limit === 'default' ){
            $limit = htmega_get_option( 'loadpostlimit', 'htmega_general_tabs', '20' );
        }
        $options = array();
        $options = ['0' => esc_html__( 'None', 'htmega-addons' )];
        $wh_post = array( 'posts_per_page' => $limit, 'post_type'=> $post_type );
        $wh_post_terms = get_posts( $wh_post );
        if ( ! empty( $wh_post_terms ) && ! is_wp_error( $wh_post_terms ) ){
            foreach ( $wh_post_terms as $term ) {
                $options[ $term->ID ] = $term->post_title;
            }
            return $options;
        }
    }
}

/**
* Blog page return true
*/
if( !function_exists('htmega_builder_is_blog_page') ){
    function htmega_builder_is_blog_page() {
        global $post;
        //Post type must be 'post'.
        $post_type = get_post_type( $post );
        return (
            ( is_home() || is_archive() )
            && ( $post_type == 'post')
        ) ? true : false ;
    }
}

/**
 * Get all menu list
 * return array
 */
if( !function_exists('htmega_get_all_create_menus') ){
    function htmega_get_all_create_menus() {
        $raw_menus = wp_get_nav_menus();
        $menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
        $parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;
        if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
            unset( $menus[ $parent ] );
        }
        return $menus;
    }
}

/*
 * Caldera Form
 * @return array
 */
if( !function_exists('htmega_caldera_forms_options') ){
    function htmega_caldera_forms_options() {
        if ( class_exists( 'Caldera_Forms' ) ) {
            $caldera_forms = Caldera_Forms_Forms::get_forms( true, true );
            $form_options  = ['0' => esc_html__( 'Select Form', 'htmega-addons' )];
            $form          = array();
            if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
                foreach ( $caldera_forms as $form ) {
                    if ( isset($form['ID']) and isset($form['name'])) {
                        $form_options[$form['ID']] = $form['name'];
                    }   
                }
            }
        } else {
            $form_options = ['0' => esc_html__( 'Form Not Found!', 'htmega-addons' ) ];
        }
        return $form_options;
    }
}

/*
 * Check user Login and call this function
 */
global $user;
if ( empty( $user->ID ) ) {
    add_action('elementor/init', 'htmega_ajax_login_init' );
    add_action( 'elementor/init', 'htmega_ajax_register_init' );
}

/*
 * wp_ajax_nopriv Function
 */
function htmega_ajax_login_init() {
    add_action( 'wp_ajax_nopriv_htmega_ajax_login', 'htmega_ajax_login' );
}

/*
 * ajax login
 */
function htmega_ajax_login(){
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    $user_data = array();
    $user_data['user_login'] = !empty( $_POST['username'] ) ? $_POST['username']: "";
    $user_data['user_password'] = !empty( $_POST['password'] ) ? $_POST['password']: "";
    $user_data['remember'] = true;
    $user_signon = wp_signon( $user_data, false );

    if ( is_wp_error($user_signon) ){
        echo json_encode( [ 'loggeauth'=>false, 'message'=> esc_html__('Invalid username or password!', 'htmega-addons') ] );
    } else {
        echo json_encode( [ 'loggeauth'=>true, 'message'=> esc_html__('Login Successfully', 'htmega-addons') ] );
    }
    wp_die();
}

/*
 * wp_ajax_nopriv Register Function
 */
function htmega_ajax_register_init() {
    add_action( 'wp_ajax_nopriv_htmega_ajax_register', 'htmega_ajax_register' );
}

/*
* Ajax Register Call back
*/
function htmega_ajax_register(){

    $user_data = array(
        'user_login'    => !empty( $_POST['reg_name'] ) ? $_POST['reg_name']: "",
        'user_pass'     => !empty( $_POST['reg_password'] ) ? $_POST['reg_password']: "",
        'user_email'    => !empty( $_POST['reg_email'] ) ? $_POST['reg_email']: "",
        'user_url'      => !empty( $_POST['reg_website'] ) ? $_POST['reg_website']: "",
        'first_name'    => !empty( $_POST['reg_fname'] ) ? $_POST['reg_fname']: "",
        'last_name'     => !empty( $_POST['reg_lname'] ) ? $_POST['reg_lname']: "",
        'nickname'      => !empty( $_POST['reg_nickname'] ) ? $_POST['reg_nickname']: "",
        'description'   => !empty( $_POST['reg_bio'] ) ? $_POST['reg_bio']: "",
    );

    if( htmega_validation_data( $user_data ) !== true ){
        echo htmega_validation_data( $user_data );
    }else{
        $register_user = wp_insert_user( $user_data );
        if ( is_wp_error( $register_user ) ){
            echo json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Something is wrong please check again!', 'htmega-addons') ] );
        } else {
            echo json_encode( [ 'registerauth' =>true, 'message'=> esc_html__('Successfully Register', 'htmega-addons') ] );
        }
    }
    wp_die();

}

// Register Data Validation
function htmega_validation_data( $user_data ){

    if( empty( $user_data['user_login'] ) || empty( $_POST['reg_email'] ) || empty( $_POST['reg_password'] ) ){
        return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Username, Password and E-Mail are required', 'htmega-addons') ] );
    }
    if( !empty( $user_data['user_login'] ) ){

        if ( 4 > strlen( $user_data['user_login'] ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Username too short. At least 4 characters is required', 'htmega-addons') ] );
        }

        if ( username_exists( $user_data['user_login'] ) ){
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Sorry, that username already exists!', 'htmega-addons') ] );
        }

        if ( !validate_username( $user_data['user_login'] ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Sorry, the username you entered is not valid', 'htmega-addons') ] );
        }

    }
    if( !empty( $user_data['user_pass'] ) ){
        if ( 5 > strlen( $user_data['user_pass'] ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Password length must be greater than 5', 'htmega-addons') ] );
        }
    }
    if( !empty( $user_data['user_email'] ) ){
        if ( !is_email( $user_data['user_email'] ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Email is not valid', 'htmega-addons') ] );
        }
        if ( email_exists( $user_data['user_email'] ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Email Already in Use', 'htmega-addons') ] );
        }
    }
    if( !empty( $user_data['user_url'] ) ){
        if ( !filter_var( $user_data['user_url'], FILTER_VALIDATE_URL ) ) {
            return json_encode( [ 'registerauth' =>false, 'message'=> esc_html__('Website is not a valid URL', 'htmega-addons') ] );
        }
    }
    return true;

}

/*
 * Redirect 404 page select from plugins options
 */
if( !function_exists('htmega_redirect_404') ){
    function htmega_redirect_404() {
        $errorpage_id = htmega_get_option( 'errorpage','htmega_general_tabs' );
        if ( is_404() && !empty ( $errorpage_id ) ) {
            wp_redirect( esc_url( get_page_link( $errorpage_id ) ) ); die();
        }
    }
    add_action('template_redirect','htmega_redirect_404');
}
