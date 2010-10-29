<?php

class TagFixture extends CakeTestFixture {
	var $name = 'Tag';

	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'name' => array('type' => 'string','null' => true,'default' => NULL),
		'item_count' => array('type' => 'integer','null' => true,'default' => NULL),
		'created' => array('type' => 'datetime','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	var $records = array(

	);
}
?>