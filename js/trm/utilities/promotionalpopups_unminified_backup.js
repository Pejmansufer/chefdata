		// init vars
		var forcedpopups = "";
		var trmexitpopups = "";

		var cookievalue = "";
		var cookieexpiry = "";
		var promotionalpopup_id = "";
		var popuptitle = "";
		var popupwidth = "";
		var popupheight = "";
		var popupuntildate = "";
		var popupstyles = "";
		var backgroundimage = "";
		var backgroundcolor = "";
		var autoclosedelay = "";
		
		var modalbackground = "";
		var modalvideomp4 = "";
		var modalvideoogv = "";
		var modalvideoloop = ""; 
		var closechainedpopup_id = "";
		var conversionchainedpopup_id = "";
		var chainedstatus = 0;
		var css_reset = "";
		
		
		///
		
		popupCloseType = "standardClose"; 
		
		var magentoPopupBox = Class.create({
		open : function () {
			
			this._fade('open', this.container);
		},
		
		close : function () {
			this._fade('close', this.container);
		},
		
		_fade : function fadeBg(userAction,whichDiv){
			if(userAction=='close'){
				new Effect.Opacity('backgroundFade',
						   {duration:fadeoutduration,
							from:modalopacity,
							to:0,
							afterFinish:this._makeInvisible,
							afterUpdate:this._hideLayer(whichDiv)});
			}else{
				new Effect.Opacity('backgroundFade',
						   {duration:fadeinduration,
							from:0,
							to:modalopacity,
							beforeUpdate:this._makeVisible,
							afterFinish:this._showLayer(whichDiv)});
			}
		},
		
		_makeVisible : function makeVisible(){
			$("backgroundFade").style.visibility="visible";
			$("magentoPopupContainer").style.visibility="visible";
			if(modalclickclose == '1'){
			
			$('backgroundFade').observe('click', function(event) { autoClosePopup();checkForVideo();
			 });
			}
		},
	
		_makeInvisible : function makeInvisible(){
			$("backgroundFade").style.visibility="hidden";
		},
	
		_showLayer : function showLayer(userAction){
			$(userAction).style.display="block";
		},
		
		_hideLayer : function hideLayer(userAction){
			$(userAction).style.display="none";
		},
		
		_centerWindow : function centerWindow(element) {
			var windowHeight = parseFloat($(element).getHeight())/2; 
			var windowWidth = parseFloat($(element).getWidth())/2;
	
			if(typeof window.innerHeight != 'undefined') {
				$(element).style.top = Math.round(document.body.offsetTop + ((window.innerHeight - $(element).getHeight()))/2)+'px';
				$(element).style.left = Math.round(document.body.offsetLeft + ((window.innerWidth - $(element).getWidth()))/2)+'px';
			} else {
				$(element).style.top = Math.round(document.body.offsetTop + ((document.documentElement.offsetHeight - $(element).getHeight()))/2)+'px';
				$(element).style.left = Math.round(document.body.offsetLeft + ((document.documentElement.offsetWidth - $(element).getWidth()))/2)+'px';
			}
		},
		
		initialize : function(containerDiv) {
			this.container = containerDiv;
			if($('backgroundFade') == null) {
				var screen = new Element('div', {'id': 'backgroundFade'});
				document.body.appendChild(screen);
			}
			
			
			this._hideLayer(this.container);
		}
	});


///

