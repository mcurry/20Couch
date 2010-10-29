<?php
App::import('Vendor', 'PluginBase');

class ScrollMarkAsRead extends PluginBase {
	var $name = "Scroll Mark As Read";
	var $description = "Mark items as read when they scroll off the top";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.01';
	
	function js() {
		return array('/scroll_mark_as_read/js/scroll_mark_as_read.js');
	}
}
?>