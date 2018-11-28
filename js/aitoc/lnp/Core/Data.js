
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Data = Class.create({

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {Aitoc_Lnp_Data_Fetcher}
     */
    _fetcher: null,

    /**
     * @private {Aitoc_Lnp_Core_Mediator}
     */
    _mediator: null,

    /**
     * @private {Aitoc_Lnp_Data_Collection}
     */
    _collection: null,

    /**
     * @private {boolean}
     */
    _loaded: false,

    /**
     * @private {Object}
     */
    _promise: null,

    /**
     * @param {Object} config
     * @param {Aitoc_Lnp_Core_Mediator} mediator
     */
    initialize: function( config, mediator ) {
        this._config = config;
        this._mediator = mediator;

        this._fetcher = new Aitoc_Lnp_Core_Data_Fetcher( this._config.Data.fetchUrl );
        this._collection = new Aitoc_Lnp_Core_Data_Collection( this._config );
        this._promise = promise();
    },

    init: function() {

        this._fetcher.request( this._config.settings.category_id, this._processData.bind(this) );
    },

    /**
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    getCollection: function() {
        return this._collection;
    },

    _processData: function( data ) {
        data = data.evalJSON();
        this._collection.setFields( data.fields );
        this._collection.setData( data.data );
        this._collection.setIndex( data.attributes );
        this._collection.setCategoriesIndex( data.categories );
        this._loaded = true;

        if ( this._config.settings.debug_enabled ) {
            console.log('Category data loaded:', data );
        }

        this._promise.fulfill();
    },

    /**
     * @returns {boolean}
     */
    isLoaded: function() {
        return this._loaded;
    },

    onLoad: function( onComplete, onFail ) {
        this._promise.then( onComplete, onFail );
    }
});