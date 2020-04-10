<?php

if ( ! isset( $motopressCERequirements ) ) {
	$motopressCERequirements = new MPCERequirements();
}
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/renderShortcode.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/renderShortcodesString.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/getAttachmentThumbnail.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/updatePalettes.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/updateObjectTemplatesLibrary.php';
require_once mpceSettings()['plugin_dir_path'] . 'includes/ce/updateStylePresets.php';

add_action( 'wp_ajax_motopress_ce_get_wp_settings', 'motopressCEGetWpSettings' );
add_action( 'wp_ajax_motopress_ce_render_shortcode', 'motopressCERenderShortcode' );
add_action( 'wp_ajax_motopress_ce_render_shortcodes_string', 'motopressCERenderShortcodeString' );
add_action( 'wp_ajax_motopress_ce_get_attachment_thumbnail', 'motopressCEGetAttachmentThumbnail' );
add_action( 'wp_ajax_motopress_ce_colorpicker_update_palettes', 'motopressCEupdatePalettes' );
add_action( 'wp_ajax_motopress_ce_render_youtube_bg', array( 'MPCEShortcode', 'renderYoutubeBackgroundVideo' ) );
add_action( 'wp_ajax_motopress_ce_render_video_bg', array( 'MPCEShortcode', 'renderHTML5BackgroundVideo' ) );

add_action( 'wp_ajax_motopress_ce_save_post', array( MPCEContentManager::getInstance(), 'savePostAjax' ) );
add_action( 'wp_ajax_motopress_ce_save_object_templates', 'motopressCEUpdateObjectTemplatesLibrary' );
add_action( 'wp_ajax_motopress_ce_save_presets', 'motopressCEUpdateStylePresets' );