
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var Aitoc_Lnp_Layered_Renderer_Category = Class.create( Aitoc_Lnp_Layered_Renderer_Abstract, {

    initialize: function() {
        this._wrapperTemplate =
            '<dt id="lnp_filter_title_{{name}}">{{title}}{{remove}}</dt>' +
                '<dd id="lnp_filter_{{name}}" class="lnp_filter_category" >' +
                '<ol>{{options}}</ol>' +
                '</dd>';
    },

    render: function() {
        if ( this._config.settings.theme == 'ultimo' ) {
            return '';
        }

        this._beforeRender();

        var category = this._filter.getCategories(),
            optionsHtml = this._renderCategories( category ),
            wrapperTemplate = new Template( this._wrapperTemplate, Aitoc_Lnp.TEMPLATE_SYNTAX);

        var html = '';
        if ( optionsHtml != '' ) {
            html = wrapperTemplate.evaluate({
                options: optionsHtml,
                name : this._filter.getName(),
                title: this._filter.getTitle(),
                remove: this._filter.getValue().length ? '<a href="#" class="btn-remove"></a>' : ''
            });
        }

        var transport = { html: html };
        this._afterRender( transport );

        return transport.html;
    },

    _renderCategories: function( category ) {
        var html = '',
            level = category.getLevel(),
            index = category.getIndex(),
            children = category.getChildren(),
            filterValue = this._filter.getValue(),
            currentLevel = filterValue.length;
        for ( var i = 0; i < index.length; i++ ) {
            var subCategory = children[index[i]];
            //if ( level < currentLevel ||  options[i][2] > 0 ) {
                html += '<li>';
                var selected = ( filterValue.indexOf( subCategory.getId() ) !== -1 );
                var opened = ( subCategory.getRecursiveIndex().indexOf( filterValue[0] ) !== -1 );
                if ( subCategory.getItemsQty() ) {
                    if ( selected ) {
                        html += '<a class="aitoc_selected_category" href="#" rel="value:' + subCategory.getId() + '">' +'<strong>' + subCategory.getTitle() + '</strong>';
                    } else {
                        html += '<a href="#" rel="value:' + subCategory.getId() + '">' + subCategory.getTitle();
                    }
                } else {
                    html += '<a class="aitoc_selected_category" href="#" rel="value:' + subCategory.getId() + '">' +subCategory.getTitle();
                }
                html +=' <span class="count"> (' + subCategory.getItemsQty() + ')</span>' + '</a>';

                if ( (selected || opened) && subCategory.hasChildren() ) {
                    html += '<ol class="level' + (level+1) + '">';
                    html += this._renderCategories( subCategory );
                    html += '</ol>';
                }

                html += '</li>';
            //}
        }
        return html;
    }

});