<?php

$installer = $this;


$installer->startSetup();


$entity_type = Mage::getSingleton("eav/entity_type")->loadByCode("catalog_category");
$entity_type_id = $entity_type->getId();
$collection = Mage::getModel("eav/entity_attribute")
			->getCollection()
			->addFieldToFilter("entity_type_id",$entity_type_id)
			->addFieldToFilter("attribute_code","fea_category");
		
if(!count($collection))
{
	
	$attribute = $collection->getFirstItem();
	$data = array();
	$data['id'] = null;
	$data['entity_type_id'] = $entity_type_id;
	$data['attribute_code'] = "fea_category";
	$data['backend_type'] = "int";
	$data['frontend_input'] = "select";
	$data['frontend_label'] = 'Featured Category';     
	$attribute->setData($data);
	$attribute->save();

	$resource = Mage::getSingleton('core/resource');
	$read= $resource->getConnection('core_read');	
	$write = $resource->getConnection('core_write');

	//get entity_type_id
	$entity_type = Mage::getSingleton("eav/entity_type")->loadByCode("catalog_category");
	$entity_type_id = $entity_type->getId();

	//get attribute_set_id	
	$select = $read->select()
				   ->from($this->getTable("eav_attribute_set"),array('attribute_set_id'))
				   ->where("entity_type_id=?",$entity_type_id);
	$attribute_sets = $read->fetchAll($select);		

	foreach($attribute_sets as $attribute_set) {
		$attribute_set_id = $attribute_set['attribute_set_id'];
		$select = $read->select()
					   ->from($this->getTable("eav_attribute"),array('attribute_id'))
					   ->where("entity_type_id=?",$entity_type_id)
					   ->where("attribute_code=?","fea_category");
		$attribute = $read->fetchRow($select);		
		$attribute_id = $attribute['attribute_id'];

		$select = $read->select()
					   ->from($this->getTable("eav_attribute_group"),array('attribute_group_id'))
					   ->where("attribute_set_id=?",$attribute_set_id)
					   ->where("attribute_group_name=?","General Information");
		$attribute_group = $read->fetchRow($select);		
		$attribute_group_id = $attribute_group['attribute_group_id'];

		$write->beginTransaction();
		$write->insert($this->getTable("eav_entity_attribute"),array("entity_type_id"=>$entity_type_id,"attribute_set_id"=>$attribute_set_id,"attribute_group_id"=>$attribute_group_id,"attribute_id"=>$attribute_id,"sort_order"=>0));
		$sql="update eav_attribute set source_model=' 	eav/entity_attribute_source_boolean' where attribute_code='fea_category';";
		$write->query($sql);
		$write->commit();
	}
}


$installer->endSetup(); 