// BOF Retrieve Promotional Pop-up

			
			function retrievePromotionalPopup() {
				
			var curbrowserwidth = getBrowserWidth();		
				
			new Ajax.Request(loadpopupurl, {
			  method:'post',
			  parameters: { cookies: document.cookie, forcepopup: forcedpopups, previewpopup: previewid, browserwidth: curbrowserwidth },
			  onSuccess: function(transport) {
				var response = transport.responseText;
				var popupreturn = response.evalJSON();
				
				cookievalue = popupreturn['cookie'];
				// check for session cookie
				if(popupreturn['cookie_expiry'] != '0'){
				cookieexpiry = popupreturn['cookie_expiry'];
				}
				
				promotionalpopup_id = popupreturn['promotionalpopup_id'];
				popuptitle = popupreturn['popuptitle'];
				popupwidth = popupreturn['width'];
				popupheight = popupreturn['height'];
				popupuntildate = popupreturn['untildate'];
				backgroundimage = popupreturn['filename'];
				backgroundcolor = popupreturn['background_color'];
				template = popupreturn['template'];
				timestatus = popupreturn['timestatus'];
				autoclosedelay = popupreturn['delay'];
				
				if(popupreturn['modal_color'] != ""){
				modalcolor = popupreturn['modal_color'];
				}
				if(popupreturn['modal_opacity'] != ""){
				modalopacity = popupreturn['modal_opacity'];
				}
				if(popupreturn['modal_background'] != ""){
				modalbackground = popupreturn['modal_background'];
				}
				modalvideomp4 = popupreturn['modal_video_mp4'];
				modalvideoogv = popupreturn['modal_video_ogv'];
				modalvideoloop = popupreturn['modal_video_loop'];
				
				closechainedpopup_id = popupreturn['closechainedpopup_id'];
				conversionchainedpopup_id = popupreturn['conversionchainedpopup_id'];
				chainedstatus = 1;
				
				css_reset = popupreturn['css_reset'];
				
				var popupmarginwidth = parseInt(popupwidth) / 2;
				var popupmarginheight = parseInt(popupheight) / 2;
				
				
				// BOF popup styles 
				
				// BOF backgroundSetup
				
				if(backgroundimage != "" || backgroundcolor != "") {
				var backgroundSetup = 'background:';
				  
				if(backgroundimage != "") { backgroundSetup += 'url('+ basemediaurl + backgroundimage + ') no-repeat'; }
				if(backgroundcolor != "") { backgroundSetup += ' '+backgroundcolor; }
				
				backgroundSetup += ';';
		
		
				}
				
				// BOF Modal Overrides
				
				//modal appearance and overrides
			
			
			
			//modal background image
			var modalbackgroundbuild = "";
			if (modalbackground != ""){
			modalbackgroundbuild = 'background-size:100% auto; background-image:url("'+basemediaurl+modalbackground+'");'; 
			}
			
				// EOF Modal Overrides
				
				// EOF backgroundSetup
				
				popupstyles = '<style>#backgroundFade { background-color:'+modalcolor+'; '+modalbackgroundbuild+' height:140%; left:0px; margin:0px; padding:0px; position:fixed; top:0px; visibility:hidden; width:100%; z-index:160000; opacity:'+modalopacity+'; }';
			popupstyles += '#videoBackground { overflow: hidden; position: absolute; top: 0; width: 100%; z-index: 160001; }';
			popupstyles += '#closeLink { font-size:16px; text-decoration:none; cursor:pointer; color:#fff; }'; 
			popupstyles += '#magentoPopupContainer  { position: fixed; '+backgroundSetup+' width: '+popupwidth+'px; height: '+popupheight+'px; z-index: 160010; top:50%; left:50%; margin-left:-'+popupmarginwidth+'px;  margin-top:-'+popupmarginheight+'px; visibility:hidden; }';
				popupstyles += popupreturn['styles'];
				popupstyles += '</style>';
				
				$('popupstyles').update(popupstyles);
				// EOF popup styles
				
				if(css_reset == '1') {
   					$('magentoPopupContainer').addClassName("trm-pp-resetcss");
				}
				$('magentoPopupContainer').update(popupreturn['content']);
				
				// BOF popup template //
				
				if (template != 'blank_no_close'){
					$('magentoPopupContainer').insert(popupreturn['default_close_button']);
				}
				
				// EOF popup template //
				
				// initalize widgets
				widgetTypeInit();
				magentoPopup = new magentoPopupBox('magentoPopupContainer');	
				
				openPopupDelay.delay(parseInt(timestatus));
				
			  },
			  onFailure: function() { /*alert('Request Failed');*/ }
			});
			
			}
			
			
			
			document.observe("dom:loaded", function() {
			// Get all forced pop-ups 
			// Get popup forced through URL parameter
			urlForced = trmGetURLParameter('forcepopup');
			
			if(urlForced) {
				forcedpopups	+= urlForced + '|';
			}
			
			
			// Get exit pop-ups from embedded widgets
				
			$$('.promotionalExitPopups').each(function(e){ trmexitpopups += $(e).value + '|'; hasexitpopup = true; }.bind(this));
			
			// Get forced pop-ups from embedded widgets	
			$$('.promotionalPopupsForced').each(function(e){ forcedpopups += $(e).value + '|'; }.bind(this));
			
			//console.log(trmexitpopups);
			//
			retrievePromotionalPopup();
			});
			// EOF Retrieve Promotional Pop-up
			

