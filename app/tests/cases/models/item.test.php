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
App::import('Model', array('Item', 'Origin'));
Mock::generatePartial('Origin', 'MockOrigin', array('save', 'isMuted', 'create'));

class ItemTestCase extends CakeTestCase {
	var $Item = null;
	var $fixtures = array('app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');


	function startTest() {
		ClassRegistry::removeObject('Item');
		$this->Item =& ClassRegistry::init('Item');
	}

	function testItemInstance() {
		$this->assertTrue(is_a($this->Item, 'Item'));
	}
	
	function testSave() {
		$this->Item->Origin = new MockOrigin();
		$this->Item->Origin->setReturnValue('save', array('Origin' => array('id' => 49)));
		$this->Item->Origin->setReturnValue('isMuted', false);
		
		$results = $this->Item->save(array('Item' => array('posted' => '2009-12-15 00:11:22',
																											 'text' => 'This is a test save'),
																			 'Origin' => array()));
		$this->assertEqual(11, $results['Item']['id']);
		$item = $this->Item->read(null, $results['Item']['id']);
		$this->assertEqual(49, $item['Item']['origin_id']);
	}

	function testMarkAsReadProviderAll() {
		$this->Item->markAsRead(array('timestamp' => '2009-12-31 00:00:00',
																	'type' => 'provider'));
		
		$this->assertEqual(0, $this->Item->find('count', array('conditions' => array('read' => 0))));
	}

	function testMarkAsReadProviderSingle() {
		$this->Item->markAsRead(array('timestamp' => '2009-12-31 00:00:00',
																	'type' => 'provider',
																	'id' => 2));
		
		$this->assertEqual(0, $this->Item->find('count', array('conditions' => array('provider_id' => 2, 'read' => 0))));
		$this->assertEqual(2, $this->Item->find('count', array('conditions' => array('provider_id !=' => 2, 'read' => 0))));
	}
	
	function testFindConversation() {
		
	}
	
	function testFindProviderKey() {
		$results = $this->Item->find('provider_key');
		$this->assertEqual(array(), $results);

		$results = $this->Item->find('provider_key', 1);
		$this->assertEqual(array(), $results);
		
		$results = $this->Item->find('provider_key', 987654);
		$this->assertEqual(array(987654), array_keys($results));
		$this->assertEqual(array('Item', 'Origin', 'Provider'), array_keys($results[987654]));
		$this->assertEqual('test update append', $results[987654]['Item']['text']);
		$this->assertEqual('20couch', $results[987654]['Origin']['name']);
		
		$results = $this->Item->find('provider_key', array(987651, 987647));
		$this->assertEqual(array(987651, 987647), array_keys($results));
		$this->assertEqual(array('Item', 'Origin', 'Provider'), array_keys($results[987651]));
		$this->assertEqual(array('Lorum ipsum', 'So basically you don\'t even need to bother scraping...emails are returned in a nice machine readable format.'),
											 Set::extract('/Item/text', $results));
		$this->assertEqual(array('mcurry', 'mcurry'), Set::extract('/Origin/name', $results));
	}
	
	function testFindCounts() {
		$results = $this->Item->find('counts');
		$expected = array('sources' => array('provider' => array(
																						 array('id' => 1, 'item_count' => 263),
																						 array('id' => 2, 'item_count' => 12),
																						 array('id' => 3, 'item_count' => 20)
																				 )),
											'all' => 295);

		$this->assertEqual($expected, $results);
	}
}
?>