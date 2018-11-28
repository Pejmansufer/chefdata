
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavAttributeValues = Class.create({
    _selected: 'a.adj-nav-attribute-selected',
    _collapse: 'adjnav-attr-val-collapse',
    _expand: 'adjnav-attr-val-expand',
    _adjnavExpandedFilters: {},

    initialize: function(value) {
        this.value = value;

        this.observeMoreValues();

        $$(this.value).each(function(moreLink)
        {
            moreLink.observe('click', function()
            {
                var rel = moreLink.readAttribute('rel');
                var isExpandedValues = false;
                for (var i in this._adjnavExpandedFilters)
                {
                    if (i == rel)
                    {
                        isExpandedValues = this._adjnavExpandedFilters[i];
                        break;
                    }
                }
                this._adjnavExpandedFilters[rel] = !isExpandedValues;

                adjnavNavigation.initOther();
                this.observeMoreValues();
            }.bind(this));
        }.bind(this));
    },

    observeMoreValues: function() {
        $$(this.value).each(function(moreLink) {
            var rel = moreLink.readAttribute('rel');

            var isExpandedValues = false;
            for (var i in this._adjnavExpandedFilters)
            {
                if (i == rel)
                {
                    isExpandedValues = this._adjnavExpandedFilters[i];
                    break;
                }
            }

            $$('#adj-nav-filter-' + rel + ' li.attr-val-other').each(function(li) {
                if (isExpandedValues)
                {
                    li.show();
                }
                else if (0 == li.select(this._selected).length)
                {
                    li.hide();
                }
            }.bind(this));

            $$('li.attr-val-more-li-' + rel + ' a').each(function(el) {
                el.innerHTML = isExpandedValues ? $(this._collapse).value : $(this._expand).value;
            }.bind(this));
        }.bind(this));
    }
});