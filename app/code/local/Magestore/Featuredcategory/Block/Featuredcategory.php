<?php
class Magestore_Featuredcategory_Block_Featuredcategory extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    public function getFeaturedCategoryCollection()
    {
    	$ids=array();
    	$catCollection=Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->addFieldToFilter('fea_category',1);
   		return $catCollection;
    }
	public function addLinkCategory()
	{
		 $footerBlock = $this->getParentBlock();
		 if($footerBlock)
		 $footerBlock->addLink($this->__('Categories'),'featuredcategory',
		 'Featured Categories',true,array(),10
		);
	}
    public function getFeaturedImage($cat)
    {
    	$category=Mage::helper('featuredcategory');
		$category->init($cat);
		if(!$cat->getImage())
		{
            return false;
		}
    	else
		{
			$src=$category->resize(135);
			$img = "<img  src='". $src . "' title='". $cat->getName()."' border='0'/>";
			return $img;
		}
    }
    public function getFeaturedCategoryBeginBy($begin)
    {
    	$cats=array();
		$catCollection=Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->addFieldToFilter('is_active',1);
    	if(count($catCollection)){
	    	foreach($catCollection as $cat)
	    	{
	    		$name=$cat->getName();
	    		if($cat->getParentId()!=1){
		    		if(!$begin){
		    			$cats[]=$cat;
		    		}else{
			    		if(!strcmp($begin,strtoupper($name[0])))
			    		{
			    			$cats[]=$cat;
			    		}
		    		}
	    		}
	    	}
    	}
    	return $cats;
    }
    public function generateListCharacter()
	{
		$lists = array('1','2','3','4','5','6','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','U','V','X','Y','Z');		
		
	    echo("<ul id='featuredcategory_char_filter'>");
		
		echo("<li style='display:inline;'><a href='".$this->getCharSearchUrl("All") . "'>" . "All" . "</a></li>");
		
		for($i = 0; $i < count($lists); $i++)
		{
			echo("<li style='display:inline;'><a href='".$this->getCharSearchUrl($lists[$i]) . "'>    " . $lists[$i] . "    </a></li>");
		}
		
		echo("</ul>");
		
	} 
    
    public function getCharSearchUrl($begin)
	{
		$lists = array('1','2','3','4','5','6','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','U','V','X','Y','Z');		
		if(!in_array($begin,$lists))
		{
			return $url = $this->getUrl("featuredcategory/index/index", array());
		}
		
		return $this->getUrl("featuredcategory/index/index", array("begin"=>$begin));

	}
}