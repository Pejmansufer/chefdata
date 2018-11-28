<?php
class Extendware_EWSearchSuggest_Block_Autocomplete extends Extendware_EWCore_Block_Mage_Core_Template
{
	protected $_priceBlock = array();
	protected $_block = 'catalog/product_price';
	protected $_priceBlockDefaultTemplate = '/extendware/ewsearchsuggest/catalog/product/price.phtml';
    protected $_priceBlockTypes = array();
    protected $_useLinkForAsLowAs = true;
    
	public function _construct()
    {
    	parent::_construct();
        $this->setTemplate('extendware/ewsearchsuggest/autocomplete.phtml');
    }
    
    public function getGroupedProducts() {
    	if ($this->hasProducts() === false) return null;

    	$products = $this->getProducts();
    	$groups = array();
    	if ($this->hasData('grouped_products') === false) {
    		if ($this->mHelper('config')->isProductGroupByCategoryEnabled() === false) {
	    		$groups[] = array(
    				'header' => $this->__('Products'), 
    				'products' => $this->getProducts(),
	    			'search_url' => Mage::helper('catalogsearch')->getResultUrl(Mage::helper('catalogsearch')->getQueryText()),
	    		);
    		} else {
    			$categoriesToProducts = $this->mHelper('utility')->getCategoriesToProducts($this->getProducts());
    			foreach ($categoriesToProducts as $group) {
    				$item = array(
    					'products' => $group['products'],
    					'search_url' => Mage::helper('catalogsearch')->getResultUrl(Mage::helper('catalogsearch')->getQueryText()),
    				);
    				if ($this->mHelper('config')->getProductCategoryNameSource() == 'path') {
	            		$item['header'] = $this->mHelper()->getSnippedCategoryPath($group['category'], $this->mHelper('config')->getProductCategoryPathSnippetFormat());
	            	} else {
	            		$item['header'] = $group['category']->getData($this->mHelper('config')->getProductCategoryNameSource());
	            	}
	            	
	    			if ($this->mHelper('config')->getProductMoreResultsMode() == 'category') {
						$item['search_url'] = $this->getUrl('catalogsearch/result', array(
							'_query' => array(Mage_CatalogSearch_Helper_Data::QUERY_VAR_NAME => Mage::helper('catalogsearch')->getQueryText(), 'cat' => $group['category']->getId()),
							'_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
    					));
	    			}
	    			$groups[] = $item;
    			}
    		}
    		$this->setData('grouped_products', $groups);
    	}
    	return $this->getData('grouped_products');
    }
    
	protected function _toHtml() {
		if ($this->hasResults() or $this->mHelper('config')->getNoResultsMessage()) {
			return parent::_toHtml();
		}
		return '';
	}
	
	public function hasResults() {
		return ($this->hasQuerySuggestions() or $this->hasProducts() or $this->hasCategories() or $this->hasPages());
	}
	
	public function hasContentResults() {
		return ($this->hasProducts() or $this->hasCategories() or $this->hasPages());
	}
	
	protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $block = $this->getLayout()->getBlock('ewsearchsuggest_catalog_product_price_template');
        if ($block) {
            foreach ($block->getPriceBlockTypes() as $type => $priceBlock) {
                $this->addPriceBlockType($type, $priceBlock['block'], $priceBlock['template']);
            }
        }

        return $this;
    }
    
	protected function _getPriceBlock($productTypeId)
    {
        if (!isset($this->_priceBlock[$productTypeId])) {
            $block = $this->_block;
            if (isset($this->_priceBlockTypes[$productTypeId])) {
                if ($this->_priceBlockTypes[$productTypeId]['block'] != '') {
                    $block = $this->_priceBlockTypes[$productTypeId]['block'];
                }
            }
            $this->_priceBlock[$productTypeId] = $this->getLayout()->createBlock($block);
        }
        return $this->_priceBlock[$productTypeId];
    }
    
	public function addPriceBlockType($type, $block = '', $template = '')
    {
        if ($type) {
            $this->_priceBlockTypes[$type] = array(
                'block' => $block,
                'template' => $template
            );
        }
    }
    
	protected function _getPriceBlockTemplate($productTypeId)
    {
        if (isset($this->_priceBlockTypes[$productTypeId])) {
            if ($this->_priceBlockTypes[$productTypeId]['template'] != '') {
                return $this->_priceBlockTypes[$productTypeId]['template'];
            }
        }
        return $this->_priceBlockDefaultTemplate;
    }


    public function _preparePriceRenderer($productType)
    {
        return $this->_getPriceBlock($productType)
            ->setTemplate($this->_getPriceBlockTemplate($productType))
            ->setUseLinkForAsLowAs($this->_useLinkForAsLowAs);
    }

    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
        $typeId = $product->getTypeId();
        $catalogHelper = Mage::helper('catalog');
        return $this->_preparePriceRenderer($typeId)
            ->setProduct($product)
            ->setDisplayMinimalPrice($displayMinimalPrice)
            ->setIdSuffix($idSuffix)
            ->toHtml();
    }
    
 	public function hasGroupedProducts() {
    	return $this->hasProducts();
    }
    
    public function getProductCount() {
    	return ($this->hasProducts() ? $this->getProducts()->count() : 0);
    }
    
    public function hasProducts() {
    	return is_object($this->getProducts()) and $this->getProducts()->count() > 0;
    }
    
	public function hasQuerySuggestions() {
    	return is_object($this->getQuerySuggestions()) and $this->getQuerySuggestions()->count() > 0;
    }
    
	public function hasCategories() {
    	return is_object($this->getCategories()) and $this->getCategories()->count() > 0;
    }
    
	public function hasPages() {
    	return is_object($this->getPages()) and $this->getPages()->count() > 0;
    }
}