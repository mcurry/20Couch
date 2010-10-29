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
class TwitterBase extends AppModel {
	var $useDbConfig = 'twitter';
	var $hasOne = array('Provider');
	
	function afterFind($data) {
		if ($data && is_array($data) && !Set::numeric(array_keys($data))) {
			$data = array($data);
		}
		
		if (is_array($data) && Set::numeric(array_keys($data))) {
			foreach($data as $i => $row) {
				if(empty($row['user']) && !empty($row['sender'])) {
					$row['user'] = $row['sender'];
					$row['message'] = true;
				} else {
					$row['message'] = false;
				}
				
				
				$data[$i] = array('Item' => array('provider_key' => $row['id'],
																					'message' => $row['message'],
																					'text' => $row['text'],
																					'client' => $this->parse($row, 'source'),
																					'link' => 'http://twitter.com/' . $row['user']['screen_name'] . '/status/' . $row['id'],
																					'reply_user_provider_key' => $this->parse($row, 'in_reply_to_user_id'),
																					'reply_status_provider_key' => $this->parse($row, 'in_reply_to_status_id'),
																					'posted' => date('Y-m-d H:i:s', strtotime($row['created_at']))),
													'Origin' => array('provider_key' => $row['user']['id'],
																						'name' => $row['user']['screen_name'],
																						'profile' => $row['user']['description'],
																						'origin_link' => $row['user']['url'],
																						//http://groups.google.com/group/twitter-development-talk/browse_frm/thread/42ba883b9f8e3c6e
																						//'follower' => ($row['user']['following'] == 'false' ? true : false),
																						'follower_count' => $row['user']['followers_count'],
																						'following_count' => $row['user']['friends_count'],
																						'update_count' => $row['user']['statuses_count'],
																						'avatar' => $row['user']['profile_image_url'],
																						'link' => 'http://twitter.com/' . $row['user']['screen_name']));
				
				if($this->name == 'TwitterAccount') {
					$data[$i]['Origin']['following'] = true;
				}
				
				if(!empty($row['retweeted_status'])) {
					$data[$i]['Item']['text'] = $row['retweeted_status']['text'];
					$data[$i]['ForwardOrigin'] = array('provider_key' => $row['retweeted_status']['user']['id'],
																						'name' => $row['retweeted_status']['user']['screen_name'],
																						'profile' => $row['retweeted_status']['user']['description'],
																						'origin_link' => $row['retweeted_status']['user']['url'],
																						'follower_count' => $row['retweeted_status']['user']['followers_count'],
																						'following_count' => $row['retweeted_status']['user']['friends_count'],
																						'update_count' => $row['retweeted_status']['user']['statuses_count'],
																						'avatar' => $row['retweeted_status']['user']['profile_image_url'],
																						'link' => 'http://twitter.com/' . $row['retweeted_status']['user']['screen_name']);
				}
			}
		} else if (is_string($data)) {
			$dataArray = explode('&', $data);
			$data = array();
			foreach($dataArray as $value) {
				list($key, $value) = explode('=', $value);
				$data[$key] = $value;
			}
		}

		return $data;
	}
	
	function parse($data, $field) {
		if(empty($data[$field])) {
			return null;
		}
		
		return $data[$field];
	}
	
	function __findConversation($item=array()) {
		$items = array($item);
		
		$reply_status_provider_key = $item['Item']['reply_status_provider_key'];
		while($reply_status_provider_key) {
			$single = $this->Provider->Item->find('provider_key', $reply_status_provider_key);
			
			if($single) {
				$single = array_values($single);
			} else {
				$options = array('action' => 'single', 'conditions' => array('id' => $reply_status_provider_key));
				$single = parent::find('all', $options);
			}
			
			$reply_status_provider_key = null;
			if($single) {
				if(!empty($single[0]['Item']['reply_status_provider_key'])) {
					$reply_status_provider_key = $single[0]['Item']['reply_status_provider_key'];
				}
				
				$items[] = $single[0];
			}
		}

		return array_reverse($items);
	}
}
?>