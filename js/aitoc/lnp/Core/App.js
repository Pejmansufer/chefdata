
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_App = Class.create({

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {Aitoc_Lnp_Core_Mediator}
     */
    _mediator: null,

    /**
     * @private {Object.<Aitoc_Lnp_Core_Component_Abstract>}
     */
    _components: null,

    /**
     * @private {Aitoc_Lnp_Core_Data}
     */
    _data: null,

    /**
     * @param {Object} config
     */
    initialize: function( config ) {
        this._config = config;
        this._mediator = new Aitoc_Lnp_Core_Mediator( this );
        this._data = new Aitoc_Lnp_Core_Data( this._config, this._mediator );

        this._buildComponents();
    },

    run: function() {
        this.getData().init();
        for ( var name in this._components ) {
            // TODO: asynchronous call
            // setTimeout( function ( name ) { this._components[name].init() }.bind(this, name), 0 )
            this._components[name].init();
        }

        if ( this._config.settings.debug_enabled ) {
            console.log( 'App object after run:', this);
        }
    },

    /**
     * @return {Aitoc_Lnp_Core_Data}
     */
    getData: function() {
        return this._data;
    },

    /**
     * @param {string} name
     * @return {Aitoc_Lnp_Core_Component_Abstract}
     */
    getComponent: function( name ) {
        return this._components[name];
    },

    /**
     * @private
     */
    _buildComponents: function() {
        this._components = {};
        var components = this._config.components;
        for ( var i = 0; i <  components.length; i++ ) {
            var className = components[i];
            if ( window[className] !== undefined ) {
                var component = new window[className]();
                component.setConfig( this._config )
                         .setMediator( this._mediator )
                         .setData( this._data );
                this._components[component.getName()] = component;
            } else {
                throw new Error('Attempt to initialize a component which does not exist');
            }
        }
    },

    /**
     * @return {Aitoc_Lnp_Core_Cookie}
     */
    cookie: function() {
        if ( !this._cookie ) {
            var c = this._config.settings.cookie;
            this._cookie = new Aitoc_Lnp_Core_Cookie( c.domain, c.path, c.lifetime );
        }
        return this._cookie;
    },

    /**
     * @return {Object}
     */
    getConfig: function() {
        return this._config;
    }
});