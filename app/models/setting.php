<?php
/***************************************************************************
 20Couch

 Copyright (c) 2009-2010 Matt Curry

 @author      Matt Curry <matt@pseudocoder.com>
 @license     MIT
 
 More info at: http://www.20couch.com
****************************************************************************/
?>
<?php
class Setting extends AppModel {

	var $name = 'Setting';
	var $order = array('Setting.key' => 'asc');
	
	function saveSingle($data) {
		if(!empty($data['Setting'])) {
			$data = $data['Setting'];
		}
		
		if(empty($data['key']) || empty($data['value'])) {
			return false;
		}
		
		$setting = $this->find('first', array('conditions' => array('key' => $data['key'])));
		
		if(!$setting) {
			return parent::save($data);
		} else {
			return parent::save(array('id' => $setting['Setting']['id'], 'value' => $data['value']));
		}
	}

	function saveAll($data) {
		$fixedData = array();
		$settings = $this->find('list', array('fields' => array('Setting.key', 'Setting.id')));

		foreach($data['Setting'] as $key => $value) {
			if (!empty($settings[$key])) {
				$fixedData = array('id' => $settings[$key],
														 'key' => trim($key),
														 'value' => trim($value));
				
				if(!parent::save($fixedData)) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	function __findLoad() {
		$settings = parent::find('list', array('fields' => array('Setting.key', 'Setting.value')));
		
		foreach($settings as $key => $val) {
			if(strpos($key, '.') !== false) {
				list($group, $newKey) = explode('.', $key);
				
				if(empty($settings[$group])) {
					$settings[$group] = array();
				}
				
				$settings[$group][$newKey] = $val;
				unset($settings[$key]);
			}
		}
		
		return $settings;
	}
	
	function __findValue($key) {
		$setting = parent::find('first', array('fields' => array('Setting.value'),
																					 'conditions' => array('Setting.key' => $key)));
		
		return $setting['Setting']['value'];
	}
	
	function __findEditable() {
		return parent::find('all', array('conditions' => array('Setting.editable' => true)));
	}
}
?>