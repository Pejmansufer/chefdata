
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Core_Data_Collection = Class.create({

    /**
     * @private {Object}
     */
    _config: null,

    /**
     * @private {Array}
     */
    _data: null,

    /**
     * @private {Array.<number>}
     */
    _idsIndex: null,

    /**
     * @private {Array.<number>}
     */
    _filteredData: null,

    /**
     * @private {Object.<number>}
     */
    _fields: null,

    /**
     * @private {Object}
     */
    _index: null,

    /**
     * @private: {Object}
     */
    _categoriesIndex: null,

    /**
     * @private {Array.<Aitoc_Lnp_Core_Data_Collection_Item>}
     */
    _items: null,

    /**
     * @private {number}
     */
    _limit: 0,

    /**
     * @private {number}
     */
    _page: 1,

    /**
     * @private {string}
     */
    _order: '',

    /**
     * Sorting order. Should be set 1 for ASC and -1 for DESC
     *
     * @private {number}
     */
    _orderDir: Aitoc_Lnp.ORDER_ASC,

    /**
     * @private {boolean}
     */
    _orderChanged: true,

    /**
     * @private {Object.<Array>}
     */
    _filters: null,

    /**
     * @private {boolean}
     */
    _filtersChanged: true,

    /**
     * @private {boolean}
     */
    _lock: false,

    _directions: { 'asc': Aitoc_Lnp.ORDER_ASC, 'desc': Aitoc_Lnp.ORDER_DESC },

    initialize: function( config ) {
        this._config = config;
        this._items = [];
        this._filters = {};
        this._filteredData = [];
        this._idsIndex = [];
    },

    /**
     * @param {Object.<Array>} data
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setData: function( data ) {
        this._data = data;

        var priceIndex = this._fields['min_price'];

        // create indexed array of all available ids
        // and base transformations of the data
        for ( var id in this._data ) {
            if ( this._data[id][priceIndex] ) {
                this._data[id][priceIndex] = parseFloat(this._data[id][priceIndex]);
            }
            this._idsIndex.push( id );
        }
        return this;
    },

    /**
     * @param {Array} fields
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setFields: function( fields ) {
        this._fields = fields;
        return this;
    },

    /**
     * @param {Object} index
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setIndex: function( index ) {
        this._index = index;
        return this;
    },

    /**
     * @param {Object} index
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setCategoriesIndex: function( index ) {
        this._categoriesIndex = index;
        return this;
    },

    /**
     * @return {Array.<Aitoc_Lnp_Core_Data_Collection_Item>}
     */
    getItems: function() {
        return this._items;
    },

    /**
     * @param {number} limit
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setLimit: function( limit ) {
        this._limit = parseInt(limit);
        return this;
    },

    /**
     * @param {number} page
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setPage: function( page ) {
        this._page = page;
        return this;
    },

    /**
     * @param {string} order
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setOrder: function( order ) {
        this._order = order;
        this._orderChanged = true;
        return this;
    },

    /**
     * @param {string} orderDir
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setOrderDir: function( orderDir ) {
        this._orderDir = this._directions[orderDir];
        this._orderChanged = true;
        return this;
    },

    /**
     * @param {number} id
     * @param {*} value
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    setFilter: function( id, value ) {
        this._filters[id] = value;
        if ( this._filters[id].length == 0 ) {
            delete this._filters[id];
        }
        this._filtersChanged = true;
        this._orderChanged = true;
        return this;
    },

    /**
     * @returns {Object}
     */
    getFilters: function() {
        return this._filters;
    },

    // ----- PROCESSING ----- //
    load: function( force ) {
        force = force || false;

        if ( this._lock ) {
            // TODO: implement interception
        }
        this._lock = true;
        this._processFilters( force )
            ._applyOrder( force )
            ._updateItems();
        this._lock = false;
        if ( this._config.settings.debug_enabled ) {
            console.log( 'Collection loaded:', this );
        }
        return this;
    },

    /**
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    _processFilters: function( force ) {
        if ( this._filtersChanged || force ) {
            this._filteredData = [];
            var filtersNum = 0;
            for ( var filterId in this._filters ) {
                filtersNum++;
            }

            var priceIndex = this._fields['min_price'];

            if ( filtersNum ) {
                for ( var i = 0; i < this._idsIndex.length; i++ ) {
                    var itemId = this._idsIndex[i],
                        valid = true;
                    for ( filterId in this._filters ) {
                        // TODO: move all the following filtering logic to filters
                        if ( filterId == 'price' ) {
                            if ( !(this._data[itemId][priceIndex] >= this._filters[filterId]['0'] && this._data[itemId][priceIndex] <= this._filters[filterId]['1']) ) {
                                valid = false;
                                break;
                            }
                        } else if ( filterId == 'category' ) {
                            if ( !this._filterCategory( itemId ) ) {
                                valid = false;
                                break;
                            }
                        } else {
                            // base check for item presence in attributes index
                            if ( !this._filterAttribute( itemId, filterId ) ) {
                                valid = false;
                                break;
                            }
                        }

                    }
                    if ( valid ) {
                        this._filteredData.push( itemId );
                    }
                }
            } else {
                this._filteredData = this._idsIndex.clone();
            }
            this._filtersChanged = false;
        }
        return this;
    },

    /**
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    _applyOrder: function( force ) {
        if ( this._orderChanged || force ) {
            this._filteredData.sort( function( item1Id, item2Id ){
                var index = this._fields[this._order],
                    value1 = this._data[item1Id][index],
                    value2 = this._data[item2Id][index],
                    result = 0;

                if ( value1 == value2 ) {
                    value1 = parseInt(item1Id);
                    value2 = parseInt(item2Id);
                } else if ( value1 !== null && value2 !== null && !isNaN(value1) && !isNaN(value2)) {
                    value1 = parseFloat(value1);
                    value2 = parseFloat(value2);
                }

                if ( value1 > value2 ) {
                    result = this._orderDir;
                } else {
                    result = -this._orderDir;
                }

                return result;
            }.bind(this));

            this._orderChanged = false;
        }
        return this;
    },

    /**
     * Apply page and populate
     *
     * @return {Aitoc_Lnp_Core_Data_Collection}
     */
    _updateItems: function() {
        var pagedItems = [];
        if ( this._limit && this._filteredData.length > this._limit ) {
            var startIndex = this._limit * (this._page - 1),
                endIndex = startIndex + this._limit;
            pagedItems = this._filteredData.slice( startIndex, endIndex );
        } else {
            pagedItems = this._filteredData;
        }

        this._items = [];
        for ( var i = 0; i < pagedItems.length; i++ ) {
            var newItem = new Aitoc_Lnp_Core_Data_Collection_Item(),
                itemId = pagedItems[i];
            newItem.setData( this._data[itemId] )
                   .setFields( this._fields );
            this._items.push( newItem );
        }
        return this;
    },

    /**
     * @return {number}
     */
    getAllItemsCount: function() {
        return this._filteredData.length;
    },

    // -------- FILTERS' UPDATERS ------- //
    // TODO: move all the following updating logic to filters

    /**
     * Update all filters' options with appropriate quantities
     *
     * @param {Object} filters
     */
    updateFilters: function( filters ) {
        var fields = this._fields,
            priceFilterValue = filters['price'].getValue(),
            minPrice = 999999999,
            maxPrice = 0;

        for ( var name in filters ) {
            filters[name].resetOptionsCounts();
        }

        for ( var i = 0; i < this._idsIndex.length; i++ ) {
            var itemId = this._idsIndex[i],
                item = this._data[itemId],
                itemPrice = item[fields['min_price']],
                valid = true;

            // calculate price filter min and max values
            for ( var id in this._filters ) {
                if ( id == 'category' ) {
                    if ( !this._filterCategory( itemId ) ) {
                        valid = false;
                        break;
                    }
                } else if ( id != 'category' && id != 'price' && !this._filterAttribute( itemId, id ) ) {
                    valid = false;
                    break;
                }
            }
            if ( valid ) {
                minPrice = Math.min( itemPrice, minPrice );
                maxPrice = Math.max( itemPrice, maxPrice );
            }

            if ( priceFilterValue.length && (itemPrice < priceFilterValue[0] || itemPrice > priceFilterValue[1]) ) {
                // an item is out of price borders - pass
                continue;
            }

            // process all filters except categories fiilter
            for ( var name in filters ) {
                if( name == 'category' || name == 'price' ) {
                    continue;
                }

                var filter   = filters[name],
                    filterId = filter.getId();

                valid = true;

                // we do not need to count this item if it does not present in index
                // or does not have connection to current filter
                if ( this._index[itemId] && this._index[itemId][filterId] ) {
                    for ( var id in this._filters ) {
                        if ( id == 'category' ) {
                            if (!this._filterCategory( itemId )) {
                                valid = false;
                                break;
                            }
                        } else if ( id != filterId && id != 'price' && !this._filterAttribute( itemId, id ) ) {
                            // we should not apply current filter to prevent zero values for non-selected options
                            valid = false;
                            break;
                        }
                    }

                    // if item passed a filtration - increment counts for each matching option
                    if ( valid ) {
                        var matchingOptions = this._index[itemId][filterId];
                        filter.incrementOptionsCounts( matchingOptions );
                    }
                }
            }

            // process categories filter
            if ( filters['category'] != undefined ) {
                valid = true;
                for ( var id in this._filters ) {
                    if ( id != 'category' && id != 'price' && !this._filterAttribute( itemId, id ) ) {
                        valid = false;
                        break;
                    }
                }

                // if item passed a filtration - increment counts for each matching category
                if ( valid ) {
                    var matchingOptions = this._categoriesIndex[itemId];
                    filters['category'].incrementOptionsCounts( matchingOptions );
                }
            }
        }

        // update price filter
        filters['price'].setMinPrice( minPrice );
        filters['price'].setMaxPrice( maxPrice );
    },

    /**
     * @param {string} itemId
     * @return {boolean}
     * @private
     */
    _filterCategory: function( itemId ) {
        var valid = false;
        if ( this._categoriesIndex[itemId] ) {
            var intersect = this._categoriesIndex[itemId].intersect(this._filters['category']);
            if ( intersect.length > 0 ) {
                valid = true
            }
        }
        return valid;
    },

    _filterAttribute: function( itemId, filterId ) {
        return ( this._index[itemId] && this._index[itemId][filterId] &&
            this._filters[filterId].intersect( this._index[itemId][filterId] ).length > 0 );
    }
});