////


			
				 
			 function openPopupDelay(){
				 
				 var dimensionCheck = disableByDimension(disablepopupbelow);
				 
				 
				 if((!cookieGetTRM(cookievalue)||previewid != "")&&!dimensionCheck){
				 
				 magentoPopup.open();
					 if(previewid == ""){
					 	cookieSetTRM(cookievalue, phptime(), cookieexpiry, popupcookieconfigpath);
					 }
				 new Ajax.Updater('popupviewed', viewpopupurl, {
     			parameters: { id: promotionalpopup_id }
   				});
				
				// BOF GA View Tracking
				
				// Universal Analytics tracking
					 
					
					
					if (popupSendToAnalytics == 'GAUniversal'){
					ga('set', 'nonInteraction', false);
					ga('send', 'event', { eventCategory: popupgaeventcategory, eventAction: popupgavieweventaction, eventLabel: popuptitle, eventValue: 1});
 
					}
					// Classic Analytics Tracking
					 
					if (popupSendToAnalytics == 'GAClassic'){
					
					try {
        			
        			// Google Analytics asynchronus code
					_gaq.push(['_trackEvent', popupgaeventcategory, popupgavieweventaction, popuptitle, 1, false ]);

    				} catch(err) { }
					
					}
					// Google Tag Manager
					if (popupSendToAnalytics == 'GTM'){
					dataLayer.push({'event': 'trackEvent', 'eventCategory': popupgaeventcategory, 'eventAction': popupgavieweventaction, 'eventLabel': popuptitle, 'eventValue':'1' });
					}
				
				// EOF GA View Tracking
				
				
				// BOF modal video background //
				if((modalvideomp4 != "") || (modalvideoogv != "")){
				
				var video = new Videoplayer(basemediaurl+modalvideomp4,basemediaurl+modalvideoogv, modalvideoloop);
				}
				// EOF modal video background //
				
				// BOF autoclose delay //
				if (autoclosedelay != '0'){
				
				autoClosePopup.delay(parseInt(autoclosedelay));
				checkForVideo.delay(parseInt(autoclosedelay));
				}
				
				 }
				// EOF autoclose delay //
				
			 }
			
			function autoClosePopup() {
				magentoPopup.close();
				if(popupCloseType == "standardClose" && closechainedpopup_id && closechainedpopup_id != "0") { clickOpenPopup( closechainedpopup_id ); }
				if(popupCloseType == "conversionUpdate" && conversionchainedpopup_id && conversionchainedpopup_id != "0") { clickOpenPopup( conversionchainedpopup_id ); }
				
				 
				}
			
			function checkForVideo() {
				if(typeof stopVideo == 'function') { stopVideo(); }	
				}
				
			function promotionalPopupConversion(autocloseValue, trackconversion, conversionexpiry, conversionsuccesslabel) {
				// check if new expiry should be used
				if(conversionexpiry != 'nochange') {
				popupCloseType = 'conversionUpdate';
				//ajax updated call goes here
					if(previewid == ""){
						cookieSetTRM(cookievalue, phptime(), conversionexpiry, popupcookieconfigpath)
					}
				}
				
				// check if conversion should be tracked
				if(trackconversion == 'yes') {
				//ajax updated call 
				new Ajax.Updater('popupconversion', conversionpopupurl, {
     			parameters: { id: promotionalpopup_id }
   				});
				
					// Universal Analytics tracking
					if (popupSendToAnalytics == 'GAUniversal'){
					ga('set', 'nonInteraction', false);
					ga('send', 'event', { eventCategory: popupgaeventcategory, eventAction: popupgaconversioneventaction, eventLabel: popuptitle + conversionsuccesslabel, eventValue: 1});
 
					}
					// Classic Analytics Tracking
					 
					if (popupSendToAnalytics == 'GAClassic'){
					
					try {
        			
        			// Google Analytics asynchronus code
					_gaq.push(['_trackEvent', popupgaeventcategory, popupgaconversioneventaction, popuptitle, 1, false ]);

    				} catch(err) { }
					
					}
					// Google Tag Manager
					if (popupSendToAnalytics == 'GTM'){
					dataLayer.push({'event': 'trackEvent', 'eventCategory': popupgaeventcategory, 'eventAction': popupgaconversioneventaction, 'eventLabel': popuptitle + conversionsuccesslabel, 'eventValue':'1' });
					}
					
					
				
				}
				
				//setup autoclose on success
				if(autocloseValue != 'no') {
					if(autocloseValue == 'instant') { autoClosePopup(); }
				autoClosePopup.delay(autocloseValue);
			    checkForVideo.delay(autocloseValue);
				}
				
			}

