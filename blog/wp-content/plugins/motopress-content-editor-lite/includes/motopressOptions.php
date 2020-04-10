<?php
function motopressCEOptions() {

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressSettings',
            esc_attr('settings_updated'),
            __("Settings saved.", 'motopress-content-editor-lite'),
            'updated'
        );
    }

	$pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : mpceSettings()['plugin_short_name'];

	echo '<div class="wrap">';
	echo '<h1>' . __("Settings", 'motopress-content-editor-lite') . '</h1>';

	// Tabs
	$tabs = apply_filters('admin_mpce_settings_tabs', array(
		mpceSettings()['plugin_short_name'] => array(
			'label' => __("Visual Builder", 'motopress-content-editor-lite'),
			'priority' => 0,
			'callback' => 'motopressCESettingsTabContent'
		)
	));

    echo '<h2 class="nav-tab-wrapper">';
	if (is_array($tabs)) {
		uasort($tabs, 'motopressCESortTabs');
		foreach ($tabs as $tabId => $tab) {
			$class = ($tabId == $pluginId) ? ' nav-tab-active' : '';
			echo '<a href="' . esc_url(add_query_arg(array('page' => $_GET['page'], 'plugin' => $tabId), admin_url('admin.php'))) . '" class="nav-tab' . $class . '">' . esc_html($tab['label']) . '</a>';
		}
	}
    echo '</h2>';

	if (is_array($tabs) && array_key_exists($pluginId, $tabs)) {
		$callbackFunc = $tabs[$pluginId]['callback'];
		if (!empty($callbackFunc)) {
			if (
				(is_string($callbackFunc) && function_exists($callbackFunc)) ||
				(is_array($callbackFunc) && count($callbackFunc) === 2 && method_exists($callbackFunc[0], $callbackFunc[1]))
			) {
				call_user_func($callbackFunc);
			}
		}
	}
	echo '</div>';
}

function motopressCESettingsTabContent() {
	settings_errors('motopressSettings', false);
	echo '<form actoin="options.php" method="POST">';
//    settings_fields('motopressOptionsFields');
	do_settings_sections('motopress_options');
	echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="' . __("Save", 'motopress-content-editor-lite') . '" /></p>';
	echo '</form>';
}

