<?php
/***************************************************************************
 20Couch

 Copyright (c) 2009-2010 Matt Curry


 More info at279: http://www.20couch.com
****************************************************************************/
?>
<?php
class Provider extends AppModel {

	var $name = 'Provider';
	var $order = array('Provider.name' => 'asc');

	var $hasMany = array('Item' => array('dependent'=> true, 'exclusive' => true));
	var $hasOne = array('TwitterAccount', 'TwitterSearch', 'TwitterFollow', 'TwitterReply');
	var $msg = array();

	function beforeSave() {
		if(empty($this->data['Provider']['id'])) {
			switch($this->data['Provider']['service_id']) {
				case 1:
				$options = array('request_token' => $this->data['Provider']['request_token'],
												 'pin' => trim($this->data['Provider']['pin']));
	
				$results = $this->__findService($this->data['Provider']['service_id'], 'access_token', $options);
	
				if (!is_array($results) || empty($results['oauth_token'])) {
					return false;
				}
	
				$this->data['Provider']['name'] = $results['screen_name'];			
				$this->data['Provider']['provider_key'] = $results['user_id'];
	
				if (empty($this->data['Provider']['id'])) {
					//App::import('Core', 'Security');
					//$this->data['Provider']['access_token'] = Security::cipher($results['oauth_token'], Configure::read('Cipher.key'));
					//$this->data['Provider']['access_token_secret'] = Security::cipher($results['oauth_token_secret'], Configure::read('Cipher.key'));

					$this->data['Provider']['access_token'] = $results['oauth_token'];
					$this->data['Provider']['access_token_secret'] = $results['oauth_token_secret'];
				}
				break;
			case 4:
				$this->Item->contain('Origin');
				$item = $this->Item->read(null, $this->data['Provider']['item_id']);
				if(!$item) {
					return false;
				}
				
				$this->data['Provider']['name'] = '@' . $item['Origin']['name'] . ' (id: ' . $item['Item']['provider_key'] . ')';
				$this->data['Provider']['keyword'] = $item['Origin']['name'];
				$this->data['Provider']['provider_key'] = $item['Item']['provider_key'];
				break;
			default:
				$this->data['Provider']['name'] = $this->data['Provider']['keyword'];
				
				if($this->data['Provider']['service_id'] == Configure::read('ServicesName.TwitterFollow')) {
					$this->data['Provider']['name'] = '@' . $this->data['Provider']['name'];
				}
				break;
			}
		}

		return true;
	}

