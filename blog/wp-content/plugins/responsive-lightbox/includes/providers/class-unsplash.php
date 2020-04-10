<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Responsive Lightbox Remote Library Unsplash class.
 *
 * @class Responsive_Lightbox_Remote_Library_Unsplash
 */
class Responsive_Lightbox_Remote_Library_Unsplash extends Responsive_Lightbox_Remote_Library_API {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		// provider slug
		$this->slug = 'unsplash';

		// provider name
		$this->name = __( 'Unsplash', 'responsive-lightbox' );

		// default values
		$this->defaults = array(
			'active'	=> false,
			'api_key'	=> ''
		);

		// setting fields
		$this->fields = array(
			'title'		=> $this->name,
			'section'	=> 'responsive_lightbox_remote_library_providers',
			'type'		=> 'custom',
			'callback'	=> array( $this, 'render_field' )
		);

		// add provider
		parent::add_provider( $this );
	}

	/**
	 * Render field.
	 *
	 * @return void
	 */
	public function render_field() {
		echo '
		<p><label class="cb-checkbox"><input id="rl_unsplash_active" class="rl-media-provider-expandable" type="checkbox" name="responsive_lightbox_remote_library[unsplash][active]" value="1" ' . checked( $this->rl->options['remote_library']['unsplash']['active'], true, false ) . ' />' . __( 'Enable Unsplash.', 'responsive-lightbox' ) . '</label></p>
		<div class="rl-media-provider-options"' . ( $this->rl->options['remote_library']['unsplash']['active'] ? '' : ' style="display: none;"' ) . '>
			<p><input id="rl_unsplash_api_key" class="large-text" placeholder="' . __( 'Access key', 'responsive-lightbox' ) . '" type="text" value="' . $this->rl->options['remote_library']['unsplash']['api_key'] . '" name="responsive_lightbox_remote_library[unsplash][api_key]"></p>
			<p class="description">' . sprintf( __( 'Provide your <a href="%s">Unsplash API key</a>.', 'responsive-lightbox' ), 'https://unsplash.com/oauth/applications/new' ) . '</p>
		</div>';
	}

	/**
	 * Validate settings.
	 *
	 * @param array $input POST data
	 * @return array Validated settings
	 */
	public function validate_settings( $input ) {
		if ( ! isset( $_POST['responsive_lightbox_remote_library'] ) )
			$input['unsplash'] = $this->rl->defaults['remote_library']['unsplash'];
		else {
			// active
			$input['unsplash']['active'] = isset( $_POST['responsive_lightbox_remote_library']['unsplash']['active'] );

			// api key
			$input['unsplash']['api_key'] = preg_replace( '/[^0-9a-zA-Z\-.]/', '', $_POST['responsive_lightbox_remote_library']['unsplash']['api_key'] );
		}

		return $input;
	}

	/**
	 * Prepare data to run remote query.
	 *
	 * @param string $search_phrase Search phrase
	 * @param array $args Provider arguments
	 * @return void
	 */
	public function prepare_query( $search_phrase, $args = array() ) {
		// check page parameter
		if ( isset( $args['preview_page'] ) )
			$args['preview_page'] = (int) $args['preview_page'];
		else
			$args['preview_page'] = 1;

		if ( $args['preview_page'] < 1 )
			$args['preview_page'] = 1;

		// check per page parameter
		if ( isset( $args['preview_per_page'] ) )
			$args['preview_per_page'] = (int) $args['preview_per_page'];
		else
			$args['preview_per_page'] = 20;

		if ( $args['preview_per_page'] < 5 || $args['preview_per_page'] > 30 )
			$args['preview_per_page'] = 20;

		// set query arguments
		$this->query_args = $args;

		$query_args = array(
			'per_page'	=> $args['preview_per_page'],
			'page'		=> $args['preview_page'],
			'order_by'	=> 'latest'
		);

		if ( $search_phrase !== '' ) {
			$query_args['query'] = urlencode( $search_phrase );

			$url = 'https://api.unsplash.com/search/photos';
		} else
			$url = 'https://api.unsplash.com/photos';

		// set query string
		$this->query = add_query_arg( $query_args, $url );

		// set query remote arguments
		$this->query_remote_args = array(
			'timeout'	=> 30,
			'headers'	=> array(
				'Authorization'	=> 'Client-ID ' . $this->rl->options['remote_library']['unsplash']['api_key'],
				'User-Agent'	=> __( 'Responsive Lightbox', 'responsive-lightbox' ) . ' ' . $this->rl->defaults['version']
			)
		);
	}

	/**
	 * Get images from media provider.
	 *
	 * @param mixed $response Remote response
	 * @param array $args Query arguments
	 * @return array Valid images or WP_Error
	 */
	public function get_query_results( $response, $args = array() ) {
		$results = array();
		$error = new WP_Error( 'rl_remote_library_unsplash_get_query_results', __( 'Parsing request error', 'responsive-lightbox' ) );

		// retrieve body
		$response_body = wp_remote_retrieve_body( $response );

		// any data?
		if ( $response_body !== '' ) {
			$response_json = json_decode( $response_body, true );

			// invalid data?
			if ( $response_json === null )
				$results = $error;
			else {
				// search phrase query?
				if ( $args['media_search'] !== '' ) {
					// get results
					$results = isset( $response_json['results'] ) && is_array( $response_json['results'] ) ? $response_json['results'] : array();

					// sanitize results
					$results = $this->sanitize_results( $results );
				} else
					$results = $this->sanitize_results( $response_json );
			}
		} else
			$results = $error;

		return $results;
	}

	/**
	 * Sanitize single result.
	 *
	 * @param array $result Single result
	 * @return mixed Array on success, otherwise false
	 */
	public function sanitize_result( $result ) {
		// set dimensions
		$width = (int) $result['width'];
		$height = (int) $result['height'];
		$thumbnail_width = 200;

		// calculate new height based on original ratio
		$thumbnail_height = (int) floor( $thumbnail_width / ( $width / $height ) );

		$imagedata = array(
			'id'				=> 0,
			'link'				=> '',
			'source'			=> $result['links']['html'],
			'title'				=> $result['id'],
			'caption'			=> $this->get_attribution( 'Unsplash', $result['links']['html'], $result['user']['name'], $result['user']['links']['html'] ),
			'description'		=> ! empty( $result['description'] ) ? $result['description'] : '',
			'alt'				=> '',
			'url'				=> $result['urls']['raw'],
			'width'				=> $width,
			'height'			=> $height,
			'thumbnail_url'		=> $result['urls']['thumb'],
			'thumbnail_width'	=> $thumbnail_width,
			'thumbnail_height'	=> $thumbnail_height,
			'media_provider'	=> 'unsplash',
			'filename'			=> basename( $result['urls']['raw'] ),
			'dimensions'		=> $width . ' x ' . $height
		);

		// create thumbnail link
		$imagedata['thumbnail_link'] = $this->rl->galleries->get_gallery_image_link( $imagedata, 'thumbnail' );

		return $imagedata;
	}
}

new Responsive_Lightbox_Remote_Library_Unsplash();