add_action('admin_init', 'motopressCEInitOptions');
function motopressCEInitOptions() {

//    register_setting('motopressCEOptionsFields', 'motopressCEOptions'/*, 'plugin_options_validate'*/);
    register_setting('motopressCEOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCEOptionsFields', '', 'motopressCEOptionsSecTxt', 'motopress_options');
    add_settings_field('motopressContentType', __("Enable Visual Builder for", 'motopress-content-editor-lite'), 'motopressCEContentTypeSettings', 'motopress_options', 'motopressCEOptionsFields');

    $currentUser = wp_get_current_user();
    if (in_array('administrator', $currentUser->roles)) {
        register_setting('motopressCERolesSettingsFields', 'motopressCERolesOptions');
        add_settings_section('motopressCERolesSettingsFields', '', 'motopressCERolesSettingsSecTxt', 'motopress_options');
        add_settings_field('motopressRoles', __("Disable Visual Builder for user groups", 'motopress-content-editor-lite'), 'motopressCERolesSettingsFields', 'motopress_options', 'motopressCERolesSettingsFields');
    }

    register_setting('motopressCESpellcheckSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCESpellcheckSettingsFields', '', 'motopressCESpellcheckSecTxt', 'motopress_options');
    add_settings_field('motopressSpellcheck', __("Check spelling", 'motopress-content-editor-lite'), 'motopressCESpellcheckFields', 'motopress_options', 'motopressCESpellcheckSettingsFields');

	register_setting('motopressCEFixedRowWidthOptionsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEFixedRowWidthOptionsFields', '', 'motopressCEFixedRowWidthSecTxt', 'motopress_options');
    add_settings_field('motopressCEFixedRowWidth', __("Fixed Row Width", 'motopress-content-editor-lite'), 'motopressCEFixedRowWidthFields', 'motopress_options', 'motopressCEFixedRowWidthOptionsFields');

    register_setting('motopressCECustomCSSOptionsFields', 'motopressContentEditorOptions'/*, 'plugin_options_validate'*/);
    add_settings_section('motopressCECustomCSSOptionsFields', '', 'motopressCECustomCSSSecTxt', 'motopress_options');
    add_settings_field('motopressCustomCSS', __("Custom CSS code:", 'motopress-content-editor-lite'), 'motopressCECustomCSSFields', 'motopress_options', 'motopressCECustomCSSOptionsFields');

    register_setting('motopressCEExcerptSettingsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEExcerptSettingsFields', '', 'motopressCEExcerptSecTxt', 'motopress_options');
    add_settings_field('motopressExcerpt', __("Excerpt and More tag", 'motopress-content-editor-lite'), 'motopressCEExcerptFields', 'motopress_options', 'motopressCEExcerptSettingsFields');

    register_setting('motopressCEGoogleFontsFields', 'motopressContentEditorOptions');
    add_settings_section('motopressCEGoogleFontsFields', '', 'motopressCEGoogleFontsSecTxt', 'motopress_options');
    add_settings_field('motopressGoogleFonts', __("Google Fonts", 'motopress-content-editor-lite'), 'motopressCEGoogleFontsFields', 'motopress_options', 'motopressCEGoogleFontsFields');

    if (is_multisite() && is_main_site() && is_super_admin()) {
        register_setting('motopressCEHideSettingsFields', 'motopressContentEditorOptions');
        add_settings_section('motopressCEHideSettingsFields', '', 'motopressCEHideSecTxt', 'motopress_options');
        add_settings_field('motopressHide', __("WordPress Multisite", 'motopress-content-editor-lite'), 'motopressCEHideFields', 'motopress_options', 'motopressCEHideSettingsFields');
    }
}

function motopressCEOptionsSecTxt() {}
function motopressCEContentTypeSettings() {
    $postTypes = get_post_types(array('public' => true));
    $excludePostTypes = array('attachment' => 'attachment');
    $postTypes = array_diff_assoc($postTypes, $excludePostTypes);
    $checkedPostTypes = get_option('motopress-ce-options', array('post', 'page'));

    foreach ($postTypes as $key => $val) {
        if (post_type_supports($key, 'editor')) {
            $checked = '';
            if (in_array($key, $checkedPostTypes)) {
                $checked = 'checked="checked"';
            }
            echo '<label><input type="checkbox" name="post_types[]" value="'.$key.'" '.$checked.' /> ' . ucfirst($val) . '</label><br/>';
        }
    }
    echo '<br/>';
}

function motopressCERolesSettingsSecTxt(){}
function motopressCERolesSettingsFields(){
    global $wp_roles;
    if ( ! isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }
    $disabledRoles = get_option('motopress-ce-disabled-roles', array());

    $roles = $wp_roles->get_names();
    unset($roles['administrator']);

    foreach ($roles as $role => $roleName ){
        $checked = '';
        if (in_array($role, $disabledRoles)){
            $checked = 'checked="checked"';
        }
        echo '<label><input type="checkbox" name="disabled_roles[]" value="'.$role.'" '.$checked.' /> '.$roleName.'</label><br/>';
    }
}

function motopressCESpellcheckSecTxt(){}
function motopressCESpellcheckFields(){
    $spellcheck_enable = get_option('motopress-ce-spellcheck-enable', '1');

    $checked = '';
    if ($spellcheck_enable) {
        $checked = 'checked="checked"';
    }
    echo '<label><input type="checkbox" name="spellcheck_enable" ' . $checked . ' />' . __("Check my spelling as I type", 'motopress-content-editor-lite') . '</label><br/>';
    echo '<p class="description">'.__("To spell check your entry, spell checking must be enabled in your browser.", 'motopress-content-editor-lite').'</p>';
}

