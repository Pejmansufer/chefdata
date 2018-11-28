
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Component_Abstract = Class.create({

    /**
     * @private {Aitoc_Lnp_Core_Mediator}
     */
    _mediator: null,

    /**
     * @private {string}
     */
    _name: '',

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {Aitoc_Lnp_Core_Data}
     */
    _data: null,

    /**
     * @param {Object} config
     * @return {Aitoc_Lnp_Core_Component_Abstract}
     */
    setConfig: function( config ) {
        this._config = config;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_Core_Mediator} mediator
     * @return {Aitoc_Lnp_Core_Component_Abstract}
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
     * @param {Aitoc_Lnp_Core_Data} data
     * @return {Aitoc_Lnp_Core_Component_Abstract}
     */
    setData: function( data ) {
        this._data = data;
        return this;
    },

    /**
     * @return {string}
     */
    getName: function() {
        return this._name;
    },

    /**
     * @abstract
     */
    init: function() {}

});