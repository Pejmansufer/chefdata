<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */
class Amasty_Acart_Adminhtml_Amacart_LogController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return void
     */
    public function downloadAction()
    {
        /** @var \Amasty_Acart_Model_Log_Xml_LogFile $logFileModel */
        $logFileModel = Mage::getModel('amacart/log_xml_logFile');

        try {
            if ($logFileModel->getLogFile()) {
                $logFile = $logFileModel->getPathToFile();

                $this->_prepareDownloadResponse(
                    $logFileModel->getLogFilename(),
                    array(
                        'value' => $logFile,
                        'type' => 'filename'
                    ));
            } else {
                Mage::throwException("File not found");
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_forward('noRoute');
        }
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('promo/amacart');
    }
}
