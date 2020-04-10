<?php

/**
 * Wrap and apply the_content filters
 *
 * @param string $content
 * @param array  $atts [
 *                     wrapOuterCode: bool=false Wrap outer code to MPCE grid
 *                     contentWrapper: string='', wrapper class. Content will not be wrapped for empty string
 *                     parse: bool=true Parse content and wrap shortcodes into system divs
 *                     ]
 *
 * @return string
 */
function mpceRenderContent($content, $atts = array()){
	$atts    = array_merge( array(
		'wrapOuterCode'  => false,
		'contentWrapper' => '',
		'parse'          => true,
	), $atts );

	if ( $atts['wrapOuterCode'] ) {
		$content = motopressCEWrapOuterCode( $content );
	}
	if ( $atts['parse'] ) {
		$content = MPCERenderContent::motopressCEParseObjectsRecursive( $content );
	}
	if ( $atts['contentWrapper'] ) {
		$content = '<div class="' . $atts['contentWrapper'] . '">' . $content . '</div>';
	}

	$content = apply_filters('the_content', $content);
	$content = MPCEContentManager::getInstance()->filterContent( $content );

	return $content;
}

/**
 * Cut "more section" from the content and append it at the end.
 *
 * @param string $content
 * @return string
 */
function motopressCEMoreTagBubbling($content) {
	if (preg_match('/(<section class="mpce-wp-more-tag">.*?<\/section>)/', $content, $matches)) {
		$content = preg_replace('/<section class="mpce-wp-more-tag">.*?<\/section>/', '', $content);
		$content .= $matches[1];
	}
	return motopressCEClearEmptyRows($content);
}

function motopressCERemoveMoreTag($content) {
    return preg_replace('/<section class="mpce-wp-more-tag">.*?<\/section>/', '', $content);
}

function motopressCEClearEmptyRows( $content ){
    $motopressCELibrary = MPCELibrary::getInstance();
    $grid = $motopressCELibrary->getGridObjects();
    if (isset($grid['span']['type']) &&  $grid['span']['type'] === 'multiple') {
        $fullSpanShortcodeName = end($grid['span']['shortcode']);
        reset($grid['span']['shortcode']);
        $fullSpanShortcode = '\[' . $fullSpanShortcodeName .'\]';
        $fullSpanCloseShortcode = '\[\/'.$fullSpanShortcodeName.'\]';
    } else {
        $fullSpanShortcode = '\[' . $grid['span']['shortcode'].' '.$grid['span']['attr'].'="'.$grid['row']['col'] . '"\]';
        $fullSpanCloseShortcode = '\[\/'.$grid['span']['shortcode'].'\]';
    }
    return preg_replace('/(?:<p>)?\['.$grid['row']['shortcode'].'\]'  . '(?:<\\/p>)?(?:<p>)?' . $fullSpanShortcode . '(?:<\\/p>)?(?:<p>)?' . $fullSpanCloseShortcode . '(?:<\\/p>)?(?:<p>)?' . '\[\/'.$grid['row']['shortcode'].'\](?:<\\/p>)?(?:<p>)?/', '', $content);
}

/**
 * Wrap non-builder content to MPCE grid structure.
 *
 * @param string $content
 *
 * @return string
 */
function motopressCEWrapOuterCode($content) {
	if ( empty( $content ) ) {
		return $content;
	}
        $motopressCELibrary = MPCELibrary::getInstance();
        $grid = $motopressCELibrary->getGridObjects();
        if (isset($grid['span']['type']) && $grid['span']['type'] === 'multiple') {
            $fullSpanShortcodeName = end($grid['span']['shortcode']);
            reset($grid['span']['shortcode']);
            $fullSpanShortcode = '[' . $fullSpanShortcodeName .']';
            $fullSpanCloseShortcode = '[/'.$fullSpanShortcodeName.']';
        } else {
            $fullSpanShortcode = '['.$grid['span']['shortcode'].' '.$grid['span']['attr'].'="'.$grid['row']['col'].'"]';
            $fullSpanCloseShortcode = '[/'.$grid['span']['shortcode'].']';
        }
        if (!preg_match('/.*?\['.$grid['row']['shortcode'].'\s?.*\].*\[\/'.$grid['row']['shortcode'].'\].*/s', $content)){
            $content = '['.$grid['row']['shortcode'].']' . $fullSpanShortcode  . $content . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']';
        }
        preg_match('/(\A.*?)((?:<p>)?\['.$grid['row']['shortcode'].'\s?.*\].*\[\/'.$grid['row']['shortcode'].'\](?:<\\/p>)?)(.*\Z)/s', $content, $matches);
        $result = '';
        $beforeContent = !empty($matches[1]) ? '['.$grid['row']['shortcode'].']' . $fullSpanShortcode . $matches[1] . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']' :'';
        $result .= motopressCEMoreTagBubbling($beforeContent);
        $result .= $matches[2];
        $afterContent = !empty($matches[3]) ? '['.$grid['row']['shortcode'].']' . $fullSpanShortcode . $matches[3] . $fullSpanCloseShortcode . '[/'.$grid['row']['shortcode'].']' :'';
        $result .= motopressCEMoreTagBubbling($afterContent);

        return $result;
}

