
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Data_Fetcher = Class.create({

    /**
     * @private {string}
     */
    _url: '',

    /**
     * @param {string} url
     */
    initialize: function( url ) {
        this._url = url;
    },

    /**
     * Fetch category data from a server
     *
     * @param {number} categoryId
     * @param {function} callback
     */
    request: function( categoryId, callback ) {
        new Ajax.Request( this._url, {
            method: 'post',
            parameters: { category_id: categoryId },
            onSuccess: function ( transport ){ callback( transport.responseText )}
        });
    }
});