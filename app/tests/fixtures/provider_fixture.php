<?php

class ProviderFixture extends CakeTestFixture {
	var $name = 'Provider';

	function create(&$db) {
		$result = parent::create($db);
		
		if($result) {
			$db->execute('ALTER TABLE `providers` CHANGE `last_item_provider_key` `last_item_provider_key` BIGINT( 20 ) NULL DEFAULT NULL ');
		}
		
		return $result;
	}
	
	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'service_id' => array('type' => 'integer','null' => true,'default' => NULL),
		'provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'name' => array('type' => 'string','null' => true,'default' => NULL,'length' => 50),
		'keyword' => array('type' => 'string','null' => true,'default' => NULL,'length' => 50),
		'update_frequency' => array('type' => 'integer','null' => true,'default' => NULL),
		'access_token' => array('type' => 'string','null' => true,'default' => NULL),
		'access_token_secret' => array('type' => 'string','null' => true,'default' => NULL),
		'item_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'last_item_provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'last_message_provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'last_updated' => array('type' => 'datetime','null' => true,'default' => NULL),
		'created' => array('type' => 'datetime','null' => true,'default' => NULL),
		'modified' => array('type' => 'datetime','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	var $records = array(
		array(
			'id' => 1,
			'service_id' => 2,
			'provider_key' => 0,
			'name' => '#cakephp',
			'keyword' => '#cakephp',
			'update_frequency' => 300,
			'access_token' => NULL,
			'access_token_secret' => NULL,
			'item_count' => 263,
			'last_item_provider_key' => 1,
			'last_message_provider_key' => NULL,
			'last_updated' => '2009-12-14 14:03:22',
			'created' => '2009-11-25 10:30:40',
			'modified' => '2009-12-14 14:03:24'
		),
		array(
			'id' => 2,
			'service_id' => 1,
			'provider_key' => 88286789,
			'name' => '20couch',
			'keyword' => NULL,
			'update_frequency' => 300,
			'access_token' => NULL,
			'access_token_secret' => NULL,
			'item_count' => 12,
			'last_item_provider_key' => 2,
			'last_message_provider_key' => 3,
			'last_updated' => '2009-12-14 14:03:40',
			'created' => '2009-11-25 16:41:10',
			'modified' => '2009-12-14 14:03:40'
		),
		array(
			'id' => 3,
			'service_id' => 3,
			'provider_key' => 0,
			'name' => 'rsstalker',
			'keyword' => 'rsstalker',
			'update_frequency' => 300,
			'access_token' => NULL,
			'access_token_secret' => NULL,
			'item_count' => 20,
			'last_item_provider_key' => 4,
			'last_message_provider_key' => NULL,
			'last_updated' => '2009-12-14 14:03:46',
			'created' => '2009-12-02 11:18:07',
			'modified' => '2009-12-14 14:03:46'
		)
	);
}
?>