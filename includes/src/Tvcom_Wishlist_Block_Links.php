<?php

/*
 * Author: Tony Vu
 * Description: use bootstrap Badges in the toplinks
 */
class Tvcom_Wishlist_Block_Links extends Mage_Wishlist_Block_Links{
    
     protected function _toHtml(){
        if ($this->helper('wishlist')->isAllow() && ($this->_getItemCount() != 0)) {
            $text = $this->_createLabel($this->_getItemCount());
            $this->_label = '<i class="wishlist-icon fa fa-gift"></i> <span class="wishlist-text">My Wishlist</span> <span class="item-count">' . $this->_getItemCount() . '</span>';
            $this->_title = "My Wishlist";
            $this->_url = $this->getUrl('wishlist');
            return Mage_Page_Block_Template_Links_Block::_toHtml();
        }
    }
}
?>
