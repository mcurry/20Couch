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
App::import('Core', 'Security');
App::import('Model', array('Provider', 'TwitterAccount', 'TwitterSearch', 'TwitterFollow'));
Mock::generatePartial('TwitterAccount', 'MockTwitterAccount', array('__findAccessToken', 'updateStatus', '__findUpdate', '__findMentions', '__findMessages'));
Mock::generatePartial('TwitterSearch', 'MockTwitterSearch', array('__findUpdate'));
Mock::generatePartial('TwitterFollow', 'MockTwitterFollow', array('__findUpdate'));

class ProviderTestCase extends CakeTestCase {
	var $Provider = null;
	var $fixtures = array('app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');

	function startTest() {
		$this->Provider =& ClassRegistry::init('Provider');
		$this->Provider->TwitterAccount = new MockTwitterAccount();
		$this->Provider->TwitterSearch = new MockTwitterSearch();
		$this->Provider->TwitterFollow = new MockTwitterFollow();

		//$data = array('id' => 2,
		//							'access_token' => Security::cipher('access_token', Configure::read('Cipher.key')),
		//							'access_token_secret' => Security::cipher('access_token_secret', Configure::read('Cipher.key')));
		$data = array('id' => 2,
									'access_token' => 'access_token',
									'access_token_secret' => 'access_token_secret');
		$this->Provider->create();
		$this->Provider->save($data, array('callbacks' => false));
		$this->Provider->create();
	}

	function testProviderInstance() {
		$this->assertTrue(is_a($this->Provider, 'Provider'));
	}

	function testBeforeSaveTwitterAccount() {
		$oathResults = array('oauth_token' => '17596518-AUpNgmy3c7g03U6hXFtU5DsX6zAYaQlXWSyU50k9a',
												 'oauth_token_secret' => 'uPYxwhLkfkon6WkL9Fmx0xaZ36kZ7PGOQoDS05t7F',
												 'screen_name' => '20couch',
												 'user_id' => '88286789');
		$this->Provider->TwitterAccount->setReturnValue('__findAccessToken', $oathResults);

		$this->Provider->data = array('Provider' =>array('service_id' => 1,
																	'request_token' => 'bgeySa5Jqy3Fna7uVjKU3GJahKZmWIwpRZjrdvK0M',
																	'pin' => '1234567'));
		$result = $this->Provider->beforeSave();
		$this->assertTrue($result);
		$expected = array ('Provider' => array ('service_id' => 1,
																						'request_token' => 'bgeySa5Jqy3Fna7uVjKU3GJahKZmWIwpRZjrdvK0M',
																						'pin' => '1234567',
																						'name' => '20couch',
																						'provider_key' => '88286789',
																						//'access_token' => Security::cipher($oathResults['oauth_token'], Configure::read('Cipher.key')),
																						//'access_token_secret' => Security::cipher($oathResults['oauth_token_secret'], Configure::read('Cipher.key'))));
																						'access_token' => $oathResults['oauth_token'],
																						'access_token_secret' =>$oathResults['oauth_token_secret']));
		$this->assertEqual($this->Provider->data, $expected);
	}

	function testBeforeSaveOther() {
		$this->Provider->data = array('Provider' =>array('service_id' => 2,
																	'keyword' => '20couch'));

		$result = $this->Provider->beforeSave();
		$this->assertTrue($result);
		$this->assertEqual('20couch', $this->Provider->data['Provider']['name']);
	}
	
