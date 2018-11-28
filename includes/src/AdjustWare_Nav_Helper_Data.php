<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     HxObcXe62uQsQGs98TzFPLxwiM3aCakojlW7nJdmvI
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class AdjustWare_Nav_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_params = null;
    protected $_continueShoppingUrl = null;
    
    public function isSearch()
    {
        $mod = Mage::app()->getRequest()->getModuleName();
        if ('catalogsearch' === $mod)
        {
            return true;
        }
            
        if ('adjnav' === $mod && 'search' == Mage::app()->getRequest()->getActionName())
        {
            return true;
        }
        
        return false;
    }
    
    public function isCategoryCleared( $checkAdjClear = false )
    {
        $request = Mage::app()->getRequest();
        if( $request->getQuery('cat') == 'clear' ) 
        {
            return true;
        }
        if($checkAdjClear && $request->getParam('adjclear', false))
        {
            return true;
        }
        return false;
    }
    
    public function getContinueShoppingUrl()
    {
        if (is_null($this->_continueShoppingUrl))
        {
            $url = '';
            
            $allParams = $this->getParams();
            $keys = $this->getNonFilteringParamKeys();
            
            $query = array();
            foreach ($allParams as $k=>$v){
                if (in_array($k, $keys))
                    $query[$k] = $v;
            }
            
            if ($this->isSearch()){
                $url = Mage::getModel('core/url')->getUrl('catalogsearch/result/index', array('_query'=>$query));
            }
            else {
                $category = Mage::registry('current_category');
                $rootId = Mage::app()->getStore()->getRootCategoryId();
                if ($category && $category->getId() != $rootId){
                    $url = $category->getUrl();
                }
                else {
                    $url = Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
                }
                $url .= $this->toQuery($query);
            } 
            $this->_continueShoppingUrl = $url;      
        }
        
        return $this->_continueShoppingUrl;
    }
    
    public function wrapProducts($html)
    {
        if ($this->isPossibleUseLayeredNavigation())
        {
            $html = str_replace('onchange="setLocation', 'onchange="adjnavToolbar.makeRequest', $html);
        }  
        $loaderHtml =  '<div class="adj-nav-progress" style="display:none"><img src="'. Mage::getDesign()->getSkinUrl('images/adj-nav-progress.gif') .'" /></div>';  
        $html .= $loaderHtml;
        
        if (Mage::app()->getRequest()->isXmlHttpRequest()){
            $html = str_replace('?___SID=U&amp;', '?', $html);
            $html = str_replace('?___SID=U', '', $html);
            $html = str_replace('&amp;___SID=U', '', $html);
            
            $k = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
            $v = Mage::helper('core')->urlEncode($this->getContinueShoppingUrl());
            $html = preg_replace("#$k/[^/]+#","$k/$v", $html);
            
        }
        else {
            $html = '<div id="adj-nav-container">'
                  . $html
                  . '</div>'
                  . '';
        }    
        if (Mage::getStoreConfig('design/adjnav/scroll_to_top'))
	{
            $html .= '<script>
                        scroll(0,0);
                      </script>';
        }
        
        return $html;        
    }
    
    public function wrapHomepage($html)
    {
        $loaderHtml =  '<div class="adj-nav-progress" style="display:none"><img src="'. Mage::getDesign()->getSkinUrl('images/adj-nav-progress.gif') .'" /></div>';

        $html = '<div id="adj-nav-container">'
              . $html
              . $loaderHtml
              . '</div>'
              . '<script>AdjnavToolbar.init()</script>';
        return $html;       
    }
    
    
    public function getParam($k){
        $p = $this->getParams();
        $v = isset($p[$k]) ? $p[$k] : null;
        return $v;
    }  
    
    
    /**
    * @refactor
    * too large method. split method into several. 
    * place content from if-elements to new methods
    */
    // currently we use $without only if $asString=true
    public function getParams($asString=false, $without=null)
    {
        if (is_null($this->_params)){			
            $sessionObject = Mage::getSingleton('catalog/session');

            $bNeedClearAll = false;
            $bPreserveCategoryFilter = false;

            if (Mage::getStoreConfig('design/adjnav/reset_filters') && Mage::registry('adjnav_new_category'))
            {
                $bNeedClearAll = true;
                $bPreserveCategoryFilter = true;
            }


            if ($this->isSearch())
            {
                $sessionObject = Mage::getSingleton('catalogsearch/session');
                $query = Mage::app()->getRequest()->getQuery();
                if (isset($query['q']))
                {
                    if ($sessionObject->getData('advnavquery') && $sessionObject->getData('advnavquery') != $query['q'])
                    {
                        $bNeedClearAll = true;
                    }
                    $sessionObject->setData('advnavquery', $query['q']);
                }
            }

            // start fix for diff currency and input type
            
            $nSavedCurrencyRate = $sessionObject->getAdjNavCurrencyRate();
            
            $nCurrentCurrencyRate =  Mage::app()->getStore()->convertPrice(1000000, false);
            $nCurrentCurrencyRate = $nCurrentCurrencyRate / 1000000;
            
            $nSavedPriceStyle = $sessionObject->getAdjNavPriceStyle();
            $nCurrentPriceStyle = Mage::getStoreConfig('design/adjnav/price_style');
            
            $bNeedClearPriceFilter = false;
            
            if ($nSavedCurrencyRate AND $nSavedCurrencyRate != $nCurrentCurrencyRate)
            {
                $bNeedClearPriceFilter = true;
            }
            
            if ($nSavedPriceStyle != $nCurrentPriceStyle)
            {
                $bNeedClearPriceFilter = true;
            }
            
            if ($bNeedClearPriceFilter)
            {
                $sess  = (array)$sessionObject->getAdjNav();
                
                if ($sess)
                {
                    $aNonFilteringParamKeys = $this->getNonFilteringParamKeys();
                    
                    foreach ($sess as $sKey => $sVal)
                    {
                        if (!in_array($sKey, $aNonFilteringParamKeys))
                        {
                            $attribute = Mage::getModel('eav/entity_attribute');

                            $attribute->load($sKey, 'attribute_code');
                            
                            if ($attribute->getFrontendInput() == 'price')
                            {
                                unset($sess[$sKey]);
                            }
                        }
                    }
                    
                    $sessionObject->setAdjNav($sess);
                }
            }
            
            $sessionObject->setAdjNavCurrencyRate($nCurrentCurrencyRate);
            $sessionObject->setAdjNavPriceStyle($nCurrentPriceStyle);
            
            // end fix for diff currency and stores
            
            
            $query = Mage::app()->getRequest()->getQuery();
            $sess  = (array)$sessionObject->getAdjNav();
            $sess  = array(); // @author ksenevich@aitoc.com Disable session storage of params with ajax hashes implementation
            $this->_params = array_merge($sess, $query);

            if (!empty($query['adjclear']) OR $bNeedClearAll)
            {
                $back = $this->_params;
                $this->_params = array();       
                if ($bPreserveCategoryFilter && isset($back['cat']) && is_numeric($back['cat']))
                {
                    //checking if category was changed and if it wasn't 'clear'ed
                    $this->_params['cat'] = $back['cat'];
                }
                if ($this->isSearch() && isset($query['q']))
                {
                    $this->_params['q'] = $query['q'];
                }
                unset($back);
            }

           /* if (Mage::registry('adjnav_new_category') && isset($this->_params['cat']))
            {
                unset($this->_params['cat']);
            }*/
            //remove empty
            $sess = array();
            foreach ($this->_params as $k => $v){
                if ($v && 'clear' != $v)
                    $sess[$k] = $v;
            }
            
            if (Mage::registry('adjnav_new_category') AND isset($sess['p']))
            {
                unset($sess['p']);
            }
            
            $sessionObject->setAdjNav($sess);
            $this->_params = $sess;
            
            Mage::register('adjnav_current_session_params', $sess);
            
            // add values from session to request for product list toolbar
            // this code assumes we call the function BEFORE toolbar,
            // in general it is not correct
            foreach ($this->getNonFilteringParamKeys() as $k){
                if (!empty($sess[$k])){
                   # Mage::app()->getRequest()->setParam($k, $sess[$k]);     <-- this string add to url $_GET params like join("/",$_GET)
                }
            }
            
            Mage::dispatchEvent('adjustware_nav_layer_set_params_after', array(
                'helper' =>  $this,
            ));
        }

        if ($asString)
        {
            return $this->toQuery($this->_params, $without);
        }

        return $this->_params;
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @return AdjustWare_Nav_Helper_Data 
     */
    public function setParam($key, $value = null)
    {        
        $this->_params[$key] = $value;
        return $this;
    }
    
    /**
     * @param string $key
     * @return AdjustWare_Nav_Helper_Data 
     */
    public function unsetParam($key)
    {        
        unset($this->_params[$key]);        
        return $this;
    }
    
    public function toQuery($params, $without=null)
    {           
        if (!is_array($without))
            $without = array($without);
            
        $queryStr = '?';
        foreach ($params as $k => $v){
            if (!in_array($k, $without))
                $queryStr .= $k . '=' . urlencode($v) . '&';    
        }
        return substr($queryStr, 0, -1);           
    }
    
    public function getClearAllUrl($baseUrl)
    {
        $baseUrl .= '?adjclear=true';
        if ($this->isSearch())
            $baseUrl .= '&q=' . urlencode($this->getParam('q'));  
               
        return $baseUrl;
    }
    
    public function bNeedClearAll()
    {
        if ($aParams = Mage::registry('adjnav_current_session_params'))
        {
            $aNonFilteringParamKeys = $this->getNonFilteringParamKeys();
            
            foreach ($aParams as $sKey => $sVal)
            {
                if (!in_array($sKey, $aNonFilteringParamKeys))
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function getCacheKey($attrCode){
        $keys = $this->getNonFilteringParamKeys();
        $keys[] = $attrCode;        
        return md5($this->getParams(true, $keys) . $attrCode);
    }
    
    protected function getNonFilteringParamKeys(){
        return array('x','y','mode','p','order','dir','limit','q','___store', '___from_store','sns','no_cache');
    }
    
    public function isHomePage()
    {
        return ($this->_getUrl('') == strtok($this->_getUrl('*/*/*', array('_current'=>false, '_use_rewrite'=>true)), '?')) || Mage::app()->getRequest()->getParam('home');
    }
    
    public function isPageAutoload()
    {
        if (!$this->isPossibleUseLayeredNavigation() || Mage::app()->getRequest()->getParam('home'))
        {
            return false;
        }

        return Mage::getStoreConfig('design/adjnav_endless_page/enable_page_autoload');
    }
    
    public function isPossibleUseLayeredNavigation()
    {
        if($this->isModuleEnabled('Aitoc_Aitmanufacturers'))
        {
            $canUseLNP = Mage::helper('aitmanufacturers')->canUseLayeredNavigation(Mage::registry('shopby_attribute'), true);
            if($canUseLNP)
                return true;
        }
    
        $category = Mage::registry('current_category');
        if($category)
        {
            $pageLayout = $this->getPageLayout($category);

            if ($category->getIsAnchor() && (in_array($pageLayout, array('', 'two_columns_left', 'three_columns')) || (Mage::app()->getRequest()->getControllerName()=='ajax'))) {
                return true;
            }
        }
        elseif($this->isSearch()) {
            return true;
        }
        return false;
    }
 
    /**
     * @refactor
     * move method to private AdjustWare_Nav_Model_Observer. It uses only there.
     */ 
    public function getPageLayout($category)
    {
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.4.2'))
        {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);
            $pageLayout = $settings->getPageLayout();
        } else {
            $pageLayout = $category->getPageLayout();
        }
        return $pageLayout;
    }
	
	/**
     * @return Varien_Object
     */
    public function getShopByBrandsStatus()
    {
        return $this->isModuleEnabled('Aitoc_Aitmanufacturers');
    }
    
    /**
     * @return string
     */
    public function trimBaseUrl()
    {
        return trim(Mage::app()->getStore()->getBaseUrl(), '/');
    }

    public function getCategoryLayeredBlockType()
    {
        return Mage::getStoreConfig('design/adjnav/cat_block_type');
    }

    public function getCategoryDisplayType()
    {
        return Mage::getStoreConfig('design/adjnav/cat_type');
    }

	public function getCategoryFilterEnabled()
    {
        return Mage::getStoreConfig('design/adjnav/cat_style');
    }
	
    public function ifBrowserNotFF()
    {
        return !strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox');
    }
	
	public function getCategories($categoriesId)
    {
        $children = Mage::getModel('catalog/category')->getCategories($categoriesId, 1, 'position', true);
	    $allCategories = array();
        foreach($children as $category) {
            $allCategories[] = $category;
            if($category->hasChildren()) {
                $allCategories = array_merge($allCategories, $this->getCategories($category->getId()));
            }
        }
        return $allCategories;
    }

    /**
     * @param Varien_DB_select
     * @return Varien_DB_select
     */
    public function addManufacturerFilter(Varien_Db_Select $select){
        if($this->isModuleEnabled('Aitoc_Aitmanufacturers') && Mage::helper('aitmanufacturers')->canUseLayeredNavigation(Mage::registry('shopby_attribute'), true))
        {
            $code=$this->getParam('shopby_attribute');
            if(!empty($code)){
                $attributeId=Mage::getModel('aitmanufacturers/config')->getAttributeId($code);
                if(!$this->_isAttributeSearchable($attributeId)){
                    $session = Mage::getSingleton('core/session');
                    $manufactureId = $session->getAitocManufacturersCurrentManufacturerId();
                    $productIds = Mage::getModel('aitmanufacturers/aitmanufacturers')->getProductsByManufacturer($manufactureId, Mage::app()->getStore()->getId(), $attributeId);
                    $select->where('e.entity_id IN (?)', $productIds);
                }
            }
        }
        return $select;
    }

    /**
     * @param string $attributeId
     * @return boolean
     */
    private function _isAttributeSearchable($attributeId)
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $collection
            ->setItemObjectClass('catalog/resource_eav_attribute')
            ->addStoreLabel(Mage::app()->getStore()->getId())
            ->setOrder('position', 'ASC');
        $collection->addIsFilterableFilter()->addFieldToFilter('additional_table.attribute_id', array('=' => $attributeId));
        $collection->load();
        return (bool)$collection->count();
    }

}