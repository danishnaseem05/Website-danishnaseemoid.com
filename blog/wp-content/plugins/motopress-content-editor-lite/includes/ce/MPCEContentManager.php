<?php

// TODO: Maybe remove `motopress-ce-edited-post` meta usage
// TODO: Fix switching new post AND editing draft post

if ( ! class_exists( 'MPCEContentManager' ) ) {
	class MPCEContentManager {

		const CONTENT_EDITOR_ID = 'motopresscecontent';
		const ENABLED_META = '_mpce_enabled';
		const CONTENT_META = '_mpce_post_content';
		const SAVE_IN_VERSION_META = 'motopress-ce-save-in-version';

		private static $_instance = null;
		private static $postRendering;
		private $renderingEditableContent = false;
		private static $builderRunning = false;
		private $builderRunningError = null;

		/**
		 * @var MPCEAccess|null
		 */
		private $accessChecker = null;

		private function __construct() {
			$this->hooks();
			$this->accessChecker = MPCEAccess::getInstance();
		}

		public static function getInstance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		private function hooks() {
			add_action( 'admin_init', array( $this, 'adminInit' ) );
			add_action( 'wp', array( $this, 'iframeHooks' ) );
			add_filter( 'content_save_pre', array( $this, 'contentSavePreAction' ) );
			add_action( 'edit_form_after_title', array( $this, 'addFieldsToEditPostForm' ) );
			add_filter( 'wp_default_editor', array( $this, 'enableTinymceEditorMode' ) );
			add_filter( '_wp_post_revision_fields', array( $this, 'wpPostRevisionFields' ) );
			add_filter( 'post_class', array( $this, 'hideTitleClass'), 10, 3 );

			// Excerpt shortcode
			$excerptShortcode = get_option( 'motopress-ce-excerpt-shortcode', '1' );
			if ( $excerptShortcode ) {
				remove_filter( 'the_excerpt', 'wpautop' );
				add_filter( 'the_excerpt', 'do_shortcode' );
				add_filter( 'get_the_excerpt', 'do_shortcode' );
			}

			$editingPostID = $this->getEditingPostId();
			if ( self::isEditorPage() && $editingPostID ) {
				// General empty post_content fix
				add_filter( 'the_posts', array( $this, 'generalEmptyContentFix' ) );
//				add_filter('the_posts', array($this, 'generalEmptyContentFix'), 999, 1);
			}

			// NOTE: WP shortcode `embed` doesn't work because of this filter
			add_filter( 'the_content', array( $this, 'renderFrontContent' ), 1 );
		}

		function adminInit() {
			add_action( 'load-post.php', array( $this, 'adminEditPageAction' ) );

			// motopressCERegisterHtmlAttributes
			global $allowedposttags;
			if ( isset( $allowedposttags['div'] ) && is_array( $allowedposttags['div'] ) ) {
				$attributes             = array_fill_keys( array_values( MPCEShortcode::$attributes ), true );
				$allowedposttags['div'] = array_merge( $allowedposttags['div'], $attributes );
			}
		}

		function iframeHooks() {
			global $post;

			if ( self::isEditorPage() ) {
				if ( $this->checkEditorNonce() ) {
					if ( $this->accessChecker->hasAccess( $post ) ) {
						$this->setBuilderRunning( true );

						add_filter( 'show_admin_bar', '__return_false' );

						add_action( 'the_post', array( $this, 'renderIframeContent' ), 9999 );
						add_action( 'wp_footer', array( $this, 'printPostShortcodes' ) );

//						add_filter( 'the_title', array( $this, 'editorFilterTitle' ), 1, 2 );
						add_filter( 'get_post_metadata', array( $this, 'replacePageTemplate' ), 10, 3 );

						/* Fix empty post_content */

						/*
						// Fix Cherry empty post_content
						global $mpceIsCherryContentEmpty;
						$mpceIsCherryContentEmpty = false;
						add_action('cherry_entry_before', array($this, 'cherryEntryBefore'));
						add_action('cherry_entry_after', array($this, 'cherryEntryAfter'));
						*/

						// `suppress_filters` ?

						// Cherry 4 fix
						add_filter( 'cherry_content_template_hierarchy', array( $this, 'cherry4EmptyContentFix' ) );

						/* END Fix empty post_content */

					} else {
						$this->setBuilderRunningError( 'access' );
					}

				} else {
					$this->setBuilderRunningError( 'nonce' );
				}

				add_action( 'wp_head', array( $this, 'editorIframeReady' ), 9999 );
			}
		}

		public function renderFrontContent( $content ) {
			global $post;

			if ( is_null( $post ) ) {
				return $content;
			}

			$postID = $post->ID;

			// Skip if editing-content
			if ( self::isEditorPage() && $postID == $this->getEditingPostId() && $this->checkEditorNonce( $postID ) ) {
				return $content;
			}

			$postActive      = self::isPostEnabledForEditor( $postID );
			$rendering       = $postID === self::$postRendering;

			// Use builder content
			if ( $postActive && $this->accessChecker->isPostTypeSupport( $postID ) && ! $rendering ) {
				self::$postRendering = $postID;

				$previewID = isset( $_GET['preview_id'] ) ? $_GET['preview_id'] : false;
				$isPreview = $previewID == $postID && is_preview();

				$content = self::getEditorContent( $postID, $isPreview );

				self::$postRendering = null;
			}

			return $content;
		}

		/**
		 * @param null|int $postID
		 *
		 * @return bool
		 */
		private function checkEditorNonce( $postID = null ) {
			if ( is_null( $postID ) ) {
				global $post;
				if ( ! is_null( $post ) ) {
					$postID = $post->ID;
				} else {
					return false;
				}
			}

			if ( isset( $_REQUEST['_wpnonce'] ) && // Nonce exists
			     wp_verify_nonce( $_REQUEST['_wpnonce'], self::getIframeNonceAction( $postID ) ) !== false // Nonce valid
			) {
				return true;
			}

			return false;
		}

		public function generalEmptyContentFix( $posts ) {
			$editPostId = $this->getEditingPostId();
			if ( $editPostId ) {
				foreach ( $posts as $post ) {
					if ( $this->fixEmptyContent( $post, $editPostId ) ) {
						break;
					}
				}
			}

			return $posts;
		}

		public function cherry4EmptyContentFix( $templates ) {
			$editPostId = $this->getEditingPostId();
			if ( $editPostId ) {
				global $post;
				$this->fixEmptyContent( $post, $editPostId );
			}

			return $templates;
		}


		private function getEditingPostId() {
			return isset( $_REQUEST['mpce-post-id'] ) ? $_REQUEST['mpce-post-id'] : false;
		}

		private function fixEmptyContent( $post, $editPostId ) {
			if ( $post->ID == $editPostId ) {
				if ( ! $post->post_content ) {
					$post->post_content = 'empty-content';
				}

				return true;
			}

			return false;
		}

		/*
		function cherryEntryBefore() {
			global $post, $mpceIsCherryContentEmpty;
			$mpceIsCherryContentEmpty = false;
			if ($post && !$post->post_content) {
				$mpceIsCherryContentEmpty = true;
				$post->post_content = 'mpce-empty-cherry-content';
			}
		}
		function cherryEntryAfter() {
			global $post, $mpceIsCherryContentEmpty;
			if ($post && $mpceIsCherryContentEmpty) {
				$post->post_content = '';
			}
		}
		*/

		public function replacePageTemplate( $value, $postId, $metaKey ) {
			if ( $metaKey === '_wp_page_template' ) {
				$editPostId = isset( $_REQUEST['mpce-post-id'] ) ? $_REQUEST['mpce-post-id'] : false;
				if ( $editPostId && $postId == $editPostId ) {
					$template = isset( $_POST['mpce_page_template'] ) ? $_POST['mpce_page_template'] : get_post_meta( $postId, '_mpce_page_template', true );
					if ( $template ) {
						$value = $template;
					}
				}
			}

			return $value;
		}


		/**
		 * Output script that trigger MPCESceneDocReady or MPCESceneDocError
		 * must be output before shortcodes scripts
		 * for reason that editor must be intialized (also content insert) before shortcodes initialization.
		 *
		 */
		public function editorIframeReady() {
			if ( wp_script_is( 'jquery', 'done' ) ) {
				/**
				 * MPCESceneDocReady - Editor iframe ready
				 * MPCESceneDocError :
				 * - nonce - Nonce invalid
				 * - access - No permission to edit
				 */
				if ( self::isBuilderRunning() ) {
					$action = 'MPCESceneDocReady';
					$errorType = '';
				} else {
					$action    = 'MPCESceneDocError';
					$errorType = $this->getBuilderRunningError();
				}
				?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						MPCESceneStatus = {
							action: '<?php echo $action; ?>',
							errorType: '<?php echo $errorType; ?>'
						};
						MPCESceneStatus.status = MPCESceneStatus.action === 'MPCESceneDocReady' ? 'ready' : 'error';

						parent.CE.Iframe.myThis.element.trigger( MPCESceneStatus.action, MPCESceneStatus.errorType );
					});
				</script>
				<?php
			}
		}

		public function iframeContentMarker( $content ) {
			do_shortcode( $content ); // Needed to enqueue js/css
			return '<span id="mpce-editable-content-marker" style="display:none !important;"></span>';
		}

		public function printPostShortcodes() {
			echo isset( $GLOBALS['mpce_editable_content'] ) ? $GLOBALS['mpce_editable_content'] : '';
		}

		public function renderIframeContent( $post ) {
			$editPostId = isset( $_REQUEST['mpce-post-id'] ) ? $_REQUEST['mpce-post-id'] : false;
			if ( $editPostId && $post->ID == $editPostId && ! $this->isRenderingEditableContent() ) {
				$this->setRenderingEditableContent( true );

				mpceShowNoticeForWrongRequestOrAccessDenied();
				mpceClearWPAttachmentDetails();
				$content = self::getEditorContent( $post->ID );
				$content = mpce_wpautop($content, false);
				$content = mpceRenderContent( $content, array(
					'wrapOuterCode' => true,
					'contentWrapper' => 'motopress-content-wrapper'
				) );

				$attachmentDetailsJSON = mpce_wp_json_encode( mpceGetWPAttachmentDetails() );

				$script = '<p class="motopress-hide-script"><script type="text/javascript">' . 'window.mpce_wp_attachment_details = ' . $attachmentDetailsJSON . ';' . '</script></p>';

				$GLOBALS['mpce_editable_content'] = '<script type="template/html" id="mpce-post-content-template" style="display:none">' . rawurlencode( $script . $content ) . '</script>';

				remove_all_filters( 'the_content' );
				add_filter( 'the_content', array( $this, 'iframeContentMarker' ) );
			}
		}

		public function filterContent( $content ) {
			// [mp_tmp_base64]
			$content = preg_replace_callback( '/\[mp_tmp_base64\](.*)\[\/mp_tmp_base64\]/', function ( $matches ) {
				return base64_decode( $matches[1] );
			}, $content );

			return $content;
		}

		function editorFilterTitle( $title, $postId ) {
			$editPostId = isset( $_REQUEST['mpce-post-id'] ) ? $_REQUEST['mpce-post-id'] : $postId;
			if ( $postId == $editPostId ) {
				$title = isset( $_POST['mpce_title'] ) ? $_POST['mpce_title'] : get_post_meta( $postId, '_mpce_title', true );
				$title = stripslashes( trim( $title ) );
				$title = '&zwnj;' . $title . '&zwnj;';
			}

			return $title;
		}

		public function adminEditPageAction() {
			// Enable post for editor if post-edit page opened with mpce-auto-open
			$isEditAction = isset( $_GET['action'] ) && $_GET['action'] == 'edit';
			$postID       = isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ? (int) $_GET['post'] : false;
			$isAutoOpen   = isset( $_GET['motopress-ce-auto-open'] ) && $_GET['motopress-ce-auto-open'] === 'true';

			if ( $isEditAction && $postID && $isAutoOpen && $this->accessChecker->hasAccess( $postID ) ) {
				if ( ! self::getEditorContent( $postID ) ) {
					$this->setEditorContent( $postID, get_post_field( 'post_content', $postID ) );
				}
				$this->enablePostForEditor( $postID );

//				wp_redirect(get_edit_post_link($postID, false));
			}
		}

		/**
		 * @param string $content
		 *
		 * @return string
		 */
		private function simplifyContent( $content ) {
			require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/shortcode/ShortcodeSimple.php';

			global $shortcode_tags;
			$store_shortcode_tags = $shortcode_tags;
			remove_all_shortcodes();

			$shortcode = new MPCEShortcodeSimple();
			$shortcode->register();

			$content = do_shortcode( $content );

			// Filter content
			$content = str_replace( array( "\r\n", "\r" ), "\n", $content );
			$content = preg_replace( "/\n\n+/", "\n\n", $content );

			$shortcode_tags = $store_shortcode_tags;

			return $content;
		}

		public function contentSavePreAction( $content ) {

			$postID = get_the_ID();
			$post = get_post( $postID );

			if ( is_null( $post ) ) {
				return $content;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $content;
			}

			if ( ! $this->accessChecker->hasAccess( $post ) ) {
				return $content;
			}

			$isUpdate  = ! wp_is_post_revision( $postID );
			$isPreview = isset( $_POST['wp-preview'] ) && $_POST['wp-preview'] === 'dopreview';

			// Fix for draft. If draft then save data as always.
			if ( $isPreview ) {
				if ( ! wp_check_post_lock( $postID ) && get_current_user_id() == $post->post_author && ( 'draft' == $post->post_status || 'auto-draft' == $post->post_status ) ) {
					$isPreview = false;
				}
			}

			// Update post content
			if ( $isUpdate || $isPreview ) {
				$editorContent       = isset( $_POST[ self::CONTENT_EDITOR_ID ] ) ? $_POST[ self::CONTENT_EDITOR_ID ] : false;
				$editorContentExists = $editorContent !== false;

				// Update editor content in Builder tab
				if ( $editorContentExists ) {
					$updated = $this->setEditorContent( $postID, $editorContent, $isPreview );

					do_action( 'mpce_before_save', /*$status,*/ $updated );
				}

				if ( ! $isPreview ) {
					$statusExists = isset( $_POST['mpce-status'] ) && ! empty( $_POST['mpce-status'] );
					$oldStatus    = self::isPostEnabledForEditor( $postID );
					$newStatus    = $statusExists ? ( $_POST['mpce-status'] === 'enabled' ) : $oldStatus;

					// Save simplified content if Enabled mode
					if ( $editorContentExists && $oldStatus === true ) {
						remove_filter( 'content_save_pre', array( $this, 'contentSavePreAction' ) );
						$content = $this->simplifyContent( wp_unslash( $editorContent ) );
					} else {
						// Clone wp content to editor if switching status to Enabled && mp-content empty
						if ( $oldStatus === false && $newStatus === true && ! self::getEditorContent( $postID ) ) {
							$this->setEditorContent( $postID, $content );
						}
					}

					// Update editor status
					if ( $statusExists ) {
						$enable = $newStatus;
						if ( $enable ) {
							$this->enablePostForEditor( $postID );
						} else {
							$this->disablePostForEditor( $postID );
						}
					}

					$this->updateSaveInVersionMeta($postID);
				}
			}

			return $content;
		}

        /**
         * TODO: Test with Draft
         *
         * @param int $postID
         * @param array $args
         *
         * @return bool
         */
		private function saveContent( $postID, $args ) {
            extract( array_merge( array(
                'title'     => '',
                'hideTitle' => 0,
                'template'  => null,
                'content'   => '',
                'status'    => null,
            ), $args ) );

			// Set $post global variable
			global $post;
			$post = get_post( $postID );

			if ( is_null( $post ) ) {
				return false;
			}

			if ( ! $this->accessChecker->hasAccess( $post ) ) {
				return false;
			}

			$updated = $this->setEditorContent( $postID, $content );
			$updated = $updated || MPCECustomStyleManager::isPrivateSaved();

			do_action( 'mpce_before_save', /*$status,*/ $updated );

			$postData = array(
                'ID'           => $postID,
                'post_title'   => $title,
                'post_content' => $this->simplifyContent( wp_unslash( $content ) ),
            );

            $allowedPostStatuses = get_post_statuses();
            if ( !is_null( $status ) && $status !== $post->post_status && isset( $allowedPostStatuses[ $status ] ) ) {
                $postTypeObject = get_post_type_object( $post->post_type );
                if ( $status !== 'publish' || current_user_can( $postTypeObject->cap->publish_posts ) ) {
                    $postData['post_status'] = $status;
                }
            }

			wp_update_post( $postData );

			$this->savePageSettings( $postID, array(
				'hide_title' => $hideTitle,
			) );

			if ( is_null( $template ) ) {
                $template = 'default';
            }
            update_post_meta( $postID, '_wp_page_template', $template );

			$this->updateSaveInVersionMeta( $postID );

			// Clear $post global variable
			$post = null;

			return true;
		}

        /**
         * @param {int} $postID
         * @param {array} $settings
         */
        private function savePageSettings($postID, $settings) {
            if ( ! empty( $settings ) ) {
                update_post_meta( $postID, '_mpce_page_settings', $settings );
            }
        }

        /**
         * @param {int} $postID
         *
         * @return array
         */
        public function getPageSettings($postID) {
            $settings = get_post_meta( $postID, '_mpce_page_settings', true );

            if ( ! $settings ) {
                $settings = array();
            }

            $settings = array_merge( array(
                'hide_title' => 0
            ), $settings );

            return $settings;
        }

		public function addFieldsToEditPostForm() {
			global $post;

			if ( is_null( $post ) ) {
				return;
			}

			if ( $this->accessChecker->hasAccess( $post ) ) {

				$postID      = $post->ID;
				$mpceEnabled = self::isPostEnabledForEditor( $postID );

				// Tabs
				$selectedCls     = 'nav-tab-active';
				$wpSelectedCls   = ! $mpceEnabled ? $selectedCls : '';
				$mpceSelectedCls = $mpceEnabled ? $selectedCls : '';
				$wpTitle         = __( "Text Editor", 'motopress-content-editor-lite' );
				$mpceTitle       = __( "Visual Builder", 'motopress-content-editor-lite' );

				// Visual Editor button
				$postStatus   = get_post_status( $postID );
				$CEButtonText = apply_filters( 'mpce_button_text', __( "Open Visual Builder", 'motopress-content-editor-lite' ) );
				?>

				<div class="nav-tab-wrapper mpce-tab-wrapper">
					<a href="javascript:void(0);" onclick="return false;"
					   class="nav-tab mpce-tab <?php echo $wpSelectedCls; ?>" id="mpce-tab-default"
					   data-ref-id="#postdivrich"><?php echo $wpTitle; ?></a>

					<a href="javascript:void(0);" onclick="return false;"
					   class="mpce-tab nav-tab <?php echo $mpceSelectedCls; ?>" id="mpce-tab-editor"
					   data-ref-id="#motopress-ce-tinymce-wrap"><?php echo $mpceTitle; ?></a>
				</div>
				<br/>

				<?php if ( $mpceEnabled ) { ?>
					<?php
					// Prevent editing WooCommerce shop page
					if ( ! $this->isWcShopPage( $postID ) ) { ?>
						<input type="button" id="motopress-ce-btn"
						       class="wp-core-ui button button-primary button-large"
						       data-post-id="<?php echo $postID; ?>"
						       data-post-status="<?php echo $postStatus; ?>"
						       value="<?php echo $CEButtonText; ?>"
						       disabled="disabled"/>
					<?php } ?>
					<p><?php echo __( "Note: content created in Visual Builder will be displayed on your site when Visual Builder tab is active. You may switch to default Text Editor anytime but your edits will not be replicated in the visual version. Content in Text Editor tab is automatically updated with edits you make in Visual Builder.", 'motopress-content-editor-lite' ) ?></p>
				<?php } ?>

				<div class="mpce-form-fields"></div>
				<div class="mpce-hidden-fields"></div>
				<?php
			}
		}

		// @todo+ why screen is empty
		public function enableTinymceEditorMode( $r ) {
			global $post;
			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();

				if ( $screen && $screen->parent_base == 'edit' ) {
					if ( ! is_null( $post ) ) {
						return self::isPostEnabledForEditor( $post->ID ) && $this->accessChecker->hasAccess( $post ) ? 'tinymce' : $r;
					}
				}
			}

			return $r;
		}

		/**
		 * Needed for the call `content_save_pre` hook on preview.
		 */
		function wpPostRevisionFields( $fields ) {
			$fields[ self::CONTENT_EDITOR_ID ] = __( "Visual Builder", 'motopress-content-editor-lite' );

			return $fields;
		}

		/*
		 add_filter('tiny_mce_before_init', 'motopressCERegisterTinyMCEHtmlAttributes', 10, 1);
//		 this func override valid_elements of tinyMCE.
//		 If you need to use this function you will set all html5 attrs in addition to motopress-attributes
		function motopressCERegisterTinyMCEHtmlAttributes($options) {

		    if (!isset($options['extended_valid_elements'])) {
		        $options['extended_valid_elements'] = '';
		    }

		    $attributes = array_values(MPCEShortcode::$attributes);
		    //html5attrs must contain all valid html5 attributes
		    $html5attrs = array('class', 'id', 'align', 'style');
		    if (strpos($options['extended_valid_elements'], 'div[')) {
		        $attributesStr = implode('|', $attributes);
		        $options['extended_valid_elements'] .= preg_replace('/div\[([^\]]*)\]/', 'div[$1|' . $attributesStr . ']', $options['extended_valid_elements']);
		    } else {
		        array_push($attributes, $html5attrs);
		        $attributesStr = implode('|', $attributes);
		        $options['extended_valid_elements'] .= ',div[' . $attributesStr . ']';
		    }

		    return $options;
		}
		*/

		private static function isEditorPage() {
			$pID     = 'mpce-post-id';

			return mpceIsEditorScene() && ( isset( $_GET[ $pID ] ) && $_GET[ $pID ] );
		}

		static function isPostEnabledForEditor( $postID ) {
			if ( ! is_admin() && post_password_required() ) {
				return false;
			} else {
				return ! ! get_post_meta( $postID, self::ENABLED_META, true );
			}
		}

		/**
		 *
         * @return array
         */
		static function getEditablePostTypes() {
            return get_option('motopress-ce-options', array('post', 'page'));
        }

		/**
		 * @param int  $postID
		 * @param bool $trySetContent
		 *
		 * @return bool|int
		 */
		public function enablePostForEditor( $postID, $trySetContent = false ) {
			if ( $trySetContent && ! self::getEditorContent( $postID ) ) {
				$this->setEditorContent( $postID, get_post_field( 'post_content', $postID ) );
			}

			return update_post_meta( $postID, self::ENABLED_META, true );
		}

		public function disablePostForEditor( $postID ) {
			return update_post_meta( $postID, self::ENABLED_META, false );
		}

		public function setPostEditorStatus($postID, $status) {
			if ($status) {
				$this->enablePostForEditor($postID);
			} else {
				$this->disablePostForEditor($postID);
			}
		}

		public function savePostAjax() {
			mpceVerifyNonce();
			mpceShowNoticeForWrongRequestOrAccessDenied();

			if ( ! isset( $_POST['postID'], $_POST['content'], $_POST['title']/*, $_POST['status']*/ ) ) {
				wp_send_json_error();
				return;
			}

			$postId = filter_input( INPUT_POST, 'postID', FILTER_VALIDATE_INT );
			if(!$postId) {
				wp_send_json_error();
				return;
			}

			$title = $_POST['title'];
			$hideTitle = $_POST['hideTitle'] ? 1 : 0;
			$template = !empty( $_POST['template'] ) ? $_POST['template'] : null;
			$content = $_POST['content'];
			$status = !empty( $_POST['status'] ) ? $_POST['status'] : null;

			remove_filter( 'content_save_pre', array( $this, 'contentSavePreAction' ) );

			// Old `mpce_ajax_save_post`
			do_action( 'mpce_ajax_before_save_post', $postId, false );

			$updated = $this->saveContent($postId, array(
                'title'     => $title,
                'hideTitle' => $hideTitle,
                'template'  => $template,
                'content'   => $content,
                'status'    => $status,
            ));

			do_action( 'mpce_ajax_after_save_post', $postId, false );

			$response = apply_filters( 'mpce_ajax_save_post_response', array(
				'post_status' => get_post_status( $postId ),
				'iframe_nonce' => wp_create_nonce( self::getIframeNonceAction( $postId ) )
			), $postId );

			if ( $updated ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error();
			}
		}

		// TODO: Maybe don't check `motopress-ce-edited-post`
		private function updateSaveInVersionMeta($postID) {
			$editedPostKey = 'motopress-ce-edited-post';
			$isPostEdited  = isset( $_POST[ $editedPostKey ] ) && ! empty( $_POST[ $editedPostKey ] );
			if ( $isPostEdited && $postID === (int) $_POST[ $editedPostKey ] ) {
				update_post_meta( $postID, self::SAVE_IN_VERSION_META, mpceSettings()['plugin_version'] );
			}
		}

		/**
		 * Retrieve editor content of post. Content in no-p format (\r\n instead of <p>)
		 *
		 * @param      $postID
		 * @param bool $isPreview
		 *
		 * @return string
		 */
		static function getEditorContent( $postID, $isPreview = false ) {
			if ( $isPreview ) {
				$content = wp_unslash( get_transient( self::getContentTransName( $postID ) ) );
			} else {
				$content = get_post_meta( $postID, self::CONTENT_META, true );
			}

			return is_string($content) ? $content : '';
		}

		/**
		 * @param int $postID
		 * @param string $content Expected quoted(slashed) string.
		 * @param bool   $isPreview
		 *
		 * @return bool|int
		 */
		private function setEditorContent( $postID, $content = '', $isPreview = false ) {
			if ( $isPreview ) {
				return set_transient( self::getContentTransName( $postID ), $content, DAY_IN_SECONDS );
			} else {
				return update_metadata( 'post', $postID, self::CONTENT_META, $content );
			}
		}

		private static function getContentTransName( $postID ) {
			return 'mpce-content-' . $postID;
		}

		private function setBuilderRunning( $state ) {
			self::$builderRunning = $state;
		}

		static function isBuilderRunning() {
			return self::$builderRunning;
		}

		private function setBuilderRunningError( $error ) {
			$this->builderRunningError = $error;
		}

		private function getBuilderRunningError() {
			return ! is_null( $this->builderRunningError ) ? $this->builderRunningError : 'access';
		}

		private function setRenderingEditableContent( $state ) {
			$this->renderingEditableContent = $state;
		}

		private function isRenderingEditableContent() {
			return $this->renderingEditableContent;
		}

		/**
		 * This function is only for admin panel
		 *
		 * @param int $postID
		 *
		 * @return bool
		 */
		private function isWcShopPage( $postID ) {

			$result = false;

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

				if ( function_exists( 'wc_get_page_id' ) ) {
					$shopPageID = wc_get_page_id( 'shop' );
				} else {
					$shopPageID = woocommerce_get_page_id( 'shop' );
				}

				$result = absint( $shopPageID ) === absint( $postID );

			}

			return $result;
		}

		public function hideTitleClass( $classes, $class, $postID ) {
	        if ( is_single() || is_page() ) {
	            $classes[] = 'mpce-post-div';

		        $pageSettings = $this->getPageSettings($postID);
	            if ( intval( $pageSettings['hide_title'] ) ) {
	                $classes[] = 'mpce-hide-post-title';
	            }
	        }

	        return $classes;
	    }

	    public static function getIframeNonceAction( $postID ) {
			return "mpce-edit-post_$postID";
	    }

		private function __clone() {
		}

		private function __wakeup() {
		}
	}
}
MPCEContentManager::getInstance();
