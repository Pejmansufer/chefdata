
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Renderer_Abstract = Class.create({

    /**
     * @private {Object}
     */
    _templates: null,

    /**
     * @private {Aitoc_Lnp_List}
     */
    _list: null,

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @param {Object} config
     * @return {Aitoc_Lnp_List_Renderer_Abstract}
     */
    setConfig: function( config ) {
        this._config = config;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_List} list
     * @return {Aitoc_Lnp_List_Renderer_Abstract}
     */
    setList: function( list ) {
        this._list = list;
        return this;
    },

    /**
     * @abstract
     */
    render: function(){},

    /**
     * @abstract
     */
    decorate: function(){},

    /**
     * @param {string} theme
     * @param {Object} template
     * @return {Aitoc_Lnp_List_Renderer_Abstract}
     */
    addTemplate: function( theme, template ) {
        this._templates[theme] = template;
        return this;
    }

});