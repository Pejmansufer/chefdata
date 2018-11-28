/**
 * Add In Mage::
 *
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the EULA at http://add-in-mage.com/support/presales/eula-community/
 *
 *
 * PROPRIETARY DATA
 * 
 * This file contains trade secret data which is the property of Add In Mage:: Ltd. 
 * Information and source code contained herein may not be used, copied, sold, distributed, 
 * sub-licensed, rented, leased or disclosed in whole or in part to anyone except as permitted by written
 * agreement signed by an officer of Add In Mage:: Ltd. 
 * A separate installation package must be downloaded for each new Magento installation from Add In Mage web site.
 * You may modify the source code of the software to get the functionality you need for your store. 
 * You must retain, in the source code of any Derivative Works that You create, 
 * all copyright, patent, or trademark notices from the source code of the Original Work.
 * 
 * 
 * @category    AddInMage
 * @package     js
 * @copyright   Copyright (c) 2012 Add In Mage:: Ltd. (http://www.add-in-mage.com)
 * @license     http://add-in-mage.com/support/presales/eula-community/  End User License Agreement (EULA)
 * @author      Add In Mage:: Team <team@add-in-mage.com>
 */

Effect.MoveUp = Class.create();
Object.extend(Object.extend(Effect.MoveUp.prototype, Effect.Base.prototype), {
  initialize: function(element) {
    this.element = $(element);
    if(!this.element) throw(Effect._elementDoesNotExistError);
    var options = Object.extend({
      x:    0,
      y:    0,
      mode: 'relative'
    }, arguments[1] || {});
    this.start(options);
  },
  setup: function() {
    this.element.makePositioned();
    this.originalLeft = parseFloat(this.element.getStyle('left') || '0');
    this.originalBottom  = parseFloat(this.element.getStyle('bottom')  || '0');
    if(this.options.mode == 'absolute') {
      this.options.x = this.options.x - this.originalLeft;
      this.options.y = this.options.y - this.originalBottom;
    }
  },
  update: function(position) {
    this.element.setStyle({
      left: Math.round(this.options.x  * position + this.originalLeft) + 'px',
      bottom:  Math.round(this.options.y  * position + this.originalBottom)  + 'px'
    });
  }
});

///////////////////////////////////////////////////////////////////////////////////////


(function(){
var toastNotificationsOptions = {};
var notificationOptions = {
	position: 			top,
	useClose: 			false,
	delay: 				5,
	appearance: 		0.4,
	disappearance: 		0.5,
	appearanceShift: 	{y:+40},
	disappearanceShift: {y:-40},
	opacity:			0.85,
	className: 			""
};


function getTnApShift(options){
	
	switch(options.position){
		case "top":
			return {y:+40};
		case "bottom":
			return {y:+71};
	}
}

function getTnDesapShift(options){
	
	switch(options.position){
		case "top":
			return {y:-40};
		case "bottom":
			return {y:-71};
	}
}

function removeNotification(nb, o){
	o = o || notificationOptions;
	
	switch(o.position){
		case "top":
			new Effect.Parallel([
			    new Effect.Move(nb, Object.extend({ sync: true, mode: 'relative' }, getTnDesapShift(o))),
			    new Effect.Opacity(nb, { sync: true, to: 0 }) 
			], {
					duration: o.disappearance,
					afterFinish: function(){
					try {
						var ne = nb.down("div.notification-bar-close");
						if(ne != undefined){
							ne.stopObserving("click", removeNotification);
						}
						if(o.created && Object.isFunction(o.created)){
							nb.stopObserving("notification:created", o.created);
					    }
					    if(o.destroyed && Object.isFunction(o.destroyed)){
					    	nb.fire("notification:destroyed");
					        nb.stopObserving("notification:destroyed", o.destroyed);
					    }
					} catch(e){}
					try {
						nb.remove();
					} catch(e){}
				}
			});
			break;
		case "bottom":
			new Effect.Parallel([
			   new Effect.MoveUp(nb, Object.extend({ sync: true, mode: 'relative' }, getTnDesapShift(o))),
			   new Effect.Opacity(nb, { sync: true, to: 0 }) 
			], {
				duration: o.disappearance,
				afterFinish: function(){
				try {
					var ne = nb.down("div.notification-bar-close");
					if(ne != undefined){
						ne.stopObserving("click", removeNotification);
					}
					if(o.created && Object.isFunction(o.created)){
						nb.stopObserving("notification:created", o.created);
				    }
				    if(o.destroyed && Object.isFunction(o.destroyed)){
				    	nb.fire("notification:destroyed");
				        nb.stopObserving("notification:destroyed", o.destroyed);
				    }
				} catch(e){}
				try {
					nb.remove();
				} catch(e){}
			}
		});
		break;
	}
}
function createNotification(toastNotification, msg, options){
	var opt = Object.clone(notificationOptions);
	options = options || {};
	Object.extend(opt, options);
	var notification;
	
	notification = new Element("div").addClassName('notification-bar-wrapper').setStyle({display: "block", opacity: 0});
	if (opt.className != ""){
		notification.addClassName(opt.className);
	}
	
	notification.addClassName('tn-position-' + opt.position);
	
	if(opt.created && Object.isFunction(opt.created)){
		notification.observe("notification:created", opt.created);
	}
	if(opt.destroyed && Object.isFunction(opt.destroyed)){
		notification.observe("notification:destroyed", opt.destroyed);
	}
	
	if (opt.useClose){				
		var notificationClose = new Element("div").addClassName('notification-bar-close').update("&times;");
		notificationClose.observe("click", function(){ removeNotification(notification, opt); });
		notification.insert(notificationClose);
	}
	notification.insert(new Element("div").addClassName('notification-bar-notice').update(msg));
	toastNotification.insert(notification);	
	
	switch(opt.position){
		case "top":
			new Effect.Parallel([
			   new Effect.Move(notification, Object.extend({ sync: true, mode: 'relative' }, getTnApShift(opt))),
			   new Effect.Opacity(notification, {sync: true, to: opt.opacity}) 
			], {duration: opt.appearance});
			break;
		case "bottom":
			new Effect.Parallel([
			   new Effect.MoveUp(notification, Object.extend({ sync: true, mode: 'relative' }, getTnApShift(opt))),
			   new Effect.Opacity(notification, {sync: true, to: opt.opacity}) 
			], {duration: opt.appearance});
			break;
	}
	
	
	if (!opt.useClose){
		removeNotification.delay(opt.delay, notification, opt);
	}
	notification.fire("notification:created");
	return notification;
}
ToastNotification = Class.create({
	initialize: function(options){
		var opt = Object.clone(toastNotificationsOptions);
		options = options || {};
		Object.extend(opt, options);
		this.toastNotification = new Element("div", {"id": "notification-bar"}).addClassName('notification-bar');
		this.toastNotification.wrap( document.body );		
	},
	showNotification: function(msg, options) {
		return createNotification(this.toastNotification, msg, options);
	},
	hideNotification: function(n, o){
		removeNotification(n, o);
	}
});
})();
