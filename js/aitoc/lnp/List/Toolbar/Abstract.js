
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Abstract = Class.create({

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {boolean}
     */
    _active: false,

    /**
     * @private {Array.<Element>}
     */
    _elements: null,

    /**
     * @private {*}
     */
    _value: null,

    /**
     * @private {Aitoc_Lnp_Core_Mediator}
     */
    _mediator: null,

    /**
     * @private {Aitoc_Lnp_List_Toolbar}
     */
    _toolbar: null,

    bindElements: function(){},

    init: function() {},

    /**
     * @return {boolean}
     */
    isActive: function() {
        return this._active;
    },

    /**
     * @param {boolean} value
     * @return {Aitoc_Lnp_List_Toolbar_Abstract}
     */
    setActive: function( value ) {
        this._active = value;
        return this;
    },

    /**
     * @return {*}
     */
    getValue: function() {
        return this._value;
    },

    /**
     * @param {*} value
     * @param {boolean} update
     * @return {Aitoc_Lnp_List_Toolbar_Abstract}
     */
    setValue: function( value, update ) {
        this._value = value;
        return this;
    },

    /**
     * @return {Aitoc_Lnp_Core_Mediator}
     */
    getMediator: function() {
        return this._mediator;
    },

    /**
     * @param {Aitoc_Lnp_Core_Mediator} mediator
     * @return {Aitoc_Lnp_List_Toolbar_Abstract}
     */
    setMediator: function( mediator ) {
        this._mediator = mediator;
        return this;
    },

    /**
     * @param {Object} config
     * @return {Aitoc_Lnp_List_Toolbar_Abstract}
     */
    setConfig: function( config ) {
        this._config = config;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_List_Toolbar} toolbar
     * @return {Aitoc_Lnp_List_Toolbar_Abstract}
     */
    setToolbar: function( toolbar ) {
        this._toolbar = toolbar;
        return this;
    },

    /**
     * @return {Aitoc_Lnp_List_Toolbar}
     */
    getToolbar: function() {
        return this._toolbar;
    }
});