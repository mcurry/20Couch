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
App::import('Model', array('Origin'));

class OriginTestCase extends CakeTestCase {
	var $Origin = null;
	var $fixtures = array('app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');


	function startTest() {
		$this->Origin =& ClassRegistry::init('Origin');
	}

	function testOriginInstance() {
		$this->assertTrue(is_a($this->Origin, 'Origin'));
	}
	
	function testSave() {
		$result = $this->Origin->save(array('link' => 'http://twitter.com/20couch'));
		$this->assertEqual(5, $result['Origin']['id']);

		$result = $this->Origin->save(array('link' => 'http://twitter.com/20couch'));
		$this->assertEqual(5, $result['Origin']['id']);
		
		$result = $this->Origin->save(array('link' => 'http://twitter.com/mcurry',
																				'provider_key' => '11373892'));
		$this->assertEqual(4, $result['Origin']['id']);
	}
	
	function testIsMuted() {
		$result = $this->Origin->isMuted(array('Origin' => array('muted' => false, 'muted_until' => null)), '2009-12-15 00:11:22');
		$this->assertFalse($result);
		
		$result = $this->Origin->isMuted(array('Origin' => array('muted' => -1, 'muted_until' => null)), '2009-12-15 00:11:22');
		$this->assertTrue($result);
		
		$result = $this->Origin->isMuted(array('Origin' => array('muted' => 1, 'muted_until' => '2009-12-15 00:00:00')), '2009-12-15 00:11:22');
		$this->assertFalse($result);
		
		$result = $this->Origin->isMuted(array('Origin' => array('muted' => 1, 'muted_until' => '2009-12-15 12:00:00')), '2009-12-15 00:11:22');
		$this->assertTrue($result);
	}
}
?>