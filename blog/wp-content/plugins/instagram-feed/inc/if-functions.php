<?php
/**
 * Contains functions used primarily on the frontend but some also used in the
 * admin area.
 *
 * - Function for the shortcode that displays the feed
 * - AJAX call for pagination
 * - All AJAX calls for image resizing triggering
 * - Clearing page caches for caching plugins
 * - Starting cron caching
 * - Getting settings from the database
 * - Displaying frontend errors
 * - Enqueueing CSS and JS files for the feed
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'widget_text', 'do_shortcode' );

/**
 * The main function the creates the feed from a shortcode.
 * Can be safely added directly to templates using
 * 'echo do_shortcode( "[instagram-feed]" );'
 */
add_shortcode('instagram-feed', 'display_instagram');
function display_instagram( $atts = array() ) {

	$database_settings = sbi_get_database_settings();

	if ( $database_settings['sb_instagram_ajax_theme'] !== 'on' && $database_settings['sb_instagram_ajax_theme'] !== 'true' ) {
		wp_enqueue_script( 'sb_instagram_scripts' );
	}

	if ( $database_settings['enqueue_css_in_shortcode'] === 'on' || $database_settings['enqueue_css_in_shortcode'] === 'true' ) {
		wp_enqueue_style( 'sb_instagram_styles' );
	}
	$instagram_feed_settings = new SB_Instagram_Settings( $atts, $database_settings );

	if ( empty( $database_settings['connected_accounts'] ) && empty( $atts['accesstoken'] ) ) {
		$style = current_user_can( 'manage_instagram_feed_options' ) ? ' style="display: block;"' : '';
		ob_start(); ?>
        <div id="sbi_mod_error" <?php echo $style; ?>>
            <span><?php _e('This error message is only visible to WordPress admins', 'instagram-feed' ); ?></span><br />
            <p><b><?php _e( 'Error: No connected account.', 'instagram-feed' ); ?></b>
            <p><?php _e( 'Please go to the Instagram Feed settings page to connect an account.', 'instagram-feed' ); ?></p>
        </div>
		<?php
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();
	$settings = $instagram_feed_settings->get_settings();
	$feed_type_and_terms = $instagram_feed_settings->get_feed_type_and_terms();

	$instagram_feed = new SB_Instagram_Feed( $transient_name );

	if ( $database_settings['sbi_caching_type'] === 'background' ) {
		$instagram_feed->add_report( 'background caching used' );
		if ( $instagram_feed->regular_cache_exists() ) {
			$instagram_feed->add_report( 'setting posts from cache' );
			$instagram_feed->set_post_data_from_cache();
		}

		if ( $instagram_feed->need_to_start_cron_job() ) {
			$instagram_feed->add_report( 'setting up feed for cron cache' );
			$to_cache = array(
                'atts' => $atts,
                'last_requested' => time(),
		    );

			$instagram_feed->set_cron_cache( $to_cache, $instagram_feed_settings->get_cache_time_in_seconds() );

			SB_Instagram_Cron_Updater::do_single_feed_cron_update( $instagram_feed_settings, $to_cache, $atts, false );

			$instagram_feed->set_post_data_from_cache();

		} elseif ( $instagram_feed->should_update_last_requested() ) {
			$instagram_feed->add_report( 'updating last requested' );
			$to_cache = array(
				'last_requested' => time(),
			);

			$instagram_feed->set_cron_cache( $to_cache, $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

    } elseif ( $instagram_feed->regular_cache_exists() ) {
		$instagram_feed->add_report( 'page load caching used and regular cache exists' );
		$instagram_feed->set_post_data_from_cache();

        if ( $instagram_feed->need_posts( $settings['num'] ) && $instagram_feed->can_get_more_posts() ) {
	        while ( $instagram_feed->need_posts( $settings['num'] ) && $instagram_feed->can_get_more_posts() ) {
				$instagram_feed->add_remote_posts( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
			}
			$instagram_feed->cache_feed_data( $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

	} else {
		$instagram_feed->add_report( 'no feed cache found' );

		while ( $instagram_feed->need_posts( $settings['num'] ) && $instagram_feed->can_get_more_posts() ) {
			$instagram_feed->add_remote_posts( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
		}

		if ( ! $instagram_feed->should_use_backup() ) {
			$instagram_feed->cache_feed_data( $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

	}

	if ( $instagram_feed->should_use_backup() ) {
		$instagram_feed->add_report( 'trying to use backup' );
		$instagram_feed->maybe_set_post_data_from_backup();
		$instagram_feed->maybe_set_header_data_from_backup();
	}


	// if need a header
	if ( $instagram_feed->need_header( $settings, $feed_type_and_terms ) ) {
		if ( $instagram_feed->should_use_backup() && $settings['minnum'] > 0 ) {
			$instagram_feed->add_report( 'trying to set header from backup' );
			$header_cache_success = $instagram_feed->maybe_set_header_data_from_backup();
		} elseif ( $database_settings['sbi_caching_type'] === 'background' ) {
			$instagram_feed->add_report( 'background header caching used' );
			$instagram_feed->set_header_data_from_cache();
		} elseif ( $instagram_feed->regular_header_cache_exists() ) {
			// set_post_data_from_cache
			$instagram_feed->add_report( 'page load caching used and regular header cache exists' );
			$instagram_feed->set_header_data_from_cache();
		} else {
			$instagram_feed->add_report( 'no header cache exists' );
			$instagram_feed->set_remote_header_data( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
			$instagram_feed->cache_header_data( $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}
	} else {
		$instagram_feed->add_report( 'no header needed' );
	}

	if ( $settings['resizeprocess'] === 'page' ) {
		$instagram_feed->add_report( 'resizing images for post set' );
		$post_data = $instagram_feed->get_post_data();
		$post_data = array_slice( $post_data, 0, $settings['num'] );

		$post_set = new SB_Instagram_Post_Set( $post_data, $transient_name );

		$post_set->maybe_save_update_and_resize_images_for_posts();
    }

	if ( $settings['disable_js_image_loading'] || $settings['imageres'] !== 'auto' ) {
		global $sb_instagram_posts_manager;
		$post_data = $instagram_feed->get_post_data();

		if ( ! $sb_instagram_posts_manager->image_resizing_disabled() ) {
			$image_ids = array();
			foreach ( $post_data as $post ) {
				$image_ids[] = SB_Instagram_Parse::get_post_id( $post );
			}
			$resized_images = SB_Instagram_Feed::get_resized_images_source_set( $image_ids, 0, $transient_name );

			$instagram_feed->set_resized_images( $resized_images );
		}
	}

	return $instagram_feed->get_the_feed_html( $settings, $atts, $instagram_feed_settings->get_feed_type_and_terms(), $instagram_feed_settings->get_connected_accounts_in_feed() );
}

/**
 * For efficiency, local versions of image files available for the images actually displayed on the page
 * are added at the end of the feed.
 *
 * @param object $instagram_feed
 * @param string $feed_id
 */
function sbi_add_resized_image_data( $instagram_feed, $feed_id ) {
	global $sb_instagram_posts_manager;

	if ( ! $sb_instagram_posts_manager->image_resizing_disabled() ) {
		SB_Instagram_Feed::update_last_requested( $instagram_feed->get_image_ids_post_set() );
	}
	?>
    <span class="sbi_resized_image_data" data-feed-id="<?php echo esc_attr( $feed_id ); ?>" data-resized="<?php echo esc_attr( wp_json_encode( SB_Instagram_Feed::get_resized_images_source_set( $instagram_feed->get_image_ids_post_set(), 0, $feed_id ) ) ); ?>">
	</span>
	<?php
}
add_action( 'sbi_before_feed_end', 'sbi_add_resized_image_data', 10, 2 );

/**
 * Called after the load more button is clicked using admin-ajax.php.
 * Resembles "display_instagram"
 */
function sbi_get_next_post_set() {
	if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sbi' ) === false ) {
		die( 'invalid feed ID');
	}

	$feed_id = sanitize_text_field( $_POST['feed_id'] );
	$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
	if ( is_array( $atts_raw ) ) {
		array_map( 'sanitize_text_field', $atts_raw );
	} else {
		$atts_raw = array();
	}
	$atts = $atts_raw; // now sanitized

	$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;

	$database_settings = sbi_get_database_settings();
	$instagram_feed_settings = new SB_Instagram_Settings( $atts, $database_settings );

	if ( empty( $database_settings['connected_accounts'] ) && empty( $atts['accesstoken'] ) ) {
		die( 'error no connected account' );
	}

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();

	if ( $transient_name !== $feed_id ) {
		die( 'id does not match' );
	}

	$settings = $instagram_feed_settings->get_settings();
	$current_image_resolution = isset( $_POST['current_resolution'] ) ? (int)$_POST['current_resolution'] : 640;

	switch ( $current_image_resolution ) {
		case 150 :
			$settings['imageres'] = 'thumb';
			break;
		case 320 :
			$settings['imageres'] = 'medium';
			break;
		default :
			$settings['imageres'] = 'full';
	}

	$feed_type_and_terms = $instagram_feed_settings->get_feed_type_and_terms();

	$instagram_feed = new SB_Instagram_Feed( $transient_name );
	if ( $database_settings['sbi_caching_type'] === 'background' ) {
		$instagram_feed->add_report( 'background caching used' );
		if ( $instagram_feed->regular_cache_exists() ) {
			$instagram_feed->add_report( 'setting posts from cache' );
			$instagram_feed->set_post_data_from_cache();
		}

        if ( $instagram_feed->need_posts( $settings['num'], $offset ) && $instagram_feed->can_get_more_posts() ) {
            while ( $instagram_feed->need_posts( $settings['num'], $offset ) && $instagram_feed->can_get_more_posts() ) {
                $instagram_feed->add_remote_posts( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
            }

	        if ( $instagram_feed->need_to_start_cron_job() ) {
		        $instagram_feed->add_report( 'needed to start cron job' );
		        $to_cache = array(
			        'atts' => $atts,
			        'last_requested' => time(),
		        );

		        $instagram_feed->set_cron_cache( $to_cache, $instagram_feed_settings->get_cache_time_in_seconds() );

	        } else {
		        $instagram_feed->add_report( 'updating last requested and adding to cache' );
		        $to_cache = array(
			        'last_requested' => time(),
		        );

		        $instagram_feed->set_cron_cache( $to_cache, $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
	        }
        }

	} elseif ( $instagram_feed->regular_cache_exists() ) {
		$instagram_feed->add_report( 'regular cache exists' );
		$instagram_feed->set_post_data_from_cache();

		if ( $instagram_feed->need_posts( $settings['num'], $offset ) && $instagram_feed->can_get_more_posts() ) {
			while ( $instagram_feed->need_posts( $settings['num'], $offset ) && $instagram_feed->can_get_more_posts() ) {
				$instagram_feed->add_remote_posts( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
			}

			$instagram_feed->add_report( 'adding to cache' );
			$instagram_feed->cache_feed_data( $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}


	} else {
		$instagram_feed->add_report( 'no feed cache found' );

		while ( $instagram_feed->need_posts( $settings['num'], $offset ) && $instagram_feed->can_get_more_posts() ) {
			$instagram_feed->add_remote_posts( $settings, $feed_type_and_terms, $instagram_feed_settings->get_connected_accounts_in_feed() );
		}

		if ( $instagram_feed->should_use_backup() ) {
			$instagram_feed->add_report( 'trying to use a backup cache' );
			$instagram_feed->maybe_set_post_data_from_backup();
		} else {
			$instagram_feed->add_report( 'transient gone, adding to cache' );
			$instagram_feed->cache_feed_data( $instagram_feed_settings->get_cache_time_in_seconds(), $settings['backup_cache_enabled'] );
		}

	}

	if ( $settings['disable_js_image_loading'] || $settings['imageres'] !== 'auto' ) {
		global $sb_instagram_posts_manager;
		$post_data = array_slice( $instagram_feed->get_post_data(), $offset, $settings['minnum'] );

		if ( ! $sb_instagram_posts_manager->image_resizing_disabled() ) {
			$image_ids = array();
			foreach ( $post_data as $post ) {
				$image_ids[] = SB_Instagram_Parse::get_post_id( $post );
			}
			$resized_images = SB_Instagram_Feed::get_resized_images_source_set( $image_ids, 0, $feed_id );

			$instagram_feed->set_resized_images( $resized_images );
		}
	}

	$feed_status = array( 'shouldPaginate' => $instagram_feed->should_use_pagination( $settings, $offset ) );

	$return = array(
		'html' => $instagram_feed->get_the_items_html( $settings, $offset, $instagram_feed_settings->get_feed_type_and_terms(), $instagram_feed_settings->get_connected_accounts_in_feed() ),
		'feedStatus' => $feed_status,
		'report' => $instagram_feed->get_report(),
        'resizedImages' => SB_Instagram_Feed::get_resized_images_source_set( $instagram_feed->get_image_ids_post_set(), 0, $feed_id )
	);

	echo wp_json_encode( $return );

	global $sb_instagram_posts_manager;

	$sb_instagram_posts_manager->update_successful_ajax_test();

	die();
}
add_action( 'wp_ajax_sbi_load_more_clicked', 'sbi_get_next_post_set' );
add_action( 'wp_ajax_nopriv_sbi_load_more_clicked', 'sbi_get_next_post_set' );

/**
 * Posts that need resized images are processed after being sent to the server
 * using AJAX
 *
 * @return string
 */
function sbi_process_submitted_resize_ids() {
	if ( ! isset( $_POST['feed_id'] ) || strpos( $_POST['feed_id'], 'sbi' ) === false ) {
		die( 'invalid feed ID');
	}

	$feed_id = sanitize_text_field( $_POST['feed_id'] );
	$images_need_resizing_raw = isset( $_POST['needs_resizing'] ) ? $_POST['needs_resizing'] : array();
	if ( is_array( $images_need_resizing_raw ) ) {
		array_map( 'sanitize_text_field', $images_need_resizing_raw );
	} else {
		$images_need_resizing_raw = array();
	}
	$images_need_resizing = $images_need_resizing_raw;

	$atts_raw = isset( $_POST['atts'] ) ? json_decode( stripslashes( $_POST['atts'] ), true ) : array();
	if ( is_array( $atts_raw ) ) {
		array_map( 'sanitize_text_field', $atts_raw );
	} else {
		$atts_raw = array();
	}
	$atts = $atts_raw; // now sanitized

	$offset = isset( $_POST['offset'] ) ? (int)$_POST['offset'] : 0;

	$database_settings = sbi_get_database_settings();
	$instagram_feed_settings = new SB_Instagram_Settings( $atts, $database_settings );

	if ( empty( $database_settings['connected_accounts'] ) && empty( $atts['accesstoken'] ) ) {
		return '<div class="sb_instagram_error"><p>' . __( 'Please connect an account on the Instagram Feed plugin Settings page.', 'instagram-feed' ) . '</p></div>';
	}

	$instagram_feed_settings->set_feed_type_and_terms();
	$instagram_feed_settings->set_transient_name();
	$transient_name = $instagram_feed_settings->get_transient_name();

	if ( $transient_name !== $feed_id ) {
		die( 'id does not match' );
	}

	sbi_resize_posts_by_id( $images_need_resizing, $transient_name, $instagram_feed_settings->get_settings() );

	global $sb_instagram_posts_manager;

	$sb_instagram_posts_manager->update_successful_ajax_test();

	die();
}
add_action( 'wp_ajax_sbi_resized_images_submit', 'sbi_process_submitted_resize_ids' );
add_action( 'wp_ajax_nopriv_sbi_resized_images_submit', 'sbi_process_submitted_resize_ids' );

/**
 * Used for testing if admin-ajax.php can be successfully reached using
 * AJAX in the frontend
 */
function sbi_update_successful_ajax() {

    global $sb_instagram_posts_manager;

	delete_transient( 'sb_instagram_doing_ajax_test' );

    $sb_instagram_posts_manager->update_successful_ajax_test();

	die();
}
add_action( 'wp_ajax_sbi_on_ajax_test_trigger', 'sbi_update_successful_ajax' );
add_action( 'wp_ajax_nopriv_sbi_on_ajax_test_trigger', 'sbi_update_successful_ajax' );

/**
 * Outputs an organized error report for the front end.
 * This hooks into the end of the feed before the closing div
 *
 * @param object $instagram_feed
 * @param string $feed_id
 */
function sbi_error_report( $instagram_feed, $feed_id ) {
    global $sb_instagram_posts_manager;

    $style = current_user_can( 'manage_instagram_feed_options' ) ? ' style="display: block;"' : '';

	$error_messages = $sb_instagram_posts_manager->get_frontend_errors();
    if ( ! empty( $error_messages ) ) {?>
        <div id="sbi_mod_error"<?php echo $style; ?>>
            <span><?php _e('This error message is only visible to WordPress admins', 'instagram-feed' ); ?></span><br />
        <?php foreach ( $error_messages as $error_message ) {
            echo $error_message;
        } ?>
        </div>
        <?php
    }

	$sb_instagram_posts_manager->reset_frontend_errors();
}
add_action( 'sbi_before_feed_end', 'sbi_error_report', 10, 2 );

/**
 * Debug report added at the end of the feed when sbi_debug query arg is added to a page
 * that has the feed on it.
 *
 * @param object $instagram_feed
 * @param string $feed_id
 */
function sbi_debug_report( $instagram_feed, $feed_id ) {

    if ( ! isset( $_GET['sbi_debug'] ) ) {
        return;
    }

    ?>
    <p>Status</p>
    <ul>
        <li>Time: <?php echo date( "Y-m-d H:i:s", time() ); ?></li>
    <?php foreach ( $instagram_feed->get_report() as $item ) : ?>
        <li><?php echo esc_html( $item ); ?></li>
    <?php endforeach; ?>

	</ul>

    <?php
	$database_settings = sbi_get_database_settings();

	$public_settings_keys = SB_Instagram_Settings::get_public_db_settings_keys();
    ?>
    <p>Settings</p>
    <ul>
        <?php foreach ( $public_settings_keys as $key ) : if ( isset( $database_settings[ $key ] ) ) : ?>
        <li>
            <small><?php echo esc_html( $key ); ?>:</small>
        <?php if ( ! is_array( $database_settings[ $key ] ) ) :
                echo $database_settings[ $key ];
        else : ?>
<pre>
<?php var_export( $database_settings[ $key ] ); ?>
</pre>
        <?php endif; ?>
        </li>

        <?php endif; endforeach; ?>
    </ul>
    <?php
}
add_action( 'sbi_before_feed_end', 'sbi_debug_report', 11, 2 );

/**
 * Uses post IDs to process images that may need resizing
 *
 * @param array $ids
 * @param string $transient_name
 * @param array $settings
 * @param int $offset
 */
function sbi_resize_posts_by_id( $ids, $transient_name, $settings, $offset = 0 ) {
	$instagram_feed = new SB_Instagram_Feed( $transient_name );

	if ( $instagram_feed->regular_cache_exists() ) {
		// set_post_data_from_cache
		$instagram_feed->set_post_data_from_cache();

		$cached_post_data = $instagram_feed->get_post_data();

		$num_ids = count( $ids );
		$found_posts = array();
		$i = 0;
		while ( count( $found_posts) < $num_ids && isset( $cached_post_data[ $i ] ) ) {
		    if ( ! empty( $cached_post_data[ $i ]['id'] ) && in_array( $cached_post_data[ $i ]['id'], $ids, true ) ) {
			    $found_posts[] = $cached_post_data[ $i ];
            }
		    $i++;
        }

		$fill_in_timestamp = date( 'Y-m-d H:i:s', time() + 120 );

		if ( $offset !== 0 ) {
			$fill_in_timestamp = date( 'Y-m-d H:i:s', strtotime( $instagram_feed->get_earliest_time_stamp() ) - 120 );
		}

		$post_set = new SB_Instagram_Post_Set( $found_posts, $transient_name, $fill_in_timestamp );

		$post_set->maybe_save_update_and_resize_images_for_posts();
	}
}

/**
 * Get the settings in the database with defaults
 *
 * @return array
 */
function sbi_get_database_settings() {
	$defaults = array(
		'sb_instagram_at'                   => '',
		'sb_instagram_user_id'              => '',
		'sb_instagram_preserve_settings'    => '',
		'sb_instagram_ajax_theme'           => false,
		'sb_instagram_disable_resize'       => false,
		'sb_instagram_cache_time'           => 1,
		'sb_instagram_cache_time_unit'      => 'hours',
		'sbi_caching_type'                  => 'page',
		'sbi_cache_cron_interval'           => '12hours',
		'sbi_cache_cron_time'               => '1',
		'sbi_cache_cron_am_pm'              => 'am',
		'sb_instagram_width'                => '100',
		'sb_instagram_width_unit'           => '%',
		'sb_instagram_feed_width_resp'      => false,
		'sb_instagram_height'               => '',
		'sb_instagram_num'                  => '20',
		'sb_instagram_height_unit'          => '',
		'sb_instagram_cols'                 => '4',
		'sb_instagram_disable_mobile'       => false,
		'sb_instagram_image_padding'        => '5',
		'sb_instagram_image_padding_unit'   => 'px',
		'sb_instagram_sort'                 => 'none',
		'sb_instagram_background'           => '',
		'sb_instagram_show_btn'             => true,
		'sb_instagram_btn_background'       => '',
		'sb_instagram_btn_text_color'       => '',
		'sb_instagram_btn_text'             => __( 'Load More...', 'instagram-feed' ),
		'sb_instagram_image_res'            => 'auto',
		//Header
		'sb_instagram_show_header'          => true,
		'sb_instagram_header_size'  => 'small',
		'sb_instagram_header_color'         => '',
		//Follow button
		'sb_instagram_show_follow_btn'      => true,
		'sb_instagram_folow_btn_background' => '',
		'sb_instagram_follow_btn_text_color' => '',
		'sb_instagram_follow_btn_text'      => __( 'Follow on Instagram', 'instagram-feed' ),
		//Misc
		'sb_instagram_custom_css'           => '',
		'sb_instagram_custom_js'            => '',
		'sb_instagram_cron'                 => 'no',
		'sb_instagram_backup' => true,
		'sb_ajax_initial'    => false,
		'enqueue_css_in_shortcode' => false,
		'sb_instagram_disable_mob_swipe' => false,
		'sbi_font_method' => 'svg',
		'sb_instagram_disable_awesome'      => false
	);
	$sbi_settings = get_option( 'sb_instagram_settings', array() );

	return array_merge( $defaults, $sbi_settings );
}

/**
 * May include support for templates in theme folders in the future
 *
 * @since 2.1 custom templates supported
 */
function sbi_get_feed_template_part( $part, $settings = array() ) {
	$file = '';

	$using_custom_templates_in_theme = apply_filters( 'sbi_use_theme_templates', $settings['customtemplates'] );
	$generic_path = trailingslashit( SBI_PLUGIN_DIR ) . 'templates/';

	if ( $using_custom_templates_in_theme ) {
		$custom_header_template = locate_template( 'sbi/header.php', false, false );
		$custom_item_template = locate_template( 'sbi/item.php', false, false );
		$custom_footer_template = locate_template( 'sbi/footer.php', false, false );
		$custom_feed_template = locate_template( 'sbi/feed.php', false, false );
	} else {
		$custom_header_template = false;
		$custom_item_template = false;
		$custom_footer_template = false;
		$custom_feed_template = false;
	}

	if ( $part === 'header' ) {
        if ( $custom_header_template ) {
            $file = $custom_header_template;
        } else {
            $file = $generic_path . 'header.php';
        }
	} elseif ( $part === 'item' ) {
		if ( $custom_item_template ) {
			$file = $custom_item_template;
		} else {
			$file = $generic_path . 'item.php';
		}
	} elseif ( $part === 'footer' ) {
		if ( $custom_footer_template ) {
			$file = $custom_footer_template;
		} else {
			$file = $generic_path . 'footer.php';
		}
	} elseif ( $part === 'feed' ) {
		if ( $custom_feed_template ) {
			$file = $custom_feed_template;
		} else {
			$file = $generic_path . 'feed.php';
		}
	}

	return $file;
}

/**
 * Triggered by a cron event to update feeds
 */
function sbi_cron_updater() {
    $sbi_settings = sbi_get_database_settings();

    if ( $sbi_settings['sbi_caching_type'] === 'background' ) {
        $cron_updater = new SB_Instagram_Cron_Updater();

        $cron_updater->do_feed_updates();
    }

}
add_action( 'sbi_feed_update', 'sbi_cron_updater' );

/**
 * @param $maybe_dirty
 *
 * @return string
 */
function sbi_maybe_clean( $maybe_dirty ) {
	if ( substr_count ( $maybe_dirty , '.' ) < 3 ) {
		return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
	}

	$parts = explode( '.', trim( $maybe_dirty ) );
	$last_part = $parts[2] . $parts[3];
	$cleaned = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );

	return $cleaned;
}

/**
 * @param $whole
 *
 * @return string
 */
function sbi_get_parts( $whole ) {
	if ( substr_count ( $whole , '.' ) !== 2 ) {
		return $whole;
	}

	$parts = explode( '.', trim( $whole ) );
	$return = $parts[0] . '.' . base64_encode( $parts[1] ). '.' . base64_encode( $parts[2] );

	return substr( $return, 0, 40 ) . '.' . substr( $return, 40, 100 );
}

/**
 * @param $a
 * @param $b
 *
 * @return false|int
 */
function sbi_date_sort( $a, $b ) {
	$time_stamp_a = SB_Instagram_Parse::get_timestamp( $a );
	$time_stamp_b = SB_Instagram_Parse::get_timestamp( $b );

	if ( isset( $time_stamp_a ) ) {
		return $time_stamp_b - $time_stamp_a;
	} else {
		return rand ( -1, 1 );
	}
}

function sbi_code_check( $code ) {
	if ( strpos( $code, '634hgdf83hjdj2') !== false ) {
		return true;
	}
	return false;
}

function sbi_fixer( $code ) {
	if ( strpos( $code, '634hgdf83hjdj2') !== false ) {
		return $code;
	} else {
		return substr_replace( $code , '634hgdf83hjdj2', 15, 0 );
	}
}

/**
 * @param $a
 * @param $b
 *
 * @return false|int
 */
function sbi_rand_sort( $a, $b ) {
    return rand ( -1, 1 );
}

/**
 * @return string
 *
 * @since 2.1.1
 */
function sbi_get_resized_uploads_url() {
	$upload = wp_upload_dir();

	return trailingslashit( $upload['baseurl'] ) . trailingslashit( SBI_UPLOADS_NAME );
}

/**
 * Converts a hex code to RGB so opacity can be
 * applied more easily
 *
 * @param $hex
 *
 * @return string
 */
function sbi_hextorgb( $hex ) {
	// allows someone to use rgb in shortcode
	if ( strpos( $hex, ',' ) !== false ) {
		return $hex;
	}

	$hex = str_replace( '#', '', $hex );

	if ( strlen( $hex ) === 3 ) {
		$r = hexdec( substr( $hex,0,1 ).substr( $hex,0,1 ) );
		$g = hexdec( substr( $hex,1,1 ).substr( $hex,1,1 ) );
		$b = hexdec( substr( $hex,2,1 ).substr( $hex,2,1 ) );
	} else {
		$r = hexdec( substr( $hex,0,2 ) );
		$g = hexdec( substr( $hex,2,2 ) );
		$b = hexdec( substr( $hex,4,2 ) );
	}
	$rgb = array( $r, $g, $b );

	return implode( ',', $rgb ); // returns the rgb values separated by commas
}


/**
 * Added to workaround MySQL tables that don't use utf8mb4 character sets
 *
 * @since 2.2.1/5.3.1
 */
function sbi_sanitize_emoji( $string ) {
	$encoded = array(
		'jsonencoded' => $string
	);
	return wp_json_encode( $encoded );
}

/**
 * Added to workaround MySQL tables that don't use utf8mb4 character sets
 *
 * @since 2.2.1/5.3.1
 */
function sbi_decode_emoji( $string ) {
	if ( strpos( $string, '{"' ) !== false ) {
		$decoded = json_decode( $string, true );
		return $decoded['jsonencoded'];
	}
	return $string;
}

/**
 * @return int
 */
function sbi_get_utc_offset() {
	return get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
}

function sbi_get_current_timestamp() {
	$current_time = time();

	//$current_time = strtotime( 'November 25, 2022' ) + 1;

	return $current_time;
}

function sbi_is_after_deprecation_deadline() {
	$current_time = sbi_get_current_timestamp();

	return $current_time > strtotime( 'March 3, 2020' );
}

/**
 * Used to clear caches when transients aren't working
 * properly
 */
function sb_instagram_cron_clear_cache() {
	//Delete all transients
	global $wpdb;
	$table_name = $wpdb->prefix . "options";
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_sbi\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_sbi\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_&sbi\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_&sbi\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_\$sbi\_%')
        " );
	$wpdb->query( "
        DELETE
        FROM $table_name
        WHERE `option_name` LIKE ('%\_transient\_timeout\_\$sbi\_%')
        " );

	sb_instagram_clear_page_caches();
}

/**
 * When certain events occur, page caches need to
 * clear or errors occur or changes will not be seen
 */
function sb_instagram_clear_page_caches() {
	if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ){
		/* Clear WP fastest cache*/
		$GLOBALS['wp_fastest_cache']->deleteCache();
	}

	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}

	if ( class_exists('W3_Plugin_TotalCacheAdmin') ) {
		$plugin_totalcacheadmin = & w3_instance('W3_Plugin_TotalCacheAdmin');

		$plugin_totalcacheadmin->flush_all();
	}

	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}

	if ( class_exists( 'autoptimizeCache' ) ) {
		/* Clear autoptimize */
		autoptimizeCache::clearall();
	}

	// Litespeed Cache
	if ( method_exists( 'LiteSpeed_Cache_API', 'purge' ) ) {
		LiteSpeed_Cache_API::purge( 'esi.instagram-feed' );
    }
}

/**
 * Makes the JavaScript file available and enqueues the stylesheet
 * for the plugin
 */
function sb_instagram_scripts_enqueue() {
	//Register the script to make it available

	//Options to pass to JS file
	$sb_instagram_settings = get_option( 'sb_instagram_settings' );

	$js_file = 'js/sb-instagram-2-2.min.js';
	if ( isset( $_GET['sbi_debug'] ) ) {
		$js_file = 'js/sb-instagram.js';
	}

	if ( isset( $sb_instagram_settings['enqueue_js_in_head'] ) && $sb_instagram_settings['enqueue_js_in_head'] ) {
		wp_enqueue_script( 'sb_instagram_scripts', trailingslashit( SBI_PLUGIN_URL ) . $js_file, array('jquery'), SBIVER, false );
	} else {
		wp_register_script( 'sb_instagram_scripts', trailingslashit( SBI_PLUGIN_URL ) . $js_file, array('jquery'), SBIVER, true );
	}

	if ( isset( $sb_instagram_settings['enqueue_css_in_shortcode'] ) && $sb_instagram_settings['enqueue_css_in_shortcode'] ) {
		wp_register_style( 'sb_instagram_styles', trailingslashit( SBI_PLUGIN_URL ) . 'css/sb-instagram-2-2.min.css', array(), SBIVER );
	} else {
		wp_enqueue_style( 'sb_instagram_styles', trailingslashit( SBI_PLUGIN_URL ) . 'css/sb-instagram-2-2.min.css', array(), SBIVER );
	}

	$font_method = isset( $sb_instagram_settings['sbi_font_method'] ) ? $sb_instagram_settings['sbi_font_method'] : 'svg';

	if ( isset( $sb_instagram_settings['sb_instagram_disable_awesome'] ) ) {
		$disable_font_awesome = isset( $sb_instagram_settings['sb_instagram_disable_awesome'] ) ? $sb_instagram_settings['sb_instagram_disable_awesome'] === 'on' : false;
	} else {
		$disable_font_awesome = isset( $sb_instagram_settings['sb_instagram_disable_font'] ) ? $sb_instagram_settings['sb_instagram_disable_font'] === 'on' : false;
	}

	if ( $font_method === 'fontfile' && ! $disable_font_awesome ) {
		wp_enqueue_style( 'sb-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	}
	
	$data = array(
		'font_method' => $font_method,
		'resized_url' => sbi_get_resized_uploads_url(),
		'placeholder' => trailingslashit( SBI_PLUGIN_URL ) . 'img/placeholder.png'
    );
	//Pass option to JS file
	wp_localize_script('sb_instagram_scripts', 'sb_instagram_js_options', $data );

	if ( SB_Instagram_Blocks::is_gb_editor() ) {
		wp_enqueue_style( 'sb_instagram_styles' );
		wp_enqueue_script( 'sb_instagram_scripts' );
	}
}
add_action( 'wp_enqueue_scripts', 'sb_instagram_scripts_enqueue', 2 );

/**
 * Adds the ajax url and custom JavaScript to the page
 */
function sb_instagram_custom_js() {
	$options = get_option('sb_instagram_settings');
	isset($options[ 'sb_instagram_custom_js' ]) ? $sb_instagram_custom_js = trim($options['sb_instagram_custom_js']) : $sb_instagram_custom_js = '';

	echo '<!-- Instagram Feed JS -->';
	echo "\r\n";
	echo '<script type="text/javascript">';
	echo "\r\n";
	echo 'var sbiajaxurl = "' . admin_url('admin-ajax.php') . '";';

	if ( !empty( $sb_instagram_custom_js ) ) {
		echo "\r\n";
		echo "jQuery( document ).ready(function($) {";
		echo "\r\n";
		echo "window.sbi_custom_js = function(){";
		echo "\r\n";
		echo stripslashes($sb_instagram_custom_js);
		echo "\r\n";
		echo "}";
		echo "\r\n";
		echo "});";
    }

	echo "\r\n";
	echo '</script>';
	echo "\r\n";
}
add_action( 'wp_footer', 'sb_instagram_custom_js' );

//Custom CSS
add_action( 'wp_head', 'sb_instagram_custom_css' );
function sb_instagram_custom_css() {
	$options = get_option('sb_instagram_settings');

	isset($options[ 'sb_instagram_custom_css' ]) ? $sb_instagram_custom_css = trim($options['sb_instagram_custom_css']) : $sb_instagram_custom_css = '';

	//Show CSS if an admin (so can see Hide Photos link), if including Custom CSS or if hiding some photos
	( current_user_can( 'edit_posts' ) || !empty($sb_instagram_custom_css) || !empty($sb_instagram_hide_photos) ) ? $sbi_show_css = true : $sbi_show_css = false;

	if( $sbi_show_css ) echo '<!-- Instagram Feed CSS -->';
	if( $sbi_show_css ) echo "\r\n";
	if( $sbi_show_css ) echo '<style type="text/css">';

	if( !empty($sb_instagram_custom_css) ){
		echo "\r\n";
		echo stripslashes($sb_instagram_custom_css);
	}

	if( current_user_can( 'edit_posts' ) ){
		echo "\r\n";
		echo "#sbi_mod_link, #sbi_mod_error{ display: block !important; width: 100%; float: left; box-sizing: border-box; }";
	}

	if( $sbi_show_css ) echo "\r\n";
	if( $sbi_show_css ) echo '</style>';
	if( $sbi_show_css ) echo "\r\n";
}

/**
 * Used to change the number of posts in the api request. Useful for filtered posts
 * or special caching situations.
 *
 * @param int $num
 * @param array $settings
 *
 * @return int
 */
function sbi_raise_num_in_request( $num, $settings ) {
    if ( $settings['sortby'] === 'random' ) {
        if ( $num > 5 ) {
	        return min( $num * 4, 100 );
        } else {
            return 20;
        }
    }
    return $num;
}
add_filter( 'sbi_num_in_request', 'sbi_raise_num_in_request', 5, 2 );
