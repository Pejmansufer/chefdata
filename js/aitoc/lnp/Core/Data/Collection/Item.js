
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Data_Collection_Item = Class.create({

    /**
     * @private {Array}
     */
    _data: null,

    /**
     * @private {Object.<number>}
     */
    _fields: null,

    /**
     * @param {Array} data
     * @return {Aitoc_Lnp_Core_Data_Collection_Item}
     */
    setData: function( data ) {
        this._data = data;
        return this;
    },

    /**
     * @param {Object.<number>} fields
     * @return {Aitoc_Lnp_Core_Data_Collection_Item}
     */
    setFields: function( fields ) {
        this._fields = fields;
        return this;
    },

    /**
     * @param {string|number} key
     * return {*}
     */
    getData: function( key ) {
        if ( typeof key !== undefined ) {
            if ( typeof key == 'string' ) {
                key = this._fields[ key ];
            }
            return this._data[key];
        } else {
            return this._data;
        }
    }

});