	function update($id=null, $options=array()) {
		$options = array_merge(array('force' => false), $options);
		$providers = $this->find('to_be_updated', array_merge(array('id' => $id), $options));

		foreach($providers as $provider) {
			$findOptions = array('limit' => Configure::read('UpdateLimit'),
											 'conditions' => array('last_item_provider_key' => $provider['Provider']['last_item_provider_key'],
																						 'last_message_provider_key' => $provider['Provider']['last_message_provider_key']));

			$actions = array('update');
			switch ($provider['Provider']['service_id']) {
				case Configure::read('ServicesName.TwitterAccount'):
					//App::import('Core', 'Security');
					$actions[] = 'mentions';
					if(!Configure::read('demo')) {
						$actions[] = 'messages';
					}
					//$findOptions['auth'] = array('access_token' => Security::cipher($provider['Provider']['access_token'], Configure::read('Cipher.key')),
					//												 'access_token_secret' => Security::cipher($provider['Provider']['access_token_secret'], Configure::read('Cipher.key')));

					$findOptions['auth'] = array('access_token' => $provider['Provider']['access_token'],
																	 'access_token_secret' => $provider['Provider']['access_token_secret']);
					
					break;
				case Configure::read('ServicesName.TwitterSearch'):
					$findOptions['conditions']['search'] = $provider['Provider']['keyword'];
					break;
				case Configure::read('ServicesName.TwitterFollow'):
					$findOptions['conditions']['screen_name'] = $provider['Provider']['keyword'];
					break;
				case Configure::read('ServicesName.TwitterReply'):
					$this->TwitterReply->replyProviderKey = $provider['Provider']['provider_key'];
					$findOptions['conditions']['search'] = 'to:' . $provider['Provider']['keyword'];
					break;
			}

			$results = array();
			$maxPage = Configure::read('UpdateMaxPage');
			if($provider['Provider']['last_item_provider_key'] == null) {
				$maxPage = 1;
			}
			
			foreach($actions as $action) {
				$msg = sprintf('Updating %s (%s)', $provider['Provider']['name'], $action);
				$temp = array();
				$page = 0;
				unset($findOptions['max_item_provider_key']);
				
				while($page < $maxPage && ($page === 0 || count($temp) == $findOptions['limit'])) {
					$page ++;
					$findOptions['page'] = $page;
					$temp = $this->__findService($provider['Provider']['service_id'], $action, $findOptions);
					if (is_array($temp)) {
						$msg .= sprintf(' %d new', count($temp));
						if($temp) {
							$results = array_merge($results, $temp);
						}
					} else {
						$msg .= ' ERROR';
						break;
					}
				}
				
				$this->msg[] = $msg;
			}

			$last_item_provider_key = $provider['Provider']['last_item_provider_key'];
			$last_message_provider_key = $provider['Provider']['last_message_provider_key'];
			foreach($results as $i => $row) {
				if(!empty($row['maxReplyId'])) {
					$last_item_provider_key = max($last_item_provider_key, $row['maxReplyId']);
					continue;	
				}
				
				//only show recent entries for new providers w/ a min
				if(!$provider['Provider']['last_updated'] && $i >= 10 && $row['Item']['posted'] < date('Y-m-d H:i:s', strtotime('-24 hours'))) {
					$row['Item']['read'] = true;
				}
				
				$row['Origin']['provider_id'] = $provider['Provider']['id'];
				if(!empty($row['ForwardOrigin'])) {
					$row['ForwardOrigin']['provider_id'] = $provider['Provider']['id'];
				}
				
				$row['Item']['provider_id'] = $provider['Provider']['id'];
				$this->Item->create();
				$this->Item->save($row);

				if (!empty($row['Item']['message'])) {
					$last_message_provider_key = max($last_message_provider_key, $row['Item']['provider_key']);
				} else {
					$last_item_provider_key = max($last_item_provider_key, $row['Item']['provider_key']);
				}
			}

			$providerUpdate = array('id' => $provider['Provider']['id'],
															'last_updated' => date('Y-m-d H:i:s'),
															'item_count' => $this->updateItemCounterCache($provider['Provider']['id'], true),
															'last_item_provider_key' => $last_item_provider_key,
															'last_message_provider_key' => $last_message_provider_key);

			$this->save($providerUpdate, array('validation' => false, 'callbacks' => false));
		}

		//$this->Item->Origin->Tag->updateItemCounterCache();
		
		return true;
	}

	function updateStatus($data) {
		//validate - TODO

		$provider = $this->read(null, $data['Provider']['provider_id']);

		//$options = array('status' => $data['Provider']['status'],
		//								 'access_token' => Security::cipher($provider['Provider']['access_token'], Configure::read('Cipher.key')),
		//								 'access_token_secret' => Security::cipher($provider['Provider']['access_token_secret'], Configure::read('Cipher.key')));

		$options = array('status' => $data['Provider']['status'],
										 'access_token' => $provider['Provider']['access_token'],
										 'access_token_secret' => $provider['Provider']['access_token_secret']);
		
		if (!empty($data['Provider']['item_id'])) {
			$item = $this->Item->read(null, $data['Provider']['item_id']);
			$options['action'] = $data['Provider']['action'];

			switch ($data['Provider']['action']) {
				case 'reply':
				default:
					$options['provider_reply_key'] = $item['Item']['provider_key'];
					break;
				case 'forwardManual':
					$options['provider_forward_key'] = $item['Item']['provider_key'];
					break;
				case 'forwardApi':
					$options['url_suffix'] = $item['Item']['provider_key'];
					unset($options['status']);
					break;
			}
		}

		$service = Configure::read('Services.' . $provider['Provider']['service_id']);
		$this-> {$service}->updateStatus($options);
	}

