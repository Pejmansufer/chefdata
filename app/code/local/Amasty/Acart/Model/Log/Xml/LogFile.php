<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


class Amasty_Acart_Model_Log_Xml_LogFile extends Mage_Core_Model_Abstract
{
    const LOG_XNL_FILENAME = 'amasty_abandoned_execute_log.xml';

    /**
     * @return string
     */
    public function getLogFilename()
    {
        return self::LOG_XNL_FILENAME;
    }

    /**
     * @return string
     */
    public function getLogDirectory()
    {
        $logDirectory = Mage::getBaseDir('var') . DS . 'log';

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory);
            chmod($logDirectory, 0750);
        }

        return $logDirectory;
    }

    /**
     * @return string
     */
    public function getPathToFile()
    {
        return $this->getLogDirectory() . DS . $this->getLogFilename();
    }

    /**
     * @return bool
     */
    public function createFile()
    {
        $file = new DOMDocument('1.0','UTF-8');
        $file->formatOutput = true;
        $root = $file->createElement('log');
        $file->appendChild($root);

        $file->save($this->getPathToFile());

        return true;
    }

    /**
     * @return DOMDocument
     */
    public function loadFile()
    {
        $file = new DOMDocument();
        $file->formatOutput = true;
        $file->load($this->getPathToFile(), LIBXML_NOBLANKS);

        return $file;
    }

    /**
     * @return DOMDocument
     */
    public function getLogFile()
    {
        $pathToFile = $this->getPathToFile();

        if (!file_exists($pathToFile)) {
            $this->createFile();

        }

        return $this->loadFile();
    }

    /**
     * @param DOMDocument $domObject
     * @return bool
     */
    public function saveLogFile($domObject)
    {
        return $domObject->save($this->getPathToFile());
    }
}
