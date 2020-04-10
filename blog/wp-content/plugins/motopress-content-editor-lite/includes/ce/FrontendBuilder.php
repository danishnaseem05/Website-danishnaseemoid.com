<?php

class FrontEndBuilder {

	/**
	 * @var bool
	 */
	private $isEdit = false;

	/**
	 * @var string
	 */
	private $templatePath;

	/**
	 * FrontEndBuilder constructor.
	 *
	 * @param $atts
	 */
	public function __construct( $atts ) {

		$this->templatePath = $atts['templatePath'];

		add_action( 'wp_loaded', array( $this, 'detectIsEdit' ) );
		add_action( 'template_redirect', array( $this, 'init' ) );
	}

	public function detectIsEdit() {
		$this->isEdit = isset( $_GET['mpce-edit'] ) && $_GET['mpce-edit'];
	}

	public function init() {

		if (
			!$this->isEdit ||
			!MPCEAccess::getInstance()->hasAccess()
		) {
			return;
		}

		MPCEContentManager::getInstance()->enablePostForEditor( get_the_ID(), true );

		add_filter( 'show_admin_bar', '__return_false' );

		do_action( 'mpce_frontend_editor_init' );

		add_action( 'mpce_frontend_editor_head', array( $this, 'enqueueStyles' ) );
		add_action( 'mpce_frontend_editor_head', 'wp_print_styles' );
		add_action( 'mpce_frontend_editor_head', 'wp_print_head_scripts' );
//		add_action( 'mpce_frontend_editor_head', 'print_admin_styles' );

		add_action( 'mpce_frontend_editor_head', 'print_head_scripts' );
		add_action( 'mpce_frontend_editor_head', array( $this, 'enqueueScripts' ) );


		add_action( 'mpce_frontend_editor_footer', 'wp_print_footer_scripts' );
//		add_action( 'mpce_frontend_editor_footer', 'wp_auth_check_html' );
		add_action( 'mpce_frontend_editor_footer', 'wp_print_media_templates' );

		add_action( 'mpce_frontend_editor_main', array( $this, 'motopressCEHTML', ) );


		$this->renderEditor();
	}

	/**
	 *
	 * @return string
	 */
	private function generateIframeUrl() {
		$postId    = get_the_ID();
		$iframeSrc = get_permalink( $postId );

		//@todo: fix protocol for http://codex.wordpress.org/Administration_Over_SSL
		//fix different site (WordPress Address) and home (Site Address) url for iframe security
		$siteUrl    = get_site_url();
		$homeUrl    = get_home_url();
		$siteUrlArr = parse_url( $siteUrl );
		$homeUrlArr = parse_url( $homeUrl );
		if ( $homeUrlArr['scheme'] !== $siteUrlArr['scheme'] || $homeUrlArr['host'] !== $siteUrlArr['host'] ) {
			$iframeSrc = str_replace( $homeUrl, $siteUrl, $iframeSrc );
		}

		// Fix for Domain Mapping plugin (separate frontend and backend domains)
		if ( is_plugin_active( 'domain-mapping/domain-mapping.php' ) ) {
			$iframeSrc = add_query_arg( 'dm', 'bypass', $iframeSrc );
		}

		$iframeSrc = add_query_arg( array( 'mpce-post-id' => $postId ), $iframeSrc );
		$iframeSrc = add_query_arg( array( 'motopress-ce' => '1' ), $iframeSrc );
		$iframeSrc = add_query_arg( '_wpnonce', wp_create_nonce( MPCEContentManager::getIframeNonceAction( $postId ) ), $iframeSrc);

		return $iframeSrc;
	}

	public function motopressCEHTML() {

		if ( ! user_can_richedit() ) {
			return false;
		}

		$saveObjectTemplateBtnTitle = __('Save Object Template', 'motopress-content-editor-lite');
		$savePageTemplateBtnTitle = __('Save Page Template', 'motopress-content-editor-lite');

		$duplicateBtnTitle = __( "Duplicate Object", 'motopress-content-editor-lite' );
		$logoSrc           = apply_filters( 'mpce_logo_src', mpceSettings()['plugin_dir_url'] . 'images/logo.png?ver=' . mpceSettings()['plugin_version'] );
		$isHideTutorials   = apply_filters( 'mpce_hide_tutorials', false );

		require mpceSettings()['plugin_dir_path'] . 'templates/editor.php';
	}

