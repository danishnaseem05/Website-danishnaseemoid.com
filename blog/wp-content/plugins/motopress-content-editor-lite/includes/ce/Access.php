<?php

/**
 * Description of MPCEAccess
 *
 * @author dima
 */
class MPCEAccess {
	private static $_instance = null;

	private function __construct() {
		/*if (isset(mpceSettings()['demo']) && mpceSettings()['demo']) {
			if (isset($this->capabilities['unfiltered_html'])) {
				unset($this->capabilities['unfiltered_html']);
			}
		}*/
	}

	static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Check is Visual Builder can edit post
	 *
	 * @param int|WP_Post $postId Optional.
	 *
	 * @return bool
	 */
	public function hasAccess( $postId = null ) {

		if ( !$postId ) {
			$postId = get_the_ID();
		}

		if ( is_a( $postId, 'WP_Post' ) ) {
			$postId = $postId->ID;
		}

		if ( ( $parentId = wp_is_post_revision( $postId ) ) ) {
            $postId = $parentId;
        }

		require_once ABSPATH . WPINC . '/pluggable.php';

		return (
			is_user_logged_in() &&
	         $this->isUserCanEdit( $postId ) &&
			$this->isPostTypeSupport( $postId ) &&
			!$this->isCEDisabledForCurRole()
		);
	}

	/*
	 * @return boolean
	 */
	public function isCEDisabledForCurRole() {
		$disabledRoles    = get_option( 'motopress-ce-disabled-roles', array() );
		$currentUser      = wp_get_current_user();
		$currentUserRoles = $currentUser->roles;

		if ( is_super_admin() ) {
			return false;
		}

		foreach ( $currentUserRoles as $key => $role ) {
			if ( !in_array( $role, $disabledRoles ) ) {
				return false;
			}
		}

		// in case if all user rules are disabled
		return true;
	}

	/**
	 * @param int $postId
     * @return bool
	 */
	public function isUserCanEdit( $postId ) {

		$capabilities = array(
			'read',
			'upload_files' /*, 'unfiltered_html' */
		);

		if ( get_post_type( $postId ) === 'page' ) {
			$capabilities[] = 'edit_pages';
			$capabilities[] = 'edit_page';
		} else {
			$capabilities[] = 'edit_posts';
			$capabilities[] = 'edit_post';
		}

		$userCanEdit = true;

		foreach ( $capabilities as $capability ) {
			if ( !current_user_can( $capability, $postId ) ) {
				$userCanEdit = false;
				break;
			}
		}

		return $userCanEdit;
	}

	/**
	 * @param int|WP_Post|null $post
	 *
	 * @return bool
	 */
	public function isPostTypeSupport( $post = null ) {
		$postTypes = get_option( 'motopress-ce-options', array( 'post', 'page' ) );
		$postType  = get_post_type( $post );

		return in_array( $postType, $postTypes ) && post_type_supports( $postType, 'editor' );
	}
}