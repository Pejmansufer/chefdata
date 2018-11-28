
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
// constants and static functions
var Aitoc_Lnp = {

    _cookie: null,

    // list modes
    MODE_GRID: 'grid',
    MODE_LIST: 'list',

    ORDER_ASC :  1,
    ORDER_DESC: -1,

    // filter defaults
    FILTER_DEFAULT_TYPE    : 'Default',
    FILTER_DEFAULT_RENDERER: 'Default',

    TEMPLATE_SYNTAX: /(^|.|\r|\n)({{(\w+)}})/,

    /**
     * Converts the first symbol of a string to uppercase
     *
     * @param {string} str
     * @returns {string}
     */
    ucFirst: function( str ) {
        if ( str.length ) {
            str = str.charAt(0).toUpperCase() + str.slice(1);
        }
        return str;
    }
};