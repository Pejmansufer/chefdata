
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Data_Category = Class.create({

    /**
     * @private {number}
     */
    _id: 0,

    /**
     * @private {string}
     */
    _title: '',

    /**
     * @private {number}
     */
    _level: 0,

    /**
     * @private {Aitoc_Lnp_Core_Data_Category}
     */
    _parent: null,

    /**
     * @private {Object.<number, Aitoc_Lnp_Core_Data_Category>}
     */
    _children: null,

    /**
     * @private {Object.<number, Aitoc_Lnp_Core_Data_Category>}
     */
    _plainChildren: null,

    /**
     * @private {Array.<number>}
     */
    _index: null,

    /**
     * @private: {Array.<number>}
     */
    _recursiveIndex: null,

    /**
     * @private {number}
     */
    _itemsQty: 0,

    initialize: function( data )
    {
        this._id = parseInt( data[0] );
        this._title = data[1];
        this._itemsQty = data[2];
        this._children = {};
        this._plainChildren = {};
        this._index = [];
        this._recursiveIndex = [];
        if ( data[3].length > 0 ) {
            var nextLevel = this._level + 1;
            for ( var i = 0; i < data[3].length; i++ ) {
                var newChild = new Aitoc_Lnp_Core_Data_Category( data[3][i] ),
                    newChildId = newChild.getId();
                this._index.push( newChildId );
                this._children[newChildId] = newChild;
                newChild.setParent( this )
                        .setLevel( nextLevel );
                this._recursiveIndex = this._recursiveIndex.concat( newChild.getRecursiveIndex() );
                Object.extend( this._plainChildren, newChild.getPlainChildren() );
            }
            this._recursiveIndex = this._recursiveIndex.concat( this._index );
            Object.extend( this._plainChildren, this.getChildren() );
        }
    },

    /**
     * @return {Array.<number>}
     */
    getIndex: function() {
        return this._index;
    },

    /**
     * @return {Array.<number>}
     */
    getRecursiveIndex: function() {
        return this._recursiveIndex;
    },

    /**
     * @return {number}
     */
    getLevel: function() {
        return this._level;
    },

    /**
     * @param {number} level
     * @return {Aitoc_Lnp_Core_Data_Category}
     */
    setLevel: function( level ) {
        this._level = level;
        return this;
    },

    /**
     * @return {string}
     */
    getTitle: function() {
        return this._title;
    },

    /**
     * @return {Object.<number, Aitoc_Lnp_Core_Data_Category>}
     */
    getChildren: function() {
        return this._children;
    },

    /**
     * @return {Object.<number, Aitoc_Lnp_Core_Data_Category>}
     */
    getPlainChildren: function() {
        return this._plainChildren;
    },

    /**
     * @return {boolean}
     */
    hasChildren: function() {
        return ( this._index.length > 0 );
    },

    /**
     * @return {number}
     */
    getId: function() {
        return this._id;
    },

    /**
     * @param {number} id
     * @return {boolean}
     */
    hasChild: function( id ) {
        return ( this._recursiveIndex.indexOf( id ) !== -1 );
    },

    getChild: function ( id ) {
        return this._plainChildren[id];
    },

    /**
     * @return {Aitoc_Lnp_Core_Data_Category}
     */
    getParent: function() {
        return this._parent;
    },

    /**
     * @param {Aitoc_Lnp_Core_Data_Category} parent
     * @return {Aitoc_Lnp_Core_Data_Category}
     */
    setParent: function( parent ) {
        this._parent = parent;
        return this;
    },

    /**
     * @param {number} qty
     * @return {Aitoc_Lnp_Core_Data_Category}
     */
    setItemsQty: function( qty ) {
        this._itemsQty = qty;
        return this;
    },

    /**
     * @returns {Aitoc_Lnp_Core_Data_Category}
     */
    incrementItemsQty: function() {
        this._itemsQty++;
        return this;
    },

    /**
     * @return {number}
     */
    getItemsQty: function() {
        return this._itemsQty;
    }

});