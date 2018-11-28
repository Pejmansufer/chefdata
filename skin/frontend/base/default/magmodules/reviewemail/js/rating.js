function toggleReview(item) {
	$('summary_field_' + item).toggleClassName('required-entry');
	$('review_field_' + item).toggleClassName('required-entry');		
	new Effect.toggle('item-' + item, 'Appear', {duration: 0.5});
};		

function toggleReviewOnly(item) {
	new Effect.toggle('item-' + item, 'Appear', {duration: 0.5});
};		

function toggleShopreview(item) {
	new Effect.toggle(item, 'Appear', {duration: 0.5});
}

if(navigator.userAgent.indexOf('Mac') > 0 && navigator.userAgent.indexOf('Firefox') > 0) {
	document.write ('<style type=text/css> .rating:not(:checked) > label{font-size:225%!important;} .rating > span{top:12px!important;}</style>');
}

if(navigator.userAgent.indexOf('Safari') > 0) {
	document.write ('<style type=text/css> .rating:not(:checked) > label{width: 19px!important; font-size:150%!important;} .rating > span{top:0px!important;}</style>');
}