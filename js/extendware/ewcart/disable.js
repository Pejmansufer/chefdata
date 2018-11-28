EWCart = Class.create(EWCart, {
	rewritePage: function($super) {}
});
setLocation=function(url){
    window.location.href=url;
};

setPLocation=function(url,setFocus){
	if(setFocus) window.opener.focus();
	window.opener.location.href = url;
};