////			


function widgetTypeInit(){
	//BOF initalize countdown
	if(document.getElementById("promotionalPopupsCountdown")) {
		
		var countdate = popupuntildate;
		if($('promotionalPopupsCountdown').readAttribute('countdate') != "") { countdate =  $('promotionalPopupsCountdown').readAttribute('countdate'); }	
		
		var storedate =  $('promotionalPopupsCountdown').readAttribute('storedate');
		
		promotionalPopupCountdown(countdate, storedate);
		
	}
	//EOF initalize countdown
	//BOF initalize popuplinks	
	if(document.getElementById("promotionalpopupLink")) {
		$('promotionalpopupLink').observe('click', function(event) { 
			var previouslyClicked = false;
			if(!previouslyClicked) {
				previouslyClicked = true;
				promotionalpopupForwardTo($('promotionalpopupLink').readAttribute('rel')).delay(500);
			}
		});	 
	}
	//EOF initalize popuplinks
	// BOF Initalize Youtube player
	
	if(document.getElementById("promotionalpopup-player")) {
		
	var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	  
	  

     
      var player;
      
	  
	}
		// EOF Initalize Youtube player	
		
			 	
}


function clearSubText(theField){
if (theField.defaultValue == theField.value)
theField.value = '';
}
function addSubText(theField) {
if (theField.value == '')
theField.value = theField .defaultValue;
} 

