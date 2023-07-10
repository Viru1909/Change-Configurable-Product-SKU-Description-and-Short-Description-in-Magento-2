define(
    ['jquery', 'Vct_ChangeSkuDynamically/js/configurable-swatch-renderer'], function ($, configurableSwatchRenderer) {
        'use strict';

        return function (widget) {
            $.widget('mage.configurable', widget, {
                _reloadPrice: function () {
                    configurableSwatchRenderer.switcher(this.options.spConfig, this.simpleProduct);
                    

                    this._super();
                },
            });

            return $.mage.configurable;
        };
    },
);
