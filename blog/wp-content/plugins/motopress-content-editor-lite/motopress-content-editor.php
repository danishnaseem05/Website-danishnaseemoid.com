<?php
/*
Plugin Name: MotoPress Content Editor Lite
Plugin URI: https://motopress.com/products/content-editor/
Description: Drag and drop frontend page builder for any theme.
Version: 3.0.6
Author: MotoPress
Author URI: https://motopress.com/
Text Domain: motopress-content-editor-lite
Domain Path: /lang
License: GPLv2 or later
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(!is_plugin_active('motopress-content-editor/motopress-content-editor.php')) {
	/*
	 * Allow symlinked plugin for wordpress < 3.9
	 */
	global $wp_version;
	if ( version_compare( $wp_version, '3.9', '<' ) && isset( $network_plugin ) ) {
		$motopress_plugin_file = $network_plugin;
	} else {
		$motopress_plugin_file = __FILE__;
	}
	$motopress_plugin_dir_path = plugin_dir_path( $motopress_plugin_file );

	require_once $motopress_plugin_dir_path . 'includes/Requirements.php';
	require_once $motopress_plugin_dir_path . 'includes/functions.php';
	require_once $motopress_plugin_dir_path . 'includes/settings.php';
	mpceInitSettings(array(
		'pluginFile' => $motopress_plugin_file,
		'pluginVar' => isset($plugin) ? $plugin : null,
	));
	require_once $motopress_plugin_dir_path . 'includes/compatibility.php';
	require_once $motopress_plugin_dir_path . 'includes/MPCEUtils.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/Access.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/MPCECustomStyleManager.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/MPCERenderContent.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/shortcodes/post_grid/MPCEShortcodePostsGrid.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/shortcode/ShortcodeCommon.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/MPCEContentManager.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/MPCERevisionManager.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/MPCEObjectTemplatesLibrary.php';
	require_once $motopress_plugin_dir_path . 'includes/ce/renderContent.php';
    require_once $motopress_plugin_dir_path . 'includes/ce/MPCEGutenbergSupport.php';

    $motopressCEGutenbergSwitcher = new MPCEGutenbergSupport();

	add_action( 'wp_head', 'motopressCEWpHead', 7 );

	add_action( 'plugins_loaded', 'motopressCELoadTextdomain' );
	function motopressCELoadTextdomain() {
		load_plugin_textdomain( 'motopress-content-editor-lite', false, mpceSettings()['plugin_symlink_dir_name'] . '/lang/' );
	}

// Custom CSS [if exsists]
	add_action( 'wp_head', 'motopressCECustomCSS', 999 );
	function motopressCECustomCSS() {
		if ( ! mpceSettings()['wp_upload_dir_error'] ) {
			if ( file_exists( mpceSettings()['custom_css_file_path'] ) ) {
				echo "\n<!-- MotoPress Custom CSS Start -->\n<style type=\"text/css\">\n@import url('" . mpceSettings()['custom_css_file_url'] . "?" . filemtime( mpceSettings()['custom_css_file_path'] ) . "');\n</style>\n<!-- MotoPress Custom CSS End -->\n";
			}
		}
	}

