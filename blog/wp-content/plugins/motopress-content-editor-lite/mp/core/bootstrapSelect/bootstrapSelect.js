(function ($) {
    MP.BootstrapSelect = can.Construct(
    {
        setSelected: function(option) {
            option.prop('selected', true);
            option.closest('select').next('.bootstrap-select').find('.filter-option').text(option.text());
        },

        setDisabled: function(option, flag) {
            if (typeof flag == 'undefined') flag = true;

            var select = option.closest('select');
            var index = option.prop('index');
            var a = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + index + '"] > a');

            if (flag) {
                a.attr('data-disabled', '');
            } else {
                a.removeAttr('data-disabled');
            }
        },

        appendOption: function(select, option) {

            var li = $('<li />', {
                rel: option.prop('index')
            });
            var a = $('<a />', {
                tabindex: '-1',
                href: '#',
                text: option.text()
            });
            if (typeof option.attr('data-disabled') != 'undefined') a.attr('data-disabled', '');
            a.appendTo(li);

            select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"]').append(li);
        },

        removeOption: function(option) {
            var select = option.closest('select');

            if (select.find('option').length > 1) {
                var index = option.prop('index');
                var li = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="'+ index +'"]');
                li.nextAll('li').each(function() {
                    $(this).attr('rel', parseInt($(this).attr('rel')) - 1);
                });

                li.remove();

                if (option.parent().is('optgroup') && !option.siblings('option').length) {
                    option.parent('optgroup').remove();

                    if (select.children('optgroup').length == 1) {
                        var optIndex = select.find('optgroup:first option:last').prop('index');
                        var optLi = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + optIndex + '"]');

                        optLi.removeClass('optgroup-div');
                    }
                } else {
                    option.remove();
                }

                setSelected(select.find('option:first'));
            }
        },

        updateOptionText: function(option, text) {
            var select = option.closest('select');
            var index = option.prop('index');
            var a = select.next('.bootstrap-select').find('ul[data-select-id="' + select.prop('id') + '"] > li[rel="' + index + '"] > a');

            option.text(text);
            a.text(text);

        }
    },
    {})
})(jQuery);