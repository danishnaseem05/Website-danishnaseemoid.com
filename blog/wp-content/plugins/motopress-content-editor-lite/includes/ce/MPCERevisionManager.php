<?php

// TODO: Remove `$status` usage
// TODO: Remove `autosave` status usage
// TODO: ? Restoring revision on PostEdit page: maybe copy editor revision meta to restored revision

/* TODO: Fix editor-scene with cherry4
 * - publish post with simple content
 * - open with mpce-editor
 * - drop new widget
 * - save OR remove old|new element
 * - see the wrong behavior
 */

if (!class_exists('MPCERevisionManager'))
{
class MPCERevisionManager
{
	const MAX_REVISIONS_TO_DISPLAY = 100;

	private static $_instance = null;
	private $authors = array();


	public function __construct()
	{
		$this->hooks();
	}

	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function hooks()
	{
        add_action('wp_restore_post_revision', array($this, 'restoreRevision'), 10, 2);
        add_action('init', array($this, 'addRevisionSupport'), 9999);
		add_filter('motopress_ce_localize_settings', array($this, 'editorSettings'), 10, 2);
        add_filter('mpce_ajax_save_post_response', array($this, 'savePostResponse'), 10, 2 );
        add_action('mpce_before_save', array($this, 'beforeSave'));

        if (MPCEUtils::isAjax()) {
            add_action('wp_ajax_motopress_ce_get_revision_data', array($this, 'onRevisionDataRequest'));
            add_action('wp_ajax_motopress_ce_delete_revision', array($this, 'onDeleteRevisionRequest'));
        }
	}

	// TODO: Maybe don't run when MPCE-mode disabled
	public function addRevisionSupport()
    {
        $postTypes = MPCEContentManager::getEditablePostTypes();

        foreach ($postTypes as $postType) {
            add_post_type_support($postType, 'revisions');
        }
    }

    public function editorSettings($settings, $postID)
    {
        $settings = array_replace_recursive($settings, array(
            'nonces'            => array(
                'motopress_ce_get_revision_data' => wp_create_nonce('wp_ajax_motopress_ce_get_revision_data'),
                'motopress_ce_delete_revision'   => wp_create_nonce('wp_ajax_motopress_ce_delete_revision'),
            ),
            'revisions'         => $this->getRevisions(),
            'revisions_enabled' => ($postID && wp_revisions_enabled(get_post($postID))),
        ));

		return $settings;
	}

    /**
     * Get editor-enabled revisions only
     *
     * @return array
     */
    private function getRevisions($postID = 0, $queryArgs = array(), $parseResult = true)
    {
        $post = get_post($postID);

        if (!$post || empty($post->ID)) {
            return array();
        }

        $revisions = array();

        $queryArgs = array_merge(array(
            'posts_per_page' => self::MAX_REVISIONS_TO_DISPLAY,
            'meta_key'       => MPCEContentManager::ENABLED_META,
            'meta_value'     => true,
        ), $queryArgs);

        $posts = wp_get_post_revisions($post->ID, $queryArgs);

        if (!$parseResult) {
            return $posts;
        }

        $currentTime = current_time('timestamp');

        /** @var WP_Post $revision */
        foreach ($posts as $revision) {
            $date = date_i18n(
                _x('M j @ H:i', 'revision date format', 'motopress-content-editor-lite'),
                strtotime($revision->post_modified)
            );

            $humanTime = human_time_diff(strtotime($revision->post_modified), $currentTime);

            if (false !== strpos($revision->post_name, 'autosave')) {
                $type = 'autosave';
            } else {
                $type = 'revision';
            }

            if (!isset($this->authors[$revision->post_author])) {
                $this->authors[$revision->post_author] = array(
                    'avatar'       => get_avatar($revision->post_author, 22),
                    'display_name' => get_the_author_meta('display_name', $revision->post_author),
                );
            }

            $revisions[] = array(
                'id'       => $revision->ID,
                'author'   => $this->authors[$revision->post_author]['display_name'],
                'date'     => sprintf(__('%1$s ago (%2$s)', 'motopress-content-editor-lite'), $humanTime, $date),
                'type'     => $type,
                'gravatar' => $this->authors[$revision->post_author]['avatar'],
            );
        }

        return $revisions;
    }

    public function savePostResponse( $response, $postID )
    {
		$lastRevision = $this->getRevisions(
			$postID,
            array('posts_per_page' => 1)
		);

		$revisionIDs = $this->getRevisions(
		    $postID,
            array('fields' => 'ids'),
            false
        );

		if ( ! empty( $lastRevision ) ) {
			$response['last_revision'] = $lastRevision[0];
			$response['revisions_ids'] = $revisionIDs;
		}

		return $response;
	}

	public function beforeSave(/*$status,*/ $hasChanges)
	{
		if ($hasChanges) {
			$this->handleRevision();
		}
	}

	private function handleRevision()
	{
		add_filter('wp_save_post_revision_post_has_changed', '__return_true');
		add_action('_wp_put_post_revision', array($this, 'saveRevision'));
	}

	public function saveRevision($revisionID)
	{
		$parentID = wp_is_post_revision($revisionID);

		if ($parentID && MPCEContentManager::isPostEnabledForEditor($parentID)) {
			$this->copyMeta($parentID, $revisionID);
		}
	}

	// TODO: Maybe move to ContentManager
	private function copyMeta($fromID, $toID)
	{
        $metaToCopy = array(
            MPCEContentManager::ENABLED_META,
            MPCEContentManager::CONTENT_META,
            MPCEContentManager::SAVE_IN_VERSION_META,
            MPCECustomStyleManager::WP_POST_META_STYLES,
        );
		$parentMeta = get_post_meta($fromID);

		foreach ($parentMeta as $key => $value) {
			if (in_array($key, $metaToCopy)) {
				update_metadata('post', $toID, $key, maybe_unserialize($value[0]));
			}
		}
	}

    public function restoreRevision($parentID, $revisionID)
    {
        $isEditorContent = MPCEContentManager::isPostEnabledForEditor($revisionID);

        MPCEContentManager::getInstance()->setPostEditorStatus($parentID, $isEditorContent);

        if ($isEditorContent) {
            $this->copyMeta($revisionID, $parentID);
        }
    }

    public function onRevisionDataRequest()
    {
        $id          = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $contentType = !empty($_POST['content_type']) ? $_POST['content_type'] : 'raw'; // raw | editor

        if (!$id || !wp_is_post_revision($id)) {
            motopressCESetError(__('Invalid Revision', 'motopress-content-editor-lite'));
        }

        if (!MPCEAccess::getInstance()->hasAccess($id)) {
            motopressCESetError(__('You have no access to this revision', 'motopress-content-editor-lite'));
        }

        if ($contentType === 'raw') {
            $data = $this->getRevisionRawData($id);
        } else {
            $data = $this->getRevisionEditorData($id);
        }

        wp_send_json_success($data);
        exit;
    }

    public function onDeleteRevisionRequest()
    {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if (!$id) {
            motopressCESetError(__("You must set the id", 'motopress-content-editor-lite'));
        }

        if (!MPCEAccess::getInstance()->hasAccess($id)) {
            motopressCESetError(__('You have no access to this revision', 'motopress-content-editor-lite'));
        }

        $deleted = wp_delete_post_revision($id);

        if ($deleted && !is_wp_error($deleted)) {
            wp_send_json_success();
            exit;
        } else {
            motopressCESetError(__("Cannot delete this Revision", 'motopress-content-editor-lite'));
        }
    }

    private function getRevisionRawData($id)
    {
        $content = MPCEContentManager::getEditorContent($id);
        $content = '<div class="motopress-content-wrapper motopress-content-wrapper-preview">' . $content . '</div>';
        $content = apply_filters('the_content', $content);

        $sm = MPCECustomStyleManager::getInstance();
        $sm->enqueuePrivateStyle($id);
        $stylesStr = $sm->getPrivateStylesString();
        $styles = '';

        if (!empty($stylesStr)) {
            $styles = '<div class="motopress-ce-private-styles-wrapper-preview">';
            $styles .= '<style type="text/css">' . $stylesStr . '</style>';
            $styles .= '</div>';
        }

        return array(
            'content' => $content,
            'styles' => $styles,
        );
    }

    private function getRevisionEditorData($id)
    {
        // Get content
        $content = MPCEContentManager::getEditorContent($id);
        $content = mpce_wpautop($content);
        $content = mpceRenderContent($content);

        // Get styles
        $sm = MPCECustomStyleManager::getInstance();
        $postStyles = MPCECustomStyleManager::getAllPrivates($id);
        $otherStyles = $sm->getPrivateStylesTag(true);

        return array(
            'content' => $content,
            'post_styles' => $postStyles,
            'other_styles' => $otherStyles,
        );
    }

	private function __clone() {}
	private function __wakeup() {}
}
}
MPCERevisionManager::getInstance();