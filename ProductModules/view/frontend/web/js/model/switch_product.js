define([
    'jquery',
    'mage/utils/wrapper',
    'mage/translate'
], function ($, wrapper, $t) {
    'use strict';

    return function (targetModule) {
        var reloadPrice = targetModule.prototype._reloadPrice;
        targetModule.prototype._reloadPrice = wrapper.wrap(reloadPrice, function (original) {
            var result = original(),
                colorCountAttributeId = $("#attribute" + window.colorCountAttributeId + " option:selected"),
                moduleAttributeId = $("#attribute" + window.moduleAttributeId + " option:selected"),
                mounting_products = this.options.spConfig.mounting_products[this.simpleProduct],
                mounting_products_by_module = this.options.spConfig.mounting_products_by_module[moduleAttributeId.val()],
                options,
                optionsToHtml = "";
            $('.product-options-wrapper .fieldset .custom').remove();
            if (moduleAttributeId.val() != '' && colorCountAttributeId.val() != '') {
                $('.mounting_products').find('option').remove().end().append('<option value="">Choose an Option...</option>').val('');
                if (mounting_products != '' && mounting_products) {
                    $.each(mounting_products, function (key, value) {
                        $('.mounting_products').append($("<option></option>").attr("value", key).text(value));
                    });
                }
                if (colorCountAttributeId.val() == 1) {
                    options = JSON.parse(window.singleColor);
                } else if (colorCountAttributeId.val() == 2) {
                    options = JSON.parse(window.dualColor);
                } else if (colorCountAttributeId.val() == 3) {
                    options = JSON.parse(window.triColor);
                }
                $.each(options, function (key, option) {
                    optionsToHtml += "<option value='" + option.value + "'>" + option.label + "</option>\n";
                });

                for (var color = 1; color <= moduleAttributeId.val(); color++) {
                    var labelColor = $t('Module %1 Colors').replace('%1', color);
                    $('.product-options-wrapper .fieldset').append("<div class=\"field configurable required custom\">\n" +
                        "    <label class=\"label\"\n" +
                        "        <span>" + labelColor + "</span>\n" +
                        "    </label>\n" +
                        "    <div class=\"control\" style=\"width: 80%\">\n" +
                        "        <select " + "id=\"" + window.colorCountAttributeId + "module_" + color + "_color\" name=\"" + window.colorCountAttributeId + "[module_" + color + "_color]\">\n" +
                        "            <option value=\"\">" + $t('Choose an Option...') + "</option>\n" + optionsToHtml +
                        "        </select>\n" +
                        "    </div>\n" +
                        "</div>")
                }
                if (window.selectedOptions) {
                    $.each(JSON.parse(window.selectedOptions), function (key, option) {
                        $("#" + window.colorCountAttributeId + option.label_default).val(option.value_number);
                    });
                }
            } else if (moduleAttributeId.val() != '' && colorCountAttributeId.val() == '') {
                $('.mounting_products').find('option').remove().end().append('<option value="">Choose an Option...</option>').val('');
                if (mounting_products_by_module != '' && mounting_products_by_module) {
                    $.each(mounting_products_by_module, function (key, value) {
                        $('.mounting_products').append($("<option></option>").attr("value", key).text(value));
                    });
                }
            }
            else {
                $('.mounting_products').find('option').remove().end().append('<option value="">Choose an Option...</option>').val('');
            }
            return result;
        });
        return targetModule;
    };
});
