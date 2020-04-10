<?php

if (!class_exists('MPCEShortcode')) {
require_once dirname(__FILE__) . '/ShortcodeBase.php';

class MPCEShortcode extends MPCEShortcodeBase {

	const DEFAULT_YOUTUBE_BG = 'https://www.youtube.com/watch?v=hPLoY1rQ2z4';

	private static $isNeedFix = false;
    private static $curPostSaveInVer;
	private static $_instance;

	protected function getViewDirName() {
		return 'common';
	}

	private function fixTheContentFiltersPriority(){
		global $wp_filter;

		$filterNames = array_keys($wp_filter['the_content'][10]);
		$from = array_search('MPCEShortcode::unautopBuilderShortcodes', $filterNames);
		$to = array_search('shortcode_unautop', $filterNames);
		$wp_filter['the_content'][10] = mpce_array_move_element($wp_filter['the_content'][10], $from, $to);
	}

    public function register() {
//        add_filter('the_content', array($this, 'runShortcodesBeforeAutop'), 8);
	    add_filter('the_content', array('MPCEShortcode', 'unautopBuilderShortcodes'), 10);
	    $this->fixTheContentFiltersPriority();

	    // Prevent wptexturize shortcodes use csv-format of content
	    add_filter('no_texturize_shortcodes', function($shortcodes){
		    $shortcodes[] = 'mp_table';
		    $shortcodes[] = 'mp_google_chart';
		    return $shortcodes;
	    });

        // Register basic shortcodes
        $this->addShortcodes(self::$scBasic);

        // Shortcodes which use 'the_content' must register here
        $this->addShortcodes(self::$scTheContent);

	    $this->registerAjaxCallbacks();
    }

	private function registerAjaxCallbacks() {
		add_action('wp_ajax_motopress_ce_posts_grid_filter', array('MPCEShortcodePostsGrid', 'ajaxFilter'));
		add_action('wp_ajax_nopriv_motopress_ce_posts_grid_filter', array('MPCEShortcodePostsGrid', 'ajaxFilter'));
		add_action('wp_ajax_motopress_ce_posts_grid_turn_page', array('MPCEShortcodePostsGrid', 'ajaxTurnPage'));
		add_action('wp_ajax_nopriv_motopress_ce_posts_grid_turn_page', array('MPCEShortcodePostsGrid', 'ajaxTurnPage'));
		add_action('wp_ajax_motopress_ce_posts_grid_load_more', array('MPCEShortcodePostsGrid', 'ajaxLoadMore'));
		add_action('wp_ajax_nopriv_motopress_ce_posts_grid_load_more', array('MPCEShortcodePostsGrid', 'ajaxLoadMore'));
	}

	/**
	 * @return MPCEShortcode
	 */
	public static function getInstance(){
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
     * @param string $content
     * @return string
     */
    public function runShortcodesBeforeAutop($content) {
        global $shortcode_tags;

        // Back up current registered shortcodes and clear them all out
        $orig_shortcode_tags = $shortcode_tags;
        remove_all_shortcodes();

        $this->addShortcodes(self::$scBasic);

        // Do the shortcode (only the [motopress shortcodes] are registered)
        $content = do_shortcode($content);

        // Put the original shortcodes back
        $shortcode_tags = $orig_shortcode_tags;

        return $content;
    }

    public static function setCurPostData($wp, $id = null) {
        $postId = (isset($id) && !empty($id)) ? (int) $id : get_the_ID();
        self::$curPostSaveInVer = get_post_meta($postId, MPCEContentManager::SAVE_IN_VERSION_META, true);
        self::$isNeedFix = version_compare(self::$curPostSaveInVer, '1.5', '<');
    }

    public static function isNeedStyleClassesFix() {
		return self::$isNeedFix;
	}

    /**
	 * Margin classes must output in outer tag of shortcode.
	 *
     * @param string $margin
     * @param bool $space
     * @return string
     */
    public static function getMarginClasses($margin, $space = true) {
        $result = '';
        if (is_string($margin)) {
            $margin = trim($margin);
            if (!empty($margin)) {
                $margin = explode(',', $margin, 4);
                $margin = array_map('trim', $margin);
                $marginClasses = array();
                if (count($margin) === 4 && count(array_unique($margin)) === 1 && $margin[0] !== 'none') {
                    $marginClasses[] = 'motopress-margin-' . $margin[0];
                } else {
                    $sides = array('top', 'bottom', 'left', 'right');
                    foreach ($margin as $key => $value) {
                        if ($value !== 'none') {
                            $marginClasses[] = 'motopress-margin-' . $sides[$key] . '-' . $value;
                        }
                    }
                }
                if (!empty($marginClasses)) $result = implode(' ', $marginClasses);
                if (!empty($result) && $space) $result = ' ' . $result;
            }
        }
		// Mark outer tag
	    if ( mpceIsEditorScene() ) {
			$result .= ' motopress-ce-child-detector';
		}
        return $result;
    }
    /**
     * @param string $shortcodeName
     * @param bool $space
     * @return string
     */
    public static function getBasicClasses($shortcodeName, $space = false) {
		$motopressCELibrary = MPCELibrary::getInstance();
        $result = '';
        if (isset($motopressCELibrary) && !empty($shortcodeName)) {
            $object = &$motopressCELibrary->getObject($shortcodeName);
            if ($object) {
                $styleClasses = &$object->getStyle('mp_style_classes');
                if (array_key_exists('basic', $styleClasses) && !empty($styleClasses['basic'])) {
                    $classes = array();
                    if (!array_key_exists('class', $styleClasses['basic'])) {
                        foreach ($styleClasses['basic'] as $value) {
                            $classes[] = $value['class'];
                        }
                    } else {
                        $classes[] = $styleClasses['basic']['class'];
                    }
                    if (!empty($classes)) $result = implode(' ', $classes);
                    if (!empty($result) && $space) $result = ' ' . $result;
                }
            }
        }
        return $result;
    }

	/**
	 * Filter custom styles classes, add limitations classes
	 * and enqueue Private/Preset styles if needed.
	 *
	 * @param string $classes Value of 'mp_custom_style' attribute of shortcode
	 * @param string $shortcodeName Name of shortcode tag
	 * @param bool $isAddSpace Whether add leading space
	 * @return string
	 */
	public static function handleCustomStyles($classes, $shortcodeName, $isAddSpace = true){
		$mpceCustomStyleManager = MPCECustomStyleManager::getInstance();
		if (MPCECustomStyleManager::hasPresetClass($classes)) {
			$mpceCustomStyleManager->enqueuePresetsStyle();
		}
		$hasPrivateClass = MPCECustomStyleManager::hasPrivateClass($classes);
		if ($hasPrivateClass) {
			$postId = get_the_ID();
			$mpceCustomStyleManager->enqueuePrivateStyle($postId);
		}

		$limitationClasses = MPCECustomStyleManager::getLimitationClass($shortcodeName, true);
		return !empty($classes) && $isAddSpace ? ' ' . $classes . $limitationClasses : $classes . $limitationClasses;
	}

    /**
     * @param string $shortcodeName
     * @param string $classes
     * @return string
     */
    public static function enqueueCustomStyle($shortcodeName, $classes){
        $motopressCELibrary = MPCELibrary::getInstance();
        if (!empty($classes)) {
            $object = &$motopressCELibrary->getObject($shortcodeName);
            if ($object) {
                $styleClasses = &$object->getStyle('mp_style_classes');
                if (array_key_exists('predefined', $styleClasses)
                        && array_key_exists('google-font-classes', $styleClasses['predefined'])
                        && array_key_exists('values', $styleClasses['predefined']['google-font-classes'])) {
                    $fontClasses = $styleClasses['predefined']['google-font-classes']['values'];
                    $classes = explode(' ', $classes);
                    $enqueueArr = array_intersect(array_keys($fontClasses), $classes);
                    foreach($enqueueArr as $key) {
                        $handle = 'motopress-custom-class-' . $key;
                        wp_enqueue_style($handle, $fontClasses[$key]['external']);
                    }
                }
            }
        }
    }

	public function rowUniversal($atts, $content, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		// Convert stretch classes to parameters. Fix for old versions.
		$mpStyleClassesArr = explode(' ', $mp_style_classes);
		if (($key = array_search('mp-row-fullwidth', $mpStyleClassesArr)) !== false){
			unset($mpStyleClassesArr[$key]);
			$stretch = 'full';
			$width_content = '';
		}
		$mp_style_classes = implode(' ', $mpStyleClassesArr);

		$rowAttrs = array();
		$rowClassesArr = array('mp-row-fluid motopress-row');
        $styleArr = array();

	    if ($stretch === 'full') {
		    self::enqFrontScript();
		}

        $videoHTML = '';
        switch ($bg_media_type) {
            case 'video' :
				self::enqFrontScript();
                $videoHTML = self::generateHTML5BackgroundVideoHTML($bg_video_webm, $bg_video_mp4, $bg_video_ogg, $bg_video_cover, $bg_video_mute, $bg_video_repeat);
                $rowClassesArr[] = 'mp-row-video';
                break;
            case 'youtube' :
				wp_enqueue_script('mp-youtube-api');
				self::enqFrontScript();
                $videoHTML = self::generateYoutubeBackgroundVideoHtml($bg_video_youtube, $bg_video_youtube_cover, $bg_video_youtube_repeat, $bg_video_youtube_mute);
                $rowClassesArr[] = 'mp-row-video';
                break;
            case 'parallax' :
				wp_enqueue_script('stellar');
				self::enqFrontScript();
                $parallax_speed = 0.5;
				$rowAttrs['data-stellar-background-ratio'] = $parallax_speed;
                if (!empty($parallax_image)) {
                    $imgSrc = wp_get_attachment_image_src( $parallax_image, 'full' );
                    $styleArr['background-image'] = 'url("' . esc_url($imgSrc[0]) . '")';
	                if ($parallax_bg_size !== 'normal') {
		                $styleArr['background-size'] = $parallax_bg_size;
	                }
                }
                $rowClassesArr[] = 'motopress-row-parallax';
				break;
        }

		global $is_IE;
		if ($is_IE){
			$rowClassesArr[] = 'mp-row-ie-fix';
		}

		if ($full_height === 'true') {
			$rowClassesArr[] = 'mp-row-fullheight';
		}

		switch ($stretch) {
			case '':
				break;
			case 'full':
				if (isset($width_content)){
					if ($width_content === '') { // Auto
						$rowClassesArr[] = 'mp-row-fullwidth';

					} elseif ($width_content === 'fixed') { // Fixed
						$rowClassesArr[] = 'mp-row-fixed-width-content';

					} else { // Full
						$rowClassesArr[] = 'mp-row-fullwidth-content';
					}
				}
				break;
			case 'fixed':
				$rowClassesArr[] = 'mp-row-fixed-width';
				break;
		}

		$rowClassesArr[] = self::handleCustomStyles($mp_custom_style, $tag);
		$rowClassesArr[] = self::getMarginClasses($margin);
		$rowClassesArr[] = self::getBasicClasses($tag, true);
		$rowClassesArr[] = $mp_style_classes;

		if (!empty($id)) {
			$rowAttrs['id'] = $id;
		}
		$rowAttrs['class'] = MPCEUtils::concatClassesGroups($rowClassesArr);
		if ( '' !== ( $style = MPCEUtils::generateStylesString($styleArr ) ) ) {
			$rowAttrs['style'] = $style;
		}

        return '<div ' . MPCEUtils::generateAttrsString($rowAttrs) . '>' . do_shortcode( $content ) . $videoHTML . '</div>';
    }


	function mp_row($atts, $content, $tag) {
		if ( !mpceIsEditorScene() ) {
            wp_enqueue_style('mpce-bootstrap-grid');
            //@todo for support custom grid must enqueue on all pages
//			wp_enqueue_style('mpce-theme');
        }

		return $this->rowUniversal( $atts, $content, $tag );
	}

	function mp_row_inner($atts, $content, $tag) {
		return $this->rowUniversal( $atts, $content, $tag );
	}

    public static function renderYoutubeBackgroundVideo(){
        $bg_video_youtube = isset($_POST['bg_video_youtube']) ? $_POST['bg_video_youtube'] : '';
        $bg_video_youtube_cover = isset($_POST['bg_video_youtube_cover']) ? $_POST['bg_video_youtube_cover'] : '';
        $bg_video_youtube_repeat = isset($_POST['bg_video_youtube_repeat']) ? $_POST['bg_video_youtube_repeat'] : 'false';
        $bg_video_youtube_mute = isset($_POST['bg_video_youtube_mute']) ? $_POST['bg_video_youtube_mute'] : 'false';
        exit(self::generateYoutubeBackgroundVideoHtml($bg_video_youtube, $bg_video_youtube_cover, $bg_video_youtube_repeat, $bg_video_youtube_mute));
    }
    public static function generateYoutubeBackgroundVideoHtml($bg_video_youtube, $bg_video_youtube_cover, $bg_video_youtube_repeat, $bg_video_youtube_mute){
        if (!empty($bg_video_youtube)) {
            if (preg_match('/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/', $bg_video_youtube, $idVideo)) {
                $videoHTML = '<section class="mp-video-container"><div class="mp-youtube-container">';
	            if ( mpceIsEditorScene() ) {
                    $videoHTML .= '<img src="//img.youtube.com/vi/' . $idVideo[1] . '/0.jpg">';
                } else {
                    if ($bg_video_youtube_repeat == 'true') {
                        $loop ='&loop=1';
                        $playlist = '&playlist=' . $idVideo[1];
                    } else {
                        $loop = '';
                        $playlist = '';
                    }
                    $dataMute = ($bg_video_youtube_mute == 'true') ? ' data-mute="1"' : ' data-mute="0"';
                    $videoHTML .= '<iframe class="mp-youtube-video"' . $dataMute . ' src="https://www.youtube.com/embed/' . $idVideo[1] . '?controls=0&rel=0&showinfo=0&autoplay=1' . $loop . '&disablekb=1&showsearch=0&iv_load_policy=3&enablejsapi=1&vq=hd720' . $playlist . '"></iframe>';
                }
                $videoCover = '<div class="mp-youtube-cover"></div>';
                if (!empty($bg_video_youtube_cover)){
                    $imgSrc = wp_get_attachment_image_src( $bg_video_youtube_cover, 'full' );
                    if ($imgSrc) {
                        $videoCover = '<div class="mp-youtube-cover" style="background-image:url(\'' . $imgSrc[0] . '\')"></div>';
                    }
                }
                $videoHTML .= '</div>' . $videoCover . '</section>';
            }
        } else {
            $videoHTML = '';
        }
        return $videoHTML;
    }
    public static function renderHTML5BackgroundVideo(){
        $bg_video_webm = isset($_POST['bg_video_webm']) ? $_POST['bg_video_webm'] : '';
        $bg_video_mp4 = isset($_POST['bg_video_mp4']) ? $_POST['bg_video_mp4'] : '';
        $bg_video_ogg = isset($_POST['bg_video_ogg']) ? $_POST['bg_video_ogg'] : '';
        $bg_video_cover = isset($_POST['bg_video_cover']) ? $_POST['bg_video_cover'] : '';
        $bg_video_mute = isset($_POST['bg_video_mute']) ? $_POST['bg_video_mute'] : 'false';
        $bg_video_repeat = isset($_POST['bg_video_repeat']) ? $_POST['bg_video_repeat'] : 'false';
        exit(self::generateHTML5BackgroundVideoHTML($bg_video_webm, $bg_video_mp4, $bg_video_ogg, $bg_video_cover, $bg_video_mute, $bg_video_repeat));
    }
    public static function generateHTML5BackgroundVideoHTML($bg_video_webm, $bg_video_mp4, $bg_video_ogg, $bg_video_cover, $bg_video_mute, $bg_video_repeat){
        $loop = ($bg_video_repeat == 'true') ? ' loop="loop"' : '';
        $mute = ($bg_video_mute == 'true') ? ' muted="muted"' : '';
        $autoplay = mpceIsEditorScene() ? '' : ' autoplay="autoplay"';
        $videoCover = '';
        if (!empty($bg_video_cover)){
            $imgSrc = wp_get_attachment_image_src( $bg_video_cover, 'full' );
            if ($imgSrc) {
                $videoCover = '<div class="mp-video-cover" style="background-image:url(\'' . $imgSrc[0] . '\')"></div>';
            }
        }
        $videoHTML = '<section class="mp-video-container"><video' . $autoplay . $loop . $mute . '>';
        if (!empty($bg_video_mp4)) {
            $videoHTML .= '<source id="mp4" src="' . $bg_video_mp4 . '" type="video/mp4">';
        }
        if (!empty($bg_video_ogg)) {
            $videoHTML .= '<source id="ogg" src="' . $bg_video_ogg . '" type="video/ogg">';
        }
        if (!empty($bg_video_webm)) {
            $videoHTML .= '<source id="webm" src="' . $bg_video_webm . '" type="video/webm">';
        }
        $videoHTML .= '</video>' . $videoCover . '</section>';
        return $videoHTML;
    }

    function mp_span($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        if (!empty($style)) $style = ' style="' . $style . '"';

	    $spanClasses = "motopress-clmn mp-span{$col} " . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	                   self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;

	    return '<div class="' . $spanClasses . '"' . $style . '>' . do_shortcode( $content ) . '</div>';
    }

    function mp_span_inner($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        if (!empty($style)) $style = ' style="' . $style . '"';

	    $spanClasses = "motopress-clmn mp-span{$col}" . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	                   self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;

	    return '<div class="' . $spanClasses . '"' . $style . '>' . do_shortcode( $content ) . '</div>';
    }

	function mp_text($atts, $content = null, $tag) {
		$atts = shortcode_atts(self::addStyleAtts(), $atts);

		if (!empty($atts['classes'])) {
			$atts['classes'] = ' ' . $atts['classes'];
		}
		if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($atts['custom_class'])) {
            	$atts['mp_style_classes'] = $atts['custom_class'];
            }
        }
        if (!empty($atts['mp_style_classes'])) {
			$atts['mp_style_classes'] = ' ' . $atts['mp_style_classes'];
        }
        $wrapClasses = 'motopress-text-obj' . self::handleCustomStyles($atts['mp_custom_style'], $tag) . $atts['classes'] . self::getMarginClasses($atts['margin']) . self::getBasicClasses($tag, true) . $atts['mp_style_classes'];

        self::enqueueCustomStyle($tag, $atts['mp_style_classes']);

		$content = do_shortcode( $content );
		return "<div class=\"{$wrapClasses}\">{$content}</div>";
	}

    function mp_heading($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(), $atts));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    $result = empty( $content ) ? '<h2>' . do_shortcode( $content ) . '</h2>' : $content;
        self::enqueueCustomStyle($tag, $mp_style_classes);

	    return '<div class="motopress-text-obj' . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	               self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes . '">' .
	               $result . '</div>';
    }

    function mp_image($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        $error = null;
        if (isset($id) && !empty($id)) {
            $id = (int) $id;
            $attachment = get_post($id);
            if (!empty($attachment) && $attachment->post_type === 'attachment') {
                if (wp_attachment_is_image($id)) {
                    $title = esc_attr($attachment->post_title);
                    $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
                    if (empty($alt)) {
                        $alt = trim(strip_tags($attachment->post_excerpt));
                    }
                    if (empty($alt)) {
                        $alt = trim(strip_tags($attachment->post_title));
                    }
                    if ($size === 'custom') {
                        $size = array_pad(explode('x', $custom_size), 2, 0);
                    }
                    $imgSrc = wp_get_attachment_image_src( $id, $size );
                    $imgSrc = ($imgSrc && isset($imgSrc[0])) ? $imgSrc[0] : false;
                } else {
                    $error = __("Attachment is not an image", 'motopress-content-editor-lite');
                }
            } else {
                $error = __("Empty attachment or post type not equal 'attachment'", 'motopress-content-editor-lite');
            }
        } else {
            $imgSrc = mpceSettings()['plugin_dir_url'] . 'images/ce/no-image.png?ver=' . mpceSettings()['plugin_version'];
        }
        if (empty($error)) {
            $img = '<img';
            if ($imgSrc) {
                $img .= ' src="' . $imgSrc  . '"';
            }
            if (!empty($title)) {
                $img .= ' title="' . $title . '"';
            }
            if (!empty($alt)) {
                $img .= ' alt="' . $alt . '"';
            }
            if (self::$isNeedFix && empty($mp_style_classes)) {
                if (!empty($custom_class)) $mp_style_classes = $custom_class;
            }
            if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
            $img .= ' class="'. self::getBasicClasses($tag) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) . '"';
            $img .= ' />';

            $linkAtts = '';
            if ($link_type !== 'custom_url') {
                $linkArr = wp_get_attachment_image_src( $id, 'full');
                $link = $linkArr[0];
                if ($link_type === 'lightbox') {
                    $linkAtts = ' data-action="motopressLightbox"';
                    if (!mpceIsEditorScene()) {
                        wp_enqueue_script('mpce-magnific-popup');
	                    self::enqFrontScript();
                    }
				} else {
					$linkAtts = ' rel="' . htmlentities($rel) . '"';
				}
            }
            if (isset($link) && !empty($link) && $link !== '#') {
                $target = ($target == 'true') ? '_blank' : '_self';
                $img = '<a href="' . $link . '"' . $linkAtts . ' target="' . $target . '">' . $img . '</a>';
            }
        }
        if (!empty($classes)) $classes = ' ' . $classes;
        $imgHtml = '<div class="motopress-image-obj motopress-text-align-' . $align . $classes . self::getMarginClasses($margin) . '">';
        if (empty($error)) {
            $imgHtml .= $img;
            if ($caption === 'true') {
                $imgHtml .= '<p class="motopress-image-caption">' . $attachment->post_excerpt . '</p>';
            }
        } else {
            $imgHtml .= $error;
        }
        $imgHtml .= '</div>';

	    return $imgHtml;
    }

    function mp_image_slider($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		$sliderHtml = '';
	    $keyboard = 'true';
	    if (mpceIsEditorScene()) $slideshow = $keyboard = 'false';
        $slideshow_speed = (int) $slideshow_speed * 1000;
        if ($animation !== 'slide')
            $smooth_height = 'false';

        $error = null;
	    if ( !isset( $ids ) ) {
		    $ids = '';
	    }
	    $ids = trim( $ids );
	    $ids = explode( ',', $ids );
	    $ids = array_filter( $ids );
        if (!empty($ids)) {
            wp_enqueue_style('mpce-flexslider');
            wp_enqueue_script('mpce-flexslider');
			self::enqFrontScript();
            $images = array();
            $imageErrors = array();
            foreach ($ids as $id) {
                    $id = (int) trim($id);
                    $attachment = get_post($id);
                    if (!empty($attachment) && $attachment->post_type === 'attachment') {
                        if (wp_attachment_is_image($id)) {
                            $title = esc_attr($attachment->post_title);
                            $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
                            if (empty($alt)) {
                                $alt = trim(strip_tags($attachment->post_excerpt));
                            }
                            if (empty($alt)) {
                                $alt = trim(strip_tags($attachment->post_title));
                            }
                            if ($size === 'custom') {
                                $size = array_pad(explode('x', $custom_size), 2, 0);
                            }
                            $img = '<img';
                            $imgSrc = wp_get_attachment_image_src( $id, $size );
                            if ($imgSrc && isset($imgSrc[0])) {
                                $img .= ' src="' . $imgSrc[0]  . '"';
                            }
                            if (!empty($title)) {
                                $img .= ' title="' . $title . '"';
                            }
                            if (!empty($alt)) {
                                $img .= ' alt="' . $alt . '"';
                            }
                            $img .= ' />';
                            $images[] = $img;
                            unset($img);
                        } else {
                            $imageErrors[] = __("Attachment is not an image", 'motopress-content-editor-lite');
                        }
                    } else {
                        $imageErrors[] = __("Empty attachment or post type not equal 'attachment'", 'motopress-content-editor-lite');
                    }
                }
        } else {
            $error = __("List of images is empty. Select images.", 'motopress-content-editor-lite');
        }
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    $dataAtts = array(
		    'slideshow'      => $slideshow === 'true',
		    'animation'      => $animation,
		    'controlNav'     => $control_nav === 'true',
		    'slideshowSpeed' => $slideshow_speed,
		    'animationSpeed' => (int) $animation_speed,
		    'smoothHeight'   => $smooth_height === 'true',
		    'keyboard'       => $keyboard === 'true',
	    );

	    $dataAttrsStr = MPCEUtils::generateAttrsString( array(
		    'data-atts' => htmlspecialchars( json_encode( $dataAtts ) ),
	    ) );

        $sliderHtml .= '<div class="motopress-image-slider-obj motopress-flexslider mp-flexslider-obj flexslider'
                       . $classes . self::getMarginClasses($margin) . self::handleCustomStyles($mp_custom_style, $tag, true) . '"'
                       . $dataAttrsStr . '>';
        if (empty($error)) {
            if (!empty($images)) {
                $sliderHtml .= '<ul class="slides' . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
                foreach ($images as $image) {
                    $sliderHtml .= '<li>' . $image . '</li>';
                }
                $sliderHtml .= '</ul>';
            } elseif (!empty($imageErrors)) {
                $sliderHtml .= '<ul class="' . self::getBasicClasses($tag) . $mp_style_classes . '">';
                foreach ($imageErrors as $imageError) {
                    $sliderHtml .= '<li>' . $imageError . '</li>';
                }
                $sliderHtml .= '</ul>';
            }
        } else {
            $sliderHtml .= $error;
        }
        $sliderHtml .= '</div>';

	    return $sliderHtml;
    }

    function mp_posts_slider($attrs, $content = null, $scTag) {
		global $motopressPostsSliderRunning;
		if (!isset($motopressPostsSliderRunning) || empty($motopressPostsSliderRunning)) {
			$motopressPostsSliderRunning = true;
		} else {
			return '';
		}

        extract(shortcode_atts(self::addStyleAtts(self::getAtts($scTag)), $attrs));

        $exclude_posts = array();

        if (mpceIsEditorScene()) {
	        $auto_rotate = 'false';
            if ( isset($_POST['postID']) && !empty($_POST['postID'])) {
                $id = $_POST['postID'];
                $exclude_posts[] = (int) $_POST['postID'];
            } else {
                $id = get_the_ID();
            }
            $editedPost = get_post_meta($id, 'motopress-ce-edited-post', true);
            if (!empty($editedPost)) {
                $exclude_posts[] = (int) $editedPost;
            }
            if (isset($_GET['p'])) {
                $exclude_posts[] = (int) $_GET['p'];
            }
        } else {
            wp_enqueue_style('mpce-bootstrap-grid');
            $id = get_the_ID();
            $exclude_posts = array($id);
        }
         $tax_query = array();
                if (isset($category) && !empty($category) && $post_type == 'post') {
                    $tax_query_cat = array(
                        'taxonomy' => 'category',
                        'field' => 'slug'
                    );
                    if (strpos($category, '+') !== false && strpos($category, ',') !== false) {
                        $cat_regex = '/[+,\s]+/';
                    } else if (strpos($category, '+') !== false) {
                        $tax_query_cat['operator'] = 'AND';
                        $cat_regex = '/[+\s]+/';
                    } else {
                        $cat_regex = '/[,\s]+/';
                    }
                    $tax_query_cat['terms'] = array_unique( preg_split( $cat_regex, $category ) );
                    $tax_query[] = $tax_query_cat;
                }


                if (isset($tag) && !empty($tag) && $post_type == 'post') {
                    $tax_query_tag = array(
                        'taxonomy' => 'post_tag',
                        'field' => 'slug'
                    );
                    if (strpos($tag, '+') !== false && strpos($tag, ',') !== false) {
                        $tag_regex = '/[+,\s]+/';
                    } else if (strpos($tag, '+') !== false) {
                        $tax_query_tag['operator'] = 'AND';
                        $tag_regex = '/[+\s]+/';
                    } else {
                        $tag_regex = '/[,\s]+/';
                    }
                    $tax_query_tag['terms'] = array_unique( preg_split( $tag_regex, $tag ) );
                    $tax_query[] = $tax_query_tag;
                }

                if (!empty($custom_tax) && !empty($custom_tax_field) && !empty($custom_tax_terms)) {
                    $tax_query_defaults = array(
                        'taxonomy' => $custom_tax,
                        'field' => $custom_tax_field,
                    );
                    if ( strpos($custom_tax_terms, '+') !== false ) {
                        $terms = preg_split( '/[+]+/', $custom_tax_terms );
                        foreach ( $terms as $term ) {
                            $tax_query[] = array_merge( $tax_query_defaults, array(
                                'terms' => array( $term )
                            ) );
                        }
                    } else {
                        $tax_query[] = array_merge( $tax_query_defaults, array(
                            'terms' => preg_split( '/[,]+/', $custom_tax_terms )
                        ) );
                    }
                }

        $args = array(
                    'post_type' => $post_type, // post, page, any
                    'post_status' => 'publish',
                    'posts_per_page' => $posts_count, // count of posts
                    'post__not_in' => $exclude_posts,
                    'order' => $sort_order, //asc desc
                    'orderby' => $order_by,
					'ignore_sticky_posts' => true
                );
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        $custom_query = new WP_Query($args);
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

	    $classes = 'motopress-posts_slider-obj mp-flexslider-obj flexslider' .
	               self::handleCustomStyles( $mp_custom_style, $scTag ) . self::getMarginClasses( $margin ) .
	               self::getBasicClasses( $scTag, true ) . $mp_style_classes;
	    $postSliderHtml = '<div class="' . $classes . '" data-post-type="' . $post_type . '">';
        $slide_counter = 0;
        $link = '';
        $error = '';
        $i = 0;

        // Split custom links (only if required)
        $custom_links_array = array();
        if ($post_link == 'custom_link' && isset($custom_links)) {
            $custom_links = trim($custom_links);
            if ($custom_links !== '') {
                $custom_links_array = preg_split('/\s+/', $custom_links);
            }
        }
        $custom_links_count = count($custom_links_array);

        // Build slides
        if($custom_query->have_posts()){
			$flexSliderHtml = '<ul class="slides">';
            wp_enqueue_style('mpce-flexslider');
            wp_enqueue_script('mpce-flexslider');
	        self::enqFrontScript();
            while($custom_query->have_posts()){
                $slide_counter++;
                $custom_query->the_post();

				switch($post_link){
                    case 'link_to_post':
                        $link = get_permalink();
						break;
                    case 'custom_link':
                        if ($i < $custom_links_count){
                            $link = $custom_links_array[$i];
                            $i++;
                        }
						break;
                }
                $post_img = '';
                $imgHtml = '<div class="motopress-ps-thumbnail mp-ce-align-'. $img_position .'">';
				if ($image_size === 'custom') {
                    $image_size = array_pad(explode('x', $custom_size), 2, 0);
                }
				$imgId = get_post_thumbnail_id();
				$imgSrc = wp_get_attachment_image_src($imgId, $image_size);
                $imgSrc = ($imgSrc && isset($imgSrc[0])) ? $imgSrc[0] : false;
                if($imgSrc != false){
					$alt = trim(strip_tags(get_post_meta($imgId, '_wp_attachment_image_alt', true)));
					if (empty($alt)) {
						$attachment = get_post($imgId);
						$alt = trim(strip_tags($attachment->post_excerpt));
						if (empty($alt)) {
							$alt = trim(strip_tags($attachment->post_title));
						}
					}

                    $post_img = '<img src="'. $imgSrc .'" alt="' . $alt . '">';
                    $imgHtml .= $post_img;
                }
                $imgHtml .= '</div>';
				if ($title_tag !== 'hide') {
					$title_start = '<'. $title_tag .'>';
					$title_end = get_the_title( $custom_query->post->ID ) .'</'. $title_tag .'>';
					$titleHtml = $title_start . $title_end;
				} else {
					$titleHtml = '';
				}

                $no_description = false;
                $contentHtml = '';
                $description = '';
				switch($show_content) {
					case 'full': case 'short':
						$description = get_the_content();
						$description = apply_filters('the_content', $description);
						break;
					case 'excerpt':
						ob_start();
						the_excerpt();
						$description = ob_get_clean();
						break;
					case 'none':
						$no_description = true;
						break;
				}
                $description = wp_strip_all_tags($description);
                $description = wp_kses($description, array());

                if ($show_content === 'short') {
	                $short_content_length = (int) $short_content_length;
	                $description_length = strlen($description);
	                $description = strlen($description) > $short_content_length ? substr($description, 0, $short_content_length) : $description;
	                if ($description_length > 0 && $short_content_length > 0 && $description_length > $short_content_length) {
		                $description .= '...';
	                }
                }

                $descr_start = '';
                $descr_end = '';
                if(!$no_description){
                    $descr_start = '<div class="motopress-ps-description">';
                    $descr_end = $description  .'</div>';
                    $contentHtml =  $descr_start . $descr_end;
                }

                if(!empty($error)){
                    $flexSliderHtml .= '<p>'. $error .'</p>';
                }
                $flexSliderHtml .= '<li>';
                if($post_link != 'no_link'){
                    $flexSliderHtml .= '<a class="motopress-slide-link" href="'. $link .'">';
                }
				switch($layout) {
					case 'title_img_text_wrap':
						$flexSliderHtml .= $titleHtml .'<div class="layout-text-wrap mp-ce-align-'. $img_position .'">'. $descr_start . $post_img . $descr_end .'</div>';
						break;
					case 'img_title_text':
						$flexSliderHtml .= $imgHtml . $titleHtml . $contentHtml;
						break;
					case 'title_text':
						$flexSliderHtml .= $titleHtml . $contentHtml;
						break;
				}
                if($post_link != 'no_link'){
                    $flexSliderHtml .= '</a>';
                }
                $flexSliderHtml .= '</li>';

            }

	        $flexSliderHtml .= '</ul>';

	        if($posts_count > $slide_counter){
	            $posts_count = $slide_counter;
	        }
	        if($slideshow_speed != 'disable'){
	            $slideshow_speed = (int)$slideshow_speed;
	        }else{
	            $slideshow_speed = (int)0;
	            $auto_rotate = 'false';
	        }
	        if ($animation !== 'slide') {
		        $smooth_height = 'false';
	        }

	        $dataAtts = array(
		        'animation'      => $animation,
		        'animationLoop'  => true,
		        'smoothHeight'   => $smooth_height === 'true',
		        'slideshow'      => $auto_rotate === 'true',
		        'slideshowSpeed' => $slideshow_speed,
		        'maxItems'       => $posts_count,
		        'showNav'        => $show_nav === 'true',
		        'pauseOnHover'   => $pause_on_hover === 'true',
		        'prevText'       => '',
		        'nextText'       => '',
	        );

	        $dataAttrsStr = MPCEUtils::generateAttrsString( array(
		        'data-atts' => htmlspecialchars( json_encode( $dataAtts ) ),
	        ) );

			// Wrap to div.viewport for fade animation (?)
	        if($animation == 'fade'){
		        $flexSliderHtml = '<div class="viewport" style="overflow: hidden;">' . $flexSliderHtml . '</div>';
	        }
	        $postSliderHtml .= '<div class="motopress-flexslider" ' . $dataAttrsStr . '>' . $flexSliderHtml . '</div>';

        } else {
            $postSliderHtml .= '<p>' . __('No posts found.') . '</p>';
        }
        $postSliderHtml .= '</div>';
		wp_reset_postdata();
		$motopressPostsSliderRunning = false;

        return $postSliderHtml;
    }

    function mp_grid_gallery($atts, $content = null, $tag){
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        $error = null;
	    $galleryItems = array();

        if (!mpceIsEditorScene()) {
            wp_enqueue_style('mpce-bootstrap-grid');
        }

        if (isset($ids) && !empty($ids)) {
            $ids = trim($ids);
            $ids = explode(',', $ids);
            $ids = array_filter($ids);
            if (!empty($ids)) {
                $images = array();
                $imageErrors = array();
                foreach ($ids as $id) {
                    $id = (int) trim($id);
                    $attachment = get_post($id);
                    if (!empty($attachment) && $attachment->post_type === 'attachment') {
                        if (wp_attachment_is_image($id)) {
                            $title = esc_attr($attachment->post_title);
                            $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
                            if (empty($alt)) {
                                $alt = trim(strip_tags($attachment->post_excerpt));
                            }
                            if (empty($alt)) {
                                $alt = trim(strip_tags($attachment->post_title));
                            }
                            if ($size === 'custom') {
                                $size = array_pad(explode('x', $custom_size), 2, 0);
                            }
                            $imgSrc = wp_get_attachment_image_src( $id, $size );
                            $galleryItem = '<img';
                            if ($imgSrc && isset($imgSrc[0])) {
                                $galleryItem .= ' src="' . $imgSrc[0]  . '"';
                            }
                            if (!empty($title)) {
                                $galleryItem .= ' title="' . $title . '"';
                            }
                            if (!empty($alt)) {
                                $galleryItem .= ' alt="' . $alt . '"';
                            }
                            $galleryItem .= ' />';
                            if ($link_type !== 'none') {
                                $galleryItemAttrs = '';
								switch ($link_type) {
									case 'attachment':
										$link = get_attachment_link($id);
										break;
									case 'media_file':
										$galleryItemAttrs .= ' rel="' . $rel . '"';
										$imgSrcFull = wp_get_attachment_image_src( $id, 'full' );
										$link = $imgSrcFull && isset($imgSrcFull[0]) ? $imgSrcFull[0] : '';
										break;
									case 'lightbox':
										if (!mpceIsEditorScene()) {
											wp_enqueue_script('mpce-magnific-popup');
											self::enqFrontScript();
										}
										$galleryItemAttrs .= ' data-action="motopressGalleryLightbox"';
										$imgSrcFull = wp_get_attachment_image_src( $id, 'full' );
										$link = $imgSrcFull && isset($imgSrcFull[0]) ? $imgSrcFull[0] : '';
										break;
								}
                                $target = ($target == 'true') ? '_blank' : '_self';
                                $galleryItem = '<a href="' . $link . '"' . $galleryItemAttrs . ' target="' . $target
                                               . '" title="' . $attachment->post_title . '">' . $galleryItem . '</a>';
                            }
                            $captionHtml = ($caption == 'true') ? '<p class="motopress-image-caption">' . $attachment->post_excerpt . '</p>' : '';
                            $galleryItem = $galleryItem . $captionHtml;
                            $galleryItems[] = $galleryItem;
                            unset($galleryItem);
                        } else {
                            $galleryErrors[] = __("Attachment is not an image", 'motopress-content-editor-lite');
                        }
                    } else {
                        $galleryErrors[] = __("Empty attachment or post type not equal 'attachment'", 'motopress-content-editor-lite');
                    }
                }
            } else {
                $error = __("List of images is empty. Select images.", 'motopress-content-editor-lite');
            }
        } else {
            $error = __("List of images is empty. Select images.", 'motopress-content-editor-lite');
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $needRecalcClass = '';
        $oneColumnClass = '';
        if (($columns !== '1') && (count($galleryItems) > $columns)) {
            $needRecalcClass = ' motopress-grid-gallery-need-recalc';
        } elseif ($columns == '1') {
            $oneColumnClass = ' motopress-grid-gallery-one-column';
        }
        $galleryHtml = '<div class="motopress-grid-gallery-obj' . self::handleCustomStyles($mp_custom_style, $tag)
                       . self::getBasicClasses($tag, true) . $mp_style_classes . self::getMarginClasses($margin)
                       . $needRecalcClass . $oneColumnClass . '">';
        if (empty($error)) {
            if (!empty($galleryItems)) {
	            self::enqFrontScript();
                $galleryHtml .= '<div class="mp-row-fluid">';
                $i = 0;
                $spanClass = 12 / $columns;
                foreach ($galleryItems as $galleryItem) {
                    $galleryHtml .= '<div class="mp-span' . $spanClass . '">' . $galleryItem . '</div>';
                    if ( ($i % $columns == $columns - 1) && ($i != count($galleryItems) -1) ) {
                        $galleryHtml .= '</div>';
                        $galleryHtml .= '<div class="mp-row-fluid">';
                    }
                    $i++;
                }
                $galleryHtml .= '</div>';
            } elseif (!empty($galleryErrors)) {
                foreach ($galleryErrors as $galleryError) {
                    $galleryHtml .= $galleryError;
                }
            }
        } else {
            $galleryHtml .= $error;
        }
        $galleryHtml .= '</div>';

	    return $galleryHtml;
    }

    const DEFAULT_VIDEO = 'www.youtube.com/watch?v=A1QKC1GBNLU';
    const YOUTUBE = 'youtube';
    const VIMEO = 'vimeo';

    function mp_video($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        $error = null;
        if (!empty($src)) {
            $src = filter_var($src, FILTER_SANITIZE_URL);
            $src = str_replace('&amp;', '&', $src);
            $url = parse_url($src);
            if ($url) {
                if (!isset($url['scheme']) || empty($url['scheme'])) {
                    $src = 'http://' . $src; //protocol use only for correct parsing url
                    $url = parse_url($src);
                }
            }
            if ($url) {
                if (isset($url['host']) && !empty($url['host']) && isset($url['path']) && !empty($url['path'])) {
                    $videoSite = self::getVideoSite($url);
                    if ($videoSite) {
                        $videoId = self::getVideoId($videoSite, $url);
                        if ($videoId) {
                            $query = (isset($url['query'])) ? $url['query'] : null;
                            $src = self::getVideoSrc($videoSite, $videoId, $query);
                        } else {
                            $error = __("Error while getting the video id", 'motopress-content-editor-lite');
                        }
                    } else {
                        $error = __("Incorrect video URL", 'motopress-content-editor-lite');
                    }
                } else {
                    $error = __("Incorrect video URL", 'motopress-content-editor-lite');
                }
            } else {
                $error = __("Error while parsing the video URL", 'motopress-content-editor-lite');
            }
        } else {
            $error = __("Incorrect video URL", 'motopress-content-editor-lite');
        }
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $videoHtml = '<div class="motopress-video-obj' . $classes . self::getMarginClasses($margin) . '">';
        if (empty($error)) {
            $videoHtml .= '<iframe src="' . $src . '" class="' . self::getBasicClasses($tag) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else {
            $videoHtml .= $error;
        }
        $videoHtml .= '</div>';

	    return $videoHtml;
    }

    private static function getVideoSite($url) {
        $videoSite = false;
        $youtubeRegExp = '/youtube\.com|youtu\.be/is';
        $vimeoRegExp = '/vimeo\.com/is';
        if (preg_match($youtubeRegExp, $url['host'])) {
            $videoSite = self::YOUTUBE;
        } else if (preg_match($vimeoRegExp, $url['host'])) {
            $videoSite = self::VIMEO;
        }
        return $videoSite;
    }

    private static function getVideoId($videoSite, $url) {
        $videoId = false;
        switch ($videoSite) {
            case self::YOUTUBE:
                if (preg_match('/youtube\.com/is', $url['host'])) {
                    if (preg_match('/watch/is', $url['path']) && isset($url['query']) && !empty($url['query'])) {
                        parse_str($url['query'], $parameters);
                        if (isset($parameters['v']) && !empty($parameters['v'])) {
                            $videoId = $parameters['v'];
                        }
                    } else if (preg_match('/embed/is', $url['path'])) {
                        $path = explode('/', $url['path']);
                        if (isset($path[2]) && !empty($path[2])) {
                            $videoId = $path[2];
                        }
                    }
                } else if (preg_match('/youtu\.be/is', $url['host'])) {
                    $path = explode('/', $url['path']);
                    if (isset($path[1]) && !empty($path[1])) {
                        $videoId = $path[1];
                    }
                }
                break;
            case self::VIMEO:
                if (preg_match('/player\.vimeo\.com/is', $url['host']) && preg_match('/video/is', $url['path'])) {
                    $path = explode('/', $url['path']);
                    if (isset($path[2]) && !empty($path[2])) {
                        $videoId = $path[2];
                    }
                } else if (preg_match('/vimeo\.com/is', $url['host'])) {
                    $path = explode('/', $url['path']);
                    if (isset($path[1]) && !empty($path[1])) {
                        $videoId = $path[1];
                    }
                }
                break;
        }
        return $videoId;
    }

    private static function getVideoSrc($videoSite, $videoId, $query) {
        $youtubeSrc = '//www.youtube.com/embed/';
        $vimeoSrc = '//player.vimeo.com/video/';
        $videoQuery = '';
        $wmode = 'wmode=opaque';
        if (!empty($query)) {
            parse_str($query, $parameters);
            if (mpceIsEditorScene()) {
                if (isset($parameters['autoplay']) && !empty($parameters['autoplay'])) {
                    unset($parameters['autoplay']);
                }
            }
        }
        switch ($videoSite) {
            case self::YOUTUBE:
                $videoSrc = $youtubeSrc;
                if (isset($parameters['v']) && !empty($parameters['v'])) {
                    unset($parameters['v']);
                }
                break;
            case self::VIMEO:
                $videoSrc = $vimeoSrc;
                break;
        }
        $videoSrc .= $videoId;
        if (!empty($parameters)) {
            $videoQuery = http_build_query($parameters);
        }
        if (!empty($videoQuery)) {
            $videoSrc .= '?' . $videoQuery . '&' . $wmode;
        } else {
            $videoSrc .= '?' . $wmode;
        }
        return $videoSrc;
    }

    function mp_code($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(), $atts));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

	    self::enqueueCustomStyle($tag, $mp_style_classes);

	    return '<div class="motopress-code-obj' . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	           self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes . '">' .
	           do_shortcode( $content ) . '</div>';
    }

    function mp_space($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

        $styleAttr = !empty($min_height) ? ' style="min-height: ' . esc_attr( $min_height ) . 'px;"' : '';
        $classes  = 'motopress-space-obj' . self::handleCustomStyles($mp_custom_style, $tag)
                    . $mp_style_classes  . self::getMarginClasses($margin);
        return '<div class="' . $classes . '" ' . $styleAttr . ' data-min-height="' . $min_height . '"><div></div></div>';
    }

	public static function downloadAttachment() {
		$id = filter_input(INPUT_GET, 'mpce-download-attachment', FILTER_VALIDATE_INT);
		$nonce = filter_input(INPUT_GET, 'mpce-download-attachment-nonce');

		if ($id && wp_verify_nonce($nonce, 'mpce-download-attachment-' . $id)) {
			$filePath = get_attached_file($id);
			if ($filePath) {
				$contentType = get_post_mime_type($id);
				$fileName = basename($filePath);

				if( function_exists('ini_set') ) {
					@ini_set( 'display_errors', 0 );
				}

				@session_write_close();

				if ( function_exists( 'apache_setenv' ) ) {
					@apache_setenv( 'no-gzip', 1 );
				}

				if( function_exists('ini_set') ) {
					@ini_set('zlib.output_compression', 'Off');
				}

				if (!ini_get( 'safe_mode' )) {
					@set_time_limit(0);
				}

				@session_cache_limiter('none');

				$bufferLevel = ob_get_level();
				do {
					@ob_end_clean();
					if(ob_get_level() == $bufferLevel) {
						break;
					}
					$bufferLevel = ob_get_level();
				} while ( ob_get_level() > 0 );

				$originalFileSize = $fileSize = file_exists($filePath) ? filesize($filePath) : 0;


				nocache_headers();
				header( "X-Robots-Tag: noindex, nofollow", true );
				header("Robots: none");
				header('Content-Description: File Transfer');
				header("Content-Disposition: attachment;filename=\"" . $fileName. "\"");
				header("Content-Type: $contentType");
				header("Content-Transfer-Encoding: binary");

				$file = @fopen($filePath, "rb");

				// check if http_range is sent by browser
				if (isset($_SERVER['HTTP_RANGE']) && $fileSize > 0) {
					list($bytes, $httpRange) = explode("=", $_SERVER['HTTP_RANGE']);
					$tmp = explode('-', $httpRange);
					$tmp = array_shift($tmp);
					$pointer = intval($tmp);
					$newLength = $fileSize - $pointer;

					header("Accept-Ranges: bytes");
					header("HTTP/1.1 206 Partial Content");
					header("Content-Length: $newLength");
					header("Content-Range: bytes $httpRange-$fileSize/$originalFileSize");

					fseek($file, $pointer);
				} else {
					header("Content-Length: " . $fileSize);
				}

				if ($file) {
					$bandwidth = 0;
					$packet = 1;
					$speed = $buffer = 1024 * 1024; // 1 Mb
					while (!(connection_aborted() || connection_status() == 1) && $fileSize > 0) {
						if ($fileSize > $buffer) {
							echo fread($file, $buffer);
						} else {
							echo fread($file, $fileSize);
						}
						ob_flush();
						flush();
						$fileSize -= $buffer;
						$bandwidth += $buffer;
						if ($speed > 0 && ($bandwidth > $speed * $packet * 1024)) {
							sleep(1);
							$packet++;
						}
					}
					@fclose($file);
				}
			}
		}
		return;
	}

	function mp_download_button($atts, $content = null, $tag) {
		extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		$linkAtts = '';
		$link = '#';

		if (isset($attachment) && !empty($attachment)) {
			$attachmentUrl = wp_get_attachment_url($attachment);
			if ($attachmentUrl !== false){

				global $is_chrome, $is_gecko, $is_opera;
				if ($is_chrome || $is_gecko || $is_opera) {
					$link = $attachmentUrl;
					$linkAtts .= ' download="' . basename($attachmentUrl) . '"';
				} else {
					// fallback for browsers that not support html5 download attribute
					// http://caniuse.com/#feat=download
					$link = esc_url(wp_nonce_url(add_query_arg('mpce-download-attachment', $attachment), 'mpce-download-attachment-' . $attachment, 'mpce-download-attachment-nonce'));
				}
			}
		}

		$iconHTML = '';
		if ($icon != 'none') {
			wp_enqueue_style('mpce-font-awesome');

			$iconAlignClass = ' motopress-btn-icon-align-' . $icon_position;
			$iconHTML = '<i class="' . esc_attr($icon) . $iconAlignClass . '"></i>';
		}

		$fullWidthClass = ($full_width === 'true') ? ' motopress-btn-full-width' : '';
		$alignClass = ($full_width === 'false') ? ' motopress-text-align-' . $align : '';

		if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

		$buttonHtml = '<div class="motopress-download-button-obj ' . $alignClass . self::getMarginClasses($margin) . '" >'
					. '<a href="' . $link . '" class="' . self::getBasicClasses($tag) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) . $fullWidthClass . '" ' . $linkAtts . ' >';
		$buttonHtml .= ($icon_position == 'left') ? $iconHTML . $text : $text . $iconHTML;
		$buttonHtml .= '</a></div>';

		return $buttonHtml;
	}

	function mp_button_inner($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		$wrapperAttrs = array();
		$linkAttrs = array();

        // Icon
	    $iconHTML = '';
	    if ($icon != 'none') {
		    wp_enqueue_style('mpce-font-awesome');

		    $iconHTML = '<i class="' . esc_attr($icon . ' motopress-btn-icon-align-' . $icon_position)  . '"></i>';
	    }

		$wrapperAttrs['class'] = MPCEUtils::concatClassesGroups(array(
			'motopress-button-inner-obj',
			($group_layout === 'horizontal' ? 'motopress-text-align-' . $align : ''),
			self::getMarginClasses($margin), // now unusable
			self::handleCustomStyles($mp_custom_style, $tag, false),
		));

		$linkAttrs['href'] = $link;
		$linkAttrs['target'] = ($target == 'true' ? '_blank' : '_self');

		if ($color == 'custom' && isset($custom_color) && !empty($custom_color)) {
			$linkStyles = array();
			$linkStyles['background-color'] = $custom_color;
			$linkAttrs['style'] = MPCEUtils::generateStylesString($linkStyles);
		}


		$linkAttrs['class'] = MPCEUtils::concatClassesGroups(array(
			self::getBasicClasses($tag),
			'motopress-btn-size-' . $size,
			'motopress-btn-icon-indent-' . $icon_indent,
			'motopress-btn-' . $shape,
			$color !== 'custom' ? $color : '',
			$mp_style_classes
		));

        return '<div ' . MPCEUtils::generateAttrsString($wrapperAttrs) . '>'
			. '<a ' . MPCEUtils::generateAttrsString($linkAttrs) . '>'
			. ( ($icon_position == 'left') ? $iconHTML . $text : $text . $iconHTML )
			. '</a></div>';
    }

    function mp_button($atts, $content = null, $tag) {
	    extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($color)) {
                if ($color === 'default') $color = 'silver';
                $mp_style_classes .= 'motopress-btn-color-' . $color;
            }
            if (!empty($size)) {
                if ($size === 'default') $size = 'middle';
                $mp_style_classes .= ' motopress-btn-size-' . $size;
            }
            $mp_style_classes .= ' motopress-btn-rounded';
            if (!empty($custom_class)) $mp_style_classes .= ' ' . $custom_class;
        }

        $linkAtts = array();
		$wrapperAtts = array();
		$iconHTML = '';

        // Icon
        if($icon !== 'none') {
        	wp_enqueue_style('mpce-font-awesome');

			$iconAtts = array();
			$iconAtts['class'] = MPCEUtils::concatClassesGroups(array(
				$icon,
				'motopress-btn-icon-align-' . $icon_position
			));
            $iconHTML = '<i ' . MPCEUtils::generateAttrsString($iconAtts) . '></i>';
        }

        if ($color === 'custom' && isset($custom_color)) {
            $linkAtts['style'] = 'background-color: ' . $custom_color . ';';
        }

	    $fullWidthClass = ($full_width === 'true') ? ' motopress-btn-full-width' : '';
	    $alignClass = ($full_width === 'false') ? ' motopress-text-align-' . $align : '';

		$wrapperAtts['class'] = MPCEUtils::concatClassesGroups(array(
			'motopress-button-obj',
			$alignClass,
			$classes,
			self::getMarginClasses($margin)
		));

		$linkAtts['href'] = $link;
		$linkAtts['target'] = $target === 'true' ? '_blank' : '_self';
		$linkAtts['class'] = MPCEUtils::concatClassesGroups(array(
			self::getBasicClasses($tag),
			$mp_style_classes,
			self::handleCustomStyles($mp_custom_style, $tag, false),
			$fullWidthClass
		));

        $buttonHtml = '<div ' . MPCEUtils::generateAttrsString($wrapperAtts) . '><a ' . MPCEUtils::generateAttrsString($linkAtts) . '>';
        $buttonHtml .= ($icon_position === 'left') ? $iconHTML . $text : $text . $iconHTML;
	    $buttonHtml .= '</a></div>';

	    return $buttonHtml;
    }

	function mp_icon($atts, $content = null, $tag) {
		extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		wp_enqueue_style('mpce-font-awesome');

		if (!mpceIsEditorScene()) {
			wp_enqueue_script('mpce-waypoints');
			self::enqFrontScript();
		}

		$iconHolderStyle = '';
		$iconCustomSize = '';
		$border_color = 'transparent';
		$outline_style = array('outline-circle', 'outline-square', 'outline-rounded');
		$iconStyle = 'style="color:'. $icon_color .'"';

		if($bg_shape != 'none' && !in_array($bg_shape, $outline_style)) {
			$shapeClass = ' motopress-ce-icon-shape-'. $bg_shape . ' ';
		} else if(in_array($bg_shape, $outline_style)) {
			$border_color = $bg_color;
			$bg_color = 'transparent';
			$shapeClass = ' motopress-ce-icon-shape-'. $bg_shape . ' ';
		} else {
			$bg_color = 'transparent';
			$shapeClass = ' motopress-ce-icon-shape-'. $bg_shape . ' ';
		}

		if($bg_shape != 'none') {
			$iconHolderStyle .= sprintf(' min-height: %Fem;', $icon_background_size);
			$iconHolderStyle .= sprintf(' height: %Fem;', $icon_background_size);
			$iconHolderStyle .= sprintf(' min-width: %Fem;', $icon_background_size);
			$iconHolderStyle .= sprintf(' width: %Fem;', $icon_background_size);
		}

		$iconBgStyle = 'background-color:'. $bg_color .'; border-color: '.$border_color .';';

		$iconSizeClass = ' motopress-ce-icon-size-'.$icon_size . ' ';
		if($icon_size == 'custom'){
			$iconCustomSize = sprintf(' font-size: %Fpx;', $icon_size_custom);
		}
		$iconAlignmentClass = ' motopress-ce-icon-align-'. $icon_alignment . ' ';

		$styleClasses = $shapeClass . $iconSizeClass . $iconAlignmentClass;

		$iconWrapperAttrs = array();
		// Why `ce_icon` ?
		$iconWrapperAttrs['class'] = MPCEUtils::concatClassesGroups(array(
			'motopress-ce-icon-obj',
			self::getBasicClasses(self::PREFIX . 'ce_icon', true),
			$mp_style_classes,
			$styleClasses,
			self::getMarginClasses($margin),
			self::handleCustomStyles($mp_custom_style, $tag, false)
		));
		$iconWrapperAttrs['style'] = $iconHolderStyle . $iconCustomSize;

		if ( !mpceIsEditorScene() && $animation !== 'none' ) {
			$iconWrapperAttrs['class'] .= ' motopress-need-animate';
			$iconWrapperAttrs['data-animation'] = $animation;
		}

		return '<div ' . MPCEUtils::generateAttrsString($iconWrapperAttrs) . '>'
			. (!empty($link) ? '<a class="motopress-ce-icon-link" href="'. $link.'">' : '')
			. '<div style="'.$iconBgStyle . $iconHolderStyle . '" class="motopress-ce-icon-bg"><span class="' . $icon . ' motopress-ce-icon-preview" ' .$iconStyle. '>'
			. '</span></div>'
			. (!empty($link) ? '</a>' : '')
			. '</div>';
	}

    function mp_countdown_timer($attrs, $content = null, $tag){
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        $uniqid = str_replace( '.', '-', uniqid('', true) );

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $block_space = (int)$blocks_space/2;
        $label_line_height = (int)$labels_font_size*1;
        $digit_line_height = (int)$digits_font_size*1;
        /*styles*/
        $style_part = '<style type="text/css">'
				. '.motopress-countdown_timer #CE-timer_'. $uniqid .' .countdown-section{'
				. '		margin: 5px '. $block_space .'px;'
				. ($block_color ? ('background-color:' . $block_color .';') : '')
				. '     width: '. $blocks_size .'px !important;'
                . '     height: '. $blocks_size .'px !important;'
				. '}'
                . '.motopress-countdown_timer #CE-timer_'. $uniqid .' .countdown-element{'
                . '     width: '. $blocks_size .'px !important;'
                . '     height: '. $blocks_size .'px !important;'
                . '}'
                . '.motopress-countdown_timer #CE-timer_'. $uniqid .' .countdown-section .countdown-amount{'
                . '     font-size: '. $digits_font_size .'px;'
                . '     line-height: '. $digit_line_height .'px;'
				. (!empty($font_color) ? 'color: '. $font_color .';' : '')
				. '		max-width: '. $blocks_size .'px !important;'
                . '}'
                . '.motopress-countdown_timer #CE-timer_'. $uniqid .' .countdown-section .countdown-period{'
                . '     font-size: '. $labels_font_size .'px;'
                . '     line-height: '. $label_line_height .'px;'
				. (!empty($font_color) ? 'color: '. $font_color .';' : '')
                . '}'
                . '</style>';
        /*end styles*/

        if (!mpceIsEditorScene()) {
            wp_enqueue_script('mpce-countdown-plugin');
            wp_enqueue_script('mpce-countdown-timer');
	        if (wp_script_is('keith-wood-countdown-language', 'registered')) {
		        wp_enqueue_script('keith-wood-countdown-language');
	        }
	        self::enqFrontScript();
        }

        if(!$date){
            $date = current_time('mysql');
        }

	    $msInSecond = 1000;
	    $endDateTimestamp = mysql2date('U', $date) * $msInSecond;

		$layout = "";
		$low_flag = false;
		$sections = str_split($format);
		foreach($sections as $section){

			if (preg_match('/[y,o,w,d,h,m,s]/', $section)){
				$low_flag = true;
			}else if(preg_match('/[Y,O,W,D,H,M,S]/', $section)){
				$section = strtolower($section);
			}else{
				continue;
			}
			if($low_flag){
				$layout .= "{".$section."<}";
			}
			$layout .= "<span class='countdown-section'><span class='countdown-element'><span class='countdown-amount'>{".$section."nn}</span><span class='countdown-period'>{".$section."l}</span></span></span>";
					if($low_flag){
						$layout .= "{".$section.">}";
					}
				$low_flag = false;
		}

	    $dataAttrs = array(
		    'endDate'    => $endDateTimestamp,
		    'format'      => $format,
		    'layout'      => $layout,
	    );
		if ($time_zone == 'server_time') {
			$dataAttrs['serverDate'] = current_time( 'timestamp' ) * $msInSecond;
		}

	    $dataAttrsStr = MPCEUtils::generateAttrsString( array(
		    'data-atts' => htmlspecialchars( json_encode( $dataAttrs ) ),
	    ) );

	    $result = '<div class="motopress-countdown_timer' . self::handleCustomStyles($mp_custom_style, $tag) . $classes
	              . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
	    $result .= '<div id="CE-timer_'. $uniqid .'" class="CE_timer motopress-countdown_timer-el" ' . $dataAttrsStr . '></div>';
	    $result .= '</div>';
	    $result .= $style_part;

	    return $result;
    }

    function mp_wp_archives($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        ($dropdown == 'true' || $dropdown == 1)  ? $attrs['dropdown'] = 1 : $attrs['dropdown'] = 0;
        ($count == 'true' || $count == 1) ? $attrs['count'] = 1 : $attrs['count'] = 0;
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_archives' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Archives';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_calendar($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_calendar' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Calendar';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

	    return $result;
    }

    function mp_wp_categories($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        ($dropdown == 'true' || $dropdown == 1) ? $attrs['dropdown'] = 1 : $attrs['dropdown'] = 0;
        ($count == 'true' || $count == 1) ? $attrs['count'] = 1 : $attrs['count'] = 0;
        ($hierarchical == 'true' || $hierarchical == 1) ? $attrs['hierarchical'] = 1 : $attrs['hierarchical'] = 0;
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_categories' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Categories';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_navmenu($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_custommenu' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Nav_Menu_Widget';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_meta($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_meta' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Meta';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_pages($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_pages' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Pages';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_posts($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        ($show_date == 'true' || $show_date == 1) ? $attrs['show_date'] = 1 : $attrs['show_date'] = 0;
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_posts' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Recent_Posts';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_comments($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_recentcomments' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Recent_Comments';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

	function mp_wp_rss($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if ($url == '') return;
        $attrs['title'] = $title;
        $attrs['items'] = ($items + 1);
        ($show_summary == 'true' || $show_summary == 1) ? $attrs['show_summary'] = 1 : $attrs['show_summary'] = 0;
        ($show_author == 'true' || $show_author == 1) ? $attrs['show_author'] = 1 : $attrs['show_author'] = 0;
        ($show_date == 'true' || $show_date == 1) ? $attrs['show_date'] = 1 : $attrs['show_date'] = 0;
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_rss' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_RSS';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_search($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_search_widget' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Search';
        $args = array();
        ob_start();
        the_widget($type, $attrs, $args);
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    function mp_wp_tagcloud($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_tagcloud' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $type = 'WP_Widget_Tag_Cloud';
        $args = array();
        ob_start();
        add_filter( 'widget_tag_cloud_args', array($this, 'tagCloudFilter'));
        the_widget($type, $attrs, $args);
        remove_filter('widget_tag_cloud_args', array($this, 'tagCloudFilter'));
        $result .= ob_get_clean();
        $result .= '</div>';

        return $result;
    }

    public function tagCloudFilter($args){
        $args['separator'] = ' ';
        return $args;
    }

	function mp_wp_widgets_area($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-wp_widgets_area' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        if ($title)
            $result .= '<h2 class="widgettitle">' . $title . '</h2>';
        if (function_exists('dynamic_sidebar') && $sidebar && $sidebar != 'no') {
            ob_start();
            dynamic_sidebar($sidebar);
            $result .= ob_get_clean();
            $result .= '</div>';

	        return $this->emulateOldShortcodeRender( $result );
        } else {
            return false;
        }
    }

    function mp_gmap($attrs, $content = null, $tag) {
        $result = __("Nothing found. Please enter correct address.", 'motopress-content-editor-lite');
        $address = '';
        $zoom = '';
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

	    self::enqFrontScript();

        if (!isset($attrs['address'])) return $result;

        $address = str_replace(" ", "+", $address);
        $formattedAddresses = get_transient('motopress-gmap-addresses');
        $formattedAddresses = (false === $formattedAddresses) ? array() : $formattedAddresses;
        if (!array_key_exists($address, $formattedAddresses)) {
            $formattedAddress = false;
            $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='. $address .'&sensor=false';
            $requirements = new MPCERequirements();
            if ($requirements->getCurl()) {
                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true
                );
                curl_setopt_array($ch, $options);
                $jsonData = curl_exec($ch);
                curl_close($ch);
            } else {
                $jsonData = file_get_contents($url); //TODO:  Warning: file_get_contents(): php_network_getaddresses: getaddrinfo failed: Name or service not known
            }
            $data = json_decode($jsonData);
            if ($data && isset($data->status)) {
                if ($data->status === 'OK') {
                    if ($data && isset($data->results)) {
                        $results = $data->{'results'};
                        if ($results && $results[0]) {
                            $formattedAddress = $results[0]->{'formatted_address'};
                            $expiration = 60 * 60 * 24; // one day
                            $formattedAddresses[$address] = $formattedAddress;
                            set_transient('motopress-gmap-addresses', $formattedAddresses, $expiration);
                        }
                    }
                } else {
                    switch ($data->status) {
                        case 'ZERO_RESULTS' : $result = __("Nothing found. Please enter correct address.", 'motopress-content-editor-lite'); break;
                        case 'OVER_QUERY_LIMIT' : $result = "Usage limits exceeded."; break;
                        case 'REQUEST_DENIED' : $result = "Request was denied for some reason."; break;
                        case 'INVALID_REQUEST' : $result = "Query (address) is missing."; break;
                    }
                }
            } else {
                $result = "Bad response from Google Map API.";
            }
        } else {
            $formattedAddress = $formattedAddresses[$address];
        }

        if ($formattedAddress) {
            if (!empty($classes)) $classes = ' ' . $classes;
            if (self::$isNeedFix && empty($mp_style_classes)) {
                if (!empty($custom_class)) $mp_style_classes = $custom_class;
            }
            if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
            $styleAttr = !empty($min_height) ? ' style="min-height: ' . esc_attr($min_height) . 'px;"' : '';
            $result = '<div class="motopress-google-map-obj' . $classes . self::getMarginClasses($margin) . '" ' . $styleAttr . '>';
            $result .= '<iframe class="' . self::getBasicClasses($tag) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='.$address.'&amp;t=m&amp;z='.$zoom.'&amp;output=embed&amp;iwloc=near"></iframe>';
            $result .= '</div>';
        }

        return $result;
    }

    function mp_embed($attrs, $content = null, $tag) {
        $embed = $data = $result = $fill_space = '';
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        $embed = base64_decode(strip_tags($data));
        $embed = preg_replace('~[\r\n]~', '', $embed);
        if (mpceIsEditorScene()) {
            $embed = '<div class="motopress-embed-obj-select"></div>' . $embed;
        }
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

	    $embedClasses = 'motopress-embed-obj' . self::handleCustomStyles( $mp_custom_style, $tag ) .
	               ( ( $fill_space == 'true' || $fill_space == '1' ) ? " fill-space" : "" ) . $classes .
	               self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;

	    return '<div class="' . $embedClasses . '">' . do_shortcode( $embed ) . '</div>';
    }

    function mp_quote($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
		
		// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/blockquote
		$result = '<div class="motopress-quotes' . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '"><blockquote';
		if ($cite_url) {
			$result .= ' cite="' . esc_url($cite_url) . '">';
		} else {
			$result .= '>';
		}
		$result .= '<p>'. $quote_content .'</p>';
		if ($cite) {
			if ($cite_url) {
				$result .= sprintf('<cite><a href="%1$s">%2$s</a></cite>', esc_url($cite_url), esc_html($cite));
			} else {
				$result .= '<cite>' . esc_html($cite) . '</cite>';
			}
		}
		$result .= '</blockquote></div>';

        return $result;
    }

	function mp_members_content($attrs, $content = null, $tag) {
		extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

		if (!empty($classes)) $classes = ' ' . $classes;
		if (self::$isNeedFix && empty($mp_style_classes)) {
			if (!empty($custom_class)) $mp_style_classes = $custom_class;
		}
		if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
		if (!is_user_logged_in()) {
			if (!$message) $message = 'This content is for registered users only. Please %login%.';
			if (!$login_text) $login_text = 'login';
			$text = '<a href="' . esc_attr(wp_login_url()) . '">' . $login_text . '</a>';
			$members_content = str_replace( '%login%', $text, $message );
		} else {
			$content = trim($content);
			$members_content = $content ? $content : $members_content;

			// Render shortcodes only for logged-in users
			$members_content = do_shortcode( $members_content );
		}

		return '<div class="motopress-members-content' . self::handleCustomStyles( $mp_custom_style, $tag ) .
		          $classes . self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) .
		          $mp_style_classes . '">' . $members_content . '</div>';
	}

    function mp_social_buttons($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!$align) $align = 'motopress-text-align-left';
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($size)) $mp_style_classes = $size;
            if (!empty($style)) $mp_style_classes .= ' ' . $style;
            if (!empty($custom_class)) $mp_style_classes .= ' ' . $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    self::enqFrontScript();
        $result = '<div class="motopress-share-buttons ' . $align . self::handleCustomStyles($mp_custom_style, $tag) . $classes . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        $result.= '<span class="motopress-button-facebook"><a href="#" title="Facebook" target="_blank"></a></span>';
        $result.= '<span class="motopress-button-twitter"><a href="#" title="Twitter" target="_blank"></a></span>';
        $result.= '<span class="motopress-button-google"><a href="#" title="Google +" target="_blank"></a></span>';
        $result.= '<span class="motopress-button-pinterest"><a href="#" title="Pinterest" target="_blank"></a></span>';
        $result.= '</div>';

        return $result;
    }

    function mp_social_profile($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

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
        $target = ' target="_blank"';
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($size)) $mp_style_classes = 'motopress-buttons-' . $size . 'x' . $size;
            if (!empty($style)) $mp_style_classes .= ' motopress-buttons-' . $style;
            if (!empty($custom_class)) $mp_style_classes .= ' ' . $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $socialProfileHtml = '<div class="motopress-social-profile-obj motopress-text-align-' . $align . self::handleCustomStyles($mp_custom_style, $tag) . self::getMarginClasses($margin) . $classes . self::getBasicClasses($tag, true) . $mp_style_classes . '">';
        foreach($sites as $name => $title) {
            $link = trim(filter_var($$name, FILTER_SANITIZE_URL));
            if (!empty($link) && filter_var($link, FILTER_VALIDATE_URL) !== false) {
                $socialProfileHtml.= '<span class="motopress-button-' . $name . '"><a href="' . $link . '" title="' . $title . '"' . $target . '></a></span>';
            }
        }
        $socialProfileHtml .= '</div>';

        return $socialProfileHtml;
    }

    function mp_google_chart($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs) );

	    wp_enqueue_script('google-charts-api');
	    self::enqFrontScript();

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $chartTable = array();
        if ($content) {
            $content = self::filterListContent($content);
            $content = motopressCEJsonEncodeUnescapedUnicode($content);
            $delimiter = ( strpos( $content, '\r\n') !== false) ? '\r\n' : '\n';
            $content = trim($content, '"');
            $content = str_replace('\"', '"', $content);
            $rows = explode( $delimiter, $content );
            $rowsCount = count($rows);
            if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
                for ($i=0; $i < $rowsCount; $i++) {
                    $rows[$i] = str_getcsv($rows[$i]);
                    if ($i !== 0) {
                        $newArr = array();
                        for ($index=0; $index < count($rows[$i]); $index++) {
                            if ($index == 0) {
                                $newArr[] = $rows[$i][0];
                            } else {
                                $newArr[] = (integer) $rows[$i][$index];
                            }
                        }
                        $rows[$i] = $newArr;
                    }
                    $chartTable[] = $rows[$i];
                }
            } else {
                $tmpFile = new SplTempFileObject();
                $tmpFile->setFlags(SplFileObject::SKIP_EMPTY);
                $tmpFile->setFlags(SplFileObject::DROP_NEW_LINE);
                $resultedArray = $rowsConv = $itemsTypeConv = array();
                for ($i=0; $i < $rowsCount; $i++) {
                    $write = $tmpFile->fwrite( $rows[$i] . "\n" );
                    if (!is_null($write)) {
                        if ( $i == $rowsCount - 1 ) {
                            $tmpFile->rewind();
                            while (!$tmpFile->eof()) {
                                $row = $tmpFile->fgetcsv();
                                $resultedArray[] = $row;
                            }
                        }
                    }
                }
                foreach ($resultedArray as $array => $arrs) {
                    $arrsCounter = count($arrs);
                    for ($i = 0; $i < $arrsCounter; $i++) {
                        if ($array === 0) {
                            $rowsConv[0] = $arrs;
                        }
                        if ($array != 0 ) {
                            if ($i != 0) {
                                $itemsTypeConv[$i] = (int) $arrs[$i];
                            } else {
                                $itemsTypeConv[$i] = $arrs[$i];
                            }
                        }
                        if (!empty($itemsTypeConv) && $i == ($arrsCounter - 1)) {
                            $rowsConv[] = $itemsTypeConv;
                        }
                    }
                }
                $chartTable = $rowsConv;
            }
            $colors = str_replace(' ', '', $colors);
            if (!empty($colors)) {
                $colors = explode(',', $colors);
            } else {
                $colors = null;
            }
            if ($transparency !== 'false') {
                $backgroundColor = array('fill' => 'transparent');
            } else {
                $backgroundColor = null;
            }
            $chartData = array(
                'type' => $type,
                'title' => $title,
                'donut' => $donut,
                'table' => $chartTable,
                'height' => null,
                'colors' => $colors,
                'backgroundColor' => $backgroundColor
            );
            $content = json_encode($chartData);
            $content = htmlspecialchars($content);
        } else {
            $content = null;
        }
	    $classes = 'motopress-google-chart' . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	               self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;
        $styleAttr = !empty($min_height) ? ' style="min-height: ' . esc_attr($min_height) . 'px;"' : '';
	    $result = '<div class="' . $classes . '" data-chart="' . $content .' " ' . $styleAttr . '></div>';

        return $result;
    }

    // TODO: Apply fix MPCE-347 (from old Editor)
    function mp_wp_audio($attrs, $content = null, $tag) {
        $shortcode = '';
        $mediaIsSet = '';
        $audioTitle = '';
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if ( !empty($id) ) {
            $attachment = get_post( $id );
            $audioTitle = ' data-audio-title="'. $attachment->post_title .'"';
        }
        if ( $source == 'library' && !empty($id) ) {
            $audioURL = wp_get_attachment_url( $id );
            $mediaIsSet = true;
        } elseif ( $source == 'external' && !empty($url) ) {
            $audioURL = $url;
            $mediaIsSet = true;
        }
        if ( $mediaIsSet ) {
            $src = $audioURL;
            if ( self::isContentEditor() ) {
                $autoplay = '0';
                $loop = 'off';
            } else {
                $autoplay = ($autoplay == 'true' || $autoplay == 1) ? '1' : '0';
                $loop = ($loop == 'true' || $loop == 1) ? 'on' : 'off';
            }
            $shortcode = "[audio src=\"$src\" autoplay=\"$autoplay\" loop=\"$loop\"]";
        } else {
            $shortcode = "<p>". __("Audio source is empty.", 'motopress-content-editor-lite') ."</p>";
        }
        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($custom_class)) $mp_style_classes = $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    $classes = 'motopress-audio-object' . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	               self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;
	    $shortcode = '<div class="' . $classes . '" ' . $audioTitle . '>' . $shortcode . '</div>';
	    $result = do_shortcode( $shortcode );

        return $result;
    }

    function mp_tabs($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        wp_enqueue_script('jquery-ui-tabs');
        self::enqFrontScript();

	    $tabsHtml = '';
        $tabsMatched = preg_match_all('/\[mp_tab(?U).*\]/', $content, $tabs);
        if ($tabsMatched) {
            $navHtml = '';
            $activeTabNo = -1;
            $tabDefaults = array(
                'id' => '',
                'title' => '',
                'icon' => 'none',
                'icon_size' => 'normal',
                'icon_custom_size' => '26',
                'icon_color' => 'inherit',
				'icon_custom_color' => '',
                'icon_margin_left' => '0',
                'icon_margin_right' => '0',
                'icon_margin_top' => '0',
                'icon_margin_bottom' => '0',
                'active' => 'false'
            );
            $tabNo = 0;
            foreach( $tabs[0] as $tab ) {
                // Parse attributes from shortcode code
                $atts = array();
                $attsMatched = preg_match_all('/(\w+)="((?U).*)"/', $tab, $raw_atts);

                if ($attsMatched) {
                    for( $i = 0; $i < $attsMatched; ++$i ) {
                        $atts[$raw_atts[1][$i]] = $raw_atts[2][$i];
                    }
                    $atts = array_merge($tabDefaults, $atts);
                    if (!empty($atts['id']) && !empty($atts['title']) && !empty($atts['active'])) {
                        $liClasses = 'ui-tabs-nav-item';
                        if ($atts['icon_size'] == 'custom') {
                            $liClasses .= ' mp-icon-size-custom';
                        }
                        $navHtml .= '<li data-tab-no="' . $tabNo . '" class="' . $liClasses . '">';
                        $navHtml .= '<a href="#' . $atts['id'] . '">';

                        // Icon
                        $use_icon = ($atts['icon'] !== 'none');
                        $iconHTML = '';
                        $iconStyle = '';
                        if ($use_icon) {
                            wp_enqueue_style('mpce-font-awesome');
                            $iconColorClass = '';
							switch($atts['icon_color']) {
								case 'custom':
									if (!empty($atts['icon_custom_color'])) {
										$iconStyle .= ' color: ' . $atts['icon_custom_color'] . ';';
									}
									break;
								case 'inherit':
									$iconStyle .= ' color: inherit;';
									break;
								default:
									$iconColorClass = ' ' . $atts['icon_color'];
									break;
							}
                            if ($atts['icon_size'] == 'custom') {
                                $iconFontSize = sprintf('%dpx', $atts['icon_custom_size']);
                                $iconStyle .= sprintf(' font-size: %s;', $iconFontSize);
                            }
                            $iconStyle .= sprintf(' padding-left: %dpx;', $atts['icon_margin_left']);
                            $iconStyle .= sprintf(' padding-right: %dpx;', $atts['icon_margin_right']);
                            $iconStyle .= sprintf(' padding-top: %dpx;', $atts['icon_margin_top']);
                            $iconStyle .= sprintf(' padding-bottom: %dpx;', $atts['icon_margin_bottom']);
                            $iconStyle = !empty($iconStyle) ? ' style="' . $iconStyle . '"' : '';
                            $iconHTML .= '<i class="' . esc_attr($atts['icon']) . $iconColorClass . '" ' . $iconStyle .'></i>';
                            $navHtml .= $iconHTML;
                        } // if ($use_icon)

                        $navHtml .= '<span class="tab-text">' . $atts['title'] . '</span>';
                        $navHtml .= '</a>';
                        $navHtml .= '</li>';
                        if ($activeTabNo < 0 && $atts['active'] === 'true') {
                            $activeTabNo = $tabNo;
                        }

                        $tabNo += 1;
                    } // if not empty main atts
                } // if atts matched /(\w+)="((?U).*)"/
            } // foreach tab
            if (!empty($navHtml)) {
                $navHtml = '<ul>' . $navHtml . '</ul>';
                $tabsHtml .= $navHtml;
                $tabsHtml .= do_shortcode($content);
                if ($activeTabNo < 0) $activeTabNo = 0;

                $active = (!mpceIsEditorScene() || is_null($active)) ? $activeTabNo : (int) $active;

            } // if (!empty($navHtml))
        } // if /\[mp_tab(?U).*\]/ matched

	    if (!empty($classes)) $classes = ' ' . $classes;
	    if (self::$isNeedFix && empty($mp_style_classes)) {
		    if (!empty($custom_class)) $mp_style_classes = $custom_class;
	    }
	    if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    if ($vertical == 'true') {
		    $classes .= ' motopress-tabs-vertical';
	    } else if ($vertical == 'false') {
		    $classes .= ' motopress-tabs-no-vertical';
	    }
	    $classes = 'motopress-tabs-obj' . self::handleCustomStyles( $mp_custom_style, $tag ) . $classes .
	               ' motopress-tabs-padding-' . $padding . self::getMarginClasses( $margin ) .
	               self::getBasicClasses( $tag, true ) . $mp_style_classes;
	    $dataAttrsStr = MPCEUtils::generateAttrsString( array(
		    'data-atts' => htmlspecialchars( json_encode( array(
			    'active'     => $active,
			    'rotateTime' => $rotate !== 'disable' ? $rotate : 0,
			    'tabsCount'  => $tabsMatched ? $tabsMatched : 0,
			    'vertical'   => $vertical === 'true',
		    ) ) ),
	    ) );
	    $tabsHtml = '<div class="' . $classes . '" ' . $dataAttrsStr . '>' . $tabsHtml . '</div>';

        return $tabsHtml;
    }

    function mp_tab($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

	    $tabsHtml = '<div class="motopress-tab' . self::getMarginClasses( $margin ) .
	                self::handleCustomStyles( $mp_custom_style, $tag ) . self::getBasicClasses( $tag, true ) .
	                '" id="' . $id . '">' . do_shortcode( $content ) . '</div>';

        return $tabsHtml;
    }

    function mp_accordion($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        wp_enqueue_script('jquery-ui-accordion');
        self::enqFrontScript();

	    $accordionHtml = '';
        preg_match_all('/mp_accordion_item(\s{0}|\sid="([^\"]+)") title="([^\"]+)" active="(true|false)"/i', $content, $matches);
        if (!empty($matches[2]) && !empty($matches[3]) && !empty($matches[4])) {
            $accordionHtml .= do_shortcode($content);
            if (!mpceIsEditorScene() || $active === 'false') {
                $search = array_search('true', $matches[4]);
                if ($search !== false) $active = $search;
            }
        }

	    if (!empty($classes)) $classes = ' ' . $classes;
	    if (self::$isNeedFix && empty($mp_style_classes)) {
		    if (!empty($style)) $mp_style_classes = 'motopress-accordion-' . $style;
		    if (!empty($custom_class)) $mp_style_classes .= ' ' . $custom_class;
	    }
	    if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    $classes = 'motopress-accordion-obj' . $classes . self::handleCustomStyles( $mp_custom_style, $tag ) .
	               self::getMarginClasses( $margin ) . self::getBasicClasses( $tag, true ) . $mp_style_classes;
	    $dataAttrsStr = MPCEUtils::generateAttrsString( array(
		    'data-atts' => htmlspecialchars( json_encode( array(
			    'active'      => $active,
			    'collapsible' => true,
			    'header'      => mpceIsEditorScene() ? '> div > div > h3' : '> div > h3',
			    'heightStyle' => "content",
		    ) ) ),
	    ) );
	    $accordionHtml = '<div class="' . $classes . '" ' . $dataAttrsStr . '>' . $accordionHtml . '</div>';

        return $accordionHtml;
    }

    function mp_accordion_item($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        $accordionItemHtml = '<div class="motopress-accordion-item' . self::handleCustomStyles($mp_custom_style, $tag) . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . '">';
        $accordionItemHtml .= '<h3>' . $title . '</h3>';
        $accordionItemHtml .= '<div>' . do_shortcode($content) . '</div>';
        $accordionItemHtml .= '</div>';

        return  $accordionItemHtml;
    }

    public function mp_table($attrs, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        if (!empty($classes)) $classes = ' ' . $classes;
        if (self::$isNeedFix && empty($mp_style_classes)) {
            if (!empty($style) && $style != 'none') $mp_style_classes = 'motopress-table-style-' . $style;
            if (!empty($custom_class)) $mp_style_classes .= ' ' . $custom_class;
        }
        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
        $result = '<div class="motopress-table-obj' . self::getMarginClasses($margin) . $classes . '">';
        $content = self::filterListContent($content);
        if (!empty($content)) {
	        $tableAttsStr = 'class="' . self::getBasicClasses($tag) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) . '"';
            $result .= self::tableUniversal($content, $tableAttsStr);
        } else {
            $result .= __("Table data is empty.", 'motopress-content-editor-lite');
        }
        $result .= '</div>';

        return wptexturize($this->emulateOldShortcodeRender($result));
    }

    function mp_posts_grid($attrs, $content = null, $scTag) {
		if (!MPCEShortcodePostsGrid::isRunning()) {
			MPCEShortcodePostsGrid::runPostsGrid();
		} else {
			return '';
		}
		$shortcode = new MPCEShortcodePostsGrid($attrs);
		$result = $shortcode->render();
		MPCEShortcodePostsGrid::stopPostsGrid();

		return $result;
    }

    function mp_service_box($attrs, $content = null, $tag){
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $attrs));

        $result = '';
        $layoutClass = '';
        $textHeadingWrapperBegin = '';
        $textHeadingWrapperEnd = '';
        $iconSectionStyle = '';
        $iconSectionClass = '';
        $iconHTML = '';
        $iconHolderStyle = '';
        $iconHolderClass = '';

        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;

        switch($icon_type) {
            case 'font':
                wp_enqueue_style('mpce-font-awesome');
                $iconStyle = '';
                $iconColorClass = '';
                $iconSectionClass .= ' motopress-service-box-small-icon';

                if ($icon_color === 'custom') {
					if (!empty($icon_custom_color)) {
						$iconStyle .= ' color: ' . $icon_custom_color . ';';
					}
                } else {
                    $iconColorClass = ' ' . $icon_color;
                }

                switch($icon_size) {
                    case 'mini':
                        $iconFontSize = '20px';
                        break;
                    case 'small':
                        $iconFontSize = '26px';
                        break;
                    case 'normal':
                        $iconFontSize = '35px';
                        break;
                    case 'large':
                        $iconFontSize = '46px';
                        break;
                    case 'extra-large':
                        $iconFontSize = '80px';
                        break;
                    case 'custom':
                        $iconFontSize = sprintf('%dpx', $icon_custom_size);
                        break;
                }
                $iconHolderStyle .= sprintf(' font-size: %s;', $iconFontSize);
                $iconStyle = !empty($iconStyle) ? ' style="' . $iconStyle . '"' : '';

                $iconHTML = '<i class="' . esc_attr($icon) . $iconColorClass . '" ' . $iconStyle .'></i>';
                break;
            case 'image':
                $iconSectionClass .= ' motopress-service-box-small-icon';
                if (!empty($image_id)) {

                    if ($image_size === 'custom') {
                        $image_size = array_pad(explode('x', $image_custom_size), 2, 0);
                    } else if (!in_array($image_size, array('full', 'large', 'medium', 'thumbnail'))) {
                        $image_size = 'thumbnail';
                    }

                    $imageAttrs = wp_get_attachment_image_src($image_id, $image_size);
                    $imageSrc = $imageAttrs && isset($imageAttrs[0]) ? $imageAttrs[0] : '';
                    if (!empty($imageSrc)) {
                        $biggerSize = max($imageAttrs[1], $imageAttrs[2]);
                        $iconHolderStyle .= sprintf(' font-size: %dpx;', $biggerSize);
                        $iconHTML = '<img src="' . esc_url($imageSrc) . '" />';
                    }

                }

                break;
            case 'big_image' :
                if (!empty($image_id)) {
                    $layout = 'centered';
                    $icon_background_type = 'none';

                    $imageAttrs = wp_get_attachment_image_src($image_id, 'full');
                    $imageSrc = $imageAttrs && isset($imageAttrs[0]) ? $imageAttrs[0] : '';
                    if (!empty($imageSrc)) {
                        $iconSectionClass .= ' motopress-service-box-big-image';
                        $iconHolderStyle .= sprintf(' font-size: %dpx;', $big_image_height);
                        $iconHTML .= '<div style="background-image: url(\'' . $imageSrc . '\');"></div>';
                    }
                }
                break;
        }

        if ($icon_background_type !== 'none') {

            $iconHolderClass .= ' motopress-service-box-icon-holder-' . $icon_background_type;

            if (!empty($icon_background_color)) {
                $iconHolderStyle .= sprintf(' background-color: %s;', $icon_background_color);
            }

            $iconHolderStyle .= sprintf(' min-height: %Fem;', $icon_background_size);
            $iconHolderStyle .= sprintf(' height: %Fem;', $icon_background_size);
            $iconHolderStyle .= sprintf(' min-width: %Fem;', $icon_background_size);
            $iconHolderStyle .= sprintf(' width: %Fem;', $icon_background_size);

        }

        switch($layout) {
            case 'centered':
                $layoutClass = ' motopress-service-box-centered';
                break;
            case 'heading-float':
                $layoutClass = ' motopress-service-box-heading-float';
                break;
            case 'text-heading-float':
                $layoutClass = ' motopress-service-box-text-heading-float';
                $textHeadingWrapperBegin = '<div class="motopress-service-box-text-heading-wrapper">';
                $textHeadingWrapperEnd = '</div>';
                break;
        }

        switch($icon_effect) {
            case 'grayscale' :
                global $is_IE;
	            $scriptSuffix = mpceSettings()['script_suffix'];

                if ($is_IE) {
                    wp_enqueue_script('mp-service-box-ie-fix', mpceSettings()['plugin_dir_url'] . 'includes/js/mp-service-box-ie-fix' . $scriptSuffix . '.js');
                }
                $layoutClass .= ' motopress-service-box-icon-effect-grayscale';
                break;
            case 'zoom' :
                $layoutClass .= ' motopress-service-box-icon-effect-zoom';
                break;
            case 'rotate' :
                $layoutClass .= ' motopress-service-box-icon-effect-rotate';
                break;
        }

        $iconSectionStyle .= sprintf(' padding-left: %dpx;', $icon_margin_left);
        $iconSectionStyle .= sprintf(' padding-right: %dpx;', $icon_margin_right);
        $iconSectionStyle .= sprintf(' padding-top: %dpx;', $icon_margin_top);
        $iconSectionStyle .= sprintf(' padding-bottom: %dpx;', $icon_margin_bottom);

        $iconSectionStyle = !empty($iconSectionStyle) ? ' style="' . $iconSectionStyle . '"' : '';
        $iconHolderStyle = !empty($iconHolderStyle) ? ' style="' . $iconHolderStyle . '"' : '';

        $result .= '<div class="motopress-service-box-obj' . self::handleCustomStyles($mp_custom_style, $tag) . $layoutClass . self::getMarginClasses($margin) . self::getBasicClasses($tag, true) . $mp_style_classes .'">';

        // Icon
        $result .= '<div class="motopress-service-box-icon-section' . $iconSectionClass . '" ' . $iconSectionStyle . '>';
        if (!empty($iconHTML)) {
            $result .= '<div class="motopress-service-box-icon-holder' . $iconHolderClass . '" ' . $iconHolderStyle .'>';
            $result .= $iconHTML;
            $result .= '</div>';
        }
        $result .= '</div>';
        $result .= $textHeadingWrapperBegin;
        // Heading
        $result .= '<div class="motopress-service-box-heading-section">';
        $result .= '<' . $heading_tag . '>' . $heading . '</' . $heading_tag . '>';
        $result .= '</div>';
        // Content
        $result .= '<div class="motopress-service-box-content-section">';
        $result .= do_shortcode($content);
        $result .= '</div>';

        if ($button_show === 'true') {
            $buttonClasses = 'motopress-btn motopress-btn-size-middle motopress-btn-rounded';
            $buttonStyle = '';

            if ($button_color !== 'custom') {
                $buttonClasses .= ' ' . $button_color;
            } else {
	            if (!empty($button_custom_bg_color)) {
		            $buttonStyle .= sprintf(' background-color: %s;', $button_custom_bg_color);
	            }
				if (!empty($button_custom_text_color)) {
					$buttonStyle .= sprintf(' color: %s;', $button_custom_text_color);
				}
            }
            $buttonStyle = !empty($buttonStyle) ? ' style="' . $buttonStyle . '"' : '';

            $result .= '<div class="motopress-service-box-button-section motopress-text-align-' . $button_align . '">';
            $result .= '<a href="' . $button_link . '" class="' . $buttonClasses . '" rel="" ' . $buttonStyle . '>' . $button_text . '</a>';
            $result .= '</div>';
        }

        $result .= $textHeadingWrapperEnd;
        $result .= '</div>';

        return $result;
    }

    function mp_modal($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
	    $uniqid = str_replace( '.', '-', uniqid('', true) );
		$style = '';
		if (!mpceIsEditorScene()) {
			wp_enqueue_script('mpce-magnific-popup');
			self::enqFrontScript();
		}

		$buttonIconHTML = '';
        if($button_icon != 'none') {
        	wp_enqueue_style('mpce-font-awesome');

	        $iconAlignClass = ' motopress-btn-icon-align-' . $button_icon_position;
            $buttonIconHTML = '<i class="' . esc_attr($button_icon) . $iconAlignClass . '"></i>';
        }

		$buttonClasses = ($button_full_width === 'true') ? ' motopress-btn-full-width' : '';
		$wrapperClasses = ($button_full_width === 'false') ? ' motopress-text-align-' . $button_align : '';
		$showAnimation = $show_animation !== '' ? 'motopress-modal-animated motopress-modal-animation-' . $show_animation : '';
		if ($hide_animation === 'auto') {
			$hide_animation = $this->getAutoHideAnimation($show_animation);
		}
		$hideAnimation = $hide_animation !== '' ? 'motopress-modal-animated motopress-modal-animation-' . $hide_animation : '';
		$buttonAttrs = ' data-mfp-show-animation="' . $showAnimation . '"';
		$buttonAttrs .= ' data-mfp-hide-animation="' . $hideAnimation . '"';
		if ($modal_style == 'custom') {
			$buttonAttrs .= ' data-modal-style=""';
			$style = '<style type="text/css">'
				. '.motopress-modal-' . $uniqid . '.mfp-bg{'
				. 'background-color: ' . $modal_shadow_color . ';'
				. '}'
				. '.motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-content{ background-color: ' . $modal_content_color . ';}'
				. '.motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-close:after, .motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-close:before{'
				. 'background-color:' . $modal_content_color . ';'
				. '}'
				. '</style>';
		} else {
			$buttonAttrs .= ' data-modal-style="motopress-modal-' . $modal_style . '"';
		}

		$buttonHtml = '<button data-action="motopress-modal" data-mfp-src="#motopress-modal-content-' . $uniqid . '"' . $buttonAttrs . ' class="motopress-button ' . $buttonClasses . self::getBasicClasses($tag, true) . $mp_style_classes . self::handleCustomStyles($mp_custom_style, $tag) .'" data-uniqid="' . $uniqid . '">'
				. ( ($button_icon_position == 'left') ? $buttonIconHTML . $button_text : $button_text . $buttonIconHTML  ) . '</button>';

	    $modalContent = '<div  id="motopress-modal-content-' . $uniqid . '" class="mfp-hide motopress-modal-content">' .
	                    do_shortcode( $content ) . '</div>';

        return '<div class="motopress-modal-obj' . $wrapperClasses . self::getMarginClasses($margin) . '">'
				. $buttonHtml
				. $modalContent
				. $style
				. '</div>';
    }

	function mp_popup($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        if (!empty($mp_style_classes)) $mp_style_classes = ' ' . $mp_style_classes;
		$uniqid = str_replace( '.', '-', uniqid('', true) );

		$shortcodeInnerHtml = '';
		$triggerAttrs = '';

		$isShowOnce = $display === 'once';
		if ($isShowOnce) {
			$showOnceCookieName = 'motopress-popup-show-once-' . get_the_ID();
			$triggerAttrs .= ' data-show-once="' . $showOnceCookieName . '"';
			$isShowedOnce = isset($_COOKIE[$showOnceCookieName]);
		}

		if (mpceIsEditorScene() || !$isShowOnce || ($isShowOnce && !$isShowedOnce) ) {
			$style = '';
			if (!mpceIsEditorScene()) {
				wp_enqueue_script('mpce-waypoints');
				wp_enqueue_script('mpce-magnific-popup');
				if ($isShowOnce && !$isShowedOnce) {
					wp_enqueue_script('mp-js-cookie');
				}
				self::enqFrontScript();
			}

			$triggerAttrs .= ' data-delay="' . intval($delay) . '"';
			$showAnimation = $show_animation !== '' ? 'motopress-modal-animated motopress-modal-animation-' . $show_animation : '';
			if ($hide_animation === 'auto') {
				$hide_animation = $this->getAutoHideAnimation($show_animation);
			}
			$hideAnimation = $hide_animation !== '' ? 'motopress-modal-animated motopress-modal-animation-' . $hide_animation : '';
			$triggerAttrs .= ' data-mfp-show-animation="' . $showAnimation . '"';
			$triggerAttrs .= ' data-mfp-hide-animation="' . $hideAnimation . '"';
			if ($modal_style == 'custom') {
				$triggerAttrs .= ' data-modal-style=""';
				$style = '<style type="text/css">'
					. '.motopress-modal-' . $uniqid . '.mfp-bg{'
					. 'background-color: ' . $modal_shadow_color . ';'
					. '}'
					. '.motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-content{ background-color: ' . $modal_content_color . ';}'
					. '.motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-close:after, .motopress-modal-' . $uniqid . '.mfp-wrap .motopress-modal-close:before{'
					. 'background-color:' . $modal_content_color . ';'
					. '}'
					. '</style>';
			} else {
				$triggerAttrs .= ' data-modal-style="motopress-modal-' . $modal_style . '"';
			}

			$customClasses =  self::handleCustomStyles($mp_custom_style, $tag) . self::getBasicClasses($tag, true) . $mp_style_classes;

			$triggerHtml = '<div data-mfp-src="#motopress-modal-content-' . $uniqid . '"' . $triggerAttrs . ' class="motopress-popup-trigger '
					. '" data-uniqid="' . $uniqid . '" data-custom-classes="' . $customClasses . '"></div>';

			$popupContent = '<div id="motopress-modal-content-' . $uniqid . '" class="mfp-hide motopress-modal-content">' .
			                do_shortcode( $content ) . '</div>';
			$shortcodeInnerHtml = $triggerHtml . $popupContent . $style;
		}

		return '<div class="motopress-popup-obj' . self::getMarginClasses($margin) . '" >'
			. $shortcodeInnerHtml
			. '</div>';
    }

	public function getAutoHideAnimation($show_animation){
		$animationPairs =  array(
			'' => '',
			'bounce' => 'bounce',
			'pulse' => 'pulse',
			'rubberBand' => 'rubberBand',
			'shake' => 'shake',
			'swing' => 'swing',
			'tada' => 'tada',
			'wobble' => 'wobble',
			'jello' => 'jello',
			'bounceIn' => 'bounceOut',
			'bounceInDown' => 'bounceOutDown',
			'bounceInLeft' => 'bounceOutLeft',
			'bounceInRight' => 'bounceOutRight',
			'bounceInUp' => 'bounceOutUp',
			'fadeIn' => 'fadeOut',
			'fadeInDown' => 'fadeOutDown',
			'fadeInDownBig' => 'fadeOutDownBig',
			'fadeInLeft' => 'fadeOutLeft',
			'fadeInLeftBig' => 'fadeOutLeftBig',
			'fadeInRight' => 'fadeOutRight',
			'fadeInRightBig' => 'fadeOutRightBig',
			'fadeInUp' => 'fadeOutUp',
			'fadeInUpBig' => 'fadeOutUpBig',
			'flip' => 'flip',
			'flipInX' => 'flipOutX',
			'flipInY' => 'flipOutY',
			'lightSpeedIn' => 'lightSpeedOut',
			'rotateIn' => 'rotateOut',
			'rotateInDownLeft' => 'rotateOutDownLeft',
			'rotateInDownRight' => 'rotateOutDownRight',
			'rotateInUpLeft' => 'rotateOutUpLeft',
			'rotateInUpRight' => 'rotateOutUpRight',
			'rollIn' => 'rollOut',
			'zoomIn' => 'zoomOut',
			'zoomInDown' => 'zoomOutDown',
			'zoomInLeft' => 'zoomOutLeft',
			'zoomInRight' => 'zoomOutRight',
			'zoomInUp' => 'zoomOutUp',
			'slideInDown' => 'slideOutUp',
			'slideInLeft' => 'slideOutLeft',
			'slideInRight' => 'slideOutRight',
			'slideInUp' => 'slideOutDown',
		);
		return (isset($animationPairs[$show_animation])) ? $animationPairs[$show_animation] : '';
	}

    function mp_list($atts, $content = '', $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

	    $useIcon = ($list_type === 'icon');
	    $content = self::filterListContent($content);
	    $list = preg_split("/\r\n|\r|\n/", $content);
	    $result = '';

	    if ($useIcon) {
	    	wp_enqueue_style('mpce-font-awesome');
	    }

		$textInlineStyle = $use_custom_text_color !== 'false' ? ' style="color:' . esc_attr($text_color) . ';"' : '';
		$iconInlineStyle = ($useIcon && $use_custom_icon_color !== 'false') ? ' style="color:' . esc_attr($icon_color) . ';"': '';

        foreach ($list as $item) {
            if ($item !== '') { // empty() is not appropriate for value "0"
				$result .= '<li' . $textInlineStyle . '>';
                if ($useIcon) {
                    $result .= '<i ' . $iconInlineStyle . ' class="' . esc_attr($icon) . '"></i>';
                }
                $result .= $item;
                $result .= '</li>';
            }
        }

        $listWrapperClasses = 'motopress-list-obj' . self::handleCustomStyles($mp_custom_style, $tag) . self::getBasicClasses($tag, true) . self::getMarginClasses($margin) . ' ' . $mp_style_classes;

        return '<div class="' . esc_attr( $listWrapperClasses ) . '"' . '>'
			. '<ul class="' . esc_attr( 'motopress-list-type-' . $list_type ) . '">'
				. do_shortcode( $result )
			. '</ul></div>';
    }

    function mp_button_group($atts, $content = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

		$wrapperAttrs = array();
		$wrapperAttrs['class'] = MPCEUtils::concatClassesGroups(array(
			'motopress-button-group-obj',
			'motopress-text-align-' . $align,
			'motopress-button-group-'. $group_layout,
			'motopress-button-group-indent-'. $indent,
			self::getMarginClasses($margin),
			self::getBasicClasses($tag),
			$mp_style_classes,
			self::handleCustomStyles($mp_custom_style, $tag, false)
		));

        // Add "indent" and "size" to button_inner's (button_inner does not have
        // that attributes)
	    $buttonInnerTag = self::PREFIX . 'button_inner';
        $search = '[' . $buttonInnerTag;
        $replace = '[' . $buttonInnerTag . ' group_layout="'. $group_layout .'" indent="' . $indent . '" size="' . $size . '" icon_position="' . $icon_position . '" icon_indent="' . $icon_indent . '"';
        $content = str_replace($search, $replace, $content);

		$result = '<div ' . MPCEUtils::generateAttrsString($wrapperAttrs) . '>';
        $result .= '<div class="motopress-button-group-inner-wrapper">';
	    $result .= do_shortcode($content);
	    $result .= '</div>';
	    $result .= '</div>';

        return $result;
    }

    function mp_cta($atts, $shortcodeContent = null, $tag) {
        extract(shortcode_atts(self::addStyleAtts(self::getAtts($tag)), $atts));

        $result = '';

        $icon = '';
        $button = '';
        $content = '';

        $classes = array(
            'cta-container' => array(
				'motopress-cta-obj',
				self::getMarginClasses($margin, false),
				self::getBasicClasses($tag),
				$mp_style_classes,
                'style-' . $style,
				self::handleCustomStyles($mp_custom_style, $tag, false)
            ),
            'cta-block' => array(
                'motopress-cta',
                'motopress-cta-shape-' . $shape,
                'motopress-cta-style-' . $style
            ),
            'icon-holder' => array(
                'motopress-cta-icon-section'
            ),
            'icon' => array(
                esc_attr($icon_type)
            ),
            'content-container' => array(
                'motopress-cta-content-container'
            ),
            'content-body' => array(
                'motopress-cta-content-section',
                'motopress-text-align-' . $text_align
            ),
            'content-header' => array(
                'motopress-cta-content-header'
            )
        );
        $styles = array(
            'cta-block' => array(),
            'icon' => array()
        );

        // "animation" field
		if ( !mpceIsEditorScene() && ( $animation !== 'none' || $button_animation !== 'none' || $icon_animation !== 'none' ) ) {
			$classes['cta-block'][] = 'motopress-need-animate';
			wp_enqueue_script('mpce-waypoints');
		}

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
        } else {
            $classes['cta-block'][] = 'motopress-cta-fullwidth';
        }

        // "icon_on_border" field
        if ($icon_on_border == 'true') {
            $classes['cta-block'][] = 'motopress-cta-icon-on-border';
        }

        // Create button
        if ($button_pos != 'none') {
            $classes['content-container'][] = 'motopress-cta-button-' . $button_pos;
            if ( in_array($button_pos, array('left', 'right')) ) {
                $classes['content-container'][] = 'motopress-cta-button-aside';
            } else {
                $classes['content-container'][] = 'motopress-cta-button-in-column';
            }

			$buttonWrapperAttrs = array();
			$buttonWrapperAttrs['class'] = MPCEUtils::concatClassesGroups(array(
				'motopress-button-wrap',
				'motopress-text-align-' . $button_align,
			));

			$buttonAttrs = array();
			$buttonAttrs['target'] = ($button_target == 'true' ? '_blank' : '_self');
			$buttonAttrs['href'] = $button_link;
			$buttonAttrs['class'] = MPCEUtils::concatClassesGroups(array(
				'motopress-btn',
				'motopress-btn-size-' . $button_size,
				'motopress-btn-icon-indent-small',
				'motopress-btn-' . $button_shape,
				$button_color,
			));
			$buttonAttrs['data-animation'] = $button_animation;

			$iconHTML = '';
			if($button_icon !== 'none') {
				wp_enqueue_style('mpce-font-awesome');

				$iconHTML = '<i class="' . esc_attr( $button_icon . ' motopress-btn-icon-align-' . $button_icon_position )  . '"></i>';
			}

			$button = '<div ' . MPCEUtils::generateAttrsString($buttonWrapperAttrs) . '>'
				. '<a ' . MPCEUtils::generateAttrsString($buttonAttrs) . ' >';
			$button .= ($button_icon_position == 'left') ? $iconHTML . $button_text : $button_text . $iconHTML;
			$button .= '</a></div>';
        }
        // Create icon
        if ($icon_pos != 'none') {
            wp_enqueue_style('mpce-font-awesome');

            $classes['cta-block'][] = 'motopress-cta-icon-' . $icon_pos;
            if ( in_array($icon_pos, array('left', 'right')) ) {
                $classes['cta-block'][] = 'motopress-cta-icon-aside';
            } else {
                $classes['cta-block'][] = 'motopress-cta-icon-in-column';
            }

            if ($icon_color === 'custom') {
				if (!empty($icon_custom_color)) {
					$styles['icon'][] = 'color: ' . $icon_custom_color . ';';
				}
            } else {
                $classes['icon'][] = $icon_color;
            }
            switch($icon_size) {
                case 'mini':
                    $iconFontSize = '20px';
                    break;
                case 'small':
                    $iconFontSize = '26px';
                    break;
                case 'normal':
                    $iconFontSize = '35px';
                    break;
                case 'large':
                    $iconFontSize = '46px';
                    break;
                case 'extra-large':
                    $iconFontSize = '80px';
                    break;
                case 'custom':
                    $iconFontSize = sprintf('%dpx', $icon_custom_size);
                    break;
            }
            $styles['icon'][] = sprintf('font-size: %s;', $iconFontSize);

            $iconClass = !empty($iconClasses) ? ' class="' . implode(' ', $iconClasses) . '"' : '';
            $iconStyle = !empty($iconStyles) ? ' style="' . implode(' ', $iconStyles) . '"' : '';

            $icon .= '<div class="' . implode(' ', $classes['icon-holder']) . '">';
                $icon .= '<i class="' . implode(' ', $classes['icon']) . '" style="' . implode(' ', $styles['icon']) . '" data-animation="' . $icon_animation . '"></i>';
            $icon .= '</div>';
        }

        // Create content section
        $content .= '<div class="' . implode(' ', $classes['content-body']) . '">';
            $content .= '<header class="' . implode(' ', $classes['content-header']) . '">';
                if (!empty($heading)) {
                    $content .= '<h2 class="motopress-cta-content-heading">' . $heading . '</h2>';
                }
                if (!empty($subheading)) {
                    $content .= '<h4 class="motopress-cta-content-subheading">' . $subheading . '</h4>';
                }
            $content .= '</header>';
            if (!empty($content_text)) {
                $content .= '<p class="motopress-cta-content-text">' . $content_text . '</p>';
            }
        $content .= '</div>';

        // Build result
        $result .= '<div class="' . implode(' ', $classes['cta-container']) . '">';
            $result .= '<div class="' . implode(' ', $classes['cta-block']) . '" style="' . implode(' ', $styles['cta-block']) . '" data-animation="' . $animation . '">';

                $ctaContent = $content;
                // Insert button
                if ($button_pos != 'none') {
                    if ( in_array($button_pos, array('top', 'left')) ) {
                        $ctaContent = $button . $ctaContent;
                    } else {
                        $ctaContent = $ctaContent . $button;
                    }
                }
                // Wrap current content
                $ctaContent = '<div class="' . implode(' ', $classes['content-container']) . '">' . $ctaContent . '</div>';
                // Insert icon
                if ($icon_pos != 'none') {
                    if ( in_array($icon_pos, array('top', 'left')) ) {
                        $ctaContent = $icon . $ctaContent;
                    } else {
                        $ctaContent = $ctaContent . $icon;
                    }
                }

                $result .= $ctaContent;
            $result .= '</div>';
        $result .= '</div>';

        return $result;
    }

    public static function getPostTypes($page = false){
        $args = array(
            'public' => TRUE,
        );
        $postTypes = get_post_types($args, 'objects');
        if($page == false){
            if (isset($postTypes['page'])) unset($postTypes['page']);
        }
        if (isset($postTypes['attachment'])) unset($postTypes['attachment']);
        $result = array();
        foreach($postTypes as $postTypeName => $postType){
            $result[$postTypeName] = $postType->labels->singular_name;
        }
        return $result;
    }

	public static function generateTaxonomyLabel($taxDetails){
		return $taxDetails['label'] . ' (' . join(', ', $taxDetails['posttypes']) . ')';
	}

	public static function getTaxonomiesList($firstItem, $page = false){
		$taxonomies = array();
		$postTypes = self::getPostTypes($page);
		foreach($postTypes as $postTypeSlug => $postTypeLabel){
			$taxs = get_object_taxonomies($postTypeSlug, 'objects');
			foreach($taxs as $taxName => $taxDetails) {
				if (!isset($taxonomies[$taxName])) {
					$taxonomies[$taxName] = array('label' => $taxDetails->labels->singular_name, 'posttypes' => array($postTypeLabel));
				} else {
					$taxonomies[$taxName]['posttypes'][] = $postTypeLabel;
				}
			}
		}

		$taxonomies = array_map(array('MPCEShortcode', 'generateTaxonomyLabel'),$taxonomies);

		$firstItemRealPosition = array_search($firstItem, array_keys($taxonomies));
		if($firstItemRealPosition !== FALSE && $firstItemRealPosition !== 0 ) {
			$cuttedElement = array_splice($taxonomies, $firstItemRealPosition, 1);
			$taxonomies = $cuttedElement + $taxonomies;
		}

		return $taxonomies;
	}

    public static function getPostsGridTemplatesList() {
        $templates  = array();
        $path = dirname(mpceSettings()['plugin_file']) . '/includes/ce/shortcodes/post_grid/templates/';
        $files = array_diff(scandir($path), array('.', '..'));
        $phpFilePattern = '/\.php$/is';
        $templateFiles = preg_grep($phpFilePattern, $files);
        if (!empty($templateFiles)) {
            foreach ($templateFiles as $templateFile) {
                $fileContent = file_get_contents($path . '/' . $templateFile);
                $namePattern = '/\*\s*Name:\s*([^\*]+)\s*\*/is';
                preg_match($namePattern, $fileContent, $matches);

                $name = basename($templateFile, '.php');

                if (!empty($matches[1])) {

                	$templateName = trim($matches[1]);

                	switch ($templateName) {
		                case 'CEPostsGridTemplate1':
		                	$name = __("Featured Image, Title, Content", 'motopress-content-editor-lite');
		                	break;
		                case 'CEPostsGridTemplate2':
		                	$name = __("Title, Featured Image, Content", 'motopress-content-editor-lite');
		                	break;
		                case 'CEPostsGridTemplate3':
		                	$name = __("Featured Image left, Title, Content", 'motopress-content-editor-lite');
		                	break;
	                }

                }

                $relativePath =  'plugins/' . dirname( plugin_basename(mpceSettings()['plugin_file']) ) . '/includes/ce/shortcodes/post_grid/templates/' . $templateFile;
                $templates[$relativePath] = $name;
            }
        }
        return $templates;
    }

	/**
	 * Enqueue front script
	 * @note Must be called after scripts which depend on
	 */
	public static function enqFrontScript() {
		if (!mpceIsEditorScene()) {
			wp_dequeue_script('mp-frontend');
			wp_enqueue_script('mp-frontend');
		}
	}

	/**
	 *
	 * @return boolean
	 *
	 * @deprecated 3.0.0
	 */
	public static function isContentEditor() {
		return mpceIsEditorScene();
    }

    private function __construct() {
		$this->register();
    }

	/**
	 * Before 3.0.0 version basic shortcodes render before normal wp shortcode doing.
	 * This led to applying do_shortcode (regular wp filter on the_content - 10) to the rendered content
	 * and was used by customers as feature.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
    private function emulateOldShortcodeRender($content){
		return do_shortcode($content);
    }

}
add_action('init', array('MPCEShortcode', 'downloadAttachment'));
}