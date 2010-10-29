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
class TwitterAccountTestCase extends CakeTestCase {
	var $TwitterAccount = null;
	var $fixtures = array('app.provider',
												'app.twitter_account', 'app.twitter_search', 'app.twitter_follow',
												'app.item', 'app.origin', 'app.tag', 'app.origins_tag');

	function startTest() {
		$this->TwitterAccount =& ClassRegistry::init('TwitterAccount');
	}

	function testTwitterAccountInstance() {
		$this->assertTrue(is_a($this->TwitterAccount, 'TwitterAccount'));
	}
}
?>