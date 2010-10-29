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
class Origin extends AppModel {

	var $name = 'Origin';

	var $hasMany = array('Item');
	var $belongsTo = array('Provider');
	var $hasOne = array('TwitterAccount');

	var $__cache = array();

	function save($data) {
		if (empty($data['Origin']) && !Set::numeric(array_keys($data))) {
			$data = array('Origin' => $data);
		}

		$key = null;
		if (!empty($data['Origin']['link'])) {
			$key = md5($data['Origin']['link']);

			if (!empty($this->__cache[$key])) {
				return $this->__cache[$key];
			}
		}

		if (empty($data['Origin']['id']) && !empty($data['Origin']['provider_key'])) {
			$found = parent::find('first', array('conditions' => array('Origin.provider_key' => $data['Origin']['provider_key'])));
			if ($found) {
				unset($found['Origin']['modified']);
				$this->set($found);
			}
		}

		$this->set($data);
		$result = parent::save();
		$result['Origin']['id'] = $this->id;

		if ($key) {
			$this->__cache[$key] = $result;
		}

		return $result;
	}

	function isMuted($origin, $date) {
		if (!empty($origin['Origin']['muted']) && $origin['Origin']['muted'] == -1) {
			return true;
		}

		if (!empty($origin['Origin']['muted_until']) && $origin['Origin']['muted_until'] >= $date) {
			return true;
		}

		return false;
	}

	function unfriend($id) {
		$this->contain('Provider');
		$origin = $this->read(null, $id);

		$options = array('id' => $origin['Origin']['provider_key'],
										 'auth' => array('access_token' => $origin['Provider']['access_token'],
																		 'access_token_secret' => $origin['Provider']['access_token_secret']));
		$this->TwitterAccount->unfriend($options);
	}

	function __findAutocomplete($params) {
		$conditions = array();
		if(!empty($params['name'])) {
			$conditions['Origin.name LIKE'] = ltrim($params['name'], '@') . '%';
		}
		if(!empty($params['provider_id'])) {
			$conditions['Origin.provider_id'] = $params['provider_id'];
		}		
		
		$origins = parent::find('all', array('fields' => array('Origin.name'),
																		 'conditions' => $conditions,
																		 'order' => array('Origin.name' => 'asc'),
																		 'limit' => 15));
		
		$ret = array();
		foreach($origins as $origin) {
			$ret[] = '@' . $origin['Origin']['name'];
		}
		
		return array_values(array_unique($ret));
	}
	
	function __findFollowing() {
		$origins = parent::find('all', array('fields' => array('Origin.name'),
																		 'conditions' => array('Origin.following' => true),
																		 'order' => array('Origin.name' => 'asc')));
		
		$ret = array();
		foreach($origins as $origin) {
			$ret[] = '@' . $origin['Origin']['name'];
		}
		
		return $ret;
	}

	function __findTag($tag_id) {
		return $this->OriginsTag->find('all', array('conditions' => array('OriginsTag.tag_id' => $tag_id)));
	}

	function __findTags($origin_id) {
		return $this->OriginsTag->find('all', array(
																			 'fields' => array('OriginsTag.origin_id', 'Tag.name'),
																			 'joins' => array(
																										array('table' => 'tags',
																													'alias' => 'Tag',
																													'type' => 'inner',
																													'foreignKey' => false,
																													'conditions'=> array('Tag.id = OriginsTag.tag_id')
																												 )
																								),
																			 'conditions' => array('OriginsTag.origin_id' => $origin_id)
																	 )
																	);
	}
}
?>