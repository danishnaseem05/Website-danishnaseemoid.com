<?php
/**
 * Includes functions related to actions while in the admin area.
 *
 * - All AJAX related features
 * - Enqueueing of JS and CSS files
 * - Settings link on "Plugins" page
 * - Creation of local avatar image files
 * - Connecting accounts on the "Configure" tab
 * - Displaying admin notices
 * - Clearing caches
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sb_instagram_admin_style() {
	wp_register_style( 'sb_instagram_admin_css', SBI_PLUGIN_URL . 'css/sb-instagram-admin.css', array(), SBIVER );
	wp_enqueue_style( 'sb_instagram_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
	wp_enqueue_style( 'sb_instagram_admin_css' );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'sb_instagram_admin_style' );

function sb_instagram_admin_scripts() {
	wp_enqueue_script( 'sb_instagram_admin_js', SBI_PLUGIN_URL . 'js/sb-instagram-admin-2-2.js', array(), SBIVER );
	wp_localize_script( 'sb_instagram_admin_js', 'sbiA', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'sbi_nonce' => wp_create_nonce( 'sbi_nonce' )
		)
	);
	if( !wp_script_is('jquery-ui-draggable') ) {
		wp_enqueue_script(
			array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-draggable'
			)
		);
	}
	wp_enqueue_script(
		array(
			'hoverIntent',
			'wp-color-picker'
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sb_instagram_admin_scripts' );

// Add a Settings link to the plugin on the Plugins page
$sbi_plugin_file = 'instagram-feed/instagram-feed.php';
add_filter( "plugin_action_links_{$sbi_plugin_file}", 'sbi_add_settings_link', 10, 2 );

//modify the link by unshifting the array
function sbi_add_settings_link( $links, $file ) {
	$sbi_settings_link = '<a href="' . admin_url( 'admin.php?page=sb-instagram-feed' ) . '">' . __( 'Settings', 'instagram-feed' ) . '</a>';
	array_unshift( $links, $sbi_settings_link );

	return $links;
}


/**
 * Called via ajax to automatically save access token and access token secret
 * retrieved with the big blue button
 */