// BOF General Ajax Subscribe //
function subscribeFunctionPromotionalPopup()
		{	
			
			var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail-promotional-popup');
			var form_email = $('popup-newsletter').getValue();
			
			var autoclosesuccess = $('popup-autoclosesuccess').getValue();
			var tracksubconversion = $('popup-tracksubconversion').getValue();
			var subsuccessexpiry = $('popup-subsuccessexpiry').getValue();
			var conversionsuccesslabel = $('popup-conversionsuccesslabel').getValue();
			if(conversionsuccesslabel != "") conversionsuccesslabel = "("+conversionsuccesslabel+")";
			var subscriberurl = $('popup-subscriberurl').getValue();
			
			var emailsuccesstext = $('popup-emailsuccesstext').getValue();
			var noguest = $('popup-noguest').getValue();
			var customeremail = $('popup-customeremail').getValue();
			var noduplicates = $('popup-noduplicates').getValue();
			var curResponse;
			
			var sendemail = $('popup-sendemail').getValue();
			var couponid = $('popup-couponid').getValue();
			var couponlength = $('popup-couponlength').getValue();
			var emailtemplate = $('popup-emailtemplate').getValue();
			
			var params_form = $('newsletter-validate-detail-promotional-popup');
			//alert(params_form);
			new Validation('popup-newsletter');
			if(validateEmailFunction(form_email))
			{
			//alert(form_email);
			//alert(Form.serialize($('newsletter-validate-detail')));
			new Ajax.Updater({ success: 'newsletter-validate-detail-promotional-popup' }, subscriberurl, {
				asynchronous:true, evalScripts:false,
				parameters: { email: form_email, emailstatus: sendemail, ruleid: couponid, codelength: couponlength, template: emailtemplate },
				onSuccess:function(transport){ curResponse = transport.responseText;  },
				onComplete:function(request, json){
					Element.hide('newsletter-validate-detail-promotional-popup'); 
					Element.hide('promotional-popup-loader');
					
					
					
					switch(curResponse)
						{
						case '0':
						  //$('magentoPopupContainer').insert(noguest);
						   //Element.show('promotional-popup-feedback');
						  break;
						case '1':
						  $('promotional-popup-feedback').insert(noguest);
						   Element.show('promotional-popup-feedback');
						  break;
						case '2':
							$('promotional-popup-feedback').insert(customeremail);
						   Element.show('promotional-popup-feedback'); 
						  break;
						case 'noduplicates':
							$('promotional-popup-feedback').insert(noduplicates);
						   Element.show('promotional-popup-feedback'); 
						  break;    
						default:
						$('promotional-popup-feedback').insert(emailsuccesstext);
						  Element.show('promotional-popup-feedback'); 
						onSignupSuccess(autoclosesuccess, tracksubconversion, subsuccessexpiry, conversionsuccesslabel);
						}
					 
					
					},
				onLoading:function(request, json){Element.show('promotional-popup-loader'); Element.hide('newsletter-validate-detail-promotional-popup');},
				//onFailure: function(){ alert('Something went wrong...') }
				
			});

				 
				 
			}
			else
			{
			//alert(form_email);
				return false;
			}

		}	
// EOF General Ajax Subscribe //

// BOF Terms Ajax Subscribe //

