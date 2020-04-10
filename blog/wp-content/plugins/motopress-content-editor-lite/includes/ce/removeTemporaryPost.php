<?php

/** @deprecated 1.6.9 */
function motopressCERemoveTemporaryPost() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();
    require_once dirname(__FILE__).'/ThemeFix.php';
    require_once dirname(__FILE__).'/postMetaFix.php';

    $errors = array();

    $post_id = $_POST['post_id'];
    $post = get_post($post_id);

    if (!is_null($post)) {
        motopressCERemoveHeadwayFix();

        $delete = wp_trash_post($post_id);

        new MPCEThemeFix(MPCEThemeFix::ACTIVATE);

        if ($delete === false) {
            $errors[] = __("An error occurred while removing temporary post", 'motopress-content-editor-lite');
        }
    }

    if (!empty($errors)) {
        if (mpceSettings()['debug']) {
            print_r($errors);
        } else {
            motopressCESetError(__("An error occurred while removing temporary post", 'motopress-content-editor-lite'));
        }
    }
    exit();
}