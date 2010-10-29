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
class Fake extends AppModel {
	var $useTable = false;
	
	function save($model, $data) {
		switch($model) {
			default:
				return true;
			case 'Provider':
				if(empty($data['Provider']['keyword'])) {
					$data['Provider']['keyword'] = 'username would be here';
				}
				
				if(empty($data['Provider']['id'])) {
					return array('Provider' => array('service_id' => $data['Provider']['service_id'],
																				 'name' => $data['Provider']['keyword'],
																				 ));
				} else {
					return true;
				}
		}
	}
}
?>