function motopressCEFixedRowWidthSecTxt() {}
function motopressCEFixedRowWidthFields() {
	$fixedRowWidth = get_option('motopress-ce-fixed-row-width', mpceSettings()['default_fixed_row_width']);
	echo '<input type="text" name="fixed_row_width" value="' . $fixedRowWidth . '" class="regular-text" />';
}

function motopressCECustomCSSSecTxt() {}
function motopressCECustomCSSFields() {

    if ( !mpceSettings()['wp_upload_dir_error'] ) {
        if (!file_exists(mpceSettings()['plugin_upload_dir_path']))
            mkdir(mpceSettings()['plugin_upload_dir_path'], 0777);

        clearstatcache();
        if ( is_writable(mpceSettings()['plugin_upload_dir_path']) ) {
            $css_file = mpceSettings()['custom_css_file_path'];
            if ( file_exists($css_file) ) {
                $cssValue = file_get_contents($css_file);
                $cssValue = esc_html( $cssValue );
            }else {
                $cssValue = '';
            }
            echo '<label><textarea name="custom_css" cols="40" rows="10" style="width:100%;max-width:1000px;">'.$cssValue.'</textarea></label>';
            echo '<p class="description">'.__("Submit your CSS code to this field to add new styles to the theme.", 'motopress-content-editor-lite').'</p>';
        }else {
            printf(__("Note: you are not able to edit custom css code because the directory %s not found or not writable.", 'motopress-content-editor-lite'), mpceSettings()['plugin_upload_dir_path']);
        }
    }else {
        printf(__("Note: you are not able to edit custom css code because the directory %s not found or not writable.", 'motopress-content-editor-lite'), mpceSettings()['wp_upload_dir']);
    }
}

function motopressCEExcerptSecTxt() {}
function motopressCEExcerptFields() {
    // Excerpt shortcode
    $excerptShortcode = get_option('motopress-ce-excerpt-shortcode', '1');
    $checked = '';
    if ($excerptShortcode) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="excerpt_shortcode"' . $checked . '>' . __("Convert shortcodes in excerpt to html", 'motopress-content-editor-lite') . '</label><br>';
}

