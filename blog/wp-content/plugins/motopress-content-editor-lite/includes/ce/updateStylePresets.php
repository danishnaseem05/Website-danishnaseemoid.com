<?php
function motopressCEUpdateStylePresets() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();

	if ( isset( $_POST['presetsData'] ) && !empty( $_POST['presetsData'] ) ) {
		$presetsData = wp_unslash( $_POST['presetsData'] );

		MPCECustomStyleManager::savePresetsLastId( $presetsData['presetsLastId'] );
		MPCECustomStyleManager::savePresets( json_decode($presetsData['presets'], true) );

		wp_send_json_success();
	} else {
		motopressCESetError( __( "Error while getting the presets data", 'motopress-content-editor-lite' ) );
	}

	exit;
}