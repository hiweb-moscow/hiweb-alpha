/**
 * Created by hiweb on 21.10.2016.
 */
String.prototype.escapeRegExp = function () {
    return this.toString().replace(/[\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
};

var hiweb_field_repeat = {

    selector: '.hiweb-field-repeat',
    selector_source: '[data-row-source]',
    selector_wrap: 'tbody.wrap',
    selector_row: '[data-row]',
    selector_button_add: '[data-action-add]',
    selector_button_clear: '[data-action-clear]',
    selector_button_remove: '[data-action-remove]',
    selector_button_duplicate: '[data-action-duplicate]',

    init: function (root) {
        hiweb_field_repeat.make_sortable(root);
        jQuery(root).find('> table > thead .dropdown, > table > tfoot .dropdown')
            .dropdown({
                action: 'hide'
            });
        hiweb_field_repeat.make_table_names(root);
    },

    init_once: function () {
        jQuery(hiweb_field_repeat.selector).each(function () {
            hiweb_field_repeat.init(this);
        });
        jQuery('body').on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_add, hiweb_field_repeat.click_add).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_remove, hiweb_field_repeat.click_remove).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_duplicate, hiweb_field_repeat.click_duplicate).on('click', hiweb_field_repeat.selector + ' ' + hiweb_field_repeat.selector_button_clear, hiweb_field_repeat.click_clear_full);
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_row_source: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-source] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows_list: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-list]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_rows: function (root) {
        return jQuery(root).find('> table > tbody[data-rows-list] > tr[data-row]');
    },

    /**
     *
     * @param root
     * @returns {*|{}}
     */
    get_cols: function (root) {
        return jQuery(root).find('> table > thead [data-col]');
    },

    /**
     *
     * @param row
     * @returns {*}
     */
    get_cols_by_row: function (row) {
        return jQuery(row).find('> [data-col], > td > [data-col], > td > table > tbody > tr > [data-col]');
    },

    make_sortable: function (root) {
        var rows = hiweb_field_repeat.get_rows_list(root);
        if (typeof rows.sortable == 'undefined') {
            alert('Плагин jquery.ui.sortable.js не подключен!');
            return;
        }
        if (rows['sortable'].hasOwnProperty('destroy')) {
            rows.sortable("destroy");
        }
        rows.sortable({
            update: function (e, ui) {
                hiweb_field_repeat.make_table_names(jQuery(this).closest(hiweb_field_repeat.selector));
                ui.placeholder.find('> [data-col] > *').trigger('drag_update');
            },
            distance: 3,
            handle: '> [data-drag], > [data-drag] button, > [data-drag] i',
            helper: function (e, ui) {
                ui.find('th, td').each(function () {
                    jQuery(this).width(jQuery(this).width());
                });
                ui.find('> [data-col] > *').trigger('dragged');
                return ui;
            },
            revert: true,
            start: function (e, elements) {
                elements.placeholder.height(elements.helper.height());
                elements.helper.find('> [data-col] > *').trigger('drag_start', elements);
            },
            stop: function (e, ui) {
                ui.item.find('[data-col] > *, .flex-column > .hiweb-field-repeat-flex > tbody > tr > [data-col] > *').trigger('drag_stop', ui.placeholder);
            }
        });
        //jQuery(hiweb_field_repeat.selector + ' tbody').disableSelection();
    },

    make_table_names: function (root) {
        root = jQuery(root);
        var base_name = root.attr('name');
        //set rows
        var index = 0;
        //Each rows
        hiweb_field_repeat.get_rows(root).each(function () {
            ////....
            var row = jQuery(this).attr('data-row', index);
            //Each cols
            hiweb_field_repeat.get_cols_by_row(row).each(function () {
                var col_id = jQuery(this).attr('data-col');
                var replace_name = base_name + '[' + index + '][' + col_id + ']';
                //Each inputs
                jQuery(this).find('[name]').each(function () {
                    var input = jQuery(this);
                    var pattern = '^' + base_name.escapeRegExp() + '\\\[(\\\s{1}|\\\d+)\\\]\\\[' + col_id + '\\\]';
                    var newName = input.attr('name').replace(new RegExp(pattern, 'i'), replace_name);
                    input.attr('name', newName);
                });
            });
            index++;
        });
        root.find('> table > tbody[data-rows-message] [data-row-empty]').attr('data-row-empty', index > 0 ? '1' : '0');
        hiweb_field_repeat.make_sortable();
    },


    get_global_id: function (e) {
        return jQuery(e).is('[data-global-id]') ? jQuery(e).attr('data-global-id') : jQuery(e).closest('[data-global-id]').attr('data-global-id');
    },

    get_name_id: function (e) {
        return jQuery(e).is('[data-input-name]') ? jQuery(e).attr('name') : jQuery(e).closest('[data-input-name]').attr('name');
    },

    click_add: function (e) {
        e.preventDefault();
        if (jQuery(this).is('[data-action-add="flex-dropdown"]')) {
            var dropdown = jQuery(this).closest('[data-ctrl]').find('[data-sub-flex-dropdown]');
            var timeout = null;
            dropdown.fadeIn().off('mouseover mouseout').on('mouseout', function () {
                timeout = setTimeout(function () {
                    dropdown.fadeOut();
                }, 1000);
            }).on('mouseover', function () {
                clearTimeout(timeout);
            });
        } else {
            var prepend = jQuery(this).is('[data-action-add="1"]');
            var root = jQuery(this).closest(hiweb_field_repeat.selector);
            var flex_row_id = jQuery(this).is('[data-flex-id]') ? jQuery(this).attr('data-flex-id') : '';
            if (flex_row_id !== '') {
                jQuery(this).closest('[data-sub-flex-dropdown]').fadeOut();
            }
            hiweb_field_repeat.add_rows(root, prepend, 1, '', flex_row_id);
        }
    },

    add_rows: function (root, prepend, rows_count, callback, flex_row_id) {
        if (typeof rows_count === 'undefined') rows_count = 1;
        if (typeof prepend === 'undefined') prepend = false;
        if (typeof flex_row_id === 'undefined') flex_row_id = '';
        ///
        var row_list = hiweb_field_repeat.get_rows_list(root);
        var index = prepend ? 0 : hiweb_field_repeat.get_rows(root).length;
        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {
                id: hiweb_field_repeat.get_global_id(root),
                method: 'ajax_html_row',
                input_name: hiweb_field_repeat.get_name_id(root),
                index: index,
                flex_row_id: flex_row_id
            },
            dataType: 'json',
            success: function (data) {

                if (data.hasOwnProperty('result') && data.result === true) {
                    var newLine = jQuery(data.data).hide().fadeIn();
                    for (n = 0; n < rows_count; n++) {
                        if (prepend) {
                            row_list.prepend(newLine);
                        } else {
                            row_list.append(newLine);
                        }
                        newLine.find('> td')
                            .wrapInner('<div style="display: none;" />')
                            .parent()
                            .find('> td > div')
                            .slideDown(400, function () {
                                var $set = jQuery(this);
                                $set.replaceWith($set.contents());
                                newLine.find('[data-col] > *, .flex-column > .hiweb-field-repeat-flex > tbody > tr > [data-col] > *').trigger('init_3');
                            });
                        hiweb_field_repeat.make_table_names(root);
                        newLine.find('[data-col] > *, .flex-column > .hiweb-field-repeat-flex > tbody > tr > [data-col] > *').trigger('init');
                        if (typeof callback === 'function') {
                            callback(root, newLine);
                        }
                    }
                } else {
                    console.warn(data);
                }
            },
            error: function (data) {
                console.warn(data);
            }
        });
    },

    objectifyForm: function (formArray) {//serialize data function

        var returnArray = {};
        for (var i = 0; i < formArray.length; i++) {
            returnArray[formArray[i]['name']] = formArray[i]['value'];
        }
        return returnArray;
    },

    //TODO!
    click_duplicate: function (e) {
        e.preventDefault();
        var root = jQuery(this).closest(hiweb_field_repeat.selector);
        var row_list = hiweb_field_repeat.get_rows_list(root);
        var currentRow = jQuery(this).closest(hiweb_field_repeat.selector_row);
        var values = {};
        hiweb_field_repeat.get_cols(root).each(function () {
            var col_id = jQuery(this).attr('data-col');
            values[col_id] = hiweb_field_repeat.objectifyForm(currentRow.find('> td[data-col="' + col_id + '"] [name]').serializeArray());
        });

        jQuery.ajax({
            url: ajaxurl + '?action=hiweb-field-repeat-get-row',
            type: 'post',
            data: {
                id: hiweb_field_repeat.get_global_id(this),
                method: 'ajax_html_row',
                input_name: hiweb_field_repeat.get_name_id(this),
                value: {}
            }, //todo: value
            dataType: 'json',
            success: function (data) {
                if (data.hasOwnProperty('result') && data.result === true) {
                    var newLine = jQuery(data.data).hide().fadeIn();
                    row_list.append(newLine);
                    newLine.find('[data-col] > *').trigger('init');
                    newLine.css('opacity', 0).animate({opacity: 1}).find('> td')
                        .wrapInner('<div style="display: none;" />')
                        .parent()
                        .find('> td > div')
                        .slideDown(700, function () {
                            var $set = jQuery(this);
                            $set.replaceWith($set.contents());
                        });
                    hiweb_field_repeat.make_table_names(root);
                } else {
                    console.warn(data);
                }
            },
            error: function (data) {
                console.warn(data);
            }
        });
        /*
        var row = jQuery(this).closest(hiweb_field_repeat.selector_row);
        var newRow = row.clone(false).insertAfter(row);
        newRow.find('[data-col] > *').trigger('init');
        newRow.css('opacity', 0).animate({opacity: 1}).find('> td')
            .wrapInner('<div style="display: none;" />')
            .parent()
            .find('> td > div')
            .slideDown(700, function () {
                var $set = jQuery(this);
                $set.replaceWith($set.contents());

            });
        hiweb_field_repeat.make_table_names(jQuery(this).closest(hiweb_field_repeat.selector));
         newRow.find('[data-col] > *').trigger('init_2');*/
    },

    click_remove: function (e) {
        e.preventDefault();
        var row = jQuery(this).closest(hiweb_field_repeat.selector_row);
        hiweb_field_repeat.do_remove_row(row);
    },

    do_remove_row: function (row) {
        return jQuery(row).find('> td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('> td > div')
            .slideUp(700, function () {
                var root = row.closest(hiweb_field_repeat.selector);
                jQuery(this).parent().parent().remove();
                row.remove();
                hiweb_field_repeat.make_table_names(root);
                if (hiweb_field_repeat.get_rows(root).length === 0) {
                    jQuery(root).find('.message').fadeIn();
                }
            });
    },

    click_clear_full: function (e) {
        e.preventDefault();
        if (confirm('Remove all table rows?')) {
            var root = jQuery(this).closest(hiweb_field_repeat.selector);
            hiweb_field_repeat.get_rows(root).each(function () {
                hiweb_field_repeat.do_remove_row(this)
            });
        }
    }

};

jQuery(document).ready(hiweb_field_repeat.init_once);
jQuery('body').on('init_3', '.hiweb-field-repeat', function(){
    hiweb_field_repeat.init(this)
});