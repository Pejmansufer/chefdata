
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Renderer_List = Class.create( Aitoc_Lnp_List_Renderer_Abstract, {

    initialize: function() {
        this._templates = {};
        this.addTemplate( 'default', {
            template:
                '<ol id="products-list" class="products-list">' +
                    '{{items}}' +
                '</ol>'
        });

        this.addTemplate( 'ultimo', {
            template:
                '<ul id="products-list" class="products-list{{classes}}">' +
                    '{{items}}' +
                '</ul>'
        });

        this.addTemplate( 'rwd', {
            template:
                '<ol id="products-list" class="products-list">' +
                    '{{items}}' +
                    '</ol>'
        });
    },

    render: function() {
        var items    = this._list.getItems(),
            products = this._list.getProductsQty(),
            columns  = this._config.List.grid_columns,
            theme    = this._config.settings.theme;

        if ( !products ) {
            return '<div class="aitoc_lnp_no_products">' + this._config.List.text_no_products + '</div>';
        }

        if ( this._templates[theme] === undefined ) {
            throw new Error( 'Incorrect theme request' );
        }

        var template  = this._templates[theme].template,
            itemsHtml = '';

        for ( var i = 0; i < Math.min(products, items.length) ; i++ ) {
            itemsHtml += items[i].render();
        }

        var itemsTemplate = new Template( itemsHtml, Aitoc_Lnp.TEMPLATE_SYNTAX );
        itemsHtml = itemsTemplate.evaluate( this._config.Data.parseInto );

        var templateObj = new Template( template, Aitoc_Lnp.TEMPLATE_SYNTAX );

        return templateObj.evaluate({
            items: itemsHtml,
            classes: this._config.List.list_classes
        });
    },

    decorate: function() {
        decorateList('products-list', 'none-recursive');
    }
});