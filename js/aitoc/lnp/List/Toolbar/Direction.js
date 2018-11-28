
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Direction = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    _themeUpdaters: null,

    initialize: function() {
        this._themeUpdaters = {};
        this._themeUpdaters['default'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['rwd'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['ultimo'] = this.ultimoThemeUpdater.bind(this);
    },

    bindElements: function() {
        this._elements = $$('.col-main div.toolbar div.sort-by a');
        if ( this._elements.length ) {
            this.setActive( true );
        }
        return this;
    },

    init: function() {
        this.setValue( this._config.List.Toolbar.tools.Sorter.direction, false );

        if ( this.isActive() ) {
            for ( var i = 0; i < this._elements.length; i++ ) {
                var elem = this._elements[i];
                elem.href = '#';
                elem.observe('click', this.onChange.bind(this));
            }
        }
    },

    onChange: function( event ) {
        event.preventDefault();
        var newValue = (this._value == 'asc') ? 'desc' : 'asc';
        this.setValue( newValue, true );
        return false;
    },

    setValue: function( value, update ) {
        this._value = value;
        this.getMediator().orderDirChanged( value, update );

        var theme = this._config.settings.theme;
        if ( typeof this._themeUpdaters[theme] == 'function' ) {
            this._themeUpdaters[theme]();
        }
        return this;
    },

    //-------------- integrated theme updaters --------------//

    defaultThemeUpdater: function() {
        var newValue = this._value,
            oldValue = (newValue == 'asc') ? 'desc' : 'asc';
        for ( var i = 0; i < this._elements.length; i++ ) {
            var elem = this._elements[i];
            var img = elem.down();
            elem.title = this._config.List.Toolbar.tools.Direction['text_' + oldValue];
            if ( img ) {
                img.alt = this._config.List.Toolbar.tools.Direction['text_' + oldValue];
                img.src = img.src.replace('_' + oldValue + '_', '_' + newValue + '_');
            }
        }
    },

    ultimoThemeUpdater: function() {
        var newValue = this._value,
            oldValue = (newValue == 'asc') ? 'desc' : 'asc';
        for ( var i = 0; i < this._elements.length; i++ ) {
            var elem = this._elements[i];
            elem.title = elem.innerHTML = this._config.List.Toolbar.tools.Direction['text_' + oldValue];
            elem.className = elem.className.replace(oldValue, newValue);
        }
    }
});