function motopressCEGetMPShortcodeRegex(){
    $motopressCELibrary = MPCELibrary::getInstance();

    $shortcodes = $motopressCELibrary->getObjectsNames();

    $tagnames = array_values($shortcodes);
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
    // see wp_spaces_regexp() Since: WordPress 4.0.0
    $spaces = '[\r\n\t ]|\xC2\xA0|&nbsp;';

    $pattern  =
            '(?:<p>)?'                              // Opening paragraph
            . '(?:' . $spaces . ')*+'   // Optional leading whitespace
            . '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . '(' . $tagregexp . ')'                     // 2: Shortcode name
            . '\\b'                              // Word boundary
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            . '(?:<br \\/>)?'
//            .     '(?:<\\/p>)?'
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
//            .             '[^<]*+'             // Not an opening bracket
//            .             '(?:'
//            .                 '<(?!p>\\[\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
//            .                 '[^<]*+'         // Not an opening bracket
//            .             ')*+'
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
//            .     '(?:<p>)?'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)'                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
            . '(?:' . $spaces . ')*+'            // optional trailing whitespace
            . '(?:<br \\/>)?'
            . '(?:<\\/p>)?';                           // closing paragraph
    return $pattern;
}

/*
 * replacement of [ to [] for supression of incorect rendering
 */
function motopressCEScreeningDataAttrShortcodes($content){
    return htmlspecialchars_decode(preg_replace('/\[/', '[]', $content), ENT_QUOTES);
}

function motopressCEWrapCustomCode($content){
    return '[mp_code unwrap="true"]' . $content . '[/mp_code]';
}

/**
 * @deprecated 1.6.9
 * Create temporary post with motopress adapted content
 */
function motopressCECreateTemporaryPost($post_id, $content) {
    $post = get_post($post_id);
    $post->ID = '';
    $post->post_title = 'temporary';
    $post->post_content = '<div class="motopress-content-wrapper">' . $content . '</div>';
    $post->post_status = 'trash';

    $userRole = wp_get_current_user()->roles[0];
    $optionName = 'motopress_tmp_post_id_' . $userRole;
    $id = get_option($optionName);

    if ($id) {
        if (is_null(get_post($id))) {
            $id = wp_insert_post($post, false);
            update_option($optionName, $id);
        }
    } else {
        $id = wp_insert_post($post, false);
        add_option($optionName, $id);
    }

    $post->ID = (int) $id;

    global $wpdb;
    $wpdb->delete($wpdb->posts, array('post_parent' => $post->ID, 'post_type' => 'revision'), array('%d', '%s')); //@todo: remove in next version

    wp_update_post($post);
    wp_untrash_post($post->ID);
    motopressCEClonePostmeta($post_id, $post->ID);
    do_action('mp_post_meta', $post->ID, $post->post_type);
    do_action('mp_theme_fix', $post_id, $post->ID, $post->post_type);
    $pageTemplate = get_post_meta($post_id, '_wp_page_template', true);
    $pageTemplate = (!$pageTemplate or empty($pageTemplate)) ? 'default' : $pageTemplate;
    update_post_meta($post->ID, '_wp_page_template', $pageTemplate);

    return $post->ID;
}

/** @deprecated 1.6.9 */
function motopressCEClonePostmeta( $post_id_from, $post_id_to){
    motopressCEClearPostmeta($post_id_to);

    update_post_meta($post_id_to, 'motopress-ce-edited-post', $post_id_from);

    $all_post_meta = get_post_custom_keys($post_id_from);
    if (is_array($all_post_meta)){
        foreach( $all_post_meta as $post_meta_key){
            // fix of the issue with "Custom Permalinks" plugin http://atastypixel.com/blog/wordpress/plugins/custom-permalinks/
            if ($post_meta_key == "custom_permalink") continue;
            $values = get_post_custom_values($post_meta_key, $post_id_from);
            foreach ($values as $value){
                add_post_meta($post_id_to, $post_meta_key, maybe_unserialize($value));
            }
        }
    }
}

function motopressCEClearPostmeta( $post_id ) {

    $all_post_meta = get_post_custom_keys($post_id);

    if (is_array($all_post_meta)) {
        foreach( $all_post_meta as $post_meta_key){
            delete_post_meta($post_id, $post_meta_key);
        }
    }

}

function motopressCECleanupShortcode($content) {
    return strtr($content, array (
        '<p>[' => '[',
        '</p>[' => '[',
        ']<p>' => ']',
        ']</p>' => ']',
        ']<br />' => ']'
    ));
}

/**
 * @deprecated 1.6.9
 * Disable store revisions for tmpPost
 */
function motopressCEDisableRevisions($num, $post) {
    $tmpPostId = get_option('motopress_tmp_post_id_' . wp_get_current_user()->roles[0]);
    if ($tmpPostId && $post->ID == $tmpPostId) {
        $num = 0;
    }
    return $num;
}
