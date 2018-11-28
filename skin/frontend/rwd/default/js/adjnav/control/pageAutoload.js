
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavPageAutoload = Class.create({
    initialize: function() {
        this.autoloadProgress = '.adjnav-page-autoload-progress';
        this.autoloadPholder = '.adjnav-page-autoload-pholder';
        this.productGrid = '.products-grid';
        this.productList = 'products-list';
        this.onScroll = this.makeRequest.bindAsEventListener(this);
        this.onShowProducts = this.showProducts.bindAsEventListener(this);
        this.init();
    },

    init: function() {
        jQuery(document).ready(this.first);
        Event.observe(window, 'scroll', this.onScroll);
    },

    first: function() {
        var viewport = document.viewport.getDimensions();
        var docHeight = Element.getHeight(document.body);

        if (viewport.height >= docHeight) {
            this.makeRequest();
        }
    },

    makeRequest: function() {
        var pholder = Element.down(document, this.autoloadPholder);
        if (!pholder || $$('.adj-nav-progress').last().visible()) {
            return;
        }

        var docElement = document;
        var elOffset = Element.cumulativeOffset(pholder);
        var viewDimens = docElement.viewport.getDimensions();
        var viewScrollOffsets = docElement.viewport.getScrollOffsets();

        if ((viewScrollOffsets.top + viewDimens.height >= elOffset.top) && (elOffset.top > 0)) {
            if (!adjnavPageLoadInProcess) {
                adjnavPageLoadInProcess = true;
                this.showProgress();
                var hashe = canChangeLocationHash;
                canChangeLocationHash = false;
                adjnavNavigation.prepareParams();
                canChangeLocationHash = hashe;
                var pageParam = '&p=' + $$('.adjnav-page-autoload-nextpage').first().value;

                new Ajax.Request($(nav_ajaxurl).value + '?' + $(nav_params).value + '&no_cache=true&home=' + lookhomepage + pageParam + adjnavNavigation.getAitanswer(),
                    {method: 'get', onSuccess: this.onShowProducts}
                );
            } else {
                return;
            }
        }
    },

    insertProducts: function(element, mode) {
        if (mode == 'list')
        {
            Element.select(element, '.item').each(function(item){
                $(this.productList).insert({bottom:item});
            }.bind(this));
            $(this.productList).select('li.item').each(function(item){
                item.removeClassName('odd');
                item.removeClassName('even');
                item.removeClassName('last');
            }.bind(this));
            decorateList(this.productList, 'none-recursive');
        }
        if (mode == 'grid')
        {
            this.updateGrid(element);
            $$(this.productGrid, '.item').each(function(item){
                item.removeClassName('odd');
                item.removeClassName('even');
                item.removeClassName('first');
                item.removeClassName('last');
            }.bind(this));
            decorateGeneric($$(this.productGrid), ['odd','even','first','last']);
            $$(this.productGrid).each(function(item){
                decorateGeneric(item.select('.item'), ['first','last']);
            });
        }
    },

    showProducts: function(transport) {
        var resp = {} ;
        if (transport && transport.responseText){
            try {
                resp = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                resp = {};
            }
        }

        if (resp.products)
        {
            var el = document.createElement('div');
            el.innerHTML = resp.products;
            var mode = '';
            if ($(this.productList))
            {
                mode = 'list';
            } else {
                mode = 'grid';
            }
            this.insertProducts(el, mode);
        }
        $$(this.autoloadProgress).last().hide();
        this.updatePholder(el);
        this.updateToolbar(el);
        adjnavPageLoadInProcess = false;
        this.init();
    },

    showProgress: function() {
        var bottomToolbar = $$('.toolbar-bottom');
        if (bottomToolbar.size())
        {
            bottomToolbar.last().insert({before:$$(this.autoloadProgress).last()});
        }
        $$(this.autoloadProgress).last().show();
    },

    _insert: function(count, element) {
        $R(0, count, true).each(function(index){
            var item = Element.select(element, '.item').first();
            $$(this.productGrid).last().insert({bottom:item});
        }.bind(this));
        var row = Element.select(element, this.productGrid).first();
        if (Element.select(row, '.item').size() > 0)
        {
            $$(this.productGrid).last().insert({after:row});
        } else {
            Element.remove(row)
        }
        if (Element.select(element, this.productGrid).first())
        {
            this._insert(count, element);
        } else {
            return;
        }
    },

    updateGrid: function(element) {
        var columnCount = $('adjnav-page-column-count').value;
        var pageLimit = $('adjnav-page-product-limit').value;
        var currentPage = $$('.adjnav-page-autoload-nextpage').first().value - 1;

        var count = columnCount - (pageLimit * currentPage) % columnCount;

        if (count == columnCount)
        {
            Element.select(element, this.productGrid).each(function(item){
                $$(this.productGrid).last().insert({after:item});
            }.bind(this));
        } else {
            this._insert(count, element);
        }
    },

    updatePholder: function(container) {
        var pholder = Element.down(container, this.autoloadPholder),
            domPholder = Element.down(document, this.autoloadPholder);

        if (!pholder && domPholder)
        {
            $$(this.autoloadPholder).invoke('remove');
        }
    },

    updateToolbar: function(container) {
        adjnavToolbar.update(container);
    }
});