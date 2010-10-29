<?php
App::import('Vendor', 'Find.find_app_model');

class AppModel extends FindAppModel {
	var $recursive = -1;
	var $actsAs = array('Containable');
	
	function save($data = null, $validate = true, $fieldList = array()) {
		if(Configure::read('demo')) {
			$Fake = ClassRegistry::init('Fake');
			$this->id = time();
			return $Fake->save($this->name, $data);
		}
		
		return parent::save($data, $validate, $fieldList);
	}

	function delete($id = null, $cascade = true) {
		if(Configure::read('demo')) {
			return true;
		}
		
		return parent::delete($id, $cascade);
	}
	
	function updateAll($fields, $conditions = true) {
		if(Configure::read('demo')) {
			return true;
		}
		
		return parent::updateAll($fields, $conditions);
	}
}
?>