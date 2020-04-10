<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Responsive Lightbox Remote Library class.
 *
 * @class Responsive_Lightbox_Remote_Library
 */
class Responsive_Lightbox_Remote_Library {

	public $providers = array();

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		// is remote library active?
		if ( ! Responsive_Lightbox()->options['remote_library']['active'] )
			return;

		// actions
		add_action( 'wp_ajax_rl_remote_library_query', array( $this, 'ajax_query_media' ) );
		add_action( 'wp_ajax_rl_upload_image', array( $this, 'ajax_upload_image' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'remote_library_scripts' ) );

		// add filter to send new data to editor
		add_filter( 'image_send_to_editor', array( $this, 'send_image_to_editor' ), 21, 8 );
	}

	/**
	 * Hidden field with response data for gallery preview.
	 *
	 * @param array $args Field arguments
	 * @return string Rendered field
	 */
	public function remote_library_response_data( $args ) {
		// access main instance
		$rl = Responsive_Lightbox();

		// get active providers
		$providers = $this->get_active_providers();

		$html = '';

		// any providers?
		if ( ! empty( $providers ) ) {
			foreach ( $providers as $provider ) {
				// get provider
				$provider = $rl->providers[$provider];

				// add response data arguments if needed
				if ( ! empty( $provider['response_args'] ) ) {
					$response = $provider['instance']->get_response_data();

					foreach ( $provider['response_args'] as $arg ) {
						if ( array_key_exists( $arg, $response ) ) {
							$html .= '<input id="rl_' . $args['tab_id'] . '_' . $args['menu_item'] . '_' . $args['field'] . '_' . $provider['slug'] . '_' . $arg . '" type="hidden" value="' . base64_encode( json_encode( $response[$arg] ) ) . '" name="rl_gallery[' . $args['tab_id'] . '][' . $args['menu_item'] . '][' . $args['field'] . '][' . $provider['slug'] . '][' . $arg . ']" data-previous="" data-subarg="2" />';
						}
					}
				}
			}
		}

		return $html;
	}

	/**
	 * Send updated image data to editor.
	 *
	 * @param string $html The image HTML markup to send
	 * @param int $id The attachment ID
	 * @param string $caption The image caption
	 * @param string $title The image title
	 * @param string $align The image alignment
	 * @param string $url The image source URL
	 * @param string|array $size Size of image
	 * @param string $alt The image alternative text.
	 * @return string Updated image HTML
	 */
	function send_image_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
		if ( $id === Responsive_Lightbox()->galleries->maybe_generate_thumbnail() && isset( $_POST['attachment'] ) ) {
			$attachment = wp_unslash( $_POST['attachment'] );

			if ( isset( $attachment['remote_library_image'], $attachment['width'], $attachment['height'] ) ) {
				$html = preg_replace( '/src=(\'|")(.*?)(\'|")/', 'src="' . $url . '"', $html );
				$html = preg_replace( '/width=(\'|")(.*?)(\'|")/', 'width="' . ( (int) $attachment['width'] ) . '"', $html );
				$html = preg_replace( '/height=(\'|")(.*?)(\'|")/', 'height="' . ( (int) $attachment['height'] ) . '"', $html );
				$html = preg_replace( '/(\s)?id="attachment_' . $id . '"/', '', $html );
				$html = preg_replace( '/(\s)?wp-image-' . $id . '/', '', $html );
			}
		}

		return $html;
	}

	/**
	 * Get all available providers.
	 *
	 * @return array Providers
	 */
	public function get_providers() {
		return apply_filters( 'rl_get_providers', Responsive_Lightbox()->providers );
	}

	/**
	 * Get all active providers.
	 *
	 * @return array Providers
	 */
	public function get_active_providers() {
		$providers = $this->get_providers();
		$active_providers = array();

		foreach ( $providers as $provider => $data ) {
			if ( Responsive_Lightbox()->options['remote_library'][$provider]['active'] ) {
				$active_providers[] = $provider;
			}
		}

		return apply_filters( 'rl_get_active_providers', $active_providers );
	}

	/**
	 * Check whether provider is active.
	 *
	 * @param string $provider Media provider
	 * @return bool
	 */
	public function is_active_provider( $provider ) {
		$providers = $this->get_providers();
		$rl = Responsive_Lightbox();

		return (bool) apply_filters( 'rl_is_active_provider', array_key_exists( $provider, $rl->options['remote_library'] ) && $rl->options['remote_library'][$provider]['active'], $provider );
	}

	/**
	 * Scripts and styles for media frame.
	 *
	 * @return void
	 */
	public function remote_library_scripts() {
		global $pagenow;

		// display only for post edit pages
		if ( ! ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) )
			return;

		// get main instance
		$rl = Responsive_Lightbox();

		wp_enqueue_script( 'rl-remote-library-media', RESPONSIVE_LIGHTBOX_URL . '/js/admin-media.js', array( 'jquery', 'media-models', 'underscore' ), $rl->defaults['version'] );

		wp_localize_script(
			'rl-remote-library-media',
			'rlRemoteLibraryMedia',
			array(
				'thumbnailID'			=> $rl->galleries->maybe_generate_thumbnail(),
				'postID'				=> get_the_ID(),
				'providers'				=> $this->get_providers(),
				'providersActive'		=> $this->get_active_providers(),
				'allProviders'			=> __( 'All providers', 'responsive-lightbox' ),
				'uploadAndInsert'		=> __( 'Upload and Insert', 'responsive-lightbox' ),
				'uploadAndSelect'		=> __( 'Upload and Select', 'responsive-lightbox' ),
				'filterByremoteLibrary'	=> __( 'Filter by remote library', 'responsive-lightbox' ),
				'getUploadNonce'			=> wp_create_nonce( 'rl-remote-library-upload-image' )
			)
		);

		// enqueue gallery
		$rl->galleries->enqueue_gallery_scripts_styles();
	}

	/**
	 * AJAX media query action.
	 *
	 * @return void
	 */
	public function ajax_query_media() {
		$data = stripslashes_deep( $_POST );
		$results = array(
			'last' => false,
			'images' => array(),
			'data' => array()
		);

		if ( isset( $data['media_provider'], $data['media_search'], $data['media_page'] ) && ( $data['media_provider'] === 'all' || $this->is_active_provider( $data['media_provider'] ) ) ) {
			$data['preview_page'] = (int) $data['media_page'];
			$data['preview_per_page'] = 20;

			// get images
			$results['images'] = $this->get_remote_library_images( $data );

			// get main instance
			$rl = Responsive_Lightbox();

			// single provider?
			if ( $data['media_provider'] !== 'all' ) {
				// get provider
				$provider = $rl->providers[$data['media_provider']];

				// add response data arguments if needed
				if ( ! empty( $provider['response_args'] ) ) {
					$response = $provider['instance']->get_response_data();

					foreach ( $provider['response_args'] as $arg ) {
						if ( array_key_exists( $arg, $response ) )
							$results['data'][$provider['slug']][$arg] = base64_encode( json_encode( $response[$arg] ) );
					}
				}
			} else {
				// get active providers
				$providers = $this->get_active_providers();

				if ( ! empty( $providers ) ) {
					foreach ( $providers as $provider ) {
						// get provider
						$provider = $rl->providers[$provider];

						// add response data arguments if needed
						if ( ! empty( $provider['response_args'] ) ) {
							$response = $provider['instance']->get_response_data();

							foreach ( $provider['response_args'] as $arg ) {
								if ( array_key_exists( $arg, $response ) )
									$results['data'][$provider['slug']][$arg] = base64_encode( json_encode( $response[$arg] ) );
							}
						}
					}
				}
			}

			if ( ! empty( $results['images'] ) ) {
				// create WP compatible attachments
				$results['images'] = $this->create_wp_remote_attachments( $results['images'], $data );

				// handle last page if needed
				$results['last'] = apply_filters( 'rl_remote_library_query_last_page', false, $results, $data );
			} else
				$results['last'] = true;
		}

		// send JSON
		wp_send_json( $results );
	}

	/**
	 * AJAX upload image action.
	 *
	 * @return void
	 */
	public function ajax_upload_image() {
		$data = stripslashes_deep( $_POST );
		$new_data = array();

		// verified upload?
		if ( current_user_can( 'upload_files' ) && isset( $data['rlnonce'], $data['image'], $data['post_id'] ) && wp_verify_nonce( $data['rlnonce'], 'rl-remote-library-upload-image' ) ) {
			if ( ! function_exists( 'media_handle_upload' ) )
				require_once( path_join( ABSPATH, 'wp-admin/includes/media.php' ) );

			if ( ! function_exists( 'wp_handle_upload' ) )
				require_once( path_join( ABSPATH, 'wp-admin/includes/file.php' ) );

			if ( ! empty( $data['image']['url'] ) ) {
				// get image as binary data
				$response = wp_safe_remote_get( esc_url_raw( $data['image']['url'] ) );

				// get file name
				$file_name = basename( parse_url( $data['image']['name'], PHP_URL_PATH ) );

				// check extension
				$file_ext = strrpos( $file_name, '.' );

				// no extension?
				if ( $file_ext === false )
					$file_name .= '.jpg';

				// no errors?
				if ( ! is_wp_error( $response ) ) {
					$bits = wp_remote_retrieve_body( $response );
					$loaded = wp_upload_bits( $file_name, null, $bits, current_time( 'Y/m' ) );

					if ( isset( $loaded['error'] ) && $loaded['error'] ) {
						$results = array(
							'error'		 => true,
							'message'	 => $loaded['error']
						);
					} else {
						// simulate upload
						$_FILES['rl-remote-image'] = array(
							'error'		 => 0,
							'name'		 => $file_name,
							'tmp_name'	 => $loaded['file'],
							'size'		 => filesize( $loaded['file'] )
						);

						// get post ID
						$post_id = isset( $data['post_id'] ) ? (int) $data['post_id'] : 0;

						// upload image
						$attachment_id = media_handle_upload(
							'rl-remote-image',
							$post_id,
							array(
								'post_title'	 => $data['image']['title'],
								'post_content'	 => $data['image']['description'],
								'post_excerpt'	 => $data['image']['caption']
							), array(
								'action'	 => 'rl_remote_library_handle_upload',
								'test_form'	 => false
							)
						);

						// upload success?
						if ( ! is_wp_error( $attachment_id ) ) {
							add_post_meta( $attachment_id, '_wp_attachment_image_alt', $data['image']['alt'] );

							$new_data['id'] = $attachment_id;
							$new_data['full'] = wp_get_attachment_image_src( $attachment_id, 'full' );
						}
					}
				}
			}
		}

		// send JSON
		wp_send_json( $new_data );
	}

	/**
	 * Create WP compatible attachments for JavaScript.
	 *
	 * @param array $results Requested images
	 * @param array $args Additional arguments
	 * @return array Compatible attachments
	 */
	public function create_wp_remote_attachments( $results, $args ) {
		$user = wp_get_current_user();
		$copy = $results;
		$time = current_time( 'timestamp' );
		$date_format = get_option( 'date_format' );
		$date = date_i18n( __( 'F j Y' ), $time );

		foreach ( $results as $no => $result ) {
			// detect orientation
			$orientation = $result['width'] > $result['height'] ? 'landscape' : 'portrait';

			// make sure those attributes are strings
			$copy[$no]['caption'] = (string) $result['caption'];
			$copy[$no]['description'] = (string) $result['description'];
			$copy[$no]['title'] = (string) $result['title'];
			$copy[$no]['filename'] = $copy[$no]['name'] = (string) $result['filename'];

			// rest of attributes
			$copy[$no]['id'] = 'rl-attachment-' . ( ( $args['preview_page'] - 1 ) * $args['preview_per_page'] + $no ) . '-' . $args['media_provider'];
			$copy[$no]['remote_library_image'] = true;
			$copy[$no]['author'] = $user->ID;
			$copy[$no]['authorName'] = $user->user_login;
			$copy[$no]['can'] = array(
				'save' => true,
				'remove' => false
			);
			$copy[$no]['compat'] = '';
			$copy[$no]['date'] = $time;
			$copy[$no]['dateFormatted'] = $date;
			$copy[$no]['delete'] = '';
			$copy[$no]['edit'] = '';
			$copy[$no]['update'] = '';
			$copy[$no]['filesizeHumanReadable'] = '';
			$copy[$no]['filesizeInBytes'] = 0;
			$copy[$no]['icon'] = '';
			$copy[$no]['link'] = $result['url'];
			$copy[$no]['menuOrder'] = 0;
			$copy[$no]['meta'] = false;

			// check extension
			$file_ext = strrpos( basename( parse_url( $result['url'], PHP_URL_PATH ) ), '.' );

			// no extension?
			if ( $file_ext === false )
				$file_ext .= 'jpg';

			if ( $file_ext === 'png' || $file_ext === 'gif' ) {
				$copy[$no]['mime'] = 'image/' . $file_ext;
				$copy[$no]['subtype'] = $file_ext;
			} else {
				$copy[$no]['mime'] = 'image/jpeg';
				$copy[$no]['subtype'] = 'jpeg';
			}

			$copy[$no]['modified'] = $time;
			$copy[$no]['nonces'] = array(
				'delete'	=> '',
				'edit'	=> '',
				'update'	=> ''
			);
			$copy[$no]['orientation'] = $orientation;
			$copy[$no]['status'] = 'inherit';
			$copy[$no]['type'] = 'image';
			$copy[$no]['uploadedTo'] = 0;
			$copy[$no]['uploadedToLink'] = '';
			$copy[$no]['uploadedToTitle'] = '';
			$copy[$no]['sizes'] = array(
				// 'thumbnail' => array(
					// 'height'		=> $result['thumbnail_height'],
					// 'width'			=> $result['thumbnail_width'],
					// 'orientation'	=> $orientation,
					// 'url'			=> $result['thumbnail_url']
				// ),
				'medium' => array(
					'height'		=> $result['thumbnail_height'],
					'width'			=> $result['thumbnail_width'],
					'orientation'	=> $orientation,
					'url'			=> $result['thumbnail_url']
				),
				'full' => array(
					'height'		=> $result['height'],
					'width'			=> $result['width'],
					'orientation'	=> $orientation,
					'url'			=> $result['url']
				)
			);
		}

		return apply_filters( 'rl_remote_library_wp_attachments', $copy, $args );
	}

	/**
	 * Remote library media query.
	 *
	 * @param array $args Arguments
	 * @return array Images
	 */
	public function get_remote_library_images( $args ) {
		$args = stripslashes_deep( $args );

		// search phrase
		if ( isset( $args['media_search'] ) )
			$args['media_search'] = strtolower( trim( $args['media_search'] ) );
		else
			$args['media_search'] = '';

		// media provider
		if ( isset( $args['media_provider'] ) )
			$args['media_provider'] = trim( $args['media_provider'] );
		else
			$args['media_provider'] = 'all';

		// page number
		if ( isset( $args['preview_page'] ) )
			$args['preview_page'] = (int) $args['preview_page'];
		else
			$args['preview_page'] = 1;

		// number of images per page
		if ( isset( $args['preview_per_page'] ) )
			$args['preview_per_page'] = (int) $args['preview_per_page'];
		else
			$args['preview_per_page'] = 20;

		// get active providers
		$providers = $this->get_active_providers();

		// prepare valid providers
		$valid_providers = array();

		if ( $args['media_provider'] === 'all' )
			$valid_providers = $providers;
		elseif ( in_array( $args['media_provider'], $providers, true ) )
			$valid_providers[] = $args['media_provider'];

		$results = array();

		// any valid providers?
		if ( ! empty( $valid_providers ) ) {
			// get main instance
			$rl = Responsive_Lightbox();

			foreach ( $valid_providers as $provider_name ) {
				if ( ! empty( $args['response_data'][$provider_name] ) ) {
					// get provider
					$provider = $rl->providers[$provider_name];

					if ( ! empty( $provider['response_args'] ) ) {
						foreach ( $provider['response_args'] as $arg ) {
							if ( array_key_exists( $arg, $args['response_data'][$provider_name] ) ) {
								$base64 = base64_decode( $args['response_data'][$provider_name][$arg] );

								if ( ! empty( $base64 ) )
									$args['response_data'][$provider_name][$arg] = json_decode( $base64, true );
							}
						}
					}
				}

				$results = apply_filters( 'rl_remote_library_query', $results, $args['media_search'], $provider_name, $args );
				$nor = count( $results );

				// more than requested images?
				if ( $nor > $args['preview_per_page'] ) {
					// get part of images
					$results = array_slice( $results, 0, $args['preview_per_page'], true );

					break;
				// same amount of images?
				} elseif ( $nor === $args['preview_per_page'] )
					break;
			}
		}

		return $results;
	}
}