function subscribeTermsFunctionPromotionalPopup()
		{	
			
			
			var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail-promotional-popup');
			var form_email = $('popup-newsletter').getValue();
			
			var autoclosesuccess = $('popup-autoclosesuccess').getValue();
			var tracksubconversion = $('popup-tracksubconversion').getValue();
			var subsuccessexpiry = $('popup-subsuccessexpiry').getValue();
			var conversionsuccesslabel = $('popup-conversionsuccesslabel').getValue();
			if(conversionsuccesslabel != "") conversionsuccesslabel = "("+conversionsuccesslabel+")";
			var subscriberurl = $('popup-subscriberurl').getValue();
			
			var emailsuccesstext = $('popup-emailsuccesstext').getValue();
			var noguest = $('popup-noguest').getValue();
			var customeremail = $('popup-customeremail').getValue();
			var noduplicates = $('popup-noduplicates').getValue();
			var curResponse;
			
			var sendemail = $('popup-sendemail').getValue();
			var couponid = $('popup-couponid').getValue();
			var couponlength = $('popup-couponlength').getValue();
			var emailtemplate = $('popup-emailtemplate').getValue();
			
			var params_form = $('newsletter-validate-detail-promotional-popup');
			//alert(params_form);
			new Validation('popup-newsletter');
			if(validateEmailFunction(form_email))
			{
				// checks terms
				if(document.getElementById('popupterms').checked) {
				// EOF checks terms
			//alert(form_email);
			//alert(Form.serialize($('newsletter-validate-detail')));
			new Ajax.Updater({ success: 'newsletter-validate-detail-promotional-popup' }, subscriberurl, {
				asynchronous:true, evalScripts:false,
				parameters: { email: form_email, emailstatus: sendemail, ruleid: couponid, codelength: couponlength, template: emailtemplate },
				onSuccess:function(transport){ curResponse = transport.responseText;  },
				onComplete:function(request, json){
					Element.hide('newsletter-validate-detail-promotional-popup'); 
					Element.hide('promotional-popup-loader');
					
					
					
					switch(curResponse)
						{
						case '0':
						  //$('magentoPopupContainer').insert(noguest);
						   //Element.show('promotional-popup-feedback');
						  break;
						case '1':
						  $('promotional-popup-feedback').insert(noguest);
						   Element.show('promotional-popup-feedback');
						  break;
						case '2':
							$('promotional-popup-feedback').insert(customeremail);
						   Element.show('promotional-popup-feedback'); 
						  break;
						case 'noduplicates':
							$('promotional-popup-feedback').insert(noduplicates);
						   Element.show('promotional-popup-feedback'); 
						  break;     
						default:
						$('promotional-popup-feedback').insert(emailsuccesstext);
						  Element.show('promotional-popup-feedback'); 
						onSignupSuccess(autoclosesuccess, tracksubconversion, subsuccessexpiry, conversionsuccesslabel);
						}
					
					},
				onLoading:function(request, json){Element.show('promotional-popup-loader'); Element.hide('newsletter-validate-detail-promotional-popup');},
				//onFailure: function(){ alert('Something went wrong...') }
				
			});
			
			// checks terms

			} else {
				termsNotChecked();
			}	 
			// eof of checks terms
				 
			}
			else
			{
			//alert(form_email);
				return false;
			}
			
			

		}
		
		
		
		function termsNotChecked()
		{
		  Element.show('error-promotional-popup-terms-msg');
		  
		}	
		
// EOF Terms Ajax Subscribe //				
		
		
function validateEmailFunction(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   //alert("Invalid E-mail ID")
		   goProcedural()
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   //alert("Invalid E-mail ID")
		   goProcedural()
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    //alert("Invalid E-mail ID")
		    goProcedural()
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    //alert("Invalid E-mail ID")
		    goProcedural()
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    //alert("Invalid E-mail ID")
		    goProcedural()
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    //alert("Invalid E-mail ID")
		    goProcedural()
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    //alert("Invalid E-mail ID")
		    goProcedural()
		    return false
		 }

 		 return true					
	}
function goProcedural()
{
  Element.show('error-promotional-popup-msg');
  //Element.hide.delay(5, 'error-promotional-popup-msg');
}

function onSignupSuccess(autoclosesuccess, tracksubconversion, subsuccessexpiry, conversionsuccesslabel) {
//alert("successful signup");

promotionalPopupConversion( autoclosesuccess, tracksubconversion, subsuccessexpiry, conversionsuccesslabel );

 
}


// Countdown to Promotion Until Date
function promotionalPopupCountdown(newdate, currentdate){
	
var countdate = newdate;	
// set the date we're counting down to
var target_date = new Date(countdate).getTime();
var current_date = new Date(currentdate).getTime();
// variables for time units
var days, hours, minutes, seconds;
 
// get tag element
var countdown = document.getElementById("promotionalPopupsCountdown");
 
// update the tag with id "countdown" every 1 second
setInterval(function () {
 
    // find the amount of "seconds" between now and target
    
    var seconds_left = (target_date - current_date) / 1000;
 
    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
     
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
     
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
     
    // format countdown string + set tag value
    //countdown.innerHTML = days + "d, " + hours + "h, " + minutes + "m, " + seconds + "s";
	
	countdown.innerHTML = "<table class='countTable'><tr>" +
        "<td class='countNumbers'>" + days + "</td>" +
        "<td class='countNumbers'>" + hours + "</td>" +
        "<td class='countNumbers'>" + minutes + "</td>" +
        "<td class='countNumbers'>" + seconds + "</td>" +
		"</tr><tr>" +
		"<td class='countLabels'>" + (days == 1? "day" : "days") + "</td>" +
        "<td class='countLabels'>" + (hours == 1? "hour" : "hours") + "</td>" +
        "<td class='countLabels'>" + (minutes == 1? "min" : "mins") +"</td>" +
        "<td class='countLabels'>" + (seconds == 1? "sec" : "secs") +"</td>" +
		"</tr></table>";
		
	current_date+=1000;	  
 
}, 1000);

}
	

