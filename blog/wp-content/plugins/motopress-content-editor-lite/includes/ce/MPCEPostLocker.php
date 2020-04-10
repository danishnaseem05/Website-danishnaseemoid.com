<?php

class MPCEPostLocker {

	/**
	 * MPCEPostLocker constructor.
	 */
	public function __construct() {
		add_filter( 'heartbeat_received', array( $this, 'handleHeartbeat' ), 10, 2 );
	}

	/**
	 *
	 * @param array $response
	 * @param array $data
	 *
	 * @return array
	 */
	public function handleHeartbeat( $response, $data ) {

		if ( isset( $data['mpcePostLock'] ) ) {

			$postId      = $data['mpcePostLock'];
			$lockingUser = $this->getLockingUser( $postId );

			if ( false === $lockingUser || !empty( $data['mpceForcePostLock'] ) ) {
				$this->lockPost( $postId );
			} else {
				$response['lockingUser'] = $lockingUser->display_name;
			}
		}

		return $response;
	}

	/**
	 * @param $postId
	 */
	function lockPost( $postId ) {
		if ( !function_exists( 'wp_set_post_lock' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
		}

		wp_set_post_lock( $postId );
	}

	/**
	 * @param int|string $postId
	 *
	 * @return false|WP_User ID of the user with lock. False if the post does not exist, post is not locked,
	 *                   the user with lock does not exist, or the post is locked by current user.
	 */
	function getLockingUser( $postId ) {
		if ( !function_exists( 'wp_check_post_lock' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
		}

		$lockingUser = wp_check_post_lock( $postId );
		if ( !$lockingUser ) {
			return false;
		}

		return get_user_by( 'id', $lockingUser );
	}

	/**
	 * @param $postId
	 *
	 * @return bool
	 */
	function isPostLocked( $postId ) {
		return false !== $this->getLockingUser( $postId );
	}
}