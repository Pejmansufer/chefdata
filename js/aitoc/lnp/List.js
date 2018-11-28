
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List = Class.create( Aitoc_Lnp_Core_Component_Abstract, {

    /**
     * @private {string}
     */
    _name: 'List',

    /**
     * @private {Aitoc_Lnp_List_Toolbar}
     */
    _toolbar: null,

    /**
     * @private {Array.<Aitoc_Lnp_List_Item>}
     */
    _items: null,

    /**
     * @private {Aitoc_Lnp_List_Renderer_Abstract}
     */
    _renderer: null,

    /**
     * @private {number}
     */
    _productsQty: 0,

    initialize: function() {
        this._items = [];
        this._toolbar = new Aitoc_Lnp_List_Toolbar();
    },

    init: function() {
        this._toolbar.setMediator( this.getMediator() )
                     .setConfig( this._config )
                     .init();

        var mode = this._toolbar.getMode();
        this._renderer = new window['Aitoc_Lnp_List_Renderer_' + Aitoc_Lnp.ucFirst( mode )];
        this._renderer.setList( this )
                      .setConfig( this._config );

        this.updateItemsQty()
            .initFirstItems();

    },

    /**
     * @return {Array.<Aitoc_Lnp_List_Item>}
     */
    getItems: function() {
        return this._items;
    },

    /**
     * @param {Array.<Aitoc_Lnp_Core_Data_Collection_Item>} items
     * @return {Aitoc_Lnp_List}
     */
    updateItems: function( items ) {
        this._productsQty = items.length;
        var mode = this._toolbar.getMode();
        var qty = this._toolbar.getLimit();
        var counter = qty ? qty : this._productsQty;
        if ( !qty ) {
            this._items = [];
        }
        for (var i = 0; i < counter; i++) {
            var newData = items[i] ? items[i].getData( 'data_' + mode ) : '';
            if( qty ) {
                this._items[i].update( newData );
            } else {
                var item = new Aitoc_Lnp_List_Item();
                item.bindDomElement();
                item.update( newData );
                this._items.push( item );
            }
        }
        return this;
    },

    /**
     * @return {number}
     */
    getProductsQty: function() {
        return this._productsQty;
    },

    /**
     * @return {Aitoc_Lnp_List}
     */
    updateItemsQty: function() {
        var qty = this._toolbar.getLimit();

        if ( qty == 0 ) {
            this._items = [];
        } else {
            var curQty = this._items.length;

            if ( qty != curQty ) {
                var delta = qty - curQty;
                if ( delta < 0 ) {
                    this._items.splice( qty, -delta );
                } else {
                    for ( var i = 0; i < delta; i++ ) {
                        var item = new Aitoc_Lnp_List_Item();
                        item.bindDomElement();
                        this._items.push( item );
                    }
                }
            }
        }
        return this;
    },

    initFirstItems: function() {
        var pageItems = $$('.col-main .category-products li.item');
        this._productsQty = pageItems.length;
        for( var i = 0; i < this._items.length; i++ ) {
            this._items[i].bindDomElement( pageItems[i] );
        }
    },

    render: function() {
        $$('.col-main .products-list, .col-main .products-grid, .aitoc_lnp_no_products').invoke('remove');

        var colMain  = $$('.col-main')[0],
            products = $$('.col-main div.category-products')[0],
            toolbar  = $$('.col-main div.category-products div.toolbar')[0];

        if ( toolbar ) {
            toolbar.insert({ after: this._renderer.render() });
            this._renderer.decorate();
        } else if( products ) {
            products.insert({ top: this._renderer.render() });
            this._renderer.decorate();
        } else {
            var newProducts = new Element('div', {'class': 'category-products'});
            newProducts.insert( this._toolbar.getReservedHtml() );
            newProducts.insert( '<div class="toolbar-bottom">' + this._toolbar.getReservedHtml() + '</div>' );
            colMain.insert(newProducts);
            this._toolbar.init();
            this.getMediator().reinitList();
        }
    },

    blockControls: function() {
        this._toolbar.lock();
    },

    releaseControls: function() {
        this._toolbar.release();
    },

    /**
     * @return {Aitoc_Lnp_List_Toolbar}
     */
    getToolbar: function() {
        return this._toolbar;
    },

    switchMode: function( mode, update ) {
        this._renderer = new window['Aitoc_Lnp_List_Renderer_' + Aitoc_Lnp.ucFirst( mode )];
        this._renderer.setList( this )
            .setConfig( this._config );

        this.getToolbar().getTool('Limiter').switchMode( mode, update );
    },

    updatePager: function( itemsQty, newPage ) {
        this.getToolbar().getTool('Pager').update( itemsQty, newPage );
        return this;
    }
});