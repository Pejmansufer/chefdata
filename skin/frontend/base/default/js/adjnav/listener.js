
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavListener = {
    adjnav: adjnavNavigation,
	
    listenCategory: function(evt) {
        var link = Event.findElement(evt, 'A');
        var catId = link.id.split('-')[1];

        var reg = /cat-/;
        if (reg.test(link.id)){ //is search
            this.adjnav.addParams('cat', catId, 1);
            this.adjnav.addParams('p', 'clear', 1);
            this.adjnav.makeRequest();
            Event.stop(evt);
        }
    },

    listenAttribute: function(evt) {
        this.adjnav.addParams('p', 'clear', 1);
        this.adjnav.updateLinks(evt, 'adj-nav-attribute', 0);
    },

    listenClear: function(evt) {
        var link = Event.findElement(evt, 'A'),
            varName = link.id.split('-')[0];

        this.adjnav.addParams('p', 'clear', 1);
        this.adjnav.addParams(varName, 'clear', 1);

        if ('price' == varName){
            var from =  $('adj-nav-price-from'),
                to   = $('adj-nav-price-to');

            if (Object.isElement(from)){
                from.value = from.name;
                to.value   = to.name;
            }
        }

        this.adjnav.makeRequest();
        Event.stop(evt);
    },

    listenClearAll: function(evt) {
        $(nav_params).value = $(nav_params).value.gsub(/\+/, ' ');
        var params = $(nav_params).value.parseQuery();
        $(nav_params).value = 'adjclear=true';
        if (params['q'])
        {
            $(nav_params).value += '&q=' + params['q'];
        }
        if (params['shopby_attribute']) {
            $(nav_params).value += '&shopby_attribute=' + params['shopby_attribute'];
            $(nav_params).value += '&' + params['shopby_attribute'] + '=' + params[params['shopby_attribute']];
        }
        this.adjnav.makeRequest();
        Event.stop(evt);
    },

    listenDt: function(evt) {
        var e = Event.findElement(evt, 'DT');
        e.nextSiblings()[0].toggle();
        e.toggleClassName('adj-nav-dt-selected');
    },

    listenIcon: function(evt) {
        this.adjnav.addParams('p', 'clear', 1);
        this.adjnav.updateLinks(evt, 'adj-nav-icon', 0);
    },

    listenPrice: function(evt) {
        this.adjnav.addParams('p', 'clear', 1);
        this.adjnav.updateLinks(evt, 'adj-nav-price', 1);
    },

    listenPriceInput: function(evt) {
        if (evt.type == 'keypress' && 13 != evt.keyCode)
            return;

        if (evt.type == 'keypress')
        {
            var inpObj = Event.findElement(evt, 'INPUT');
        }
        else
        {
            var inpObj = Event.findElement(evt, 'BUTTON');
        }

        var sKey = inpObj.id.split('---')[1];

        var price = new AdjnavPrice();
        var numFrom = price.round($('adj-nav-price-from---' + sKey).value),
            numTo   = price.round($('adj-nav-price-to---' + sKey).value);

        if (numFrom<0 || numTo<0)
            return;

        this.adjnav.addParams('p', 'clear', 1);
        this.adjnav.addParams(sKey, numFrom + ',' + numTo, true);
        this.adjnav.makeRequest();
    },

    listenToolbar: function(evt) {
        adjnavToolbar.makeRequest(Event.findElement(evt, 'A').href);
        Event.stop(evt);
    }
};