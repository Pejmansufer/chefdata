
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Filter_Price = Class.create( Aitoc_Lnp_Layered_Filter_Abstract, {

    _type: 'price',

    updateValue: function( value, update ) {
        update = update || false;
        this._value = [];
        if ( value.length > 0 ) {
            var from = value[0],
                to   = value[1];
            if ( from != this.getMinPrice() || to != this.getMaxPrice() ) {
                this._value = value;
            }
        }

        this.getMediator().filterUpdated( this._name, this._value, update );
    },

    calculate: function( width, value, round ) {
        round = round || false;
        var result = ((this.getMaxPrice() - this.getMinPrice()) * value / width) + this.getMinPrice();
        return round ? this.round( result ) : result;
    },

    round: function( num ) {
        num = parseFloat( num );
        if ( isNaN( num ) ) {
            num = 0;
        }

        return num.toFixed( 2 );
    },

    /**
     * @param {float} price
     * @return {Aitoc_Lnp_Layered_Filter_Price}
     */
    setMinPrice: function( price ) {
        this._options[0] = price;
        return this;
    },

    /**
     * @return {float}
     */
    getMinPrice: function() {
        return this._options[0];
    },

    /**
     * @param {number} price
     * @return {Aitoc_Lnp_Layered_Filter_Price}
     */
    setMaxPrice: function( price ) {
        this._options[1] = price;
        return this;
    },

    /**
     * @return {number}
     */
    getMaxPrice: function() {
        return this._options[1];
    }

});