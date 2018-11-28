
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_List_Renderer_Grid = Class.create( Aitoc_Lnp_List_Renderer_Abstract, {

    initialize: function() {
        this._templates = {};
        this.addTemplate( 'default', {
            template:
                '<ul class="products-grid">' +
                    '{{items}}' +
                '</ul>',
            separator: '</ul><ul class="products-grid">'
        });

        this.addTemplate( 'ultimo', {
            template:
                '<ul class="products-grid category-products-grid itemgrid itemgrid-adaptive itemgrid-{{columns}}col{{classes}}">' +
                    '{{items}}' +
                '</ul>'
        });

        this.addTemplate( 'rwd', {
            template:
                '<ul class="products-grid products-grid--max-{{columns}}-col">' +
                    '{{items}}' +
                    '</ul>',
            separator: ''
        });
    },

    render: function() {
        var items    = this._list.getItems(),
            products = this._list.getProductsQty(),
            columns  = this._config.List.grid_columns,
            theme    = this._config.settings.theme;

        if ( !products ) {
            return '<div class="aitoc_lnp_no_products">' + this._config.List.text_no_products + '</div>';
        }

        if ( this._templates[theme] === 'undefined' ) {
            throw new Error( 'Incorrect theme request' );
        }

        var template  = this._templates[theme].template,
            separator = this._templates[theme].separator,
            itemsHtml = '';

        for ( var i = 0; i < Math.min(products, items.length) ; i++ ) {
            if ( separator !== undefined && i && i%columns == 0 ) {
                itemsHtml += separator;
            }
            itemsHtml += items[i].render();
        }
        var itemsTemplate = new Template( itemsHtml, Aitoc_Lnp.TEMPLATE_SYNTAX );
        this._config.Data.parseInto.columns=columns;
        itemsHtml = itemsTemplate.evaluate( this._config.Data.parseInto );

        var templateObj = new Template( template, Aitoc_Lnp.TEMPLATE_SYNTAX );

        return templateObj.evaluate({
            items: itemsHtml,
            columns: columns,
            classes: this._config.List.grid_classes
        });
    },

    decorate: function() {
        if ( this._config.settings.theme == 'ultimo' ) {
            this._ultimoDecoration();
        } else if ( this._config.settings.theme == 'rwd' ) {
            this._rwdDecoration();
        } else {
            decorateGeneric($$('ul.products-grid'), ['odd','even','first','last']);
        }
    },

    _rwdDecoration: function() {
        $j(window).trigger('delayed-resize');
    },

    _ultimoDecoration: function() {
        var gc = this._config.settings.theme_options;
        if( gc['equal_height'] ) {
            gridItemsEqualHeightApplied = false;
            setGridItemsEqualHeight(jQuery);
        }

        jQuery(function($) {

            if ( gc['hover_effect'] ) {

                var startHeight;
                var bpad;
                $('.category-products-grid').on('mouseenter', '.item', function() {

                    if ( !gc['disable_hover_effect'] || $(window).width() >= gc['disable_hover_effect'] ) {

                        if ( gc['equal_height'] && gridItemsEqualHeightApplied === false ) {
                            return false;
                        }

                        startHeight = $(this).height();
                        $(this).css("height", "auto"); //Release height
                        $(this).find(".display-onhover").fadeIn(400, "easeOutCubic"); //Show elements visible on hover
                        var h2 = $(this).height();

                        //Compare start height with on-hover height, calculate the difference
                        ////////////////////////////////////////////////////////////////
                        var addtocartHeight = 0;
                        var addtolinksHeight = 0;
                        if ( gc['display_addtocart'] == 1) { //if displayed on hover
                            //addtocartHeight = $(this).find('.btn-cart').height(); //obsolete
                            var buttonOrStock = $(this).find('.btn-cart');
                            if (buttonOrStock.length == 0) {
                                buttonOrStock = $(this).find('.availability');
                            }
                            addtocartHeight = buttonOrStock.height();
                        }

                        if ( gc['display_addtolinks'] == 1 ){ //if displayed on hover (but when is NOT on image) ?>
                            var addtolinksEl = $(this).find('.add-to-links');
                            if (addtolinksEl.hasClass("addto-onimage") == false) {
                                addtolinksHeight = addtolinksEl.innerHeight(); //.height();
                            }
                        }

                        if ( gc['equal_height'] && ( gc['display_addtocart'] == 1 || gc['display_addtolinks'] == 1)) {
                            var h3 = h2 + addtocartHeight + addtolinksHeight;
                            var diff = 0;
                            if (h3 < startHeight) {
                                $(this).height(startHeight);
                            } else {
                                $(this).height(h3); //Apply height explicitly
                                diff = h3 - startHeight;
                            }
                        } else {
                            var diff = 0;
                            if ( h2 < startHeight ) {
                                $(this).height(startHeight);
                            } else {
                                $(this).height(h2);
                                diff = h2 - startHeight;
                            }
                        }
                        ////////////////////////////////////////////////////////////////

                        $(this).css("margin-bottom", "-" + diff + "px"); //Apply difference as nagative margin
                    }

                }).on('mouseleave', '.item', function() {

                    if ( !gc['disable_hover_effect'] || $(window).width() >= gc['disable_hover_effect'] ) { //If hover effect disabled for vieport width below specified value ?>
                        //Clean up
                        $(this).find(".display-onhover").stop(true).hide();
                        $(this).css("margin-bottom", "");

                        //Return the default height. If "Egual Height" disabled, unset item's height.
                        if ( gc['equal_height']) {
                            $(this).height(startHeight);
                        } else {
                            $(this).css("height", "");
                        }
                    }

                });

            } else { //hover effect NOT enabled
                //Display elements visible on hover
                $('.category-products-grid').on('mouseenter', '.item', function() {
                    $(this).find(".display-onhover").fadeIn(400, "easeOutCubic");
                }).on('mouseleave', '.item', function() {
                    $(this).find(".display-onhover").stop(true).hide();
                });
            }

            //Display alternative image
            $('.products-grid, .products-list').on('mouseenter', '.item', function() {
                $(this).find(".alt-img").fadeIn(400, "easeOutCubic");
            }).on('mouseleave', '.item', function() {
                $(this).find(".alt-img").stop(true).fadeOut(400, "easeOutCubic");
            });
        });
    }
});