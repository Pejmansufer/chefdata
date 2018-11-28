
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Mode = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    _themeUpdaters: null,

    initialize: function() {
        this._themeUpdaters = {};
        this._themeUpdaters['default'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['rwd'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['ultimo'] = this.ultimoThemeUpdater.bind(this);
    },

    bindElements: function() {
        this._elements = $$('.col-main div.toolbar p.view-mode');
        if ( this._elements.length && this._elements[0].innerHTML.trim() != '' ) {
            this.setActive( true );
        }
        return this;
    },

    init: function() {
        this.setValue( this._config.List.Toolbar.tools.Mode.mode );
    },

    setValue: function( value, update ) {
        this._value = value;
        this.getMediator().modeChanged( value, update );

        var theme = this._config.settings.theme;
        if ( this.isActive() &&  typeof this._themeUpdaters[theme] == 'function' ) {
            this._themeUpdaters[theme]();
        }
        return this;
    },

    onChange: function( event ) {
        event.preventDefault();
        var newValue = (this._value == 'grid') ? 'list' : 'grid';
        this.setValue( newValue, true );
        return false;
    },

    //-------------- integrated theme updaters --------------//

    defaultThemeUpdater: function() {
        this._mainUpdater( 'strong', 'a' );
    },

    ultimoThemeUpdater: function() {
        this._mainUpdater( 'span', 'a' );
    },

    _mainUpdater: function( inactive, active ) {
        var newValue = this._value,
            oldValue = (newValue == 'grid') ? 'list' : 'grid';
        for ( var i = 0; i < this._elements.length; i++ ) {
            var elem = this._elements[i];
            elem.select('.list, .grid').invoke('remove');
            elem.cleanWhitespace();
            var label = elem.select('label');
            var grid = this.newSelector('grid', inactive, active);
            var list = this.newSelector('list', inactive, active);
            elem.insert( {bottom: ' '} );
            elem.insert( {bottom: grid} );
            elem.insert( {bottom: ' '} );
            elem.insert( {bottom: list} );
        }
    },

    newSelector: function( type, inactive, active ) {
        var value = this._value,
            selected = ( value == type ),
            elType =  selected ? inactive : active,
            label = this._config.List.Toolbar.tools.Mode['text_' + type];

        var result = new Element( elType, {
            'class': type,
            title: label
        });
        result.innerHTML = label;
        if ( !selected ) {
            result.href="#";
            result.observe('click', this.onChange.bind(this));
        }
        return result;
    }
});