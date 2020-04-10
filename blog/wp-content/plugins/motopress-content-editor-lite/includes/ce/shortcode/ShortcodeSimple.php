<?php

if (!class_exists('MPCEShortcodeSimple')) {
	require_once dirname(__FILE__) . '/ShortcodeBase.php';

	class MPCEShortcodeSimple extends MPCEShortcodeBase {

		/**
		 * Register shortcodes
		 * @return void
		 */
		function register() {
			// Register all shortcodes
			$this->addShortcodes(self::$sc);

			/**
			 * Hook for registration simple shortcodes
			 * @since 2.2.0
			 */
			do_action('mpce_add_simple_shortcode');
		}

		/**
		 * Return view dir name for shortcodes
		 * @return string
		 */
		protected function getViewDirName() {
			return 'simple';
		}


		function mp_row($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_row_inner($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_span($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_span_inner($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_text($atts, $content, $tag) {
			return $content;
		}

		function mp_heading($atts, $content, $tag) {
			return $content;
		}

		function mp_image($atts, $content = null, $tag) {
			$defAtts = self::getAtts($tag);
			extract(shortcode_atts($defAtts, $atts));

			$res = '';
			if (isset($id) && !empty($id)) {
	            $id = (int) $id;
	            $attachment = get_post($id);

	            if (!empty($attachment) && $attachment->post_type === 'attachment') {
	                if (wp_attachment_is_image($id)) {
	                	require_once ABSPATH . '/wp-admin/includes/media.php';

	                    $title = esc_attr($attachment->post_title);
	                    $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
                        empty($alt) && ($alt = trim(strip_tags($attachment->post_excerpt)));
	                    empty($alt) && ($alt = trim(strip_tags($attachment->post_title)));

	                    // NOTE: There is a bag in `get_image_tag` when passed an array sizes
		                ($size === 'custom') && ($size = $defAtts['size']);

			            if ($link_type !== 'custom_url') {
			                $linkArr = wp_get_attachment_image_src($id, 'full');
			                $link = $linkArr[0];
			            }

			            // Note: rel can be only TRUE or FALSE and then auto-generated (what to do?)
			            ($link_type === 'media_file') && ($rel = htmlentities($rel));

			            $caption = ($caption === 'true') ? $attachment->post_excerpt : '';

			            $res = get_image_send_to_editor($id, $caption, $title, $align, $link, $rel, $size, $alt);

			            // Add target
			            if (isset($link) && !empty($link) && $link !== '#') {
			                if ($target == 'true') {
				                $res = preg_replace('/(<a\b[^>]*)>/i', '$1 target="_blank">', $res);
			                }
			            }
	                }
	            }
	        }

			return $res;
		}

		function mp_image_slider($atts, $content, $tag) {
			$defAtts = self::getAtts($tag);
			extract(shortcode_atts($defAtts, $atts));

			$res = '';
			if (isset($ids) && !empty($ids)) {
				$ids = trim($ids);
				if (!empty($ids)) {
					$size = ($size === 'custom') ? $defAtts['size'] : $size;
					$res = "[gallery ids=\"$ids\" size=\"$size\"]";
				}
			}

	        return $res;
		}

		function mp_grid_gallery($atts, $content, $tag) {
			$defAtts = self::getAtts($tag);
			extract(shortcode_atts($defAtts, $atts));

			$res = '';
			if (isset($ids) && !empty($ids)) {
				$ids = trim($ids);
				if (!empty($ids)) {
					$size = ($size === 'custom') ? $defAtts['size'] : $size;

					$link = '';
					switch ($link_type) {
						case 'none': $link = 'none'; break;
						case 'media_file': $link = 'file'; break;
						case 'attachment': case 'lightbox': $link = ''; break;
					}
					$link && ($link = " link=\"{$link}\"");
					$res = "[gallery ids=\"{$ids}\" size=\"{$size}\" columns=\"{$columns}\"{$link}]";
				}
			}

	        return $res;
		}

		function mp_video($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			(!strpos($src, '://')) && ($src = "http://{$src}");
			$src = esc_url_raw($src);

			$res = $src ? "[embed]{$src}[/embed]" : '';

			return $res;
		}

		function mp_code($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_space($atts, $content, $tag) {
			return '<hr>';
		}

		function mp_icon($atts, $content, $tag) {
			return '';
		}

		function mp_download_button($atts, $content = null, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$linkAtts = '';
			$link = '#';
			if (isset($attachment) && !empty($attachment)) {
				$attachmentUrl = wp_get_attachment_url($attachment);
				if ($attachmentUrl !== false) {
					$link = $attachmentUrl;
					$linkAtts .= ' download="' . basename($attachmentUrl) . '"';
				}
			}

			return "<a href=\"{$link}\"{$linkAtts}>{$text}</a>";
		}

		function mp_countdown_timer($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			if (!$date) {
				$date = current_time('mysql');
			}

			return "<p>{$date}</p>";
		}

		function mp_wp_archives($atts, $content, $tag) {
			return '';
		}

		function mp_wp_calendar($atts, $content, $tag) {
			return '';
		}

		function mp_wp_categories($atts, $content, $tag) {
			return '';
		}

		function mp_wp_navmenu($atts, $content, $tag) {
			return '';
		}

		function mp_wp_meta($atts, $content, $tag) {
			return '';
		}

		function mp_wp_pages($atts, $content, $tag) {
			return '';
		}

		function mp_wp_posts($atts, $content, $tag) {
			return '';
		}

		function mp_wp_comments($atts, $content, $tag) {
			return '';
		}

		function mp_wp_rss($atts, $content, $tag) {
			return '';
		}

		function mp_wp_search($atts, $content, $tag) {
			return '';
		}

		function mp_wp_tagcloud($atts, $content, $tag) {
			return '';
		}

		function mp_wp_widgets_area($atts, $content, $tag) {
			return '';
		}

		function mp_gmap($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			if (!isset($atts['address'])) return '';

			$address = str_replace(" ", "+", $address);

			return '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='.$address.'&amp;t=m&amp;z='.$zoom.'&amp;output=embed&amp;iwloc=near"></iframe>';
		}

		function mp_embed($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$embed = base64_decode(strip_tags($data));
			$embed = preg_replace('~[\r\n]~', '', $embed);

			return !empty($embed) ? "<div>{$embed}</div>" : '';
		}

		function mp_quote($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$res = '<blockquote';
			if ($cite_url) {
				$res .= ' cite="' . esc_url($cite_url) . '">';
			} else {
				$res .= '>';
			}
			$res .= '<p>'. $quote_content .'</p>';
			if ($cite) {
				if ($cite_url) {
					$res .= sprintf('<cite><a href="%1$s">%2$s</a></cite>', esc_url($cite_url), esc_html($cite));
				} else {
					$res .= '<cite>' . esc_html($cite) . '</cite>';
				}
			}
			$res .= '</blockquote>';

	        return $res;
		}

		function mp_members_content($atts, $content, $tag) {
			return '';
		}

		function mp_social_buttons($atts, $content, $tag) {
			return '';
		}

		function mp_social_profile($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

	        $sites = array(
	            'facebook' => 'Facebook',
	            'google' => 'Google +',
	            'twitter' => 'Twitter',
	            'pinterest' => 'Pinterest',
	            'linkedin' => 'LinkedIn',
	            'flickr' => 'Flickr',
	            'vk' => 'VK',
	            'delicious' => 'Delicious',
	            'youtube' => 'YouTube',
	            'rss' => 'RSS',
	            'instagram' => 'Instagram'
	        );

	        $res = '';
	        foreach($sites as $name => $title) {
	            $link = trim(filter_var($$name, FILTER_SANITIZE_URL));
	            if (!empty($link) && filter_var($link, FILTER_VALIDATE_URL) !== false) {
	                $res .= "<span><a href=\"{$link}\" title=\"{$title}\" target=\"_blank\">{$title}</a></span>";
	            }
	        }

	        return "<div>{$res}</div>";
		}

		function mp_google_chart($atts, $content = null, $tag) {
			$atts = shortcode_atts(self::getAtts($tag), $atts);
			$res = '';

			$res .= !empty($atts['title']) ? "<p>{$atts['title']}</p>" : '';

			$content = self::filterListContent($content);
			$res .= !empty($content) ? self::tableUniversal($content) : '';

			return wptexturize($res);
		}

		function mp_wp_audio($atts, $content, $tag) {
	        extract(shortcode_atts(self::getAtts($tag), $atts));

	        $res = '';
	        $mediaIsSet = false;
	        $audioURL = '';

			if ($source == 'library' && !empty($id)) {
				$audioURL = wp_get_attachment_url($id);
				$mediaIsSet = true;
			}
			elseif ($source == 'external' && !empty($url)) {
				$audioURL = $url;
				$mediaIsSet = true;
			}

			if ($mediaIsSet) {
				$src = $audioURL;
				$autoplay = ($autoplay == 'true' || $autoplay == 1) ? '1' : '0';
                $loop = ($loop == 'true' || $loop == 1) ? 'on' : 'off';
                $res = "[audio src=\"$src\" autoplay=\"$autoplay\" loop=\"$loop\"]";
			}

			return $res;
		}

		private function tabsAccordionUniversal($content) {
			$res = '';

			$content = trim($content);
			if (!empty($content)) {
				$res = '<ul>' . do_shortcode($content) . '</ul>';
			}

			return $res;
		}

		private function tabAccordionItemUniversal($title, $content) {
			$res = '<li>';
			$res .= "<h3>{$title}</h3>";
			$res .= '<p>' . do_shortcode($content) . '</p>';
			$res .= '</li>';

			return $res;
		}

		function mp_tabs($atts, $content, $tag) {
			return $this->tabsAccordionUniversal($content);
		}

		function mp_tab($atts, $content, $tag) {
			$atts = shortcode_atts(self::getAtts($tag), $atts);
			return $this->tabAccordionItemUniversal($atts['title'], $content);
		}

		function mp_accordion($atts, $content, $tag) {
			return $this->tabsAccordionUniversal($content);
		}

		function mp_accordion_item($atts, $content, $tag) {
			$atts = shortcode_atts(self::getAtts($tag), $atts);
			return $this->tabAccordionItemUniversal($atts['title'], $content);
		}

		function mp_table($atts, $content, $tag) {
			$content = self::filterListContent($content);
			$content = !empty($content) ? self::tableUniversal($content) : '';

			return wptexturize($content);
		}

		function mp_service_box($atts, $content = null, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$result = '';
			$iconSectionStyle = '';
			$iconHTML = '';
			$iconHolderStyle = '';

			if (in_array($icon_type, array('image', 'big_image')) && !empty($image_id)) {
				if ($icon_type === 'image') {
					if ($image_size === 'custom') {
						$image_size = array_pad(explode('x', $image_custom_size), 2, 0);
					} else if (!in_array($image_size, array('full', 'large', 'medium', 'thumbnail'))) {
						$image_size = 'thumbnail';
					}
				} else {
					$image_size = 'full';
				}

				$imageAttrs = wp_get_attachment_image_src($image_id, $image_size);
				$imageSrc = $imageAttrs && isset($imageAttrs[0]) ? $imageAttrs[0] : '';
				if (!empty($imageSrc)) {
					$biggerSize = max($imageAttrs[1], $imageAttrs[2]);
					$iconHolderStyle .= sprintf(' font-size: %dpx;', $biggerSize);
					$iconHTML = '<img src="' . esc_url($imageSrc) . '" />';
				}
			}

			if ($icon_background_type !== 'none') {
				if (!empty($icon_background_color)) {
					$iconHolderStyle .= sprintf(' background-color: %s;', $icon_background_color);
				}
				$iconHolderStyle .= sprintf(' min-height: %Fem;', $icon_background_size);
				$iconHolderStyle .= sprintf(' height: %Fem;', $icon_background_size);
				$iconHolderStyle .= sprintf(' min-width: %Fem;', $icon_background_size);
				$iconHolderStyle .= sprintf(' width: %Fem;', $icon_background_size);
			}

			$iconSectionStyle .= sprintf(' padding-left: %dpx;', $icon_margin_left);
			$iconSectionStyle .= sprintf(' padding-right: %dpx;', $icon_margin_right);
			$iconSectionStyle .= sprintf(' padding-top: %dpx;', $icon_margin_top);
			$iconSectionStyle .= sprintf(' padding-bottom: %dpx;', $icon_margin_bottom);

			$iconSectionStyle = !empty($iconSectionStyle) ? ' style="' . $iconSectionStyle . '"' : '';
			$iconHolderStyle = !empty($iconHolderStyle) ? ' style="' . $iconHolderStyle . '"' : '';

			$result .= '<div>';

			// Icon
			$result .= '<div ' . $iconSectionStyle . '>';
			if (!empty($iconHTML)) {
				$result .= '<div ' . $iconHolderStyle . '>';
				$result .= $iconHTML;
				$result .= '</div>';
			}
			$result .= '</div>';
			// Heading
			$result .= '<' . $heading_tag . '>' . $heading . '</' . $heading_tag . '>';
			// Content
			$result .= do_shortcode($content);

			if ($button_show === 'true') {
				$buttonStyle = '';
				if ($button_color === 'custom') {
					if (!empty($button_custom_bg_color)) {
						$buttonStyle .= sprintf(' background-color: %s;', $button_custom_bg_color);
					}
					if (!empty($button_custom_text_color)) {
						$buttonStyle .= sprintf(' color: %s;', $button_custom_text_color);
					}
				}
				$buttonStyle = !empty($buttonStyle) ? ' style="' . $buttonStyle . '"' : '';

				$result .= '<div>';
				$result .= '<a href="' . $button_link . '" rel="" ' . $buttonStyle . '>' . $button_text . '</a>';
				$result .= '</div>';
			}

			$result .= '</div>';

			return $result;
		}

		function mp_modal($atts, $content, $tag) {
			return $content;
		}

		function mp_popup($atts, $content, $tag) {
			return $content;
		}

		function mp_list($atts, $content = '', $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$res = '';
	        $content = self::filterListContent($content);
	        $list = preg_split("/\r\n|\r|\n/", $content);
			$textInlineStyle = $use_custom_text_color !== 'false' ? ' style="color:' . esc_attr($text_color) . ';"' : '';

	        foreach ($list as $item) {
	            if ($item !== '') { // empty() is not appropriate for value "0"
					$res .= "<li{$textInlineStyle}>{$item}</li>";
	            }
	        }

	        return "<ul>{$res}</ul>";
		}

		function mp_button($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$linkAtts = array(
				'href' => $link,
				'target' => $target === 'true' ? '_blank' : '_self'
			);

			return "<a " . MPCEUtils::generateAttrsString($linkAtts) . ">{$text}</a>";
		}

		function mp_button_group($atts, $content, $tag) {
			return do_shortcode($content);
		}

		function mp_button_inner($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$linkAtts = array(
				'href' => $link,
				'target' => $target === 'true' ? '_blank' : '_self'
			);

			if ($color == 'custom' && isset($custom_color) && !empty($custom_color)) {
				$linkAtts['style'] = MPCEUtils::generateStylesString(array(
					'background-color' => $custom_color
				));
			}

			return "<a " . MPCEUtils::generateAttrsString($linkAtts) . ">{$text}</a>";
		}

		function mp_cta($atts, $content, $tag) {
			extract(shortcode_atts(self::getAtts($tag), $atts));

			$result = '';
			$button = '';
			$content = '';
			$styles = array(
				'cta-block' => array(),
			);

			// "style" field
			if ($style == 'custom') {
				if (!empty($style_bg_color)) {
					$styles['cta-block'][] = 'background-color: ' . $style_bg_color . ';';
				}
				if (!empty($style_text_color)) {
					$styles['cta-block'][] = 'color: ' . $style_text_color . ';';
				}
			}

			// "width" field
			$width = intval($width);
			if ($width < 100) {
				$styles['cta-block'][] = ' width: ' . $width . '%;';
			}

			// Create button
			if ($button_pos != 'none') {
				$buttonAttrs = array(
					'target' => $button_target == 'true' ? '_blank' : '_self',
					'href' => $button_link
				);
				$button = '<a ' . MPCEUtils::generateAttrsString($buttonAttrs) . ' >' . $button_text . '</a>';
			}

			// Create content section
			$content .= '<header>';
			if (!empty($heading)) {
				$content .= '<h2>' . $heading . '</h2>';
			}
			if (!empty($subheading)) {
				$content .= '<h4>' . $subheading . '</h4>';
			}
			$content .= '</header>';
			if (!empty($content_text)) {
				$content .= '<p>' . $content_text . '</p>';
			}

			// Build result
			$result .= '<div style="' . implode(' ', $styles['cta-block']) . '">';

			// Insert button
			if ($button_pos != 'none') {
				if (in_array($button_pos, array('top', 'left'))) {
					$content = $button . $content;
				} else {
					$content = $content . $button;
				}
			}

			$result .= $content;
			$result .= '</div>';

			return $result;
		}

		function mp_posts_grid($atts, $content, $tag) {
			return '';
		}

		function mp_posts_slider($atts, $content, $tag) {
			return '';
		}
	}
}