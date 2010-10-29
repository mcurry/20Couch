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
class TwitterBaseTestCase extends CakeTestCase {
	var $TwitterBase = null;
	var $fixtures = array('app.twitter_base', 'app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');

	function startTest() {
		$this->TwitterBase =& ClassRegistry::init('TwitterBase');
	}

	function testTwitterBaseInstance() {
		$this->assertTrue(is_a($this->TwitterBase, 'TwitterBase'));
	}
	
	function testAfterFind() {
		
	}
	
	function testParse() {
		$result = $this->TwitterBase->parse(array('test' => 'value'), 'test');
		$this->assertEqual('value', $result);
		
		$result = $this->TwitterBase->parse(array('test' => 'value'), 'test2');
		$this->assertEqual(null, $result);
	}
}
?>