	function testUpdate() {
		$this->Provider->TwitterAccount->setReturnValue('__findUpdate', unserialize($this->twitterAccountUpdateData));
		$this->Provider->TwitterAccount->setReturnValue('__findMentions', unserialize($this->twitterAccountMentionsData));
		$this->Provider->TwitterAccount->setReturnValue('__findMessages', unserialize($this->twitterAccountMessagesData));
		$this->Provider->TwitterSearch->setReturnValue('__findUpdate', unserialize($this->twitterSearchUpdateData));
		$this->Provider->TwitterFollow->setReturnValue('__findUpdate', unserialize($this->twitterFollowUpdateData));
		
		$this->Provider->update();
		
		$providers = $this->Provider->find('all');
		$this->assertEqual(array(6,12,5), Set::extract('/Provider/item_count', $providers));
		$this->assertEqual(array(6705638190,6703846969,6703929431), Set::extract('/Provider/last_item_provider_key', $providers));
		$this->assertEqual(array(null,620287381,null), Set::extract('/Provider/last_message_provider_key', $providers));
		
		$origin = $this->Provider->Item->Origin->find('first', array('conditions' => array('Origin.provider_key' => '11373892')));
		$this->assertEqual(4, $origin['Origin']['id']);
		$this->assertPattern('/' . date('Y-m-d H:i') . ':[0-9]{2}/', $origin['Origin']['modified']);
		
		$origin = $this->Provider->Item->Origin->find('first', array('conditions' => array('Origin.provider_key' => '68494794')));
		$this->assertEqual('rsstalker', $origin['Origin']['name']);
		
		$item = $this->Provider->Item->find('first', array('conditions' => array('Item.provider_key' => '6703929431')));
		$this->assertEqual('$13.99 (33.4% drop) A Clockwork Orange (Two-Disc Special Edition) http://rsstalker.com/g/1267319', $item['Item']['text']);
		
		$item = $this->Provider->Item->find('first', array('conditions' => array('Item.provider_key' => '620287381')));
		$this->assertTrue($item['Item']['message']);
	}

	function testUpdateStatus() {
		$this->Provider->TwitterAccount->setReturnValue('updateStatus', true);
		$this->Provider->TwitterAccount->expectOnce('updateStatus', array(array('status' => 'This is a test update 1',
						'access_token' => 'access_token',
						'access_token_secret' => 'access_token_secret')));
		$result = $this->Provider->updateStatus(array('Provider' => array('provider_id' => 2,
																						'status' => 'This is a test update 1')));
	}

	function testUpdateStatusReply() {
		$this->Provider->TwitterAccount->setReturnValue('updateStatus', true);
		$this->Provider->TwitterAccount->expectOnce('updateStatus', array(array('status' => 'This is a test update 2',
						'access_token' => 'access_token',
						'access_token_secret' => 'access_token_secret',
						'action' => 'reply',
						'provider_reply_key' => '987653')));
		$result = $this->Provider->updateStatus(array('Provider' => array('provider_id' => 2,
																						'item_id' => 2,
																						'action' => 'reply',
																						'status' => 'This is a test update 2')));
	}

	function testUpdateStatusForwardManual() {
		$this->Provider->TwitterAccount->setReturnValue('updateStatus', true);
		$this->Provider->TwitterAccount->expectOnce('updateStatus', array(array('status' => 'This is a test update 2',
						'access_token' => 'access_token',
						'access_token_secret' => 'access_token_secret',
						'action' => 'forwardManual',
						'provider_forward_key' => '987653')));
		$result = $this->Provider->updateStatus(array('Provider' => array('provider_id' => 2,
																						'item_id' => 2,
																						'action' => 'forwardManual',
																						'status' => 'This is a test update 2')));
	}

	function testUpdateStatusForwardApi() {
		$this->Provider->TwitterAccount->setReturnValue('updateStatus', true);
		$this->Provider->TwitterAccount->expectOnce('updateStatus', array(array(
								'access_token' => 'access_token',
								'access_token_secret' => 'access_token_secret',
								'action' => 'forwardApi',
								'url_suffix' => '987653')));
		$result = $this->Provider->updateStatus(array('Provider' => array('provider_id' => 2,
																						'item_id' => 2,
																						'action' => 'forwardApi',
																						'status' => 'This is a test update 2')));
	}

	function testUpdateItemCounterCacheAll() {
		$results = $this->Provider->updateItemCounterCache();
		$providers = $this->Provider->find('all');
		$this->assertEqual(array(1,5,0), Set::extract('/Provider/item_count', $providers));
	}

	function testUpdateItemCounterCacheSingle() {
		$results = $this->Provider->updateItemCounterCache(2);
		$providers = $this->Provider->find('all');
		$this->assertEqual(array(263,5,20), Set::extract('/Provider/item_count', $providers));
	}

	function testUpdateItemCounterCacheAllReturn() {
		$results = $this->Provider->updateItemCounterCache(null, true);
		$this->assertEqual(array(1,5,0), Set::extract('/0/cnt', $results));
	}

