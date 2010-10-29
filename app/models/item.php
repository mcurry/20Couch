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
class Item extends AppModel {

	var $name = 'Item';
	var $belongsTo = array('Origin', 'Provider',
												 'ForwardOrigin' => array('className' => 'Origin',
																									'foreignKey' => 'forward_origin_id'));

	function save($data) {
		$this->Origin->create();
		$origin = $this->Origin->save($data['Origin']);
		if (!empty($data['ForwardOrigin'])) {
			$this->Origin->create();
			$forwardOrigin = $this->Origin->save($data['ForwardOrigin']);
		}

		if ($this->Origin->isMuted($origin, $data['Item']['posted'])) {
			$data['Item']['read'] = true;
		}

		$data['Item']['origin_id'] = $origin['Origin']['id'];
		if (!empty($forwardOrigin)) {
			$data['Item']['forward_origin_id'] = $forwardOrigin['Origin']['id'];
		}

		if (empty($data['Item']['id']) && !empty($data['Item']['provider_id']) && !empty($data['Item']['provider_key'])) {
			$found = parent::find('first', array('conditions' => array('Item.provider_id' => $data['Item']['provider_id'],
																					 'Item.provider_key' => $data['Item']['provider_key'])));
			if ($found) {
				$this->set($found);
			}
		}

		$this->set($data['Item']);
		$data = parent::save();
		$data['Item']['id'] = $this->id;
		$data['Origin'] = $origin['Origin'];
		return $data;
	}

	function markAsRead($data) {
		if (!empty($data['timestamp'])) {
			if (!empty($data['use_posted'])) {
				$conditions = array('Item.posted <=' => $data['timestamp']);
			} else {
				$conditions = array('Item.created <=' => $data['timestamp']);
			}
		}

		if ($data['type'] == 'provider') {
			if (!empty($data['id']) && is_numeric($data['id'])) {
				$conditions['Item.provider_id'] = $data['id'];
			}
		} else if ($data['type'] == 'tag') {
			$origins = $this->Origin->find('tag', $data['id']);
			$conditions['Item.origin_id'] = Set::extract('/OriginsTag/origin_id', $origins);
		} else if ($data['type'] == 'origin') {
			$conditions['Item.origin_id'] = $data['id'];
		} else if ($data['type'] == 'item_id') {
			$conditions['Item.id '] = explode(',', $data['id']);
		}

		$this->updateAll(array('read' => true), $conditions);

		if (!empty($conditions['Item.provider_id'])) {
			$this->Provider->updateItemCounterCache($conditions['Item.provider_id']);
		} else {
			$this->Provider->updateItemCounterCache();
		}
	}

	function __findConversation($id) {
		$this->contain('Provider', 'Origin');
		$item = $this->read(null, $id);
		return $this->Provider->find('conversation', $item);
	}

	function __findProviderKey($providerKeys) {
		if (empty($providerKeys)) {
			return array();
		}

		$this->contain('Provider', 'Origin');
		$items = parent::find('all', array('conditions' => array('Item.provider_key' => $providerKeys)));

		if (!$items) {
			return array();
		}

		$itemsByProviderKey = array();
		foreach($items as $item) {
			$itemsByProviderKey[$item['Item']['provider_key']] = $item;
		}

		return $itemsByProviderKey;
	}

	function __findCounts() {
		$counts = array();

		//providers
		$providers = $this->Provider->find('all', array('fields' => array('Provider.id', 'Provider.item_count')));
		$counts['sources']['provider'] = Set::extract('/Provider/.', $providers);

		//tags
		//tbd

		//sum all
		$counts['all'] = array_sum(Set::extract('/sources/provider/item_count', $counts));
		return $counts;
	}
}
?>