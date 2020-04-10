<?php

if (!class_exists('MPCEShortcodeAtts')) {
	require_once dirname(__FILE__) . '/Interfaces.php';

	/**
	 * Class MPCEShortcodeAtts
	 * Must implement all iMPCEShortcode's methods.
	 * It's methods must return array of shortcode default attributes.
	 */
	abstract class MPCEShortcodeAtts implements iMPCEShortcodeAtts {

		private static $atts = array();

		private static $styles = array(
	        'mp_style_classes'  => '',
	        'margin'            => '',
			'mp_custom_style'   => ''
	    );

		static function get($tag) {
			if (!isset(self::$atts[$tag])) {
				if (method_exists(__CLASS__, $tag)) {
					self::$atts[$tag] = self::$tag();
				} else {
					self::$atts[$tag] = array();
				}
			}

			return self::$atts[$tag];
		}

		static function getStyle() {
			return self::$styles;
		}

		static function addStyle($atts = array()) {
	        $styles = self::getStyle();
	        $styles['classes'] = ''; //for support versions less than 1.4.6 where margin save in classes
	        $styles['custom_class'] = ''; //for support versions less than 1.5 where mp_style_classes has not yet been
	        $intersect = array_intersect_key($atts, $styles);

	        if (!empty($intersect)) {
	            echo '<p>Shortcode attributes intersect with style attributes</p>';
	        }

	        return array_merge($atts, $styles);
	    }

	    /***** Attributes *****/

		private static function mp_row() {
			return array(
				'bg_media_type' => 'disabled',
				'bg_video_mp4' => '',
				'bg_video_webm' => '',
				'bg_video_ogg' => '',
				'bg_video_cover' => '',
				'bg_video_repeat' => 'false',
				'bg_video_mute' => 'false',
				'bg_video_youtube' => '',
				'bg_video_youtube_cover' => '',
				'bg_video_youtube_repeat' => 'false',
				'bg_video_youtube_mute' => 'false',
				'parallax_image' => null,
				'parallax_bg_size' => 'normal',
				'id' => '',
				'stretch' => '',
				'width_content' => '',
				'full_height' => 'false'
			);
		}

		private static function mp_row_inner() {
			return self::mp_row();
		}

		private static function mp_span() {
			return array(
				'col' => 12,
	            'style' => ''
			);
		}

		private static function mp_span_inner() {
			return self::mp_span();
		}

		private static function mp_text() {
			return array();
		}

		private static function mp_heading() {
			return array();
		}

		private static function mp_image() {
			return array(
				'id' => '',
	            'link_type' => 'custom_url',
	            'link' => '#',
	            'target' => 'false',
	            'rel' => '',
	            'caption' => false,
	            'align' => 'left',
	            'size' => 'full',
	            'custom_size' => ''
			);
		}

		private static function mp_image_slider() {
			return array(
				'ids' => '',
	            'size' => 'full',
	            'custom_size' => '',
	            'animation' => 'fade',
	            'control_nav' => 'true',
	            'slideshow' => 'true',
	            'slideshow_speed' => 7,
	            'animation_speed' => 600,
	            'smooth_height' => 'false'
			);
		}

		private static function mp_posts_slider() {
			$postAttrs = array(
	            'posts_count' => '',
	            'post_type' => 'post',
	            'category' => '',
	            'tag' => '',
	            'order_by' => '',
	            'sort_order' => '',
	            'custom_tax' => '',
	            'custom_tax_field' => '',
	            'custom_tax_terms' => ''
	        );
	        $widgetAttrs = array(
	            'title_tag' => '',
	            'layout' => '',
	            'img_position' => 'left',
	            'image_size' => 'medium',
	            'custom_size' => '',
	            'show_content' => 'short',
	            'short_content_length' => '500',
	            'post_link' => '',
	            'custom_links' => site_url(),
	            'auto_rotate' => 'true',
	            'slideshow_speed' => '',
	            'show_nav' => 'true',
	            'pause_on_hover' => 'true',
	            'animation' => '',
	            'smooth_height' => '',
	        );

			return array_merge($postAttrs, $widgetAttrs);
		}

		private static function mp_grid_gallery() {
			return array(
				'ids' => '',
	            'columns' => '2',
	            'size' => 'thumbnail',
	            'custom_size' => '',
	            'link_type' => 'none',
	            'rel' => '',
	            'target' => 'false',
	            'caption' => 'false'
			);
		}

		private static function mp_video() {
			return array('src' => '');
		}

		private static function mp_code() {
			return array();
		}

		private static function mp_space() {
			return array(
				'min_height' => 0,
			);
		}

		private static function mp_download_button() {
			return array(
				'attachment' => '',
				'text' => 'Download',
				'color' => 'silver', // unused
				'size' => 'middle',
				'icon' => 'fa fa-download',
				'icon_position' => 'left',
				'full_width' => 'false',
				'align' => 'left'
			);
		}

		private static function mp_button_inner() {
			return array(
				'text' => '',
	            'link' => '#',
	            'target' => 'false',
	            'align' => 'left',
	            'icon' => 'none',
	            'icon_position' => 'left',
	            'icon_indent' => 'small',
	            'color' => 'motopress-btn-color-silver',
	            'custom_color' => '',
	            'size' => 'middle',
				'group_layout' => 'horizontal',
	            'indent' => '0',
	            'shape' => 'rounded'
			);
		}

		private static function mp_button_group() {
			return array(
				'align' => 'left',
				'group_layout' => 'horizontal',
	            'indent' => '5',
	            'size' => 'middle',
	            'icon_position' => 'left',
	            'icon_indent' => 'small'
			);
		}

		private static function mp_button() {
			return array(
				'text' => '',
	            'link' => '#',
	            'target' => 'false',
	            'color' => 'silver',
	            'size' => 'middle',
	            'align' => 'left',
	            'full_width' => 'false',
	            'icon' => 'none',
	            'icon_position' =>'left'
			);
		}

		private static function mp_icon() {
			return array(
				'icon' => '',
				'icon_color' => '',
				'icon_size' => 'middle',
				'icon_size_custom' => '',
				'icon_alignment' => '',
				'bg_color' => '',
				'bg_shape' => '',
				'icon_background_size' => '1.5',
				'animation' => 'none',
				'link' => '',
			);
		}

		private static function mp_countdown_timer() {
			return array(
				'date' => '',
	            'time_zone' => '',
	            'format' => '',
	            'block_color' => '',
	            'font_color' => '',
	            'blocks_size' => '60',
	            'digits_font_size' => '30',
	            'labels_font_size' => '13',
	            'blocks_space' => '5'
			);
		}

		private static function mp_wp_archives() {
			return array(
				'title' => '',
	            'dropdown' => '',
	            'count' => ''
			);
		}

		private static function mp_wp_calendar() {
			return array('title' => '');
		}

		private static function mp_wp_categories() {
			return array(
				'title' => '',
	            'dropdown' => '',
	            'count' => '',
	            'hierarchical' => ''
			);
		}

		private static function mp_wp_navmenu() {
			return array(
				'title' => '',
	            'nav_menu' => ''
			);
		}

		private static function mp_wp_meta() {
			return array('title' => '');
		}

		private static function mp_wp_pages() {
			return array(
				'title' => '',
	            'sortby' => 'menu_order',
	            'exclude' => null
			);
		}

		private static function mp_wp_posts() {
			return array(
				'title' => '',
	            'number' => 5,
	            'show_date' => false
			);
		}

		private static function mp_wp_comments() {
			return array(
				'title' => '',
	            'number' => 5
			);
		}

		private static function mp_wp_rss() {
			return array(
				'title' => '',
	            'url' => '',
	            'items' => 10,
	            'show_summary' => '',
	            'show_author' => '',
	            'show_date' => ''
			);
		}

		private static function mp_wp_search() {
			return array(
				'title' => '',
	            'align' => 'left'
			);
		}

		private static function mp_wp_tagcloud() {
			return array(
				'title' => __('Tags'),
	            'taxonomy' => 'post_tag'
			);
		}

		private static function mp_wp_widgets_area() {
			return array(
				'title' => '',
	            'sidebar' => ''
			);
		}

		private static function mp_gmap() {
			return array(
				'address' => 'Sidney, New South Wales, Australia',
	            'zoom' => '13',
				'min_height' => 0
			);
		}

		private static function mp_embed() {
			return array(
				'data' => '',
	            'fill_space' => 'true'
			);
		}

		private static function mp_quote() {
			return array(
				'cite' => '',
	            'cite_url' => '',
	            'quote_content' => ''
			);
		}

		private static function mp_members_content() {
			return array(
				'message' => '',
				'login_text' => '',
				'members_content' => ''
			);
		}

		private static function mp_social_buttons() {
			return array(
				'size' => 'motopress-buttons-32x32',
	            'style' => 'motopress-buttons-square',
	            'align' =>  'motopress-text-align-left'
			);
		}

		private static function mp_social_profile() {
			return array(
				'facebook' => '',
	            'google' => '',
	            'twitter' => '',
	            'pinterest' => '',
	            'linkedin' => '',
	            'flickr' => '',
	            'vk' => '',
	            'delicious' => '',
	            'youtube' => '',
	            'rss' => '',
	            'instagram' => '',
	            'size' => 32,
	            'style' => 'square',
	            'align' =>  'left'
			);
		}

		private static function mp_google_chart() {
			return array(
				'title' => '',
	            'type' => '',
	            'colors' => '',
	            'transparency' => 'false',
	            'donut' => '',
				'min_height' => 0
			);
		}

		private static function mp_wp_audio() {
			return array(
				'source' => '',
	            'id' => '',
	            'url' => '',
	            'autoplay' => '',
	            'loop'     => ''
			);
		}

		private static function mp_tabs() {
			return array(
				'active' => null,
	            'padding' => 20,
	            'vertical' => 'false',
	            'rotate' => 'disable',
			);
		}

		private static function mp_tab() {
			return array(
				'id' => '',
	            'title' => '',
			);
		}

		private static function mp_accordion() {
			return array(
				'active' => 'false',
	            'style' => 'light'
			);
		}

		private static function mp_accordion_item() {
			return array(
				'title' => '',
	            'active' => ''
			);
		}

		private static function mp_table() {
			return array('style' =>  'none');
		}

		private static function mp_posts_grid() {
			return array(
				'query_type' => 'simple',
				'post_type' =>  'post',
				'columns' => 3,
				'category' => '',
				'tag' => '',
				'posts_per_page' => 3,
				'posts_order' => 'DESC',
				'custom_tax' => '',
				'custom_tax_field' => '',
				'custom_tax_terms' => '',
				'custom_query' => '',
				'ids' => '',
				'template' => 'plugins/motopress-content-editor/includes/ce/shortcodes/post_grid/templates/template1.php',
				'posts_gap' => 30,
				'show_featured_image' => 'true',
				'image_size' => 'large',
				'image_custom_size' => '',
				'title_tag' => 'h2',
				'show_date_comments' => 'true',
				'show_content' => 'short',
				'short_content_length' => 200,
				'read_more_text' => '',
				'display_style' => 'false', // Not "show_all", for compatibility with elder versions
				'pagination' => 'false',
				'load_more_btn' => 'false',
				'load_more_text' => __("Load More", 'motopress-content-editor-lite'),
				'filter' => 'none',
				'filter_tax_1' => 'category',
				'filter_tax_2' => 'post_tag',
				'filter_btn_color' => 'motopress-btn-color-silver',
				'filter_btn_divider' => '',
				'filter_cats_text' => '',
				'filter_tags_text' => '',
				'filter_all_text' => '',
//				'show_sticky_posts' => 'false',
			);
		}

		private static function mp_service_box() {
			return array(
				'layout' => 'centered',
	            'icon_type' => 'font',
	            'icon' => 'fa fa-glass',
	            'icon_size' => 'normal',
	            'icon_custom_size' => '26',
	            'icon_color' => 'mp-text-color-default',
	            'icon_custom_color' => '',
	            'image_id' => '',
	            'image_size' => 'thumbnail',
	            'image_custom_size' => '50x50',
	            'big_image_height' => '150',
	            'icon_background_type' => 'square',
	            'icon_background_size' => '1.5',
	            'icon_background_color' => '',
	            'icon_margin_left' => '0',
	            'icon_margin_right' => '0',
	            'icon_margin_top' => '0',
	            'icon_margin_bottom' => '0',
	            'icon_effect' => 'none',
	            'heading' => '',
	            'heading_tag' => 'h2',
	            'button_show' => 'true',
	            'button_text' => 'Button',
	            'button_link' => '#',
	            'button_color' => 'motopress-btn-color-silver',
	            'button_custom_bg_color' => '',
	            'button_custom_text_color' => '',
	            'button_align' => 'center'
			);
		}

		private static function mp_modal() {
			return array(
//	            'title' => '',
				'modal_shadow_color' => '#0b0b0b',
				'modal_content_color' => '#ffffff',
				'modal_style' => 'dark',
	            'icon' => 'none',
				'button_text' => '',
				'button_full_width' => 'false',
				'button_align' => 'left',
				'button_icon' => '',
				'button_icon_position' => '',
				'show_animation' => '',
				'hide_animation' => ''
			);
		}

		private static function mp_popup() {
			return array(
				'delay' => 0,
				'modal_shadow_color' => '#0b0b0b',
				'modal_content_color' => '#ffffff',
				'modal_style' => 'dark',
				'show_animation' => '',
				'hide_animation' => '',
				'display' => '', // always
			);
		}

		private static function mp_list() {
			return array(
				'list_type' => 'none',
				'use_custom_text_color' => 'false',
	            'icon' => 'fa fa-glass',
				'use_custom_icon_color' => 'false',
	            'icon_color' => '#000000',
//				'items' => '', - saved in content
	            'text_color' => '#000000'
			);
		}

		private static function mp_cta() {
			return array(
				'heading' => '',
	            'subheading' => '',
				'content_text' => '',
	            'text_align' => 'left',
	            'shape' => 'rounded',
	            'style' => '3d',
	            'style_bg_color' => '',
	            'style_text_color' => '',
	            'width' => '100',
	            'button_pos' => 'none',
				'button_text' => '',
	            'button_link' => '#',
				'button_target' => 'false',
	            'button_align' => 'center',
				'button_shape' => 'rounded',
				'button_color' => 'motopress-btn-color-silver',
	            'button_size' => 'middle',
	            'button_icon' => 'none',
	            'button_icon_position' => 'left',
				'button_animation' => 'none',
	            'icon_pos' => 'none',
				'icon_on_border' => 'false',
	            'icon_type' => 'fa fa-glass',
	            'icon_color' => 'mp-text-color-default',
	            'icon_custom_color' => '',
	            'icon_size' => 'normal',
	            'icon_custom_size' => '26',
	            'icon_animation' => 'none',
	            'animation' => 'none'
			);
		}

	}
}