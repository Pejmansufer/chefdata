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
 * Queued Popup Email resource model
 *
 * @category    Trmmarketing
 * @package     Trmmarketing_Subscribeconfirmed
 * @author      Ultimate Module Creator
 */
class Trmmarketing_Subscribeconfirmed_Model_Resource_Popupemail
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * constructor
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct(){
        $this->_init('trmmarketing_subscribeconfirmed/popupemail', 'entity_id');
    }
}