function motopressCEGoogleFontsSecTxt() {}
function motopressCEGoogleFontsFields() {
    clearstatcache();
    $error = motopress_check_google_font_dir_permissions(true);

    if (!isset($error['error'])) {
        $prefix = mpceSettings()['google_font_classes_prefix'];
        $fonts = array();
        $googleFontsJSON = file_get_contents(mpceSettings('plugin_dir_path') . 'googlefonts/webfonts.json' );
        if ($googleFontsJSON) {
            $googleFonts = json_decode( $googleFontsJSON, true );
            if (!is_null($googleFonts) && isset($googleFonts['items'])) {
                foreach($googleFonts['items'] as $googleFont) {
                    $id = strtolower( str_replace( ' ', '_', $googleFont['family'] ) );
                    $fonts[$id] = $googleFont;
                }
            }
        }
        $googleFontsJSON = json_encode($fonts);

	    $scriptSuffix = mpceSettings()['script_suffix'];

        wp_register_script('mp-google-font-class-manager', mpceSettings()['plugin_dir_url'] . 'includes/js/mp-google-font-class-manager' . $scriptSuffix . '.js', array('jquery'), mpceSettings()['plugin_version']);
        wp_localize_script('mp-google-font-class-manager', 'motopressGoogleFontsJSON', $googleFontsJSON);
        wp_enqueue_script('mp-google-font-class-manager');
        $googleFontClasses = get_option('motopress_google_font_classes', array());
        echo '<p>' . sprintf(__("Use this form to add <a href='https://www.google.com/fonts'>Google Fonts</a> to %s. You can find these fonts at the Style panel of the text objects. Press <b>Save</b> button at the bottom of the page to apply your changes.", 'motopress-content-editor-lite'), mpceSettings()['brand_name']) . '</p><br/>';
        echo '<p>' . __("Tip: <i>Using many font styles and character sets can slow down your webpage, so only select the ones you actually need on your webpage.</i>", 'motopress-content-editor-lite') . '</p><br/>';
        echo '<div id="motopress-google-font-class-manager">';
        echo '<input type="hidden" name="google_font_dir_writable" value="true">';
        foreach ($googleFontClasses as $className => $googleFontClass) {
            $variantCheckboxes = '';
            $subsetCheckboxes = '';
            echo '<div class="mp-google-font-class-entry">';
            echo '<div class="mp-google-font-class-name-container">';
            echo '<span class="mp-google-font-class-name">' . $className . '</span>';
            echo '<button class="mp-remove-google-font-class-entry button">' . __("Remove", 'motopress-content-editor-lite') . '</button>';
            echo '</div>';
            echo '<div class="mp-google-font-details">';
            echo '<label class="mp-google-fonts-list-container">'.__("Font Family", 'motopress-content-editor-lite').'<select class="mp-google-fonts-list" name="motopress_google_font_classes[' . $className . '][family]">';
            foreach ($googleFonts['items'] as $googleFont) {
                if ( $googleFontClass['family'] === $googleFont['family'] ) {
                    $selected = ' selected="selected"';
                    $variantCheckboxes = '<div class="mp-google-font-variants"><label>'.__("Styles:", 'motopress-content-editor-lite').'</label>';
                    foreach($googleFont['variants'] as $variant) {
                        $checked = isset($googleFontClass['variants']) && in_array($variant, $googleFontClass['variants']) ? ' checked="checked"' : '';
                        $variantCheckboxes .= '<label><input type="checkbox" ' . $checked . ' name="motopress_google_font_classes[' . $className . '][variants][]" value="' . $variant . '">'.$variant.'</label>';
                    }
                    $variantCheckboxes .= '</div>';
                    $subsetCheckboxes = '<div class="mp-google-font-subsets"><label>'.__("Character sets:", 'motopress-content-editor-lite').'</label>';
                    foreach($googleFont['subsets'] as $subset) {
                        $checked = isset($googleFontClass['subsets']) && in_array($subset, $googleFontClass['subsets']) ? ' checked="checked"' : '';
                        $subsetCheckboxes .= '<label><input type="checkbox" ' . $checked . ' name="motopress_google_font_classes[' . $className . '][subsets][]" value="' . $subset . '">'.$subset.'</label>';
                    }
                    $subsetCheckboxes .= '</div>';
                } else {
                    $selected = '';
                }
                echo '<option value="' . $googleFont['family'] . '" ' . $selected . '>' . $googleFont['family'] . '</option>';
            }
            echo '</select></label>';
            echo $variantCheckboxes;
            echo $subsetCheckboxes;
            echo '</div>';
            echo '</div>';
        }
        echo '<div id="motopress-google-font-class-manager-tools">';
        echo '<label class="mp-google-fonts-list-container">'.__("Font Family", 'motopress-content-editor-lite').'<select class="mp-google-fonts-list">';
        foreach($googleFonts['items'] as $googleFont){
            echo '<option value="' . $googleFont['family'] . '">' . $googleFont['family'] . '</option>';
        }
        echo '</select></label>';
        echo '<div class="mp-google-font-variants"><label>'.__("Styles:", 'motopress-content-editor-lite').'</label></div>';
        echo '<div class="mp-google-font-subsets"><label>'.__("Character sets:", 'motopress-content-editor-lite').'</label></div>';
        echo '<button class="mp-remove-google-font-class-entry button">' . __("Remove", 'motopress-content-editor-lite') . '</button>';
        echo '<p class="mp-google-font-add-new-label">'.__("Add New Font style:", 'motopress-content-editor-lite').'</p>';
        echo '<label for="class-name">'.__("Custom Style Name:", 'motopress-content-editor-lite').'</label>';
        echo '<input id="class-name" class="class-name" type="text" />';
        echo '<button class="mp-create-google-font-class-entry button">' . __("Add Google Font", 'motopress-content-editor-lite') . '</button>';
        echo '<p class="description mp-google-font-custom-style-desc">'.__("Enter Custom Style Name ex. HomePageHeader to use in Style selector and press Add Google Font button. Choose Font Family and styles of the created font style.", 'motopress-content-editor-lite') .'</p>';
        echo '<p class="font-name-info"><span class="wrong-class-name hidden">'.__("Custom Style Name can contain only latin letters, numbers, hyphens and underscores.", 'motopress-content-editor-lite').'</span><span class="duplicate-class-name hidden">'.__("This Custom Style Name already exists.", 'motopress-content-editor-lite').'</span></p>';
        echo '</div>';
        echo '</div>';
    } else {
        echo $error['error'];
    }
}

