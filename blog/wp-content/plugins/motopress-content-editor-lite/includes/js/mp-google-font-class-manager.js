jQuery(document).ready(function(){
    var motopressGoogleFonts = jQuery.parseJSON(motopressGoogleFontsJSON);
    var wrapper = jQuery('#motopress-google-font-class-manager');
    var motopressGoogleFontsTools = wrapper.find('#motopress-google-font-class-manager-tools');
    wrapper.on('change', '.mp-google-font-class-entry .mp-google-fonts-list', function(e){
        var container = jQuery(this).closest('.mp-google-font-class-entry');
        var fontClass = container.find('.mp-google-font-class-name').text();
        var id = jQuery(this).val().toLowerCase().split(' ').join('_');
        container.find('.mp-google-font-variants').replaceWith(generateVariantsList(fontClass, id));
        container.find('.mp-google-font-subsets').replaceWith(generateSubsetsList(fontClass, id));
    });
    wrapper.on('click', '.mp-google-font-class-entry .mp-remove-google-font-class-entry', function(e){
        e.preventDefault();
        e.stopPropagation();
        var container = jQuery(this).closest('.mp-google-font-class-entry');
        container.remove();
    });
    motopressGoogleFontsTools.find('.mp-create-google-font-class-entry').on('click', createGoogleFontClassEntry);
    motopressGoogleFontsTools.find('.class-name').on('keypress', function(e){
        if (e.which === 13) {
            createGoogleFontClassEntry(e);
        }
    });

    function createGoogleFontClassEntry(e){
        e.preventDefault();
        e.stopPropagation();
        motopressGoogleFontsTools.find('.font-name-info .duplicate-class-name, .font-name-info .wrong-class-name').addClass('hidden');
        var fontClass = motopressGoogleFontsTools.find('.class-name').val().toLowerCase();
        var fontClassPattern = new RegExp("^[a-z0-9][-_a-z0-9]*$");
        if (fontClassPattern.test(fontClass)) {
            if (!wrapper.find('[name="motopress_google_font_classes[' + fontClass + '][family]"]').length) {
                var googleFontClassContainer = jQuery('<div />', {
                    'class' : 'mp-google-font-class-entry'
                });
                var googleFontRemoveBtn = motopressGoogleFontsTools.find('.mp-remove-google-font-class-entry').clone();
                var googleFontClassNameContainer = jQuery('<div />', {
                    'class' : 'mp-google-font-class-name-container'
                });
                var googleFontClassName = jQuery('<span />', {
                    'class' : 'mp-google-font-class-name',
                    'text' : fontClass
                });
                var googleFontDetails = jQuery('<div />', {
                    'class' : 'mp-google-font-details'
                });
                var googleFontSelectContainer = motopressGoogleFontsTools.find('.mp-google-fonts-list-container').clone();
                var id = googleFontSelectContainer.find('.mp-google-fonts-list').attr('name', 'motopress_google_font_classes[' + fontClass + '][family]').val().toLowerCase().split(' ').join('_');
                googleFontClassNameContainer.append(googleFontClassName,googleFontRemoveBtn);
                googleFontDetails.append(googleFontSelectContainer, generateVariantsList(fontClass, id), generateSubsetsList(fontClass, id));
                googleFontClassContainer.append( googleFontClassNameContainer, googleFontDetails);
                motopressGoogleFontsTools.before(googleFontClassContainer);
                motopressGoogleFontsTools.find('.class-name').val('');
            } else {
                motopressGoogleFontsTools.find('.font-name-info .duplicate-class-name').removeClass('hidden');
            }
        } else {
            motopressGoogleFontsTools.find('.font-name-info .wrong-class-name').removeClass('hidden');
        }
    }

    function generateVariantsList(fontClass, fontId){
        var variants = motopressGoogleFonts[fontId]['variants'];
        var variantsHTML = motopressGoogleFontsTools.find('.mp-google-font-variants').clone();
        jQuery.each(variants, function(index, variant){
            var label = jQuery('<label />', {
                'text' : variant
            });
            var input = jQuery('<input />', {
                'type' : 'checkbox',
                'name' : 'motopress_google_font_classes['+ fontClass +'][variants][]',
                'value' : variant
            });
            label.prepend(input)
            variantsHTML.append(label);
        });
        return variantsHTML;
    }

    function generateSubsetsList(fontClass, fontId){
        var subsets = motopressGoogleFonts[fontId]['subsets'];
        var subsetsHTML = motopressGoogleFontsTools.find('.mp-google-font-subsets').clone();
        jQuery.each(subsets, function(index, subset){
            var label = jQuery('<label/>', {
                'text' : subset
            });
            var input = jQuery('<input />', {
                'type' : 'checkbox',
                'name' : 'motopress_google_font_classes['+ fontClass +'][subsets][]',
                'value' : subset
            });
            label.prepend(input);
            subsetsHTML.append(label);
        });
        return subsetsHTML;
    }
});