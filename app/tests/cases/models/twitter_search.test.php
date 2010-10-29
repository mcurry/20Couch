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
class TwitterSearchTestCase extends CakeTestCase {
	var $TwitterSearch = null;
	var $fixtures = array('app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');

	function startTest() {
		$this->TwitterSearch =& ClassRegistry::init('TwitterSearch');
	}

	function testTwitterSearchInstance() {
		$this->assertTrue(is_a($this->TwitterSearch, 'TwitterSearch'));
	}

	function testAfterFind() {
		$data = array('results' => array(array('profile_image_url' => 'http://a1.twimg.com/profile_images/1.jpg', 'created_at' => 'Wed, 16 Dec 2009 20:46:32 +0000',
																					 'from_user' => 'User1', 'to_user_id' => NULL, 'text' => 'Some text...', 'id' => '123', 'from_user_id' => '987',
																					 'geo' => NULL, 'iso_language_code' => 'en', 'source' => '<a href="http://twitter.com/">web</a>'),
																		 array('profile_image_url' => 'http://a3.twimg.com/profile_images/2.png', 'created_at' => 'Wed, 16 Dec 2009 20:10:59 +0000',
																					 'from_user' => 'User2', 'to_user_id' => NULL, 'text' => 'More text...', 'id' => '456', 'from_user_id' => '789',
																					 'geo' => NULL, 'iso_language_code' => 'es', 'source' => '<a href="http://twitter.com/">web</a>')));

		$results = $this->TwitterSearch->afterFind($data);
		$expected = array (array ('Item' => array ('provider_key' => '123', 'text' => 'Some text...', 'client' => '<a href="http://twitter.com/">web</a>',
																			'link' => 'http://twitter.com/User1/status/123', 'posted' => '2009-12-16 15:46:32'),
															'Origin' => array ('provider_key' => '987', 'name' => 'User1', 'avatar' => 'http://a1.twimg.com/profile_images/1.jpg',
																								 'link' => 'http://twitter.com/User1')),
											 array ('Item' => array ('provider_key' => '456', 'text' => 'More text...', 'client' => '<a href="http://twitter.com/">web</a>',
																							 'link' => 'http://twitter.com/User2/status/456', 'posted' => '2009-12-16 15:10:59'),
															'Origin' => array ('provider_key' => '789', 'name' => 'User2', 'avatar' => 'http://a3.twimg.com/profile_images/2.png',
																								 'link' => 'http://twitter.com/User2')));
		$this->assertEqual($results, $expected);
	}
}
?>