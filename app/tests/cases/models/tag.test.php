<?php 
/* SVN FILE: $Id$ */
/* Tag Test cases generated on: 2009-10-20 20:51:25 : 1256071885*/
App::import('Model', 'Tag');

class TagTestCase extends CakeTestCase {
	var $Tag = null;
	var $fixtures = array('app.tag');

	function startTest() {
		$this->Tag =& ClassRegistry::init('Tag');
	}

	function testTagInstance() {
		$this->assertTrue(is_a($this->Tag, 'Tag'));
	}

	function testTagFind() {
		$this->Tag->recursive = -1;
		$results = $this->Tag->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Tag' => array(
			'id'  => 1,
			'text'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2009-10-20 20:51:25'
		));
		$this->assertEqual($results, $expected);
	}
}
?>