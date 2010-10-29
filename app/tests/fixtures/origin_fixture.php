<?php

class OriginFixture extends CakeTestFixture {
	var $name = 'Origin';

	function create(&$db) {
		$result = parent::create($db);
		
		if($result) {
			$db->execute('ALTER TABLE `origins` CHANGE `provider_key` `provider_key` BIGINT( 20 ) NULL DEFAULT NULL ');
		}
		
		return $result;
	}
	
	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20,'key' => 'unique'),
		'name' => array('type' => 'string','null' => true,'default' => NULL),
		'profile' => array('type' => 'text','null' => true,'default' => NULL),
		'origin_link' => array('type' => 'string','null' => true,'default' => NULL),
		'follower' => array('type' => 'boolean','null' => true,'default' => NULL),
		'following' => array('type' => 'boolean','null' => true,'default' => NULL),
		'follower_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'following_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'update_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'avatar' => array('type' => 'text','null' => true,'default' => NULL),
		'link' => array('type' => 'text','null' => true,'default' => NULL),
		'item_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'muted' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 2),
		'muted_until' => array('type' => 'datetime','null' => true,'default' => NULL),
		'created' => array('type' => 'datetime','null' => true,'default' => NULL),
		'modified' => array('type' => 'datetime','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'provider_key' => array('column' => 'provider_key', 'unique' => 1))
	);

	var $records = array(
		array(
			'id' => 1,
			'provider_key' => 12345,
			'name' => 'nateabele',
			'profile' => 'Lead developer and chief fanboy of the Lithium project, the light, fast web framework for PHP 5.3.  Inefficient things upset me.',
			'origin_link' => 'http://li3.rad-dev.org/',
			'follower' => 0,
			'following' => 1,
			'follower_count' => 769,
			'following_count' => 57,
			'update_count' => 2831,
			'avatar' => 'http://a1.twimg.com/profile_images/82402408/small_normal.jpg',
			'link' => 'http://twitter.com/nateabele',
			'item_count' => 0,
			'muted' => 0,
			'muted_until' => NULL,
			'created' => '2009-12-02 16:12:22',
			'modified' => '2009-12-02 16:12:22'
		),
		array(
			'id' => 2,
			'provider_key' => 12346,
			'name' => 'snookca',
			'profile' => 'I make stuff on the web.',
			'origin_link' => 'http://snook.ca/',
			'follower' => 0,
			'following' => 1,
			'follower_count' => 12987,
			'following_count' => 490,
			'update_count' => 10370,
			'avatar' => 'http://a3.twimg.com/profile_images/552415081/me_normal.jpg',
			'link' => 'http://twitter.com/snookca',
			'item_count' => 0,
			'muted' => 0,
			'muted_until' => NULL,
			'created' => '2009-12-04 14:40:08',
			'modified' => '2009-12-04 14:40:08'
		),
		array(
			'id' => 3,
			'provider_key' => 88286789,
			'name' => '20couch',
			'profile' => NULL,
			'origin_link' => NULL,
			'follower' => 0,
			'following' => 1,
			'follower_count' => 1,
			'following_count' => 1,
			'update_count' => 13,
			'avatar' => 'http://s.twimg.com/a/1260393960/images/default_profile_3_normal.png',
			'link' => 'http://twitter.com/20couch',
			'item_count' => 0,
			'muted' => 0,
			'muted_until' => NULL,
			'created' => '2009-12-04 14:40:12',
			'modified' => '2009-12-04 14:40:12'
		),
		array(
			'id' => 4,
			'provider_key' => 11373892,
			'name' => 'mcurry',
			'profile' => 'When this is all over you will be baked and afterwords there will be cake.',
			'origin_link' => 'http://www.pseudocoder.com',
			'follower' => 0,
			'following' => 1,
			'follower_count' => 490,
			'following_count' => 58,
			'update_count' => 659,
			'avatar' => 'http://a3.twimg.com/profile_images/59360389/headshot_normal.jpg',
			'link' => 'http://twitter.com/mcurry',
			'item_count' => 0,
			'muted' => 0,
			'muted_until' => NULL,
			'created' => '2009-12-14 14:03:39',
			'modified' => '2009-12-14 14:03:39'
		)
	);
}
?>