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

class TwitterAccount extends TwitterBase {
	var $name = "TwitterAccount";

	function exists() {
		return true;
	}

	function unfriend($data) {
		return (bool) parent::delete($data);
	}

	function updateStatus($data) {
		if (!parent::save($data)) {
			return false;
		}

		return true;
	}

	function __findRequestToken($options=array()) {
		$result = parent::find('all', array('action' => 'request_token'));
		return $result['oauth_token'];
	}

	function __findAccessToken($options=array()) {
		return parent::find('all', array_merge(array('action' => 'access_token'), $options));
	}

	function __findUpdate($options=array()) {
		$options = array_merge(array('action' => 'update', 'limit' => 200), $options);
		return parent::find('all', $options);
	}

	function __findMentions($options=array()) {
		$options = array_merge(array('action' => 'mentions', 'limit' => 200), $options);
		return parent::find('all', $options);
	}

	function __findMessages($options=array()) {
		$options['conditions']['last_item_provider_key'] = $options['conditions']['last_message_provider_key'];
		$options = array_merge(array('action' => 'messages', 'limit' => 200), $options);
		return parent::find('all', $options);
	}
}
?>