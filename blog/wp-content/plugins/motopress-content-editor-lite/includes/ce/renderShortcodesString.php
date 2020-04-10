<?php
function motopressCERenderShortcodeString() {
	mpceVerifyNonce();
	mpceShowNoticeForWrongRequestOrAccessDenied();

    $errorMessage = sprintf(__("An error occurred while rendering %s", 'motopress-content-editor-lite'), __("shortcode", 'motopress-content-editor-lite'));

    if (!empty($_POST['content'])) {
        $errors = array();

		$content = $_POST['content'];

        $motopressCELibrary = MPCELibrary::getInstance();

        $type = $_POST['type'];

        if ($content) {
            $content = stripslashes($content);
	        $content = mpce_wpautop($content);
            $content = mpceRenderContent($content, array(
	            'wrapOuterCode' => in_array( $type, array( 'row', 'page' ) ),
            	'contentWrapper' => 'motopress-ce-rendered-content-wrapper'
            ));

            if ($content) {
            	$content .= '<div class="motopress-ce-private-styles-updates-wrapper">' . MPCECustomStyleManager::getInstance()->getPrivateStylesTag(true) . '</div>';
	            echo $content;
            }
            else {
            	$errors[] = $errorMessage;
            }
        }
        else {
            $errors[] = $errorMessage;
        }

        if (!empty($errors)) {
            if (mpceSettings()['debug']) {
                print_r($errors);
            } else {
                motopressCESetError($errorMessage);
            }
        }
    } else {
        motopressCESetError($errorMessage);
    }
    exit;
}