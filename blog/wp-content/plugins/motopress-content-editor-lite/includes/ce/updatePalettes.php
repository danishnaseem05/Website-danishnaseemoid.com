<?php
function motopressCEupdatePalettes() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();

    if ( isset( $_POST['palettes'] ) && !empty( $_POST['palettes'] ) ){
        $palettes = $_POST['palettes'];
        update_option('motopress-palettes', $palettes);
        wp_send_json_success(array('palettes' => $palettes));
    } else {
        motopressCESetError(__("Error while getting the palettes", 'motopress-content-editor-lite'));
    }

    exit;
}