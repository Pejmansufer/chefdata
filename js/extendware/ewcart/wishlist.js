var EWCart = Class.create(EWCart, {
	rewritePage: function ($super) {
		$$('div.my-account > div.my-wishlist a').each(function(e) {
			var rw = 0;
			if (e.href.indexOf('/wishlist/index/remove/') > 0) {
				this.rewriteForProcess('dwishlist', e);
				rw = 1;
			}
			if (rw) $(e).writeAttribute('href', 'javascript:ewcart.void(0);');
		}.bind(this));
	
		$super();
	},
	
	rewriteUrl: function($super, url) {
  		url = $super(url);
		if (url.indexOf('/ewcart/wishlist/') > 0) {
			url += (url.indexOf('?') > 0) ? '&' : '?';
			url += '__source=wishlist';
		}
		return url;
	}
});