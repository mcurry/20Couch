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
App::import('Model', 'TwitterBase');

class TwitterSearch extends TwitterBase {
	var $name = "TwitterSearch";
	
	function afterFind($data) {
		$data = $data['results'];
		if ($data && Set::numeric(array_keys($data))) {
			foreach($data as $i => $row) {
				$data[$i] = array('Item' => array('provider_key' => $row['id'],
																					'text' => $row['text'],
																					'client' => $row['source'],
																					'link' => 'http://twitter.com/' . $row['from_user'] . '/status/' . $row['id'],
																					'posted' => date('Y-m-d H:i:s', strtotime($row['created_at']))),
													'Origin' => array('provider_key' => $row['from_user_id'],
																						'name' => $row['from_user'],
																						'avatar' => $row['profile_image_url'],
																						'link' => 'http://twitter.com/' . $row['from_user']));
			}
		}

		return $data;
	}

	function __findUpdate($options=array()) {
		if(empty($options['rpp']) && !empty($options['limit'])) {
			$options['rpp'] = $options['limit'];
		}
		
		if(!empty($options['rpp']) && $options['rpp'] > 100) {
			$options['rpp'] = 100;
		}
		
		$options = array_merge(array('action' => 'search', 'rpp' => 100), $options);
		return parent::find('all', $options);
	}
}
?>