// Custom CSS END

	function motopressCEGetWPScriptVer( $script ) {
		$ver            = false;
		$path           = ABSPATH . WPINC;
		$versionPattern = '/v((\d+\.{1}){1}(\d+){1}(\.{1}\d+)?)/is';
		switch ( $script ) {
			case 'jQuery':
				$path .= '/js/jquery/jquery.js';
				break;
			case 'jQueryUI':
				if ( mpce_is_wp_version( '4.1', '<' ) ) {
					$path .= '/js/jquery/ui/jquery.ui.core.min.js';
				} else {
					$path .= '/js/jquery/ui/core.min.js';
					$versionPattern = '/jQuery UI Core ((\d+\.{1}){1}(\d+){1}(\.{1}\d+)?)/is';
				}
				break;
		}

		if ( is_file( $path ) ) {
			if ( file_exists( $path ) ) {
				$content = file_get_contents( $path );
				if ( $content ) {
					preg_match( $versionPattern, $content, $matches );
					if ( ! empty( $matches[1] ) ) {
						$ver = $matches[1];
					}
				}
			}
		}

		return $ver;
	}

	function motopressCEWpHead() {

		$suffix         = mpceSettings()['script_suffix'];
		$pUrl           = mpceSettings()['plugin_dir_url'];
		$pVer           = mpceSettings()['plugin_version'];
		$vendorInFooter = $frontInFooter = true;

		wp_register_style( 'mpce-bootstrap-grid', $pUrl . 'bootstrap/bootstrap-grid.min.css', array(), $pVer );

		wp_register_style( 'mpce-theme', $pUrl . 'includes/css/theme' . $suffix . '.css', array(), $pVer );

		if ( ! wp_script_is( 'jquery' ) ) {
			wp_enqueue_script( 'jquery' );
		}

		wp_register_style( 'mpce-flexslider', $pUrl . 'vendors/flexslider/flexslider.min.css', array(), $pVer );
		wp_register_script( 'mpce-flexslider', $pUrl . 'vendors/flexslider/jquery.flexslider-min.js', array( 'jquery' ), $pVer, $vendorInFooter );
		wp_register_style( 'mpce-font-awesome', $pUrl . 'fonts/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

		wp_register_script( 'google-charts-api', 'https://www.google.com/jsapi', array(), null, $vendorInFooter );
		wp_register_script( 'mp-youtube-api', '//www.youtube.com/player_api', array(), null, $vendorInFooter );
		wp_register_script( 'stellar', $pUrl . 'vendors/stellar/jquery.stellar.min.js', array( 'jquery' ), $pVer, $vendorInFooter );
		wp_register_script( 'mpce-magnific-popup', $pUrl . 'vendors/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), $pVer, $vendorInFooter );
		wp_register_script( 'mp-js-cookie', $pUrl . 'vendors/js-cookie/js.cookie.min.js', array(), $pVer );

		wp_register_script( 'mpce-countdown-plugin', $pUrl . 'vendors/keith-wood-countdown-timer/js/jquery.plugin_countdown.min.js', array( 'jquery' ), $pVer, $vendorInFooter );
		wp_register_script( 'mpce-countdown-timer', $pUrl . 'vendors/keith-wood-countdown-timer/js/jquery.countdown.min.js', array( 'jquery' ), $pVer, $vendorInFooter );

		motopressLocalizeCountdown();

		wp_register_script( 'mpce-waypoints', $pUrl . 'vendors/imakewebthings-waypoints/jquery.waypoints.min.js', array( 'jquery' ), $pVer, $vendorInFooter );
		wp_register_script( 'mp-frontend', $pUrl . 'includes/js/mp-frontend' . $suffix . '.js', array( 'jquery' ), $pVer, $frontInFooter );

		wp_localize_script( 'mp-frontend', 'MPCEVars', array(
			'fixedRowWidth' => get_option( 'motopress-ce-fixed-row-width', mpceSettings()['default_fixed_row_width'] ),
			'isEditor'      => mpceIsEditorScene(),
			'postsGridData' => array(
				'admin_ajax' => admin_url( 'admin-ajax.php' ),
				'nonces'     => array(
					'motopress_ce_posts_grid_filter'    => wp_create_nonce( 'wp_ajax_motopress_ce_posts_grid_filter' ),
					'motopress_ce_posts_grid_turn_page' => wp_create_nonce( 'wp_ajax_motopress_ce_posts_grid_turn_page' ),
					'motopress_ce_posts_grid_load_more' => wp_create_nonce( 'wp_ajax_motopress_ce_posts_grid_load_more' ),
				),
			)
		) );

		wp_enqueue_style( 'mpce-theme' );
		motopressCEAddFixedRowWidthStyle( 'mpce-theme' );
		wp_enqueue_style( 'mpce-bootstrap-grid' );

		if ( MPCEContentManager::isBuilderRunning() ) {
			MPCEScene::getInstance()->enqueueScripts();
		}
	}

	function motopressLocalizeCountdown(){
		// add language file
		$mp_keith_wood_countdown_timer_languages = array(
			"sq"    => "sq",
			"ar"    => "ar",
			"hy"    => "hy",
			"bn-BD" => "bn",
			"bs-BA" => "bs",
			"bg-BG" => "bg",
			"ca"    => "ca",
			"hr"    => "hr",
			"cs-CZ" => "cs",
			"da-DK" => "da",
			"nl-NL" => "nl",
			"et"    => "et",
			"fo"    => "fo",
			"fi"    => "fi",
			"gl-ES" => "gl",
			"de-DE" => "de",
			"el"    => "el",
			"gu"    => "gu",
			"he-IL" => "he",
			"hu-HU" => "hu",
			"is-IS" => "is",
			"id-ID" => "id",
			"ja"    => "ja",
			"kn"    => "kn",
			"ko-KR" => "ko",
			"lv"    => "lv",
			"lt-LT" => "lt",
			"ms-MY" => "ms",
			"ms-MY" => "ml",
			"ml-IN" => "ml",
			"fa-IR" => "fa",
			"pl-PL" => "pl",
			"ro-RO" => "ro",
			"ru-RU" => "ru",
			"sr-RS" => "sr",
			"sr-RS" => "sr-SR",
			"sk-SK" => "sk",
			"sl-SI" => "sl",
			"sv-SE" => "sv",
			"th"    => "th",
			"tr-TR" => "tr",
			"uk"    => "uk",
			"ur"    => "ur",
			"uz-UZ" => "uz",
			"vi"    => "vi",
			"cy"    => "cy",
		);
		$wp_lang                                 = get_bloginfo( 'language' );
		$keith_wood_timer_lang                   = array_key_exists( $wp_lang, $mp_keith_wood_countdown_timer_languages ) ? $mp_keith_wood_countdown_timer_languages[ $wp_lang ] : 'en';
		if ( $keith_wood_timer_lang != 'en' ) {
			wp_register_script( 'keith-wood-countdown-language', mpceSettings()['plugin_dir_url'] . 'vendors/keith-wood-countdown-timer/js/lang/jquery.countdown-' . $keith_wood_timer_lang . '.js', array(
				'mpce-countdown-plugin',
				'mpce-countdown-timer',
			), mpceSettings()['plugin_version'], true );
		}
	}

	/**
	 *  Add fixed row width styles to a registered stylesheet.
	 *
	 * @param string $handle Name of the stylesheet to add the extra styles to. Must be lowercase.
	 */
	function motopressCEAddFixedRowWidthStyle( $handle ) {
		$fixedRowWidth = get_option( 'motopress-ce-fixed-row-width', mpceSettings()['default_fixed_row_width'] );

		$style = '.mp-row-fixed-width {' . 'max-width:' . $fixedRowWidth . 'px;' . '}';
		wp_add_inline_style( $handle, $style );
	}

	MPCECustomStyleManager::getInstance();
	MPCEObjectTemplatesLibrary::getInstance();
	MPCEShortcode::getInstance();

	/**
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	function motopressCEAdminBarMenu( $wp_admin_bar ) {
		if ( is_admin_bar_showing() && ! is_admin() ) {
			global $wp_the_query;
			$current_object = $wp_the_query->get_queried_object();
			if ( ! empty( $current_object ) && ! empty( $current_object->post_type ) && ( $post_type_object = get_post_type_object( $current_object->post_type ) ) && $post_type_object->show_ui && $post_type_object->show_in_admin_bar ) {
				$postId = $current_object->ID;

				if ( MPCEAccess::getInstance()->hasAccess( $postId ) ) {
					$isHideLinkEditWith = apply_filters( 'mpce_hide_link_edit_with', false );
					if ( ! $isHideLinkEditWith ) {
						$menuTitle = __( "Visual Builder", 'motopress-content-editor-lite' );
						if ( MPCEFrontEndBuilder::getInstance()->getPostLocker()->isPostLocked( $postId ) ) {
							$menuTitle = sprintf( __( "%s (Locked)", 'motopress-content-editor-lite' ), $menuTitle );
						}
						$wp_admin_bar->add_menu( array(
							'href'   => add_query_arg( 'mpce-edit', '1', get_permalink( $postId ) ),
							'parent' => false,
							'id'     => 'motopress-edit',
							'title'  => $menuTitle,
							'meta'   => array(
								'title' => $menuTitle,
							),
						) );
					}
				}
			}
		}
	}

	add_action( 'admin_bar_menu', 'motopressCEAdminBarMenu', 81 );

	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/Library.php';

	function motopressCEWPInit() {
		if ( ! is_admin() ) {
			if ( ! isset( $motopressCERequirements ) ) {
				global $motopressCERequirements;
				$motopressCERequirements = new MPCERequirements();
			}
			$motopressCELibrary = MPCELibrary::getInstance();
		}
	}

	add_action( 'init', 'motopressCEWPInit' );

	function motopressSetBrandName() {
		global $motopressCESettings;
		$motopressCESettings['brand_name'] = apply_filters( 'mpce_brand_name', 'MotoPress' );
	}

	add_action( 'after_setup_theme', 'motopressSetBrandName' );

	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/contentEditorFunctions.php';
	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/MPCEPostLocker.php';
	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/MPCEFrontendBuilder.php';
	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/MPCEScene.php';

	// Init Builder
	MPCEFrontEndBuilder::getInstance();

	// Init Scene
	MPCEScene::getInstance();

	require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/ajaxActions.php';

	if ( ! is_admin() ) {
		add_action( 'wp', array( 'MPCEShortcode', 'setCurPostData' ) );
	} else {
		require_once mpceSettings()['plugin_dir_path'] . 'includes/admin.php';
	}
// WARNING! Do not write code below this line , if you are not sure that it is actually necessary.
}
else {
	add_action( 'admin_notices', 'motopressCELiteConflictNotice' ); 
	if (is_multisite()) add_action('network_admin_notices', 'motopressCELiteConflictNotice');
}
function motopressCELiteConflictNotice() {
	$class = "error";
	$message = "<b>MotoPress Content Editor Lite</b> plugin and <b>MotoPress Content Editor</b> plugin do not work simultaneously. Deactivate <b>MotoPress Content Editor Lite</b> plugin.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}
