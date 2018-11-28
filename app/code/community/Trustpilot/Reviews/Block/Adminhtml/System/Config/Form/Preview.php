<?php
class Trustpilot_Reviews_Block_Adminhtml_System_Config_Form_Preview 
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    const CODE_TEMPLATE = 'trustpilot/system/config/preview.phtml';
    const CSS_URL = '/skin/adminhtml/default/default/trustpilot/trustpilot-iframe.css';

    protected $_helper;
    protected $_previewUrl;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate(static::CODE_TEMPLATE);
        $this->_helper     = Mage::helper('trustpilot/Data');
        $this->_previewUrl = $this->_helper->getGeneralConfigValue('PreviewUrl');
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    public function getXPathValue(){
        return $this->_helper->getTrustboxConfigValue('trustpilot_trustbox_xpath');
    }

    public function getIframeCssUrl()
    {
        return static::CSS_URL;
    }

    public function getPreviewUrl()
    {
        return $this->_previewUrl;
    }

    public function getUrl($page = NULL)
    {
        try {
            $value = (isset($page)) ? $page : $this->_helper->getTrustBoxConfigValue('trustpilot_trustbox_page');
            $storeId = $this->_helper->getStoreIdOrDefault();
            switch ($value) {
                case 'trustpilot_trustbox_homepage':
                    return Mage::app()->getStore($storeId)->getUrl();
                case 'trustpilot_trustbox_category':
                    $urlPath = Mage::getModel('catalog/category')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('is_active', 1)
                        ->addAttributeToFilter('url_key', array('notnull' => true))
                        ->addAttributeToFilter('children_count', 0)
                        ->addUrlRewriteToResult()
                        ->getFirstItem()
                        ->getUrlPath();
                    $url = Mage::getUrl($urlPath, array(
                        '_use_rewrite' => true,
                        '_secure' => true,
                        '_store' => $storeId,
                        '_store_to_url' => true
                    ));
                    return  $url;
                case 'trustpilot_trustbox_product':
                    return Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSelect('name')
                        ->addAttributeToFilter('status', 1)
                        ->addAttributeToFilter('url_key', array('notnull' => true))
                        ->addAttributeToFilter('visibility', array(2, 3, 4))
                        ->addUrlRewrite()
                        ->getFirstItem()
                        ->getUrlInStore(array(
                            '_store'=>$storeId
                        ));
                default:
                    return Mage::app()->getStore($storeId)->getUrl();
            }
        } catch (Exception $e) {
            Mage::log('Unable to find URL for a page ' . $value . '. Error: ' . $e->getMessage());

            return Mage::getBaseUrl();
        }
    }
}
