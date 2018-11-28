
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Renderer_Slider = Class.create( Aitoc_Lnp_Layered_Renderer_Abstract, {

    _width: 170,

    initialize: function() {
        this._wrapperTemplate =
            '<dt id="lnp_filter_title_{{name}}">{{title}}{{remove}}</dt>' +
            '<dd id="lnp_filter_{{name}}" class="lnp_filter" >' +
                '<ol>' +
                    '<li>' +
                        '<div class="slider" style="width: 170px;">' +
                            '<div class="handle" style="left:0px"></div>' +
                            '<div class="handle" style="left:162px"></div>' +
                        '</div>' +

                        '<span class="range">{{range}}: ' +
                            '{{currency}}' +
                            '<span class="from">{{from}}</span> - ' +
                            '{{currency}}' +
                            '<span class="to">{{to}}</span>' +
                        '</span>' +
                    '</li>' +
                '</ol>' +
            '</dd>';
    },

    render: function() {
        var html = '',
        wrapperTemplate = new Template( this._wrapperTemplate, Aitoc_Lnp.TEMPLATE_SYNTAX),
        filterValue = this._filter.getValue();

        var from, to;
        if ( filterValue[0] ) {
            from = Math.max( filterValue[0], this.getFilter().getMinPrice() ).toFixed(2);
        } else {
            from = this.getFilter().getMinPrice().toFixed(2);
        }

        if ( filterValue[1] ) {
            to = Math.min( filterValue[1], this.getFilter().getMaxPrice() ).toFixed(2);
        } else {
            to = this.getFilter().getMaxPrice().toFixed(2);
        }

        html = wrapperTemplate.evaluate({
            range: this._config.Layered.text_range,
            currency: this._config.Layered.currency_symbol,
            from : from,
            to   : to,
            name : this._filter.getName(),
            title: this._filter.getTitle(),
            remove: filterValue.length ? '<a href="#" class="btn-remove"></a>' : ''
        });

        var transport = { html: html };
        this._afterRender( transport );

        return transport.html;
    },

    initObservers: function() {
        var price_slider = $$('#lnp_filter_' + this.getFilter().getName() + ' div.slider')[0],
            filterValue  = this.getFilter().getValue(),
            filterRange  = this.getFilter().getMaxPrice() - this.getFilter().getMinPrice();
        if ( filterRange > 0 ) {
            var sliderFrom = filterValue[0] ? (filterValue[0]-this.getFilter().getMinPrice())/filterRange*this._width : 0,
                sliderTo   = filterValue[1] ? (filterValue[1]-this.getFilter().getMinPrice())/filterRange*this._width : this._width;
        } else {
            var sliderFrom = 0,
                sliderTo   = this._width;
        }

        new Control.Slider(price_slider.select('.handle'), price_slider, {
            range: $R(0, this._width),
            sliderValue: [sliderFrom, sliderTo],
            restricted: true,
            renderer: this,
            width: this._width,

            onChange: function ( values ) {
                this.renderer.updateRange( values );

                this.renderer.getFilter().updateValue( [
                        this.renderer.getFilter().calculate( this.width, values[0] ),
                        this.renderer.getFilter().calculate( this.width, values[1] )
                    ], true
                );
            },
            onSlide: function( values ) {
                this.renderer.updateRange( values );
            }
        });

        $$('#lnp_filter_title_' + this._filter.getName() + ' a.btn-remove').each( function( elem ) {
            elem.observe('click', function( e ) {
                e.preventDefault();
                this.clear();
                return false;
            }.bind(this._filter))
        }.bind(this));
    },

    /**
     * @param {Array} values
     */
    updateRange: function( values ) {
        var from = this._filter.calculate( this._width, values[0], true ),
            to   = this._filter.calculate( this._width, values[1], true );
        $$('#lnp_filter_' + this.getFilter().getName() + ' span.range span.from')[0].update( from );
        $$('#lnp_filter_' + this.getFilter().getName() + ' span.range span.to')[0].update( to );
    }

});