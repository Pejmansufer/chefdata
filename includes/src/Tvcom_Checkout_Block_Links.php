<?php
/** 
 * @author      Tony Vu <vuductrung2003@gmail.com>
 */
class Tvcom_Checkout_Block_Links extends Mage_Checkout_Block_Links{
    public function addCartLink(){
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
            $count = $this->getSummaryQty() ? $this->getSummaryQty()
                : $this->helper('checkout/cart')->getSummaryCount();
            
            $text = $this->__('My Cart');            
            $parentBlock->removeLinkByUrl($this->getUrl('checkout/cart'));
            if($count == 0){
                $parentBlock->addLink('<span class="cart-text"> ' . $text . '</span>', 'checkout/cart', $text, true, array(), 50, null, 'class="top-link-cart"');
            }
            else{
                $parentBlock->addLink('<span class="cart-text"> ' . $text . '</span> <span class="item-count">' . $count . '</span>', 'checkout/cart', $text, true, array(), 50, null, 'class="top-link-cart"');
            }
        }
        return $this;
    }
}