function motopressCEHideSecTxt() {}
function motopressCEHideFields() {

    $hideOption = get_site_option('motopress-ce-hide-options-on-subsites', '0');

    $checked = '';
    if ($hideOption) {
        $checked = ' checked="checked"';
    }
    echo '<label><input type="checkbox" name="hide_options"' . $checked . '>' . sprintf(__("Hide %s Settings on subsites", 'motopress-content-editor-lite'), mpceSettings()['brand_name']) . '</label><br>';
}

function motopressCESettingsSave() {
	$pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : mpceSettings()['plugin_short_name'];

	if ($pluginId === mpceSettings()['plugin_short_name']) {
		if (!empty($_POST)) {

			// Post Types
			$postTypes = array();
			if (isset($_POST['post_types']) and count($_POST['post_types']) > 0) {
				$postTypes = $_POST['post_types'];
			}
			update_option('motopress-ce-options', $postTypes);

			// Roles
			$disabledRoles = array();
			if (isset($_POST['disabled_roles']) and count($_POST['disabled_roles']) > 0) {
				$disabledRoles = $_POST['disabled_roles'];
			}
			update_option('motopress-ce-disabled-roles', $disabledRoles);

			// Spellcheck
			if (isset($_POST['spellcheck_enable'])) {
				$spellcheck_enable = '1';
			} else {
				$spellcheck_enable = '0';
			}
			update_option('motopress-ce-spellcheck-enable', $spellcheck_enable);

			// Custom CSS
			if (isset($_POST['custom_css'])) {

				if (!file_exists(mpceSettings()['plugin_upload_dir_path']))
					mkdir(mpceSettings()['plugin_upload_dir_path'], 0777);

				$current_css = $_POST['custom_css'];

				// css file creation & rewrite
				if (!empty($current_css)) {
					$content = stripslashes($current_css);
					clearstatcache();
					if (is_writable(mpceSettings()['wp_upload_dir']))
						file_put_contents(mpceSettings()['custom_css_file_path'], $content);
				} else {
					if (file_exists(mpceSettings()['custom_css_file_path'])) {
						clearstatcache();
						if (is_writable(mpceSettings()['wp_upload_dir']))
							unlink(mpceSettings()['custom_css_file_path']);
					}
				}
				// css file deletion END
			}

			// Excerpt shortcode
			if (isset($_POST['excerpt_shortcode']) && $_POST['excerpt_shortcode']) {
				$excerptShortcode = '1';
			} else {
				$excerptShortcode = '0';
			}
			update_option('motopress-ce-excerpt-shortcode', $excerptShortcode);

			// Hide options
			if (is_multisite() && is_main_site() && is_super_admin()) {
				if (isset($_POST['hide_options']) && $_POST['hide_options']) {
					$hideOptions = '1';
				} else {
					$hideOptions = '0';
				}
				update_site_option('motopress-ce-hide-options-on-subsites', $hideOptions);
			}

			if (isset($_POST['fixed_row_width'])) {
				$fixedRowWidth = filter_input(INPUT_POST, 'fixed_row_width', FILTER_VALIDATE_INT, array(
					'options'=>array(
						'min_range' => 1
					)
				));
				if ($fixedRowWidth) {
					update_option('motopress-ce-fixed-row-width', $fixedRowWidth);
				}
			}

			//Google Fonts Classes
			if (isset($_POST['google_font_dir_writable'])) {
				$fontClasses = isset($_POST['motopress_google_font_classes']) ? $_POST['motopress_google_font_classes'] : array();
				saveGoogleFontClasses($fontClasses);
			}

			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'plugin' => $pluginId, 'settings-updated' => 'true'), admin_url('admin.php')));
		}

	} else {
		do_action('admin_mpce_settings_save-' . $pluginId);
	}
}

