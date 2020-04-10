<?php
if (!defined('ABSPATH')) exit;

$error = motopress_check_google_font_dir_permissions(true);

if (!isset($error['error'])) {

	$fontClasses = get_option('motopress_google_font_classes');

	if (is_array($fontClasses)) {

		foreach ($fontClasses as &$class) {

			if (is_array($class) && !empty($class['css']) && !empty($class['file'])) {

				$class['css'] = str_replace('http://fonts.googleapis.com', '//fonts.googleapis.com', $class['css']);

				file_put_contents(mpceSettings()['google_font_classes_dir'] . $class['file'], $class['css']);

			}

		}

		update_option('motopress_google_font_classes', $fontClasses);

	}

}