	public function enqueueStyles() {

		if ( ! MPCEAccess::getInstance()->hasAccess() ) {
			return;
		}

		$scriptSuffix = mpceSettings()['script_suffix'];
		$pUrl = mpceSettings()['plugin_dir_url'];
		$pVer = mpceSettings()['plugin_version'];

		wp_register_style( 'mpce-bootstrap-datetimepicker', $pUrl . 'bootstrap/datetimepicker/bootstrap-datetimepicker.min.css', array(), $pVer );
		wp_register_style( 'mpce-select2', $pUrl . 'vendors/select2/select2.min.css', array(), $pVer );
		wp_register_style( 'mpce-bootstrap-select', $pUrl . 'bootstrap/select/bootstrap-select.min.css', array(), $pVer );
		wp_register_style( 'mpce-jquery-ui-dialog', includes_url( 'css/jquery-ui-dialog.css' ), array(), $pVer );
		wp_register_style( 'mpce-spectrum-theme', $pUrl . 'vendors/bgrins-spectrum/build/spectrum_theme.css', array(), $pVer );

		wp_register_style( 'mpce-style', $pUrl . 'includes/css/style' . $scriptSuffix . '.css', null, $pVer );
		// @todo register font awesome in one place @see also motopress-content-editor.php:motopressCEWpHead
		wp_register_style( 'mpce-font-awesome', $pUrl . 'fonts/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

		wp_register_style( 'mpce', $pUrl . 'mp/ce/css/ce' . $scriptSuffix . '.css', array(
			'common',
			'mpce-font-awesome',
			'mpce-bootstrap-datetimepicker',
			'mpce-select2',
			'mpce-jquery-ui-dialog',
			'mpce-bootstrap-select',
			'mpce-spectrum-theme',
			'mpce-style'
		), $pVer );

		wp_enqueue_style( 'mpce' );
		wp_enqueue_style( 'mpce-bootstrap-icon', $pUrl . 'bootstrap/bootstrap-icon.min.css', array(), $pVer );

		$customPreloaderImageSrc = apply_filters( 'mpce_preloader_src', false );
		if ( $customPreloaderImageSrc ) {
			echo '<style type="text/css">#motopress-preload{background-image: url("' . esc_url( $customPreloaderImageSrc ) . '") !important;}</style>';
		}
	}

	public function enqueueScripts() {

		if ( ! MPCEAccess::getInstance()->hasAccess() ) {
			return;
		}

		$scriptSuffix = mpceSettings()['script_suffix'];
        $post = get_post();
        $postID = get_the_ID();
		$postType = get_post_type();
        $pageSettings = MPCEContentManager::getInstance()->getPageSettings( $postID );

		require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/ThemeFix.php';
		$themeFix = new MPCEThemeFix( MPCEThemeFix::DEACTIVATE );

		wp_register_script( 'mpce-knob', mpceSettings()['plugin_dir_url'] . 'knob/jquery.knob.min.js', array('jquery'), mpceSettings()['plugin_version'], false );
		wp_register_script( 'mpce-canjs', mpceSettings()['plugin_dir_url'] . 'vendors/canjs/can.custom.min.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-bootstrap', mpceSettings()['plugin_dir_url'] . 'bootstrap/bootstrap2-custom.min.js', array(
			'jquery',
			'jquery-ui-dialog',
			'jquery-ui-tabs',
			'jquery-ui-slider',
			'jquery-ui-spinner',
			'jquery-ui-accordion',
			'jquery-ui-sortable',
		), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-bootstrapx-clickover', mpceSettings()['plugin_dir_url'] . 'bootstrap/clickover/bootstrapx-clickover.min.js', array(
			'jquery',
			'mpce-bootstrap',
		), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-bootstrap-select', mpceSettings()['plugin_dir_url'] . 'bootstrap/select/bootstrap-select.min.js', array( 'jquery', 'mpce-bootstrap' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-jquery-fonticonpicker', mpceSettings()['plugin_dir_url'] . 'vendors/fontIconPicker/jquery.fonticonpicker.min.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-spectrum', mpceSettings()['plugin_dir_url'] . 'vendors/bgrins-spectrum/build/spectrum-min.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-moment', mpceSettings()['plugin_dir_url'] . 'vendors/moment.js/moment.min.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-bootstrap-datetimepicker', mpceSettings()['plugin_dir_url'] . 'bootstrap/datetimepicker/bootstrap-datetimepicker.min.js', array( 'jquery', 'mpce-bootstrap' ), mpceSettings()['plugin_version'], true );
		wp_register_script( 'mpce-select2', mpceSettings()['plugin_dir_url'] . 'vendors/select2/select2.min.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
		if ( mpceSettings()['lang']['select2'] !== 'en' ) {
			wp_enqueue_script( 'mpce-select2-locale', mpceSettings()['plugin_dir_url'] . 'vendors/select2/select2_locale_' . mpceSettings()['lang']['select2'] . '.js', array(
				'jquery',
				'mpce-select2',
			), mpceSettings()['plugin_version'], true );
		}

		wp_enqueue_media(array( 'post' => get_the_ID() ));

		wp_enqueue_script( 'mpce-top-editor', mpceSettings()['plugin_dir_url'] . 'mp/ce/editor' . $scriptSuffix . '.js', array(
			'jquery',
			'jquery-ui-dialog',
			'jquery-ui-tabs',
			'jquery-ui-slider',
			'jquery-ui-spinner',
			'jquery-ui-accordion',
			'jquery-ui-sortable',
			'mpce-select2',
			'mpce-knob',
			'mpce-bootstrap',
			'mpce-bootstrapx-clickover',
			'mpce-bootstrap-select',
			'mpce-jquery-fonticonpicker',
			'mpce-spectrum',
			'mpce-moment',
			'mpce-bootstrap-datetimepicker',
			'mpce-select2',
			'mpce-canjs',
		), mpceSettings()['plugin_version'], true );

		wp_localize_script( 'mpce-top-editor', 'motopress', mpceSettings()['motopress_localize'] );
		wp_localize_script( 'mpce-top-editor', 'motopressCE', apply_filters( 'motopress_ce_localize_settings', array(
			'postID'              => $postID,
			'nonces'              => array(
				'motopress_ce_get_wp_settings'             => wp_create_nonce( 'wp_ajax_motopress_ce_get_wp_settings' ),
				'motopress_ce_render_shortcode'            => wp_create_nonce( 'wp_ajax_motopress_ce_render_shortcode' ),
				'motopress_ce_render_shortcodes_string'    => wp_create_nonce( 'wp_ajax_motopress_ce_render_shortcodes_string' ),
				'motopress_ce_get_attachment_thumbnail'    => wp_create_nonce( 'wp_ajax_motopress_ce_get_attachment_thumbnail' ),
				'motopress_ce_colorpicker_update_palettes' => wp_create_nonce( 'wp_ajax_motopress_ce_colorpicker_update_palettes' ),
				'motopress_ce_render_youtube_bg'           => wp_create_nonce( 'wp_ajax_motopress_ce_render_youtube_bg' ),
				'motopress_ce_render_video_bg'             => wp_create_nonce( 'wp_ajax_motopress_ce_render_video_bg' ),
				'motopress_ce_get_translations'            => wp_create_nonce( 'wp_ajax_motopress_ce_get_translations' ),
				'motopress_ce_save_post'                   => wp_create_nonce( 'wp_ajax_motopress_ce_save_post' ),
				'motopress_ce_save_object_templates'         => wp_create_nonce( 'wp_ajax_motopress_ce_save_object_templates' ),
				'motopress_ce_save_presets'                => wp_create_nonce( 'wp_ajax_motopress_ce_save_presets' ),
			),
			'postData'            => array(
				'previewUrl'    => get_preview_post_link(),
				'viewUrl'       => get_permalink(),
				'postType'      => $postType,
				'postTypeLabel' => get_post_type() ==
				                   'page' ? __( "Page", 'motopress-content-editor-lite' ) : __( "Post", 'motopress-content-editor-lite' ),
				'statusList'    => get_post_statuses(),
				'templateList'  => $this->getPageTemplateList( $postID ),
				'settings'      => array(
					'page' => array(
						'title'     => $post->post_title,
						'hideTitle' => $pageSettings['hide_title'] ? 1 : 0,
						'template'  => $this->getPageTemplate( $postID ),
						'status'    => get_post_status(),
					),
				),
				'ceUrl' => $this->generateIframeUrl()
			),
			'settings'            => array(
				'wp'                   => mpceSettings(),
				'library'              => MPCELibrary::getInstance()->getData(),
				'objectTemplatesLibrary' => MPCEObjectTemplatesLibrary::getInstance()->get(),
				'translations'         => motopressCEGetLangStrings(),
				'rowLayouts'           => array(
					array( 12 ),
					array( 6, 6 ),
					array( 4, 4, 4 ),
					array( 3, 3, 3, 3 ),
					array( 4, 8 ),
					array( 3, 6, 3 ),
					array( 2, 2, 2, 2, 2, 2 ),
					array( 2, 8, 2 ),
				),
			),
			'rendered_shortcodes' => $this->prepareRenderedShortcodes(),
			'info'                => array(
				'is_headway_themes' => $themeFix->isHeadwayTheme(),
			),
			'styleEditor'         => MPCECustomStyleManager::getLocalizeJSData(),
		), $postID ) );

		motopressCECheckDomainMapping();

		if ( get_user_meta( get_current_user_id(), 'rich_editing', true ) === 'false' && ! wp_script_is( 'editor' ) ) {
			wp_enqueue_script( 'editor' );
		}

		wp_enqueue_script( 'wp-link' );
	}

	function prepareRenderedShortcodes() {

		$motopressCELibrary = MPCELibrary::getInstance();

		$gridObjects        = $motopressCELibrary->getGridObjects();
		$renderedShortcodes = array(
			'grid'  => array(),
			'empty' => array(),
		);

		// Rendered Grid Objects
		foreach (
			array(
				$gridObjects['row']['shortcode'],
				$gridObjects['row']['inner'],
				$gridObjects['span']['shortcode'],
				$gridObjects['span']['inner'],
			) as $shortcodeName
		) {
			$shortcode                                    = generateShortcodeFromLibrary( $shortcodeName );
			$renderedShortcodes['grid'][ $shortcodeName ] = do_shortcode( $shortcode );
		}

		// Rendered Empty Spans
		foreach ( array( $gridObjects['span']['shortcode'], $gridObjects['span']['inner'] ) as $shortcodeName ) {
			$shortcode = generateShortcodeFromLibrary( $shortcodeName, array(
				'motopress-empty',
				'mp-hidden-phone',
			) );

			$renderedShortcodes['empty'][ $shortcodeName ] = do_shortcode( $shortcode );
		}

		return $renderedShortcodes;
	}

	private function renderEditor() {
		include $this->templatePath;
		die;
	}

	/**
	 * @param int|WP_Post $post
	 *
	 * @return string
	 */
	private function getPageTemplate( $post ){
		require_once ABSPATH . '/wp-admin/includes/theme.php';

		$postId = is_a($post, 'WP_Post') ? $post->ID : $post;
		$template =  get_post_meta( $postId, '_wp_page_template', true );

		$postType = get_post_type($post);
		$templates = get_page_templates( $post, $postType );

		if ( !in_array( $template, $templates ) ) {
			$template = 'default';
		}

		return $template;
	}

	/**
	 * @param int|WP_Post $post
	 *
	 * @return array
	 */
	private function getPageTemplateList( $post ) {
	    require_once ABSPATH . '/wp-admin/includes/theme.php';

	    $postType = get_post_type($post);
	    $templates = get_page_templates( $post, $postType );

        $templates['Default'] = 'default';
        ksort( $templates );

        $_templates = array();
        foreach ( $templates as $name => $file ) {
            $_templates[] = array(
                'name' => $name,
                'value' => $file,
            );
        }

	    return $_templates;
    }

}