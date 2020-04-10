<?php
require_once mpceSettings()['plugin_dir_path'] . 'includes/motopressOptions.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/Flash.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/Tutorials.php';

add_action( 'admin_init', 'motopressCEInit' );
add_action( 'admin_menu', 'motopressCEMenu', 11 );



function motopressCEInit() {
	$suffix              = mpceSettings()['script_suffix'];
	$pUrl                = mpceSettings()['plugin_dir_url'];
	$pVer                = mpceSettings()['plugin_version'];
	$brwsrDetectInFooter = false;

	wp_register_style( 'mpce-style', $pUrl . 'includes/css/style' . $suffix . '.css', array(), $pVer );
	wp_register_script( 'mpce-detect-browser', $pUrl . 'mp/core/detectBrowser/detectBrowser' . $suffix . '.js', array(), $pVer, $brwsrDetectInFooter );

	wp_enqueue_script( 'mpce-detect-browser' );

	if ( ! is_array( get_option( 'motopress_google_font_classes' ) ) ) {
		add_action( 'admin_notices', 'motopress_google_font_not_writable_notice' );
		$fontClasses = array(
			'opensans' => array(
				'family'   => 'Open Sans',
				'variants' => array( '300', 'regular', '700' ),
			),
		);
		saveGoogleFontClasses( $fontClasses );
	}
}

/**
 * @todo make updates with function calls instead of including files
 */
function motopressCEAfterUpdate() {

	$pluginVer = mpceSettings()['plugin_version'];
	// `2.3.0` is a version when updates were integrated
	$dbVer            = get_option( 'motopress_db_version', '2.3.0' );
	$versionsToUpdate = mpceSettings()['versions_to_update'];

	if ( $dbVer !== $pluginVer ) {

		foreach ( $versionsToUpdate as $verToUpdate ) {

			if ( version_compare( $dbVer, $verToUpdate, '<' ) && version_compare( $verToUpdate, $pluginVer, '<=' ) ) {

				$updaterFile = mpceSettings()['plugin_dir_path'] . "includes/updates/{$verToUpdate}.php";

				if ( file_exists( $updaterFile ) ) {
					include( $updaterFile );
				}

				update_option( 'motopress_db_version', $verToUpdate );

			}

		}

		update_option( 'motopress_db_version', $pluginVer );

	}

}

add_action( 'admin_init', 'motopressCEAfterUpdate' );


function motopress_google_font_not_writable_notice() {
	$error = motopress_check_google_font_dir_permissions();
	if ( isset( $error['error'] ) ) {
		echo '<div class="error"><p>' . __( "Error while installing the default set of Google Fonts.", 'motopress-content-editor-lite' ) . '</p><p>' . $error['error'] . '</p></div>';
	}
}

/**
 * Check permissions for writing Google Font's style files.
 *
 * @param boolean $mkdir creates the necessary directories
 *
 * @return array $error
 */
function motopress_check_google_font_dir_permissions( $mkdir = false ) {
	$error = array();
	if ( ! is_dir( mpceSettings()['google_font_classes_dir'] ) ) {
		if ( ! is_dir( mpceSettings()['plugin_upload_dir_path'] ) ) {
			if ( is_writable( mpceSettings()['wp_upload_dir'] ) ) {
				if ( $mkdir ) {
					mkdir( mpceSettings()['plugin_upload_dir_path'], 0777 );
					mkdir( mpceSettings()['google_font_classes_dir'], 0777 );
				}
			} else {
				$error['error'] = sprintf( __( "Note: you are not able to edit Google Fonts because the directory %s not found or not writable.", 'motopress-content-editor-lite' ), mpceSettings()['wp_upload_dir'] );
			}
		} elseif ( is_writable( mpceSettings()['plugin_upload_dir_path'] ) ) {
			if ( $mkdir ) {
				mkdir( mpceSettings()['google_font_classes_dir'], 0777 );
			}
		} else {
			$error['error'] = sprintf( __( "Note: you are not able to edit Google Fonts because the directory %s not found or not writable.", 'motopress-content-editor-lite' ), mpceSettings()['plugin_upload_dir_path'] );
		}
	}
	if ( ! isset( $error['error'] ) && ! is_writable( mpceSettings()['google_font_classes_dir'] ) ) {
		$error['error'] = sprintf( __( "Note: you are not able to edit Google Fonts because the directory %s not found or not writable.", 'motopress-content-editor-lite' ), mpceSettings()['google_font_classes_dir'] );
	}

	return $error;
}

