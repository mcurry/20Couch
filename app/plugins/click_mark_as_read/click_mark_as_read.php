<?php
App::import('Vendor', 'PluginBase');

class ClickMarkAsRead extends PluginBase {
	var $name = "Click Mark As Read";
	var $description = "Mark items as read when clicked on";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.01';
	
	function js() {
		return array('/click_mark_as_read/js/click_mark_as_read.js');
	}
}
?>