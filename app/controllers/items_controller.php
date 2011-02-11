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
class ItemsController extends AppController {
	var $helpers = array('Time');
	var $paginate = array('order' => 'Item.posted ASC',
												'limit' => 25,
												'contain' => array('Provider', 'Origin', 'ForwardOrigin'));

	function index($providerId=null, $update=true) {
		if (!$providerId) {
			$providerId = 'all';
		}
		
		if (empty($this->params['named']['view'])) {
			$viewAll = false;
			$this->paginate['conditions']['Item.read'] = false;
		} else {
			$viewAll = true;
			$this->paginate['order'] = 'Item.posted DESC';
		}

		if (!empty($this->params['named']['timestamp'])) {
			$this->paginate['conditions']['Item.created >'] = $this->params['named']['timestamp'];
		}

		if (!empty($this->params['named']['provider_id'])) {
			$providerId = $this->params['named']['provider_id'];
		}

		if (!empty($this->params['named']['tag_id'])) {
			$tag = $this->Item->Origin->Tag->read(null, $this->params['named']['tag_id']);
			$view = $tag['Tag'];
			$view['type'] = 'tag';
			$origins = $this->Item->Origin->find('tag', $this->params['named']['tag_id']);
			$originIds = Set::extract('/OriginsTag/origin_id', $origins);
			$this->paginate['conditions']['Item.origin_id'] = $originIds;
		} else if (is_numeric($providerId)) {
			$provider = $this->Item->Provider->read(null, $providerId);
			
			if ($update && $provider['Provider']['service_id'] != Configure::read('ServicesName.TwitterReply')) {
				$options = array();
				if (!empty($this->params['named']['force'])) {
					$options['force'] = true;
				}
				$this->Item->Provider->update($providerId, $options);
			}

			$view = $provider['Provider'];
			$view['type'] = 'provider';
			$this->set('providersKeys', Set::extract('/Provider/provider_key', $provider));
			$this->paginate['conditions']['Item.provider_id'] = $providerId;
		} else if ($providerId != 'search') {
			$providers = $this->Item->Provider->find('all');
			$this->set('providersKeys', Set::extract('/Provider/provider_key', $providers));
			$view = array('id' => 'all',
										'type' => 'provider',
										'name' => __('All Items', true),
										'item_count' => array_sum(Set::extract('/Provider/item_count', $providers)));
		}

		$items = $this->paginate();
		if(empty($items) && $this->RequestHandler->isMobile()) {
			$this->redirect(array('controller' => 'providers', 'action' => 'dashboard'));
		}
		
		$items = $this->__filterDupes($items);

		//replies
		$replies = $this->Item->find('provider_key', array_filter(Set::extract('/Item/reply_status_provider_key', $items)));

		if($provider['Provider']['service_id'] == Configure::read('ServicesName.TwitterReply')) {
			$items = array_merge($replies, $items);
		}
		
		$providerKeys = Set::extract('/Item/provider_key', $items);
		foreach($providerKeys as $providerKey) {
			if (!empty($replies[$providerKey])) {
				unset($replies[$providerKey]);
			}
		}
		
		//counts
		$counts = $this->Item->find('counts');


		//$tags = $this->Item->Origin->find('tags', array_unique(Set::extract('/Origin/id', $items)));

		$view['all'] = $viewAll;
		if (empty($this->params['named']['mode'])) {
			$this->params['named']['mode'] = 'default';
		}
		$view['mode'] = $this->params['named']['mode'];

		if (empty($this->params['named']['paginating'])) {
			$this->params['named']['paginating'] = false;
		}
		$view['paginating'] = $this->params['named']['paginating'];

		unset($view['access_token'], $view['access_token_secret']);

		$this->set(compact('items', 'view', 'replies', 'counts', 'providerKeys'));
	}

	function conversation($id) {
		$items = $this->Item->find('conversation', $id);
		$this->set(compact('items'));
	}

	function update($updateProviderId=null) {
		$providerId = $updateProviderId;
		if (!empty($this->params['named']['auto'])) {
			if (!empty($this->params['named']['provider_id'])) {
				$providerId = $this->params['named']['provider_id'];
			}

			$this->set('auto', true);
		}

		$this->Item->Provider->update($updateProviderId);

		if ($this->RequestHandler->isMobile()) {
			$this->redirect(array('controller' => 'providers', 'action' => 'dashboard'));
		} else {
			$this->index($providerId, false);
			$this->render('index');
		}
		
	}

	function search() {
		if (strpos($this->data['Item']['search'], "@") === 0) {
			$this->paginate['conditions']['Origin.name'] = substr($this->data['Item']['search'], 1);
		} else {
			$this->paginate['conditions']['Item.text LIKE'] = '%' . $this->data['Item']['search'] . '%';
		}

		$this->paginate['order'] = 'Item.posted DESC';
		$items = $this->paginate();
		$items = $this->__filterDupes($items);

		$view = array('id' => 'search',
									'type' => 'provider',
									'name' => __('Search', true) . ': ' . $this->data['Item']['search'],
									'item_count' => count($items));

		$search = true;
		$this->set(compact('items', 'view', 'search'));
		$this->render('index');
	}

	function mark_as_read() {
		if (!empty($this->data['Viewd']['id'])) {
			$provider_id = $this->data['Viewd']['id'];
		} else if (!empty($this->params['named']['provider_id'])) {
			$provider_id = $this->params['named']['provider_id'];
		} else {
			die;
		}

		$data = array('type' => 'provider',
									'id' => $provider_id);

		if (!empty($this->params['named']['range'])) {
			$data['timestamp'] = date('Y-m-d H:i:s', strtotime($this->params['named']['range']));
			$data['use_posted'] = true;
		}

		$this->Item->markAsRead($data);

		$this->params['named']['provider_id'] = $provider_id;
		if ($this->RequestHandler->isAjax()) {
			$this->index();
			$this->render('index');
		} else {
			$this->redirect('/');
		}
	}

	function mark_as_read_by_id() {
		if (!empty($this->data['Item']['id'])) {
			$this->Item->markAsRead(array('type' => 'item_id',
																		'id' => $this->data['Item']['id']));
		}
		
		if(!empty($this->data['Item']['redirect'])) {
			$this->redirect($this->data['Item']['redirect']);
		} else if($this->RequestHandler->isMobile()) {
			$this->redirect($this->referer());
		}
	}
	
	function actions($id) {
		$item = $this->Item->read(null, $id);
		$this->set('item', $item);
	}

	function __filterDupes($items) {
		$seenKeys = array();
		foreach($items as $i => $item) {
			if (in_array($item['Item']['provider_key'], $seenKeys)) {
				unset($items[$i]);
			}

			$seenKeys[] = $item['Item']['provider_key'];
		}

		return $items;
	}
}
?>