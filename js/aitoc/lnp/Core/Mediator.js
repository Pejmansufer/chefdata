
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Mediator = Class.create({

    /**
     * @private {Aitoc_Lnp_Core_App}
     */
    _app: null,

    /**
     * @private {boolean}
     */
    _blockControls: false,

    initialize: function( app ) {
        this._app = app;
    },

    _checkData: function( caller, params ) {
        var data = this._app.getData(),
            result = true;
        if ( !data.isLoaded() ) {
            result = false;
            data.onLoad( function(){
                this[caller].apply( this, params );
            }.bind(this));
            this._app.getComponent('List').blockControls();
            this._app.getComponent('Layered').blockControls();
            this._blockControls = true;
        } else if ( this._blockControls ) {
            this._app.getComponent('List').releaseControls();
            this._app.getComponent('Layered').releaseControls();
            this._blockControls = false;
        }
        return result;
    },

    filterUpdated: function( id, value, update ) {
        if ( !update || this._checkData( 'filterUpdated', [id, value, update] ) ) {
            var list = this._app.getComponent('List'),
                layered = this._app.getComponent('Layered'),
                collection = this._app.getData().getCollection();

            collection.setFilter( id, value )
                      .setPage( 1 );

            var category_id = this._app.getConfig().settings.category_id;
            this.updateCookie('filters_' + category_id, collection.getFilters(), true);
            this.updateCookie('toolbar_' + category_id, {page: 1});

            if ( update ) {
                collection.load();

                list.updateItems( collection.getItems() )
                    .updatePager( collection.getAllItemsCount() )
                    .render();

                collection.updateFilters( layered.getFilters() );
                layered.renderFilters();
            }
        }
    },

    limitChanged: function ( limit, update ) {
        if ( !update || this._checkData( 'limitChanged', [limit, update] ) ) {
            var list = this._app.getComponent('List'),
                collection = this._app.getData().getCollection();

            collection.setLimit( limit )
                      .setPage( 1 );

            this.updateCookie('limit', limit);
            var category_id = this._app.getConfig().settings.category_id;
            this.updateCookie('toolbar_' + category_id, {page: 1});

            if ( update ) {
                collection.load();

                list.updateItemsQty()
                    .updateItems( collection.getItems() )
                    .updatePager( collection.getAllItemsCount() )
                    .render();
            }
        }
    },

    orderChanged: function ( order, update ) {
        if ( !update || this._checkData( 'orderChanged', [order, update] ) ) {
            var list = this._app.getComponent('List'),
                collection = this._app.getData().getCollection();

            collection.setOrder( order )
                      .setPage( 1 );


            var category_id = this._app.getConfig().settings.category_id;
            this.updateCookie('toolbar_' + category_id, {page: 1});
            this.updateCookie('toolbar_' + category_id, {order: order});

            if ( update ) {
                collection.load();

                list.updateItems( collection.getItems() )
                    .updatePager( collection.getAllItemsCount() )
                    .render();
            }
        }
    },

    orderDirChanged: function ( orderDir, update ) {
        if ( !update || this._checkData( 'orderDirChanged', [orderDir, update] ) ) {
            var list = this._app.getComponent('List'),
            collection = this._app.getData().getCollection();

            collection.setOrderDir( orderDir )
                      .setPage( 1 );

            var category_id = this._app.getConfig().settings.category_id;
            this.updateCookie('toolbar_' + category_id, {page: 1});
            this.updateCookie('toolbar_' + category_id, {orderDir: orderDir});

            if ( update ) {
                collection.load();

                list.updateItems( collection.getItems() )
                    .updatePager( collection.getAllItemsCount() )
                    .render();
            }
        }
    },

    pageChanged: function ( page, update ) {
        if ( !update ||  this._checkData( 'pageChanged', [page, update] ) ) {
            var list = this._app.getComponent('List'),
                collection = this._app.getData().getCollection();

            collection.setPage( page );

            var category_id = this._app.getConfig().settings.category_id;
            this.updateCookie('toolbar_' + category_id, {page: page});

            if ( update ) {
                collection.load();

                list.updateItems( collection.getItems() )
                    .render();
            }
        }
    },

    reinitList: function() {
        if ( this._checkData( 'reinitList', [] ) ) {
            var list = this._app.getComponent('List'),
                collection = this._app.getData().getCollection().load(true);

            list.updateItems( collection.getItems() )
                .updatePager( collection.getAllItemsCount() )
                .render();
        }
    },

    modeChanged: function( mode, update ) {
        var list = this._app.getComponent('List');
        list.switchMode( mode, update );

        this.updateCookie('mode', mode);

        // collection will be updated by the toolbar automatically
    },

    updateCookie: function( name, value, overwrite) {
        var data = this._app.cookie().getData(name);
        if ((data instanceof Object) && !overwrite) {
            data = Object.extend(data, value);
        } else {
            data = value;
        }
        this._app.cookie().setData( name, data );
    }

});