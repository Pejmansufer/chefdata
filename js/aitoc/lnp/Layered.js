
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered = Class.create( Aitoc_Lnp_Core_Component_Abstract, {

    /**
     * @private {string}
     */
    _name: 'Layered',

    /**
     * @private {Object}
     */
    _filters: null,

    init: function() {
        this._filters = {};
        this.createFilters();
        $$('div.block-layered-nav')[0].insert( {bottom: new Element('div', {'class': 'aitoc_lnp_progress', 'style': 'display:none'} )});
    },

    /**
     * @return {Object}
     */
    getFilters: function() {
        return this._filters;
    },

    createFilters: function () {
        var data = this._config.Layered.filters;

        for ( var name in data ) {
            var filter = data[name],
                type       = filter.type       || Aitoc_Lnp.FILTER_DEFAULT_TYPE,
                renderer   = filter.renderer   || Aitoc_Lnp.FILTER_DEFAULT_RENDERER,
                multiple   = filter.multiple   || false,
                id         = filter.id         || 0;
                value      = filter.value      || [];

            this._filters[name] = new window['Aitoc_Lnp_Layered_Filter_' + Aitoc_Lnp.ucFirst(type)]( filter.label );
            this._filters[name].setMediator( this.getMediator() )
                               .setOptions( filter.options )
                               .setName( name )
                               .setMultipleValues( multiple )
                               .setConfig( this._config )
                               .setShowZeroResults( !filter.with_results )
                               .setId( id )
                               .updateValue( value, false );

            var rendererObj = new window['Aitoc_Lnp_Layered_Renderer_' + Aitoc_Lnp.ucFirst(renderer)]();
                rendererObj.setFilter( this._filters[name] )
                           .setConfig( this._config );

            this._filters[name].setRenderer( rendererObj );

            this.renderFilters();
        }
    },

    renderFilters: function() {
        var filtersHtml = '';
        for ( var name in this._filters ) {
            filtersHtml += this._filters[name].render();
        }

        $('narrow-by-list').update( filtersHtml );

        this._initObservers();

        decorateDataList('narrow-by-list');
    },

    _initObservers: function() {
        for ( var name in this._filters ) {
            this._filters[name].initObservers();
        }
    },

    /**
     * @param {string} name
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    getFilter: function( name ) {
        return this._filters[name];
    },

    blockControls: function() {
        $$('div.block-layered-nav .aitoc_lnp_progress')[0].show();
    },

    releaseControls: function() {
        $$('div.block-layered-nav .aitoc_lnp_progress')[0].hide();
    }

});