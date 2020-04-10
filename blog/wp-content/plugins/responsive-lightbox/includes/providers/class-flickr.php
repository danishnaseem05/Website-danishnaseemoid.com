<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Responsive Lightbox Remote Library Flickr class.
 *
 * @class Responsive_Lightbox_Remote_Library_Flickr
 */
class Responsive_Lightbox_Remote_Library_Flickr extends Responsive_Lightbox_Remote_Library_API {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		// provider slug
		$this->slug = 'flickr';

		// provider name
		$this->name = __( 'Flickr', 'responsive-lightbox' );

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
		<p><label class="cb-checkbox"><input id="rl_flickr_active" class="rl-media-provider-expandable" type="checkbox" name="responsive_lightbox_remote_library[flickr][active]" value="1" ' . checked( $this->rl->options['remote_library']['flickr']['active'], true, false ) . ' />' . __( 'Enable Flickr.', 'responsive-lightbox' ) . '</label></p>
		<div class="rl-media-provider-options"' . ( $this->rl->options['remote_library']['flickr']['active'] ? '' : ' style="display: none;"' ) . '>
			<p><input id="rl_flickr_api_key" class="large-text" placeholder="' . __( 'API key', 'responsive-lightbox' ) . '" type="text" value="' . $this->rl->options['remote_library']['flickr']['api_key'] . '" name="responsive_lightbox_remote_library[flickr][api_key]"></p>
			<p class="description">' . sprintf( __( 'Provide your <a href="%s">Flickr API key</a>.', 'responsive-lightbox' ), 'https://www.flickr.com/services/apps/create/' ) . '</p>
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
			$input['flickr'] = $this->rl->defaults['remote_library']['flickr'];
		else {
			// active
			$input['flickr']['active'] = isset( $_POST['responsive_lightbox_remote_library']['flickr']['active'] );

			// api key
			$input['flickr']['api_key'] = preg_replace( '/[^0-9a-zA-Z\-.]/', '', $_POST['responsive_lightbox_remote_library']['flickr']['api_key'] );
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

		if ( $args['preview_per_page'] < 5 || $args['preview_per_page'] > 500 )
			$args['preview_per_page'] = 20;

		// set query arguments
		$this->query_args = $args;

		$query_args = array(
			'api_key'	=> $this->rl->options['remote_library']['flickr']['api_key'],
			'extras'	=> 'owner_name,url_sq,url_t,url_s,url_q,url_m,url_n,url_z,url_c,url_l,url_o,description,tags',
			'per_page'	=> $args['preview_per_page'],
			'page'		=> $args['preview_page'],
			'method'	=> 'flickr.photos.getRecent',
			'format'	=> 'json'
		);

		if ( $search_phrase !== '' ) {
			$query_args['content_type'] = 1;
			$query_args['method'] = 'flickr.photos.search';
			$query_args['text'] = urlencode( $search_phrase );
			$query_args['sort'] = 'date-posted-desc';
		}

		// set query string
		$this->query = add_query_arg( $query_args, 'https://api.flickr.com/services/rest/' );

		// set query remote arguments
		$this->query_remote_args = array(
			'timeout'	=> 30,
			'headers'	=> array(
				'User-Agent' => __( 'Responsive Lightbox', 'responsive-lightbox' ) . ' ' . $this->rl->defaults['version']
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
		$error = new WP_Error( 'rl_remote_library_flickr_get_query_results', __( 'Parsing request error', 'responsive-lightbox' ) );

		// retrieve body
		$response_body = wp_remote_retrieve_body( $response );

		// check for flickr string
		if ( strpos( $response_body, 'jsonFlickrApi(' ) === 0 )
			$response_body = substr( $response_body, 14, -1 );

		// any data?
		if ( $response_body !== '' ) {
			$response_json = json_decode( $response_body, true );

			// invalid data?
			if ( $response_json === null || ( isset( $response_json['stat'] ) && $response_json['stat'] === 'fail' ) )
				$results = $error;
			else {
				// get results
				$results = isset( $response_json['photos'] ) && is_array( $response_json['photos'] ) && isset( $response_json['photos']['photo'] ) && is_array( $response_json['photos']['photo'] ) ? $response_json['photos']['photo'] : array();

				// sanitize results
				$results = $this->sanitize_results( $results );
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
		// original size exists?
		if ( isset( $result['url_o'] ) )
			$large = array( $result['url_o'], $result['width_o'], $result['height_o'] );
		// large 2048 size exists?
		elseif ( isset( $result['url_k'] ) )
			$large = array( $result['url_k'], $result['width_k'], $result['height_k'] );
		// large 1600 size exists?
		elseif ( isset( $result['url_h'] ) )
			$large = array( $result['url_h'], $result['width_h'], $result['height_h'] );
		// large 1024 size exists?
		elseif ( isset( $result['url_l'] ) )
			$large = array( $result['url_l'], $result['width_l'], $result['height_l'] );
		// medium 800 size exists?
		elseif ( isset( $result['url_c'] ) )
			$large = array( $result['url_c'], $result['width_c'], $result['height_c'] );
		// medium 640 size exists?
		elseif ( isset( $result['url_z'] ) )
			$large = array( $result['url_z'], $result['width_z'], $result['height_z'] );
		// medium 500 size exists?
		elseif ( isset( $result['url_m'] ) )
			$large = array( $result['url_m'], $result['width_m'], $result['height_m'] );
		// small 320 size exists?
		elseif ( isset( $result['url_n'] ) )
			$large = array( $result['url_n'], $result['width_n'], $result['height_n'] );
		// small 240 size exists?
		elseif ( isset( $result['url_s'] ) )
			$large = array( $result['url_s'], $result['width_s'], $result['height_s'] );
		// thumbnail size exists?
		elseif ( isset( $result['url_t'] ) )
			$large = array( $result['url_t'], $result['width_t'], $result['height_t'] );
		// skip this photo
		else
			return false;

		// large square size exists?
		if ( isset( $result['url_q'] ) )
			$small = array( $result['url_q'], $result['width_q'], $result['height_q'] );
		// square size exists?
		elseif ( isset( $result['url_sq'] ) )
			$small = array( $result['url_sq'], $result['width_sq'], $result['height_sq'] );
		// skip this photo
		else
			return false;

		$source = 'https://www.flickr.com/photos/' . $result['owner'] . '/' . $result['id'];

		$imagedata = array(
			'id'				=> 0,
			'link'				=> '',
			'source'			=> $source,
			'title'				=> $result['title'],
			'caption'			=> $this->get_attribution( 'Flickr', $source, $result['ownername'], 'https://www.flickr.com/photos/' . $result['owner'] ),
			'description'		=> $result['description']['_content'],
			'alt'				=> $result['tags'],
			'url'				=> $large[0],
			'width'				=> $large[1],
			'height'			=> $large[2],
			'thumbnail_url'		=> $small[0],
			'thumbnail_width'	=> $small[1],
			'thumbnail_height'	=> $small[2],
			'media_provider'	=> 'flickr',
			'filename'			=> basename( $large[0] ),
			'dimensions'		=> $large[1] . ' x ' . $large[2]
		);

		// thumbnail link does not exist?
		$imagedata['thumbnail_link'] = $this->rl->galleries->get_gallery_image_link( $imagedata, 'thumbnail' );

		return $imagedata;
	}
}

new Responsive_Lightbox_Remote_Library_Flickr();