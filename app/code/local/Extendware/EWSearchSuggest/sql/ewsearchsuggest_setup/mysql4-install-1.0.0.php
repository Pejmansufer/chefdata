<?php
Mage::helper('ewcore/cache')->clean();
$installer = $this;

$installer->startSetup();
$installer->endSetup();

try {
	$incompatModules = array('Fooman_Speedster', 'GT_Speed');
	foreach ($incompatModules as $module) {
		$model = Mage::getSingleton('ewcore/module');
		if (!$model) continue;
		
		$module = $model->load($module);
		if ($module->isActive() === false) continue;
		
		Mage::getModel('compiler/process')->registerIncludePath(false);
		$configTools = Extendware::helper('ewcore/config_tools');
		if ($configTools) $configTools->disableModule($module);
	}
} catch (Exception $e) {}