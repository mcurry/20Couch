<?php

class ItemFixture extends CakeTestFixture {
	var $name = 'Item';
	
	function create(&$db) {
		$result = parent::create($db);
		
		if($result) {
			$db->execute('ALTER TABLE `items` CHANGE `provider_key` `provider_key` BIGINT( 20 ) NULL DEFAULT NULL ');
		}
		
		return $result;
	}

	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'provider_id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'index'),
		'origin_id' => array('type' => 'integer','null' => true,'default' => NULL),
		'forward_origin_id' => array('type' => 'integer','null' => true,'default' => NULL),
		'provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'message' => array('type' => 'boolean','null' => true,'default' => 0),
		'read' => array('type' => 'boolean','null' => true,'default' => 0),
		'text' => array('type' => 'text','null' => true,'default' => NULL),
		'link' => array('type' => 'text','null' => true,'default' => NULL),
		'reply_user_provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'reply_status_provider_key' => array('type' => 'integer','null' => true,'default' => NULL,'length' => 20),
		'client' => array('type' => 'text','null' => true,'default' => NULL),
		'posted' => array('type' => 'datetime','null' => true,'default' => NULL),
		'created' => array('type' => 'datetime','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'provider_id' => array('column' => array('provider_id', 'provider_key'), 'unique' => 1))
	);

	var $records = array(
		array(
			'id' => 1,
			'provider_id' => 1,
			'origin_id' => 3,
			'forward_origin_id' => null,
			'provider_key' => 987654,
			'message' => 0,
			'read' => 1,
			'text' => 'test update append',
			'link' => 'http://twitter.com/20couch/status/6543434791',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-10 16:00:40',
			'created' => '2009-12-14 14:03:39'
		),
		array(
			'id' => 2,
			'provider_id' => 1,
			'origin_id' => 1,
			'forward_origin_id' => null,
			'provider_key' => 987653,
			'message' => 0,
			'read' => 1,
			'text' => 'blah blah blah',
			'link' => 'http://twitter.com/nateabele/status/6541114279',
			'reply_user_provider_key' => 123,
			'reply_status_provider_key' => 321,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-10 14:30:15',
			'created' => '2009-12-14 14:03:39'
		),
		array(
			'id' => 3,
			'provider_id' => 1,
			'origin_id' => 1,
			'forward_origin_id' => null,
			'provider_key' => 987652,
			'message' => 0,
			'read' => 1,
			'text' => 'Yadda Yadda Yadda',
			'link' => 'http://twitter.com/mcurry/status/6539931197',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-10 13:47:30',
			'created' => '2009-12-14 14:03:39'
		),
		array(
			'id' => 4,
			'provider_id' => 1,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 987651,
			'message' => 0,
			'read' => 0,
			'text' => 'Lorum ipsum',
			'link' => 'http://twitter.com/mcurry/status/6539901553',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-10 13:46:25',
			'created' => '2009-12-14 14:03:39'
		),
		array(
			'id' => 5,
			'provider_id' => 4,
			'origin_id' => 151,
			'forward_origin_id' => null,
			'provider_key' => 987650,
			'message' => 0,
			'read' => 0,
			'text' => 'Just pushed the initial version of my A/B testing plugin for CakePHP.  Still a bit rough at the moment. http://github.com/mcurry/ab_test',
			'link' => 'http://twitter.com/mcurry/status/6515998573',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-09 20:27:14',
			'created' => '2009-12-14 14:03:39'
		),
		array(
			'id' => 6,
			'provider_id' => 2,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 987649,
			'message' => 0,
			'read' => 0,
			'text' => 'Actual parameter from the ajax request: \"&removals=password%2Caccount_email\".  Guess what data came back when I took that out?',
			'link' => 'http://twitter.com/mcurry/status/6471926282',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-08 14:08:19',
			'created' => '2009-12-14 14:03:40'
		),
		array(
			'id' => 7,
			'provider_id' => 2,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 987648,
			'message' => 0,
			'read' => 0,
			'text' => 'Also I just spent 2 mins f-ing w/ the ajax requests on that builditwith.me site and got all emails, passwords (md5\'d at least) and ip addys',
			'link' => 'http://twitter.com/mcurry/status/6471895254',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-08 14:07:07',
			'created' => '2009-12-14 14:03:40'
		),
		array(
			'id' => 8,
			'provider_id' => 2,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 6471757035,
			'message' => 0,
			'read' => 0,
			'text' => '\"WILL MY EMAIL ADDRESS GET SPAMMED? The content is loaded in from the database via JSON.\" from: http://builditwith.me/about/',
			'link' => 'http://twitter.com/mcurry/status/6471757035',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => 'web',
			'posted' => '2009-12-08 14:01:54',
			'created' => '2009-12-14 14:03:40'
		),
		array(
			'id' => 9,
			'provider_id' => 2,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 987647,
			'message' => 0,
			'read' => 0,
			'text' => 'So basically you don\'t even need to bother scraping...emails are returned in a nice machine readable format.',
			'link' => 'http://twitter.com/mcurry/status/6471684314',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-08 13:59:16',
			'created' => '2009-12-14 14:03:40'
		),
		array(
			'id' => 10,
			'provider_id' => 2,
			'origin_id' => 4,
			'forward_origin_id' => null,
			'provider_key' => 987646,
			'message' => 0,
			'read' => 0,
			'text' => 'Went with a slightly modified version of this for adding headers: http://bit.ly/7fMDzN replaced *.cc w/ find to include sub dirs.',
			'link' => 'http://twitter.com/mcurry/status/6238880862',
			'reply_user_provider_key' => NULL,
			'reply_status_provider_key' => NULL,
			'client' => '<a href=\"http://www.20couch.com\" rel=\"nofollow\">20Couch</a>',
			'posted' => '2009-12-01 11:05:14',
			'created' => '2009-12-14 14:03:40'
		)
	);
}
?>