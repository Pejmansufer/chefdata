
/**
 * True AJAX Navigation Filter
 *
 * @category:    Aitoc
 * @package:     Aitoc_Lnp
 * @version      1.0.3
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
/**
 * This file contains prototype fixes related to Internet Explorer 11
 */
Prototype.Browser.IE11 = /Trident\/7/.test(navigator.userAgent);

Ajax.Response.prototype.initialize = function(request){
    this.request = request;
    var transport  = this.transport  = request.transport,
        readyState = this.readyState = transport.readyState;

     // IE11 Fix: in IE11 String.interpret() dramatically slows Ajax requests processing if there's a large response
     if ((readyState > 2 && !Prototype.Browser.IE && !Prototype.Browser.IE11) || readyState == 4) {
     this.status       = this.getStatus();
     this.statusText   = this.getStatusText();
     this.responseText = String.interpret(transport.responseText);
     this.headerJSON   = this._getHeaderJSON();
     }

    if (readyState == 4) {
        var xml = transport.responseXML;
        this.responseXML  = Object.isUndefined(xml) ? null : xml;
        this.responseJSON = this._getResponseJSON();
    }
};