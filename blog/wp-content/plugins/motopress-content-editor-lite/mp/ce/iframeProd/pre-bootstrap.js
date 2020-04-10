(function() {
	try {
		if (jQuery.hasOwnProperty('ui') && jQuery.ui.hasOwnProperty('version')) {
			var jQueryUIVer = jQuery.ui.version;

			console.warn(parent.MP.Utils.strtr('The current page contains jQuery UI v%version%', {'%version%': jQueryUIVer}));

			if (parent.MP.Utils.version_compare(jQueryUIVer, '1.9.0', '<')) {
				jQuery.curCSS = jQuery.css;
				delete jQuery.ui;

				if (typeof $ !== 'undefined') {
					$.curCSS = jQuery.css;
					delete $.ui;
				}

			}
		}

	} catch (e) {
		parent.MP.Error.log(e, true);
	}
}());