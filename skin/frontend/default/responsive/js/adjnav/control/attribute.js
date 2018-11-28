
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavAttribute = Class.create({
    _expandedAttributes: false,
    _link: 'a.adjnav-attr-more',
    _collapse: 'adjnav-attr-collapse',
    _expand: 'adjnav-attr-expand',
    _selected: 'a.adj-nav-attribute-selected',
    _selectedOption: '#adj-nav-select option:selected',

    initialize: function(name, values) {
        this._attributeName = name;
        this._attribute = values;

        this.observeMoreAttributes();

        $$(this._link).each(function(moreLink)
        {
            moreLink.observe('click', function()
            {
                this._expandedAttributes = !this._expandedAttributes;
                this.observeMoreAttributes();
            }.bind(this));
        }.bind(this))
    },

    observeMoreAttributes: function() {
        if (!this._expandedAttributes)
        {
            $$(this._attributeName).each(this.hideOther.bind(this));
            $$(this._attribute).each(this.hideOther.bind(this));

            $$(this._link).each(function(moreLink)
            {
                moreLink.innerHTML = $(this._expand).value;
            }.bind(this));
        }
        else
        {
            $$(this._attributeName).each(function(el){el.show();});
            $$(this._attribute).each(function(el){el.show();});

            $$(this._link).each(function(moreLink)
            {
                moreLink.innerHTML = $(this._collapse).value;
            }.bind(this));
        }
    },

    hideOther: function(el) {
        switch (el.tagName.toLowerCase())
        {
            case 'dt':
                if (0 == el.select('a.adj-nav-clear').length)
                {
                    el.hide();
                }
                break;

            case 'dd':
                if (0 == el.select(this._selected).length && jQuery(el.select(this._selectedOption)).index() <= 0)
                {
                    el.hide();
                }
                break;
        }
    }
});