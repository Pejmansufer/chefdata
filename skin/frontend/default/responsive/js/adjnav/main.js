
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavHash = {
    set: function() {
        if(!isProcessHashChange) {
            isProcessHashChange = true;
            return false;
        }

        var hash = jQuery.param.fragment();
        if (0 != hash.indexOf('!/') && !wasUrlHashed) {
            return false;
        }

        // shop by brands compatibility
        if (hash.indexOf('shopby_attribute') > -1) {
            return false;
        }

        var hashParams = jQuery.deparam(hash.substr(2));
        var params = $(nav_params).value.parseQuery();
        if (typeof(params.q) != 'undefined' && typeof(hashParams.q) == 'undefined') {
            var urlParams = window.location.search.parseQuery();
            if (typeof(urlParams.q) != 'undefined') {
                //preserving search query if hash was cleared, but it still in URI
                hashParams.q = params.q;
            }
        }

        jQuery('#adj-nav-params').val(jQuery.param(hashParams));

        canChangeLocationHash = false;
        adjnavNavigation.makeRequest();
        canChangeLocationHash = true;
    }
}

jQuery(document).ready(AdjnavHash.set);
jQuery(window).bind('hashchange', AdjnavHash.set);


var canChangeLocationHash   = true;
var adjnavPageLoadInProcess = false;
var isProcessHashChange     = true;
var wasUrlHashed            = false;
var nav_params              = 'adj-nav-params';
var nav_url                 = 'adj-nav-url';
var nav_ajaxurl             = 'adj-nav-ajax';

if ('function' == typeof(sns_layer_add_attr)) {
    sns_layer_add_attr();
}