<?php
function motopressCEUpdateObjectTemplatesLibrary() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();

	if ( isset( $_POST['library'] ) && !empty( $_POST['library'] ) ) {
		$library = wp_unslash($_POST['library']);
		MPCEObjectTemplatesLibrary::getInstance()->save($library);
		wp_send_json_success();
	} else {
		motopressCESetError( __( "Error while getting the library of object templates", 'motopress-content-editor-lite' ) );
	}

	exit;
}