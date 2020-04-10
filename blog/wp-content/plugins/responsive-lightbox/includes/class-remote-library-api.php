<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Responsive Lightbox Remote Library API class.
 *
 * @class Responsive_Lightbox_Remote_Library_API
 */
abstract class Responsive_Lightbox_Remote_Library_API {

	protected $rl;
	protected $slug;
	protected $name;
	protected $defaults;
	protected $fields;
	protected $query;
	protected $query_type = 'get';
	protected $query_args = array();
	protected $query_remote_args = array();
	protected $response_data = array();
	protected $response_data_args = array();

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		// assign main instance
		$this->rl = Responsive_Lightbox();
	}

	/**
	 * Register media provider.
	 *
	 * @param object $provider Provider class object
	 * @return void
	 */
	public function add_provider( $provider ) {
		// update main instance
		$this->rl = Responsive_Lightbox();

		// add provider
		$this->rl->providers[$provider->slug] = array(
			'instance'		=> $provider,
			'slug'			=> ! empty( $provider->slug ) ? sanitize_title( $provider->slug ) : '',
			'name'			=> ! empty( $provider->name ) ? esc_html( $provider->name ) : '',
			'defaults'		=> ! empty( $provider->defaults ) && is_array( $provider->defaults ) ? $provider->defaults : array(),
			'fields'		=> ! empty( $provider->fields ) && is_array( $provider->fields ) ? $provider->fields : array(),
			'response_args' => ! empty( $provider->response_data_args ) && is_array( $provider->response_data_args ) ? $provider->response_data_args : array()
		);

		// add provider default values
		$this->rl->defaults['remote_library'][$provider->slug] = $this->rl->providers[$provider->slug]['defaults'];

		// add provider field
		$this->rl->settings->settings['remote_library']['fields'][$provider->slug] = $this->rl->providers[$provider->slug]['fields'];

		// validate provider settings
		add_filter( 'rl_remote_library_settings', array( $this, 'validate_settings' ) );

		// provider query
		add_filter( 'rl_remote_library_query', array( $this, 'get_images' ), 10, 4 );
	}

	/**
	 * Get response data.
	 *
	 * @param $subdata Subargument if needed
	 * @return mixed
	 */
	public function get_response_data( $subdata = '' ) {
		if ( ! empty( $subdata ) && array_key_exists( $subdata, $this->response_data ) )
			return $this->response_data[$subdata];
		else
			return $this->response_data;
	}

	/**
	 * Get images from specified provider.
	 *
	 * @param array $results Current results
	 * @param string $terms Search phrase
	 * @param string $provider Current media provider
	 * @param array $args Additional arguments
	 * @return array
	 */
	public function get_images( $results, $terms, $provider, $args = array() ) {
		if ( $provider === $this->slug ) {
			// make sure search phrase exists
			if ( ! array_key_exists( 'media_search', $args ) )
				$args['media_search'] = $terms;

			$new_results = apply_filters( 'rl_remote_library_api_get_provider_images', $this->get_results( $terms, $args ), $results, $args );

			// valid data? combine results
			if ( ! is_wp_error( $new_results ) && ! empty( $new_results ) )
				$results = array_merge( $results, $new_results );
		}

		return apply_filters( 'rl_remote_library_api_get_images', $results );
	}

	/**
	 * Get images from media provider.
	 *
	 * @param string $search_phrase Search phrase
	 * @param array $args Additional arguments
	 * @return array
	 */
	public function get_results( $search_phrase, $args = array() ) {
		// prepare data for remote query
		$this->prepare_query( $search_phrase, $args );

		// get query
		$query = apply_filters( 'rl_remote_library_api_query', $this->query, $this->query_args );

		if ( $this->rl->options['remote_library']['caching'] ) {
			// set transient name
			$transient_name = sha1( serialize( $query ) ) . sha1( serialize( $this->query_remote_args ) );

			// get remote query transient
			$transient = get_transient( $transient_name );
		} else
			$transient = false;

		// transient exists?
		if ( $transient !== false ) {
			// get cached results
			$results = $transient;
		} else {
			// run remote query
			if ( $this->query_type === 'post' )
				$response = wp_remote_post( $query, $this->query_remote_args );
			else
				$response = wp_remote_get( $query, $this->query_remote_args );

			// wp error?
			if ( is_wp_error( $response ) )
				$results = $response;
			// invalid response?
			elseif ( ! isset( $response['response']['code'], $response['response']['message'] ) || $response['response']['code'] !== 200 || $response['response']['message'] !== 'OK' )
				$results = new WP_Error( 'rl_remote_library_get_results', __( 'Request error', 'responsive-lightbox' ) );
			else {
				// get query results
				$results = apply_filters( 'rl_remote_library_api_get_query_results', $this->get_query_results( $response, $args ), $response, $args );

				// set transient for valid results
				if ( ! is_wp_error( $results ) && $this->rl->options['remote_library']['caching'] )
					set_transient( $transient_name, $results, (int) ( $this->rl->options['remote_library']['cache_expiry'] * 3600 ) );
			}
		}

		return apply_filters( 'rl_remote_library_api_get_results', $results, $args );
	}

	/**
	 * Validate settings.
	 *
	 * @param array $input POST data
	 */
	abstract public function validate_settings( $input );

	/**
	 * Sanitize all returned results.
	 *
	 * @param array $results Results from media provider request
	 * @return array
	 */
	public function sanitize_results( $results ) {
		return is_array( $results ) ? array_filter( array_map( array( $this, 'sanitize_result' ), $results ) ) : array();
	}

	/**
	 * Sanitize single result.
	 *
	 * @param array $result Single result
	 */
	abstract public function sanitize_result( $result );

	/**
	 * Create attribution.
	 *
	 * @param string $name Image name
	 * @param string $link Image URL
	 * @param string $user_name User name
	 * @param string $user_link User URL
	 * @return string 
	 */
	public function get_attribution( $name, $link = null, $user_name = null, $user_link = null ) {
		if ( empty( $link ) )
			$source_text = sprintf( __( 'Image from %s', 'responsive-lightbox' ), esc_html( $name ) );
		else
			$source_text = sprintf( __( 'Image from <a href="%s" target="_blank">%s</a>', 'responsive-lightbox' ), esc_url( $link ), esc_html( $name ) );

		if ( empty( $user_name ) && empty( $user_link ) )
			$user_text = '';
		elseif ( empty( $user_link ) )
			$user_text = sprintf( __( 'via %s', 'responsive-lightbox' ), esc_html( $user_name ) );
		else
			$user_text = sprintf( __( 'via <a href="%s" target="_blank">%s</a>', 'responsive-lightbox' ), esc_url( $user_link ), esc_html( $user_name ) );

		return trim( $source_text . ' ' . $user_text );
	}
}