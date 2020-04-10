<?php

/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */

class HTMegaMenu_Walker_Nav_Menu_Edit extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {	}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array() ) {}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	    $title = $item->title;
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)','htmega-menu' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)','htmega-menu'), $item->title );
	    }
	    $title = empty( $item->label ) ? $title : $item->label;
	    ?>
	    <li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <div class="menu-item-bar">
	            <div class="menu-item-handle">
	                <span class="item-title"><?php echo esc_attr( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_attr( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up','htmega-menu'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down','htmega-menu'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr( $item_id ); ?>" title="<?php esc_attr_e('Edit Menu Item','htmega-menu'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><span class="screen-reader-text"><?php esc_html_e( 'Edit Menu','htmega-menu' ); ?></span></a>
	                </span>
	            </div>
	        </div>
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
	                        <?php esc_html_e( 'URL','htmega-menu' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'Navigation Label' ,'htmega-menu'); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />

	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'Title Attribute','htmega-menu' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php esc_html_e( 'Open link in a new window/tab','htmega-menu' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'CSS Classes (optional)','htmega-menu' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'Link Relationship (XFN)','htmega-menu' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'Description', 'htmega-menu' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'htmega-menu'); ?></span>
	                </label>
	            </p>

	            <?php /* New fields insertion starts here */ ?> 

	            <p class="field-columntitle description description-wide hidn">
	                <label for="edit-menu-item-disablet-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="checkbox" id="edit-menu-item-disablet-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-disalbet" name="menu-item-disablet[<?php echo esc_attr($item_id); ?>]" value="disabled" <?php checked( $item->disablet, 'disabled' ); ?> />
        				<?php esc_html_e( 'Disable Column Title', 'htmega-menu' ); ?>
	                </label>
	            </p>

	            <p class="field-enablemegamenu description description-wide hidn">
	                <label for="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>" value="enabled" name="menu-item-megamenu[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->megamenu, 'enabled' ); ?> />
	                    <strong><?php esc_html_e( 'Enable MegaMenu','htmega-menu' ); ?></strong>
	                </label>
	            </p>

	            <p class="field-template description description-wide hidn">
	                <label for="edit-menu-item-template-<?php echo esc_attr( $item_id ); ?>">
	                	<?php esc_html_e( 'Select Your template Layout ', 'htmega-menu' ); ?>
	                    <select id="edit-menu-item-template-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-template" name="menu-item-template[<?php echo esc_attr( $item_id ); ?>]">
	                    	<?php
	                    		foreach ( htmega_elementor_template() as $keyid => $title ) {
	                    			?>
	                    				<option value="<?php echo $keyid; ?>" <?php selected( $item->template, $keyid ); ?>><?php echo esc_html__( $title, 'htmega-menu' ); ?></option>
	                    			<?php
	                    		}
	                    	?>
						</select>
	                </label>
	            </p>

	            <p class="field-menuwidth description description-wide hidn">
	                <label for="edit-menu-item-menuwidth-<?php echo esc_attr( $item_id ); ?>">
	                	<?php esc_html_e( 'Menu Width ', 'htmega-menu' ); ?><br/>
	                	<input type="text" id="edit-menu-item-menuwidth-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-menuwidth[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menuwidth ); ?>" />
	                </label>
	                <span><?php esc_html_e( 'put pixel value here. such as ( 250 )', 'htmega-menu' ); ?></span>
	            </p>

	            <p class="field-ficon description description-thin htmegamenu-pro">
	                <label for="edit-menu-item-ficon-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'Menu Icon','htmega-menu' ); ?><span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-menu'); ?></span><br />
	                    <input type="text" id="edit-menu-item-ficon-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom edit-menu-item-menuicon" name="menu-item-ficon[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->ficon ); ?>" />
	                </label>
	            </p>

	            <p class="field-ficoncolor description description-thin htmegamenu-pro">
	            	<?php esc_html_e( 'Menu Icon Color','htmega-menu' ); ?><span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-menu'); ?></span><br/>
	                <label for="edit-menu-item-ficoncolor-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="text" id="edit-menu-item-ficoncolor-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom htmega-color-picker-field" name="menu-item-ficoncolor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->ficoncolor ); ?>" />
	                </label>
	            </p>

	            <p class="field-menutag description description-wide hidn htmegamenu-pro">
	                <label for="edit-menu-item-menutag-<?php echo esc_attr( $item_id ); ?>">
	                	<?php esc_html_e( 'Menu Badge ', 'htmega-menu' ); ?><span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-menu'); ?></span><br/>

	                	<input type="text" id="edit-menu-item-menutag-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-menutag[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menutag ); ?>" />

	                </label>
	            </p>

	            <p class="field-menutagcolor description description-thin htmegamenu-pro">
	            	<?php esc_html_e( 'Badge Color','htmega-menu' ); ?><span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-menu'); ?></span><br/>
	                <label for="edit-menu-item-menutagcolor-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="text" id="edit-menu-item-menutagcolor-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom htmega-color-picker-field" name="menu-item-menutagcolor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menutagcolor ); ?>" />
	                </label>
	            </p>

	            <p class="field-menutagbgcolor description description-thin htmegamenu-pro">
	            	<?php esc_html_e( 'Badge Background Color','htmega-menu' ); ?><span class="htmenu-pro-badge"><?php esc_html_e('( Pro )','htmega-menu'); ?></span><br/>
	                <label for="edit-menu-item-menutagbgcolor-<?php echo esc_attr( $item_id ); ?>">
	                    <input type="text" id="edit-menu-item-menutagbgcolor-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom htmega-color-picker-field" name="menu-item-menutagbgcolor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menutagbgcolor ); ?>" />
	                </label>
	            </p>

	            <p class="field-menuposition description description-wide hidn">
	                <label for="edit-menu-item-menuposition-<?php echo esc_attr( $item_id ); ?>">
	                    <?php esc_html_e( 'SubMenu Position','htmega-menu' ); ?>
	                    <input type="text" id="edit-menu-item-menuposition-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-menuposition" name="menu-item-menuposition[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menuposition ); ?>" />
	                </label><br>
	                <span><?php esc_html_e( 'put pixel value here. such as ( -16 ). whatever you want. this value can be + or -', 'htmega-menu' ); ?></span>
	            </p>

	            <?php /* New fields insertion ends here */ ?>

	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s','htmega-menu'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr( $item_id ); ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php esc_html_e('Remove','htmega-menu'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr( $item_id ); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e('Cancel','htmega-menu'); ?></a>
	            </div>
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>" />

	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />

	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />

	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />

	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />

	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />

	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    $output .= ob_get_clean();
	    }
}