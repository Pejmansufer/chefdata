
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Toolbar_Pager = Class.create( Aitoc_Lnp_List_Toolbar_Abstract, {

    _syntax: /(^|.|\r|\n)({{(\w+)}})/,

    _amountBlocks: null,

    _value: 1,

    _frame: 0,

    _frameStart: 0,

    _frameEnd: 0,

    _jump: 0,

    _maxPage: 0,

    _themeUpdaters: null,

    _itemsQty: 0,

    initialize: function() {
        this._themeUpdaters = {};
        this._themeUpdaters['default'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['rwd'] = this.defaultThemeUpdater.bind(this);
        this._themeUpdaters['ultimo'] = this.defaultThemeUpdater.bind(this);
    },

    bindElements: function() {
        this._amountBlocks = $$('.col-main div.toolbar p.amount');
        this._elements = $$('.col-main div.toolbar div.pages');
        return this;
    },

    init: function() {
        var config  = this._config.List.Toolbar.tools.Pager;
        this._frame = config.frame;
        this._jump  = config.jump;
        this._itemsQty = config.item_count;

        var limit = this.getToolbar().getLimit();
        this._maxPage = limit ? Math.ceil( this._itemsQty/limit ) : 1;

        if ( !this._elements.length ) {
            var pagerBlocks = $$('.col-main div.toolbar div.pager');
            if ( pagerBlocks.length == 0 ) {
                var toolbars = $$('.col-main div.toolbar');
                for ( var i = 0; i < toolbars.length; i++ ) {
                    toolbars[i].insert( new Element( 'div', {'class':'pager', 'style': "display: none"}) );
                }
                pagerBlocks = $$('.col-main div.toolbar div.pager');
            }
            for ( var i = 0; i < pagerBlocks.length; i++ ) {
                var newBlock = new Element('div', {'class': 'pages gen-direction-arrows1', 'style': "display: none"});
                    newBlock.innerHTML = '<strong>' + config.text_page + '</strong> <ol></ol>';
                    pagerBlocks[i].insert( newBlock );
                this._elements.push( newBlock );
            }
        }

        var baseLinks = $$('.col-main div.toolbar div.pages ol a');
        for (var i = 0; i < baseLinks.length; i++ ) {
            var link = baseLinks[i],
                match = /(?:\?|&)p=(\d+)/.exec( link.href ),
                index = match[1];
                link.href = '#';
                link.rel = 'value:' + index;
                link.observe( 'click', this.onChange.bind(this) );
        }
    },

    onChange: function( event ) {
        event.preventDefault();
        var match = /:(\d+)/.exec( event.currentTarget.rel );
        this.setValue( parseInt( match[1] ), true );
        return false;
    },

    setValue: function( value, update ) {
        this._value = value;
        this.getMediator().pageChanged( this._value, update );

        this._updateFrame();

        var theme = this._config.settings.theme;
        if ( typeof this._themeUpdaters[theme] == 'function' ) {
            this._themeUpdaters[theme]();
        }
        return this;
    },

    update: function( itemsQty, newPage ) {
        var limit = this.getToolbar().getLimit();
        this._itemsQty = ( itemsQty === undefined ) ? this._itemsQty : itemsQty;
        this._maxPage = limit ?  Math.ceil( itemsQty/limit ) : 1;

        newPage = newPage || 1;
        this.setValue( newPage, false );
    },

    /**
     * @see in PHP - Mage_Page_Block_Html_Pager::_initFrame()
     *
     * @private
     */
    _updateFrame: function() {
        var start = 0;
        var end = 0;
        var value = this._value;

        if ( this._maxPage == 1 ) {
            start = end = 1;
        } else {
            if ( this._maxPage <= this._frame ) {
                start = 1;
                end = this._maxPage;
            } else {
                var half = Math.ceil(this._frame / 2);
                if ( value >= half && value <= (this._maxPage - half) ) {
                    start  = (value - half) + 1;
                    end = (start + this._frame) - 1;
                } else if (value < half) {
                    start  = 1;
                    end = this._frame;
                } else if (value > (this._maxPage - half)) {
                    end = this._maxPage;
                    start  = end - this._frame + 1;
                }
            }
        }
        this._frameStart = start;
        this._frameEnd = end;
    },

    //-------------- integrated theme updaters --------------//

    //TODO: split and simplify
    defaultThemeUpdater: function() {
        var config = this._config.List.Toolbar.tools.Pager;
        if ( this._maxPage <= 1 ) {
            if ( this._config.settings.theme == 'ultimo' ) {
                $$('.col-main div.toolbar div.pager').invoke('hide');
            } else {
                this._elements.invoke('hide');
            }
            var template = new Template( config.text_totals_small, Aitoc_Lnp.TEMPLATE_SYNTAX );
            this._amountBlocks.invoke ( 'update', '<strong>' + template.evaluate([this._itemsQty]) + '</strong>' );
        } else {
            this._elements.invoke('show');
            $$('.col-main div.toolbar div.pager').invoke('show');
            var html = '',
                value = this._value;

            // previous page link
            if ( value > 1 ) {
                html += ' <li><a class="previous" href="#" rel="value:' + (value-1) + '" title="' + config.text_prev + '">' + config.label_prev + '</a></li> '
            }

            // link to the first page
            if ( this._jump > 1 && this._frameStart > 1 ) {
                html += ' <li><a class="first" href="#" rel="value:1">1</a></li> ';
            }

            // jump backward link
            if ( (this._frameStart - 1) > 1 ) {
                var backJump = Math.max(2, this._frameStart - this._jump);
                html += ' <li><a class="previous_jump" href="#" rel="value:' + backJump + '">...</a></li> ';
            }

            for ( var p = this._frameStart; p <= this._frameEnd; p++ ) {
                html += ' <li' + ((value == p) ? ' class="current"' : '') + '>';
                if ( value != p ) {
                    html += '<a href="#" rel="value:' + p + '">' + p + '</a>';
                } else {
                    html += p;
                }
                html += '</li> '
            }

            // jump forward link
            if ( (this._maxPage - this._frameEnd) > 1 ) {
                var nextJump = Math.min(this._maxPage - 1, this._frameEnd + this._jump);
                html += ' <li><a class="next_jump" href="#" rel="value:' + nextJump + '">...</a></li> ';
            }

            // link to the last page
            if ( this._jump > 1 && this._frameEnd < this._maxPage ) {
                html += ' <li><a class="last" href="#" rel="value:' + this._maxPage + '">' + this._maxPage + '</a></li> ';
            }

            // next page link
            if ( value < this._maxPage ) {
                html += ' <li><a class="next" href="#" rel="value:' + (value+1) + '" title="' + config.text_next + '">' + config.label_next + '</a></li> '
            }
            $$('.col-main div.toolbar div.pages ol').invoke( 'update', html );
            $$('.col-main div.toolbar div.pages ol a').invoke( 'observe', 'click', this.onChange.bind(this) );
            var template = new Template( config.text_totals_full, Aitoc_Lnp.TEMPLATE_SYNTAX );
            var limit = this.getToolbar().getLimit();
            this._amountBlocks.invoke ( 'update', template.evaluate([
                limit * (this._value-1)+1,
                Math.min( limit * this._value, this._itemsQty ),
                this._itemsQty
            ]) );
        }
    }

});