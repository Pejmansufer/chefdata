
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Filter_Abstract = Class.create({

    /**
     * @private {Aitoc_Lnp_Layered_Renderer_Abstract}
     */
    _renderer: null,

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {Aitoc_Lnp_Core_Mediator}
     */
    _mediator: null,

    /**
     * @private {Array.<number>}
     */
    _value: null,

    /**
     * @private {boolean}
     */
    _multipleValues: false,

    /**
     * @private {string}
     */
    _name: '',

    /**
     * @private {number}
     */
    _id: 0,

    /**
     * @private {string}
     */
    _title: '',

    /**
     * Array of option object in the following format:
     * { value: {*}, label: {string}, number: {number} }
     *
     * @private {Array.<Object>}
     */
    _options: null,

    /**
     * @private {boolean}
     */
    _showZeroResults: false,

    /**
     * @param {string} title
     */
    initialize: function( title ) {
        this._value = [];
        this._title = title;
    },

    /**
     * @param {Object} config
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setConfig: function( config ) {
        this._config = config;
        return this;
    },

    /**
     * @param {string} name
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setName: function( name )
    {
        this._name = name;
        return this;
    },

    /**
     * @return {string}
     */
    getName: function() {
        return this._name;
    },

    /**
     * @return {string}
     */
    getTitle: function() {
        return this._title;
    },

    /**
     * @param {boolean} value
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setMultipleValues: function( value ) {
        this._multipleValues = value;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_Layered_Renderer_Abstract} renderer
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setRenderer: function( renderer ) {
        this._renderer = renderer;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_Core_Mediator} mediator
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setMediator: function( mediator ) {
        this._mediator = mediator;
        return this;
    },

    /**
     * @return {Aitoc_Lnp_Core_Mediator}
     */
    getMediator: function() {
        return this._mediator;
    },

    /**
     * @param {number} id
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setId: function( id ) {
        this._id = id;
        return this;
    },

    /**
     * @return {number}
     */
    getId: function() {
        return this._id;
    },

    /**
     * @return {Array.<number>}
     */
    getValue: function() {
        return this._value;
    },

    /**
     * @return {string}
     */
    getType: function() {
        return this._type;
    },

    /**
     * @param {number} value
     * @param {boolean} update
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    updateValue: function( value, update ) {
        update = update || false;
        if ( value instanceof Array ) {
            this._value = value;
        } else {
            var index = this._value.indexOf( value );
            if ( index === -1 ) {
                if ( !this._multipleValues && this._value.length ) {
                    this._value = [];
                }
                this._value.push( value );
            } else {
                this._value.splice( index, 1 );
            }
        }
        this.getMediator().filterUpdated( this._id, this._value, update );
        return this;
    },

    /**
     * @return {Array.<Object>}
     */
    getOptions: function() {
        return this._options;
    },

    /**
     * @param {Array.<Object>} options
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setOptions: function( options ) {
        this._options = options;
        return this;
    },

    render: function() {
        return this._renderer.render();
    },

    initObservers: function() {
        this._renderer.initObservers();
    },

    clear: function() {
        this.updateValue( [], true );
    },

    resetOptionsCounts: function() {
        for ( var i = 0; i < this._options.length; i++ ) {
            this._options[i][2] = 0;
        }
    },

    /**
     * @param {Array} matchingOptions
     */
    incrementOptionsCounts: function( matchingOptions ) {
        for ( var i = 0; i < this._options.length; i++ ) {
            var option = this._options[i];
            if ( matchingOptions.indexOf( option[0] ) != -1 ) {
                option[2]++;
            }
        }
    },

    /**
     * @param {boolean} value
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    setShowZeroResults: function( value ) {
        value = value || false;
        this._showZeroResults = value;
        return this;
    },

    /**
     * @return {boolean}
     */
    canShowZeroResults: function() {
        return this._showZeroResults;
    }
});