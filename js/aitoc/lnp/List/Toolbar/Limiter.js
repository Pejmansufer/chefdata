
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Limiter = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    _mode: '',

    initialize: function() {
        this._value = { grid: null, list: null };
    },

    bindElements: function() {
        this._elements = $$('.col-main div.toolbar div.limiter select');
        if ( this._elements.length ) {
            this.setActive( true );
        }
        return this;
    },

    init: function() {
        this._mode = this.getToolbar().getMode();
        this._value[this._mode] = this._config.List.Toolbar.tools.Limiter.limit[this._mode]; // default value
        this.getMediator().limitChanged( this._value[this._mode], false );

        if ( this.isActive() ) {
            this.switchMode( this._mode, false );
            // replace observers
            for ( var el = 0; el < this._elements.length; el++ ) {
                var element = this._elements[el];
                element.removeAttribute('onchange');
                element.observe('change', this.onChange.bind(this));
            }
        }
    },

    switchMode: function( mode, update ) {
        var oldMode = this._mode;
        this._mode = mode;
        var options = this._config.List.Toolbar.tools.Limiter.limits[mode];
        var html = '';
        for ( var i = 0; i < options.length; i++ ) {
            var label = (options[i] === 0) ? this._config.List.Toolbar.tools.Limiter.text_all : options[i];
            html += '<option value="' + options[i] + '">' + label + '</option>';
        }
        for ( i = 0; i < this._elements.length; i++ ) {
            this._elements[i].update( html );
        }
        if ( this._value[this._mode] !== null ) {
            this.setValue( this._value[this._mode], update );
        } else if ( this._config.List.Toolbar.tools.Limiter.limits[mode].indexOf(parseInt(this._value[oldMode])) != -1 ) {
            this.setValue( this._value[oldMode], update );
        } else {
            this.setValue( this._config.List.Toolbar.tools.Limiter.limit[mode], update );  // default mode value
        }

    },

    onChange: function( event ) {
        this.setValue( $(event.target).getValue(), true );
    },

    setValue: function( value, update ) {
        update = update || false;
        this._value[this._mode] = value;
        this.getMediator().limitChanged( value, update );
        for ( var i = 0; i < this._elements.length; i++ ) {
            this._elements[i].setValue( value );
        }
    },

    getValue: function() {
        return this._value[this._mode];
    }
});