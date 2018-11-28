<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Model_Log_Xml_Reader extends Mage_Core_Model_Abstract
{
    /**
     * @return string
     */
    public function readLogFile()
    {
        /** @var \Amasty_Acart_Model_Log_Xml_LogFile $logFileModel */
        $logFileModel = Mage::getModel('amacart/log_xml_logFile');

        /** @var DOMDocument $logFile */
        $logFile = $logFileModel->getLogFile();

        return $logFile;
    }

    /**
     * @return bool
     */
    public function cleanLogByTime()
    {
        /** @var \Amasty_Acart_Model_Log_Xml_LogFile $logFileModel */
        $logFileModel = Mage::getModel('amacart/log_xml_logFile');

        /** @var DOMDocument $logFile $logFile */
        $logFile  = $this->readLogFile();
        $root = $logFile->documentElement;
        $results = $root->getElementsByTagName('time');
        $logPeriod = Mage::helper('amacart')->getValidLogPeriodTimestamp();

        /** @var DOMElement $result */
        foreach ($results as $result) {
            $logTime = strtotime($result->getAttribute('value'));

            if ($logTime <= $logPeriod) {
                $root->removeChild($result);
            }
        }

        return $logFileModel->saveLogFile($logFile);
    }
}
