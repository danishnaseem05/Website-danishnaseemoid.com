<?php
/**
 * Class SB_Instagram_Settings
 *
 * Creates organized settings from shortcode settings and settings
 * from the options table.
 *
 * Also responsible for creating transient names/feed ids based on
 * feed settings
 *
 * @since 2.0/5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SB_Instagram_Settings {
	/**
	 * @var array
	 */
	protected $atts;

	/**
	 * @var array
	 */
	protected $db;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $feed_type_and_terms;

	/**
	 * @var array
	 */
	protected $connected_accounts;

	/**
	 * @var array
	 */
	protected $connected_accounts_in_feed;

	/**
	 * @var string
	 */
	protected $transient_name;

	/**
	 * SB_Instagram_Settings constructor.
	 *
	 * Overwritten in the Pro version.
	 *
	 * @param array $atts shortcode settings
	 * @param array $db settings from the wp_options table
	 */
	public function __construct( $atts, $db ) {
		$this->atts = $atts;
		$this->db   = $db;

		$this->connected_accounts = isset( $db['connected_accounts'] ) ? $db['connected_accounts'] : array();

		$this->settings = shortcode_atts(
			array(
				'id'               => isset( $db['sb_instagram_user_id'] ) ? $db['sb_instagram_user_id'] : '',
				'width'            => isset( $db['sb_instagram_width'] ) ? $db['sb_instagram_width'] : '',
				'widthunit'        => isset( $db['sb_instagram_width_unit'] ) ? $db['sb_instagram_width_unit'] : '',
				'widthresp'        => isset( $db['sb_instagram_feed_width_resp'] ) ? $db['sb_instagram_feed_width_resp'] : '',
				'height'           => isset( $db['sb_instagram_height'] ) ? $db['sb_instagram_height'] : '',
				'heightunit'       => isset( $db['sb_instagram_height_unit'] ) ? $db['sb_instagram_height_unit'] : '',
				'sortby'           => isset( $db['sb_instagram_sort'] ) ? $db['sb_instagram_sort'] : '',
				'num'              => isset( $db['sb_instagram_num'] ) ? $db['sb_instagram_num'] : '',
				'apinum'           => isset( $db['sb_instagram_minnum'] ) ? $db['sb_instagram_minnum'] : '',
				'nummobile'        => isset($db[ 'sb_instagram_nummobile' ]) ? $db[ 'sb_instagram_nummobile' ] : '',
				'cols'             => isset( $db['sb_instagram_cols'] ) ? $db['sb_instagram_cols'] : '',
				'disablemobile'    => isset( $db['sb_instagram_disable_mobile'] ) ? $db['sb_instagram_disable_mobile'] : '',
				'imagepadding'     => isset( $db['sb_instagram_image_padding'] ) ? $db['sb_instagram_image_padding'] : '',
				'imagepaddingunit' => isset( $db['sb_instagram_image_padding_unit'] ) ? $db['sb_instagram_image_padding_unit'] : '',
				'background'       => isset( $db['sb_instagram_background'] ) ? $db['sb_instagram_background'] : '',
				'showbutton'       => isset( $db['sb_instagram_show_btn'] ) ? $db['sb_instagram_show_btn'] : '',
				'buttoncolor'      => isset( $db['sb_instagram_btn_background'] ) ? $db['sb_instagram_btn_background'] : '',
				'buttontextcolor'  => isset( $db['sb_instagram_btn_text_color'] ) ? $db['sb_instagram_btn_text_color'] : '',
				'buttontext'       => isset( $db['sb_instagram_btn_text'] ) ? $db['sb_instagram_btn_text'] : '',
				'imageres'         => isset( $db['sb_instagram_image_res'] ) ? $db['sb_instagram_image_res'] : '',
				'showfollow'       => isset( $db['sb_instagram_show_follow_btn'] ) ? $db['sb_instagram_show_follow_btn'] : '',
				'followcolor'      => isset( $db['sb_instagram_folow_btn_background'] ) ? $db['sb_instagram_folow_btn_background'] : '',
				'followtextcolor'  => isset( $db['sb_instagram_follow_btn_text_color'] ) ? $db['sb_instagram_follow_btn_text_color'] : '',
				'followtext'       => isset( $db['sb_instagram_follow_btn_text'] ) ? $db['sb_instagram_follow_btn_text'] : '',
				'showheader'       => isset( $db['sb_instagram_show_header'] ) ? $db['sb_instagram_show_header'] : '',
				'headersize'       => isset( $db['sb_instagram_header_size'] ) ? $db['sb_instagram_header_size'] : '',
				'showbio'          => isset( $db['sb_instagram_show_bio'] ) ? $db['sb_instagram_show_bio'] : '',
				'custombio' => isset($db[ 'sb_instagram_custom_bio' ]) ? $db[ 'sb_instagram_custom_bio' ] : '',
				'customavatar' => isset($db[ 'sb_instagram_custom_avatar' ]) ? $db[ 'sb_instagram_custom_avatar' ] : '',
				'headercolor'      => isset( $db['sb_instagram_header_color'] ) ? $db['sb_instagram_header_color'] : '',
				'class'            => '',
				'ajaxtheme'        => isset( $db['sb_instagram_ajax_theme'] ) ? $db['sb_instagram_ajax_theme'] : '',
				'cachetime'        => isset( $db['sb_instagram_cache_time'] ) ? $db['sb_instagram_cache_time'] : '',
				'media'            => isset( $db['sb_instagram_media_type'] ) ? $db['sb_instagram_media_type'] : '',
				'headeroutside' => isset($db[ 'sb_instagram_outside_scrollable' ]) ? $db[ 'sb_instagram_outside_scrollable' ] : '',
				'accesstoken'      => '',
				'user'             => isset( $db['sb_instagram_user'] ) ? $db['sb_instagram_user'] : false,
				'feedid'           => isset( $db['sb_instagram_feed_id'] ) ? $db['sb_instagram_feed_id'] : false,
				'resizeprocess'    => isset( $db['sb_instagram_resizeprocess'] ) ? $db['sb_instagram_resizeprocess'] : 'background',
				'customtemplates'    => isset( $db['custom_template'] ) ? $db['custom_template'] : '',

			), $atts );

		$this->settings['customtemplates'] = $this->settings['customtemplates'] === 'true' || $this->settings['customtemplates'] === 'on';
		if ( isset( $_GET['sbi_debug'] ) ) {
			$this->settings['customtemplates'] = false;
		}
		$this->settings['minnum'] = max( (int)$this->settings['num'], (int)$this->settings['nummobile'] );
		$this->settings['showbio'] = $this->settings['showbio'] === 'true' || $this->settings['showbio'] === 'on' || $this->settings['showbio'] === true;
		if ( isset( $atts['showbio'] ) && $atts['showbio'] === 'false' ) {
			$this->settings['showbio'] = false;
		}
		if ( isset( $atts['showheader'] ) && $atts['showheader'] === 'false' ) {
			$this->settings['showheader'] = false;
		}
		$this->settings['disable_resize'] = isset( $db['sb_instagram_disable_resize'] ) && ($db['sb_instagram_disable_resize'] === 'on');
		$this->settings['favor_local'] = isset( $db['sb_instagram_favor_local'] ) && ($db['sb_instagram_favor_local'] === 'on');
		$this->settings['backup_cache_enabled'] = ! isset( $db['sb_instagram_backup'] ) || ($db['sb_instagram_backup'] === 'on');
		$this->settings['font_method'] = isset( $db['sbi_font_method'] ) ? $db['sbi_font_method'] : 'svg';
		$this->settings['headeroutside'] = ($this->settings['headeroutside'] === true || $this->settings['headeroutside'] === 'on' || $this->settings['headeroutside'] === 'true');
		$this->settings['disable_js_image_loading'] = isset( $db['disable_js_image_loading'] ) && ($db['disable_js_image_loading'] === 'on');
		$this->settings['ajax_post_load'] = isset( $db['sb_ajax_initial'] ) && ($db['sb_ajax_initial'] === 'on');

		switch ( $db['sbi_cache_cron_interval'] ) {
			case '30mins' :
				$this->settings['sbi_cache_cron_interval'] = 60*30;
				break;
			case '1hour' :
				$this->settings['sbi_cache_cron_interval'] = 60*60;
				break;
			default :
				$this->settings['sbi_cache_cron_interval'] = 60*60*12;
		}

		$this->settings['sb_instagram_cache_time'] = isset( $this->db['sb_instagram_cache_time'] ) ? $this->db['sb_instagram_cache_time'] : 1;
		$this->settings['sb_instagram_cache_time_unit'] = isset( $this->db['sb_instagram_cache_time_unit'] ) ? $this->db['sb_instagram_cache_time_unit'] : 'hours';

		global $sb_instagram_posts_manager;

		if ( $sb_instagram_posts_manager->are_current_api_request_delays() ) {
			$this->settings['alwaysUseBackup'] = true;
		}

		$this->settings['isgutenberg'] = SB_Instagram_Blocks::is_gb_editor();
		if ( $this->settings['isgutenberg'] ) {
			$this->settings['ajax_post_load'] = false;
			$this->settings['disable_js_image_loading'] = true;
		}
	}

	/**
	 * @return array
	 *
	 * @since 2.0/5.0
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * The plugin will output settings on the frontend for debugging purposes.
	 * Safe settings to display are added here.
	 *
	 * Overwritten in the Pro version.
	 *
	 * @return array
	 *
	 * @since 2.0/5.0
	 */
	public static function get_public_db_settings_keys() {
		$public = array(
			'sb_instagram_user_id',
			'sb_instagram_cache_time',
			'sb_instagram_cache_time_unit',
			'sbi_caching_type',
			'sbi_cache_cron_interval',
			'sbi_cache_cron_time',
			'sbi_cache_cron_am_pm',
			'sb_instagram_width',
			'sb_instagram_width_unit',
			'sb_instagram_feed_width_resp',
			'sb_instagram_height',
			'sb_instagram_num',
			'sb_instagram_height_unit',
			'sb_instagram_cols',
			'sb_instagram_disable_mobile',
			'sb_instagram_image_padding',
			'sb_instagram_image_padding_unit',
			'sb_instagram_sort',
			'sb_instagram_background',
			'sb_instagram_show_btn',
			'sb_instagram_btn_background',
			'sb_instagram_btn_text_color',
			'sb_instagram_btn_text',
			'sb_instagram_image_res',
			//Header
			'sb_instagram_show_header',
			'sb_instagram_header_size',
			'sb_instagram_header_color',
			//Follow button
			'sb_instagram_show_follow_btn',
			'sb_instagram_folow_btn_background',
			'sb_instagram_follow_btn_text_color',
			'sb_instagram_follow_btn_text',
			//Misc
			'sb_instagram_cron',
			'sb_instagram_backup',
			'sb_instagram_ajax_theme',
			'sb_instagram_disable_resize',
			'disable_js_image_loading',
			'enqueue_js_in_head',
			'sbi_font_method',
			'sb_instagram_disable_awesome',
			'sb_ajax_initial',
			'use_custom'
		);

		return $public;
	}

	/**
	 * @return array
	 *
	 * @since 2.0/5.0
	 */
	public function get_connected_accounts() {
		return $this->connected_accounts;
	}

	/**
	 * @return array|bool
	 *
	 * @since 2.0/5.0
	 */
	public function get_connected_accounts_in_feed() {
		if ( isset( $this->connected_accounts_in_feed ) ) {
			return $this->connected_accounts_in_feed;
		} else {
			return false;
		}
	}

	/**
	 * @return bool|string
	 *
	 * @since 2.0/5.0
	 */
	public function get_transient_name() {
		if ( isset( $this->transient_name ) ) {
			return $this->transient_name;
		} else {
			return false;
		}
	}

	/**
	 * Uses the feed types and terms as well as as some
	 * settings to create a semi-unique feed id used for
	 * caching and other features.
	 *
	 * Overwritten in the Pro version.
	 *
	 * @param string $transient_name
	 *
	 * @since 2.0/5.0
	 */
	public function set_transient_name( $transient_name = '' ) {

		if ( ! empty( $transient_name ) ) {
			$this->transient_name = $transient_name;
		} elseif ( ! empty( $this->settings['feedid'] ) ) {
			$this->transient_name = 'sbi_' . $this->settings['feedid'];
		} else {
			$feed_type_and_terms = $this->feed_type_and_terms;

			$sbi_transient_name = 'sbi_';

			if ( isset( $feed_type_and_terms['users'] ) ) {
				foreach ( $feed_type_and_terms['users'] as $term_and_params ) {
					$user = $term_and_params['term'];
					$connected_account = $this->connected_accounts_in_feed[ $user ];
					if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
						$sbi_transient_name .= $connected_account['username'];
					} else {
						$sbi_transient_name .= $user;
					}
				}
			}

			$num = $this->settings['num'];

			$num_length = strlen( $num ) + 1;

			//Add both parts of the caching string together and make sure it doesn't exceed 45
			$sbi_transient_name = substr( $sbi_transient_name, 0, 45 - $num_length );

			$sbi_transient_name .= '#' . $num;

			$this->transient_name = $sbi_transient_name;
		}

	}

	/**
	 * @return array|bool
	 *
	 * @since 2.0/5.0
	 */
	public function get_feed_type_and_terms() {
		if ( isset( $this->feed_type_and_terms ) ) {
			return $this->feed_type_and_terms;
		} else {
			return false;
		}
	}

	/**
	 * Based on the settings related to retrieving post data from the API,
	 * this setting is used to make sure all endpoints needed for the feed are
	 * connected and stored for easily looping through when adding posts
	 *
	 * Overwritten in the Pro version.
	 *
	 * @since 2.0/5.0
	 */
	public function set_feed_type_and_terms() {
		global $sb_instagram_posts_manager;

		$connected_accounts_in_feed = array();
		$feed_type_and_terms = array(
			'users' => array()
		);
		$usernames_included = array();
		$is_after_deprecation_deadline = sbi_is_after_deprecation_deadline();
		$is_using_access_token_in_shortcode = ! empty( $this->atts['accesstoken'] ) && strpos( $this->atts['accesstoken'], '.' ) !== false;
		$users_connected_to_old_api_only = array();
		$settings_link = '<a href="'.get_admin_url().'?page=sb-instagram-feed" target="_blank">' . __( 'plugin Settings page', 'instagram-feed' ) . '</a>';

		// if using an access token in the shortcode and after the deadline, try to use a connected account by collecting the user IDs
		if ( $is_after_deprecation_deadline && $is_using_access_token_in_shortcode ) {
			$error = '<p><b>' . __( 'Error: Cannot add access token directly to the shortcode.', 'instagram-feed' ) . '</b><br>' . sprintf( __( 'Due to recent Instagram platform changes, it\'s no longer possible to create a feed by adding the access token to the shortcode. Remove the access token from the shortcode and connect an account on the %s instead.', 'instagram-feed' ), $settings_link );

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );

			$this->settings['id'] = array();
			$access_tokens = explode( ',', str_replace( ' ', '', $this->atts['accesstoken'] ) );

			foreach ( $access_tokens as $access_token ) {
				$split_token = explode( '.', $access_token );
				$this->settings['id'][] = $split_token[0];
			}
		}

		if ( ! $is_after_deprecation_deadline && $is_using_access_token_in_shortcode ) {
			$error = '<p><b>' . __( 'Warning: Cannot add access token directly to the shortcode.', 'instagram-feed' ) . '</b><br>' . sprintf( __( 'Due to upcoming Instagram platform changes on March 31, 2020, it will no longer be possible for feeds to use access tokens directly in the shortcode. Remove the access token from the shortcode and connect an account on the %s instead.', 'instagram-feed' ), $settings_link );

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );
			$access_tokens = explode( ',', str_replace( ' ', '', $this->atts['accesstoken'] ) );

			foreach ( $access_tokens as $access_token ) {
				$split_token = explode( '.', $access_token );
				$connected_accounts_in_feed[ $split_token[0] ] = array(
					'access_token' => $access_token,
					'user_id' => $split_token[0]
				);
				$feed_type_and_terms['users'][] = array(
					'term' => $split_token[0],
					'params' => array()
				);
			}

		} elseif ( ! empty( $this->settings['user'] ) ) {
			$user_array = is_array( $this->settings['user'] ) ? $this->settings['user'] : explode( ',', str_replace( ' ', '',  $this->settings['user'] ) );
			foreach ( $user_array as $user ) {
				$user_found = false;
				$user_for_deprecated_personal_account_only_found = false;
				$term_for_this_user = array();
				$username_to_match = $user;

				if ( isset( $this->connected_accounts[ $user ] ) ) {
					if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
						$term_for_this_user = array(
							'term' => $this->connected_accounts[ $user ]['user_id'],
							'params' => array()
						);
						$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
						$usernames_included[] = $this->connected_accounts[ $user ]['username'];
						$username_to_match = $this->connected_accounts[ $user ]['username'];
						$user_found = true;
						if ( ! isset( $this->connected_accounts[ $user ]['type'] ) || $this->connected_accounts[ $user ]['type'] === 'personal' ) {
							$user_for_deprecated_personal_account_only_found = true;
						}
					}
				}

				if ( ! $user_found || $user_for_deprecated_personal_account_only_found ) {
					foreach ( $this->connected_accounts as $connected_account ) {
						$account_type = isset( $connected_account['type'] ) ? $connected_account['type'] : 'personal';
						if ( strtolower( $username_to_match ) === strtolower( $connected_account['username'] ) ) {
							if ( $user_for_deprecated_personal_account_only_found || ! in_array( $connected_account['username'], $usernames_included, true ) ) {
								if ( $account_type !== 'personal' ) {
									$term_for_this_user      = array(
										'term' => $user,
										'params' => array()
									);
									$connected_accounts_in_feed[ $user ] = $connected_account;
									$user_for_deprecated_personal_account_only_found = false;
								} else {
									$term_for_this_user                              = array(
										'term' => $connected_account['user_id'],
										'params' => array()
									);
									$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
								}
								$usernames_included[] = $connected_account['username'];
								$user_found = true;
							}
						}
					}
				}

				if ( ! empty( $term_for_this_user ) ) {
					$feed_type_and_terms['users'][] = $term_for_this_user;
				}

				if ( ! $user_found ) {
					$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s.', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

					$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
				}

				if ( $user_for_deprecated_personal_account_only_found
				     && ! in_array( $user, $users_connected_to_old_api_only,  true ) ) {
					$users_connected_to_old_api_only[] = $connected_accounts_in_feed[ $user ]['username'];
				}

			}

		} elseif ( ! empty( $this->settings['id'] ) ) {
			$user_id_array = is_array( $this->settings['id'] ) ? $this->settings['id'] : explode( ',', str_replace( ' ', '',  $this->settings['id'] ) );

			foreach ( $user_id_array as $user ) {
				$user_found = false;
				$user_for_deprecated_personal_account_only_found = false;
				$term_for_this_user = array();
				$username_to_match = '';

				if ( isset( $this->connected_accounts[ $user ] ) ) {
					if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
						$term_for_this_user                                              = array(
							'term' => $this->connected_accounts[ $user ]['user_id'],
							'params' => array()
						);
						$connected_accounts_in_feed[ $this->connected_accounts[ $user ]['user_id'] ] = $this->connected_accounts[ $user ];
						if ( ! in_array( $this->connected_accounts[ $user ]['username'], $usernames_included, true ) ) {
							$usernames_included[] = $this->connected_accounts[ $user ]['username'];
						}
						$username_to_match = $this->connected_accounts[ $user ]['username'];
						$user_found = true;
						if ( ! isset( $this->connected_accounts[ $user ]['type'] ) || $this->connected_accounts[ $user ]['type'] === 'personal' ) {
							$user_for_deprecated_personal_account_only_found = true;
						}
					}

				}

				if ( ! $user_found || $user_for_deprecated_personal_account_only_found ) {

					foreach ( $this->connected_accounts as $connected_account ) {
						$account_type = isset( $connected_account['type'] ) ? $connected_account['type'] : 'personal';
						$old_id_matches = ($account_type === 'basic' && isset( $connected_account['old_user_id'] ) && (string)$connected_account['old_user_id'] === (string)$user);
						if ( $old_id_matches
						     || (strtolower( $username_to_match ) === strtolower( $connected_account['username'] )) ) {
							if ( $user_for_deprecated_personal_account_only_found || ! in_array( $connected_account['username'], $usernames_included, true ) ) {
								if ( $account_type !== 'personal' ) {
									$term_for_this_user      = array(
										'term' => $user,
										'params' => array()
									);
									$connected_accounts_in_feed[ $user ] = $connected_account;
									$user_for_deprecated_personal_account_only_found = false;
								} else {
									$term_for_this_user                              = array(
										'term' => $connected_account['user_id'],
										'params' => array()
									);
									$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
								}
								if ( ! in_array( $connected_account['username'], $usernames_included, true ) ) {
									$usernames_included[] = $connected_account['username'];
								}
								$user_found = true;
							}
						}
					}

				}

				if ( ! empty( $term_for_this_user ) ) {
					$feed_type_and_terms['users'][] = $term_for_this_user;
				}

				if ( ! $user_found ) {
					$error = '<p><b>' . sprintf( __( 'Error: There is no connected account for the user %s', 'instagram-feed' ), $user ) . ' ' . __( 'Feed will not update.', 'instagram-feed' ) . '</b>';

					$sb_instagram_posts_manager->add_frontend_error( 'no_connection_' . $user, $error );
				}

				if ( $user_for_deprecated_personal_account_only_found
				     && ! in_array( $user, $users_connected_to_old_api_only,  true ) ) {
					$users_connected_to_old_api_only[] = $connected_accounts_in_feed[ $user ]['username'];
				}

			}

		} else {
			foreach ( $this->connected_accounts as $connected_account ) {
				$account_type = isset( $connected_account['type'] ) ? $connected_account['type'] : 'personal';

				if ( empty( $feed_type_and_terms['users'] ) ) {
					if ( $account_type !== 'personal' ) {
						$feed_type_and_terms['users'][]      = array(
							'term' => $connected_account['username'],
							'params' => array()
						);
						$connected_accounts_in_feed[ $connected_account['username'] ] = $connected_account;
					} elseif ( ! $is_after_deprecation_deadline ) {
						$feed_type_and_terms['users'][]                              = array(
							'term' => $connected_account['user_id'],
							'params' => array()
						);
						$connected_accounts_in_feed[ $connected_account['user_id'] ] = $connected_account;
					}
				}

			}
		}

		if ( ! empty( $users_connected_to_old_api_only ) ) {
			$total = count( $users_connected_to_old_api_only );
			if ( $total > 1 ) {
				$user_string = '';
				$i = 0;

				foreach ( $users_connected_to_old_api_only as $username ) {
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
				$user_string = $users_connected_to_old_api_only[0];
			}

			if ( $is_after_deprecation_deadline ) {
				$error = '<p><b>' . sprintf( __( 'Error: The account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to recent Instagram platform changes this Instagram account needs to be reconnected in order to continue updating.', 'instagram-feed' ) . '<a href="'.get_admin_url().'?page=sb-instagram-feed" class="sb_frontend_btn"><i class="fa fa-cog" aria-hidden="true"></i> ' . __( 'Reconnect on plugin Settings page', 'instagram-feed' ) . '</a>';
			} else {
				$error = '<p><b>' . sprintf( __( 'Warning: The account for %s needs to be reconnected.', 'instagram-feed' ), '<em>'.$user_string.'</em>' ) . '</b><br>' . __( 'Due to Instagram platform changes on March 31, 2020, this Instagram account needs to be reconnected to allow the feed to continue updating.', 'instagram-feed' ) . '<a href="'.get_admin_url().'?page=sb-instagram-feed" class="sb_frontend_btn"><i class="fa fa-cog" aria-hidden="true"></i> ' . __( 'Reconnect on plugin Settings page', 'instagram-feed' ) . '</a>';
			}

			$sb_instagram_posts_manager->add_frontend_error( 'deprecation_warning', $error );
		}

		$this->connected_accounts_in_feed = $connected_accounts_in_feed;
		$this->feed_type_and_terms = $feed_type_and_terms;
	}

	/**
	 * @return float|int
	 *
	 * @since 2.0/5.0
	 */
	public function get_cache_time_in_seconds() {
		if ( $this->db['sbi_caching_type'] === 'background' ) {
			return SBI_CRON_UPDATE_CACHE_TIME;
		} else {
			//If the caching time doesn't exist in the database then set it to be 1 hour
			$cache_time = isset( $this->settings['sb_instagram_cache_time'] ) ? (int)$this->settings['sb_instagram_cache_time'] : 1;
			$cache_time_unit = isset( $this->settings['sb_instagram_cache_time_unit'] ) ? $this->settings['sb_instagram_cache_time_unit'] : 'hours';

			//Calculate the cache time in seconds
			if ( $cache_time_unit == 'minutes' ) $cache_time_unit = 60;
			if ( $cache_time_unit == 'hours' ) $cache_time_unit = 60*60;
			if ( $cache_time_unit == 'days' ) $cache_time_unit = 60*60*24;

			return $cache_time * $cache_time_unit;
		}
	}
}