function motopressCEMenu() {

	if ( ! MPCEAccess::getInstance()->isCEDisabledForCurRole() ) {
		global $motopressCERequirements;
		$motopressCERequirements = new MPCERequirements();
		global $motopressCEIsjQueryVer;
		$motopressCEIsjQueryVer = motopressCECheckjQueryVer();

		$isHideMenu = apply_filters( 'mpce_hide_menu_page', false );
		if ( ! $isHideMenu ) {
			$mainMenuSlug = 'motopress';

			$mainMenuExists = has_action( 'admin_menu', 'motopressMenu' );
			if ( ! $mainMenuExists ) {
				$iconSrc  = apply_filters( 'mpce_menu_icon_src', mpceSettings()['plugin_dir_url'] . 'images/menu-icon.png' );
				$mainPage = add_menu_page( mpceSettings()['brand_name'], mpceSettings()['brand_name'], 'read', $mainMenuSlug, 'motopressCE', $iconSrc );
			} else {
				$optionsHookname = get_plugin_page_hookname( 'motopress_options', $mainMenuSlug );
				remove_action( $optionsHookname, 'motopressOptions' );
				remove_submenu_page( 'motopress', 'motopress_options' );
			}
			$proMenuTitle  = __( "Visual Builder", 'motopress-content-editor-lite' );
			$liteMenuTitle = __( "Visual Builder Lite", 'motopress-content-editor-lite' );
			$menuTitle     = apply_filters( 'mpce_submenu_title', $liteMenuTitle );
			$mainPage      = add_submenu_page( $mainMenuSlug, $menuTitle, $menuTitle, 'read', $mainMenuExists ? 'motopress_content_editor' : 'motopress', 'motopressCE' );
			$hideOptions   = get_site_option( 'motopress-ce-hide-options-on-subsites', '0' );
			if ( $hideOptions === '0' || ( is_multisite() && is_main_site() ) ) {
				$optionsPage = add_submenu_page( $mainMenuSlug, __( "Settings", 'motopress-content-editor-lite' ), __( "Settings", 'motopress-content-editor-lite' ), 'manage_options', 'motopress_options', 'motopressCEOptions' );
				add_action( 'load-' . $optionsPage, 'motopressCESettingsSave' );
				add_action( 'admin_print_styles-' . $optionsPage, 'motopressCEAdminStylesAndScripts' );
				do_action( 'admin_mpce_settings_init', $optionsPage );
			}

			$isHideLicensePage = false
			;
			if ( ! $isHideLicensePage && is_main_site() ) {
				$licenseMenuSlug = 'motopress_license';
				$licensePage     = add_submenu_page( $mainMenuSlug, __( "Licenses", 'motopress-content-editor-lite' ), __( "Licenses", 'motopress-content-editor-lite' ), 'manage_options', $licenseMenuSlug, 'motopressCELicense' );
				add_action( 'load-' . $licensePage, 'motopressCELicenseLoad' );
				add_action( 'admin_print_styles-' . $licensePage, 'motopressCEAdminStylesAndScripts' );
				do_action( 'admin_mpce_license_init', $optionsPage );
				motopressCESetLicenseTabs();
				if (!count(mpceSettings()['license_tabs'])) remove_submenu_page($mainMenuSlug, $licenseMenuSlug);
			}
			add_action( 'admin_print_styles-' . $mainPage, 'motopressCEAdminStylesAndScripts' );
		}

		add_action( 'admin_print_scripts-post.php', 'motopressCEAddTools' );
		add_action( 'admin_print_scripts-post-new.php', 'motopressCEAddTools' );
	}
}

function motopressCEAddTools() {

	if ( MPCEAccess::getInstance()->hasAccess() ) {
		add_action( 'admin_head', 'motopressCEEnqueueAdminScripts' );
		wp_localize_script( 'jquery', 'motopressCE', array() );

//		wp_enqueue_style( 'mpce-style' );

	}
}

