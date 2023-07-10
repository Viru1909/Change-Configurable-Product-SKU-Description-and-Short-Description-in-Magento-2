define(['jquery'], function ($) {
    'use strict';

    return {
        switcher: function (config, usedProductId) {
            if (config['sku']['enable'] === '1'
                && config['fullActionName'] === 'catalog_product_view'
                && usedProductId !== undefined) {
                // Here using JS we Set all Data to Particular Path / Class Where it will change when onclick Swatch 
                $(config['sku']['selector']).html(config['sku'][usedProductId]);
                $(config['short_description']['selector']).html(config['short_description'][usedProductId]);
                $(config['description']['selector']).html(config['description'][usedProductId]);
                // Extra Script Code Currently Not in Use.
                $(document).ready(function(){
                    var container = $('.swatch-attribute');
                    var firstChild = container.first().children();
                    firstChild.attr('aria-checked', true);
                    var value = firstChild.data('value');
                    $('.swatch-attribute-selected-option').text(value);
                });
            }
        },
    };
});
