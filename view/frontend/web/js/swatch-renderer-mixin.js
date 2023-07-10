define(
    ['jquery', 'Vct_ChangeSkuDynamically/js/configurable-swatch-renderer'], function ($, configurableSwatchRenderer) {
        'use strict';

        return function (widget) {
    $.widget('mage.SwatchRenderer', widget, {
        _UpdatePrice: function () {
            configurableSwatchRenderer.switcher(this.options.jsonConfig, this.getProduct());

            // Check if special price is available and add SALE label
            // var $product = this.element.parents(this.options.selectorProduct),
            //     $productPrice = $product.find(this.options.selectorProductPrice),
            //     result = this._getNewPrices(),
            //     isShow = typeof result != 'undefined' && result.oldPrice.amount !== result.finalPrice.amount;

            // $productPrice.find('span:first').toggleClass('special-price', isShow);
            // if (isShow && result.oldPrice.amount > result.finalPrice.amount) {
            //   var saleTag = '<span class="product-label-sale">SALE</span>';
            //   $product.find('.product-info-main').append(saleTag);
            // } else {
            //   $product.find('.product-label-sale').remove();
            // }

            this._super();
        },
    });

    return $.mage.SwatchRenderer;
};

    });
    