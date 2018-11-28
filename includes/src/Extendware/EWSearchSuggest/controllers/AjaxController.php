<?php
class Extendware_EWSearchSuggest_AjaxController extends Extendware_EWCore_Controller_Frontend_Action
{
    public function suggestAction()
	{
		if ($this->mHelper('config')->isEnabled() === false) return '';
		
		if (@method_exists(Mage_Bundle_Model_Product_Price, 'getTotalPrices') === true) {
			$this->loadLayout('ewsearchsuggest_price_handle');
		} else {
			$this->loadLayout('ewsearchsuggest_price_old_handle');
		}
		
		$queryText = $this->getRequest()->getParam('q', false);
        if (!$queryText || $queryText == '') exit;
        
		$queryObject = Mage::helper('catalogsearch')->getQuery();
        $queryObject->setStoreId(Mage::app()->getStore()->getId());
        $queryObject->prepare();
        if ($this->mHelper('config')->isDisplayInTermsEnabled() === false) {
	        if ($queryObject->getData('display_in_terms') === null) {
	        	$queryObject->setDisplayInTerms(0);
	        }
        }
        $queryObject->save(); 
        
		$queries = null;
		if ($this->mHelper('config')->isQueryEnabled()) {
			$sortBy = $this->mHelper('config')->getQuerySortBy();
			$collection = Mage::getResourceModel('catalogsearch/query_collection');
			$collection->setStoreId(Mage::app()->getStore()->getId());
			$collection->setQueryFilter($queryText);
			$collection->getSelect()->limit($this->mHelper('config')->getQueryMaxItems());

			if (is_array($sortBy)) {
				$collection->getSelect()->reset(Zend_Db_Select::ORDER);
				$collection->setOrder($sortBy['column'], $sortBy['direction']);
			}
			
			$maxNameLength = $this->mHelper('config')->getQueryMaxNameLength();
			foreach ($collection as $item) {
				$name = $item->getQuery() ? $item->getQuery() : $item->getQueryText();
				if (strtolower($name) == strtolower($queryText)) {
					$collection->removeItemByKey($item->getId());
				}
				
				$item->setData('ewname', $name);
				$item->setData('ewurl', Mage::helper('catalogsearch')->getResultUrl($name));
			}
			
			if ($collection->count() > 0) {
				$queries = $collection;
			}
        }
        
        $products = $totalProductCount = null;
		if ($this->mHelper('config')->isProductEnabled()) {
			$sortBy = $this->mHelper('config')->getProductSortBy();
            $collection = Mage::getResourceModel('catalogsearch/fulltext_collection');
            $layer = new Mage_CatalogSearch_Model_Layer(); // done to avoid rewrites that conflict
            $layer->prepareProductCollection($collection);
            $collection->addAttributeToSelect('name');
            $collection->getSelect()->limit($this->mHelper('config')->getProductMaxItems()); 
            
            if ($this->mHelper('config')->isInStockRequired() === true) {
            	Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
            }
            
            $collection->getSelect()->reset(Zend_Db_Select::ORDER);
            if ($this->mHelper('config')->isSortNoImagesLast()) {
            	$select = $collection->getSelect();
				if ($collection->isEnabledFlat() === true) {
					$case = new Zend_Db_Expr('CASE
						WHEN small_image = "no_selection" THEN 0
						WHEN small_image != "no_selection" THEN 1
					END DESC');
					$select->order($case);
				} else if (strpos($select, 'at_image') === false) {
					$collection->addAttributeToSelect('image')->addAttributeToFilter('image', array('neq' => '###'));
					$case = new Zend_Db_Expr('CASE
						WHEN image = "no_selection" THEN 0
						WHEN image != "no_selection" THEN 1
					END DESC');
					$select->order($case);
				}
            }
            
			if (is_array($sortBy)) {
				$column = $sortBy['column'];
				$dir = $sortBy['direction'];
				if (strpos($column, 'ps:') === 0) {
					$column = str_replace('ps:', '', $column);
					$method = Mage::getSingleton('ewpsorting/method');
					if ($method) {
						$sorter = $method->loadByCode($column);
						if ($sorter) $sorter->apply($collection, $dir, true);
					}
				} else {
					$select = $collection->getSelect();
					$select->order(new Zend_Db_Expr(sprintf('%s %s', $column, $dir)));
				}
			}

			//$collection->getSelect()->reset(Zend_Db_Select::COLUMNS);
			//$collection->getSelect()->columns(array(new ZEND_DB_EXPR('SQL_CALC_FOUND_ROWS e.entity_id AS entity_id')));
			
			$select = (string)$collection->getSelect();
			$select = preg_replace('/^select/i', 'select SQL_CALC_FOUND_ROWS', $select);
			$rows = Mage::getSingleton('core/resource')->getConnection('catalog_read')->fetchAll($select);

			$meta = Mage::getSingleton('core/resource')->getConnection('catalog_read')->fetchRow('SELECT FOUND_ROWS() as total;');
			$totalProductCount = $meta['total'];
			
			$products = new Varien_Data_Collection();
            foreach ($rows as $row) {
				$product = Mage::getModel('catalog/product')->load($row['entity_id']);
				$product->setData('ewname', $product->getData($this->mHelper('config')->getProductNameSource()));
				$product->setData('ewdescription', $product->getData($this->mHelper('config')->getProductDescriptionSource()));
				$product->setData('ewurl', $product->getProductUrl());
				if ($product->getData('ewname')) {
					$products->addItem($product);
				}
            }
        }
        
        $categories = null;
		if ($this->mHelper('config')->isCategoryEnabled()) {
			$sortBy = $this->mHelper('config')->getCategorySortBy();
			$query = Mage::helper('catalogsearch')->getEscapedQueryText();
			$limit = $this->mHelper('config')->getCategoryMaxItems();
			$sortyBySql = @($sortBy['column'] == 'relevance' ? join(' ' , $sortBy) : '');

			if ($this->mHelper('config')->getCategorySearchEngine() == 'sphinx') {
				$results = Mage::helper('ewsphinx/api')->searchCategories($query, $limit);
				$categoryIds = array_keys($results);
			} else $categoryIds = Mage::getResourceModel('ewsearchsuggest/fulltext_category')->getIdsByQuery($query, $limit, $sortyBySql);
			if (empty($categoryIds) === false) {
				$collection = Mage::getModel('catalog/category')->getCollection();
				$collection->addAttributeToFilter('is_active', 1);
				$collection->addAttributeToFilter('entity_id', array('in' => $categoryIds));
				$collection->addAttributeToSelect('*');
				if (is_array($sortBy) and $sortBy['column'] != 'relevance') {
					$collection->getSelect()->reset(Zend_Db_Select::ORDER);
					$collection->setOrder($sortBy['column'], $sortBy['direction']);
				}
	        
				if (!Mage::getStoreConfig(Mage_Catalog_Helper_Category_Flat::XML_PATH_IS_ENABLED_FLAT_CATALOG_CATEGORY)) {
					$collection = $collection->addAttributeToSelect('url');
				}
	            	
	            $categories = $collection->load();
	            if (is_array($sortBy) and $sortBy['column'] != 'relevance') {
	            	$categoryIds = array();
	            	foreach ($categories as $category) $categoryIds[] = $category->getId();
	            }
	            foreach ($categoryIds as $categoryId) {
	            	$category = $categories->getItemById($categoryId);
	            	if (!$category) continue;
	            	$categories->removeItemByKey($category->getId());
	            	if ($this->mHelper('config')->getCategoryNameSource() == 'path') {
	            		$category->setData('ewname', $this->mHelper()->getSnippedCategoryPath($category, $this->mHelper('config')->getCategoryPathSnippetFormat()));
	            	} else {
						$category->setData('ewname', $category->getData($this->mHelper('config')->getCategoryNameSource()));
	            	}
						$category->setData('ewdescription', $category->getData($this->mHelper('config')->getCategoryDescriptionSource()));
						$category->setData('ewurl', $category->getUrl());
					if ($category->getData('ewname')) {
						$categories->addItem($category);
					}
	            }
			}
        }
        
		$pages = null;
		if ($this->mHelper('config')->isPageEnabled()) {
			$sortBy = $this->mHelper('config')->getPageSortBy();
			$query = Mage::helper('catalogsearch')->getEscapedQueryText();
			$limit = $this->mHelper('config')->getPageMaxItems();
			$sortyBySql = @($sortBy['column'] == 'relevance' ? join(' ' , $sortBy) : '');
			
			$pageIds = array();
			if ($this->mHelper('config')->getPageSearchEngine() == 'sphinx') {
				$pageResults = Mage::helper('ewsphinx/api')->searchPages($query, $limit);
				$pageIds = array_keys($pageResults);
			} else $pageIds = Mage::getResourceModel('ewsearchsuggest/fulltext_page')->getIdsByQuery($query, $limit, $sortyBySql);
			if (empty($pageIds) === false) {
				$collection = Mage::getModel('cms/page')->getCollection();
				$collection->addFieldToFilter('page_id', array('in' => $pageIds));
				if (is_array($sortBy) and $sortBy['column'] != 'relevance') {
					$column = $sortBy['column'];
					if ($column == 'name') $column = 'title';
					$collection->getSelect()->reset(Zend_Db_Select::ORDER);
					$collection->setOrder($column, $sortBy['direction']);
				}
				
				$pages = $collection->load();
				if (is_array($sortBy) and $sortBy['column'] != 'relevance') {
	            	$pageIds = array();
	            	foreach ($pages as $page) $pageIds[] = $page->getId();
	            }
	            foreach ($pageIds as $pageId) {
	            	$page = $collection->getItemById($pageId);
	            	if (!$page) continue;
	            	$pages->removeItemByKey($page->getId());
						$page->setData('ewname', $page->getData($this->mHelper('config')->getPageNameSource()));
						$page->setData('ewdescription', $page->getData($this->mHelper('config')->getPageDescriptionSource()));
						$page->setData('ewurl', Mage::helper('cms/page')->getPageUrl($page->getId()));
					if ($page->getData('ewname')) {
						$pages->addItem($page);
					}
	            }
			}
        }
        
        $block = $this->getLayout()->getBlock('ewsearchsuggest_autocomplete');
        $block->setQuerySuggestions($queries);
        $block->setProducts($products);
        $block->setCategories($categories);
        $block->setPages($pages);
        $block->setTotalProductCount($totalProductCount);
        
        if ($totalProductCount === null or $this->mHelper('config')->isSaveQueriesEnabled() === false) {
	        if ($queryObject->isObjectNew() === true) {
	        	$queryObject->delete();
	        }
        } 
        
        if ($queryObject->getId() > 0 and $queryObject->isDeleted() === false) {
	        $queryObject->setNumResults($totalProductCount)->setPopularity($queryObject->getPopularity()+1)->save();
        }
        return $this->getResponse()->setBody($block->toHtml());
    }
}