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
App::import('Model', array('Setting'));

class SettingTestCase extends CakeTestCase {
	var $Setting = null;
	var $fixtures = array('app.setting');


	function startTest() {
		$this->Setting =& ClassRegistry::init('Setting');
	}

	function testSettingInstance() {
		$this->assertTrue(is_a($this->Setting, 'Setting'));
	}
	
	function testSave() {
		$result = $this->Setting->saveAll(array('Setting' => array('language' => 'mrt', 'retweet_method' => 'api')));
		$this->assertTrue($result);
		
		$settings = $this->Setting->find('load');
		$expected = array('language' => 'mrt', 'retweet_method' => 'api');
		$this->assertEqual($expected, $settings);
	}

	function testFindLoad() {
		$results = $this->Setting->find('load');
		$expected = array('language' => 'eng', 'retweet_method' => 'manual');
		$this->assertEqual($expected, $results);
	}
	
	function testFindEditable() {
		$results = $this->Setting->find('editable');
		$expected = array(0 => array('Setting' => array('id' => '1', 'key' => 'language', 'value' => 'eng', 'editable' => '1')),
											1 => array('Setting' => array('id' => '2', 'key' => 'retweet_method', 'value' => 'manual', 'editable' => '1')));
		$this->assertEqual($expected, $results);
	}
}
?>