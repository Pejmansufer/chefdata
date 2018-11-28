
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Sorter = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    bindElements: function() {
        this._elements = $$('.col-main div.toolbar div.sort-by select');
        if ( this._elements.length ) {
            this.setActive( true );
        }
        return this;
    },

    init: function() {
        if ( this.isActive() ) {

            var options = this._config.List.Toolbar.tools.Sorter.orders;
            var html = '';
            for ( var value in options ) {
                html += '<option value="' + value + '">' + options[value] + '</option>';
            }
            for ( i = 0; i < this._elements.length; i++ ) {
                this._elements[i].update( html );
            }

            // replace observers
            for ( var el = 0; el < this._elements.length; el++ ) {
                var element = this._elements[el];
                element.removeAttribute('onchange');
                element.observe('change', this.onChange.bind(this));
            }
        }
        this.setValue( this._config.List.Toolbar.tools.Sorter.order, false );
    },

    onChange: function( event ) {
        this.setValue( $(event.target).getValue(), true );
    },

    setValue: function( value, update ) {
        this._value = value;
        this.getMediator().orderChanged( value, update );
        for ( var i = 0; i < this._elements.length; i++ ) {
            this._elements[i].setValue( value );
        }
    }
});