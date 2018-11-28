<?php
/**
 * Trmmarketing_Subscribeconfirmed extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Trmmarketing
 * @package        Trmmarketing_Subscribeconfirmed
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Queued Popup Email model
 *
 * @category    Trmmarketing
 * @package     Trmmarketing_Subscribeconfirmed
 * @author      Ultimate Module Creator
 */
class Trmmarketing_Subscribeconfirmed_Model_Popupemail
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'trmmarketing_subscribeconfirmed_popupemail';
    const CACHE_TAG = 'trmmarketing_subscribeconfirmed_popupemail';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'trmmarketing_subscribeconfirmed_popupemail';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'popupemail';
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct(){
        parent::_construct();
        $this->_init('trmmarketing_subscribeconfirmed/popupemail');
    }
    /**
     * before save queued popup email
     * @access protected
     * @return Trmmarketing_Subscribeconfirmed_Model_Popupemail
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * save popupemail relation
     * @access public
     * @return Trmmarketing_Subscribeconfirmed_Model_Popupemail
     * @author Ultimate Module Creator
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
}