	function testUpdateItemCounterCacheSingleReturn() {
		$results = $this->Provider->updateItemCounterCache(2, true);
		$this->assertEqual(5, $results);
	}

	function testFindItems() {
		$results = $this->Provider->find('items');
		$this->assertEqual(7, count($results));

		$results = $this->Provider->find('items', 1);
		$this->assertEqual(1, count($results));
	}

	function testFindToBeUpdated() {
		$results = $this->Provider->find('to_be_updated');
		$this->assertEqual(array('#cakephp', '20couch', 'rsstalker'), Set::extract('/Provider/name', $results));

		$results = $this->Provider->find('to_be_updated', 2);
		$this->assertEqual(array('20couch'), Set::extract('/Provider/name', $results));
	}

	
	var $twitterAccountUpdateData = 'a:5:{i:0;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703846969";s:7:"message";b:0;s:4:"text";s:24:"test 1231231333333333333";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/6703846969";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:33:36";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:18;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}i:1;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703767751";s:7:"message";b:0;s:4:"text";s:20:"test 123123133333333";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/6703767751";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:30:29";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:17;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}i:2;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703726221";s:7:"message";b:0;s:4:"text";s:16:"test 12312313333";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/6703726221";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:28:57";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:16;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}i:3;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703676928";s:7:"message";b:0;s:4:"text";s:12:"test 1231231";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/6703676928";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:27:06";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:15;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}i:4;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703657908";s:7:"message";b:0;s:4:"text";s:4:"test";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/6703657908";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:26:22";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:14;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}}';
	var $twitterAccountMentionsData = 'a:1:{i:0;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"5627513647";s:7:"message";b:0;s:4:"text";s:18:"@20couch cool post";s:6:"client";s:59:"<a href="http://www.20couch.com" rel="nofollow">20Couch</a>";s:4:"link";s:44:"http://twitter.com/20couch/status/5627513647";s:23:"reply_user_provider_key";s:8:"88286789";s:25:"reply_status_provider_key";s:10:"5625966449";s:6:"posted";s:19:"2009-11-11 14:50:21";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"88286789";s:4:"name";s:7:"20couch";s:7:"profile";N;s:11:"origin_link";N;s:14:"follower_count";i:1;s:15:"following_count";i:1;s:12:"update_count";i:18;s:6:"avatar";s:67:"http://s.twimg.com/a/1260817727/images/default_profile_3_normal.png";s:4:"link";s:26:"http://twitter.com/20couch";s:9:"following";b:1;}}}';
	var $twitterAccountMessagesData = 'a:1:{i:0;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:9:"620287381";s:7:"message";b:1;s:4:"text";s:10:"test reply";s:6:"client";N;s:4:"link";s:42:"http://twitter.com/mcurry/status/620287381";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-03 16:13:49";}s:6:"Origin";a:10:{s:12:"provider_key";s:8:"11373892";s:4:"name";s:6:"mcurry";s:7:"profile";s:74:"When this is all over you will be baked and afterwords there will be cake.";s:11:"origin_link";s:26:"http://www.pseudocoder.com";s:14:"follower_count";i:490;s:15:"following_count";i:58;s:12:"update_count";i:665;s:6:"avatar";s:63:"http://a3.twimg.com/profile_images/59360389/headshot_normal.jpg";s:4:"link";s:25:"http://twitter.com/mcurry";s:9:"following";b:1;}}}';
	var $twitterSearchUpdateData = 'a:5:{i:0;a:2:{s:4:"Item";a:5:{s:12:"provider_key";s:10:"6705638190";s:4:"text";s:134:"http://bit.ly/5gUoqV thechaw goes open source! #cakephp powered, project-oriented web-based #git repo manager, wiki, tickets and more.";s:6:"client";s:96:"&lt;a href=&quot;http://www.tweetdeck.com/&quot; rel=&quot;nofollow&quot;&gt;TweetDeck&lt;/a&gt;";s:4:"link";s:49:"http://twitter.com/pointlessjon/status/6705638190";s:6:"posted";s:19:"2009-12-15 14:45:11";}s:6:"Origin";a:4:{s:12:"provider_key";s:8:"12385515";s:4:"name";s:12:"pointlessjon";s:6:"avatar";s:89:"http://a1.twimg.com/profile_images/343549436/deb0ff1a510fee8800f3899243c0a463_normal.jpeg";s:4:"link";s:31:"http://twitter.com/pointlessjon";}}i:1;a:2:{s:4:"Item";a:5:{s:12:"provider_key";s:10:"6705413602";s:4:"text";s:124:"RT @ask_questions: RT @CakeQs New question at CakePHP Questions http://cakeqs.org/s/37 #question #cakephp #view #controllers";s:6:"client";s:59:"&lt;a href=&quot;http://twitter.com/&quot;&gt;web&lt;/a&gt;";s:4:"link";s:47:"http://twitter.com/MonasCozta/status/6705413602";s:6:"posted";s:19:"2009-12-15 14:35:38";}s:6:"Origin";a:4:{s:12:"provider_key";s:8:"27768019";s:4:"name";s:10:"MonasCozta";s:6:"avatar";s:66:"http://a1.twimg.com/profile_images/517863018/DSC007015k_normal.JPG";s:4:"link";s:29:"http://twitter.com/MonasCozta";}}i:2;a:2:{s:4:"Item";a:5:{s:12:"provider_key";s:10:"6704008573";s:4:"text";s:1:"C";s:6:"client";s:92:"&lt;a href=&quot;http://apiwiki.twitter.com/&quot; rel=&quot;nofollow&quot;&gt;API&lt;/a&gt;";s:4:"link";s:47:"http://twitter.com/cakephp_jp/status/6704008573";s:6:"posted";s:19:"2009-12-15 13:40:09";}s:6:"Origin";a:4:{s:12:"provider_key";s:8:"79769342";s:4:"name";s:10:"cakephp_jp";s:6:"avatar";s:63:"http://a3.twimg.com/profile_images/530266551/cakephp_normal.png";s:4:"link";s:29:"http://twitter.com/cakephp_jp";}}i:3;a:2:{s:4:"Item";a:5:{s:12:"provider_key";s:10:"6702663400";s:4:"text";s:125:"RT @charli3: Dutch #cakephp get-together dates proposal: Tue 12 Jan, Tue 19 Jan or Tue 21 Jan. http://is.gd/5lRHM. (via @kvz)";s:6:"client";s:92:"&lt;a href=&quot;http://www.atebits.com/&quot; rel=&quot;nofollow&quot;&gt;Tweetie&lt;/a&gt;";s:4:"link";s:44:"http://twitter.com/felixge/status/6702663400";s:6:"posted";s:19:"2009-12-15 12:47:53";}s:6:"Origin";a:4:{s:12:"provider_key";s:4:"5010";s:4:"name";s:7:"felixge";s:6:"avatar";s:71:"http://a3.twimg.com/profile_images/107142257/passbild-square_normal.jpg";s:4:"link";s:26:"http://twitter.com/felixge";}}i:4;a:2:{s:4:"Item";a:5:{s:12:"provider_key";s:10:"6702460994";s:4:"text";s:114:"RT @charli3: Dutch #cakephp get-together dates proposal: Tue 12 Jan, Tue 19 Jan or Tue 21 Jan. http://is.gd/5lRHM.";s:6:"client";s:92:"&lt;a href=&quot;http://birdfeedapp.com&quot; rel=&quot;nofollow&quot;&gt;Birdfeed&lt;/a&gt;";s:4:"link";s:40:"http://twitter.com/kvz/status/6702460994";s:6:"posted";s:19:"2009-12-15 12:40:01";}s:6:"Origin";a:4:{s:12:"provider_key";s:8:"15156274";s:4:"name";s:3:"kvz";s:6:"avatar";s:70:"http://a3.twimg.com/profile_images/288379977/pasfoto_square_normal.jpg";s:4:"link";s:22:"http://twitter.com/kvz";}}}';
	var $twitterFollowUpdateData = 'a:5:{i:0;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6703929431";s:7:"message";b:0;s:4:"text";s:96:"$13.99 (33.4% drop) A Clockwork Orange (Two-Disc Special Edition) http://rsstalker.com/g/1267319";s:6:"client";s:60:"<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>";s:4:"link";s:46:"http://twitter.com/rsstalker/status/6703929431";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 13:36:55";}s:6:"Origin";a:9:{s:12:"provider_key";s:8:"68494794";s:4:"name";s:9:"rsstalker";s:7:"profile";s:26:"Amazon.com 25% Price Drops";s:11:"origin_link";s:24:"http://www.rsstalker.com";s:14:"follower_count";i:173;s:15:"following_count";i:0;s:12:"update_count";i:5886;s:6:"avatar";s:65:"http://a1.twimg.com/profile_images/410576536/rsstalker_normal.jpg";s:4:"link";s:28:"http://twitter.com/rsstalker";}}i:1;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6702380671";s:7:"message";b:0;s:4:"text";s:81:"$10.99 (26.7% drop) Sigo Siendo Yo: Grandes Exitos http://rsstalker.com/g/1322493";s:6:"client";s:60:"<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>";s:4:"link";s:46:"http://twitter.com/rsstalker/status/6702380671";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 12:36:52";}s:6:"Origin";a:9:{s:12:"provider_key";s:8:"68494794";s:4:"name";s:9:"rsstalker";s:7:"profile";s:26:"Amazon.com 25% Price Drops";s:11:"origin_link";s:24:"http://www.rsstalker.com";s:14:"follower_count";i:173;s:15:"following_count";i:0;s:12:"update_count";i:5885;s:6:"avatar";s:65:"http://a1.twimg.com/profile_images/410576536/rsstalker_normal.jpg";s:4:"link";s:28:"http://twitter.com/rsstalker";}}i:2;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6697481681";s:7:"message";b:0;s:4:"text";s:124:"$31.50 (30.0% drop) Innovation Algorithm:TRIZ, systematic innovation and technical creativity http://rsstalker.com/g/1325589";s:6:"client";s:60:"<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>";s:4:"link";s:46:"http://twitter.com/rsstalker/status/6697481681";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 09:36:56";}s:6:"Origin";a:9:{s:12:"provider_key";s:8:"68494794";s:4:"name";s:9:"rsstalker";s:7:"profile";s:26:"Amazon.com 25% Price Drops";s:11:"origin_link";s:24:"http://www.rsstalker.com";s:14:"follower_count";i:172;s:15:"following_count";i:0;s:12:"update_count";i:5884;s:6:"avatar";s:65:"http://a1.twimg.com/profile_images/410576536/rsstalker_normal.jpg";s:4:"link";s:28:"http://twitter.com/rsstalker";}}i:3;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6697481529";s:7:"message";b:0;s:4:"text";s:74:"$9.99 (33.4% drop) Ben 10 Ultimate Omnitrix http://rsstalker.com/g/1385355";s:6:"client";s:60:"<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>";s:4:"link";s:46:"http://twitter.com/rsstalker/status/6697481529";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 09:36:56";}s:6:"Origin";a:9:{s:12:"provider_key";s:8:"68494794";s:4:"name";s:9:"rsstalker";s:7:"profile";s:26:"Amazon.com 25% Price Drops";s:11:"origin_link";s:24:"http://www.rsstalker.com";s:14:"follower_count";i:172;s:15:"following_count";i:0;s:12:"update_count";i:5883;s:6:"avatar";s:65:"http://a1.twimg.com/profile_images/410576536/rsstalker_normal.jpg";s:4:"link";s:28:"http://twitter.com/rsstalker";}}i:4;a:2:{s:4:"Item";a:8:{s:12:"provider_key";s:10:"6697481349";s:7:"message";b:0;s:4:"text";s:90:"$5.99 (35.9% drop) TY Beanie Babies SpongeBob Best Day Ever http://rsstalker.com/g/1398114";s:6:"client";s:60:"<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>";s:4:"link";s:46:"http://twitter.com/rsstalker/status/6697481349";s:23:"reply_user_provider_key";N;s:25:"reply_status_provider_key";N;s:6:"posted";s:19:"2009-12-15 09:36:55";}s:6:"Origin";a:9:{s:12:"provider_key";s:8:"68494794";s:4:"name";s:9:"rsstalker";s:7:"profile";s:26:"Amazon.com 25% Price Drops";s:11:"origin_link";s:24:"http://www.rsstalker.com";s:14:"follower_count";i:172;s:15:"following_count";i:0;s:12:"update_count";i:5882;s:6:"avatar";s:65:"http://a1.twimg.com/profile_images/410576536/rsstalker_normal.jpg";s:4:"link";s:28:"http://twitter.com/rsstalker";}}}';
}
?>