function sbi_auto_save_tokens() {
	$nonce = $_POST['sbi_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

    wp_cache_delete ( 'alloptions', 'options' );

    $options = sbi_get_database_settings();
    $new_access_token = isset( $_POST['access_token'] ) ? sanitize_text_field( $_POST['access_token'] ) : false;
    $split_token = $new_access_token ? explode( '.', $new_access_token ) : array();
    $new_user_id = isset( $split_token[0] ) ? $split_token[0] : '';

    $connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();
    $test_connection_data = sbi_account_data_for_token( $new_access_token );

    $connected_accounts[ $new_user_id ] = array(
        'access_token' => sbi_get_parts( $new_access_token ),
        'user_id' => $test_connection_data['id'],
        'username' => $test_connection_data['username'],
        'is_valid' => $test_connection_data['is_valid'],
        'last_checked' => $test_connection_data['last_checked'],
        'profile_picture' => $test_connection_data['profile_picture'],
    );

    if ( !$options['sb_instagram_disable_resize'] ) {
        if ( sbi_create_local_avatar( $test_connection_data['username'], $test_connection_data['profile_picture'] ) ) {
	        $connected_accounts[ $new_user_id ]['local_avatar'] = true;
        }
    } else {
	    $connected_accounts[ $new_user_id ]['local_avatar'] = false;
    }

    $options['connected_accounts'] = $connected_accounts;

    update_option( 'sb_instagram_settings', $options );

    echo wp_json_encode( $connected_accounts[ $new_user_id ] );

	die();
}
add_action( 'wp_ajax_sbi_auto_save_tokens', 'sbi_auto_save_tokens' );

function sbi_delete_local_avatar( $username ) {
	$upload = wp_upload_dir();

	$image_files = glob( trailingslashit( $upload['basedir'] ) . trailingslashit( SBI_UPLOADS_NAME ) . $username . '.jpg'  ); // get all matching images
	foreach ( $image_files as $file ) { // iterate files
		if ( is_file( $file ) ) {
			unlink( $file );
		}
	}
}

function sbi_create_local_avatar( $username, $file_name ) {
	$image_editor = wp_get_image_editor( $file_name );

	if ( ! is_wp_error( $image_editor ) ) {
		$upload = wp_upload_dir();

		$full_file_name = trailingslashit( $upload['basedir'] ) . trailingslashit( SBI_UPLOADS_NAME ) . $username  . '.jpg';

		$saved_image = $image_editor->save( $full_file_name );

		if ( ! $saved_image ) {
			global $sb_instagram_posts_manager;

			$sb_instagram_posts_manager->add_error( 'image_editor_save', array(
				__( 'Error saving edited image.', 'instagram-feed' ),
				$full_file_name
			) );
		} else {
		    return true;
        }
	} else {
		global $sb_instagram_posts_manager;

		$message = __( 'Error editing image.', 'instagram-feed' );
		if ( isset( $image_editor ) && isset( $image_editor->errors ) ) {
			foreach ( $image_editor->errors as $key => $item ) {
				$message .= ' ' . $key . '- ' . $item[0] . ' |';
			}
		}

		$sb_instagram_posts_manager->add_error( 'image_editor', array( $file_name, $message ) );
	}
	return false;
}

function sbi_connect_business_accounts() {
	$nonce = $_POST['sbi_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$accounts = isset( $_POST['accounts'] ) ? json_decode( stripslashes( $_POST['accounts'] ), true ) : false;
	$options = sbi_get_database_settings();
	$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

	foreach ( $accounts as $account ) {
		$access_token = isset( $account['access_token'] ) ? $account['access_token'] : '';
		$page_access_token = isset( $account['page_access_token'] ) ? $account['page_access_token'] : '';
		$username = isset( $account['username'] ) ? $account['username'] : '';
		$name = isset( $account['name'] ) ? $account['name'] : '';
		$profile_picture = isset( $account['profile_picture_url'] ) ? $account['profile_picture_url'] : '';
		$user_id = isset( $account['id'] ) ? $account['id'] : '';
		$type = 'business';

		$connected_accounts[ $user_id ] = array(
			'access_token' => $access_token,
			'page_access_token' => $page_access_token,
			'user_id' => $user_id,
			'username' => $username,
			'is_valid' => true,
			'last_checked' => time(),
			'profile_picture' => $profile_picture,
			'name' => sbi_sanitize_emoji( $name ),
			'type' => $type,
			'use_tagged' => '1'
		);

		if ( !$options['sb_instagram_disable_resize'] ) {
			if ( sbi_create_local_avatar( $username, $profile_picture ) ) {
				$connected_accounts[ $user_id ]['local_avatar'] = true;
			}
		} else {
			$connected_accounts[ $user_id ]['local_avatar'] = false;
		}

		delete_transient( SBI_USE_BACKUP_PREFIX . 'sbi_'  . $user_id );
	}

	$options['connected_accounts'] = $connected_accounts;

	update_option( 'sb_instagram_settings', $options );

	echo wp_json_encode( $connected_accounts );

	die();
}
add_action( 'wp_ajax_sbi_connect_business_accounts', 'sbi_connect_business_accounts' );

function sbi_auto_save_id() {
	$nonce = $_POST['sbi_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}
	if ( current_user_can( 'edit_posts' ) && isset( $_POST['id'] ) ) {
		$options = get_option( 'sb_instagram_settings', array() );

		$options['sb_instagram_user_id'] = array( sanitize_text_field( $_POST['id'] ) );

		update_option( 'sb_instagram_settings', $options );
	}
	die();
}
add_action( 'wp_ajax_sbi_auto_save_id', 'sbi_auto_save_id' );

function sbi_test_token() {
	$access_token = isset( $_POST['access_token'] ) ? sanitize_text_field( $_POST['access_token'] ) : false;
	$account_id = isset( $_POST['account_id'] ) ? sanitize_text_field( $_POST['account_id'] ) : false;
	$options = sbi_get_database_settings();
	$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

	if ( $access_token ) {
		wp_cache_delete ( 'alloptions', 'options' );

		$number_dots = substr_count ( $access_token , '.' );
		$test_connection_data = array( 'error_message' => 'A successful connection could not be made. Please make sure your Access Token is valid.');

		if ( $number_dots > 1 ) {
			$split_token = explode( '.', $access_token );
			$new_user_id = isset( $split_token[0] ) ? $split_token[0] : '';

			$test_connection_data = sbi_account_data_for_token( $access_token );
		} else if (! empty( $account_id ) ) {

			if ( sbi_code_check( $access_token ) ) {
				$data = array(
					'access_token' => $access_token,
					'user_id' => $account_id,
					'type' => 'basic'
				);
				$basic_account_attempt = new SB_Instagram_API_Connect( $data, 'header', array() );
				$basic_account_attempt->connect();

				if ( !$basic_account_attempt->is_wp_error() && ! $basic_account_attempt->is_instagram_error() ) {
					$new_data = $basic_account_attempt->get_data();

					$basic_account_access_token_connect = new SB_Instagram_API_Connect( $data, 'access_token', array() );
					$basic_account_access_token_connect->connect();
					if ( !$basic_account_access_token_connect->is_wp_error() && ! $basic_account_access_token_connect->is_instagram_error() ) {

						$token_data = $basic_account_access_token_connect->get_data();
						$expires_in = $token_data['expires_in'];
						$expires_timestamp = time() + $expires_in;

						$new_connected_account = array(
							'access_token' => $access_token,
							'account_type' => $new_data['account_type'],
							'user_id' => $new_data['id'],
							'username' => $new_data['username'],
							'expires_timestamp' => $expires_timestamp,
							'type' => 'basic'
						);

						$updated_options = sbi_connect_basic_account( $new_connected_account );

						echo wp_json_encode( $updated_options['connected_accounts'][ $new_data['id'] ] );
						die();

					}

				}
			}

			$url = 'https://graph.facebook.com/'.$account_id.'?fields=biography,id,username,website,followers_count,media_count,profile_picture_url,name&access_token='.sbi_maybe_clean( $access_token );
			$json = json_decode( sbi_business_account_request( $url, array( 'access_token' => $access_token ) ), true );

			if ( isset( $json['error'] ) && $json['error']['type'] === 'OAuthException' ) {
				$data = array(
					'access_token' => $access_token,
					'user_id' => $account_id,
					'type' => 'basic'
				);
				$basic_account_attempt = new SB_Instagram_API_Connect( $data, 'header', array() );
				$basic_account_attempt->connect();

				if ( !$basic_account_attempt->is_wp_error() && ! $basic_account_attempt->is_instagram_error() ) {
					$new_data = $basic_account_attempt->get_data();

					$basic_account_access_token_connect = new SB_Instagram_API_Connect( $data, 'access_token', array() );
					$basic_account_access_token_connect->connect();
					if ( !$basic_account_access_token_connect->is_wp_error() && ! $basic_account_access_token_connect->is_instagram_error() ) {

						$token_data = $basic_account_access_token_connect->get_data();
						$expires_in = $token_data['expires_in'];
						$expires_timestamp = time() + $expires_in;

						$new_connected_account = array(
							'access_token' => $access_token,
							'account_type' => $new_data['account_type'],
							'user_id' => $new_data['id'],
							'username' => $new_data['username'],
							'expires_timestamp' => $expires_timestamp,
							'type' => 'basic'
						);

						$updated_options = sbi_connect_basic_account( $new_connected_account );

						echo wp_json_encode( $updated_options['connected_accounts'][ $new_data['id'] ] );
						die();

					}

				}

				die();

			} else {
				if ( isset( $json['id'] ) ) {
					$new_user_id = $json['id'];
					$test_connection_data = array(
						'access_token' => $access_token,
						'id' => $json['id'],
						'username' => $json['username'],
						'type' => 'business',
						'is_valid' => true,
						'last_checked' => time(),
						'profile_picture' => $json['profile_picture_url']
					);
				}
			}



			delete_transient( SBI_USE_BACKUP_PREFIX . 'sbi_'  . $json['id'] );

		}

		if ( isset( $test_connection_data['error_message'] ) ) {
			echo $test_connection_data['error_message'];
		} elseif ( $test_connection_data !== false && ! empty( $new_user_id ) ) {
			$username = $test_connection_data['username'] ? $test_connection_data['username'] : $connected_accounts[ $new_user_id ]['username'];
			$user_id = $test_connection_data['id'] ? $test_connection_data['id'] : $connected_accounts[ $new_user_id ]['user_id'];
			$profile_picture = $test_connection_data['profile_picture'] ? $test_connection_data['profile_picture'] : $connected_accounts[ $new_user_id ]['profile_picture'];
			$type = isset( $test_connection_data['type'] ) ? $test_connection_data['type'] : 'personal';
			$connected_accounts[ $new_user_id ] = array(
				'access_token' => sbi_get_parts( $access_token ),
				'user_id' => $user_id,
				'username' => $username,
				'type' => $type,
				'is_valid' => true,
				'last_checked' => $test_connection_data['last_checked'],
				'profile_picture' => $profile_picture
			);

			if ( !$options['sb_instagram_disable_resize'] ) {
				if ( sbi_create_local_avatar( $username, $profile_picture ) ) {
					$connected_accounts[ $new_user_id ]['local_avatar'] = true;
				}
			} else {
				$connected_accounts[ $new_user_id ]['local_avatar'] = false;
			}

			if ( $type === 'business' ) {
				$url = 'https://graph.facebook.com/'.$user_id.'/tags?user_id='.$user_id.'&fields=id&limit=1&access_token='.sbi_maybe_clean( $access_token );
				$args = array(
					'timeout' => 60,
					'sslverify' => false
				);
				$response = wp_remote_get( $url, $args );

				if ( ! is_wp_error( $response ) ) {
					// certain ways of representing the html for double quotes causes errors so replaced here.
					$response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );
					if ( isset( $response['data'] ) ) {
						$connected_accounts[ $new_user_id ]['use_tagged'] = '1';
					}
				}
			}

			delete_transient( SBI_USE_BACKUP_PREFIX . 'sbi_'  . $user_id );

			$options['connected_accounts'] = $connected_accounts;

			update_option( 'sb_instagram_settings', $options );

			echo wp_json_encode( $connected_accounts[ $new_user_id ] );
		} else {
			echo 'A successful connection could not be made. Please make sure your Access Token is valid.';
		}

	}

	die();
}
add_action( 'wp_ajax_sbi_test_token', 'sbi_test_token' );

function sbi_delete_account() {
	$nonce = $_POST['sbi_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}
	$account_id = isset( $_POST['account_id'] ) ? sanitize_text_field( $_POST['account_id'] ) : false;
	$options = get_option( 'sb_instagram_settings', array() );
	$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

	wp_cache_delete ( 'alloptions', 'options' );
	$username = $connected_accounts[ $account_id ]['username'];

	$num_times_used = 0;

	$new_con_accounts = array();
	foreach ( $connected_accounts as $connected_account ) {

		if ( $connected_account['username'] === $username ) {
			$num_times_used++;
		}

		if ( $connected_account['username'] !== '' && $account_id !== $connected_account['user_id'] && ! empty( $connected_account['user_id'] ) ) {
			$new_con_accounts[ $connected_account['user_id'] ] = $connected_account;
		}
	}

	if ( $num_times_used < 2 ) {
		sbi_delete_local_avatar( $username );
	}

	$options['connected_accounts'] = $new_con_accounts;

	update_option( 'sb_instagram_settings', $options );


	die();
}
add_action( 'wp_ajax_sbi_delete_account', 'sbi_delete_account' );

function sbi_account_data_for_token( $access_token ) {
	$return = array(
		'id' => false,
		'username' => false,
		'is_valid' => false,
		'last_checked' => time()
	);
	$url = 'https://api.instagram.com/v1/users/self/?access_token=' . sbi_maybe_clean( $access_token );
	$args = array(
		'timeout' => 60,
		'sslverify' => false
	);
	$result = wp_remote_get( $url, $args );

	if ( ! is_wp_error( $result ) ) {
		$data = json_decode( $result['body'] );
	} else {
		$data = array();
	}

	if ( isset( $data->data->id ) ) {
		$return['id'] = $data->data->id;
		$return['username'] = $data->data->username;
		$return['is_valid'] = true;
		$return['profile_picture'] = $data->data->profile_picture;

	} elseif ( isset( $data->error_type ) && $data->error_type === 'OAuthRateLimitException' ) {
		$return['error_message'] = 'This account\'s access token is currently over the rate limit. Try removing this access token from all feeds and wait an hour before reconnecting.';
	} else {
		$return = false;
	}

	$sbi_options = get_option( 'sb_instagram_settings', array() );
	$sbi_options['sb_instagram_at'] = '';
	update_option( 'sb_instagram_settings', $sbi_options );

	return $return;
}

function sbi_get_connected_accounts_data( $sb_instagram_at ) {
	$sbi_options = get_option( 'sb_instagram_settings' );
	$return = array();
	$return['connected_accounts'] = isset( $sbi_options['connected_accounts'] ) ? $sbi_options['connected_accounts'] : array();

	if ( ! empty( $return['connected_accounts'] ) ) {
		$return['access_token'] = '';
	} else {
		$return['access_token'] = $sb_instagram_at;
	}

	if ( ! sbi_is_after_deprecation_deadline() && empty( $connected_accounts ) && ! empty( $sb_instagram_at ) ) {
		$tokens = explode(',', $sb_instagram_at );
		$user_ids = array();

		foreach ( $tokens as $token ) {
			$account = sbi_account_data_for_token( $token );
			if ( isset( $account['is_valid'] ) ) {
				$split = explode( '.', $token );
				$return['connected_accounts'][ $split[0] ] = array(
					'access_token' => sbi_get_parts( $token ),
					'user_id' => $split[0],
					'username' => '',
					'is_valid' => true,
					'last_checked' => time(),
					'profile_picture' => ''
				);
				$user_ids[] = $split[0];
			}

		}

		$sbi_options['connected_accounts'] = $return['connected_accounts'];
		$sbi_options['sb_instagram_at'] = '';
		$sbi_options['sb_instagram_user_id'] = $user_ids;

		$return['user_ids'] = $user_ids;

		update_option( 'sb_instagram_settings', $sbi_options );
	}

	return $return;
}

function sbi_connect_basic_account( $new_account_details ) {

	$options = sbi_get_database_settings();
	$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

	$accounts_to_save = array();
	$old_account_user_id = '';
	$ids_to_save = array();
	$user_ids = is_array( $options[ 'sb_instagram_user_id' ] ) ? $options[ 'sb_instagram_user_id' ] : explode( ',', str_replace( ' ', '', $options[ 'sb_instagram_user_id' ] ) );

	$profile_picture = '';

	// do not connect as a basic display account if already connected as a business account
	if ( isset( $connected_accounts[ $new_account_details['user_id'] ] )
	     && isset( $connected_accounts[ $new_account_details['user_id'] ]['type'] )
	     && $connected_accounts[ $new_account_details['user_id'] ]['type'] === 'business' ) {
		return $options;
	}

	foreach ( $connected_accounts as $account ) {
		$account_type = isset( $account['type'] ) ? $account['type'] : 'personal';
		if ( ($account['username'] !== $new_account_details['username'])
		     || $account_type === 'business' ) {
			$accounts_to_save[ $account['user_id'] ] = $account;
		} else {
			$old_account_user_id = $account['user_id'];
			$profile_picture = isset( $account['profile_picture'] ) ? $account['profile_picture'] : '';
		}
	}

	foreach ( $user_ids as $id ) {
		if ( $id === $old_account_user_id ) {
			$ids_to_save[] = $new_account_details['user_id'];
		} else {
			$ids_to_save[] = $id;
		}
	}

	$accounts_to_save[ $new_account_details['user_id'] ] = array(
		'access_token' => sbi_fixer( $new_account_details['access_token'] ),
		'user_id' => $new_account_details['user_id'],
		'username' => $new_account_details['username'],
		'is_valid' => true,
		'last_checked' => time(),
		'expires_timestamp' => $new_account_details['expires_timestamp'],
		'profile_picture' => $profile_picture,
		'account_type' => strtolower( $new_account_details['account_type'] ),
		'type' => 'basic',
	);

	if ( ! empty( $old_account_user_id ) && $old_account_user_id !== $new_account_details['user_id'] ) {
		$accounts_to_save[ $new_account_details['user_id'] ]['old_user_id'] = $old_account_user_id;

		// get last saved header data
		$fuzzy_matches = sbi_fuzzy_matching_header_data( $old_account_user_id );
		if ( ! empty( $fuzzy_matches[0] ) ) {
			$header_data = sbi_find_matching_data_from_results( $fuzzy_matches, $old_account_user_id );
			$bio = SB_Instagram_Parse::get_bio( $header_data );
			$accounts_to_save[ $new_account_details['user_id'] ]['bio'] = sbi_sanitize_emoji( $bio );
		}

	}

	if ( ! empty( $profile_picture ) && !$options['sb_instagram_disable_resize'] ) {
		if ( sbi_create_local_avatar( $new_account_details['username'], $profile_picture ) ) {
			$accounts_to_save[ $new_account_details['user_id'] ]['local_avatar'] = true;
		}
	} else {
		$accounts_to_save[ $new_account_details['user_id'] ]['local_avatar'] = false;
	}

	delete_transient( SBI_USE_BACKUP_PREFIX . 'sbi_'  . $new_account_details['user_id'] );

	$options['connected_accounts'] = $accounts_to_save;
	$options['sb_instagram_user_id'] = $ids_to_save;

	update_option( 'sb_instagram_settings', $options );
	return $options;
}

function sbi_fuzzy_matching_header_data( $user_id ) {

	if ( empty( $user_id ) || strlen( $user_id ) < 4 ) {
		return array();
	}
	global $wpdb;
	$escaped_id = esc_sql( $user_id );

	$values = $wpdb->get_results( "
    SELECT option_value
    FROM $wpdb->options
    WHERE option_name LIKE ('%!sbi\_header\_".$escaped_id."%')
    LIMIT 10", ARRAY_A );

	$regular_values = $wpdb->get_results( "
    SELECT option_value
    FROM $wpdb->options
    WHERE option_name LIKE ('%sbi\_header\_".$escaped_id."%')
    LIMIT 10", ARRAY_A );

	$values = array_merge( $values, $regular_values );

	return $values;
}

function sbi_find_matching_data_from_results( $results, $user_id ) {

	$match = array();

	$i = 0;

	while( empty( $match ) && isset( $results[ $i ] ) ) {
		if ( ! empty( $results[ $i ] ) ) {
			$header_data = json_decode( $results[ $i ]['option_value'], true );
			if ( isset( $header_data['id'] ) && (string)$header_data['id'] === (string)$user_id ) {
				$match = $header_data;
			}
		}
		$i++;
	}

	return $match;
}

function sbi_matches_existing_personal( $new_account_details ) {

	$options = sbi_get_database_settings();
	$connected_accounts =  isset( $options['connected_accounts'] ) ? $options['connected_accounts'] : array();

	$matches_one_account = false;
	$accounts_to_save = array();
	foreach ( $connected_accounts as $account ) {
		$account_type = isset( $account['type'] ) ? $account['type'] : 'personal';
		if ( ($account_type === 'personal' || $account_type === 'basic')
		     && $account['username'] == $new_account_details['username'] ) {
			$matches_one_account = true;


		}
	}

	return $matches_one_account;

}

function sbi_business_account_request( $url, $account, $remove_access_token = true ) {
	$args = array(
		'timeout' => 60,
		'sslverify' => false
	);
	$result = wp_remote_get( $url, $args );

	if ( ! is_wp_error( $result ) ) {
		$response_no_at = $remove_access_token ? str_replace( sbi_maybe_clean( $account['access_token'] ), '{accesstoken}', $result['body'] ) : $result['body'];
		return $response_no_at;
	} else {
		return wp_json_encode( $result );
	}
}

function sbi_after_connection() {

	if ( isset( $_POST['access_token'] ) ) {
		$access_token = sanitize_text_field( $_POST['access_token'] );
		$account_info = 	sbi_account_data_for_token( $access_token );
		echo wp_json_encode( $account_info );
	}

	die();
}
add_action( 'wp_ajax_sbi_after_connection', 'sbi_after_connection' );

function sbi_account_type_display( $type ) {
	if ( $type === 'basic' ) {
		return 'personal (new API)';
	}
	return $type;
}

function sbi_clear_backups() {
	$nonce = isset( $_POST['sbi_nonce'] ) ? sanitize_text_field( $_POST['sbi_nonce'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	//Delete all transients
	global $wpdb;
	$table_name = $wpdb->prefix . "options";
	$wpdb->query( "
    DELETE
    FROM $table_name
    WHERE `option_name` LIKE ('%!sbi\_%')
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

	die();
}
add_action( 'wp_ajax_sbi_clear_backups', 'sbi_clear_backups' );

function sbi_reset_resized() {

	global $sb_instagram_posts_manager;
	$sb_instagram_posts_manager->delete_all_sbi_instagram_posts();

	echo "1";

	die();
}
add_action( 'wp_ajax_sbi_reset_resized', 'sbi_reset_resized' );

function sbi_lite_dismiss() {
	$nonce = isset( $_POST['sbi_nonce'] ) ? sanitize_text_field( $_POST['sbi_nonce'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sbi_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	set_transient( 'instagram_feed_dismiss_lite', 'dismiss', 1 * WEEK_IN_SECONDS );

	die();
}
add_action( 'wp_ajax_sbi_lite_dismiss', 'sbi_lite_dismiss' );

function sbi_reset_log() {

	delete_option( 'sb_instagram_errors' );

	echo "1";

	die();
}
add_action( 'wp_ajax_sbi_reset_log', 'sbi_reset_log' );

add_action('admin_notices', 'sbi_admin_error_notices');
function sbi_admin_error_notices() {
	//Only display notice to admins
	if( !current_user_can( 'manage_options' ) ) return;

	global $sb_instagram_posts_manager;

	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'sb-instagram-feed' )) ) {
		$errors = $sb_instagram_posts_manager->get_errors();
		if ( ! empty( $errors ) && ( isset( $errors['database_create_posts'] ) || isset( $errors['database_create_posts_feeds'] ) || isset( $errors['upload_dir'] ) || isset( $errors['ajax'] )  ) ) : ?>
            <div class="notice notice-warning is-dismissible sbi-admin-notice">

				<?php foreach ( $sb_instagram_posts_manager->get_errors() as $type => $error ) : ?>
					<?php if ( (in_array( $type, array( 'database_create_posts', 'database_create_posts_feeds', 'upload_dir' ) ) && !$sb_instagram_posts_manager->image_resizing_disabled() ) ) : ?>
                        <p><strong><?php echo $error[0]; ?></strong></p>
                        <p><?php _e( 'Note for support', 'instagram-feed' ); ?>: <?php echo $error[1]; ?></p>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( ( isset( $errors['database_create_posts'] ) || isset( $errors['database_create_posts_feeds'] ) || isset( $errors['upload_dir'] ) ) && !$sb_instagram_posts_manager->image_resizing_disabled() ) : ?>
                    <p><?php _e( sprintf( 'Visit our %s page for help', '<a href="https://smashballoon.com/instagram-feed/support/faq/" target="_blank">FAQ</a>' ), 'instagram-feed' ); ?></p>
				<?php endif; ?>

				<?php foreach ( $sb_instagram_posts_manager->get_errors() as $type => $error ) : ?>
					<?php if (in_array( $type, array( 'ajax' ) )) : ?>
                        <p class="sbi-admin-error" data-sbi-type="ajax"><strong><?php echo $error[0]; ?></strong></p>
                        <p><?php echo $error[1]; ?></p>
					<?php endif; ?>
				<?php endforeach; ?>

            </div>

		<?php endif;
	}

}

function sbi_maybe_add_ajax_test_error() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'sb-instagram-feed' ) {
		global $sb_instagram_posts_manager;

		if ( $sb_instagram_posts_manager->should_add_ajax_test_notice() ) {
			$sb_instagram_posts_manager->add_error( 'ajax', array( __( 'Unable to use admin-ajax.php when displaying feeds. Some features of the plugin will be unavailable.', 'instagram-feed' ), __( sprintf( 'Please visit %s to troubleshoot.', '<a href="https://smashballoon.com/admin-ajax-requests-are-not-working/">'.__( 'this page', 'instagram-feed' ).'</a>' ), 'instagram-feed' ) ) );
		} else {
			$sb_instagram_posts_manager->remove_error( 'ajax' );
		}
	}
}
add_action( 'admin_init', 'sbi_maybe_add_ajax_test_error' );

function sbi_get_user_names_of_personal_accounts_not_also_already_updated() {
	$sbi_options = get_option( 'sb_instagram_settings', array() );
	$users_in_personal_accounts = array();
	$non_personal_account_users = array();

	$connected_accounts = isset( $sbi_options['connected_accounts'] ) ? $sbi_options['connected_accounts'] : array();

	if ( ! empty( $connected_accounts ) ) {

		foreach ( $connected_accounts as $account ) {
			$account_type = isset( $account['type'] ) ? $account['type'] : 'personal';

			if ( $account_type === 'personal' ) {
				$users_in_personal_accounts[] = $account['username'];
			} else {
				$non_personal_account_users[] = $account['username'];
			}

		}

		if ( ! empty( $users_in_personal_accounts ) ) {
			$user_accounts_that_need_updating = array();
			foreach ( $users_in_personal_accounts as $personal_user ) {
				if ( ! in_array( $personal_user, $non_personal_account_users, true ) && $personal_user !== '' ) {
					$user_accounts_that_need_updating[] = $personal_user;
				}
			}

			return $user_accounts_that_need_updating;
		}
	} elseif ( empty( $connected_accounts ) && ! empty( $sbi_options['sb_instagram_at'] ) ) {
		return array( 'your Instagram feed');
	}

	return array();
}

function sbi_reconnect_accounts_notice() {
	if( ! current_user_can( 'manage_options' ) ) return;

	$should_show_link = ! isset( $_GET['page'] ) || $_GET['page'] !== 'sb-instagram-feed';
	$personal_accounts_that_need_updating = sbi_get_user_names_of_personal_accounts_not_also_already_updated();
	if ( empty( $personal_accounts_that_need_updating ) ) {
		return;
	} else {
		$total = count( $personal_accounts_that_need_updating );
		if ( $total > 1 ) {
			$user_string = '';
			$i = 0;

			foreach ( $personal_accounts_that_need_updating as $username ) {
				if ( ($i + 1) === $total ) {
					$user_string .= ' and ' . $username;
				} else {
					if ( $i !== 0 ) {
						$user_string .= ', ' . $username;
					} else {
						$user_string .= $username;
					}
				}
				$i++;
			}
		} else {
			$user_string = $personal_accounts_that_need_updating[0];
		}

		if ( sbi_is_after_deprecation_deadline() ) {
			$notice_class = 'notice-error';
			$error = '<p><b>' . sprintf( __( 'Error: Instagram Feed plugin - account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to recent Instagram platform changes some Instagram accounts will need to be reconnected in the plugin in order for them to continue updating.', 'instagram-feed' );
		} else {
			$notice_class = 'notice-warning';
			$error = '<p><b>' . sprintf( __( 'Warning: Instagram Feed plugin - account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to Instagram platform changes on March 31, 2020, some Instagram accounts will need to be reconnected in the plugin to avoid disruption to your feeds.', 'instagram-feed' );
		}
		if( !$should_show_link ) $error .= __( ' Use the big blue button below to reconnect your account.', 'instagram-feed' );
	}
	$url = admin_url( '?page=sb-instagram-feed' );

	?>
    <div class="notice <?php echo $notice_class; ?> is-dismissible">
		<?php echo $error; ?>
        <p>
			<?php if ( $should_show_link ) : ?>
                <a href="<?php echo $url; ?>" class="button-primary" style="margin-right:10px;"><i class="fa fa-instagram" aria-hidden="true"></i> &nbsp;Reconnect on Settings Page</a>
			<?php endif; ?>
            <a href="https://smashballoon.com/instagram-api-changes-march-2-2020/" target="_blank" rel="noopener">See more details</a>
        </p>
    </div>

	<?php

}
add_action( 'admin_notices', 'sbi_reconnect_accounts_notice' );

function sbi_get_current_time() {
	$current_time = time();

	// where to do tests
	// $current_time = strtotime( 'November 25, 2022' ) + 1;

	return $current_time;
}

// generates the html for the admin notices
function sbi_notices_html() {

	//Only show to admins
	$current_screen = get_current_screen();
	$is_plugins_page = isset( $current_screen->id ) && $current_screen->id === 'plugins';
	$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
	//Only show to admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$sbi_statuses_option = get_option( 'sbi_statuses', array() );
	$current_time = sbi_get_current_time();
	$sbi_bfcm_discount_code = 'happysmashgiving' . date('Y', $current_time );

	// reset everything for testing
	if ( false ) {
		global $current_user;
		$user_id = $current_user->ID;
		delete_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice' );
		//delete_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice' );
		//$sbi_statuses_option = array( 'first_install' => strtotime( 'December 8, 2019' ) );
		//$sbi_statuses_option = array( 'first_install' => time() );

		//update_option( 'sbi_statuses', $sbi_statuses_option, false );
		//delete_option( 'sbi_rating_notice');
		//delete_transient( 'instagram_feed_rating_notice_waiting' );

		//set_transient( 'instagram_feed_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS );
		//update_option( 'sbi_rating_notice', 'pending', false );
	}

	//$sbi_statuses_option['rating_notice_dismissed'] = time();
	//update_option( 'sbi_statuses', $sbi_statuses_option, false );
	// rating notice logic
	$sbi_rating_notice_option = get_option( 'sbi_rating_notice', false );
	$sbi_rating_notice_waiting = get_transient( 'instagram_feed_rating_notice_waiting' );
	$should_show_rating_notice = ($sbi_rating_notice_waiting !== 'waiting' && $sbi_rating_notice_option !== 'dismissed');

	// black friday cyber monday logic
	$thanksgiving_this_year = sbi_get_future_date( 11, date('Y', $current_time ), 4, 4, 1 );
	$one_week_before_black_friday_this_year = $thanksgiving_this_year - 7*24*60*60;
	$one_day_after_cyber_monday_this_year = $thanksgiving_this_year + 5*24*60*60;
	$has_been_two_days_since_rating_dismissal = isset( $sbi_statuses_option['rating_notice_dismissed'] ) ? ((int)$sbi_statuses_option['rating_notice_dismissed'] + 2*24*60*60) < $current_time : true;

	$could_show_bfcm_discount = ($current_time > $one_week_before_black_friday_this_year && $current_time < $one_day_after_cyber_monday_this_year);
	$should_show_bfcm_discount = false;
	if ( $could_show_bfcm_discount && $has_been_two_days_since_rating_dismissal ) {
		global $current_user;
		$user_id = $current_user->ID;

		$ignore_bfcm_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice' );
		$ignore_bfcm_sale_notice_meta = isset( $ignore_bfcm_sale_notice_meta[0] ) ? $ignore_bfcm_sale_notice_meta[0] : '';

		/* Check that the user hasn't already clicked to ignore the message */
		$should_show_bfcm_discount = ($ignore_bfcm_sale_notice_meta !== 'always' && $ignore_bfcm_sale_notice_meta !== date( 'Y', $current_time ));
	}

	// new user discount logic
	$in_new_user_month_range = true;
	$should_show_new_user_discount = false;
	$has_been_one_month_since_rating_dismissal = isset( $sbi_statuses_option['rating_notice_dismissed'] ) ? ((int)$sbi_statuses_option['rating_notice_dismissed'] + 30*24*60*60) < $current_time + 1: true;

	if ( isset( $sbi_statuses_option['first_install'] ) && $sbi_statuses_option['first_install'] === 'from_update' ) {
		global $current_user;
		$user_id = $current_user->ID;
		$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice' );
		$ignore_new_user_sale_notice_meta = isset( $ignore_new_user_sale_notice_meta[0] ) ? $ignore_new_user_sale_notice_meta[0] : '';
		if ( $ignore_new_user_sale_notice_meta !== 'always' ) {
			$should_show_new_user_discount = true;
		}
	} elseif ( $in_new_user_month_range && $has_been_one_month_since_rating_dismissal ) {
		global $current_user;
		$user_id = $current_user->ID;
		$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice' );
		$ignore_new_user_sale_notice_meta = isset( $ignore_new_user_sale_notice_meta[0] ) ? $ignore_new_user_sale_notice_meta[0] : '';

		if ( $ignore_new_user_sale_notice_meta !== 'always'
		     && isset( $sbi_statuses_option['first_install'] )
		     && $current_time > (int)$sbi_statuses_option['first_install'] + 60*60*24*30 ) {
			$should_show_new_user_discount = true;
		}
	}

	// for debugging
	if ( false ) {
		global $current_user;
		$user_id = $current_user->ID;
		$ignore_bfcm_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice' );
		$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice' );

		var_dump( 'new user rating option', $sbi_rating_notice_option );
		var_dump( 'new user rating transient', $sbi_rating_notice_waiting );

		var_dump( 'should show new user rating notice?', $should_show_rating_notice );

		var_dump( 'new user discount month range?', $in_new_user_month_range );
		var_dump( 'should show new user discount?', $should_show_new_user_discount );

		var_dump( 'Thanksgiving this year?', date('m/d/Y', $thanksgiving_this_year ) );

		var_dump( 'could show bfcm discount?', $could_show_bfcm_discount );
		var_dump( 'should show bfcm discount?', $should_show_bfcm_discount );

		var_dump( 'ignore_bfcm_sale_notice_meta', $ignore_bfcm_sale_notice_meta );
		var_dump( 'ignore_new_user_sale_notice_meta', $ignore_new_user_sale_notice_meta );

		var_dump( $sbi_statuses_option );
	}
	

	if ( $should_show_rating_notice ) {
		$other_notice_html = '';
		$dismiss_url = add_query_arg( 'sbi_ignore_rating_notice_nag', '1' );
		$later_url = add_query_arg( 'sbi_ignore_rating_notice_nag', 'later' );
		if ( $should_show_bfcm_discount ) {
			$other_notice_html = '<p class="sbi_other_notice">' .  __( 'PS. We currently have a <a href="https://smashballoon.com/instagram-feed/?utm_source=plugin-free&utm_campaign=sbi&discount='.$sbi_bfcm_discount_code.'" target="_blank"><b style="font-weight: 700;">Black Friday deal</b></a> for 20% off the Pro version!', 'instagram-feed' ) . '</a></p>';

			$dismiss_url = add_query_arg( array(
					'sbi_ignore_rating_notice_nag' => '1',
					'sbi_ignore_bfcm_sale_notice' => date( 'Y', $current_time )
				)
			);
			$later_url = add_query_arg( array(
					'sbi_ignore_rating_notice_nag' => 'later',
					'sbi_ignore_bfcm_sale_notice' => date( 'Y', $current_time )
				)
			);
		}

		echo "
            <div class='sbi_notice sbi_review_notice'>
                <img src='". SBI_PLUGIN_URL . 'img/sbi-icon.png' ."' alt='" . __( 'Instagram Feed', 'instagram-feed' ) . "'>
                <div class='sbi-notice-text'>
                    <p style='padding-top: 4px;'>" . __( "It's great to see that you've been using the <strong style='font-weight: 700;'>Instagram Feed</strong> plugin for a while now. Hopefully you're happy with it!&nbsp; If so, would you consider leaving a positive review? It really helps to support the plugin and helps others to discover it too!", 'instagram-feed' ) . "</p>
                    <p class='links'";
                    if( $should_show_bfcm_discount ) echo " style='margin-top: 0 !important;'";
                    echo ">
                        <a class='sbi_notice_dismiss' href='https://wordpress.org/support/plugin/instagram-feed/reviews/' target='_blank'>" . __( 'Sure, I\'d love to!', 'instagram-feed' ) . "</a>
                        &middot;
                        <a class='sbi_notice_dismiss' href='" .esc_url( $dismiss_url ). "'>" . __( 'No thanks', 'instagram-feed' ) . "</a>
                        &middot;
                        <a class='sbi_notice_dismiss' href='" .esc_url( $dismiss_url ). "'>" . __( 'I\'ve already given a review', 'instagram-feed' ) . "</a>
                        &middot;
                        <a class='sbi_notice_dismiss' href='" .esc_url( $later_url ). "'>" . __( 'Ask Me Later', 'instagram-feed' ) . "</a>
                    </p>"
		    . $other_notice_html .
		    "</div>
                <a class='sbi_notice_close' href='" .esc_url( $dismiss_url ). "'><i class='fa fa-close'></i></a>
            </div>";

	} elseif ( $should_show_new_user_discount ) {
		global $current_user;
		$user_id = $current_user->ID;
		$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice' );
		if ( $ignore_new_user_sale_notice_meta !== 'always' ) {

			echo "
        <div class='sbi_notice sbi_review_notice sbi_new_user_sale_notice'>
            <img src='" . SBI_PLUGIN_URL . 'img/sbi-icon-offer.png' . "' alt='Instagram Feed'>
            <div class='sbi-notice-text'>
                <p>" . __( '<b style="font-weight: 700;">Exclusive offer!</b>  We don\'t run promotions very often, but for a limited time we\'re offering <b style="font-weight: 700;">20% off</b> our Pro version to all users of our free Instagram Feed plugin.', 'instagram-feed' ) . "</p>
                <p class='sbi-links'>
                    <a class='sbi_notice_dismiss sbi_offer_btn' href='https://smashballoon.com/instagram-feed/?utm_source=plugin-free&utm_campaign=sbi&discount=instagramthankyou' target='_blank'><b>" . __( 'Get this offer', 'instagram-feed' ) . "</b></a>
                    <a class='sbi_notice_dismiss' style='margin-left: 5px;' href='" . esc_url( add_query_arg( 'sbi_ignore_new_user_sale_notice', 'always' ) ) . "'>" . __( 'I\'m not interested', 'instagram-feed' ) . "</a>

                </p>
            </div>
            <a class='sbi_new_user_sale_notice_close' href='" . esc_url( add_query_arg( 'sbi_ignore_new_user_sale_notice', 'always' ) ) . "'><i class='fa fa-close'></i></a>
        </div>
        ";
		}

	} elseif ( $should_show_bfcm_discount ) {

		echo "
        <div class='sbi_notice sbi_review_notice sbi_bfcm_sale_notice'>
            <img src='". SBI_PLUGIN_URL . 'img/sbi-icon-offer.png' ."' alt='Instagram Feed'>
            <div class='sbi-notice-text'>
                <p>" . __( '<b style="font-weight: 700;">Black Friday/Cyber Monday Deal!</b> Thank you for using our free Instagram Feed plugin. For a limited time, we\'re offering <b style="font-weight: 700;">20% off</b> the Pro version for all of our users.', 'instagram-feed' ) . "</p>
                <p class='sbi-links'>
                    <a class='sbi_notice_dismiss sbi_offer_btn' href='https://smashballoon.com/instagram-feed/?utm_source=plugin-free&utm_campaign=sbi&discount=".$sbi_bfcm_discount_code."' target='_blank'>" . __( 'Get this offer!', 'instagram-feed' ) . "</a>
                    <a class='sbi_notice_dismiss' style='margin-left: 5px;' href='" .esc_url( add_query_arg( 'sbi_ignore_bfcm_sale_notice', date( 'Y', $current_time ) ) ). "'>" . __( 'I\'m not interested', 'instagram-feed' ) . "</a>
                </p>
            </div>
            <a class='sbi_bfcm_sale_notice_close' href='" .esc_url( add_query_arg( 'sbi_ignore_bfcm_sale_notice', date( 'Y', $current_time ) ) ). "'><i class='fa fa-close'></i></a>
        </div>
        ";

	}

}
add_action( 'admin_notices', 'sbi_notices_html', 8 ); // priority 12 for Twitter, priority 10 for Facebook

function sbi_process_nags() {

	global $current_user;
	$user_id = $current_user->ID;
	$sbi_statuses_option = get_option( 'sbi_statuses', array() );

	if ( isset( $_GET['sbi_ignore_rating_notice_nag'] ) ) {
		if ( (int)$_GET['sbi_ignore_rating_notice_nag'] === 1 ) {
			update_option( 'sbi_rating_notice', 'dismissed', false );
			$sbi_statuses_option['rating_notice_dismissed'] = sbi_get_current_time();
			update_option( 'sbi_statuses', $sbi_statuses_option, false );

		} elseif ( $_GET['sbi_ignore_rating_notice_nag'] === 'later' ) {
			set_transient( 'instagram_feed_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS );
			update_option( 'sbi_rating_notice', 'pending', false );
		}
	}

	if ( isset( $_GET['sbi_ignore_new_user_sale_notice'] ) ) {
		$response = sanitize_text_field( $_GET['sbi_ignore_new_user_sale_notice'] );
		if ( $response === 'always' ) {
			update_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice', 'always' );

			$current_month_number = (int)date('n', sbi_get_current_time() );
			$not_early_in_the_year = ($current_month_number > 5);

			if ( $not_early_in_the_year ) {
				update_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice', date( 'Y', sbi_get_current_time() ) );
			}

		}
	}

	if ( isset( $_GET['sbi_ignore_bfcm_sale_notice'] ) ) {
		$response = sanitize_text_field( $_GET['sbi_ignore_bfcm_sale_notice'] );
		if ( $response === 'always' ) {
			update_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice', 'always' );
		} elseif ( $response === date( 'Y', sbi_get_current_time() ) ) {
			update_user_meta( $user_id, 'sbi_ignore_bfcm_sale_notice', date( 'Y', sbi_get_current_time() ) );
		}
		update_user_meta( $user_id, 'sbi_ignore_new_user_sale_notice', 'always' );
	}

}
add_action( 'admin_init', 'sbi_process_nags' );

function sbi_get_future_date( $month, $year, $week, $day, $direction ) {
	if ( $direction > 0 ) {
		$startday = 1;
	} else {
		$startday = date( 't', mktime(0, 0, 0, $month, 1, $year ) );
	}

	$start = mktime( 0, 0, 0, $month, $startday, $year );
	$weekday = date( 'N', $start );

	$offset = 0;
	if ( $direction * $day >= $direction * $weekday ) {
		$offset = -$direction * 7;
	}

	$offset += $direction * ($week * 7) + ($day - $weekday);
	return mktime( 0, 0, 0, $month, $startday + $offset, $year );
}
