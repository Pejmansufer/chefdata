
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Cookie = Class.create({

    COOKIE_NAME: 'aitoc_lnp',

    /**
     * @private {string}
     */
    _domain: '',

    /**
     * @private {string}
     */
    _path: '',

    /**
     * Expiration interval in milliseconds
     *
     * @private {number}
     */
    _lifetime: 0,

    /**
     * @private {Object}
     */
    _data: null,

    /**
     * @param {string} domain Current domain name
     * @param {string} path Current path on server.
     * @param {number} lifetime Time before cookie expires in seconds.
     */
    initialize: function( domain, path, lifetime ) {
        this._domain = domain;
        this._path   = path;
        this._lifetime = ( lifetime || 3600 ) * 1000;
        this._data = {};

        var data = this.getCookie( this.COOKIE_NAME );
        if ( data ) {
            this._data = data.evalJSON();
        }
    },

    /**
     * @param {number} delta
     * @return {string}
     * @private
     */
    _getDate: function( delta ) {
        var d = new Date();
        d.setTime( d.getTime() + delta );
        return d.toGMTString();
    },

    /**
     * @param {string} name
     * @param {string} value
     * @return {Aitoc_Lnp_Core_Cookie}
     */
    setCookie: function( name, value ) {
        var expiration = value ? this._lifetime : -1000000;
        var s = name + "=" + value + ";" +
                "expires=" + this._getDate( expiration ) + ";" +
                "domain=" + this._domain + ";" +
                "path=" + this._path + ";";
        document.cookie = s;
        return this;
    },

    /**
     * @param {string} name
     * @return {Aitoc_Lnp_Core_Cookie}
     */
    remove: function ( name ) {
        this.setCookie( name, '' );
        return this;
    },

    /**
     * @param {string} name
     * @return {string}
     */
    getCookie: function( name ) {
        name += "=";
        var ca = document.cookie.split(';');
        for( var i = 0; i < ca.length; i++ ) {
            var c = ca[i].trim();
            if ( c.indexOf(name) == 0 ) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    },

    setData: function( key, value ) {
        if ( key instanceof Array ) {
            this._data = key;
        } else {
            this._data[key] = value;
        }
        // store data into cookie
        var s = Object.toJSON( this._data );
        this.setCookie( this.COOKIE_NAME, s );

        return this;
    },

    getData: function( key ) {
        if ( key ) {
            if ( this.hasData( key ) ) {
                return this._data[key];
            } else {
                return null;
            }
        }
        return this._data;
    },

    hasData: function( key ) {
        return ( typeof this._data[key] != undefined );
    },

    unsData: function( key ) {
        if ( this.hasData( key ) ) {
            delete this._data[key];
        }
        return this;
    }

});