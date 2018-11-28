
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavToolbar= Class.create({
    init: function() {
        var items = $('adj-nav-container').select('.pages a', '.view-mode a', '.sort-by a');
        for (var i=0; i<items.length; ++i){
            Event.observe(items[i], 'click', AdjnavListener.listenToolbar);
        }
    },

    makeRequest: function(href) {
        href = href.gsub(/\+/, ' ');
        var params = $(nav_params).value.parseQuery();
        if (href.indexOf('?') > -1)
        {
            var href = href.parseQuery();
            if (params['shopby_attribute'])
            {
                href['shopby_attribute'] = params['shopby_attribute'];
                href[params['shopby_attribute']] = params[params['shopby_attribute']];
            }
            $(nav_params).value = Object.toQueryString(href);
        }
        adjnavNavigation.makeRequest();
    },

    update: function(container) {
        var bar = Element.select(container, '.toolbar');
        if (bar.size())
        {
            $$('.toolbar').each(function(item){
                Element.update(item, bar.first().innerHTML);
            });
        }
        this.init();
    }
});
var adjnavToolbar = new AdjnavToolbar();