	function updateItemCounterCache($id=null, $return=false) {
		$conditions = array();
		if ($id) {
			$conditions['Provider.id'] = $id;
		}

		$counts = parent::find('all', array(
															 'fields' => array('Provider.id', 'count(Item.id) as cnt'),
															 'joins' => array(
																						array('table' => 'items',
																									'alias' => 'Item',
																									'type' => 'left',
																									'foreignKey' => false,
																									'conditions'=> array('Item.provider_id = Provider.id',
																																			 'Item.read' => 0)
																								 )
																				),
															 'conditions' => $conditions,
															 'group' => array('Provider.id')
													 )
													);

		if ($return) {
			if ($id) {
				return $counts[0][0]['cnt'];
			}	else {
				return $counts;
			}
		}

		foreach($counts as $count) {
			$this->save(array('id' => $count['Provider']['id'], 'item_count' => $count[0]['cnt']),
									array('callbacks' => false, 'validate' => false));
		}

	}
	
	function __findGroups() {
		$groups = array();
		$allItemCount = 0;
		$providers = parent::find('all');
		$services = Configure::read('Services');
						
		if($providers) {
			foreach($providers as $provider) {
				$allItemCount += $provider['Provider']['item_count'];
				$groups[$services[$provider['Provider']['service_id']]][] = $provider;
			}
			$groups['all']['item_count'] = $allItemCount;
		}
		
		return $groups;
	}

	function __findSingle($item) {
		$options = array('conditions' => array('id' => $item['Item']['reply_status_provider_key']));
		$items = $this->__findService($item['Provider']['service_id'], 'single', $options);
		foreach($items as $i => $single) {
			$single['Origin']['provider_id'] = $item['Provider']['id'];
			$single['Item']['provider_id'] = $item['Provider']['id'];
			$this->Item->create();
			$items[$i] = $this->Item->save($single);
		}

		return $items;
	}

	function __findService($service_id, $type, $options) {
		$service = Configure::read('Services.' . $service_id);
		return $this-> {$service}->find($type, $options);
	}

	function __findItems($options=array()) {
		if (!is_array($options)) {
			$options = array('conditions' => array('Item.provider_id' => $options));
		}

		$options = array_merge_recursive(array('conditions' => array('Item.read' => false),
																					 'order' => array('Item.posted' => 'asc')), $options);
		$this->Item->contain('Origin');
		return $this->Item->find('all', $options);
	}

	function __findToBeUpdated($options=array()) {
		if ($options && is_numeric($options)) {
			$options = array('id' => $options);
		}

		$conditions = array();
		if(!empty($options['id']) && is_numeric($options['id'])) {
			$conditions['Provider.id'] = $options['id'];
		}
		
		if (empty($options['force'])) {
			$conditions['OR'] = array('Provider.last_updated' => null,
																'Provider.last_updated < CURRENT_TIMESTAMP - INTERVAL update_frequency SECOND');
		}

		$defaults = array('conditions' => $conditions,
											'order' => array('Provider.last_updated' => 'asc'));

		$options = array_merge_recursive($defaults, $options);
		$providers = parent::find('all', $options);
		
		//don't update replies after 24 hours
		$old = date('Y-m-d H:i:s', strtotime('-24 hours'));
		foreach($providers as $i => $provider) {
			if($provider['Provider']['service_id'] == Configure::read('ServicesName.TwitterReply')
				 && $provider['Provider']['created'] <= $old) {
				unset($providers[$i]);
			}
		}
		
		return array_values($providers);
	}
}
?>