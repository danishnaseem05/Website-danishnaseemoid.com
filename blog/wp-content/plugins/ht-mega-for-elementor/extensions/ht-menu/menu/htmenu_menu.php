<?php

class HTMega_Menu {

	function __construct() {
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'htmenu_add_custom_nav_fields' ) );
		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'htmenu_update_custom_nav_fields'), 10, 3 );
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );
	} // end constructor
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function htmenu_add_custom_nav_fields( $menu_item ) {
	    $menu_item->menuposition   = get_post_meta( $menu_item->ID, '_menu_item_menuposition', true );
	    $menu_item->megamenu       = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
        $menu_item->template       = get_post_meta( $menu_item->ID, '_menu_item_template', true );
	    $menu_item->menuwidth      = get_post_meta( $menu_item->ID, '_menu_item_menuwidth', true );
	    $menu_item->disablet       = get_post_meta( $menu_item->ID, '_menu_item_disablet', true );
	    return $menu_item;
	}
    
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function htmenu_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
        			
		
	    // Check if element is properly sent
        if( !isset( $_REQUEST['menu-item-menuposition'][$menu_item_db_id] ) ) {
           $_REQUEST['menu-item-menuposition'][$menu_item_db_id] = '';
        }
	    $menu_position_value = sanitize_text_field( $_REQUEST['menu-item-menuposition'][$menu_item_db_id] );
	    update_post_meta( $menu_item_db_id, '_menu_item_menuposition', $menu_position_value );
	  
	    // Check if element is properly sent
        if( !isset( $_REQUEST['menu-item-megamenu'][$menu_item_db_id] ) ) {
           $_REQUEST['menu-item-megamenu'][$menu_item_db_id] = '';
        }
        $megamenu_value = sanitize_text_field( $_REQUEST['menu-item-megamenu'][$menu_item_db_id] );
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $megamenu_value );
   
        // Check if element is properly sent
        if( !isset( $_REQUEST['menu-item-template'][$menu_item_db_id] ) ) {
           $_REQUEST['menu-item-template'][$menu_item_db_id] = '';
        }
        $template_value = sanitize_text_field( $_REQUEST['menu-item-template'][$menu_item_db_id] );
        update_post_meta( $menu_item_db_id, '_menu_item_template', $template_value );
   
	    // Check if element is properly sent
        if( !isset( $_REQUEST['menu-item-menuwidth'][$menu_item_db_id] ) ) {
           $_REQUEST['menu-item-menuwidth'][$menu_item_db_id] = '';
        }
        $menu_width_value = sanitize_text_field( $_REQUEST['menu-item-menuwidth'][$menu_item_db_id] );
        update_post_meta( $menu_item_db_id, '_menu_item_menuwidth', $menu_width_value );
	    
        // Check if element is properly sent
        if( !isset( $_REQUEST['menu-item-disablet'][$menu_item_db_id] ) ) {
           $_REQUEST['menu-item-disablet'][$menu_item_db_id] = '';
        }
        $disablet_value = sanitize_text_field( $_REQUEST['menu-item-disablet'][$menu_item_db_id] );
        update_post_meta( $menu_item_db_id, '_menu_item_disablet', $disablet_value );
   
	}
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_edit_walker( $walker, $menu_id ) {
        require_once HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/htmenu_edit_walker.php';
	    return 'HTMegaMenu_Walker_Nav_Menu_Edit';
	}
    
}

// instantiate plugin's class
$GLOBALS['HTMega_Menu'] = new HTMega_Menu();
require HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/htmenu_walker.php';
require HTMEGA_ADDONS_PL_PATH . 'extensions/ht-menu/menu/menu_term.php';