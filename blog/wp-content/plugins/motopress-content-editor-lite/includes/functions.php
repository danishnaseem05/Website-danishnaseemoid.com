<?php

/**
 * Check is current request load editor
 *
 * @return bool
 * @since 3.0.0
 */
function mpceIsEditor(){
	return mpceSettings('is_editor');
}

/**
 * Check is current request load editor scene or editor ajax
 *
 * @return bool
 * @since 3.0.0
 */
function mpceIsEditorScene(){
	return mpceSettings('is_editor_scene');
}

function motopressCESetError($message = '') {
    header('HTTP/1.1 500 Internal Server Error');
    wp_send_json(array(
        'debug' => mpceSettings() ? mpceSettings()['debug'] : false,
        'message' => $message
    ));
    exit;
}

function motopressCEMbEncodeNumericentity(&$item, $key, $options) {
    if (is_string($item)) {
        $item = mb_encode_numericentity($item, $options['convmap'], $options['encoding']);
    }
}

function motopressCEJsonEncodeUnescapedUnicode($array) {

	if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
		return json_encode($array, JSON_UNESCAPED_UNICODE);
	}

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
    $options = array(
        'convmap' => array(0x80, 0xffff, 0, 0xffff),
        'encoding' => 'UTF-8'
    );
    array_walk_recursive($array, 'motopressCEMbEncodeNumericentity', $options);
    return mb_decode_numericentity(json_encode($array), $options['convmap'], $options['encoding']);
}

/**
 * @return array|mixed
 */
function mpceSettings( $settingName = null ) {
	global $motopressCESettings;
	return is_null( $settingName ) ? $motopressCESettings : $motopressCESettings[ $settingName ];
}

/**
 * @param {string} $name
 * @param {mixed} $value
 */
function mpceSetSetting( $name, $value ) {
	global $motopressCESettings;
	$motopressCESettings[ $name ] = $value;
}

function mpceShowNoticeForWrongRequestOrAccessDenied(){
	if ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
	     strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest'
	) {
		if ( !MPCEAccess::getInstance()->hasAccess( $_POST['postID'] ) ) {
			motopressCESetError( __( "Maybe you are not logged in or you have no permission", 'motopress-content-editor-lite' ) );
		}
	}
}

/**
 * Exit with an error message if the nonce is incorrect
 */
function mpceVerifyNonce() {
	if ( !isset( $_REQUEST['nonce'] ) or empty( $_REQUEST['nonce'] ) or
	     !wp_verify_nonce( $_REQUEST['nonce'], 'wp_ajax_' . $_REQUEST['action'] )
	) {
		exit( 'Nonce error' );
	}
}

/**
 * @return array
 */
function motopressCEGetLang() {
	global $motopressCESettings;

	$defaultLocale = 'en';
	$wpLocale      = get_locale();
	$locale        = substr($wpLocale, 0, 2);
	$locale2       = str_replace('_', '-', $wpLocale);

	$vendors = $motopressCESettings['plugin_dir_path'] . 'vendors';

	if (file_exists("{$vendors}/tinymce/langs/{$locale}.js")) {
		$tinymce = $locale;
	} elseif (file_exists("{$vendors}/tinymce/langs/{$wpLocale}.js")) {
		$tinymce = $wpLocale;
	} else {
		$tinymce = $defaultLocale;
	}

	if (file_exists("{$vendors}/select2/select2_locale_{$locale}.js")) {
		$select2 = $locale;
	} elseif (file_exists("{$vendors}/select2/select2_locale_{$locale2}.js")) {
		$select2 = $locale2;
	} else {
		$select2 = $defaultLocale;
	}

	$lang = array(
		'tinymce' => $tinymce,
		'select2' => $select2,
	);

	return $lang;
}

/**
 * Send json of MPCE settings and exit
 */
function motopressCEGetWpSettings() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();

	wp_send_json(mpceSettings());
	exit;
}

function mpceClearWPAttachmentDetails() {
	mpceSetWPAttachmentDetails( array() );
}

function mpceSetWPAttachmentDetails( $details ) {
	global $motopressCEWPAttachmentDetails;
	$motopressCEWPAttachmentDetails = $details;
}

function mpceGetWPAttachmentDetails() {
	global $motopressCEWPAttachmentDetails;

	return $motopressCEWPAttachmentDetails;
}

/**
 * @param mixed $content
 *
 * @return false|mixed|string|void
 */
function mpce_wp_json_encode( $content ) {
	return function_exists( 'wp_json_encode' ) ? wp_json_encode( $content ) : json_encode( $content );
}

/**
 * Autop content and unautop mpce shortcodes
 *
 * @param string $content
 * @param bool [$cleanExtraSpace=true] Clean extra spaces after shortcode tag
 *
 * @return string
 */
function mpce_wpautop($content, $cleanExtraSpace = true){
	$content = trim( $content );
	$content = wpautop( $content );
	// remove extra newline which added with autop
	$content = preg_replace('/\n+$/', '', $content);
	$content = MPCEShortcode::unautopBuilderShortcodes($content);
//	$content = motopressCECleanupShortcode( $content );
	if ( $cleanExtraSpace ) {
		// @note this regex clean spaces after ] symbol, not after shortcodes only
		$content = preg_replace( '/\][\s]*/', ']', $content );
	}
	return $content;
}

/**
 * @param array $array
 * @param int $from
 * @param int $to
 *
 * @return array
 */
function mpce_array_move_element( $array, $from, $to ) {
	$movedEl = array_splice( $array, $from, 1 );
	if ( $from < $to ) {
		$to --;
	}
	$resultArray = array_slice( $array, 0, $to, true );
	$resultArray += $movedEl + array_slice( $array, $to, count( $array ), true );

	return $resultArray;
}

/**
 * @param string $from
 * @param string $to
 * @param string $classes
 *
 * @return mixed
 */
function mpce_replace_class_in_string( $from, $to, $classes ) {
	$regex = "/(^|\s)({$from})(\s|$)/i";
	return preg_replace($regex, '${1}' . $to . '${3}', $classes);
}

/**
 * Compares “PHP-standardized” version with wp version
 *
 * @param string $version
 * @param string $operator The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively.
							This parameter is case-sensitive, so values should be lowercase.
 *
 * @return bool
 */
function mpce_is_wp_version($version, $operator = '='){
	global $wp_version;
	return version_compare($wp_version, $version, $operator);
}