function motopressCEEnqueueAdminScripts() {
	global $post, $motopressCEIsjQueryVer;

	$postID = get_the_ID();

	$scriptSuffix = mpceSettings()['script_suffix'];
	$pluginDirUrl = mpceSettings()['plugin_dir_url'];
	$mpceEnabled = MPCEContentManager::isPostEnabledForEditor( $postID );
	wp_register_script( 'mpce-admin', mpceSettings()['plugin_dir_url'] . 'mp/ce/admin/admin.js', array( 'jquery' ), mpceSettings()['plugin_version'], true );
	wp_localize_script( 'mpce-admin', 'MPCEAdmin', array(
		'noTitleLabel'                      => __( "no title", 'motopress-content-editor-lite' ),
		'mpceEnabled'                       => (bool) $mpceEnabled,
		'postId'                            => $postID,
		'postStatus'                        => get_post_status( $postID ),
		'autoOpen'                          => isset( $_GET['motopress-ce-auto-open'] ) && $_GET['motopress-ce-auto-open'] === 'true' ? 'true' : 'false',
		'editUrl'                           => add_query_arg( 'mpce-edit', '1', get_permalink( $postID ) ),
	) );
	wp_enqueue_script( 'mpce-admin' );

	if ( ! extension_loaded( 'mbstring' ) ) {
		add_action( 'admin_notices', 'motopressCEIsMBStringEnabledNotice' );
	}
	if ( ! $motopressCEIsjQueryVer ) {
		add_action( 'admin_notices', 'motopressCEIsjQueryVerNotice' );
	}

	$isHideNativeEditor = apply_filters( 'mpce_hide_native_editor', false );
	?>
	<style type="text/css"><?php
			( $isHideNativeEditor || $mpceEnabled ) && print( '#postdivrich{display: none;}' );
			( $isHideNativeEditor ) && print( '#motopress-ce-tinymce-wrap{display: none;}' );
			( $isHideNativeEditor && $mpceEnabled ) && print( '#mpce-tab-default{display: none;}' );
			?>
	</style>
	<?php
}

function motopressCEAddArea() {
	global $post;

	$postID = $post->ID;
	$postActive = MPCEContentManager::isPostEnabledForEditor($postID);
	$builderActive = MPCEAccess::getInstance()->hasAccess($postID);

	if ($postActive && $builderActive) {
		$content = MPCEContentManager::getEditorContent($postID);

		$editorHeight = (int)get_user_setting('ed_size');
		$editorHeight = $editorHeight ? $editorHeight : 300;
		?>
		<div id="motopress-ce-tinymce-wrap">
			<?php wp_editor($content, MPCEContentManager::CONTENT_EDITOR_ID, array('editor_height' => $editorHeight)); ?>
		</div>
		<?php
	}
}
add_action('edit_form_after_editor', 'motopressCEAddArea');

function motopressCESetLicenseTabs() {

	$_tabs = array();
	$tabs = apply_filters( 'admin_mpce_license_tabs', $_tabs );
	$tabs = is_array( $tabs ) ? $tabs : array();

	uasort( $tabs, 'motopressCESortTabs' );
	mpceSetSetting('license_tabs', $tabs);
}

function motopressCESortTabs( $a, $b ) {
	return $a['priority'] - $b['priority'];
}

function motopressCEAdminStylesAndScripts() {
	$pluginId = isset( $_GET['plugin'] ) ? $_GET['plugin'] : mpceSettings()['plugin_short_name'];

	wp_enqueue_style( 'mpce-style' );
	do_action( 'admin_mpce_settings_print_styles-' . $pluginId );
}

function motopressCE() {
	motopressCEShowWelcomeScreen();
}

function motopressCEShowWelcomeScreen() {
	echo '<div class="motopress-title-page">';
	$logoLargeSrc = apply_filters( 'mpce_large_logo_src', mpceSettings()['plugin_dir_url'] . 'images/logo-large.png?ver=' . mpceSettings()['plugin_version'] );
	echo '<img id="motopress-logo" src="' . esc_url( $logoLargeSrc ) . '" />';
	$siteUrl  = apply_filters( 'mpce_wl_site_url', 'https://motopress.com' );
	$siteName = apply_filters( 'mpce_wl_site_name', 'motopress.com' );
	echo '<p class="motopress-description">' . sprintf( __( "%s Visual Builder makes the process of post building easy and fast. Its Drag&Drop functionality helps to manage your pages, add built-in content elements, edit them visually and see the result right away. For more information visit <a href='%s' target='_blank'>%s</a>", 'motopress-content-editor-lite' ), mpceSettings()['brand_name'], $siteUrl, $siteName ) . '</p>';

	global $motopressCEIsjQueryVer;
	if ( ! $motopressCEIsjQueryVer ) {
		MPCEFlash::setFlash( sprintf( __( "Minimum jQuery version - %s. Minimum jQuery UI version - %s. Please update WordPress to 3.5 or higher.", 'motopress-content-editor-lite' ), MPCERequirements::MIN_JQUERY_VER, MPCERequirements::MIN_JQUERYUI_VER ), 'error' );
	}

	echo '<p><div class="alert alert-error" id="motopress-browser-support-msg" style="display:none;">' . __( "Your browser is not supported. We recommend one of these supported browsers: <a href='http://www.mozilla.org/en-US/firefox/new' target='_blank'>Firefox</a>, <a href='http://www.google.com/chrome' target='_blank'>Chrome</a>.", 'motopress-content-editor-lite' ) . '</div></p>';

	$foundCEButtonDesc = apply_filters( 'mpce_found_button_description', __( "<b>Visual Builder button can be found in the Pages and Posts section.</b>", 'motopress-content-editor-lite' ) );
	echo '<div class="motopress-block"><p class="motopress-title">' . $foundCEButtonDesc . '</p>';
	$foundButtonImageSrc = apply_filters( 'mpce_found_button_img_src', mpceSettings()['plugin_dir_url'] . 'images/ce/ce.png?ver=' . mpceSettings()['plugin_version'] );
	echo '<a href="' . admin_url( 'post-new.php?post_type=page' ) . '" target="_self" id="motopress-ce-link"><img id="motopress-ce" src="' . esc_url( $foundButtonImageSrc ) . '" /></a></div>';

	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			if (MPCEBrowser.IE || MPCEBrowser.Opera) {
				$('.motopress-block #motopress-ce-link')
					.attr('href', 'javascript:void(0);')
					.css({cursor: 'default'});
				$('#motopress-browser-support-msg').show();
			}
		});
	</script>
	<?php
}

