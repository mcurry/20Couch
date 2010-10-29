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
class ProvidersController extends AppController {
	function index() {
		$this->layout = 'settings';
		$this->helpers[] = 'Time';
		$this->set('providers', $this->paginate());
	}

	function dashboard() {
		$this->helpers[] = 'Site';

		$providers = $this->Provider->find('groups');
		//$following = $this->Provider->Item->Origin->find('following');
		$tags = array();
		$updatable = array();
		if (!empty($providers['TwitterAccount'])) {
			$updatable = Set::combine($providers['TwitterAccount'], '/Provider/id', '/Provider/name');
		}

		$this->set(compact('providers', 'tags', 'updatable'));
	}

	function add() {
		if ($this->data) {
			if ($this->Session->check('Provider.requestToken')) {
				$this->data['Provider']['request_token'] = $this->Session->read('Provider.requestToken');
				$this->Session->delete('Provider.requestToken');
			}

			if ($provider = $this->Provider->save($this->data)) {
				if (!$this->RequestHandler->isAjax()) {
					$this->Session->setFlash(__('Added', true));
				}
				$this->data = array();
				$provider['Provider']['id'] = $this->Provider->id;
				$this->set('provider', $provider);
				$this->set('success', true);
			} else {
				$this->set('success', false);
			}
		}
	}

	function add_follow() {
		$this->add();
	}

	function add_search() {
		$this->add();
	}

	function add_track_reply() {
		$this->add();
	}
	
	function edit($id=null) {
		if (!$id) {
			$this->redirect('index');
		}

		$this->layout = 'settings';

		if ($this->data) {
			if ($this->Provider->save($this->data, true, array('name', 'update_frequency'))) {
				$this->Session->setFlash(__('Updated', true));
				$this->redirect('index');
			}
		} else {
			$this->data = $this->Provider->read(null, $id);
		}
	}

	function update_status($id=null, $type=null) {
		if ($this->data) {
			$this->Provider->updateStatus($this->data);

			if ($this->RequestHandler->isAjax()) {
				$this->render();
				return;
			} else {
				$this->redirect(array('controller' => 'items', 'action' => 'index', 'provider_id' => $this->data['Provider']['provider_id']));
			}
		}

		$providers = $this->Provider->find('all');
		$updatable = Set::extract('/Provider[service_id=1]', $providers);
		if ($updatable) {
			$updatable = Set::combine($updatable, '/Provider/id', '/Provider/name');
		}
		$this->set(compact('updatable'));

		if ($id) {
			$this->Provider->Item->contain('Origin');
			$item = $this->Provider->Item->read(null, $id);
			if (!$item) {
				$this->redirect($this->referer());
			}

			$this->data['Provider']['item_id'] = $id;
			$this->data['Provider']['provider_id'] = $item['Item']['provider_id'];
			if ($type) {
				$this->data['Provider']['action'] = $type;
			}

			switch ($type) {
				case 'reply':
					$replyType = '';
					if ($item['Item']['message']) {
						$replyType = 'd ';
					} else {
						$replyType = "@";
					}
					$this->data['Provider']['status'] = $replyType . $item['Origin']['name'] . ' ';
					break;

				case 'forward':
					if(User::get('Setting.retweet_method') == 'manual') {
						$this->data['Provider']['action'] = 'forwardManual';
						$this->data['Provider']['status'] = 'RT @' . $item['Origin']['name'] . ': ' . $item['Item']['text'];
					} else {
						$this->data['Provider']['action'] = 'forwardApi';
						$this->data['Provider']['status'] = $item['Item']['text'];
						$this->set('statusOptions', array('readonly' => true));
					}
					//$("#ProviderStatus").val("RT @" + data.Origin.name + ": " + data.Item.text).keyup();
					break;
			}
		}
	}

	function reply($id) {
		$this->setAction('update_status', $id, 'reply');
	}

	function forward($id, $provider_id=null) {
		$this->setAction('update_status', $id, 'forward');
	}

	function delete($id=null) {
		if ($id) {
			$this->Provider->delete($id);
			
			if($this->RequestHandler->isAjax()) {
				$this->set('provider_id', $id);
			} else {
				$this->Session->setFlash(__('Deleted', true));
				$this->redirect('index');
			}
		}
	}

	function titter_oauth_redirect() {
		$requestToken = $this->Provider->TwitterAccount->find('request_token');
		$url = sprintf('http://twitter.com/oauth/authorize?oauth_token=%s&oauth_callback=oob',
									 $requestToken);

		$this->Session->write('Provider.requestToken', $requestToken);
		$this->redirect($url);
	}
}
?>