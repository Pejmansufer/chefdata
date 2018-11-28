
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Item = Class.create({

    /**
     * @private {Element}
     */
    _li: null,

    /**
     * @private {string}
     */
    _productCard: '',

    /**
     * @param {Element} li
     */
    bindDomElement: function( li ) {
        this._li = li || new Element('li');
        this._productCard = this._li.innerHTML;
    },

    /**
     * @param {string} productCard
     */
    update: function( productCard ) {
        this._productCard = productCard;
    },

    remove: function() {
        this._li.remove();
    },

    /**
     * @return {string}
     */
    render: function() {
        var html = '';
        if ( this._productCard ) {
            this._li.className = 'item';
            this._li.update( this._productCard );
            html = this._li.outerHTML;
        }
        return html;
    }

});