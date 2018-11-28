EWCart = Class.create(EWCart, {
	rewriteUrl: function($super, url) {
  		url = $super(url);
		if (url.indexOf('/ewcart/cart/') > 0) {
			url += (url.indexOf('?') > 0) ? '&' : '?';
			url += '__source=cart';
		}
		return url;
	}
});