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

class TwitterReply extends TwitterBase {
	var $name = "TwitterReply";

	var $replyProviderKey = null;

	function afterFind($data) {
		$data = $data['results'];
		$filteredData = array();
		$maxReplyId = 0;

		if ($data && Set::numeric(array_keys($data))) {
			foreach($data as $i => $row) {
				$maxReplyId = max($maxReplyId, $row['id']);

				$options = array('callbacks' => false, 'action' => 'single', 'urlSuffix' => $row['id']);
				$item = parent::find('all', $options);

				if (!empty($item['in_reply_to_status_id']) && $item['in_reply_to_status_id'] == $this->replyProviderKey) {
					$filteredData[] = array('Item' => array('provider_key' => $row['id'],
																									'text' => $row['text'],
																									'client' => $row['source'],
																									'link' => 'http://twitter.com/' . $row['from_user'] . '/status/' . $row['id'],
																									'reply_user_provider_key' => $this->parse($item, 'in_reply_to_user_id'),
																									'reply_status_provider_key' => $this->parse($item, 'in_reply_to_status_id'),
																									'posted' => date('Y-m-d H:i:s', strtotime($row['created_at']))),
																	'Origin' => array('provider_key' => $row['from_user_id'],
																										'name' => $row['from_user'],
																										'avatar' => $row['profile_image_url'],
																										'link' => 'http://twitter.com/' . $row['from_user']));
				}
			}
		}
		
		if($maxReplyId > 0) {
			$filteredData[] = array('maxReplyId' => $maxReplyId);
		}

		return $filteredData;
	}

	function __findUpdate($options=array()) {
		if (empty($options['rpp']) && !empty($options['limit'])) {
			$options['rpp'] = $options['limit'];
		}

		if (!empty($options['rpp']) && $options['rpp'] > 50) {
			$options['rpp'] = 50;
		}

		$options = array_merge(array('action' => 'replies', 'rpp' => 50), $options);
		return parent::find('all', $options);
	}
}
?>