
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Renderer_Abstract = Class.create({

    /**
     * @private {string}
     */
    _wrapperTemplate: '',

    /**
     * @private {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    _filter: null,

    /**
     * @private {Object}
     */
    _config: null,

    initialize: function() {
        this._wrapperTemplate =
             '<dt id="lnp_filter_title_{{name}}">{{title}}{{remove}}</dt>' +
             '<dd id="lnp_filter_{{name}}" class="lnp_filter" >' +
                 '<ol>{{options}}</ol>' +
             '</dd>';
    },

    /**
     * @param {Object} config
     * @return {Aitoc_Lnp_Layered_Renderer_Abstract}
     */
    setConfig: function( config ) {
        this._config = config;
        return this;
    },

    /**
     * @param {Aitoc_Lnp_Layered_Filter_Abstract} filter
     * @return {Aitoc_Lnp_Layered_Renderer_Abstract}
     */
    setFilter: function( filter ) {
        this._filter = filter;
        return this;
    },

    /**
     * @return {Aitoc_Lnp_Layered_Filter_Abstract}
     */
    getFilter: function() {
        return this._filter;
    },

    _beforeRender: function() {

    },

    render: function() {
        this._beforeRender();

        var options = this._filter.getOptions(),
            optionsHtml = '',
            wrapperTemplate = new Template( this._wrapperTemplate, Aitoc_Lnp.TEMPLATE_SYNTAX),
            filterValue = this._filter.getValue(),
            selected = false;

        for ( var i = 0; i < options.length; i++ ) {
            optionsHtml += '';
            if ( options[i][2] > 0 ) {
                selected = ( filterValue.indexOf( options[i][0] ) !== -1 );
                optionsHtml += '<li><a href="#" rel="value:' + options[i][0] + '"';
                optionsHtml += selected ? ' class="selected"' : '';
                optionsHtml += '>' + options[i][1] + '</a>';
                if ( this._config.settings.displayNumbers ) {
                    optionsHtml += ' (' + options[i][2] + ')';
                }
                optionsHtml += '</li>';
            } else if( this._filter.canShowZeroResults() ) {
                optionsHtml += '<li><span class="disabled">' + options[i][1] + '</span></li>';
            }
        }

        var html = '';
        if ( optionsHtml != '' ) {
            html = wrapperTemplate.evaluate({
                options: optionsHtml,
                name : this._filter.getName(),
                title: this._filter.getTitle(),
                remove: this._filter.getValue().length ? '<a href="#" class="btn-remove"></a>' : ''
            });
        }

        var transport = { html: html };
        this._afterRender( transport );

        return transport.html;
    },

    _afterRender: function( transport ) {

    },

    initObservers: function() {
        $$('#lnp_filter_' + this._filter.getName() + ' a').each( function( elem ) {
            elem.observe('click', function( e ) {
                e.preventDefault();

                var match = /:([-\w]+)/.exec( e.currentTarget.rel);
                this.updateValue( match[1], true );

                return false;
            }.bind(this._filter))
        }.bind(this));

        $$('#lnp_filter_title_' + this._filter.getName() + ' a.btn-remove').each( function( elem ) {
            elem.observe('click', function( e ) {
                e.preventDefault();
                this.clear();
                return false;
            }.bind(this._filter))
        }.bind(this));
    }

});