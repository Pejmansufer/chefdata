
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */

var AdjnavBlock = Class.create({
    initialize: function(blockID) {
        this.blockID = blockID;
        this.registry = {
            ".adj-nav-category" : AdjnavListener.listenCategory,
            ".adj-nav-attribute": AdjnavListener.listenAttribute,
            ".adj-nav-icon"     : AdjnavListener.listenIcon,
            ".adj-nav-price"    : AdjnavListener.listenPrice,
            ".adj-nav-clear"    : AdjnavListener.listenClear,
            ".adj-nav-dt"       : AdjnavListener.listenDt,
            ".adj-nav-clearall" : AdjnavListener.listenClearAll
        };
        this.init();
    },

    init: function() {
        var addOther = false;

        if(typeof(arguments[0]) != 'undefined') {
            addOther = arguments[0];
        }

        this.addListeners(addOther);

        if(addOther)
            return false;

        var price = new AdjnavPrice();
        price.addListeners(this.blockID);
    },

    addListeners: function(addOther) {
        var keys = Object.keys(this.registry);
        for (var j=0; j<keys.length; ++j){
            var items = $(this.blockID).select(keys[j]);
            for (var i= 0; i<items.length; ++i){
                if(adjnavNavigation.ajdnavExpandedLoaded || addOther == items[i].hasClassName('other')) {
                    Event.observe(items[i], 'click', this.registry[keys[j]].bind(AdjnavListener));
                }
            }
        }
    },

    hideProducts: function() {
        if (this.checkIfExists()) {
            var items = $(this.blockID).select('a', 'input', 'select');
            for (var i=0; i<items.length; ++i){
                items[i].addClassName('adj-nav-disabled');
            }

            if (typeof(adj_slider) != 'undefined')
                adj_slider.setDisabled();

            var divs = $$('div.adj-nav-progress');
            for (var i=0; i<divs.length; ++i)
                divs[i].show();
        }
    },

    showProducts: function() {
        if (this.checkIfExists()) {
            var items = $(this.blockID).select('a','input', 'select');
            for (var i=0; i<items.length; ++i){
                items[i].removeClassName('adj-nav-disabled');
            }
            if (typeof(adj_slider) != 'undefined')
                adj_slider.setEnabled();
        }
    },

    checkIfExists: function() {
        return $(this.blockID) ? true : false;
    }
});