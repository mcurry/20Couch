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
class OriginsController extends AppController {
	function index() {
		$this->layout = 'settings';
		$this->helpers[] = 'Time';
		$this->paginate['conditions']['or'] = array('muted' => -1,
																					array('muted_until !=' => null,
																								'muted_until >=' => date('Y-m-d H:i:s')));
		$this->set('origins', $this->paginate());
	}

	function unfollow() {
		if ($this->data) {
			$this->Origin->save($this->data);
			$this->Origin->Item->markAsRead(array('type' => 'origin',
																						'id' => $this->data['Origin']['id']));

			$this->Origin->unfriend($this->data['Origin']['id']);
		}
	}

	function mute($id=null) {
		if ($this->data) {
			if ($this->data['Origin']['muted_length'] == -1) {
				$this->data['Origin']['muted'] = -1;
			} else {
				$this->data['Origin']['muted'] = true;
				$this->data['Origin']['muted_until'] = date('Y-m-d H:i:s', strtotime($this->data['Origin']['muted_length'] . ' hour'));
			}

			$this->Origin->save($this->data);
			$this->Origin->Item->markAsRead(array('timestamp' => date('Y-m-d H:i:s'),
																						'type' => 'origin',
																						'id' => $this->data['Origin']['id']));
			
			if ($this->RequestHandler->isAjax()) {
				$this->render();
				return;
			} else {
				$origin = $this->Origin->read(null, $this->data['Origin']['id']);
				$this->redirect(array('controller' => 'items', 'action' => 'index', 'provider_id' => $origin['Origin']['provider_id']));
			}
		}
		
		if($id) {
			$this->data['Origin']['id'] = $id;
		}
	}
	

	function unmute($id=null) {
		if ($id) {
			$data = array('id' => $id,
										'muted' => false,
										'muted_until' => null);
			$this->Origin->save($data);
			$this->Session->setFlash(__('Unmuted', true));
			$this->redirect('index');
		}
	}

	function autocomplete() {
		if (empty($this->params['named']['name'])) {
			$origins = array();
		} else {
			$origins = $this->Origin->find('autocomplete', $this->params['named']);
		}

		$this->set('origins', $origins);
	}

	function tags() {
		if ($this->data) {
			$tags = array();
			foreach(explode(',', $this->data['Origin']['tags_text']) as $tag) {
				$tag = trim($tag);
				if (!empty($tag)) {
					$tags[] = $tag;
				}
			}

			$tags = $this->Origin->Tag->find('or_save', $tags);

			$data = array('Origin' => array('id' => $this->data['Origin']['id']),
										'Tag' => array('Tag' => array_keys($tags)));
			$this->Origin->save($data);

			$tags = $this->Origin->Tag->updateItemCounterCache();

			$this->set('success', true);
			$this->set('tags', $tags);
		}
	}
}
?>