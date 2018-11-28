
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    /**
     * @private {Object.<Aitoc_Lnp_List_Toolbar_Abstract>}
     */
    _tools: null,

    /**
     * @private {Array.<string>}
     */
    _toInit: 'Mode,Limiter,Sorter,Direction,Pager'.split(','), // `Mode` should be the first as it is used by other tools

    initialize: function() {
        this._tools = {};
        for (var i = 0; i < this._toInit.length; i++) {
            var tool = new window['Aitoc_Lnp_List_Toolbar_' + this._toInit[i]]();
            tool.setToolbar( this );
            this._tools[this._toInit[i]] = tool;

        }
        this._elements = $$('.col-main div.toolbar');
    },

    init: function() {
        for (var toolName in this._tools) {
            this._tools[toolName].setMediator( this.getMediator() );
            this._tools[toolName].setConfig( this._config );
        }

        for (var toolName in this._tools) {
            this._tools[toolName].bindElements();
        }

        for (var toolName in this._tools) {
            this._tools[toolName].init();
        }

        if ( this._elements.length ) {
            this.setActive( true );

            this._elements.each( function(elem) {
                elem.insert( new Element('div', {'class': 'aitoc_lnp_progress', 'style': 'display:none'}) );
            });
        }
    },

    /**
     * @param {string} tool
     * @return {Object.<Aitoc_Lnp_List_Toolbar_Abstract>}
     */
    getTool: function( tool ) {
        return this._tools[tool];
    },

    getMode: function() {
        return this.getTool('Mode').getValue();
    },

    getLimit: function() {
        return parseInt(this.getTool('Limiter').getValue());
    },

    getPage: function() {
        return parseInt(this.getTool('Limiter').getValue());
    },

    lock: function() {
        $$('.col-main div.toolbar .aitoc_lnp_progress').each( function(elem) {
            elem.show();
        });
    },

    release: function() {
        $$('.col-main div.toolbar .aitoc_lnp_progress').each( function(elem) {
            elem.hide();
        });
    },

    getReservedHtml: function() {
        return this._config.List.Toolbar.html;
    }
});