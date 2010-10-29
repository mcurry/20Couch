<?php

class SettingFixture extends CakeTestFixture {
	var $name = 'Setting';

	var $fields = array(
		'id' => array('type' => 'integer','null' => true,'default' => NULL,'key' => 'primary'),
		'key' => array('type' => 'string','null' => true,'default' => NULL,'length' => 25,'key' => 'unique'),
		'value' => array('type' => 'string','null' => true,'default' => NULL),
		'editable' => array('type' => 'boolean','null' => true,'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'key' => array('column' => 'key', 'unique' => 1))
	);

	var $records = array(
		array(
			'id' => 1,
			'key' => 'language',
			'value' => 'eng',
			'editable' => 1
		),
		array(
			'id' => 2,
			'key' => 'retweet_method',
			'value' => 'manual',
			'editable' => 1
		)
	);
}
?>