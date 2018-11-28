<?php
class Extendware_EWLayeredNav_Helper_Data extends Extendware_EWCore_Helper_Data_Abstract
{
	public function getJs($type, array $options = array()) {
    	$js = 'try {  ';
    	switch ($type) {
    		case 'navigation':
    			$js .= "$$('div.ewlayerednav-layered-nav').first().replace(" . json_encode(Mage::getSingleton('core/layout')->getBlock('navigation')->toHtml()) . ");";
    			break;
    		case 'products':
    			$block = Mage::getSingleton('core/layout')->getBlock('product_list');
    			if (!$block) $block = Mage::getSingleton('core/layout')->getBlock('search_result_list');
    			$js .= "$('ewlayerednav-product-list').replace(" . json_encode($this->processProductListHtml($block->toHtml())) . ");";
    			
    			foreach (array('ewcart.rewritePage();', 'ewquickview.rewritePage();', 'ewpribbon.reset()') as $code) {
    				$js .= 'try { ' . $code . '} catch (e) {}';
    			}
    			
    			if (Mage::getSingleton('ewcore/module')->load('Extendware_EWPRibbon')->isActive() === true) {
	    			$block = Mage::getSingleton('core/layout')->createBlock('ewpribbon/code');
					if ($block) {
						$js .= 'try { ';
						$js .= $block->setOutputInlineJs(true)->toHtml();
						$js .= '} catch (e) {}';
					}
    			}
    			break;
    	}
    	$js .= ' } catch (e) {}';
    	return $this->cleanHtml($js);
    }
    
	public function isSearch()
    {
        $mod = Mage::app()->getRequest()->getModuleName();
        if ($mod == 'catalogsearch') {
            return true;
        }
        if ($mod == 'ewlayerednav' && Mage::app()->getRequest()->getActionName() == 'search') {
            return true;
        }
        
        return false;
    }
    
    public function cleanHtml($html) {
    	if (Mage::app()->getRequest()->isXmlHttpRequest()){
            $html = str_replace('?___SID=U&amp;', '?', $html);
            $html = str_replace('?___SID=U', '', $html);
            $html = str_replace('&amp;___SID=U', '', $html);
        } 
        
        return $html;
    }
    
    public function processProductListHtml($html)
    {
        return '<div id="ewlayerednav-product-list">' . $this->cleanHtml($html) . '</div>';;        
    }
}
