<?php

/**
 * Interface iMPCEShortcode
 * All methods must begin with an underscore character.
 * All methods present shortcode tag name and callback.
 * These methods will be parsed later to create callbacks for editor shortcodes.
 */
interface iMPCEShortcode extends iMPCEShortcodeBasic, iMPCEShortcodeTheContent {}

interface iMPCEShortcodeAtts {
	/**
	 * Get shortcode default attributes bu tag name
	 * @param string $tag Shortcode tag name
	 * @return array
	 */
	static function get($tag);

	/**
	 * Get style shortcode attributes
	 * @return array
	 */
	static function getStyle();

	/**
	 * Mixin style and class attributes to passed attributes
	 * @param array $atts
	 * @return mixed
	 */
	static function addStyle($atts = array());
}

/** Basic callbacks */
interface iMPCEShortcodeBasic {
	function mp_row($atts, $content, $tag);
	function mp_row_inner($atts, $content, $tag);
	function mp_span($atts, $content, $tag);
	function mp_span_inner($atts, $content, $tag);
	function mp_text($atts, $content, $tag);
	function mp_heading($atts, $content, $tag);
	function mp_image($atts, $content, $tag);
	function mp_image_slider($atts, $content, $tag);
	function mp_grid_gallery($atts, $content, $tag);
	function mp_video($atts, $content, $tag);
	function mp_code($atts, $content, $tag);
	function mp_space($atts, $content, $tag);
	function mp_button($atts, $content, $tag);
	function mp_icon($atts, $content, $tag);
	function mp_download_button($atts, $content, $tag);
	function mp_countdown_timer($atts, $content, $tag);
	function mp_wp_archives($atts, $content, $tag);
	function mp_wp_calendar($atts, $content, $tag);
	function mp_wp_categories($atts, $content, $tag);
	function mp_wp_navmenu($atts, $content, $tag);
	function mp_wp_meta($atts, $content, $tag);
	function mp_wp_pages($atts, $content, $tag);
	function mp_wp_posts($atts, $content, $tag);
	function mp_wp_comments($atts, $content, $tag);
	function mp_wp_rss($atts, $content, $tag);
	function mp_wp_search($atts, $content, $tag);
	function mp_wp_tagcloud($atts, $content, $tag);
	function mp_wp_widgets_area($atts, $content, $tag);
	function mp_gmap($atts, $content, $tag);
	function mp_embed($atts, $content, $tag);
	function mp_quote($atts, $content, $tag);
	function mp_members_content($atts, $content, $tag);
	function mp_social_buttons($atts, $content, $tag);
	function mp_social_profile($atts, $content, $tag);
	function mp_google_chart($atts, $content, $tag);
	function mp_wp_audio($atts, $content, $tag);
	function mp_tabs($atts, $content, $tag);
	function mp_tab($atts, $content, $tag);
	function mp_accordion($atts, $content, $tag);
	function mp_accordion_item($atts, $content, $tag);
	function mp_table($atts, $content, $tag);
	function mp_service_box($atts, $content, $tag);
	function mp_modal($atts, $content, $tag);
	function mp_popup($atts, $content, $tag);
	function mp_list($atts, $content, $tag);
	function mp_button_inner($atts, $content, $tag);
	function mp_button_group($atts, $content, $tag);
	function mp_cta($atts, $content, $tag);
}

/** Callbacks are registered after `the_content` filter */
interface iMPCEShortcodeTheContent {
	function mp_posts_grid($atts, $content, $tag);
	function mp_posts_slider($atts, $content, $tag);
}