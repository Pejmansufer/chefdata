<?php
class Equity_FacebookPixel_Block_Purchase extends Equity_FacebookPixel_Block_Abstract
{

    protected function _canShow()
    {
        return $this->_getConfigHelper()->isPurchaseEnabled();
    }

    public function getOrderAmount()
    {
        return number_format($this->_getOrder()->getGrandTotal(),2);
    }

    public function getOrderIDs()
    {
    	$orderIDs = array();
		
		foreach($this->_getOrder()->getItemsCollection() as $item){
			$product = Mage::getModel('catalog/product')->load( $item->getProductId() );
			$orderIDs[] = $this->_getProductTrackID($product);
		}

		return implode(',',$orderIDs);
    }

    protected function _getOrder()
    {
        return Mage::helper('equity_facebookpixel')->getLastOrder();
    }

}
