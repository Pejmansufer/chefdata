<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Model_Log_Xml_Writer extends Mage_Core_Model_Abstract
{
    /**
     * @var DOMDocument
     */
    protected $logFile;

    /**
     * @param array $logData
     * @return bool
     */
    public function writeLogFile(array $logData)
    {
        /** @var \Amasty_Acart_Model_Log_Xml_LogFile $logFileModel */
        $logFileModel = Mage::getModel('amacart/log_xml_logFile');
        $this->openLogFile($logFileModel);

        if ($this->logFile) {
            /** @var DOMElement $root */
            $root = $this->logFile->documentElement;

            $this->parseAndWriteLogData($logData, $root);
            $logFileModel->saveLogFile($this->logFile);
        }

        return true;
    }

    /**
     * @param \Amasty_Acart_Model_Log_Xml_LogFile $logFileModel
     * @return DOMDocument
     */
    private function openLogFile($logFileModel)
    {
        if (!$this->logFile) {
            $this->logFile = $logFileModel->getLogFile();
        }

        return $this->logFile;
    }

    /**
     * @param array $logData
     * @param DOMElement $root
     */
    private function parseAndWriteLogData($logData, $root)
    {
        /** @var DOMElement $result */
        $result = $root->appendChild($this->logFile->createElement('time'));
        $result->setAttribute('value', $logData['time']);

        $scheduleSection = $this->createDOMElement($result, 'schedule');
        $this->writeSection($logData['schedule'], $scheduleSection);

        $sendSection =$this->createDOMElement($result, 'send');
        $this->writeSection($logData['send'], $sendSection);
    }

    /**
     * @param array $sectionData
     * @param DOMElement $section
     *
     * @return DOMElement
     */
    private function writeSection($sectionData, $section)
    {
        if (is_array($sectionData) && $sectionData) {
            foreach($sectionData as $element => $value) {
                $child = $this->createDOMElement($section, $element, (is_array($value) ? null : $value));

                if (is_array($value)) {
                    $this->writeSection($value, $child);
                }
            }
        }

        return $section;
    }

    /**
     * @param DOMElement $root
     * @param string $elementName
     * @param string $elementValue
     * @return DOMNode
     */
    private function createDOMElement($root, $elementName, $elementValue = '')
    {
        return $root->appendChild($this->logFile->createElement($elementName, $elementValue));
    }
}
