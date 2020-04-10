(function($) {
	window.MP = {
		Loader: (function() {
			var callbacks = [], map = {};
			return {
				add: function(name, callback) {
					callbacks.push(callback);
					map[name] = callbacks.length - 1;
				},
				execSingle: function(name) {
					if (map[name] !== undefined) {
						callbacks[map[name]]();
					}
				},
				execAll: function() {
					var cbLen = callbacks.length;
					for (var i = 0; i < cbLen; i++) {
						if (typeof callbacks[i] === 'function') {
							callbacks[i]();
						}
					}
				}
			}
		})()
	};
	window.CE = {};

	if (MPCEAdmin.mpceEnabled) {

		window.mpceIsDirtyDecorator = function() {
			return MP.WpRevision.isDirty();
		};

		window.MP.WpRevision = new (function WpRevision() {
			var self = this,
				dirty = false,
				wpEditor = null, mpceEditor = null;

			this.init = function() {
				wpEditor = tinyMCE.get('content');
				mpceEditor = tinyMCE.get('motopresscecontent');

				bindDecorator();
			};

			this.isDirty = function() {
				var isDirty = this.isBuilderDirty();
				if (!isDirty) isDirty = mpceEditor.old_isDirty();
				if (!isDirty) isDirty = wpEditor.old_isDirty();

				return isDirty;
			};

			this.isBuilderDirty = function() {
				return dirty;
			};

			this.setBuilderDirty = function(value) {
				dirty = value;
			};

			this.makeWpNonDirty = function() {
				tinyMCE.triggerSave();
			};

			function bindDecorator() {
				var hasDirtyFunc = (mpceEditor && typeof mpceEditor.isDirty !== 'undefined');

				if (hasDirtyFunc) {
					mpceEditor.focus();

					wpEditor.old_isDirty = wpEditor.isDirty;
					mpceEditor.old_isDirty = mpceEditor.isDirty;

					wpEditor.isDirty = mpceIsDirtyDecorator;
					mpceEditor.isDirty = mpceIsDirtyDecorator;
					tinyMCE.activeEditor.isDirty = mpceIsDirtyDecorator;
				}
			}

		});
	}

	$(document).ready(function() {
		var
			$title = $('#title'),
			$defaultTab = $('#mpce-tab-default'),
			$editorTab = $('#mpce-tab-editor'),
			activeTabClass = 'nav-tab-active';

		var preloader = $('#motopress-preload');

		var insertStatusField = function(status) {
			$('.mpce-hidden-fields').empty()
				.append(
					$('<input />', {
						type: 'hidden',
						name: 'mpce-status',
						value: status ? 'enabled' : 'disabled'
					})
				);
		};

		var switchWithStatus = function(status) {
			$(window).off('beforeunload');
			insertStatusField(status);
			$('form#post').submit();
		};

		var setDefaultTitle = function() {
			var noTitle = 'Post #' + MPCEAdmin.postId;
			if (!noTitle) noTitle = MPCEAdmin.noTitleLabel;
			$title.val(noTitle);
			$('form[name="post"] #title-prompt-text').addClass('screen-reader-text');
		};

		var wpTabCallback = function() {
			if ($(this).hasClass(activeTabClass)) return;

			$(this).off('click', wpTabCallback);
			switchWithStatus(false);
		};

		var mpceTabCallback = function() {
			if ($(this).hasClass(activeTabClass)) return;
			$(this).off('click', mpceTabCallback);

			if (MPCEAdmin.postStatus == 'auto-draft') {
				if ($title.length && !$.trim($title.val()).length) {
					setDefaultTitle();
				}
			}

			switchWithStatus(true);
		};

		!$defaultTab.hasClass(activeTabClass) && $defaultTab.on('click', wpTabCallback);
		!$editorTab.hasClass(activeTabClass) && $editorTab.on('click', mpceTabCallback);


		if (MPCEAdmin.mpceEnabled) {

			var supportedBrowser = !MPCEBrowser.IE && !MPCEBrowser.Opera;
			var motopressCEButton = $('#motopress-ce-btn');

			if (supportedBrowser) {
				motopressCEButton.removeAttr('disabled');
				motopressCEButton.show();
			} else {
				motopressCEButton.remove();
			}

			if (supportedBrowser) {

				motopressCEButton.on('click', function() {
					preloader.show();
					window.location = MPCEAdmin.editUrl;
				});

				function mpceOnEditorInit() {
					$.when.apply($, [canjsStatus, bootstrapStatus, parentEditorStatus]).done(function() {
						motopressCEButton.removeAttr('disabled');
						if (pluginAutoOpen) {
							motopressCEButton.click();
						}
					});
				}
			}
		}
	});
})(jQuery);