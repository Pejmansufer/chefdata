<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */
class Amasty_Acart_Adminhtml_Amacart_QueueController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_run();

        Mage::getSingleton('adminhtml/session')->addNotice(
            'If there are no emails in the queue for a long time, please make sure that cron is setup for your Magento. Please read this for more information: <a target="_blank" href="http://support.amasty.com/index.php?/Knowledgebase/Article/View/72/24/magento-cron">'
            . ($this->__("here")) . '</a>'
        );

        $this->loadLayout();

        $this->_setActiveMenu('promo/amacart/queue');

        $this->_addContent($this->getLayout()->createBlock('amacart/adminhtml_queue'));
        $this->renderLayout();
    }

    public function massCancelAction()
    {
        $ids = $this->getRequest()->getParam('queue');

        if (is_array($ids) && count($ids) > 0) {

            Mage::getModel('amacart/schedule')->massCancel($ids);

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Actions has been canceled.')
            );

            $this->_redirect('*/*/index');
        }
    }

    protected function _run()
    {
        Mage::getModel('amacart/schedule')->run();
    }

    public function runAction()
    {
        $this->_run();

//        $msg = $this->__('Process has been successfully runned.');
//                
//        Mage::getSingleton('adminhtml/session')->addSuccess($msg);

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('promo/amacart');
    }
}
