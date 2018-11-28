<?php
/**
 * @category    Trmmarketing
 * @package     Trmmarketing_PopupWidgets
 * @copyright   Copyright (c) 2014 TRM Marketing LLC
 * @license     http://www.trm-marketing.com/solutions/license/TRM-Marketing-Standard-License-Agreement.html
 */
class Trmmarketing_PopupWidgets_Block_Socialshare
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

    /**
     * A model to serialize attributes
     * @var Varien_Object
     */
    protected $_serializer = null;

    /**
     * Initialization
     */
    protected function _construct()
    {
        $this->_serializer = new Varien_Object();
        parent::_construct();
    }

    
    protected function _toHtml()
    {
       
		$html = '';
		
		$facebookshare = $this->getData('facebookshare');
		$this->assign('facebookshare', $facebookshare);
		$facebookshareurl = $this->getData('facebookshareurl');
		$this->assign('facebookshareurl', $facebookshareurl);
		
		$twittershare = $this->getData('twittershare');
		$this->assign('twittershare', $twittershare);
		$twittertweet = $this->getData('twittertweet');
		$this->assign('twittertweet', $twittertweet);
		
		$googleplusshare = $this->getData('googleplusshare');
		$this->assign('googleplusshare', $googleplusshare);
		$googleplusshareurl = $this->getData('googleplusshareurl');
		$this->assign('googleplusshareurl', $googleplusshareurl);
		
		$pinterestshare = $this->getData('pinterestshare');
		$this->assign('pinterestshare', $pinterestshare);
		$pinterestpinurl = $this->getData('pinterestpinurl');
		$this->assign('pinterestpinurl', $pinterestpinurl);
		$pinterestmediaurl = $this->getData('pinterestmediaurl');
		$this->assign('pinterestmediaurl', $pinterestmediaurl);
		$pinterestdescription = $this->getData('pinterestdescription');
		$this->assign('pinterestdescription', $pinterestdescription);
		
		$linkedinshare = $this->getData('linkedinshare');
		$this->assign('linkedinshare', $linkedinshare);
		$linkedinshareurl = $this->getData('linkedinshareurl');
		$this->assign('linkedinshareurl', $linkedinshareurl);
		$linkedintitle = $this->getData('linkedintitle');
		$this->assign('linkedintitle', $linkedintitle);
		$linkedinsummary = $this->getData('linkedinsummary');
		$this->assign('linkedinsummary', $linkedinsummary);
		$linkedinsource = $this->getData('linkedinsource');
		$this->assign('linkedinsource', $linkedinsource);
		
        return parent::_toHtml();
    }
	
	


}
