<?php

function motopressCEGetLangStrings() {
	return include(mpceSettings()['plugin_dir_path'] . 'includes/jsTranslations.php');
}

function generateShortcodeFromLibrary($shortcodeName, $customClasses = array()) {
	$motopressCELibrary = MPCELibrary::getInstance();
	$shortcodeObject = $motopressCELibrary->getObject($shortcodeName);
	$gridObjects = $motopressCELibrary->getGridObjects();
	$shortcode = '[' . $shortcodeName;
	foreach($shortcodeObject->getParameters() as $parameterName => $parameter) {
		if (isset($parameter['default']) && $parameter['default'] !== '') {
			$shortcode .= ' ' . $parameterName . '="' . $parameter['default'] . '"';
		}
	}
	$shortcodeStyles = $shortcodeObject->getStyles();
	$styleClassesArr = isset($shortcodeStyles['default']) && !empty($shortcodeStyles['default']) ? array_merge($customClasses, $shortcodeStyles['default']) : $customClasses;
	if (!empty($styleClassesArr)) {
		$shortcode .= ' mp_style_classes="';
		$shortcode .= implode(' ', $styleClassesArr);
		$shortcode .= '"';
	}

	// Add column width parameter
//    if (in_array($shortcodeName, array($gridObjects['span']['shortcode'], $gridObjects['span']['inner']))) {
//        $shortcode .= ' ' . $gridObjects['span']['attr'] . '="' . $gridObjects['row']['col'] . '"';
//    }

	$shortcode .= ']<div class="motopress-filler-content"><i class="fa fa-plus"></i></div>[/' . $shortcodeName . ']';
	return $shortcode;
}

function motopressCECheckDomainMapping() {
	global $wpdb;

	if (is_multisite()) {
		$wmudmActive = is_plugin_active('wordpress-mu-domain-mapping/domain_mapping.php');
		if ($wmudmActive) {
			$blogDetails = get_blog_details();
			$mappedDomains = $wpdb->get_col(sprintf("SELECT domain FROM %s WHERE blog_id = %d ORDER BY id ASC", $wpdb->dmtable, $blogDetails->blog_id));
			if (!empty($mappedDomains)) {
				if (!in_array(parse_url($blogDetails->siteurl, PHP_URL_HOST), $mappedDomains)) {
					add_action('admin_notices', 'motopressCEDomainMappingNotice');
				}
			}
		}
	}
}

function motopressCEDomainMappingNotice() {
	$linkDomainMapping = apply_filters('mpce_link_domain_mapping', 'https://motopress.zendesk.com/hc/en-us/articles/200884839-WordPress-Multisite-domain-mapping-configuration');
	echo '<div class="error"><p>' . sprintf(__("Use the mapped domain to access the admin area. Find out <a href='%s' target='_blank'>here</a> how to setup it", 'motopress-content-editor-lite'), esc_url($linkDomainMapping)) . '</p></div>';
}

function motopressCEIsjQueryVerNotice() {
	echo '<div class="error"><p>' . sprintf(__("Minimum jQuery version - %s. Minimum jQuery UI version - %s. Please update WordPress to 3.5 or higher.", 'motopress-content-editor-lite'), MPCERequirements::MIN_JQUERY_VER, MPCERequirements::MIN_JQUERYUI_VER) . '</p></div>';
}

function motopressCEIsMBStringEnabledNotice() {
	echo '<div class="error"><p>' . sprintf(__("%s error: contact your hosting provider to enable mbstring extension.", 'motopress-content-editor-lite'), mpceSettings()['brand_name']) . '</p></div>';
}