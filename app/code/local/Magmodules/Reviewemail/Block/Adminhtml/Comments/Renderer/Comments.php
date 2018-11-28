<?php 
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Reviewemail
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Block_Adminhtml_Comments_Renderer_Comments extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

	public function render(Varien_Object $row) {
		$html = '<table><tr>';
		$order = Mage::getModel('sales/order');
		$order->load($row->getOrderId());
		$comments = $order->getStatusHistoryCollection();
		$comments_html = '<table>';

		foreach($comments as $comment) {
			$date = Mage::helper('core')->formatDate($comment->getCreatedAt(), 'medium', false);
			$text = $comment->getComment();
			$status = $comment->getStatus();
			$comments_html .= '<tr><td width="100"><strong>' . $date . '</td><td> ' . $status . '</td><td>' . nl2br($text) . '</td></tr>';
		}

		$comments_html .= '</table>';
		$html = '<a href="#" class="magtooltip"><img src="' . $this->getSkinUrl('/magmodules/core/images/comments.png') .'"><span>' . $comments_html . '</span></a>';

		return $html;
	}

}