// Plugin Activation
function motopressCEInstall( $network_wide ) {
	global $wpdb;
	if ( is_multisite() && $network_wide ) {
		if ( mpce_is_wp_version( '3.7', '>=' ) ) {
			if ( mpce_is_wp_version( '4.6', '<' ) ) {
				$sites = wp_get_sites();
			} else {
				$sites = get_sites();
				$sites = array_map( 'get_object_vars', $sites );
			}

			if ( function_exists( 'array_column' ) ) {
				$blogids = array_column( $sites, 'blog_id' );
			} else {
				$blogids = array();
				foreach ( $sites as $key => $site ) {
					$blogids[ $key ] = $site['blog_id'];
				}
			}
		} else {
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		}
		foreach ( $blogids as $blog_id ) {
			motopressActivationDefaults( $blog_id );
		}
	} else {
		motopressActivationDefaults();
	}
	$autoLicenseKey = apply_filters( 'mpce_auto_license_key', false );
	if ( $autoLicenseKey ) {
		motopressCESetAndActivateLicense( $autoLicenseKey );
	}
}

/*
 * @param bool|int $blog_id Id of blog that need set defaults. FALSE for single site.
 */
function motopressActivationDefaults( $blog_id = false ) {
	if ( $blog_id ) {
//		add_blog_option($blog_id, 'motopress-ce-options', array('post', 'page'));
	} else {
//		add_option('motopress-ce-options', array('post', 'page'));
	}
}

function motopressSetDefaultsForNewBlog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	motopressActivationDefaults( $blog_id );
}

register_activation_hook( mpceSettings('plugin_file'), 'motopressCEInstall' );
// Plugin Activation END
add_action( 'wpmu_new_blog', 'motopressSetDefaultsForNewBlog', 10, 6 );

function motopressCECheckjQueryVer() {
	$jQueryVer   = motopressCEGetWPScriptVer( 'jQuery' );
	$jQueryUIVer = motopressCEGetWPScriptVer( 'jQueryUI' );

	return ( version_compare( $jQueryVer, MPCERequirements::MIN_JQUERY_VER, '>=' ) && version_compare( $jQueryUIVer, MPCERequirements::MIN_JQUERYUI_VER, '>=' ) ) ? true : false;
}

function motopress_edit_link( $actions, $post ) {
	$isHideLinkEditWith = apply_filters( 'mpce_hide_link_edit_with', false );

	if ( ! $isHideLinkEditWith && MPCEAccess::getInstance()->hasAccess( $post->ID ) ) {
		$newActions = array();
		foreach ( $actions as $action => $value ) {
			$newActions[ $action ] = $value;
			if ( $action === 'inline hide-if-no-js' ) {
				$linkTitle                         = __( "Visual Builder", 'motopress-content-editor-lite' );
				$linkUri                           = add_query_arg( 'mpce-edit', '1', get_permalink( $post->ID ) );
				$newActions['motopress_edit_link'] = '<a href="' . $linkUri . '" title="' . esc_attr( $linkTitle ) . '">' . $linkTitle . '</a>';
			}
		}

		$actions = $newActions;
	}

	return $actions;
}

add_filter( 'page_row_actions', 'motopress_edit_link', 10, 2 );
add_filter( 'post_row_actions', 'motopress_edit_link', 10, 2 );