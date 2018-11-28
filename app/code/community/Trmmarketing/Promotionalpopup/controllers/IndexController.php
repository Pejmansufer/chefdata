<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_Promotionalpopup
 * @copyright   Copyright (c) 2015 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */

class Trmmarketing_Promotionalpopup_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function loadpopupAction()
    {
    	$arrParams = $this->getRequest()->getParams();
		
		////////////////////////
		
		$popupModalClickClose = Mage::getStoreConfig('trmpopupconfig/trmpopupdesign_group/popupmodalclickclose',Mage::app()->getStore());
		$popupModalColor = Mage::getStoreConfig('trmpopupconfig/trmpopupdesign_group/popupmodalcolor',Mage::app()->getStore());
		$popupModalOpacity = Mage::getStoreConfig('trmpopupconfig/trmpopupdesign_group/popupmodalopacity',Mage::app()->getStore());
		$popupModalInDuration = Mage::getStoreConfig('trmpopupconfig/trmpopupdesign_group/popupmodalinduration',Mage::app()->getStore());
		$popupModalOutDuration = Mage::getStoreConfig('trmpopupconfig/trmpopupdesign_group/popupmodaloutduration',Mage::app()->getStore());
			
		$currentStoreId = Mage::app()->getStore()->getId();
		$customergroup = Mage::getSingleton('customer/session')->getCustomerGroupId(); 
		
		//store cookie data	
		$cookieConfig = Mage::getSingleton('core/cookie');
			
		// excluse any of the specified user agents
		$blockPopup = false;
			
		if (Mage::getStoreConfig('trmpopupconfig/trmpopup_group/excludebrowsers',Mage::app()->getStore()) != ""):
			$exclude = explode("|", Mage::getStoreConfig('trmpopupconfig/trmpopup_group/excludebrowsers',Mage::app()->getStore()));
			
			foreach ($exclude as $exludedBroswer):
			
				if(isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], $exludedBroswer)) $blockPopup = true;
			
			endforeach;
            
		endif;
			
			
		if($blockPopup == false):
			
			
			
			$curCookies = explode(";", $arrParams['cookies']);
			$curCookieList = array();
			
			foreach($curCookies as $curCookie):
				$curKey = explode("=", $curCookie);
				$curCookieList[] = str_replace(' ', '',$curKey[0]);
			endforeach;
			
			
			if(($arrParams['forcepopup'] == "")&&($arrParams['previewpopup'] == "")):
				// load popups default
				
				// collection
				
				  
				  $popupCollection = Mage::getModel('promotionalpopup/promotionalpopup')->getCollection()
						->addFieldToFilter('cookie_value', array('nin' => $curCookieList ) ) // array of set cookies
						->addFieldToFilter('status', array('eq' => '1') ) //status enabled
						->addfieldtofilter('begin_time', array('to' => Mage::getModel('core/date')->gmtDate() ) ) // display from
						->addfieldtofilter('end_time', array('gteq' => Mage::getModel('core/date')->gmtDate() ) ) // display to
						->addFieldToFilter('customergroup_ids',
                    		array(
								array('eq' => $customergroup ), // single customer group
                        		array('like' => $customergroup . ',%'), // customer group beginning of array
								array('like' => '%,'. $customergroup . ',%'), // customer group in an array
								array('like' => '%,'. $customergroup ), // customer group at the end of an array
								)
           				 	)
						->addFieldToFilter('stores_id',
                    		array(
                        		array('eq' => '0'), // all store views
								array('like' => '0,%'), // all store views
								array('like' => $currentStoreId ), // single store selected
                        		array('like' => $currentStoreId . ',%'), // store beginning of array of stores
								array('like' => '%,'. $currentStoreId . ',%'), // store in an array of stores
								array('like' => '%,'. $currentStoreId ), // store in at the end of an array of stores
								)
           				 	)
						->setOrder('sort_order', 'ASC')
						->setPageSize(1);
						
				   // collection
				   
				   
			elseif ($arrParams['previewpopup'] != ""):
			
				// collection
				
				   $popupCollection = Mage::getModel('promotionalpopup/promotionalpopup')->getCollection()
						->addFieldToFilter('promotionalpopup_id', array('eq' => $arrParams['previewpopup'] ) ) // array of set cookies
						->setOrder('sort_order', 'ASC');
				   // collection
				   
			else:
				
				$forcedpopups = array_filter(explode("|", $arrParams['forcepopup']));
				
				Mage::log('Promotional Popup forpopup: ' .  implode(",", $forcedpopups), null, 'promotionalpopups.log');
				// collection
				
				   
				   $popupCollection = Mage::getModel('promotionalpopup/promotionalpopup')->getCollection()
						->addFieldToFilter('cookie_value', array('nin' => $curCookieList ) ) // array of set cookies
						->addFieldtofilter('begin_time', array('to' => Mage::getModel('core/date')->gmtDate() ) ) // display from
						->addFieldtofilter('end_time', array('gteq' => Mage::getModel('core/date')->gmtDate() ) ) // display to
						
						->addFieldToFilter(
							array('status', 'promotionalpopup_id'),
								array(
									array('eq' => '1'), //status is enabled
									array('in' =>  array($forcedpopups) ) //or popup is forced
                					) 
								) 
						
						->addFieldToFilter('customergroup_ids',
                    		array(
								array('eq' => $customergroup ), // single customer group
                        		array('like' => $customergroup . ',%'), // customer group beginning of array
								array('like' => '%,'. $customergroup . ',%'), // customer group in an array
								array('like' => '%,'. $customergroup ), // customer group at the end of an array
								)
           				 	)		
						->addFieldToFilter('stores_id',
                    		array(
                        		array('eq' => '0'), // all store views
								array('like' => '0,%'), // all store views
								array('like' => $currentStoreId ), // single store selected
                        		array('like' => $currentStoreId . ',%'), // store beginning of array of stores
								array('like' => '%,'. $currentStoreId . ',%'), // store in an array of stores
								array('like' => '%,'. $currentStoreId ), // store in at the end of an array of stores
								)
           				 	)	
							
						->setOrder('sort_order', 'ASC')
						->setPageSize(1);
						
						
						
				   // collection
			
			endif;   
			   
			   
			$popup = $popupCollection->getData();
			//echo $popupCollection->getSelect();
			// enable if having collection caching between popups
			$popupCollection->clear();
			
			Mage::log('Promotional Popup Select: ' . $popupCollection->getSelect(), null, 'promotionalpopups.log');
			  
				$helper = Mage::helper('cms');
				$processor = $helper->getBlockTemplateProcessor();
				
				// check that there is a pop-up that can be returned
				if(count($popup) > 0):
				//// Beginning check cookie and set cookie
				if (!isset($_COOKIE[$popup[0]['cookie_value']])||($arrParams['previewpopup'])) :
				
					/////
					//popup code
					/////
					// prevent null return
					if(!empty($popup[0]['promotionalpopup_id'])):
					
					$newdate = new DateTime($popup[0]['end_time']);
					
					
					/// BOF Select content based on browser dimensions
					$tabletwidth = Mage::getStoreConfig('trmpopupconfig/trmpopup_group/tabletwidth',Mage::app()->getStore());
					$mobilewidth = Mage::getStoreConfig('trmpopupconfig/trmpopup_group/mobilewidth',Mage::app()->getStore());
					
					
					
					if($tabletwidth == "") $tabletwidth = 0;
					if($mobilewidth == "") $mobilewidth = 0;
					
					// check if browser is smaller than either md or sm breakpoints or are not enabled
					$browserwidth = $arrParams['browserwidth'];
					if(($browserwidth <= $tabletwidth || $browserwidth <= $mobilewidth)&&($popup[0]['status_md'] == 1 || $popup[0]['status_sm'] == 1)&& $browserwidth != ""):
					
						Mage::log('Promotional Popup content selection continues: md or sm form factor', null, 'promotionalpopups.log');
						
						// make select between md and sm form factors
						if(($browserwidth <= $mobilewidth && $popup[0]['status_sm'] == 1)||$popup[0]['status_md'] == 0):
							$curwidth = $popup[0]['width_sm'];
							$curheight = $popup[0]['height_sm'];
							$curfilename = $popup[0]['filename_sm'];
							$curbackground_color = $popup[0]['background_color_sm'];
							$curcontent = $popup[0]['promotionalpopup_content_sm'];
							$curstyles = $popup[0]['styles_sm'];
							
							$deviceLabel = Mage::getStoreConfig('trmpopupconfig/trmpopuptracking_group/devicelabel_sm',Mage::app()->getStore()); 
							Mage::log('Promotional Popup content selection: sm form factor', null, 'promotionalpopups.log'); 
						else:
							$curwidth = $popup[0]['width_md'];
							$curheight = $popup[0]['height_md'];
							$curfilename = $popup[0]['filename_md'];
							$curbackground_color = $popup[0]['background_color_md'];
							$curcontent = $popup[0]['promotionalpopup_content_md'];
							$curstyles = $popup[0]['styles_md'];
							
							$deviceLabel = Mage::getStoreConfig('trmpopupconfig/trmpopuptracking_group/devicelabel_md',Mage::app()->getStore());
							Mage::log('Promotional Popup content selection: md form factor', null, 'promotionalpopups.log'); 
						endif;
						
					else:
						// browser width is larger than all break points OR no md or sm pop-ups are enabled or defined
						$curwidth = $popup[0]['width'];
						$curheight = $popup[0]['height'];
						$curfilename = $popup[0]['filename'];
						$curbackground_color = $popup[0]['background_color'];
						$curcontent = $popup[0]['promotionalpopup_content'];
						$curstyles = $popup[0]['styles'];
						
						$deviceLabel = Mage::getStoreConfig('trmpopupconfig/trmpopuptracking_group/devicelabel_lg',Mage::app()->getStore());
						Mage::log('Promotional Popup content selection: lg form factor', null, 'promotionalpopups.log'); 
					
					endif;
					/// EOF Select content based on browser dimensions
					
					/// BOF default close button
					$defaultclose = '';
					
					$defaultcloseposition = '';
					if($popup[0]['close_btn_position'] != ""):  
					//$defaultcloseposition = 'position:absolute; '.$popup[0]['close_btn_position'];
					$horpos = '0';
					if($popup[0]['close_btn_horizontal_offset'] != "") $horpos = $popup[0]['close_btn_horizontal_offset'];
					$vertpos = '0';
					if($popup[0]['close_btn_vertical_offset'] != "") $vertpos = $popup[0]['close_btn_vertical_offset'];
					
						switch ($popup[0]['close_btn_position']) :
							case "topleft":
								$defaultcloseposition = 'position:absolute; left:'.$horpos.'px; top:'.$vertpos.'px;';
								break;
							case "topright":
								$defaultcloseposition = 'position:absolute; right:'.$horpos.'px; top:'.$vertpos.'px;';
								break;
							case "bottomleft":
								$defaultcloseposition = 'position:absolute; left:'.$horpos.'px; bottom:'.$vertpos.'px;';
								break;
							case "bottomright":
								$defaultcloseposition = 'position:absolute; right:'.$horpos.'px; bottom:'.$vertpos.'px;';
								break;
						endswitch;
					endif;
					
					// check and see if close button has an image or label assigned
					if($popup[0]['filename_close_btn'] != ""):
						$defaultclose = '<a id="closeLink" onclick="javascript:autoClosePopup();checkForVideo();" style="'.$defaultcloseposition.'"><img src="'.Mage::getBaseUrl('media').$popup[0]['filename_close_btn'].'" /></a>';
					else:
						$defaultcloselabel = 'Close [x]';
						if($popup[0]['close_btn_label'] != "")  $defaultcloselabel = $popup[0]['close_btn_label'];
						
						$defaultclosecolor = '#333;';
						if($popup[0]['close_label_color'] != "")  $defaultclosecolor = $popup[0]['close_label_color'];
						
						
						$defaultclose = '<a id="closeLink" onclick="javascript:autoClosePopup();checkForVideo();" style="color:'.$defaultclosecolor.'; '.$defaultcloseposition.'">'.$defaultcloselabel.'</a>';
					endif;
					
					// EOF default close button
						
						if($deviceLabel != "") $deviceLabel = "(" . preg_replace('/[^a-zA-Z0-9 -]+/', '', $deviceLabel) . ")";
					
						echo json_encode(array(
						'content' => $processor->filter($curcontent),
						'promotionalpopup_id' => $popup[0]['promotionalpopup_id'],
						'cookie' => $popup[0]['cookie_value'],
						'cookie_expiry' => $popup[0]['cookie_expiry'],
						'popuptitle' => preg_replace('/[^a-zA-Z0-9 -]+/', '', $popup[0]['title']) . ' ' . $deviceLabel,
						'template' => $popup[0]['template'],
						'filename' => $curfilename,
						'background_color' => $curbackground_color,
						'modal_background' => $popup[0]['modal_background'],
						'modal_color' => $popup[0]['modal_color'],
						'modal_video_mp4' => $popup[0]['modal_video_mp4'],
						'modal_video_ogv' => $popup[0]['modal_video_ogv'],
						'modal_opacity' => $popup[0]['modal_opacity'],
						'modal_video_loop' => $popup[0]['modal_video_loop'],
						'width' => $curwidth,
						'height' => $curheight,
						'untildate' => $newdate->format('M d Y H:i:s'),
						'styles' => $curstyles,
						'delay' => $popup[0]['delay'],
						'timestatus' => $popup[0]['timestatus'],
						'coupon_id' => $popup[0]['coupon_id'],
						'closechainedpopup_id' => $popup[0]['closechainedpopup_id'],
						'conversionchainedpopup_id' => $popup[0]['conversionchainedpopup_id'],
						'css_reset' => $popup[0]['css_reset'],
						'default_close_button' => $defaultclose
						
						));
						
					endif; 
					
					
					/////
					//popup code
					/////
		
				
					
				endif;
					
			
			endif;
		
		endif;
		///////////////////
		

    }
	
	
	
	public function viewAction()
    {
    	$arrParams = $this->getRequest()->getParams();
		if($arrParams['id'] != ""):
		$object = Mage::getModel('promotionalpopup/promotionalpopup')->load($arrParams['id']);
		$curViews = $object->getPopupviews();
        $object->setPopupviews($curViews + 1);
        $object->save();
		endif;

			
    }
	
	public function conversionAction()
    {
    	
    	
		
		$arrParams = $this->getRequest()->getParams();
		if($arrParams['id'] != ""):
			$object = Mage::getModel('promotionalpopup/promotionalpopup')->load($arrParams['id']);
			$curConversions = $object->getPopupconversions();
			$object->setPopupconversions($curConversions + 1);
			$object->save();
		endif;

    }
	
	public function couponAction()
	{
	$generator = Mage::getModel('salesrule/coupon_massgenerator');

	$data = array(
		'max_probability'   => .25,
		'max_attempts'      => 10,
		'uses_per_customer' => 1,
		'uses_per_coupon'   => 1,
		'qty'               => 1, //number of coupons to generate
		'length'            => 14, //length of coupon string
		'to_date'           => '2020-12-31', //ending date of generated promo
		/**
		 * Possible values include:
		 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC
		 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHABETICAL
		 * Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_NUMERIC
		 */
		'format'          => Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC,
		'rule_id'         => 2 //the id of the rule you will use as a template
	);
	
	$generator->validateData($data);
	
	$generator->setData($data);
	
	$generator->generatePool();
	
	//
	
	
	$salesRule = Mage::getModel('salesrule/rule')->load($data['rule_id']);
	$collection = Mage::getResourceModel('salesrule/coupon_collection')
				->addRuleToFilter($salesRule)
				->addGeneratedCouponsFilter()
				->getLastItem();
	
	
	
	echo $collection->getCode();	
		
	}
	
	
	// BOF subscrube function
	public function subscribeAction()
    {
    	
    	if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');
			$emailstatus        = (string) $this->getRequest()->getPost('emailstatus');
			$ruleid             = $this->getRequest()->getPost('ruleid');
			$codelength         = (string) $this->getRequest()->getPost('codelength');
			$template           = (string) $this->getRequest()->getPost('template');
			
			$subsribeStatus = 4;

            try {
				
				Mage::log('Newsletter subscription: ' . $email , null, 'promotionalpopups.log');
				
				if(!Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/skipzendvalidation',Mage::app()->getStore())): 
				Mage::log('Zend validation: Processed', null, 'promotionalpopups.log');
                if (!Zend_Validate::is($email, 'EmailAddress')) :
				
					
                    //Please enter a valid email address.
					Mage::log('Zend validation: Failed', null, 'promotionalpopups.log');
					$subsribeStatus = 0;
					
					
                endif;
				
				endif;

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
                    !$customerSession->isLoggedIn()) :
					
					if(Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/enablenoguest',Mage::app()->getStore())):
                    //Sorry, but administrator denied subscription for guests.
					Mage::log('Subscription Check: Adminstrator denied subscription for guests', null, 'promotionalpopups.log');
					$subsribeStatus = 1;
					endif;
					
                endif;
				
				if($subsribeStatus == 4):

					$ownerId = Mage::getModel('customer/customer')
							->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
							->loadByEmail($email)
							->getId();
					if ($ownerId !== null && $ownerId != $customerSession->getId()) :
						
						if(Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/enablecustomeremail',Mage::app()->getStore())): 
						//This email address is already assigned to another user.
						Mage::log('Subscription Check: Already assigned to another user', null, 'promotionalpopups.log');
						$subsribeStatus = 2;
						endif; 
						
					endif;

				endif;
				
				if ($subsribeStatus == 4 && Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/enablenoduplicates',Mage::app()->getStore())):
						
						$subscriberCheck = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
						//check if duplicate email exists
						if($subscriberCheck->getId()):
						Mage::log('Subscription Process: duplicate email ' . $subscriberCheck->getId(), null, 'promotionalpopups.log');
						$subsribeStatus = 'noduplicates';
						endif;
						
					endif;
				
				if($subsribeStatus == 4):
				
				
					$status = Mage::getModel('newsletter/subscriber')->subscribe($email);
					

					if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
						//Confirmation request has been sent.
						Mage::log('Subscription Process: Confirmation request sent', null, 'promotionalpopups.log');
						$subsribeStatus = 3;
						
						//BOF store email for confirmation
						if($emailstatus == "yes"):
							// get all previous queued emails from this email
							$collection = Mage::getModel('trmmarketing_subscribeconfirmed/popupemail')->getCollection()
							->addFieldToFilter('email', array('eq' => $email) ); 
							
							// delete all previous emails
							foreach ($collection as $item) :
								Mage::log('Popup email delete', null, 'popupemail_queue.log');
								$item->delete();
							endforeach;
							
							// assign latest promotional to email queue
							$object = Mage::getModel('trmmarketing_subscribeconfirmed/popupemail');
							//Queue transactional email for future sending
							$object->setEmail($email);
							$object->setTemplate($template);
							$object->setRuleid($ruleid);
							$object->setCodelength($codelength);
							$object->save(); 
							
							Mage::log('Popup email queued', null, 'popupemail_queue.log');  
						endif;
						//EOF store email for confirmation
						
					} 
					else {
						//Thank you for your subscription.
						$subsribeStatus = 4;
						//////
						////
						if($emailstatus == "yes"):
						
						$identity = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/identity',Mage::app()->getStore());
			
			$identityName = Mage::getStoreConfig('trans_email/ident_'.$identity.'/name');
			$identityEmail = Mage::getStoreConfig('trans_email/ident_'.$identity.'/email');
			
			if($template == "") $template = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/template',Mage::app()->getStore());
		
			
			// Define the sender
			$sender = Array('name' => $identityName,
							'email' => $identityEmail);
			
			// Set the store
			$store = Mage::app()->getStore();
			
			
			//// BOF Generate Coupon
			// checks if there is a coupon assigned, if not it will skip generation
			if($ruleid != ""):
				if($codelength == "") $codelength = 14; 
				
				$promotionCode = $this->generateCoupon($ruleid, $codelength);
			
			endif;
			//// EOF Generate Coupon
			
			// In this array, you set the variables you use in your template
			$vars = Array('subscriber_email' => $email,
						  'unsubscribe_link' => $unsubscribeLink,
						  'coupon_code' => $promotionCode);
			
			// Translation        
			$translate  = Mage::getSingleton('core/translate');
			
			// Send email
			try {
			$transactionalEmail = Mage::getModel('core/email_template');
			$transactionalEmail->sendTransactional($template, $sender, $email, $email, $vars, $store->getId());
			
			} catch (Exception $e) { 
				//Mage::log('Newsletter send exception: ' . $e , null, 'promotionalpopups.log');
			}
		
	
			// Translate email        
			$translate->setTranslateInline(true);
			
			endif;
			/*end of sending emails */	
				
			
						////
						/////
					}
				
				endif;
				
            }
            catch (Mage_Core_Exception $e) {
                //There was a problem with the subscription.
				//Mage::log('Newsletter subscription exception: ' . $e , null, 'promotionalpopups.log');
				
            }
            catch (Exception $e) {
                //There was a problem with the subscription.
				//Mage::log('Newsletter subscription exception: ' . $e , null, 'promotionalpopups.log');
				
            }
        }
        
		echo $subsribeStatus;
		
		
		function sendPopupEmail($email){
			
			$identity = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/identity',Mage::app()->getStore());
			
			$identityName = Mage::getStoreConfig('trans_email/ident_'.$identity.'/name');
			$identityEmail = Mage::getStoreConfig('trans_email/ident_'.$identity.'/email');
			
			$emailTemplate = Mage::getStoreConfig('trmpopupconfig/trmpopupsubscribe_group/template',Mage::app()->getStore());
		
			
			$sender = Array('name' => $identityName,
							'email' => $identityEmail);
			
			
			$store = Mage::app()->getStore();
			
			$vars = Array('subscriber_email' => $email);
			
			// Translation        
			$translate  = Mage::getSingleton('core/translate');
			
			// Send email
			try {
			$transactionalEmail = Mage::getModel('core/email_template');
			$transactionalEmail->sendTransactional($emailTemplate,
																	 $sender,
																	 $email,
																	 $vars,
																	 $store->getId());
			
			} catch (Exception $e) { 
			
			}
		
	
															 
			
			// Translate        
			$translate->setTranslateInline(true);
			
			
			/*end of sending emails */	
				
			}

			
	}
	// EOF subcribe function
	
	public function generateCoupon($ruleid, $codelength){
		
		if(Mage::getVersion() >= 1.7):
			
			$generator = Mage::getModel('salesrule/coupon_massgenerator');
			
			$data = array(
				'max_probability'   => .25,
				'max_attempts'      => 10,
				'uses_per_customer' => 1,
				'uses_per_coupon'   => 1,
				'qty'               => 1, //number of coupons to generate
				'length'            => $codelength, //length of coupon string
				
				'format'          => Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC,
				'rule_id'         => $ruleid //the id of the rule you will use as a template
			);
			
			$generator->validateData($data);
			
			$generator->setData($data);
			
			$generator->generatePool();
			
			
			$salesRule = Mage::getModel('salesrule/rule')->load($data['rule_id']);
			$collection = Mage::getResourceModel('salesrule/coupon_collection')
						->addRuleToFilter($salesRule)
						->addGeneratedCouponsFilter()
						->getLastItem();
			
			
			return $collection->getCode();
			
		else:
		
			
			
	
			$couponCode = $this->generateLegacyCouponCode($codelength);
			
			$checkCoupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
			$checkRule = Mage::getModel('salesrule/rule')->load($checkCoupon->getRuleId());
			
			
				do { 
					$couponCode = $this->generateLegacyCouponCode($codelength);
				} while ($checkRule['coupon_code'] == $couponCode); 
					
			
			
			$coupon = Mage::getModel('salesrule/rule')->load($ruleid);
			
			$coupon->setId(NULL);
			$coupon->setName($coupon->getName() . ' - ' . $couponCode);
			$coupon->setCouponCode($couponCode);
			
			$coupon->save();
					
			return $couponCode;	
		
		endif;
			
		
	}
	
	public function generateLegacyCouponCode($length = null)
    {
        $rndId = crypt(uniqid(rand(),1));
        $rndId = strip_tags(stripslashes($rndId));
        $rndId = str_replace(array(".", "$"),"",$rndId);
        $rndId = strrev(str_replace("/","",$rndId));
            if (!is_null($rndId)){
            return strtoupper(substr($rndId, 0, $length));
            }
        return strtoupper($rndId);
    }
	
	
}