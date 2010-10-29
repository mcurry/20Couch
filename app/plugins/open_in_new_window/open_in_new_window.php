<?php
App::import('Vendor', 'PluginBase');

class OpenInNewWindow extends PluginBase {
	var $name = "Open In New Window";
	var $description = "Open outbound links in a new window";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.0';
	
	function js() {
		return array('/open_in_new_window/js/open_in_new_window.js');
	}
}
?>