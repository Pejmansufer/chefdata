<?php
require_once 'abstract.php';
 
class GoogleProductFeedGenerator_Shell_Feed extends Mage_Shell_Abstract
{
    protected $_argname = array();
 
    public function __construct() {
        parent::__construct();
 
        // Time limit to infinity
        set_time_limit(0);     
		ini_set('memory_limit','4096M');
        // Get command line argument named "argname"
        // Accepts multiple values (comma separated)
        if($this->getArg('argname')) {
            $this->_argname = array_merge(
                $this->_argname,
                array_map(
                    'trim',
                    explode(',', $this->getArg('argname'))
                )
            );
        }
    }
 
    // Shell script point of entry
    public function run() {
		
		$products=Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect(array('name','price','status','rw_google_base_12_digit_sku','manufacturer','sku'))->load();
		
		$writer = new XMLWriter();
		$writer->openURI('../media/feeds/google_base_product.xml');
		$writer->startDocument("1.0");
		$writer->startElement("Feed");
		$writer->writeAttribute("xmlns:vc","http://www.w3.org/2007/XMLSchema-versioning");
		$writer->writeAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
		$writer->writeAttribute("xsi:noNamespaceSchemaLocation","http://www.google.com/shopping/reviews/schema/product/2.1/product_reviews.xsd");
		
		$writer->startElement("aggregator");
		$writer->writeElement("name","Chef's Deal Reviews Aggregator");
		$writer->endElement();
		
		$writer->startElement("publisher");
		$writer->writeElement("name","Chef's Deal Restaurant Equipment");
		$writer->writeElement("favicon","https://www.cdrestaurantequipment.com/skin/frontend/default/default/favicon.ico");
		$writer->endElement();
		
		$writer->startElement("reviews");
		
		foreach($products as $product){
			
			if($product->getStatus()!=1) continue;
			
			$productId = $product->getId();
			$reviews = Mage::getModel('review/review')
							->getResourceCollection()
							->addStoreFilter(Mage::app()->getStore()->getId())
							->addEntityFilter('product', $productId)
							->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
							->setDateOrder()
							->addRateVotes();

			if (count($reviews) > 0) {
				
				$data = Mage::getModel('review/review_summary')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productId);
				$summary = $data->getRatingSummary();
				$count = $data->getReviewsCount();
				$star = (5 * $summary) / 100;
				
				foreach ($reviews->getItems() as $review) {
					
					$writer->startElement("review");
					$writer->writeElement("review_id",$review->getId());
					
					$writer->startElement("reviewer");
					$writer->writeElement("name",$review->getNickname());
					$writer->endElement();
					
					$review_date=new DateTime($review->getCreatedAt());
					
					$writer->writeElement("review_timestamp",$review_date->format(DateTime::ATOM));
					
					$writer->writeElement("content",$review->getDetail());
					
					$writer->startElement("review_url");
					$writer->writeAttribute("type","singleton");
					$writer->text(str_replace("gpf_generate.php/","",$review->getReviewUrl()));
					$writer->endElement();
					
					$writer->startElement("ratings");
					$writer->startElement("overall");
					$writer->writeAttribute("min","1");
					$writer->writeAttribute("max","5");
					$writer->text($star);
					$writer->endElement();
					$writer->endElement();
					
					$writer->startElement("products");
					$writer->startElement("product");
					
					$writer->startElement("product_ids");
					
					if($product->getData("rw_google_base_12_digit_sku")!=""){
						$writer->startElement("gtins");
						$writer->writeElement("gtin",$product->getData("rw_google_base_12_digit_sku"));
						$writer->endElement();
					}
					
					$writer->startElement("brands");
					$writer->writeElement("brand",$product->getAttributeText("manufacturer"));
					$writer->endElement();
					
					$writer->startElement("skus");
					$writer->writeElement("sku",$product->getData("sku"));
					$writer->endElement();
					
					$writer->endElement();
					
					$writer->writeElement("product_url",str_replace("gpf_generate.php/","",$product->getProductUrl()));
					$writer->endElement();
					$writer->endElement();
					
					$writer->endElement();
					
				}
				
			}
			else{
				continue;
			}
			
		}
		
		$writer->endElement();
		$writer->endElement();
		$writer->endDocument();
		$writer->flush();
		
		printf(count($products).'\n');
    }
 
    // Usage instructions
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f scriptname.php -- [options]
 
  --argname <argvalue>       Argument description
 
  help                   This help
 
USAGE;
    }
}

$shell = new GoogleProductFeedGenerator_Shell_Feed();
$shell->run();