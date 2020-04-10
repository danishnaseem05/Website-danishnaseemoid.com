<?php
function motopressCEGetAttachmentThumbnail() {
    mpceVerifyNonce();
    mpceShowNoticeForWrongRequestOrAccessDenied();

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int) trim($_POST['id']);
        $attachment = get_post($id);
        if (!empty($attachment) && $attachment->post_type === 'attachment') {
            if (wp_attachment_is_image($id)) {

                $srcMedium = wp_get_attachment_image_src($id, 'medium');
                $srcFull = wp_get_attachment_image_src($id, 'full');

                if (isset($srcMedium[0]) && !empty($srcMedium[0])
                        && isset($srcFull[0]) && !empty($srcFull[0])) {
                    $attachmentImageSrc = array();
                    $attachmentImageSrc['medium'] = $srcMedium[0];
                    $attachmentImageSrc['full'] = $srcFull[0];
                    wp_send_json($attachmentImageSrc);
                } else {
                    motopressCESetError(__("Error getting the attachment image src", 'motopress-content-editor-lite'));
                }
            } else {
                motopressCESetError(__("Attachment is not an image", 'motopress-content-editor-lite'));
            }
        } else {
            motopressCESetError(__("Empty attachment or post type not equal 'attachment'", 'motopress-content-editor-lite'));
        }
    } else {
        motopressCESetError(__("Error while getting the attachment thumbnail", 'motopress-content-editor-lite'));
    }
    exit;
}