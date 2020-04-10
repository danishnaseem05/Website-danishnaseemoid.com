(function ($) {
    "use strict";

    var CEGutenbergSwitchMode = {
        $gutenberg: null,
        $switchModeButton: null,
        $goToEditorLink: null,
        $goToEditorButton: null,

        isCEMode: false,
        hasPlaceholder: false,

        init: function () {
            this.isCEMode = MPCEAdmin.mpceEnabled == '1' || MPCEAdmin.mpceEnabled === true;

            this.$gutenberg = $('#editor');

            var $modeSwitcher = $($('#mpce-switch-mode-html').html());
            this.$gutenberg.find('.edit-post-header-toolbar').append($modeSwitcher);

            this.$switchModeButton = $modeSwitcher.find('#mpce-switch-mode-button');

            this.toggleStatus();
            this.buildPlaceholder();
            this.bindSwitch();

            wp.data.subscribe(function () {
                setTimeout(function () {
                    CEGutenbergSwitchMode.buildPlaceholder();
                }, 1);
            });
        },

        buildPlaceholder: function () {
            if (this.hasPlaceholder) {
                return;
            }

            var $editorPlaceholder = $($('#mpce-editor-placeholder-html').html());
            this.$gutenberg.find('.editor-block-list__layout, .editor-post-text-editor').after($editorPlaceholder);

            this.hasPlaceholder = true;

            this.$goToEditorLink = $editorPlaceholder.find('#mpce-go-to-editor-link');
            this.$goToEditorButton = $editorPlaceholder.find('#mpce-go-to-editor-button');

            this.$goToEditorLink.on('click', function (event) {
                event.preventDefault();

                wp.data.dispatch('core/editor').savePost();
                CEGutenbergSwitchMode.redirectAfterSave();
            });
        },

        bindSwitch: function () {
            var self = this;

            this.$switchModeButton.on('click', function () {
                var wpEditor = wp.data.dispatch('core/editor');

                if (self.isCEMode) {
                    wpEditor.editPost({mpce_gutenberg_mode: false});
                } else {
                    var postTitle = wp.data.select('core/editor').getEditedPostAttribute('title');

                    if (!postTitle) {
                        wpEditor.editPost({title: MPCEAdmin.noTitleLabel});
                    }

                    wpEditor.editPost({mpce_gutenberg_mode: true});
                }

                self.isCEMode = !self.isCEMode;
                self.toggleStatus();

                if (self.isCEMode) {
                    self.$goToEditorLink.trigger('click');
                } else {
                    wpEditor.savePost();
                }
            });
        },

        redirectAfterSave: function () {
            this.$switchModeButton.prop('disabled', true);
            this.$goToEditorLink.prop('disabled', true);
            this.$goToEditorButton.addClass('disabled');

            setTimeout(function () {
                if (wp.data.select('core/editor').isSavingPost()) {
                    CEGutenbergSwitchMode.redirectAfterSave();
                } else {
                    window.location.href = MPCEAdmin.editUrl;
                }
            }, 300);
        },

        toggleStatus: function () {
            $('body').toggleClass('mpce-mode-active', this.isCEMode)
                .toggleClass('mpce-mode-inactive', !this.isCEMode);

            this.$switchModeButton.toggleClass('button-secondary', this.isCEMode)
                .toggleClass('button-primary', !this.isCEMode);
        }
    };

    $(function () {
        setTimeout( function () {
            CEGutenbergSwitchMode.init();
        }, 1);
    });
})(jQuery);