function saveGoogleFontClasses($fontClasses){
    clearstatcache();
    $error = motopress_check_google_font_dir_permissions(true);
    if (!isset($error['error'])) {
        $prefix = mpceSettings()['google_font_classes_prefix'];
        $oldFontClasses = get_option('motopress_google_font_classes', array());
        //remove unused files
        $removeClasses = array_diff_key($oldFontClasses, $fontClasses);
        foreach($removeClasses as $removeClass) {
            if (isset($removeClass['file']) && file_exists(mpceSettings()['google_font_classes_dir'] . $removeClass['file'])){
                if ( is_writable(mpceSettings()['google_font_classes_dir'] . $removeClass['file']) ){
                    unlink(mpceSettings()['google_font_classes_dir'] . $removeClass['file']);
                    clearstatcache();
                }
            }
        }
        foreach ($fontClasses as $fontClassName => $fontClass) {
            if (isset($oldFontClasses[$fontClassName])
                && ( $oldFontClasses[$fontClassName]['family'] === $fontClass['family'])
                && (
                    ( isset($oldFontClasses[$fontClassName]['variants']) && isset($fontClass['variants']) && $oldFontClasses[$fontClassName]['variants'] == $fontClass['variants'] )
                    ||
                    ( !isset($oldFontClasses[$fontClassName]['variants']) && !isset($fontClass['variants']) )
                )
                && (
                    ( isset($oldFontClasses[$fontClassName]['subsets']) && isset($fontClass['subsets']) && $oldFontClasses[$fontClassName]['subsets'] == $fontClass['subsets'] )
                    ||
                    ( !isset($oldFontClasses[$fontClassName]['subsets']) && !isset($fontClass['subsets']) )
                )
            ) {
                $fontClasses[$fontClassName] = $oldFontClasses[$fontClassName];
            } else {
                $importFamily = str_replace(' ', '+', $fontClass['family']);
                $importSubsets = '';
                $importVariants = '';
                if (isset($fontClass['subsets'])){
                    $importSubsets = '&subset=' . join(',', $fontClass['subsets']);
                }
                if (isset($fontClass['variants'])){
                    $importVariants = ':' . join(',', $fontClass['variants']);
                }
                $content = '@import url(\'https://fonts.googleapis.com/css?family=' . $importFamily . $importVariants . $importSubsets . '\');' . "\n";
                $content .= '.' . $prefix . $fontClassName . ' *{'
                        . 'font-family: ' . $fontClass['family'] . ';'
                        . '}' . "\n";
                if (isset($fontClass['variants'])) {
                    foreach($fontClass['variants'] as $variant) {
                        $fontStyle = stripos($variant, 'italic') !== false ? 'font-style:italic !important;' : 'font-style:normal !important;';
                        $emFontStyle = 'font-style:italic !important;';
                        $weight = preg_replace('/\D/', '', $variant);
                        if ($weight == '') {
                            $weight = '400';
                        }
                        if ($weight < 400) {
                            $strongFontWeight = ' font-weight: 400 !important;';
                        } else {
                            $strongFontWeight = ' font-weight: 700 !important;';
                        }
                        $fontWeight = 'font-weight:' . $weight . ' !important;';
                        $content .= '.' . $prefix . $fontClassName . '-' . $variant . ' *{'
                                . 'font-family : ' . $fontClass['family'] . ';}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' *{'
                                . $fontStyle
                                . $fontWeight
                                . '}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' strong{'
                                . $strongFontWeight
                                . '}'
                                . '.' . $prefix . $fontClassName . '-' . $variant . ' em{'
                                . $emFontStyle
                                . '}' . "\n";
                    }
                }
                $fontClasses[$fontClassName]['css'] = $content;
                $fontClasses[$fontClassName]['fullname'] = $prefix . $fontClassName;

                $filename = $fontClassName . '.css';
                if (false !== file_put_contents(mpceSettings()['google_font_classes_dir'] . $filename, $content)) {
                    $fontClasses[$fontClassName]['file'] = $filename;
                } else {
                    unset($fontClasses[$fontClassName]);
                }
            }
        }
        update_option('motopress_google_font_classes',$fontClasses);
    }
}

function motopressCELicense() {

    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error(
            'motopressLicense',
            esc_attr('settings_updated'),
            __("Settings saved.", 'motopress-content-editor-lite'),
            'updated'
        );
    }

    $pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : mpceSettings()['plugin_short_name'];

    echo '<div class="wrap">';
    echo '<h1>' . __("Licenses", 'motopress-content-editor-lite') . '</h1>';

    // Tabs
	$tabs = mpceSettings()['license_tabs'];
	if (count($tabs)) {
	    echo '<h2 class="nav-tab-wrapper">';
		foreach ($tabs as $tabId => $tab) {
			$class = ($tabId == $pluginId) ? ' nav-tab-active' : '';
			echo '<a href="' . esc_url(add_query_arg(array('page' => $_GET['page'], 'plugin' => $tabId), admin_url('admin.php'))) . '" class="nav-tab' . $class . '">' . esc_html($tab['label']) . '</a>';
		}
	    echo '</h2>';

	    if (array_key_exists($pluginId, $tabs)) {
	        $callbackFunc = $tabs[$pluginId]['callback'];
	        if (!empty($callbackFunc)) {
	            if (
	                (is_string($callbackFunc) && function_exists($callbackFunc)) ||
	                (is_array($callbackFunc) && count($callbackFunc) === 2 && method_exists($callbackFunc[0], $callbackFunc[1]))
	            ) {
	                call_user_func($callbackFunc);
	            }
	        }
	    }
	}
    echo '</div>';
}



// check a license key
function edd_mpce_check_license($license) {
    $result = array(
        'errors' => array(),
        'data' => array()
    );
	$apiParams = array(
		'edd_action' => 'check_license',
		'license'    => $license,
		'item_id'    => mpceSettings()['edd_mpce_item_id'],
		'url'        => home_url(),
	);

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, mpceSettings()['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    if (is_wp_error($response)) {
        $errors = $response->get_error_codes();
        foreach ($errors as $key => $code) {
            $result['errors'][$code] = $response->get_error_message($code);
        }
        return $result;
    }

    $licenseData = json_decode(wp_remote_retrieve_body($response));

    if (!is_null($licenseData)) {
        $result['data'] = $licenseData;
    } else {
        $result['errors']['json_decode'] = 'Unable to decode JSON string.';
    }

    return $result;
}

function motopressCELicenseLoad() {
    $pluginId = isset($_GET['plugin']) ? $_GET['plugin'] : mpceSettings()['plugin_short_name'];

	if (
		empty($_POST)
		&& (
			!isset($_GET['plugin'])
			&& !array_key_exists(mpceSettings()['plugin_short_name'], mpceSettings()['license_tabs'])
		)
	) {
		reset(mpceSettings()['license_tabs']);
		$_pluginId = key(mpceSettings()['license_tabs']);
		if ($_pluginId) {
			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'plugin' => $_pluginId), admin_url('admin.php')));
		}
	}

    if ($pluginId === mpceSettings()['plugin_short_name']) {
        if (!empty($_POST)) {
            $queryArgs = array('page' => $_GET['page']);

            if (isset($_POST['edd_mpce_license_key'])) {
                if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return;
                }
                $licenseKey = trim($_POST['edd_mpce_license_key']);
                motopressCESetLicense($licenseKey);
            }

            //activate
            if (isset($_POST['edd_license_activate'])) {
                if (!check_admin_referer('edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return; // get out if we didn't click the Activate button
                }
                $licenseData = motopressCEActivateLicense();

                if ($licenseData === false)
                    return false;

                if (!$licenseData->success && $licenseData->error === 'item_name_mismatch') {
                    $queryArgs['item-name-mismatch'] = 'true';
                }
            }

            //deactivate
            if (isset($_POST['edd_license_deactivate'])) {
                // run a quick security check
                if (!check_admin_referer( 'edd_mpce_nonce', 'edd_mpce_nonce')) {
                    return; // get out if we didn't click the Activate button
                }

                $licenseData = motopressCEDeactivateLicense();

                if ($licenseData === false)
                    return false;
            }

            $queryArgs['settings-updated'] = 'true';
            wp_redirect(add_query_arg($queryArgs, get_admin_url() . 'admin.php'));
        }
    } else {
        do_action('admin_mpce_license_save-' . $pluginId);
    }
}

