
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Filter_Category = Class.create( Aitoc_Lnp_Layered_Filter_Abstract, {

    _type: 'category',

    /**
     * @private {Aitoc_Lnp_Core_Data_Category}
     */
    _categories: null,

    setOptions: function( options ) {
        this._options = options.pop();
        this._categories = new Aitoc_Lnp_Core_Data_Category( this._options );
        return this;
    },

    setId: function( id ) {
        this._id = 'category';
        return this;
    },

    /**
     * @param {string|Array} value
     * @param {boolean} update
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    updateValue: function( value, update ) {
        update = update || false;
        if ( value instanceof Array ) {
            this._value = value;
        } else if ( !value ) {
            this._value = [];
        } else {
            this._value = [ parseInt(value) ];
        }
        this.getMediator().filterUpdated( this._name, this._value, update );
        return this;
    },

    /**
     * @returns {Aitoc_Lnp_Core_Data_Category}
     */
    getCategories: function() {
        return this._categories;
    },

    resetOptionsCounts: function() {
        var categories = this._categories.getPlainChildren();
        for ( var id in categories ) {
            categories[id].setItemsQty(0);
        }
    },

    /**
     * @param {Array} matchingOptions
     */
    incrementOptionsCounts: function( matchingOptions ) {
        for ( var i = 0; i < matchingOptions.length; i++ ) {
            var id = matchingOptions[i];
            if ( this._categories.hasChild( id )  ) {
                this._categories.getChild( id ).incrementItemsQty();
            }
        }
    }

});