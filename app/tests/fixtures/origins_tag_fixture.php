<?php

class OriginsTagFixture extends CakeTestFixture {
	var $name = 'OriginsTag';

	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'origin_id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'index'),
		'tag_id' => array('type' => 'integer','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'origin_id' => array('column' => array('origin_id', 'tag_id'), 'unique' => 1))
	);

	var $records = array(

	);
}
?>