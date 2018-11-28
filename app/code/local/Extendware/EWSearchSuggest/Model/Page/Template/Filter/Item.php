<?php
class Extendware_EWSearchSuggest_Model_Page_Template_Filter_Item extends Mage_Cms_Model_Template_Filter {

	protected $_designSettings;
	protected $_useSessionInUrl = false;
	
	protected function _applyDesignSettings() {
		if ($this->getDesignSettings()) {
			$design = Mage::getDesign();
			$this->getDesignSettings()
			->setOldArea($design->getArea())
			->setOldStore($design->getStore());

			if ($this->getDesignSettings()->getArea()) {
				Mage::getDesign()->setArea($this->getDesignSettings()->getArea());
			}

			if ($this->getDesignSettings()->getStore()) {
				Mage::app()->getLocale()->emulate($this->getDesignSettings()->getStore());
				$design->setStore($this->getDesignSettings()->getStore());
				$design->setPackageName('');
				$design->setTheme('');
			}
		}
		return $this;
	}

	public function setDesignSettings(array $settings) {
		$this->getDesignSettings()->setData($settings);
		return $this;
	}

	protected function _resetDesignSettings() {
		if ($this->getDesignSettings()) {
			if ($this->getDesignSettings()->getOldArea()) {
				Mage::getDesign()->setArea($this->getDesignSettings()->getOldArea());
			}

			if ($this->getDesignSettings()->getOldStore()) {
				Mage::getDesign()->setStore($this->getDesignSettings()->getOldStore());
				Mage::getDesign()->setPackageName('');
				Mage::getDesign()->setTheme('');
			}
		}
		Mage::app()->getLocale()->revert();
		return $this;
	}

	public function getDesignSettings() {
		if (is_null($this->_designSettings)) {
			$this->_designSettings = new Varien_Object();
		}
		return $this->_designSettings;
	}

	public function process($content) {
		$this->_applyDesignSettings();
		try {
			$result = $this->filter($content);
		} catch (Exception $e) {
			$this->_resetDesignSettings();
			throw $e;
		}
		$this->_resetDesignSettings();
		return $result;
	}
}
