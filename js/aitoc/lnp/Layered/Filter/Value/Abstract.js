
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Filter_Value_Abstract = Class.create({

    /**
     * @private {*}
     */
    _value: null,

    /**
     * @param {*} value
     */
    initialize: function( value ) {
        this._value = value;
    },

    /**
     * @return {*}
     */
    getValue: function() {
        return this._value;
    }
});