// EOF countdown
// make links in pop-up set cookie before following
function promotionalpopupForwardTo(url){
	window.location = url
	}
	
// BOF YouYube Player
var videoheight;
	  var videowidth;
	  var youtubeid;
	  var videoautoplay;

function onYouTubeIframeAPIReady() {
	
	
	  videoheight = $('promotionalpopup-player').readAttribute('videoheight');
	  videowidth = $('promotionalpopup-player').readAttribute('videowidth');
	  youtubeid = $('promotionalpopup-player').readAttribute('youtubeid');
	  videoautoplay = parseInt($('promotionalpopup-player').readAttribute('videoautoplay'));
	
        player = new YT.Player('promotionalpopup-player', {
          height: videoheight,
          width: videowidth,
          videoId: youtubeid,
		  playerVars: { 'rel': 0, 'showinfo': 0, 'autoplay': parseInt(videoautoplay), },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
		
		
		
      }
     
      function onPlayerReady(event) {
		  
      }

      
      var done = false;
      function onPlayerStateChange(event) {
        
      }
      function stopVideo() {
		if(document.getElementById("promotionalpopup-player")) {  
        	player.stopVideo();
		}
      }
// EOF YouTube Player	

// BOF Click to Open Pop-up
  function clickOpenPopup(getPopupId) {
		previewid = getPopupId;
		retrievePromotionalPopup();
}
// EOF Click to Open Pop-up
// BOF Javascript like php time
function phptime() {

  return Math.floor(new Date()
    .getTime() / 1000);
}
// EOF Javascript like php time

// BOF Get URL Parameters
function trmGetURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
}
//EOF Get URL parameters

//BOF Get browser width
function getBrowserWidth() {
	if (self.innerHeight) {
    return self.innerWidth;
	}
	if (document.documentElement && document.documentElement.clientHeight) {
    return document.documentElement.clientWidth;
	}
	if (document.body) {
    return document.body.clientWidth;
	}
}
//EOF Get browser width

//BOF Disable Pop-up By Dimension
function disableByDimension(disablebelow){
	
	// check to ensure variable is a number
	if(typeof disablebelow == "number"){
		if(getBrowserWidth() < disablebelow){
			return true;
		} else {
			return false;	
		}
		
	} else {
		// return false if disablebelow is NaN
		return false;	
	}
}
//EOF Disable Pop-up By Dimension

// BOF Exit Pop-up

// start countdown to retrieve exit pop-up
var hasexitpopup = false;
function initExitPopupTimer(exitPopupInitDelay){
	if(exitPopupInitDelay == "") { exitPopupInitDelay = 5000; }
	//console.log('init delay'+ exitPopupInitDelay);
	setTimeout(function() { initExitPopup() }, 1 * exitPopupInitDelay);
}
var hasDisplayedExitPopup = false;

			
function initExitPopup(){
	
	if(previewid == "" && hasexitpopup == true){
		
    addEvent(document, "mouseout", function(e) {
		
		if(hasDisplayedExitPopup == false){
			
			e = e ? e : window.event;
			var from = e.relatedTarget || e.toElement;
			if (!from || from.nodeName == "HTML") {
				
				hasDisplayedExitPopup = true;
				console.log('retrieve exit pop-up');
				retrieveExitPopup();
				
			}
			} 
    	});
		
	}
	
	
}

function addEvent(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    }
    else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}

function retrieveExitPopup() {
		forcedpopups = trmexitpopups;
		retrievePromotionalPopup();
}

// EOF Exit Pop-up
			