function motopressCESetAndActivateLicense($licenseKey){
    motopressCESetLicense($licenseKey);
    motopressCEActivateLicense();
}

function motopressCESetLicense($licenseKey){
    $oldLicenseKey = get_option('edd_mpce_license_key');
    if ($oldLicenseKey && $oldLicenseKey !== $licenseKey) {
        delete_option('edd_mpce_license_status'); // new license has been entered, so must reactivate
    }
    if (!empty($licenseKey)) {
        update_option('edd_mpce_license_key', $licenseKey);
    } else {
        delete_option('edd_mpce_license_key');
    }
}

function motopressCEActivateLicense(){
    $licenseKey = get_option('edd_mpce_license_key');

    // data to send in our API request
	$apiParams = array(
		'edd_action' => 'activate_license',
		'license'    => $licenseKey,
		'item_id'    => mpceSettings()['edd_mpce_item_id'],
		'url'        => home_url(),
	);

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, mpceSettings()['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    // make sure the response came back okay
    if (is_wp_error($response)) {
        return false;
    }

    // decode the license data
    $licenseData = json_decode(wp_remote_retrieve_body($response));

    // $licenseData->license will be either "active" or "inactive"
    update_option('edd_mpce_license_status', $licenseData->license);

    return $licenseData;
}

function motopressCEDeactivateLicense(){
    // retrieve the license from the database
    $licenseKey = get_option('edd_mpce_license_key');

    // data to send in our API request
	$apiParams = array(
		'edd_action' => 'deactivate_license',
		'license'    => $licenseKey,
		'item_id'    => mpceSettings()['edd_mpce_item_id'],
		'url'        => home_url(),
	);

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($apiParams, mpceSettings()['edd_mpce_store_url']), array('timeout' => 15, 'sslverify' => false));

    // make sure the response came back okay
    if (is_wp_error($response)) {
        return false;
    }

    // decode the license data
    $licenseData = json_decode(wp_remote_retrieve_body($response));

    // $license_data->license will be either "deactivated" or "failed"
    if($licenseData->license == 'deactivated') {
        delete_option('edd_mpce_license_status');
    }
    return $licenseData;
}