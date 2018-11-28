<?php

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
 * MAGENTO EDITION NOTICE
 * 
 * This software is designed for Magento Community edition.
 * Add In Mage:: Ltd. does not guarantee correct work of this extension on any other Magento edition.
 * Add In Mage:: Ltd. does not provide extension support in case of using a different Magento edition.
 * 
 * 
 * @category    AddInMage
 * @package     AddInMage_ToastNotifications
 * @copyright   Copyright (c) 2012 Add In Mage:: Ltd. (http://www.add-in-mage.com)
 * @license     http://add-in-mage.com/support/presales/eula-community/  End User License Agreement (EULA)
 * @author      Add In Mage:: Team <team@add-in-mage.com>
 */

class AddInMage_ToastNotifications_Block_Messages extends Mage_Core_Block_Messages
{

	const XML_PATH_TOAST_NOTIFICATIONS_ENABLED = 'addinmage_toastnotifications/general/use_in_';
	
	protected $_tmp_queue = null;

	
    public function getGroupedHtml()
    {
    	$area = Mage::getDesign()->getArea();
		$store = Mage::app()->getStore();
		
        $types = array(
        	Mage_Core_Model_Message::SUCCESS,
        	Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING            
        );
        
        $html = '';
        
        if(Mage::getStoreConfig(self::XML_PATH_TOAST_NOTIFICATIONS_ENABLED.$area, $store)) {
        	
        	$mode = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/hiding_mode', $store);
        	$delay_before = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/delay_before', $store);
        	$position = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/position', $store);

        	
        	$options = array();
        	
        	if($mode !== 'auto')
        		$options['useClose'] = true;
        	else {
        		$options['appearance'] = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/appearance', $store);
        		$options['disappearance'] = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/disappearance', $store);
        		$options['delay'] = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/delay', $store);
        	}
        	$options['position'] = $position;
        	$options['opacity'] = Mage::getStoreConfig('addinmage_toastnotifications/'.$area.'_settings/opacity', $store);
        	
        	$empty_stack = true;
        	$one_message = false;
        	$msg_count = count($this->getMessages());
        	if($msg_count == 1)
        	$one_message = true;
        	
        	$js = '';
        	$js = '<script type="text/javascript">';
        	$js .= "document.observe('dom:loaded', function(){";
        	$js .= 'var nbar = new ToastNotification();';
        	
        	if($delay_before)
        		$js .= 'setTimeout(function(){';        	
        	
        	$i=0;
        	
        	foreach (array_reverse($types,true) as $type) {
        		$messages = $this->getMessages($type);
        		foreach (array_reverse($messages,true) as $message) {
        			$empty_stack = false;
        			$nb_message = ($this->_escapeMessageFlag) ? $this->htmlEscape($message->getText()) : $message->getText();       			 
        			
        			$options['className'] = 'nb-'.$type.'-msg';
        			
        			if($one_message)
        				$js .= 'nbar.showNotification("'.addslashes($nb_message).'",'.Zend_Json_Encoder::encode($options).');';
        			 
        			else {
        				
        				if($i==0)
        					$this->_tmp_queue = 'nbar.showNotification("'.addslashes($nb_message).'",'.Zend_Json::encode($options).')';
        				else if ($i !== $msg_count-1) {
        					$options['destroyed'] = new Zend_Json_Expr('function(){'.$this->_tmp_queue.'}');
        					$this->_tmp_queue = 'nbar.showNotification("'.addslashes($nb_message).'",'.Zend_Json::encode($options,false,array('enableJsonExprFinder' => true)).')';
        				}
        				else {
        					$options['destroyed'] = new Zend_Json_Expr('function(){'.$this->_tmp_queue.'}');
        					$js .= 'nbar.showNotification("'.addslashes($nb_message).'",'.Zend_Json::encode($options,false,array('enableJsonExprFinder' => true)).');';
        				}
        				$i++;    				
        			}        			
        		}
        	}
        	
        	
        	if($delay_before)
        		$js .= '},'.($delay_before*1000).');';
        	$js .= '});';
        	$js .= '</script>';
        	$html = (!$empty_stack) ? $js : '';
        }
        
        else {        	
    	
        	foreach ($types as $type) {
        		if ( $messages = $this->getMessages($type) ) {
        			if ( !$html ) {
        				$html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages">';
        			}
        			$html .= '<' . $this->_messagesSecondLevelTagName . ' class="' . $type . '-msg">';
        			$html .= '<' . $this->_messagesFirstLevelTagName . '>';
        	
        			foreach ( $messages as $message ) {
        				$html.= '<' . $this->_messagesSecondLevelTagName . '>';
        				$html.= '<' . $this->_messagesContentWrapperTagName . '>';
        				$html.= ($this->_escapeMessageFlag) ? $this->htmlEscape($message->getText()) : $message->getText();
        				$html.= '</' . $this->_messagesContentWrapperTagName . '>';
        				$html.= '</' . $this->_messagesSecondLevelTagName . '>';
        			}
        			$html .= '</' . $this->_messagesFirstLevelTagName . '>';
        			$html .= '</' . $this->_messagesSecondLevelTagName . '>';
        		}
        	}
        	if ( $html) {
        		$html .= '</' . $this->_messagesFirstLevelTagName . '>';
        	}        	
        }
        
        return $html;
    }
}